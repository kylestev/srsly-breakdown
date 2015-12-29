<?php

namespace Srsly\Http\Controllers;

use Illuminate\Http\Request;

use Srsly\Http\Requests;
use Srsly\Http\Controllers\Controller;

class SummaryController extends Controller
{

    public function jsonSummary($year = 2015)
    {
        return get_summary($year);
    }

    public function summary($year = 2015)
    {
        return view('main')
            ->with('year', $year)
            ->with('continents', get_summary($year));
    }

}
