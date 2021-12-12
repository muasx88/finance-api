<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReportValidation;
use App\Services\ReportService;

class ReportController extends Controller
{
    protected $report;
    public function __construct(ReportService $report)
    {
        $this->report = $report;
    }

    public function report(ReportValidation $request)
    {
        $report = $this->report->report(auth()->user()->id, $request);

        return $this->setResponse(200, true, 'report '.$request->query('type'), $report);
    }
}
