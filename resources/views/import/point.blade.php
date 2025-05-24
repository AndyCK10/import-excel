<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Preview and Import Products</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-5">
<div class="container">

    <h2>Import Products (Preview Before Import)</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(!isset($previewData))
    <!-- STEP 1: Upload Form -->
    <form action="{{ route('import.users') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="file" name="file" required>
        <button type="submit">Import</button>
    </form>

    @else
    <!-- STEP 2: Preview Table -->
    <form action="{{ route('products.import') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <input type="hidden" name="excel_file" value="{{ old('excel_file') }}">

        <h4>Preview Products:</h4>
        <table class="table table-bordered">
            <thead>
            <tr>
                <th>member_code</th>
                <th>bar_code</th>
                <th>name</th>
                <th>status</th>
                <th>date_sync</th>
                <th>value</th>
                <th>point_use</th>
                <th>point_remain</th>
            </tr>
            </thead>
            <tbody>
            @foreach($previewData as $item)
                <tr>
                    <td>{{ $item['member_code'] }}</td>
                    <td>{{ $item['bar_code'] }}</td>
                    <td>{{ $item['name'] }}</td>
                    <td>{{ $item['status'] }}</td>
                    <td>{{ $item['date_sync'] }}</td>
                    <td>{{ $item['value'] }}</td>
                    <td>{{ $item['point_use'] }}</td>
                    <td>{{ $item['point_remain'] }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>

        <div class="d-flex justify-content-between">
            <a href="{{ route('products.import.form') }}" class="btn btn-secondary">Cancel</a>
            <button type="submit" class="btn btn-success">Confirm Import</button>
        </div>
    </form>
    @endif

</div>
</body>
</html>
