@extends('layouts.app')

@section('title') Cotizaciones @endsection

@section('css')
<!-- choices css -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

@endsection

@section('content')

<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Cotizaciones </h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('cotizaciones.index') }}">Cotizaciones</a>
                    </li>
                    <li class="breadcrumb-item active">Nueva Cotización</li>
                </ol>
            </div>

        </div>
    </div>
</div>
<!-- end page title -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header py-3">
                <h4 class="card-title">Nuevo Cotización</h4>
            </div>
            <div class="card-body p-4">
                <form id="formQuotation" action="{{ route('cotizaciones.store') }}" method="POST"
                    enctype="multipart/form-data" class="needs-validation @if ($errors->any()) was-validated @endif"
                    novalidate>
                    @csrf
                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-6">
                            <div class="mb-3">
                                <label for="customer" class="form-label">Cliente</label>
                                <select class="form-control" name="customer" id="customer" style="width: 100%">
                                    <option value="">-- Seleccione --</option>
                                    @foreach ($customers as $item)
                                    <option value="{{ $item->name }}">{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-4 col-sm-6">
                            <div class="mb-3">
                                <label for="name" class="form-label">Archivo de Propuesta detallada</label>
                                <input type="file" class="form-control" name="file_propuesta">
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-4 col-sm-6">
                            <div class="mb-3">
                                <label for="note" class="form-label">Notas</label>
                                <input type="text" class="form-control" name="note" >
                            </div>
                        </div>

                        <div class="w-100"></div>
                        <div class="col-lg-2 col-md-2 col-sm-6">
                            <div class="mb-3">
                                <label for="name" class="form-label">Fecha de Cierre</label>
                                <input type="date" class="form-control" name="closing_date" id="closing_date">
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-2 col-sm-6">
                            <div class="mb-3">
                                <label for="name" class="form-label">% de Cierre</label>
                                <input type="text" class="form-control" name="closing_percentage" id="closing_percentage">
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-6">
                            <div class="mb-3">
                                <label for="producto" class="form-label">Nombre de Producto</label>
                                <select class="form-control" name="producto" id="producto" style="width: 100%">
                                    <option value="">-- Seleccione --</option>
                                    @foreach ($products as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }} - {{ $item->category->name }}</option>
                                    @endforeach
                                </select>
                                <input type="hidden" name="product_name" id="product_name">
                                <input type="hidden" name="product_code" id="product_code">
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-2 col-sm-6">
                            <div class="mb-3">
                                <label for="name" class="form-label">Precio de Costo</label>
                                <input type="text" class="form-control" name="priceCost" id="priceCost" readonly>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-2 col-sm-6">
                            <div class="mb-3">
                                <label for="name" class="form-label">Precio de Venta</label>
                                <input type="text" class="form-control" name="priceVent" id="priceVent" readonly>
                            </div>
                        </div>

                        <div class="w-100"></div>

                        <div class="col-lg-2 col-md-2 col-sm-6">
                            <div class="mb-3">
                                <label for="name" class="form-label">Cantidad del Producto</label>
                                <input type="text" class="form-control" name="quantity" id="quantity" >
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-2 col-sm-6">
                            <div class="mb-3">
                                <label for="name" class="form-label">Precio</label>
                                <input type="text" class="form-control" name="costo_venta" id="costo_venta" >
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-2 col-sm-6">
                            <div class="mb-3">
                                <label for="name" class="form-label">Profit (%)</label>
                                <input type="text" class="form-control" name="profit" id="profit" >
                            </div>
                        </div>

                        <div class="col-lg-2 col-md-2 col-sm-6">
                            <button type="button" class="btn btn-info mt-4" id="add_product">Agregar Producto</button>
                        </div>

                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <table class="table" id="table_products">
                                <thead>
                                    <tr>
                                        <th>Producto</th>
                                        <th>Cantidad</th>
                                        <th>Costo Neto</th>
                                        <th>Profit</th>
                                        <th>Margen</th>
                                        <th>Precio Venta</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody id="table_body_products">
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="5" class="text-end"><h4>SubTotal</h4></td>
                                        <td colspan="2" ><h4 id="subtotal">0</h4></td>
                                    </tr>
                                    <tr>
                                        <td colspan="5" class="text-end"><h4>IVA (19%)</h4></td>
                                        <td colspan="2" ><h4 id="iva">0</h4></td>
                                    </tr>
                                    <tr>
                                        <td colspan="5" class="text-end"><h4>Total</h4></td>
                                        <td colspan="2" ><h4 id="total">0</h4></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    <input type="hidden" name="subtotal" id="subtotalcomplete">
                    <input type="hidden" name="total" id="totalcomplete">
                    <input type="hidden" name="iva" id="ivacomplete">
                    <input type="hidden" name="array_products" id="array_products">
                    <button type="button" id="guardar" class="btn btn-primary w-md float-end">Guardar Cotización</button>
                </form>
            </div>
        </div>
    </div>
</div>

@endSection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    var totalfinal = 0;
    var totalIVA = 0;
    var datosTabla = [];

    $(document).ready(function() {
        $('#producto').select2();
        $('#customer').select2();

        $('#producto').on('select2:select', function(e) {
            var id = $('#producto').val();
            $.ajax({
                type: 'GET',
                url: "{{ route('cotizaciones.productjson', ':id') }}".replace(':id', id),
                data: {
                    id: id
                },
                success: function(data) {
                    let costo = parseFloat(data.cost).toFixed(0);
                    let precio = parseFloat(data.price).toFixed(0);
                    $('#priceCost').val(costo);
                    $('#priceVent').val(precio);
                    $('#product_name').val(data.name);
                    $('#product_code').val(data.code);
                }
            });
        });

        $('#add_product').on('click', function() {
            let producto = $('#product_name').val();
            let code = $('#product_code').val();
            let price = parseFloat($('#costo_venta').val());
            let quantity = parseFloat($('#quantity').val());
            let profit = parseFloat($('#profit').val());
            let totalp = price * quantity;
            let margen = totalp * (profit / 100);
            let subtotal = totalp + margen;

            if (datosTabla.length > 0) {
                let index = datosTabla.findIndex((item) => item.code == code);

                if (index == -1) {
                    datosTabla.push({
                        'code': code,
                        'product': producto,
                        'quantity': quantity,
                        'price': price,
                        'profit': profit,
                        'margen': margen.toFixed(0),
                        'subtotal': subtotal.toFixed(0)
                    });

                    $("#table_products tbody").append(
                    `<tr id="row-`+code+`">
                        <td>`+producto+`</td>
                        <td id="quantity-`+code+`">`+quantity+`</td>
                        <td id="price-`+code+`">`+price+`</td>
                        <td id="profit-`+code+`">`+profit+`</td>
                        <td id="margen-`+code+`">`+margen.toFixed(0)+`</td>
                        <td id="subtotal-`+code+`">`+subtotal.toFixed(0)+`</td>
                        <td>
                            <button type="button" class="btn btn-danger btn-sm"
                                id="delete_product" data-code="`+code+`">
                                <i class="mdi mdi-delete"></i>
                            </button>
                        </td>
                    </tr>`);

                }

                if (index != -1) {
                    datosTabla[index].quantity += quantity;
                    datosTabla[index].price = price;
                    datosTabla[index].profit = profit;
                    datosTabla[index].margen = datosTabla[index].price * (profit / 100);
                    datosTabla[index].subtotal = (datosTabla[index].quantity * datosTabla[index].price) + datosTabla[index].margen;

                    let IDqty = "#quantity-"+code;
                    let IDprice = "#price-"+code;
                    let IDprofit = "#profit-"+code;
                    let IDmargen = "#margen-"+code;
                    let IDsubtotal = "#subtotal-"+code;

                    $(IDqty).text(datosTabla[index].quantity);
                    $(IDprice).text(datosTabla[index].price);
                    $(IDprofit).text(datosTabla[index].profit);
                    $(IDmargen).text(datosTabla[index].margen);
                    $(IDsubtotal).text(datosTabla[index].subtotal);
                }
            }

            if (datosTabla.length == 0 ) {
                datosTabla.push({
                    'code': code,
                    'product': producto,
                    'quantity': quantity,
                    'price': price,
                    'profit': profit,
                    'margen': margen.toFixed(0),
                    'subtotal': subtotal.toFixed(0)
                });

                $("#table_products tbody").append(
                `<tr id="row-`+code+`">
                    <td>`+producto+`</td>
                    <td id="quantity-`+code+`">`+quantity+`</td>
                    <td id="price-`+code+`">`+price+`</td>
                    <td id="profit-`+code+`">`+profit+`</td>
                    <td id="margen-`+code+`">`+margen.toFixed(0)+`</td>
                    <td id="subtotal-`+code+`">`+subtotal.toFixed(0)+`</td>
                    <td>
                        <button type="button" class="btn btn-danger btn-sm"
                            id="delete_product" data-code="`+code+`">
                            <i class="mdi mdi-delete"></i>
                        </button>
                    </td>
                </tr>`);

            }
            calcular();
            $("#producto").val(null).trigger("change");
            $("#product_name").val("");
            $("#costo_venta").val('');
            $("#quantity").val('');
            $("#profit").val('');
            $("#price").val('');
            $('#priceCost').val('');
            $('#priceVent').val('');
            $('#product_name').val('');
            $('#product_code').val('');

        });

        $('#table_products tbody').on('click', '#delete_product', function() {
            let product = $(this).data('code');
            let id = "#row-" + product;

            datosTabla = datosTabla.filter(function(item) {
                return item.code !== product;
            });

            $(id).remove();

            calcular();
        });

        $('#guardar').on('click', function() {
            if (datosTabla.length == 0) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'No hay productos agregados, por favor agrega uno',
                });
                return false;
            }

            $('#array_products').val(JSON.stringify(datosTabla));
            $('#subtotalcomplete').val(parseFloat($('#subtotal').text()));
            $('#totalcomplete').val(parseFloat($('#total').text()));
            $('#ivacomplete').val(parseFloat($('#iva').text()));
            $('#guardar').prop('disabled', true);
            $('#guardar').html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Por favor, espere...');
            $('#formQuotation').submit();
        });
    });

    function calcular() {
        totalfinal = 0;
        totalIVA = 0;
        total = 0;
        for (let i = 0; i < datosTabla.length; i++) {
            totalfinal += parseInt(datosTabla[i].subtotal);
        }
        totalIVA = parseFloat(totalfinal) * 0.19;
        total = totalfinal + totalIVA;
        $("#subtotal").empty();
        $("#subtotal").text(parseFloat(totalfinal).toFixed(0));
        $("#iva").empty();
        $("#iva").text(totalIVA.toFixed(0));
        $("#total").empty();
        $("#total").text(total.toFixed(0));
    }
</script>
@endSection
