<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Informe de Compras Totales \Carbon\Carbon::now()->format('d/m/Y H:i A')</title>
</head>
<body>
    <table cellspacing="0" cellpadding="1" style="width: 100%;font-family: Helvetica, Arial, sans-serif">
        <thead>
            <tr>
                <th colspan="5">
                    <img src="{{ asset('assets/images/logo-official.png') }}" alt="logo" height="150">
                </th>
                <th colspan="5" style="text-align: center; font-weight: bold; ">
                    <h4>
                        Jose Joaquin Prieto 5780 - San Miguel
                        <br>
                        Santiago - vulca_david@hotmail.com
                        <br>
                        56 6 5275 9029 - 56 4 1324 3313 - 56 2 3207 5270
                    </h4>
                </th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td colspan="10" style="padding: 5px; text-align: right; font-size: 14px" >
                    <strong>Fecha:</strong> {{ \Carbon\Carbon::now()->format('d/m/Y H:i A') }}
                </td>
            </tr>
            <tr style="border-bottom: 1px solid #1C84EE; ">
                <td colspan="10" style="font-weight: bold; text-align: center; padding-bottom: 15px; padding-top: 40px">
                    Información de Compras Totales
                </td>
            </tr>
            <tr >
                <td colspan="10" style="padding: 5px; text-align: right; font-size: 14px" >Nº Facturas: {{ count($informe) }}</td>
            </tr>

        </tbody>
    </table>

    <table border="1" cellspacing="0" cellpadding="0" style="width: 100%;font-family: Helvetica, Arial, sans-serif; margin-top: 20px; border-collapse: collapse;">
        <thead>
            <tr>
                <th style="width: 10px">N°</th>
                <th>Fecha</th>
                <th>Proveedor</th>
                <th>Nº de factura</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
        @foreach ($informe as $value)
            <tr style="text-align: center">
                <td >#00000{{ $value['id'] }}</td>
                <td >{{ $value['fecha'] }}</td>
                <td >{{ $value['proveedor'] }}</td>
                <td >{{ $value['factura'] }}</td>
                <td style="font-weight: bold">{{ $value['total'] }}</td>
            </tr>
        @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="4" style="padding: 5px; text-align: right; font-size: 18px; font-weight: bold" >Total: </td>
                <td style="padding: 5px; text-align: center; font-size: 18px; font-weight: bold" >{{ $total }}</td>
            </tr>
        </tfoot>
    </table>
</body>
</html>
