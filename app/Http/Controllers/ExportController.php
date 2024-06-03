<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exports\GenericExport;
use Maatwebsite\Excel\Facades\Excel;

class ExportController extends Controller
{
    public function export(Request $request, $slug)
    {
        return Excel::download(new GenericExport($slug), $slug.'.xlsx');
    }
}
