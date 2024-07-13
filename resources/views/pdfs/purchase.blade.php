<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Compras</title>
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
                <td colspan="8" style="text-align: center"><h2>Compra</h2></td>
            </tr>
            <tr>
                <td colspan="8" style="text-align: right"><strong>Fecha:</strong> {{ \Carbon\Carbon::parse($purchase->created_at)->format('d/m/Y') }}</td>
            </tr>
            <tr>
                <td colspan="8" style="text-align: right"><strong>N° de Factura:</strong> {{ $purchase->reference }}</td>
            </tr>
            <tr>
                <td colspan="8" style="text-align: right"><strong>¿Recibido?:</strong> {{ ($purchase->received == 1 ? 'Recibido' : 'No Recibido') }}</td>
            </tr>
            <tr style="margin-top: 40px;">
                <td colspan="8" ><strong>Proveedor:</strong> {{ $purchase->supplier->name }} </td>
            </tr>
            <tr>
                <td colspan="8" ><strong>Correo:</strong> {{ $purchase->supplier->email }} </td>
            </tr>
            <tr>
                <td colspan="8" ><strong>Teléfono:</strong> {{ $purchase->supplier->phone }} </td>
            </tr>
            <tr>
                <td colspan="8" ><strong>Notas:</strong> {{ $purchase->note }} </td>
            </tr>
        </tbody>
    </table>
    <table border="1" cellspacing="0" style="width: 100%; margin-top: 40px; border-collapse: collapse; font-family: Arial, Helvetica, sans-serif">
        <thead>
            <tr>
                <th colspan="4" style="text-align: center; font-weight: bold">Productos</th>
            </tr>
            <tr>
                <th>Producto</th>
                <th>Cantidad</th>
                <th>Precio</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($purchase->purchaseItems as $item)
                <tr style="text-align: center; font-size: 14px">
                    <td>{{ $item->product->name }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>{{ $item->cost }}</td>
                    <td>{{ $item->subtotal }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3" style="text-align: right; font-weight: bold">Total:</td>
                <td style="text-align: center;">{{ $purchase->total }}</td>
            </tr>
        </tfoot>
    </table>

</body>
</html>
