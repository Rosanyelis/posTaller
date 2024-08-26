@extends('layouts.app')

@section('title') Ordenes de Trabajo @endsection

@section('css')
<!-- choices css -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

@endsection

@section('content')

<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Ordenes de Trabajo </h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('ordenes-trabajo.index') }}">Ordenes de Trabajo</a>
                    </li>
                    <li class="breadcrumb-item active">Nueva Orden de Trabajo</li>
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
                <h4 class="card-title">Nueva Orden de Trabajo</h4>
                <p class="card-title-desc">Verifique la información al registrar, ya que si recarga la página, esta se perderá.</p>
            </div>
            <div class="card-body p-4">
                <form id="formQuotation" action="{{ route('ordenes-trabajo.store') }}" method="POST"
                    enctype="multipart/form-data" class="needs-validation"
                    novalidate>
                    @csrf
                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-6">
                            <div class="mb-3">
                                <label for="customer" class="form-label">Cliente</label>
                                <select class="form-control @if ($errors->has('customer')) is-invalid @endif "
                                name="customer" id="customer"
                                style="width: 100%">
                                    <option value="">-- Seleccione --</option>
                                    @foreach ($customers as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('customer'))
                                <div class="invalid-feedback">{{ $errors->first('customer') }}</div>
                                @endif
                            </div>
                        </div>

                        <div class="w-100"></div>
                        <hr>
                        <div class="col-lg-4 col-md-4 col-sm-6">
                            <div class="mb-3">
                                <label for="marca" class="form-label">Marca de Vehiculo</label>
                                <input type="text" class="form-control" name="marca" id="marca" >
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-4 col-sm-6">
                            <div class="mb-3">
                                <label for="modelo" class="form-label">Modelo de Vehiculo</label>
                                <input type="text" class="form-control" name="modelo" id="modelo" >
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-4 col-sm-6">
                            <div class="mb-3">
                                <label for="patente_vehiculo" class="form-label">Patente de Vehiculo</label>
                                <input type="text" class="form-control" name="patente_vehiculo" id="patente_vehiculo" >
                            </div>
                        </div>
                        <div class="w-100"></div>
                        <hr>
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <p><strong>Nota Importante:</strong> La cantidad indicada en la tabla de abajo, es la cantidad de Servicios que se aplicaran en el vehículo, de igual forma para los productos standard</p>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-6">
                            <div class="mb-3">
                                <label for="producto" class="form-label">Nombre de Producto</label>
                                <select class="form-control" name="producto" id="producto" style="width: 100%">
                                    <option value="">-- Seleccione --</option>
                                    @foreach ($products as $item)
                                    <option value="{{ $item->name }}">{{ $item->name }}   - ($ {{ number_format($item->price, 0, ',', '.') }})</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-2 col-md-2 col-sm-6">
                            <div class="mb-3">
                                <label for="name" class="form-label">Cantidad</label>
                                <input type="text" class="form-control" name="quantity" id="quantity" >
                            </div>
                        </div>

                        <div class="col-lg-2 col-md-2 col-sm-6">
                            <div class="mb-3">
                                <label for="details" class="form-label">Detalle</label>
                                <input type="text" class="form-control" name="details" id="details" >
                            </div>
                        </div>

                        <div class="col-lg-2 col-md-2 col-sm-6">
                            <div class="mb-3">
                                <label for="cost" class="form-label">Costo Unit. del Servicio</label>
                                <input type="text" class="form-control" name="cost" id="cost" >
                            </div>
                        </div>

                        <div class="col-lg-2 col-md-2 col-sm-6">
                            <button type="button" class="btn btn-info mt-4" id="add_product">Agregar Servicio</button>
                        </div>

                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <table class="table" id="table_products">
                                <thead>
                                    <tr>
                                        <th>Producto</th>
                                        <th>Detalles</th>
                                        <th>Cantidad</th>
                                        <th>Costo</th>
                                        <th>Total</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody id="table_body_products">
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="4" class="text-end"><h4>Total</h4></td>
                                        <td colspan="2" ><h4 id="total">0</h4></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    <input type="hidden" name="total" id="totalform">
                    <input type="hidden" name="array_products" id="array_products">
                    <button type="button" id="guardar" class="btn btn-primary w-md float-end">Guardar Orden de Trabajo</button>
                </form>
            </div>
        </div>
    </div>
</div>

@endSection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    var datosTabla = [];
    $(document).ready(function() {
        $('#producto').select2();
        $('#customer').select2();

        $('#producto').on('select2:select', function(e) {
            var name = $('#producto').val();
            $.ajax({
                type: 'POST',
                url: "{{ route('ordenes-trabajo.productjson') }}",
                data: {
                    _token: "{{ csrf_token() }}",
                    name: name
                },
                success: function(data) {
                    $('#cost').val(data.price);
                }
            });
        });

        $('#add_product').on('click', function() {
            var producto = $('#producto').val();
            var details = $('#details').val();
            let cost = parseFloat($('#cost').val());
            let quantity = parseInt($('#quantity').val());
            let subtotal = cost * quantity;
            let total = subtotal;

            if (datosTabla.length > 0)
            {
                let index = datosTabla.findIndex((item) => item.producto == producto);

                if (index == -1)
                {
                    let datosFila = {};
                        datosFila.producto = producto;
                        datosFila.details = details;
                        datosFila.quantity = quantity;
                        datosFila.cost = cost;
                        datosFila.total = total;
                        datosTabla.push(datosFila);

                    $("#table_products tbody").append(
                        `<tr>
                            <td>`+producto+`</td>
                            <td>`+details+`</td>
                            <td id="quantity-`+producto+`">`+quantity+`</td>
                            <td>`+cost+`</td>
                            <td id="total-`+producto+`">`+total+`</td>
                            <td>
                                <button type="button" class="btn btn-danger btn-sm"
                                    id="delete_product" data-name="`+producto+`">
                                    <i class="mdi mdi-delete"></i>
                                </button>
                            </td>
                        </tr>`);

                    $("#table_products tfoot #total").empty();
                    calculateTotal();

                } else if (index != -1)
                {

                    datosTabla[index].quantity = parseInt(datosTabla[index].quantity) + quantity;
                    datosTabla[index].cost = parseFloat(cost);
                    datosTabla[index].total = parseFloat(datosTabla[index].total) + parseFloat(total);

                    $("#table_products tbody").find('tr').each(function() {
                        if ($(this).find('td').eq(0).text() == producto) {
                            $(this).find('td').eq(2).text(datosTabla[index].quantity);
                            $(this).find('td').eq(4).text(datosTabla[index].total);

                            console.log($(this).find('td').eq(2).text());
                            console.log($(this).find('td').eq(4).text());

                        }
                    });

                    $("#table_products tfoot #total").empty();
                    calculateTotal();
                }

            } else {
                let datosFila = {};
                    datosFila.producto = producto;
                    datosFila.details = details;
                    datosFila.quantity = quantity;
                    datosFila.cost = cost;
                    datosFila.total = total;
                    datosTabla.push(datosFila);

                $("#table_products tbody").append(
                    `<tr>
                        <td>`+producto+`</td>
                        <td>`+details+`</td>
                        <td id="quantity-`+producto+`">`+quantity+`</td>
                        <td>`+cost+`</td>
                        <td id="total-`+producto+`">`+total+`</td>
                        <td>
                            <button type="button" class="btn btn-danger btn-sm"
                                id="delete_product" data-name="`+producto+`">
                                <i class="mdi mdi-delete"></i>
                            </button>
                        </td>
                    </tr>`);

                $("#table_products tfoot #total").empty();

                calculateTotal();
            }

            $("#quantity").val('');
            $("#cost").val('');
            $("#details").val('');
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
            $("#table_products tfoot #total").empty();
            let totalfinal = 0;
            for (let i = 0; i < datosTabla.length; i++) {
                totalfinal += datosTabla[i].total;
            }
            $("#total").append(totalfinal);
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
            $('#totalform').val($('#total').text());
            $('guardar').prop('disabled', true);
            $('guardar').html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Por favor, espere...');
            $('#formQuotation').submit();
        });
    })

    function calculateTotal() {
        let totalfinal = 0;
        for (let i = 0; i < datosTabla.length; i++) {
            totalfinal += parseFloat(datosTabla[i].total);
        }
        $("#table_products tfoot #total").append(totalfinal);
    }
</script>
@endSection
