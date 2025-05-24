<?php

namespace App\Imports;

use App\Models\MemberPoint;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;

class MemberPointImport implements ToModel, WithHeadingRow, WithValidation, WithStartRow, SkipsOnFailure
{
    use SkipsFailures;

    public $importedProducts = [];

    public function startRow(): int
    {
        return 5; // 🛑 เริ่มอ่านจากแถวที่ 5
    }

    public function model(array $row)
    {
        dd($row);
        $product = new MemberPoint([
            'member_code'     => $row[0],
            'bar_code' => $row[1],
            'name'    => $row[2],
            'status'    => $row[3],
            'date_sync'    => $row[4],
            'value'    => $row[5],
            'point_use'    => $row[6],
            'point_remain'    => $row[7],
            'province'    => $row[1],
        ]);

        $this->importedProducts[] = $product; // เก็บข้อมูลที่ import สำเร็จ

        return $product;
    }

    public function rules(): array
    {
        return [
            '*.member_code'     => ['required'],
            '*.bar_code'        => ['required'],
            '*.name'            => ['required'],
            '*.status'          => ['required'],
            '*.date_sync'       => ['required'],
        ];
    }

    public function getPreviewData()
    {
        return $this->importedProducts;
    }

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model_bk(array $row)
    {
        return new MemberPoint([
            'member_code'     => $row[0],
            'bar_code' => $row[1],
            'name'    => $row[2],
            'status'    => $row[3],
            'date_sync'    => $row[4],
            'value'    => $row[5],
            'point_use'    => $row[6],
            'point_remain'    => $row[7],
            'province'    => $row[1],
        ]);
        
    }
}
