<!DOCTYPE html>
<html>
<head>
    <title>Import Point</title>
</head>
<body>
    <h2>Import Point from Excel</h2>

    @if($errors->any())
        <p style="color: red;">{{ $errors->first() }}</p>
    @endif

    <form action="{{ route('point.import') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="file" name="excel_file" required>
        <button type="submit">Import</button>
    </form>

    <hr>

    @if(isset($importedProducts) && count($importedProducts))
        <h3>✅ Successfully Imported Products:</h3>
        <table border="1" cellpadding="8">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Quantity</th>
                    <th>Price</th>
                </tr>
            </thead>
            <tbody>
                @foreach($importedProducts as $product)
                    <tr>
                        <td>{{ $product->name }}</td>
                        <td>{{ $product->quantity }}</td>
                        <td>{{ $product->price }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    @if(isset($failures) && count($failures))
        <h3 style="color: red;">❌ Failed Imports:</h3>
        <table border="1" cellpadding="8">
            <thead>
                <tr>
                    <th>Row</th>
                    <th>Attribute</th>
                    <th>Errors</th>
                    <th>Value</th>
                </tr>
            </thead>
            <tbody>
                @foreach($failures as $failure)
                    <tr>
                        <td>{{ $failure->row() }}</td>
                        <td>{{ $failure->attribute() }}</td>
                        <td>
                            <ul>
                                @foreach($failure->errors() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </td>
                        <td>{{ $failure->values()[$failure->attribute()] ?? '' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

</body>
</html>
