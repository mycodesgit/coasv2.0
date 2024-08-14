<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

class StudentEvaluationExport implements FromView
{
    protected $studrepcard;
    protected $subjectsData;
    protected $average;

    public function __construct($studrepcard, $subjectsData, $average)
    {
        $this->studrepcard = $studrepcard;
        $this->subjectsData = $subjectsData;
        $this->average = $average;
    }

    public function view(): View
    {
        // Ensure the view is correctly set up to handle the provided data
        return view('enrollment.reports.evaluation.studevalpdf_listsearch', [
            'studrepcard' => $this->studrepcard,
            'subjectsData' => $this->subjectsData,
            'average' => $this->average,
        ]);
    }
}

