<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Imports\MemberPointImport;
use App\Imports\PreviewMemberPointImport;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\PointImport;
use Carbon\Carbon;
use App\Models\MemberPoint;

class MemberPointImportController extends Controller
{

    public function index(Request $request)
    {
        $search = $request->input('search');
        $province = $request->input('province');

        $previewData = MemberPoint::when($search, function ($query, $search) {
            return $query->where('name', 'like', "%{$search}%")
                ->orWhere('member_code', 'like', "%{$search}%")
                ->orWhere('bar_code', 'like', "%{$search}%");
            // ->orWhere('province', 'like', "%{$search}%");
        })
            ->when($province, fn($q) => $q->where('province', $province))
            ->orderBy('name')
            ->paginate(20)
            ->appends(['search' => $search]); // preserve query in pagination links

        // เอาค่าทั้งหมดของ province มาแสดงใน dropdown
        $provinces = MemberPoint::select('province')->distinct()->pluck('province');

        return view('import/import-points', compact('previewData', 'search', 'province', 'provinces'));
    }
    public function index2()
    {
        $previewData = MemberPoint::get();
        return view('import/import-points', compact('previewData'))->with('data', $previewData);
    }

    public function previewExcel(Request $request)
    {
        $user = $this->getCurrentToken($request);
        // dd($user);
        $request->validate([
            'excel_file' => 'required|mimes:xlsx,xls,csv',
        ]);

        if ($request->hasFile('excel_file')) {
            try {
                $previewData = $this->readExcel($request->file('excel_file'));
            } catch (\Exception $e) {
                $previewData = null;
            }
        }
        return view('import/import-points', compact('previewData'));
    }

    function convertDateExcel($excel_date)
    {
        // $excel_date = 43010; //here is that value 41621 or 41631
        $unix_date = ($excel_date - 25569) * 86400;
        $excel_date = 25569 + ($unix_date / 86400);
        $unix_date = ($excel_date - 25569) * 86400;
        return gmdate("Y-m-d H:i:s", $unix_date);
    }

    function readExcel($excel_file)
    {
        try {
            $previewData = array();
            $province = '';
            $rows = Excel::toArray(new PointImport, $excel_file);
            foreach ($rows[0] as $key => $value) {
                if ($key == 0) {
                    $province = $value[1];
                } else if ($key >= 5) { // เริ่มต้นบรรทัดที่ 5
                    // dd($value);
                    $date_sync = $value[4] != '' ? $this->convertDateExcel($value[4]) : Carbon::now();
                    $previewData[]    = [
                        'member_code'       => $value[0],
                        'bar_code'          => $value[1],
                        'name'              => $value[2],
                        'status'            => $value[3],
                        'date_sync'         => $date_sync,
                        'value'             => $value[5],
                        'point_use'         => $value[6],
                        'point_remain'      => $value[6],
                        'province'          => $province,
                    ];
                }
            }

            return $previewData;
        } catch (\Exception $e) {
            return $e;
        }
    }

    function save($excel_file, $user)
    {

        try {
            $previewData = array();
            $province = '';
            $rows = Excel::toArray(new PointImport, $excel_file);
            foreach ($rows[0] as $key => $value) {
                // dd($value);
                if ($key == 0) {
                    $province = $value[1];
                } else if ($key >= 5 && $value[1] != '') { // เริ่มต้นบรรทัดที่ 5

                    $date_sync = $value[4] != '' ? $this->convertDateExcel($value[4]) : Carbon::now();
                    if ($value[3] == 'ยืนยันสมาชิกแล้ว') {
                        $status = 1;
                    } else {
                        $status = 0;
                    }
                    $member_code = $value[0];
                    $memberPoint = MemberPoint::where('member_code', $member_code)->first();
                    if ($memberPoint) {
                        $excelTmp = new MemberPoint;
                        $excelTmp = $excelTmp->find($memberPoint->id);
                        $excelTmp->member_code                  = $value[0];
                        $excelTmp->bar_code                     = $value[1];
                        $excelTmp->name                         = $value[2];
                        $excelTmp->status                       = $status; // 0:ยังไม่ได้ยืนยันสมาชิก, 1: ยืนยันสมาชิกแล้ว
                        $excelTmp->date_sync                    = $date_sync;
                        $excelTmp->value                        = $value[5];
                        $excelTmp->point_use                    = $value[6];
                        $excelTmp->point_remain                 = $value[7];
                        $excelTmp->province                     = $province;
                        $excelTmp->updated_by                   = $user->id;
                        $excelTmp->save();
                    } else {
                        $excelTmp = new MemberPoint;
                        $excelTmp->member_code                  = $value[0];
                        $excelTmp->bar_code                     = $value[1];
                        $excelTmp->name                         = $value[2];
                        $excelTmp->status                       = $status; // 0:ยังไม่ได้ยืนยันสมาชิก, 1: ยืนยันสมาชิกแล้ว
                        $excelTmp->date_sync                    = $date_sync;
                        $excelTmp->value                        = $value[5];
                        $excelTmp->point_use                    = $value[6];
                        $excelTmp->point_remain                 = $value[7];
                        $excelTmp->province                     = $province;
                        $excelTmp->created_by                   = $user->id;
                        $excelTmp->save();
                    }
                }
            }

            return $previewData;
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function importExcel(Request $request)
    {
        $user = $this->getCurrentToken($request);
        // dd($request);


        // Excel::import(new PointImport, $request->file('file'));
        if ($request->hasFile('excel_file')) {
            try {
                $previewData = $this->save($request->file('excel_file'), $user);
            } catch (\Exception $e) {
                $previewData = null;
            }
        }

        return back()->with('success', 'นำเข้าข้อมูลเรียบร้อยแล้ว');
        // return view('import/import-points')->with('success', 'นำเข้าข้อมูลเรียบร้อยแล้ว');
    }

    // public function previewExcel2(Request $request)
    // {
    //     $request->validate([
    //         'excel_file' => 'required|file|mimes:xlsx,xls',
    //     ]);

    //     $file = $request->file('excel_file');

    //     $previewImport = new PreviewMemberPointImport();
    //     Excel::import($previewImport, $file);

    //     $previewData = $previewImport->getPreviewData();

    //     // $import = new MemberPointImport;
    //     // Excel::import($import, $file);
    //     // $previewData = $import->getPreviewData();

    //     return view('import/import-products', compact('previewData'));
    // }

    // public function importExcel(Request $request)
    // {
    //     $request->validate([
    //         'excel_file' => 'required|file|mimes:xlsx,xls',
    //     ]);

    //     Excel::import(new MemberPointImport, $request->file('excel_file'));

    //     return redirect()->route('products.import.form')->with('success', 'Products imported successfully.');
    // }

    // public function index()
    // {
    //     return view('import-member-points');
    // }

    // public function import(Request $request)
    // {
    //     $request->validate([
    //         'excel_file' => 'required|file|mimes:xlsx,xls,csv',
    //     ]);

    //     $import = new MemberPointImport;
    //     Excel::import($import, $request->file('excel_file'));

    //     return view('import-member-points', [
    //         'importedProducts' => $import->importedProducts,
    //         'failures' => $import->failures(),
    //     ]);
    // }

    // public function import(Request $request)
    // {
    //     $request->validate([
    //         'excel_file' => 'required|file|mimes:xlsx,xls,csv',
    //     ]);

    //     Excel::import(new MemberPointImport, $request->file('excel_file'));

    //     return back()->with('success', 'Products imported successfully!');
    // }


}
