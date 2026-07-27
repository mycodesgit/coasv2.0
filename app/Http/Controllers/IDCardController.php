<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class IDCardController extends Controller
{
    public function designer()
    {
        return view('ossas.rfidreg.sample');
    }
    public function generatePDF(Request $request)
    {
        try {
            $canvasData = $request->input('canvas_data');
            $studentData = $request->input('student_data');
            
            // Process canvas data to extract positions and text
            // In a real implementation, you would parse the JSON and render with PDF library
            
            // Sample PDF generation using DomPDF
            $data = [
                'student' => $studentData,
                'canvas' => $canvasData
            ];
            
            $pdf = PDF::loadView('pdf.id-card', $data);
            
            return $pdf->download('id-card-' . time() . '.pdf');
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error generating PDF: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Save design to database
     */
    public function saveDesign(Request $request)
    {
        try {
            $validated = $request->validate([
                'design_name' => 'required|string|max:255',
                'canvas_json' => 'required|json',
                'student_data' => 'nullable|json'
            ]);
            
            // Save to database (you would have a Design model)
            // Design::create([
            //     'name' => $validated['design_name'],
            //     'canvas_data' => $validated['canvas_json'],
            //     'student_data' => $validated['student_data'] ?? null,
            //     'user_id' => auth()->id()
            // ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Design saved successfully!',
                'design_id' => 1 // In production, this would be the actual ID
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error saving design: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Load design from database
     */
    public function loadDesign($id)
    {
        try {
            // Fetch from database
            // $design = Design::findOrFail($id);
            
            // Sample response
            return response()->json([
                'success' => true,
                'design' => [
                    'canvas_json' => '{"objects":[]}',
                    'student_data' => '{"name":"Sample Student"}'
                ]
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error loading design: ' . $e->getMessage()
            ], 404);
        }
    }
}
