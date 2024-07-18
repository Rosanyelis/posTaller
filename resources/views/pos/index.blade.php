@extends('layouts.pos')

@section('title') POS @endsection

@section('css')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.3/themes/base/jquery-ui.css">

 <style>
    ::-webkit-scrollbar {
        width: 5px; /* Ancho del scrollbar */
    }

    /* Establece el estilo del thumb (la parte deslizable del scrollbar) */
    /* En navegadores Webkit (Chrome, Safari) */
    ::-webkit-scrollbar-thumb {
        background-color: #888; /* Color del thumb */
        border-radius: 5px; /* Redondez del thumb */
    }

    /* Establece el estilo del track (la pista del scrollbar) */
    /* En navegadores Webkit (Chrome, Safari) */
    ::-webkit-scrollbar-track {
        background-color: #f1f1f1; /* Color del track */
    }


 </style>
@endsection

@section('content')
<div class="row">
    <div class="col-xl-12 col-lg-12 col-md-12">
        <!-- card -->
        <div class="card card-h-100">
            <!-- card body -->
            <div class="card-body">
                <table style="width:100%;" class="layout-table">
                    <tr>
                        <td>
                            <div id="pos">
                                <form action="{{ route('pos.store') }}" method="post" id="posForm">
                                    @csrf
                                    <div class="well well-sm" id="leftdiv">
                                        <div id="lefttop" style="margin-bottom:5px;">
                                            <div class="form-group" style="margin-bottom:5px;">
                                                <div class="input-group">
                                                    <select class="form-select" name="customer" id="customer" style="width: 96%">
                                                        <option selected>Seleccione cliente</option>
                                                    </select>
                                                    <button class="btn btn-secondary" type="button" title="Añadir cliente"
                                                        data-bs-toggle="modal" data-bs-target="#customerModal">
                                                        <i class="mdi mdi-plus-circle"></i>
                                                    </button>
                                                </div>
                                                <div style="clear:both;"></div>
                                            </div>
                                            <div class="form-group row" style="margin-bottom:5px;">
                                                <div class="col-md-2">
                                                    <label for="note_ref" class="col-form-label" >Nota de Referencia</label>
                                                </div>
                                                <div class="col-md-10">
                                                    <input type="text" name="note_ref" value="" id="note_ref"
                                                    class="form-control kb-text" placeholder="Nota de Referencia" />
                                                </div>
                                            </div>
                                            <div class="form-group row" style="margin-bottom:5px;">
                                                <div class="col-md-2">
                                                    <label for="productos" class="col-form-label" >Productos</label>
                                                </div>
                                                <div class="col-md-10">
                                                    <input type="text" name="code" id="productos" class="form-control"
                                                    placeholder="Buscar articulos por codigo o nombre, tambien puede escanear el codigo de barras" />
                                                </div>
                                            </div>
                                            <div class="form-group row" style="margin-bottom:5px;">
                                                <div class="col-md-2">
                                                    <label for="workorder" class="col-form-label" >Ordenes de Trabajo</label>
                                                </div>
                                                <div class="col-md-10">
                                                    <input type="text" name="workorder" id="workorder" class="form-control"
                                                    placeholder="Buscar Orden de Trabajo por numero de orden y esta debe tener un estatus de completda" />
                                                </div>
                                            </div>

                                        </div>
                                        <div id="print" class="fixed-table-container">
                                            <div class="slimScrollDiv" style="position: relative;overflow: auto;width: auto;height: 250px;">
                                                <table id="posTable" class="table table-sm table-striped table-condensed table-hover list-table"
                                                    style="margin:0px;" data-height="100">
                                                    <thead>
                                                        <tr class="success">
                                                            <th>Producto u Orden de Trabajo</th>
                                                            <th style="width: 12%;text-align:center;">Inventario</th>
                                                            <th style="width: 15%;text-align:center;">Precio</th>
                                                            <th style="width: 15%;text-align:center;">Cantidad</th>
                                                            <th style="width: 20%;text-align:center;">Subtotal</th>
                                                            <th style="width: 20px;" class="satu"><i class="fa fa-trash-o"></i></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody ></tbody>
                                                </table>
                                            </div>
                                            <div style="clear:both;"></div>
                                            <div id="totaldiv">
                                                <table id="totaltbl" class="table table-condensed totals" style="margin-bottom:10px;">
                                                    <tbody>
                                                        <tr class="info">
                                                            <td width="25%">Total de Articulos</td>
                                                            <td class="text-center" style="padding-right:10px;"><span id="totalItems">0</span></td>
                                                            <td width="25%">Total</td>
                                                            <td class="text-center" colspan="2"><span id="totalComplete">0</span></td>
                                                        </tr>
                                                        <tr class="info">
                                                            <td width="25%">
                                                                <a href="#" data-bs-toggle="modal"
                                                                data-bs-target="#discountModal">Descuento</a>
                                                            </td>
                                                            <td class="text-center" style="padding-right:10px;">
                                                                <span id="discount">0</span>%
                                                            </td>
                                                            <td width="25%">
                                                                <a href="javascript:void(0);"
                                                                data-bs-toggle="modal" data-bs-target="#taxModal">Impuesto</a>
                                                            </td>
                                                            <td class="text-center">
                                                                <span id="tax">0</span>%
                                                            </td>
                                                        </tr>
                                                        <tr class="success">
                                                            <td colspan="3" style="font-weight:bold;">
                                                                Total a pagar
                                                                <a role="button" data-toggle="modal" data-target="#noteModal">
                                                                    <i class="fa fa-comment"></i>
                                                                </a>
                                                            </td>
                                                            <td class="text-center"  style="font-weight:bold;">
                                                                $ <span id="totalpay">0</span>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div id="botbuttons" class=" text-center">
                                            <div class="row p-0">
                                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 d-grid gap-2" style="padding: 0;">
                                                    <div class="btn-group-vertical btn-block" style="height:72px;">
                                                        <button type="button" class="btn btn-danger"
                                                        id="salir">Salir de caja</button>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 d-grid gap-2" style="padding: 1;">
                                                    <div class="btn-group-vertical ">
                                                        <button type="button" class="btn btn-info" id="print_bill" style="height:72px;">
                                                            Imprimir Factura
                                                        </button>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 d-grid gap-2" style="padding: 0;">
                                                    <!-- id = $eid ? 'submit-sale' : 'payment'; -->
                                                    <button type="button" id="paymentmethod" class="btn btn-success" style="height:72px;">
                                                        Pagar
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <input type="hidden" name="productos" id="productosarray">
                                    <input type="hidden" name="tax" id="taxh">
                                    <input type="hidden" name="discount" id="discounth">
                                    <input type="hidden" name="subtotal" id="subtotal">
                                    <input type="hidden" name="grandtotal" id="total">
                                    <input type="hidden" name="total_items" id="total_items">
                                    <input type="hidden" name="methodpay" id="method_pay">
                                    <input type="hidden" name="balance" id="balanceh">
                                    <input type="hidden" name="note" id="noteh">
                                    <input type="hidden" name="amount" id="amounth">
                                    <input type="hidden" name="paymentby" id="paymentbyh">
                                    <input type="hidden" name="notepay" id="notepayh">
                                    <input type="hidden" name="paypartial" id="paypartial">
                                    <input type="hidden" name="notepayment" id="note_payment">

                                </form>
                            </div>
                        </td>
                    </tr>
                </table>
                <!-- Modal Clientes -->
                <div class="modal fade" id="customerModal" data-bs-backdrop="static" data-bs-keyboard="false"
                    tabindex="-1" aria-labelledby="customerModal" aria-modal="true" role="dialog">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="staticBackdropLabel">Nuevo Cliente</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body" >
                                <div class="row">
                                    <div class="col-lg-4 col-md-4 col-sm-6">
                                        <div class="mb-3">
                                            <label for="name" class="form-label">Nombre de Cliente</label>
                                            <input class="form-control" type="text" name="name" id="name" required>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-6">
                                        <div class="mb-3">
                                            <label for="rut" class="form-label">Rut de Cliente</label>
                                            <input class="form-control" type="text" name="rut" id="rut" required
                                                placeholder="00000000-0">
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-6">
                                        <div class="mb-3">
                                            <label for="email" class="form-label">Correo</label>
                                            <input class="form-control" type="email" name="email" id="email" required
                                                placeholder="test@example.com">
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-6">
                                        <div class="mb-3">
                                            <label for="phone" class="form-label">Celular</label>
                                            <input class="form-control" type="text" name="phone" id="phone" required>
                                        </div>
                                    </div>
                                    <div class="col-lg-8 col-md-8 col-sm-12">
                                        <div class="mb-3">
                                            <label for="address" class="form-label">Dirección</label>
                                            <input class="form-control" type="text" name="address" id="address" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary pull-left" data-bs-dismiss="modal"> Cerrar </button>
                                <button type="button" class="btn btn-primary" id="add_customer"> Guardar Cliente </button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Modal Impuestos -->
                <div class="modal fade" id="taxModal" data-bs-backdrop="static" data-bs-keyboard="false"
                    tabindex="-1" aria-labelledby="taxModal" aria-modal="true" role="dialog">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="staticBackdropLabel">Agregar Impuesto</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body" >
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12">
                                        <div class="mb-3">
                                            <label for="tax" class="form-label">Por favor indique el impuesto en números enteros</label>
                                            <input class="form-control" type="number" name="tax" id="order_tax" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary pull-left" data-bs-dismiss="modal"> Cancelar </button>
                                <button type="button" class="btn btn-primary" id="save_tax"> Guardar Impuesto </button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Modal Descuento -->
                <div class="modal fade" id="discountModal" data-bs-backdrop="static" data-bs-keyboard="false"
                    tabindex="-1" aria-labelledby="discountModal" aria-modal="true" role="dialog">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="staticBackdropLabel">Agregar Descuento</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body" >
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12">
                                        <div class="mb-3">
                                            <label for="tax" class="form-label">Por favor indique el descuento en números enteros</label>
                                            <input class="form-control" type="number" name="order_discount" id="order_discount" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary pull-left" data-bs-dismiss="modal"> Cancelar </button>
                                <button type="button" class="btn btn-primary" id="save_discount"> Guardar Descuento </button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Modal Payment -->
                <div class="modal fade" id="modalPayment" data-bs-backdrop="static" data-bs-keyboard="false"
                    tabindex="-1" role="dialog" aria-labelledby="modalPayment" aria-hidden="true"
                    data-bs-scroll="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="myModalLabel">Finalizando Venta</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close" id="close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-8">
                                        <h4>Total a pagar: <span id="total_amount"></span> </h4>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="method">Forma de Pago</label>
                                            <select id="method" name="method" class="form-control">
                                                <option value="Total">Total</option>
                                                <option value="Parcial">Parcial</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="w-100"></div>
                                    <div class="col-md-12">
                                        <h4>Balance: <span id="balance">0</span> </h4>
                                    </div>

                                    <hr>
                                    <div class="w-100"></div>
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="notepayment">Nota</label>
                                            <textarea name="notepayment" id="notepayment" class="form-control"></textarea>
                                        </div>
                                    </div>

                                </div>
                                <div class="row" id="total_pay" >
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="amount">Cantidad</label>
                                            <input type="number" id="amount" name="amount" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="paymentBy">Pagando por</label>
                                            <select name="paymentBy" id="paymentBy" class="form-control">
                                                <option value="seleccione">Seleccione</option>
                                                <option value="Efectivo">Efectivo</option>
                                                <option value="Tarjeta de credito">Tarjeta de credito</option>
                                                <option value="Cheque">Cheque</option>
                                                <option value="Transferencia">Transferencia</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row d-none" id="partial_pay">
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label for="importe">Importe</label>
                                            <input type="number" id="importe" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label for="methodPay">Pagando por</label>
                                            <select name="methodPay" id="methodPay" class="form-control">
                                                <option value="seleccione">Seleccione</option>
                                                <option value="Efectivo">Efectivo</option>
                                                <option value="Tarjeta de credito">Tarjeta de credito</option>
                                                <option value="Cheque">Cheque</option>
                                                <option value="Transferencia">Transferencia</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label for="notePayment" id="labelNote">Nota de Pago</label>
                                            <input type="text" id="notePayment" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <button type="button" id="add_partial" class="btn btn-primary mt-4" id="add_payment">Agregar</button>
                                    </div>
                                    <div class="col-md-12">
                                        <table id="paymentTable" class="table table-condensed table-sm ">
                                            <thead>
                                                <tr>
                                                    <th colspan="4" class="text-center">Pagos parciales</th>
                                                </tr>
                                                <tr>
                                                    <th>Forma de Pago</th>
                                                    <th>Importe</th>
                                                    <th>Detalles</th>
                                                    <th>Acción</th>
                                                </tr>
                                            </thead>
                                            <tbody id="payment_list">
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="row d-none" id="efecty_pay" >
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="change">Nota de pago</label>
                                            <input type="text" id="notepayefecty" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="row d-none" id="cheque_pay" >
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="change">Numero de cheque</label>
                                            <input type="number" id="nro_cheque" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="row d-none" id="transferencia_pay" >
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="change">Numero de referencia</label>
                                            <input type="number" id="nro_transferencia" class="form-control">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary waves-effect"
                                    data-bs-dismiss="modal" id="close">Cerrar</button>
                                <button type="button" class="btn btn-primary waves-effect"
                                    id="save_payment">Guardar</button>
                            </div>
                        </div><!-- /.modal-content -->
                    </div><!-- /.modal-dialog -->
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://code.jquery.com/ui/1.13.3/jquery-ui.js"></script>


<script>
    var dataProduct = [];
    var dataPartial = [];
    $(document).ready(function() {
        $('#customer').select2({
            placeholder: 'Seleccione un cliente',
            ajax: {
                url: '{{ route("pos.getCustomers") }}',
                dataType: 'json',
                delay: 250,
                processResults: function (data) {
                    return {
                        results:  $.map(data, function (item) {
                            return {
                                text: item.rut  + ' - ' + item.name,
                                id: item.id
                            }
                        })
                    };
                },
                cache: true
            }
        });

        $('#productos').autocomplete({
            minLength: 1,
            source: function(request, response) {
                $.ajax({
                    url: '{{ route("pos.getProducts") }}',
                    dataType: 'json',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        term: request.term
                    },
                    success: function(data) {
                        response(data);
                    }
                });
            },
            select: function(event, ui) {
                let select =  ui.item.code + ' - ' + ui.item.name ;
                let qtyHtml = '#quantity-' + ui.item.id;
                let subtotalHtml = '#subtotal-' + ui.item.id;
                let totalComplete = 0;

                if (dataProduct.length > 0) {
                    let index = dataProduct.findIndex((item) => item.code == ui.item.code);

                    if (index == -1) {
                       let datosFila = {};
                        datosFila.id = ui.item.id;
                        datosFila.code = ui.item.code;
                        datosFila.name = ui.item.name;
                        datosFila.quantity = 1;
                        datosFila.price = ui.item.price;
                        datosFila.subtotal = ui.item.price;
                        datosFila.type = 'product';

                        dataProduct.push(datosFila);

                        $('#posTable').append(
                            '<tr id="producto-' + ui.item.code + '">' +
                            '<td>' + select + '</td>' +
                            '<td class="text-center">' + ui.item.quantity + '</td>' +
                            '<td class="text-center">' + ui.item.price + '</td>' +
                            '<td class="text-center"><input type="number" id="quantity-' + ui.item.code + '" onchange="calculateQuantity(this)" data-id="' + ui.item.code + '" class="form-control form-control-sm" min="1" value="1"></td>' +
                            '<td class="text-center"><span id="subtotal-' + ui.item.code + '" data-id="' + ui.item.code + '">'+ ui.item.price +'</span></td>' +
                            '<td><button type="button" class="btn btn-danger btn-sm" onclick="deleteRow(' + ui.item.code + ')"><i class="mdi mdi-delete "></i></button></td>'
                        );

                    } else if (index != -1) {
                        dataProduct[index].quantity = parseInt(dataProduct[index].quantity) + 1;
                        dataProduct[index].price = parseFloat(dataProduct[index].price) + parseFloat(ui.item.price);
                        dataProduct[index].subtotal = parseFloat(dataProduct[index].subtotal) + parseFloat(ui.item.price);

                        $(qtyHtml).val(dataProduct[index].quantity);
                        $(subtotalHtml).text(dataProduct[index].subtotal);

                    }

                } else{
                    let datosFila = {};
                    datosFila.id = ui.item.id;
                    datosFila.code = ui.item.code;
                    datosFila.name = ui.item.name;
                    datosFila.quantity = 1;
                    datosFila.price = ui.item.price;
                    datosFila.subtotal = ui.item.price;
                    datosFila.type = 'product';

                    dataProduct.push(datosFila);

                    $('#posTable').append(
                        '<tr id="producto-' + ui.item.code + '">' +
                        '<td>' + select + '</td>' +
                        '<td class="text-center">' + ui.item.quantity + '</td>' +
                        '<td class="text-center">' + ui.item.price + '</td>' +
                        '<td class="text-center"><input type="number" id="quantity-' + ui.item.code + '" onchange="calculateQuantity(this)" data-id="' + ui.item.code + '" class="form-control form-control-sm" min="1" value="1"></td>' +
                        '<td class="text-center"><span id="subtotal-' + ui.item.code + '" data-id="' + ui.item.code + '">'+ ui.item.price +'</span></td>' +
                        '<td><button type="button" class="btn btn-danger btn-sm" onclick="deleteRow(' + ui.item.code + ')"><i class="mdi mdi-delete "></i></button></td>'
                    );


                }
                calculateTotal();
                calculateArticulos();
                calculateComplete();
                $('#productos').val('');
                return false;
            }

        }).data('ui-autocomplete')._renderItem = function(ul, item) {
            return $( "<li>" )
                .append( "<div>" + item.code + " - " + item.name + " (" + item.quantity + ") - " + item.price + "</div>" )
                .appendTo( ul );
        };

        $('#workorder').autocomplete({
            minLength: 1,
            source: function(request, response) {
                $.ajax({
                    url: '{{ route("pos.getWorkorders") }}',
                    dataType: 'json',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        term: request.term
                    },
                    success: function(data) {
                        response(data);
                    }
                });
            },
            select: function(event, ui) {
                let stock = 0;
                let quantity = 0;
                let select = 'Orden:' + ui.item.correlativo + ' - ' + ui.item.rut + ' - ' + ui.item.name;
                if (dataProduct.length > 0) {
                    let index = dataProduct.findIndex((item) => item.code == ui.item.correlativo);

                    if (index == 1) {
                        Swal.fire({
                            position: 'top-center',
                            icon: 'error',
                            title: 'Oops...',
                            text: 'La Orden ya ha sido agregada!, no puede agregarla de nuevo!',
                            showConfirmButton: false,
                            timer: 2500
                        });
                        $('#workorder').val('');
                    } else {
                        let datosFila = {};
                        datosFila.id = ui.item.id;
                        datosFila.code = ui.item.correlativo;
                        datosFila.name = 'Orden:' + ui.item.correlativo + ' - ' + ui.item.rut + ' - ' + ui.item.name;
                        datosFila.quantity = 1;
                        datosFila.price = ui.item.total;
                        datosFila.subtotal = ui.item.total;
                        datosFila.type = 'workorder';

                        dataProduct.push(datosFila);

                        $('#posTable').append(
                            '<tr id="producto-' + ui.item.correlativo + '">' +
                            '<td>Orden: ' + ui.item.correlativo + '</td>' +
                            '<td class="text-center">' + stock + '</td>' +
                            '<td class="text-center">' + ui.item.total + '</td>' +
                            '<td class="text-center">' + quantity + '</td>' +
                            '<td class="text-center">' + ui.item.total + '</td>' +
                            '<td><button type="button" class="btn btn-danger btn-sm" onclick="deleteRow(' + ui.item.correlativo + ')" ><i class="mdi mdi-delete "></i></button></td>'
                        );

                    }

                } else {

                    let datosFila = {};
                    datosFila.id = ui.item.id;
                    datosFila.code = ui.item.correlativo;
                    datosFila.name = 'Orden:' + ui.item.correlativo + ' - ' + ui.item.rut + ' - ' + ui.item.name;
                    datosFila.quantity = 1;
                    datosFila.price = ui.item.total;
                    datosFila.subtotal = ui.item.total;
                    datosFila.type = 'workorder';

                    dataProduct.push(datosFila);

                    $('#posTable').append(
                        '<tr id="producto-' + ui.item.correlativo + '">' +
                        '<td>Orden: ' + ui.item.correlativo + '</td>' +
                        '<td class="text-center">' + stock + '</td>' +
                        '<td class="text-center">' + ui.item.total + '</td>' +
                        '<td class="text-center">' + quantity + '</td>' +
                        '<td class="text-center">' + ui.item.total + '</td>' +
                        '<td><button type="button" class="btn btn-danger btn-sm"  onclick="deleteRow(' + ui.item.correlativo + ')"><i class="mdi mdi-delete "></i></button></td>'
                    );

                }

                calculateTotal();
                calculateArticulos();
                calculateComplete();
                $('#workorder').val('');
                return false;
            }
        }).data('ui-autocomplete')._renderItem = function(ul, item) {
            return $( "<li>" )
                .append( "<div> Orden:" + item.correlativo + " - " + item.rut + " - " + item.name + "</div>" )
                .appendTo( ul );
        }

        // agregar clientes
        $('#add_customer').on('click', function() {
            let name = $('#name').val();
            let rut = $('#rut').val();
            let email = $('#email').val();
            let phone = $('#phone').val();
            let address = $('#address').val();

            if (name == '' || rut == '' || email == '' || phone == '' || address == '') {
                alert('Por favor rellene todos los campos');
            } else {
                $.ajax({
                    url: '{{ route("pos.storeCustomer") }}',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        name: name,
                        rut: rut,
                        email: email,
                        phone: phone,
                        address: address
                    },
                    success: function(data) {
                        Swal.fire({
                            position: 'top-end',
                            icon: 'success',
                            title: 'Se agrego correctamente el cliente',
                            showConfirmButton: false,
                            timer: 1500
                        });

                        $('#name').val('');
                        $('#rut').val('');
                        $('#email').val('');
                        $('#phone').val('');
                        $('#address').val('');

                        $('#customer').append('<option value="' + data.id + '">' + data.rut + ' - ' + data.name + '</option>');
                        $('#customerModal').modal('hide');
                    }
                });
            }

        });

        // agregar impuestos
        $('#save_tax').on('click', function() {

            let tax = parseFloat($('#order_tax').val());
            let total = parseFloat($('#totalComplete').text());
            if (tax == '') {
                Swal.fire({
                    position: 'top-end',
                    icon: 'error',
                    title: 'Por favor rellene el campo de impuestos',
                    showConfirmButton: false,
                    timer: 1500
                });
            } else {
                let totalTax = total * (tax / 100);
                let totalWithTax = total + totalTax;
                $('#tax').text(tax);
                $('#order_tax').val('');
                $('#taxModal').modal('hide');
            }
            calculateComplete();
        });

        $('#save_discount').on('click', function() {
            let discount = parseFloat($('#order_discount').val());
            let total = parseFloat($('#totalComplete').text());
            if (discount == '') {
                Swal.fire({
                    position: 'top-end',
                    icon: 'error',
                    title: 'Por favor rellene el campo de descuento',
                    showConfirmButton: false,
                    timer: 1500
                });
            } else {
                let totalDiscount = total * (discount / 100);
                let totalWithDiscount = total - totalDiscount;
                $('#discount').text(discount);
                $('#discount').val('');
                $('#discountModal').modal('hide');
            }

            calculateComplete();
        });

        $('#paymentmethod').on('click', function() {
            $('#modalPayment').modal('show');
            $('#total_amount').text($('#totalpay').text());
            $('#amount').val($('#totalpay').text());
        });

        $('#method').on('change', function() {
            var type = $(this).val();
            if (type == 'Total') {
                $('#total_pay').removeClass('d-none');
                $('#partial_pay').addClass('d-none');
            } else if (type == 'Parcial') {
                $('#total_pay').addClass('d-none');
                $('#partial_pay').removeClass('d-none');
            } else {
                $('#total_pay').removeClass('d-none');
                $('#partial_pay').addClass('d-none');
            }
        });

        $('#paymentBy').on('change', function() {
            var type = $(this).val();
            if (type == 'Efectivo') {
                $('#efecty_pay').removeClass('d-none');
                $('#cheque_pay').addClass('d-none');
                $('#transferencia_pay').addClass('d-none');
            } else if (type == 'Cheque') {
                $('#cheque_pay').removeClass('d-none');
                $('#efecty_pay').addClass('d-none');
                $('#transferencia_pay').addClass('d-none');
            } else if (type == 'Transferencia') {
                $('#transferencia_pay').removeClass('d-none');
                $('#cheque_pay').addClass('d-none');
                $('#efecty_pay').addClass('d-none');
            } else{
                $('#efecty_pay').addClass('d-none');
                $('#cheque_pay').addClass('d-none');
                $('#transferencia_pay').addClass('d-none');
            }
        });

        $('#methodPay').on('change', function() {
            var type = $(this).val();
            console.log(type);
            if (type == 'Efectivo') {
                $('#labelNote').text('Nota de Pago');
            } else if (type == 'Cheque') {
                $('#labelNote').text('Nro de Cheque');
            } else if (type == 'Transferencia') {
                $('#labelNote').text('Nro de Transferencia');
            } else if (type == 'Tarjeta de credito') {
                $('#labelNote').text('Codigo de transaccion');
            }
            else{
                $('#labelNote').text('Nota de Pago');
            }
        });

        $('#add_partial').on('click', function() {
            let total = parseFloat($('#totalpay').text());

            if (total == 0) {
                Swal.fire({
                    position: 'top-end',
                    icon: 'error',
                    title: 'Por favor agregue los datos de venta',
                    showConfirmButton: false,
                    timer: 1500
                });
                $('#modalPayment').modal('hide');

                return;
            }
            var payment = $('#methodPay').val();
            var amount = parseFloat($('#importe').val());
            var details = $('#notePayment').val();

            let datosFila = {};
            datosFila.payment = payment;
            datosFila.amount = amount;
            datosFila.details = details;
            dataPartial.push(datosFila);

            $('#paymentTable #payment_list').append('<tr id="payment-' + payment + '"><td>' + payment + '</td><td>' + amount + '</td><td>' + details + '</td><td><button type="button" class="btn btn-danger btn-sm" id="' + payment + '" onclick="deletePartial(this)"><i class="mdi mdi-delete "></i></button></td></tr>');
            $('#methodPay').val('');
            $('#importe').val('');
            $('#notePayment').val('');

            calculateBalance();

        });
        $('#save_payment').on('click', function() {
            console.log('procesando');
            // cliente
            var customer = $('#customer').val();
            var noteRef = $('#note_ref').val();
            var tax = $('#tax').text();
            var discount = $('#discount').text();
            var subtotal = parseFloat($('#totalComplete').text());
            var total = parseFloat($('#totalpay').text());
            var totalItems = parseInt($('#totalItems').text());
            // medios y detalles de pago
            var note = $('#note').val();
            var method = $('#method').val(); // parcial o total
            var paymentBy = $('#paymentBy').val(); // efectivo, cheque, transferencia o tarjeta // pago total
            var paymentspartials = JSON.stringify(dataPartial);
            var notePay;
            var notePayment = $('#notepayment').val();
            if ($('#notepayefecty').val() != '') {
                notePay = $('#notepayefecty').val();
            }
            if ($('#nro_cheque').val() != '') {
                notePay = $('#nro_cheque').val();
            }
            if ($('#nro_transferencia').val() != '') {
                notePay = $('#nro_transferencia').val();
            }

            $('#productosarray').val(JSON.stringify(dataProduct));
            $('#taxh').val(tax);
            $('#discounth').val(discount);
            $('#subtotal').val(subtotal);
            $('#total').val(total);
            $('#total_items').val(totalItems);
            $('#method_pay').val(method);
            $('#balanceh').val(parseFloat($('#balance').text()));
            $('#noteh').val(note);
            $('#amounth').val(parseFloat($('#totalpay').text()));
            $('#paymentbyh').val(paymentBy);
            $('#notepayh').val(notePay);
            $('#paypartial').val(JSON.stringify(dataPartial));
            $('#note_payment').val(noteRef);
            $('#posForm').submit();

        });

        $('#salir').on('click', function() {
            Swal.fire({
                title: '¿Estas seguro de salir de caja?',
                text: "Se perderan los cambios realizados",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Si, Salir',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '{{ route("dashboard") }}';
                }
            })
        });
    });
    function deleteRow(dato) {
        let id = '#producto-' + dato;
        $(id).closest('tr').remove();
        calculateQuantity(dato);
        calculateTotal();
        calculateArticulos();
        calculateComplete();
    }

    function calculateTotal() {
        let total = 0;
        for (let i = 0; i < dataProduct.length; i++) {
            total += parseFloat(dataProduct[i].subtotal);
        }
        $('#totalComplete').text(total);
    }
    function calculateArticulos() {
        let total = 0;
        for (let i = 0; i < dataProduct.length; i++) {
            total += parseFloat(dataProduct[i].quantity);
        }
        $('#totalItems').text(total);
    }
    function calculateComplete() {
        let total = 0;
        let tax = parseFloat($('#tax').text());
        let discount = parseFloat($('#discount').text());

        for (let i = 0; i < dataProduct.length; i++) {
            total += parseFloat(dataProduct[i].subtotal);
        }
        // calcula el impuesto
        let totalTax = total * (tax / 100);
        // calcula el descuento
        let totalDiscount = total * (discount / 100);
        // suma el impuesto
        let totalWithTax = total + totalTax;
        // resta el descuento
        let totalWithDiscount = totalWithTax - totalDiscount;

        $('#totalpay').text(totalWithDiscount);
    }
    function calculateQuantity(dato) {
        console.log(dato);
        let id = '#' + dato.id;
        let productCode = $(id).data('id');
        let quantity = parseInt($(id).val());
        let subtotal = '#subtotal-' + productCode;

        if (dataProduct.length > 0) {
            let index = dataProduct.findIndex((item) => item.code == productCode);
            dataProduct[index].quantity = quantity;
            dataProduct[index].subtotal = dataProduct[index].price * dataProduct[index].quantity;
            $(subtotal).text(dataProduct[index].subtotal);
        }


        calculateTotal();
        calculateComplete();
    }
    function calculateBalance() {

        let total = parseFloat($('#totalpay').text());
        let totalPartial = 0;
        let balance = 0;
        for (let i = 0; i < dataPartial.length; i++) {
            totalPartial += parseFloat(dataPartial[i].amount);
        }
        balance = total - totalPartial;
        $('#balance').text(balance);
    }

    function deletePartial(payment) {
        dataPartial = dataPartial.filter((item) => item.payment != payment.id);
        let idtr = '#payment-' + payment.id;
        $(idtr).remove();
        // $('#payment-' + payment.id).remove();
        calculateBalance();
    }

</script>
@endSection
