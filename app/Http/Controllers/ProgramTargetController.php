<?php

namespace App\Http\Controllers;

use App\Models\ProgramTarget;
use Illuminate\Http\Request;
use PDF;
use Carbon\Carbon;

class ProgramTargetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $programTargets = ProgramTarget::with('iku')->get();
        return view('app.program-target', ['title' => 'IKSP', 'programTargets' => $programTargets]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the request
        $request->validate([
            'program_target.*' => 'required|string|max:255', // Validate each program target
        ]);

        // Loop through the program targets and save each one
        foreach ($request->program_target as $target) {
            ProgramTarget::create([
                'renstra_indicator_id' => $request->iku_id,
                'name' => $target,
            ]);
        }

        // Redirect to a specific route with a success message
        return redirect()->route('program_target.index')->with('success', 'Sasaran Program berhasil di simpan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(ProgramTarget $programTarget)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ProgramTarget $programTarget)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ProgramTarget $programTarget)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            // 'iku_id' => 'required|number|max:255'
            // 'iku_id' => 'nullable|exists:renstra_indicators,id',

        ]);

        $programTarget->update(['name' => $request->name]);

        return redirect()->route('program_target.index')->with('success', 'Sasaran Program berhasil diupdate.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProgramTarget $programTarget)
    {
        // Delete the ProgramTarget instance
        $programTarget->delete();

        // Redirect back with a success message
        return redirect()->back()->with('success', 'Sasaran Program berhasil dihapus.');
    }

    public function getProgramTargets(Request $request)
    {
        $search = $request->input('search', '');

        $query = ProgramTarget::query();

        if (!empty($search)) {
            $query->where('name', 'LIKE', "%{$search}%");
        }

        $programTargets = $query->get(['id', 'name']);

        return response()->json($programTargets);
    }

    // export pdf iksp
    public function downloadIkspPdf(){
        $programTargets = ProgramTarget::with('iku')->get();

        $date = Carbon::now()->format('Y-m-d_H-i-s');

        $pdf = PDF::loadView('components.custom.pdf.downloadIkspPdf', ['programTargets' => $programTargets]);
        return $pdf->download("IKSP_{$date}.pdf");
    }
}
