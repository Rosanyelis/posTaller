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
                    <li class="breadcrumb-item active">Editar Cotización</li>
                </ol>
            </div>

        </div>
    </div>
</div>
<!-- end page title -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Editar Cotización</h4>
                <p class="card-title-desc">Verifique la información al registrar, ya que si recarga la página, esta se perderá.</p>
            </div>
            <div class="card-body p-4">
                <form id="formQuotation" action="{{ route('cotizaciones.update', $quotation->id) }}" method="POST"
                    enctype="multipart/form-data" class="needs-validation @if ($errors->any()) was-validated @endif"
                    novalidate>
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-6">
                            <div class="mb-3">
                                <label for="customer" class="form-label">Cliente</label>
                                <select class="form-control" name="customer" id="customer" style="width: 100%">
                                    <option value="">-- Seleccione --</option>
                                    @foreach ($customers as $item)
                                    <option value="{{ $item->name }}" {{ $item->name == $quotation->customer_name ? 'selected' : '' }} >{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-8 col-md-8 col-sm-6">
                            <div class="mb-3">
                                <label for="note" class="form-label">Notas</label>
                                <input type="text" class="form-control" name="note" id="note" value="{{ $quotation->note }}" >
                            </div>
                        </div>

                        <div class="w-100"></div>

                        <div class="col-lg-4 col-md-4 col-sm-6">
                            <div class="mb-3">
                                <label for="producto" class="form-label">Nombre de Producto</label>
                                <select class="form-control" name="producto" id="producto" style="width: 100%">
                                    <option value="">-- Seleccione --</option>
                                    @foreach ($products as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                </select>
                                <input type="hidden" name="product_name" id="product_name">
                            </div>
                        </div>

                        <div class="col-lg-2 col-md-2 col-sm-6">
                            <div class="mb-3">
                                <label for="name" class="form-label">Precio del Producto</label>
                                <input type="text" class="form-control" name="price" id="price" >
                            </div>
                        </div>

                        <div class="col-lg-2 col-md-2 col-sm-6">
                            <div class="mb-3">
                                <label for="name" class="form-label">Cantidad del Producto</label>
                                <input type="text" class="form-control" name="quantity" id="quantity" >
                            </div>
                        </div>

                        <div class="col-lg-2 col-md-2 col-sm-6">
                            <div class="mb-3">
                                <label for="name" class="form-label">Desc. del Producto</label>
                                <input type="text" class="form-control" name="discount" id="discount" >
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
                                        <th>Precio</th>
                                        <th>Descuento</th>
                                        <th>Total</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody id="table_body_products">

                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="4" class="text-end"><h4>Total</h4></td>
                                        <td colspan="2" ><h4 id="total"></h4></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    <input type="hidden" name="total" id="totalform">
                    <input type="hidden" name="array_products" id="array_products">
                    <button type="button" id="guardar" class="btn btn-primary w-md float-end">Actualizar Cotización</button>
                </form>
            </div>
        </div>
    </div>
</div>

@endSection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>

    $(document).ready(function() {
        $('#producto').select2();
        $('#customer').select2();
        var datosTabla = [];

        $('#producto').on('select2:select', function(e) {
            var id = $('#producto').val();
            console.log(id);
            $.ajax({
                type: 'GET',
                url: "{{ route('cotizaciones.productjson', ':id') }}".replace(':id', id),
                data: {
                    id: id
                },
                success: function(data) {
                    $('#price').val(data.price);
                    $('#product_name').val(data.name);
                }
            });
        });

        // obtener la data de la tabla actual
        var oldData = @json($quotation->items);
        var totalactual = @json($quotation->total);
        $("#total").empty();
        $("#total").append(parseFloat(totalactual));

        $.each(oldData, function(index, value) {
            let datosFila = {};
                datosFila.producto = value.product_name;
                datosFila.quantity = value.quantity;
                datosFila.price = value.unit_price;
                datosFila.discount = value.discount;
                datosFila.total = value.subtotal;
                datosTabla.push(datosFila);

            $("#table_products tbody").append(
                `<tr>
                    <td>`+value.product_name+`</td>
                    <td>`+value.quantity+`</td>
                    <td>`+value.unit_price+`</td>
                    <td>`+value.discount+`</td>
                    <td>`+value.subtotal+`</td>
                    <td>
                         <button type="button" class="btn btn-danger btn-sm"
                            id="delete_product" data-name="`+value.product_name+`">
                            <i class="mdi mdi-delete"></i>
                        </button>
                    </td>
                </tr>`);
        });



        $('#add_product').on('click', function() {
            var producto = $('#product_name').val();
            let price = parseFloat($('#price').val());
            let quantity = parseFloat($('#quantity').val());
            let discountPercent = 0;
            if ($('#discount').val() == '') {
                discountPercent = 0
            } else {
                discountPercent = parseFloat($('#discount').val());
            }
            let discount = 0;
            let subtotal = price * quantity;
            if (discountPercent != '') {
                discount = subtotal * (discountPercent / 100) ;
            }

            let total = subtotal - discount;

            let datosFila = {};
                datosFila.producto = producto;
                datosFila.quantity = quantity;
                datosFila.price = price;
                datosFila.discount = discount;
                datosFila.total = total;
                datosTabla.push(datosFila);

            $("#table_products tbody").append(
                `<tr>
                    <td>`+producto+`</td>
                    <td>`+quantity+`</td>
                    <td>`+price+`</td>
                    <td>`+discountPercent+`</td>
                    <td>`+total+`</td>
                    <td>
                        <button type="button" class="btn btn-danger btn-sm"
                            id="delete_product" data-name="`+producto+`">
                            <i class="mdi mdi-delete"></i>
                        </button>
                    </td>
                </tr>`);


            $("#total").empty();
            let totalfinal = 0;
            for (let i = 0; i < datosTabla.length; i++) {
                totalfinal += parseFloat(datosTabla[i].total);
            }
            $("#total").append(totalfinal);

            console.log(datosTabla);

            $("#discount").val('');
            $("#quantity").val('');
            $("#price").val('');
            $("#product_name").val("");
            $("#producto").val(null).trigger("change");
        });

        $('#table_products').on('click', '#delete_product', function() {
            let product = $(this).data('name');
            $("#table_products tbody").find('tr').each(function() {
                if ($(this).find('td').eq(0).text() == product) {
                    $(this).remove();
                }
            });

            datosTabla = datosTabla.filter(function(item) {
                return item.producto !== product;
            });
            console.log(datosTabla);
            $("#total").empty();
            let totalfinal = 0;
            for (let i = 0; i < datosTabla.length; i++) {
                totalfinal += parseFloat(datosTabla[i].total);
            }
            $("#total").append(totalfinal);
        });

        $('#guardar').on('click', function() {
            $('#array_products').val(JSON.stringify(datosTabla));
            $('#totalform').val($('#total').text());
            $('guardar').prop('disabled', true);
            $('guardar').html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Por favor, espere...');
            $('#formQuotation').submit();
        });
    })
</script>
@endSection