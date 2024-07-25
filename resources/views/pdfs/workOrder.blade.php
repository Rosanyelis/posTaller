<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orden de Compra</title>
</head>
<body>
    <table cellspacing="0" style="width: 100%;font-family: Arial, Helvetica, sans-serif">
        <thead>
            <tr>
                <th colspan="5">
                    <img src="{{ asset('assets/images/logo-official.png') }}" alt="logo" height="150">
                    <br>
                    <h4>
                        Jose Joaquin Prieto 5780 - San Miguel
                        <br>
                        Santiago - vulca_david@hotmail.com
                        <br>
                        56 6 5275 9029 - 56 4 1324 3313 - 56 2 3207 5270
                    </h4>
                </th>
                <th colspan="5" style="text-align: center; font-weight: bold; ">
                    <h1>
                        Orden de Trabajo
                        <br>
                        N° {{ $workOrder->correlativo }}
                        <br>
                        {{ \Carbon\Carbon::parse($workOrder->created_at)->format('d - m - Y') }}
                    </h1>
                </th>
            </tr>
        </thead>
        <tbody style="border: 1px solid black">
            <tr>
                <td colspan="10" style="border: 1px solid black"><strong>Datos de Cliente</strong></td>
            </tr>
            <tr>
                <td colspan="10" style="border: 1px solid black"><strong>Cliente:</strong> {{ $workOrder->customer->name }} </td>
            </tr>
            <tr>
                <td colspan="10" style="border: 1px solid black"><strong>Dirección:</strong> {{ $workOrder->customer->address }} </td>
            </tr>
            <tr>
                <td colspan="5" style="border: 1px solid black"><strong>Rut:</strong> {{ $workOrder->customer->rut }} </td>
                <td colspan="5" style="border: 1px solid black"><strong>Teléfono:</strong> {{ $workOrder->customer->phone }} </td>
            </tr>
            <tr>
                <td colspan="10" style="border: 1px solid black"><strong>Datos de Vehiculo</strong></td>
            </tr>
            <tr>
                <td colspan="2" style="border: 1px solid black"><strong>Marca:</strong> {{ $workOrder->marca }} </td>
                <td colspan="4" style="border: 1px solid black"><strong>Modelo:</strong> {{ $workOrder->modelo }} </td>
                <td colspan="4" style="border: 1px solid black"><strong>Patente:</strong> {{ $workOrder->patente_vehiculo }} </td>
            </tr>
        </tbody>
    </table>
    <table border="1" cellspacing="0" style="width: 100%; margin-top: 40px; border-collapse: collapse; font-family: Arial, Helvetica, sans-serif">
        <thead>
            <tr>
                <th colspan="5" style="text-align: center; font-weight: bold">Servicios</th>
            </tr>
            <tr>
                <th>Servicio</th>
                <th>Cantidad</th>
                <th>Detalle</th>
                <th>Precio</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($workOrder->items as $service)
                <tr style="text-align: center; font-size: 14px">
                    <td>{{ $service->product->name }}</td>
                    <td>{{ $service->quantity }}</td>
                    <td>{{ $service->details }}</td>
                    <td>{{ $service->price }}</td>
                    <td>{{ $service->total }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="4" style="text-align: right; font-weight: bold">Total:</td>
                <td style="text-align: center;">{{ $workOrder->total }}</td>
            </tr>
        </tfoot>
    </table>
</body>
</html>