<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Point
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
                    <style>
                        .w-5 {
                            width: 2rem;
                        }

                        .h-5 {
                            height: 2rem;
                        }

                        .flex-1 {
                            height: 2rem;
                        }

                        a {
                            text-decoration: none;
                            color: #0dcaf0;
                        }

                        a:hover {
                            color: #31d2f2;
                        }

                        .text-right {
                            text-align: right;
                        }

                        .ml-2 {
                            margin-left: 0.5rem;
                        }

                        .flex-container {
                            display: flex;
                            flex-direction: row;
                        }

                        .grid-container {
                            display: grid;
                            grid-template-columns: 1fr auto;
                        }

                        .filter-container {
                            display: grid;
                            grid-template-columns: 1fr auto;
                        }

                        .table-scrollable-x-auto {
                            overflow-x: auto;
                        }

                        @media screen and (max-width: 575px) {
                            .filter-container {
                                display: block;
                            }
                        }
                    </style>


                    <h2>Import Point</h2>

                    @if (session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <form action="{{ route('points.import') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3 grid-container">
                            <input class="form-control" type="file" id="excel_file" name="excel_file" required>
                            <button type="submit" class="btn btn-info ml-2">Import Excel</button>
                        </div>

                    </form>

                    <h2>Search</h2>
                    <form method="GET" action="{{ route('dashboard') }}">
                        <div class="mb-3 filter-container">
                            <div class="row">
                                <div class="col-6">
                                    <div class="mb-3 row">
                                        <label for="staticEmail" class="col-sm-3 col-form-label">คำค้นหา: </label>
                                        <div class="col-sm-9">
                                            <input type="text" name="search" value="{{ request('search') }}"
                                                placeholder="" class="form-control" />
                                        </div>
                                    </div>
                                </div>

                                <div class="col-6">
                                    <div class="mb-3 row">
                                        <label for="staticEmail" class="col-sm-3 col-form-label">เขตการขาย: </label>
                                        <div class="col-sm-9">
                                            <select name="province" class="form-select">
                                                <option value="">ทั้งหมด</option>
                                                @foreach ($provinces as $dept)
                                                    <option value="{{ $dept }}"
                                                        {{ $dept == request('province') ? 'selected' : '' }}>
                                                        {{ $dept }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-auto text-center">
                                <button type="submit" class="btn btn-info ml-2">ค้นหา</button>
                            </div>
                        </div>

                    </form>

                    <h4>Point:</h4>
                    <div class=" table-scrollable-x-auto">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>เขตการขาย</th>
                                    <th>รหัสลูกค้า</th>
                                    <th>รหัสบาร์โค้ด</th>
                                    <th>ชื่อลูกค้า</th>
                                    <th>สถานะ</th>
                                    <th>วันที่ดึงข้อมูล</th>
                                    <th>แต้มสะสม</th>
                                    <th>แต้มใช้ไป</th>
                                    <th>แต้มคงเหลือ</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($previewData as $item)
                                    <tr>
                                        <td>{{ $loop->iteration + ($previewData->currentPage() - 1) * $previewData->perPage() }}
                                        </td>
                                        <td>{{ $item['province'] }}</td>
                                        <td>{{ $item['member_code'] }}</td>
                                        <td>{{ $item['bar_code'] }}</td>
                                        <td>{{ $item['name'] }}</td>
                                        <td>
                                            @if ($item['status'] == 1)
                                                {{ 'ยืนยันสมาชิกแล้ว' }}
                                            @else
                                                {{ 'ยังไม่ได้ยืนยันสมาชิก' }}
                                            @endif
                                        </td>
                                        <td>{{ $item['date_sync'] }}</td>
                                        <td class="text-right">{{ $item['value'] }}</td>
                                        <td class="text-right">{{ $item['point_use'] }}</td>
                                        <td class="text-right">{{ $item['point_remain'] }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="text-center">ไม่พบข้อมูล</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{ $previewData->links() }}

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
