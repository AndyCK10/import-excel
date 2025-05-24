<?php

namespace App\Imports;

use App\Models\MemberPoint;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithStartRow;

class PreviewMemberPointImport implements ToCollection, WithHeadingRow, WithStartRow
{
    protected $previewData = [];

    public function collection(Collection $rows)
    {
        // dd($rows);
        foreach ($rows[0] as $key => $row) {
        // foreach ($rows as $row) {
            // dd($row);
            $this->previewData[]    = [
                'member_code'       => $row,
                'bar_code'          => $row[0],
                'name'              => $row[2],
                'status'            => $row[3],
                'date_sync'         => $row[4],
                'value'             => $row[5],
                'point_use'         => $row[6],
                'point_remain'      => $row[6],
                'province'          => $row[1],
            ];
        }
    }

    public function getPreviewData()
    {
        return $this->previewData;
    }

    public function startRow(): int
    {
        return 5; // 🛑 เริ่มอ่านจากแถวที่ 5
    }

}
