<?php

namespace App\Http\Controllers;

use App\Models\Renstra;
use App\Models\RenstraIndicator;
use App\Models\RenstraMission;
use Illuminate\Http\Request;
use PDF;
use Carbon\Carbon;
use App\Exports\MissionsExport;
use App\Exports\IkusExport;

class RenstraController extends Controller
{
    public function vision()
    {
        $renstra = Renstra::first();

        return view('app.vision', ['title' => 'Visi', 'renstra' => $renstra]);
    }

    public function updateVision(Request $request)
    {
        $validated = $request->validate([
            'vision' => 'required|max:255',
        ]);

        $renstra = Renstra::first();
        $renstra->vision = $validated['vision'];
        $renstra->save();

        return redirect()->route('vision.index')->with('success', 'Visi berhasil diperbarui.');
    }

    public function mission()
    {
        $renstra = Renstra::with('missions')->first();
        return view('app.mission', ['title' => 'Misi', 'renstra' => $renstra]);
        
    }

    public function storeMission(Request $request)
    {
        $validatedData = $request->validate([
            'mission.*' => 'required|string|max:255', // Validate each mission input
        ]);
        $renstra = Renstra::first();
        foreach ($validatedData['mission'] as $data)
            RenstraMission::create(['renstra_id' => $renstra->id, 'description' => $data]);

        return redirect()->route('mission.index')->with('success', 'Berhasil menambahkan misi.');
    }

    public function updateMission(Request $request)
    {
        $validatedData = $request->validate([
            'id' => 'required|exists:renstra_missions,id',
            'description' => 'required|string|max:255'
        ]);
    
        $mission = RenstraMission::find($validatedData['id']);
        if ($mission) {
            $mission->update(['description' => $validatedData['description']]);
            return redirect()->route('mission.index')->with('success', 'Misi berhasil diperbarui.');
        }
    
        return redirect()->route('mission.index')->with('error', 'Misi tidak ditemukan.');
    }

    public function deleteMission(Request $request)
    {
        RenstraMission::find($request->id)->delete();
        return response()->json(['success' => 'Berhasil menghapus misi.']);
    }

    // fungsi download pdf misi
    public function downloadMissionPdf()
    {
        $missions = RenstraMission::all();

        // Get the current date and time
        $date = Carbon::now()->format('Y-m-d_H-i-s');

        // Generate the PDF
        $pdf = PDF::loadView('components.custom.pdf.downloadMissionPdf', ['missions' => $missions]);
        return $pdf->download("Misi_{$date}.pdf");
    }

    public function iku()
    {
        $renstra = Renstra::first();
        $missions = RenstraMission::get();
        $ikus = RenstraIndicator::with('mission')->get();
        // dd($ikus);

        return view('app.iku', ['title' => 'Sasaran Program', 'renstra' => $renstra, 'missions' => $missions, 'ikus' => $ikus]);
    }

    public function storeIku(Request $request)
    {
        $validatedData = $request->validate([
            'misi' => 'required|integer',
            'iku.*' => 'required|string', // Validate each iku input
        ]);
        foreach ($validatedData['iku'] as $data)
            RenstraIndicator::create([
                // 'renstra_mission_id' => $validatedData['misi'],
                'renstra_mission_id' => $validatedData['misi'], 
                'description' => $data
            ]);
        return redirect()->route('iku.index')->with('success', 'Sasaran Program berhasil ditambahkan.');
    }

    public function updateIku(RenstraIndicator $iku, Request $request)
    {
        $validatedData = $request->validate([
            // 'id' => 'required|exists:renstra_missions,id',
            'description' => 'required|string|max:255',
            'misi' => 'integer|max:255'
        ]);
        if ($iku) {
            $iku->update([
                'description' => $validatedData['description'],
                'renstra_mission_id' => $validatedData['misi']
            ]);
            return redirect()->route('iku.index')->with('success', 'Sasaran Program berhasil update.');
        }

        return redirect()->route('iku.index')->with('success', 'Sasaran Program gagal diupdate.');
    }

    // Add this method to your RenstraController

    public function deleteIku(Request $request)
    {

        RenstraIndicator::find($request->id)->delete();
        return response()->json(['success' => 'Berhasil menghapus indikator.']);
    }

    public function getRenstraIku(Request $request)
    {
        $search = $request->input('search', '');

        $query = RenstraIndicator::query();

        if (!empty($search)) {
            $query->where('description', 'LIKE', "%{$search}%");
        }

        $programTargets = $query->get(['id', 'description']);

        return response()->json($programTargets);
    }

    // fungsi download pdf IKU
    public function downloadIkuPdf()
    {
        $ikus = RenstraIndicator::with('mission')->get();

        // Mendapatkan tanggal dan waktu saat ini
        $date = Carbon::now()->format('Y-m-d_H-i-s');

        // Update the path to match the location of your Blade file
        $pdf = PDF::loadView('components.custom.pdf.downloadIkuPdf', ['ikus' => $ikus]);
        return $pdf->download("Sasaran_Program_{$date}.pdf");
    }
}
