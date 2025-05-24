<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Imports\PointImport;
use Maatwebsite\Excel\Facades\Excel;

class ImportController extends Controller
{
    public function showForm()
    {
        echo "test";
        // return view('import-form');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv'
        ]);

        // Excel::import(new PointImport, $request->file('file'));

        // return redirect()->back()->with('success', 'นำเข้าข้อมูลเรียบร้อยแล้ว!');
    }
}
