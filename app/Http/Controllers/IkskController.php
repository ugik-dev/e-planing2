<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\PerformanceIndicator;
use App\Exports\PerformanceIndicatorExport;
use App\Models\IKSK;
use App\Models\ProgramTarget;
use PDF;
use Carbon\Carbon;

class IkskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $title = 'IKSK';
        $perforceHasIksk = PerformanceIndicator::with('iksks')->get();
        return view('app.iksk', compact('title', 'perforceHasIksk'));
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
        // Validate the request data
        $request->validate([
            'performance_indicator_id' => 'required|exists:performance_indicators,id',
            'iksk.*' => 'required|string', // Validate each indicator
            // 'value' => 'required|decimal:2',
        ]);

        // Retrieve the program target ID from the request
        $programTargetId = $request->performance_indicator_id;

        // Process each performance indicator
        foreach ($request->iksk as $ss) {
            // Create new PerformanceIndicator
            $performanceIndicator = new IKSK([
                'name' => $ss,
                'performance_indicator_id' => $programTargetId,
                'value' => 0,
            ]);

            $performanceIndicator->save();
        }

        // Redirect or send a response back
        return redirect()->route('iksk.index')->with('success', 'Indikator kinerja berhasil disimpan.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, IKSK $iksk)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'value' => 'required',
            // 'type_value' => 'required',
            // 'value_end' => 'somtime',
        ]);
        // Update the performance indicator
        $iksk->name = $request->name;
        $iksk->value = $request->value;
        $iksk->type = $request->type_value;
        $iksk->value_end = $request->value_end;
        $iksk->save();

        // Redirect with a success message
        return redirect()->route('iksk.index')->with('success', 'Indikator kinerja berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(IKSK $iksk)
    {
        // Delete the ProgramTarget instance
        $iksk->delete();

        // Redirect back with a success message
        return redirect()->back()->with('success', 'Indikator kinerja berhasil dihapus.');
    }

    // download pdf
    public function downloadIkskPdf()
    {
        $performanceIndicators = PerformanceIndicator::with('iksks')->get();

        // Mendapatkan tanggal dan waktu saat ini
        $date = Carbon::now()->format('Y-m-d_H-i-s');

        // Set options for Dompdf
        $options = new \Dompdf\Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isPhpEnabled', true);
        $options->set('isRemoteEnabled', true);

        $pdf = new \Dompdf\Dompdf($options);

        $pdf = PDF::loadView('components.custom.pdf.downloadIkskPdf', ['performanceIndicators' => $performanceIndicators]);
        return $pdf->download("IKSK_{$date}.pdf");
    }
}
