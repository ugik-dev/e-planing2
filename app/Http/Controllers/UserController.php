<?php

namespace App\Http\Controllers;

use App\Mail\UserRegistered;
use App\Models\Employee;
use App\Models\User;
use App\Models\WorkUnit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;
use PDF;
use Carbon\Carbon;

class UserController extends Controller
{
    public function index()
    {
        $title = 'Kelola User';
        $users = User::with('employee')->where('id','!=',1033)->get();
        // dd($users);
        // $users = User::with('employee')->notAdmin()->get();
        $roles = Role::all();
        $work_units = WorkUnit::all();
        $identity_types = ['nik', 'nip', 'nidn'];

        // Kirim data role ke view bersamaan dengan data users dan title
        return view('app.user', compact('users', 'title', 'roles', 'work_units', 'identity_types'));
    }

    public function store(Request $request)
    {
        $validatedData = $this->validate($request, [
            'user_name' => 'required|max:255',
            'password' => 'required|max:255',
            'identity_number' => 'nullable|integer',
            'identity_type' => 'nullable|string',
            'email' => 'required|email|unique:users,email',
            'user_role' => 'required|exists:roles,name',
            'position' => 'string|required_if:identity_number,true',
            'work_unit' => 'integer|required_if:identity_number,true',
            'head_id' => 'sometimes|integer|required_if:identity_number,true|exists:employees,id',
            'letter_reference' => 'sometimes|string',
        ]);
        try {
            $randomPassword = Str::random(10);

            $user = User::create([
                'name' => $validatedData['user_name'],
                'email' => $validatedData['email'],
                'password' => Hash::make($validatedData['password']),
            ]);
            if (!empty($validatedData['identity_number']) && !empty($validatedData['position'] && !empty($validatedData['work_unit']))) {
                $employee = new Employee([
                    'id' => $validatedData['identity_number'],
                    'position' => $validatedData['position'],
                    'identity_type' => $validatedData['identity_type'],
                    'work_unit_id' => $validatedData['work_unit'],
                    'letter_reference' => $validatedData['letter_reference'] ?? null,
                ]);
                if ($validatedData['user_role'] == 'PPK') {
                    $employee->head_id = $validatedData['head_id'];
                } else {
                    $employee->head_id = null;
                }
                $user->employee()->save($employee);
            }
            $user->assignRole($validatedData['user_role']);
            $user->save();
            $user->sendEmailVerificationNotification();

            // Kirim email dengan password yang digenerate
            // Mail::to($user->email)->send(new UserRegistered($user, $randomPassword));
            return back()->with('success', 'Data user berhasil ditambahkan.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function update(Request $request, User $user)
    {
        $validatedData = $this->validate($request, [
            'user_name' => 'required|max:255',
            'identity_number' => 'nullable|numeric',
            'identity_type' => 'nullable|string',
            'email' => 'required|email',
            'user_role' => 'required|exists:roles,name',
            'position' => 'string|required_if:identity_number,true',
            'work_unit' => 'integer|required_if:identity_number,true',
            'head_id' => 'required_if:user_role,PPK',
            'letter_reference' => 'required_if:user_role,PPK',
        ]);
        // Hanya enkripsi dan update password jika field password diisi
        if (!empty($request->password)) {
            $user->password = Hash::make($request->password);
        }
        // $user->password =  Hash::make('password');

        $user->name = $validatedData['user_name'];
        if ($user->email != $validatedData['email']) {
            $user->email_verified_at = null;
            $user->email = $validatedData['email'];
            $resent_mail = true;
        } else {
            $resent_mail = false;
        }
        $user->email = $validatedData['email'];
        $employee = $user->load('employee')->employee;
        if (!$employee) {
            if (!empty($validatedData['identity_number']) || empty($validatedData['position'] || $validatedData['work_unit'])) {
                $employee = new Employee([
                    'id' => $validatedData['identity_number'],
                    'position' => $validatedData['position'],
                    'identity_type' => $validatedData['identity_type'],
                    'work_unit_id' => $validatedData['work_unit'] ?? null,
                    'letter_reference' => $validatedData['letter_reference'] ?? null,
                ]);
                if ($validatedData['user_role'] == 'PPK') {
                    $employee->head_id = $validatedData['head_id'] ?? null;
                }
                $user->employee()->save($employee);
            }
        } else {
            if ($validatedData['user_role'] == 'PPK') {
                $employee->head_id = $validatedData['head_id'] ?? null;
                $employee->letter_reference = $validatedData['letter_reference'];
            } else {
                $employee->head_id = null;
                $employee->letter_reference = null;
            }
            if ($employee->id != $validatedData['identity_number']) {
                Employee::where('head_id', $employee->id)
                    ->update(['head_id' => $validatedData['identity_number']]);
            };

            $employee->id = $validatedData['identity_number'];
            $employee->position = $validatedData['position'];
            $employee->work_unit_id = $validatedData['work_unit'];
            $employee->identity_type = $validatedData['identity_type'];
            $employee->save();
        }

        $user->syncRoles($validatedData['user_role']);
        $user->save();
        if ($resent_mail)
            $user->sendEmailVerificationNotification();
        if ($request->ajax()) {
            return response()->json(['success' => 'Data user berhasil diperbaharui.'], 200);
        }

        return redirect()->route('user.index')->with('success', 'Data user berhasil diperbaharui.');
    }

    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->back()->with('success', 'User berhasil dihapus.');
    }
    public function resendEmail(User $user)
    {
        $user->sendEmailVerificationNotification();

        return redirect()->back()->with('success', 'Verification link sent!');
    }
    public function getUsers(Request $request)
    {
        $search = $request->input('search', '');
        $limit = $request->input('limit', 10); // Default to 10 if not provided

        $query = User::query();

        if (!empty($search)) {
            $query->where('name', 'LIKE', "%{$search}%")
                ->orWhere('identity_number', 'LIKE', "%{$search}%");
        }

        $ppks = $query->limit($limit)->get(['id', 'name', 'identity_number']);

        return response()->json($ppks);
    }

    // fungsi export pdf
    public function downloadUserPdf()
    {
        $users = User::with('employee', 'roles', 'employee.workUnit')->get();

        // Mendapatkan tanggal dan waktu saat ini
        $date = Carbon::now()->format('Y-m-d_H-i-s');

        $pdf = PDF::loadView('components.custom.pdf.downloadUserPdf', ['users' => $users])
                  ->setPaper('a4', 'landscape');
        return $pdf->download("User_Data_{$date}.pdf");
    }
}
