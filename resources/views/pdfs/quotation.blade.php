<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cotización</title>
</head>
<body>
    <table cellspacing="0" style="width: 100%; border-collapse: collapse; font-family: Arial, Helvetica, sans-serif">
        <thead>
            <tr>
                <th colspan="8" style="text-align: left; ">
                    <img src="{{ asset('assets/images/logo-official.png') }}" alt="logo" height="80"></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td colspan="8" style="text-align: center"><h2>Cotización</h2></td>
            </tr>
            <tr>
                <td colspan="8" style="text-align: right"><strong>Fecha:</strong> {{ \Carbon\Carbon::parse($quotation->created_at)->format('d/m/Y') }}</td>
            </tr>
            <tr style="margin-top: 40px;">
                <td colspan="8" ><strong>Cliente:</strong> {{ $quotation->customer->name }} </td>
            </tr>
            <tr>
                <td colspan="8" ><strong>Rut:</strong> {{ $quotation->customer->rut }} </td>
            </tr>
            <tr>
                <td colspan="8" ><strong>Correo:</strong> {{ $quotation->customer->email }} </td>
            </tr>
            <tr>
                <td colspan="8" ><strong>Teléfono:</strong> {{ $quotation->customer->phone }} </td>
            </tr>
            <tr>
                <td colspan="8" ><strong>Notas:</strong> {{ $quotation->note }} </td>
            </tr>
        </tbody>
    </table>
    <table border="1" cellspacing="0" style="width: 100%; margin-top: 40px; border-collapse: collapse; font-family: Arial, Helvetica, sans-serif">
        <thead>
            <tr>
                <th colspan="6" style="text-align: center; font-weight: bold">Productos</th>
            </tr>
            <tr>
                <th>Codigo</th>
                <th>Producto</th>
                <th>Cantidad</th>
                <th>Precio</th>
                <th>Descuento</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($quotation->items as $item)
                <tr style="text-align: center; font-size: 14px">
                    <td>{{ $item->product_code }}</td>
                    <td>{{ $item->product_name }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>{{ $item->unit_price }}</td>
                    <td>{{ $item->discount }}</td>
                    <td>{{ $item->subtotal }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="5" style="text-align: right; font-weight: bold">Total:</td>
                <td style="text-align: center;">{{ $quotation->total }}</td>
            </tr>
        </tfoot>
    </table>

</body>
</html>
