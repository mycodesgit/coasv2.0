<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class StudentEvaluationExport implements FromView
{
    protected $studrepcard, $subjectsData, $average;

    public function __construct($studrepcard, $subjectsData, $average)
    {
        $this->studrepcard = $studrepcard;
        $this->subjectsData = $subjectsData;
        $this->average = $average;
    }

    public function view(): View
    {
        return view('enrollment.reports.evaluation.studevalexcel', [
            'studrepcard' => $this->studrepcard,
            'subjectsData' => $this->subjectsData,
            'average' => $this->average,
        ]);
    }
}

