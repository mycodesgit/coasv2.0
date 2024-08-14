<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

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
        return view('enrollment.reports.evaluation.studevalpdf_listsearch', [
            'studrepcard' => $this->studrepcard,
            'subjectsData' => $this->subjectsData,
            'average' => $this->average,
        ]);
    }
}
