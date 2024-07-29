<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Productos por Codigo de barras</title>
    <link rel="stylesheet" href="{{ asset('assets/bootstrap2.3/css/bootstrap.css') }}">
</head>
<body>
    <div class="bs-docs-grid">
        <div class="row">
            @php
                $contador = 0;
            @endphp
            @foreach ($products as $item)
            <div class="span3">
                <table border="1" cellspacing="0" style="text-align: center; border-collapse: collapse; font-family: Arial, Helvetica, sans-serif">
                    <tr>
                        <td>{{ $item->name }}</td>
                    </tr>
                    <tr>
                        <td>{!! DNS1D::getBarcodeHTML($item->code, 'C128', 2, 70) !!}</td>
                    </tr>
                    <tr>
                        <td>{{ $item->code }}</td>
                    </tr>
                </table>
            </div>
            @endforeach
        </div>
    </div>

</body>
</html>
