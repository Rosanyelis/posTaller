<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ventas</title>
</head>
<body>

    <table>
        <thead>
            <tr>
                <th colspan="6" style="text-align: center; font-weight: bold; font-size: 14px; ">
                    {{ $empresa->name }}
                </th>
            </tr>
            <tr>
                <th colspan="6" style="text-align: center; font-weight: bold; font-size: 14px; ">
                    {{ $empresa->email }}
                </th>
            </tr>
            <tr>
                <th colspan="6" style="text-align: center; font-weight: bold; font-size: 14px; ">
                {{ $empresa->phone }}
                </th>
            </tr>
            <tr>
                <th colspan="6" style="padding: 5px; text-align: center; font-size: 10px" >
                    <strong>Informe Generado:</strong> {{ \Carbon\Carbon::now()->format('d/m/Y H:i A') }}
                </th>
            </tr>
            <tr>
                <th style="border: 1px solid black; padding: 5px; text-align: center; font-size: 10px; font-weight: bold">Total</th>
                <th style="border: 1px solid black; padding: 5px; text-align: center; font-size: 10px; font-weight: bold">Efectivo</th>
                <th style="border: 1px solid black; padding: 5px; text-align: center; font-size: 10px; font-weight: bold">Tarjeta</th>
                <th style="border: 1px solid black; padding: 5px; text-align: center; font-size: 10px; font-weight: bold">Cheque</th>
                <th style="border: 1px solid black; padding: 5px; text-align: center; font-size: 10px; font-weight: bold">Transferencia</th>
                <th style="border: 1px solid black; padding: 5px; text-align: center; font-size: 10px; font-weight: bold">Propina</th>
            </tr>
        </thead>
        <tbody>
            <tr style="text-align: center">
                <td style="border: 1px solid black; padding: 5px; text-align: center; font-size: 10px; font-weight: bold">{{ $total }}</td>
                <td style="border: 1px solid black; padding: 5px; text-align: center; font-size: 10px; font-weight: bold">{{ $efectivo }}</td>
                <td style="border: 1px solid black; padding: 5px; text-align: center; font-size: 10px; font-weight: bold">{{ $credito }}</td>
                <td style="border: 1px solid black; padding: 5px; text-align: center; font-size: 10px; font-weight: bold">{{ $cheque }}</td>
                <td style="border: 1px solid black; padding: 5px; text-align: center; font-size: 10px; font-weight: bold">{{ $transferencia }}</td>
                <td style="border: 1px solid black; padding: 5px; text-align: center; font-size: 10px; font-weight: bold">{{ $propina }}</td>
            </tr>
        </tbody>
    </table>

    <table>
        <thead>
            <tr>
                <th colspan="2" style="border: 1px solid black; padding: 5px; text-align: center; font-size: 10px; font-weight: bold">N°</th>
                <th colspan="2" style="border: 1px solid black; padding: 5px; text-align: center; font-size: 10px; font-weight: bold">Cliente</th>
                <th colspan="2" style="border: 1px solid black; padding: 5px; text-align: center; font-size: 10px; font-weight: bold">Total</th>
            </tr>
        </thead>
        <tbody>
        @foreach ($informe as $value)
            <tr style="text-align: center">
                <td colspan="2">#00000{{ $value['id'] }}</td>
                <td colspan="2">{{ $value['cliente'] }}</td>
                <td colspan="2" style="font-weight: bold">{{ $value['total'] }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

</body>
</html>
