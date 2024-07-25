<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>factura nº 00000{{ $sale->id }}</title>
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
                    <strong>Fecha:</strong> {{ \Carbon\Carbon::parse($sale->created_at)->format('d/m/Y H:i A') }}
                </td>
            </tr>
            <tr>
                <td colspan="10" style="padding: 5px; text-align: right; font-size: 14px" >
                    <strong>Vendedor:</strong> {{ $sale->user->name }}
                </td>
            </tr>
            <tr style="border-bottom: 1px solid #1C84EE; ">
                <td colspan="10" style="font-weight: bold; text-align: center; padding-bottom: 15px; padding-top: 40px">
                    Información de Cliente
                </td>
            </tr>
            <tr>
                <td colspan="7" style="padding: 5px;font-size: 14px" >
                    <strong>Cliente:</strong> {{ $sale->customer->name }}
                </td>
                <td colspan="3" style="padding: 5px;font-size: 14px">
                    <strong>Rut:</strong> {{ $sale->customer->rut }}
                </td>
            </tr>
            <tr>
                <td colspan="7" style="padding: 5px;font-size: 14px">
                    <strong>Correo:</strong> {{ $sale->customer->email }}
                </td>
                <td colspan="3" style="padding: 5px;font-size: 14px">
                    <strong>Teléfono:</strong> {{ $sale->customer->phone }}
                </td>
            </tr>
            <tr>
                <td colspan="10" style="padding: 5px;font-size: 14px">
                    <strong>Dirección:</strong> {{ $sale->customer->address }}
                </td>
            </tr>

        </tbody>
    </table>
    <table border="1" cellspacing="0" style="width: 100%; margin-top: 40px; border-collapse: collapse; font-family: Arial, Helvetica, sans-serif">
        <thead>
            <tr>
                <td colspan="5" style="text-align: center; font-weight: bold; ">
                    Información de la Venta
                </td>
            </tr>
            <tr>
                <th style="text-align: center; font-weight: bold">Código</th>
                <th style="text-align: center; font-weight: bold">Artículo</th>
                <th style="text-align: center; font-weight: bold">Cant.</th>
                <th style="text-align: center; font-weight: bold">P. Unitario</th>
                <th style="text-align: center; font-weight: bold">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($sale->saleitems as $item)
                <tr style="text-align: center; font-size: 14px">
                    <td>{{ $item->product_code }}</td>
                    <td>{{ $item->product_name }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>{{ $item->unit_price }}</td>
                    <td>{{ $item->subtotal }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="4" style="text-align: right; font-weight: bold">SubTotal:</td>
                <td style="text-align: center;">{{ $sale->total }}</td>
            </tr>
            <tr>
                <td colspan="4" style="text-align: right; font-weight: bold">Descuento(%{{ $sale->order_discount_id }}):</td>
                <td style="text-align: center;">{{ number_format($sale->total * ($sale->order_discount_id / 100), 2) }}</td>
            </tr>
            <tr>
                <td colspan="4" style="text-align: right; font-weight: bold">Impuesto (%{{ $sale->order_tax_id }}):</td>
                <td style="text-align: center;">{{ number_format($sale->total * ($sale->order_tax_id / 100), 2) }}</td>
            </tr>
            <tr>
                <td colspan="4" style="text-align: right; font-weight: bold">Total:</td>
                <td style="text-align: center;">{{ $sale->grand_total }}</td>
            </tr>
        </tfoot>
    </table>

    <table border="0" cellspacing="0" style="width: 100%; margin-top: 40px; border-collapse: collapse; font-family: Arial, Helvetica, sans-serif">
        <tr>
            <td style="text-align: center; font-size: 14px">
                <h2>Gracias por su compra</h2>
                <h3>¡Hasta pronto!</h3>
            </td>
        </tr>
    </table>
</body>
</html>