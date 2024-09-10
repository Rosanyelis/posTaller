@extends('layouts.app')

@section('title') Cotización @endsection

@section('css')
<!-- DataTables -->
<link href="{{ asset('assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}"
    rel="stylesheet" type="text/css" />
<link
    href="{{ asset('assets/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css') }}"
    rel="stylesheet" type="text/css" />
<!-- Responsive datatable examples -->
<link
    href="{{ asset('assets/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css') }}"
    rel="stylesheet" type="text/css" />
@endsection

@section('content')

<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Cotización </h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item active">Cotización</li>
                </ol>
            </div>

        </div>
    </div>
</div>
<!-- end page title -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header ">
                <div class="row">
                    <div class="col-md-9">
                        <div class="row g-1">
                            <div class="col-md-2">
                                <input type="date" class="form-control form-control-sm" id="startday" name="startday">
                            </div>
                            <div class="col-md-2">
                                <input type="date" class="form-control form-control-sm" id="endday" name="endday">
                            </div>
                            <div class="col-md-2">
                                <select name="vendedor" id="vendedor" class="form-control form-control-sm">
                                    <option value="">Vendedores</option>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <select name="cliente" id="cliente" class="form-control form-control-sm">
                                    <option value="">Clientes</option>
                                    @foreach ($clients as $client)
                                        <option value="{{ $client->id }}">{{ $client->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <select name="status" id="status" class="form-control form-control-sm">
                                    <option value="">Estatus</option>
                                    <option value="Cotizado">Cotizado</option>
                                    <option value="Facturado">Facturado</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <button type="button" class="btn btn-primary btn-sm" id="filter"
                                    title="Filtrar">
                                    <i class="mdi mdi-filter"></i>
                                </button>
                                <button type="button" class="btn btn-danger btn-sm" id="removefilter"
                                    title="Quitar filtro">
                                    <i class="mdi mdi-filter-remove"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 text-end">
                        <a href="{{ route('cotizaciones.create') }}"
                            class="btn btn-primary btn-sm ">
                            <i class="mdi mdi-plus"></i>
                            Nueva Cotización
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">

                <div class="table-responsive">
                    <table id="datatable" class="table table-bordered dt-responsive w-100">
                        <thead>
                            <tr>
                                <th>Fecha</th>
                                <th>N° Coti</th>
                                <th>Cliente</th>
                                <th>Vendedor</th>
                                <th>Valor Neto</th>
                                <th>F. Cierre</th>
                                <th>Profit Neto</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
                <!-- sample modal content -->
                <div id="myModal" class="modal fade" data-bs-backdrop="static" data-bs-keyboard="false"
                    tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true"
                    data-bs-scroll="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="myModalLabel">Ver Cotización</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close" id="close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <strong>Cliente:</strong><br>
                                        <span id="name"></span>
                                    </div>
                                    <div class="col-md-4">
                                        <strong>Total :</strong><br>
                                        <span id="totalfinal"></span>
                                    </div>
                                    <div class="col-md-4">
                                        <strong>Fecha de Cotización:</strong><br>
                                        <span id="date"></span>
                                    </div>
                                    <div class="col-md-4">
                                        <strong>Archivo de Propuesta detallada:</strong><br>
                                        <span id="file"></span>
                                    </div>

                                    <div class="col-md-12">
                                        <strong>Notas:</strong><br>
                                        <span id="note"></span>
                                    </div>

                                    <div class="col-md-12 text-center">
                                        <h4>Detalles de Cotización</h4>
                                    </div>

                                    <div class="col-md-12">
                                        <table class="table table-sm table-striped table-bordered nowrap w-100">
                                            <thead>
                                                <tr class="text-center text-uppercase fw-semibold">
                                                    <th>Código</th>
                                                    <th>Articulo</th>
                                                    <th>Cantidad</th>
                                                    <th>Precio</th>
                                                    <th>Profit</th>
                                                    <th>Margen</th>
                                                    <th>Subtotal</th>
                                                </tr>
                                            </thead>
                                            <tbody id="details" class="text-center">
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <td colspan="6" class="text-end fw-semibold">Subtotal</td>
                                                    <td id="subtotal" class="text-center fw-semibold"></td>
                                                </tr>
                                                <tr>
                                                    <td colspan="6" class="text-end fw-semibold">IVA (% 19)</td>
                                                    <td id="iva" class="text-center fw-semibold"></td>
                                                </tr>
                                                <tr>
                                                    <td colspan="6" class="text-end fw-semibold">Total</td>
                                                    <td id="total" class="text-center fw-semibold"></td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary waves-effect"
                                    data-bs-dismiss="modal" id="close">Cerrar</button>
                            </div>
                        </div><!-- /.modal-content -->
                    </div><!-- /.modal-dialog -->
                </div><!-- /.modal -->

                <div id="myModalFactura" class="modal fade" data-bs-backdrop="static" data-bs-keyboard="false"
                    tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true"
                    data-bs-scroll="true">
                    <div class="modal-dialog modal-md">
                        <div class="modal-content">
                            <form id="myFormFactura" action="{{ route('cotizaciones.addReferencias') }}" method="post">
                                @csrf

                                <div class="modal-header">
                                    <h5 class="modal-title" id="myModalLabel">Agregar Número de Factura </h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close" id="close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="row g-3">
                                        <div class="col-md-12">
                                            <strong>N° de Factura:</strong>
                                            <input type="number" class="form-control" id="invoice_number" name="invoice_number" placeholder="N° de Factura">

                                            <input type="hidden" id="id" name="id" value="">
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary waves-effect"
                                        data-bs-dismiss="modal" id="close">Cerrar</button>

                                    <button type="submit" class="btn btn-primary waves-effect"
                                     >Guardar</button>
                                </div>
                            </form>
                        </div><!-- /.modal-content -->
                    </div><!-- /.modal-dialog -->
                </div>

                <form id="my-form" action="{{ route('cotizaciones.cambiarStatus') }}" method="POST">
                    @csrf
                    <input type="hidden" id="id" name="id" >
                    <input type="hidden" id="status" name="status">
                </form>

            </div>
        </div>
    </div> <!-- end col -->
</div> <!-- end row -->


@endSection

@section('scripts')
<!-- Required datatable js -->
<script src="{{ asset('assets/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}">
</script>
<!-- Buttons examples -->
<script src="{{ asset('assets/libs/datatables.net-buttons/js/dataTables.buttons.min.js') }}">
</script>
<script
    src="{{ asset('assets/libs/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js') }}">
</script>
<script src="{{ asset('assets/libs/jszip/jszip.min.js') }}"></script>
<script src="{{ asset('assets/libs/pdfmake/build/pdfmake.min.js') }}"></script>
<script src="{{ asset('assets/libs/pdfmake/build/vfs_fonts.js') }}"></script>
<script src="{{ asset('assets/libs/datatables.net-buttons/js/buttons.html5.min.js') }}">
</script>
<script src="{{ asset('assets/libs/datatables.net-buttons/js/buttons.print.min.js') }}">
</script>
<script src="{{ asset('assets/libs/datatables.net-buttons/js/buttons.colVis.min.js') }}">
</script>

<!-- Responsive examples -->
<script
    src="{{ asset('assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js') }}">
</script>
<script
    src="{{ asset('assets/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js') }}">
</script>
<script src="{{ asset('assets/js/moment.min.js') }}"></script>
<!-- Datatable init js -->
<script>
    const basepath = "{{ asset('assets/images/') }}";
    const baseStorage = "{{ asset('') }}";
    const numberFormat2 = new Intl.NumberFormat('de-DE');
    const table = $('#datatable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ route('cotizaciones.datatable') }}",
            data: function(d) {
                d.start = $('#startday').val();
                d.end = $('#endday').val();
                d.user_id = $('#vendedor').val();
                d.customer_id = $('#cliente').val();
                d.status = $('#status').val();
            }
        },
        dataType: 'json',
        type: "POST",
        lengthMenu: [
            [10, 25, 50, 100, -1],
            [10, 25, 50, 100, "All"]
        ],
        responsive: true,
        language: {
            url: "https://cdn.datatables.net/plug-ins/2.0.8/i18n/es-ES.json",
        },
        columns: [
            {
                data: 'created_at',
                name: 'created_at'
            },
            {
                data: 'correlativo',
                name: 'correlativo'
            },
            {
                data: 'customer',
                name: 'customer'
            },
            {
                data: 'user',
                name: 'user'
            },
            {
                data: 'subtotal',
                name: 'subtotal'
            },
            {
                data: 'closing_date',
                name: 'closing_date'
            },
            {
                data: 'grand_total',
                name: 'grand_total'
            },
            {
                data: 'status',
                name: 'status'
            },
            {
                data: 'actions',
                name: 'actions',
                orderable: false,
                searchable: false
            },
        ],
        columnDefs: [{
            targets: [0, 5],
            render: function (data) {
                return moment(data).format('DD/MM/YYYY');
            }
        },
        {
            targets:[4, 6],
            render: function (data) {
                return '$ ' + numberFormat2.format(data);
            }
        },
        {
            targets: [7],
            render: function (data, type, row) {
                if (data == 'Cotizado') {
                    return `<div class="btn-group">
                                <button type="button" class="btn btn-primary btn-sm dropdown-toggle"
                                    data-bs-toggle="dropdown" aria-expanded="false">`+data+`
                                    <i class="mdi mdi-chevron-down"></i>
                                </button>
                                <div class="dropdown-menu dropdownmenu-primary" style="">
                                    <div class="dropdown-header noti-title">
                                        <h5 class="font-size-13 text-muted text-truncate mn-0">
                                            Cambiar a
                                        </h5>
                                    </div>
                                    <a class="dropdown-item" href="#" onclick="changeStatus('Facturado', ${row.id})">Facturado</a>
                                    <a class="dropdown-item" href="#" onclick="changeStatus('Pagado', ${row.id})">Pagado</a>
                                </div>
                            </div>`;
                }
                if (data == 'Facturado') {
                    if (row.invoice_number == null) {
                        $botones = `<div class="btn-group">
                                <button type="button" class="btn btn-warning btn-sm dropdown-toggle"
                                    data-bs-toggle="dropdown" aria-expanded="false">`+data+`
                                    <i class="mdi mdi-chevron-down"></i>
                                </button>
                                <div class="dropdown-menu dropdownmenu-warning" style="">
                                    <div class="dropdown-header noti-title">
                                        <h5 class="font-size-13 text-muted text-truncate mn-0">
                                            Cambiar a
                                        </h5>
                                    </div>
                                    <a class="dropdown-item" href="#" onclick="addReferencia(${row.id})">Agregar Nro. de factura</a>
                                    <a class="dropdown-item" href="#" onclick="changeStatus('Pagado', ${row.id})">Pagado</a>
                                </div>
                            </div>`;
                    }

                    if (row.invoice_number != null) {
                        $botones = `<div class="btn-group">
                                <button type="button" class="btn btn-warning btn-sm dropdown-toggle"
                                    data-bs-toggle="dropdown" aria-expanded="false">`+data+`
                                    <i class="mdi mdi-chevron-down"></i>
                                </button>
                                <div class="dropdown-menu dropdownmenu-warning" style="">
                                    <div class="dropdown-header noti-title">
                                        <h5 class="font-size-13 text-muted text-truncate mn-0">
                                            Cambiar a
                                        </h5>
                                    </div>
                                    <a class="dropdown-item" href="#" onclick="changeStatus('Pagado', ${row.id})">Pagado</a>
                                </div>
                            </div>`;
                    }

                    return $botones;
                }
            }
        }],
    });

    $('#filter').on('click', function(){
        table.draw();
    });

    function viewRecord(id) {
        $.ajax({
            url: "{{ route('cotizaciones.show', ':id') }}"
                .replace(':id', id),
            type: 'GET',
            success: function(res) {
                $('#name').text(res.customer_name);
                $('#date').text(moment(res.created_at).format('DD/MM/YYYY hh:mm A'));
                $('#totalfinal').text(numberFormat2.format(res.grand_total));
                $('#subtotal').text(numberFormat2.format(res.subtotal));
                $('#iva').text(numberFormat2.format(res.iva));
                $('#total').text(numberFormat2.format(res.grand_total));
                $('#note').text(res.note);
                if (res.file_propuesta != '') {
                    $('#file').empty();
                    $('#file').append('<a href="' + baseStorage + res.file_propuesta + '" target="_blank">Ver propuesta</a>');
                }
                if (res.file_propuesta == null){
                    $('#file').empty();
                    $('#file').append('Sin archivo');
                }


                $('#details').empty();
                res.items.forEach((value, index) => {
                    $('#details')
                        .append('<tr>')
                        .append('<td>' + value.product_code + '</td>')
                        .append('<td>' + value.product_name + '</td>')
                        .append('<td>' + value.quantity + '</td>')
                        .append('<td>' + numberFormat2.format(value.price) + '</td>')
                        .append('<td>' + numberFormat2.format(value.profit) + '</td>')
                        .append('<td>' + numberFormat2.format(value.margen) + '</td>')
                        .append('<td>' + numberFormat2.format(value.subtotal) + '</td>')
                        .append('</tr>');
                })

                $('#myModal').modal('show');
            }
        });

    }

    function deleteRecord(id) {
        Swal.fire({
            title: '¿Esta seguro de eliminar esta Cotizacion?',
            text: "No podra recuperar la información!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si, eliminar!'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href =
                    "{{ route('cotizaciones.destroy', ':id') }}"
                    .replace(':id', id);
            }
        })
    }
    function changeStatus(status, id) {
        $('#my-form #status').val(status);
        $('#my-form #id').val(id);

        Swal.fire({
            title: '¿Esta seguro de cambiar el estado a "' + status + '" de la Cotizacion?',
            text: "No podra cambiar el estado si es Pagado!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si, cambiar!'
        }).then((result) => {
            if (result.isConfirmed) {
                $('#my-form').submit();
            }
        })
    }

    function addReferencia(id) {
        $('#myFormFactura #id').val(id);
        $('#myFormFactura #nro_factura').val('');
        $('#myModalFactura').modal('show');
    }

    $('#close').on('click', function() {
        $('#myModal').modal('hide');
        $('#name').text('');
        $('#date').text('');
        $('#totalfinal').text('');
        $('#subtotal').text('');
        $('#iva').text('');
        $('#total').text('');
        $('#note').text('');
        $('#details').empty();
    });
    $('#removefilter').on('click', function() {
        $('#startday').val('').trigger('change');
        $('#endday').val('').trigger('change');
        $('#vendedor').val('').trigger('change');
        $('#status').val('').trigger('change');
        $('#cliente').val('').trigger('change');
        table.draw();
    });
</script>
@endSection
