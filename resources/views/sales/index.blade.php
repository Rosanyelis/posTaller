@extends('layouts.app')

@section('title') Ventas @endsection

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
            <h4 class="mb-sm-0 font-size-18">Ventas </h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item active">Ventas</li>
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
                    <div class="col-md-6">
                        <h4 class="card-title">Listado de Ventas</h4>
                    </div>
                    <div class="col-md-6 text-end">
                        <a class="btn btn-info btn-sm" target="_blank" href="{{ route('ventas.generateInforme') }}">Generar Informe de Venta</a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-2">
                        <div class="mb-3">
                            <label for="dateday">Día</label>
                            <input type="date" class="form-control" id="dateday" name="dateday">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="mb-3">
                            <label for="startday">Fecha de Inicio</label>
                            <input type="date" class="form-control" id="startday" name="startday">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="mb-3">
                            <label for="endday">Fecha Final</label>
                            <input type="date" class="form-control" id="endday" name="endday">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="mb-3">
                            <label for="vendedor">Vendedor</label>
                            <select name="vendedor" id="vendedor" class="form-control">
                                <option value="">Todos</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <button type="button" class="btn btn-primary mt-4" id="filter">Filtrar</button>
                    </div>
                </div>
                <div class="table-responsive">
                    <table id="datatable" class="table table-bordered dt-responsive nowrap w-100">
                        <thead>
                            <tr>
                                <th>Fecha</th>
                                <th>Cliente</th>
                                <th>Total</th>
                                <th>Forma de pago</th>
                                <th>Vendedor</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                        <tfoot>
                            <th colspan="2" class="text-end"><h5>Total de Ventas</h5></th>
                            <th colspan="4"></th>
                        </tfoot>
                    </table>
                </div>
                <!-- sample modal content -->
                <div id="myModal" class="modal fade" data-bs-backdrop="static" data-bs-keyboard="false"
                    tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true"
                    data-bs-scroll="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="myModalLabel">Ver Venta</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close" id="close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <strong>Cliente:</strong> <span id="name"></span>
                                    </div>
                                    <div class="col-md-6">
                                        <strong>Vendedor:</strong> <span id="vendedor"></span>
                                    </div>
                                    <div class="col-md-6">
                                        <strong>Forma de Pago:</strong> <span id="forma_pago"></span>
                                    </div>
                                    <div class="col-md-6">
                                        <strong>Total:</strong> <span id="total"></span>
                                    </div>
                                    <div class="col-md-6">
                                        <strong>Fecha:</strong> <span id="date"></span>
                                    </div>
                                    <div class="col-md-12">
                                        <strong>Notas:</strong> <span id="note"></span>
                                    </div>
                                    <div class="col-md-6">
                                        <strong>Impuesto:</strong> % <span id="tax"></span>
                                    </div>
                                    <div class="col-md-6">
                                        <strong>Descuento:</strong> % <span id="discount"></span>
                                    </div>
                                    <div class="col-md-12">
                                        <strong>Productos:</strong>
                                    </div>
                                    <hr>
                                    <div class="col-md-12 text-center">
                                        <table id="productstbl" class="table table-bordered dt-responsive nowrap w-100">
                                            <thead>
                                                <tr>
                                                    <th>Código</th>
                                                    <th>Descripción</th>
                                                    <th>Cantidad</th>
                                                    <th>Precio</th>
                                                    <th>Total</th>
                                                </tr>
                                            </thead>
                                            <tbody id="productstbody">
                                            </tbody>
                                            <tfoot>
                                                <th colspan="4" class="text-end"><h5>Total</h5></th>
                                                <th colspan="4"> <span id="total1"></span> </th>
                                            </tfoot>
                                        </table>
                                    </div>
                                    <hr>
                                    <div class="col-md-12">
                                        <div class="col-md-12 text-center">
                                            <table id="paymentstbl" class="table table-bordered dt-responsive nowrap w-100">
                                                <thead>
                                                    <tr>
                                                        <th>Metodo de Pago</th>
                                                        <th>Monto</th>
                                                        <th>Notas o Referencias</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="paymentstbody">
                                                </tbody>
                                            </table>
                                        </div>
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
    const numberFormat2 = new Intl.NumberFormat('de-DE');
    const table = $('#datatable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ route('ventas.datatable') }}",
            data: function(d) {
                d.day = $('#dateday').val();
                d.start = $('#startday').val();
                d.end = $('#endday').val();
                d.user_id = $('#vendedor').val();
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
                data: 'customer_name',
                name: 'customer_name'
            },
            {
                data: 'grand_total',
                name: 'grand_total'
            },
            {
                data: 'payment_status',
                name: 'payment_status'
            },
            {
                data: 'user_name',
                name: 'user_name'
            },
            {
                data: 'actions',
                name: 'actions',
                orderable: false,
                searchable: false
            },
        ],
        columnDefs: [{
            targets: 0,
            render: function(data) {
                return moment(data).format('DD/MM/YYYY hh:mm A');
            }
        },
        {
            targets: 2,
            render: function(data) {
                return '$ ' + numberFormat2.format(data);
            }
        },
        {
            targets: 3,
            render: function(data) {
                if (data == 'paid') {
                    return '<span class="badge bg-success">Pago Total</span>';
                } else {
                    return '<span class="badge bg-danger">Pago Parcial</span>';
                }
            }
        }],
        footerCallback: function (row, data, start, end, display) {
            var api = this.api();
            // Elimine el formato para obtener datos enteros para la suma
            var intVal = function ( i ) {
                return typeof i === 'string' ?
                    i.replace(/[\$,]/g, '')*1 :
                    typeof i === 'number' ?
                        i : 0;
            };

            // Total en todas las páginas
            total = api
                .column(2)
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
            // Total en esta página
            pageTotal = api
                .column(2, { page: 'current'} )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );

            // Actualizar pie de página
            $( api.column( 4 ).footer() ).html('<h5>$ '+ numberFormat2.format(total) +'</h5>');
            // $('#total').html('Total: ' + total.toFixed(2));
        }
    });

    $('#filter').on('click', function() {
        if ($('#startday').val() > $('#endday').val()) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'La fecha inicial no puede ser mayor que la fecha final',
                timer: 1500
            });
            return false;
        }
        if ($('#dateday').val() == '') {
            table.draw();
        }

        if ($('#startday').val() == '' || $('#endday').val() == '') {
            if ($('#startday').val() > $('#endday').val()) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'La fecha inicial no puede ser mayor que la fecha final',
                    timer: 1500
                });
                return false;
            }
            table.draw();
        }

        if ($('#dateday').val() != '' && $('#startday').val() != '' && $('#endday').val() != '') {
            if ($('#startday').val() > $('#endday').val()) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'La fecha inicial no puede ser mayor que la fecha final',
                    timer: 1500
                });
                return false;
            }

            Swal.fire({
                position: 'top-center',
                title: 'Oops!',
                text: "No puede buscar por ambos parametros al mismo tiempo, intente de nuevo.",
                icon: 'warning',
                showConfirmButton: false,
                timer: 2500
            });

            $('#dateday').val('');
            $('#startday').val('');
            $('#endday').val('');
            $('#vendedor').val('');

            table.draw();
        }

        if ($('#vendedor').val() == '' && $('#dateday').val() != '' && $('#startday').val() != '' && $('#endday').val() != '') {
            Swal.fire({
                position: 'top-center',
                title: 'Oops!',
                text: "No puede buscar por los tres parametros al mismo tiempo, intente de nuevo con solo dos parametros.",
                icon: 'warning',
                showConfirmButton: false,
                timer: 2500
            });

            $('#dateday').val('');
            $('#startday').val('');
            $('#endday').val('');
            $('#vendedor').val('');

            table.draw();
        }
    });

    function viewRecord(id) {
        $.ajax({
            url: "{{ route('ventas.show', ':id') }}"
                .replace(':id', id),
            type: 'GET',
            success: function(res) {
                $('#name').text(res.customer_name);
                $('#vendedor').text(res.user.name);
                $('#total').text(res.grand_total);
                $('#date').text(moment(res.created_at).format('DD/MM/YYYY hh:mm A'));
                if (res.payment_status == 'paid') {
                    $('#forma_pago').text('Pago Total');
                } else {
                    $('#forma_pago').text('Pago Parcial');
                }

                $('#note').text(res.note);
                $('#tax').text(res.order_tax_id);
                $('#discount').text(res.order_discount_id);

                res.saleitems.forEach((value, index) => {
                    $('#productstbl #productstbody')
                        .append('<tr>')
                        .append('<td>' + value.product_code + '</td>')
                        .append('<td>' + value.product_name + '</td>')
                        .append('<td>' + value.quantity + '</td>')
                        .append('<td>' + value.unit_price + '</td>')
                        .append('<td>' + value.subtotal + '</td>')
                        .append('</tr>');
                });

                $('#total1').text(res.grand_total);

                res.payments.forEach((value, index) => {
                    $('#paymentstbl #paymentstbody')
                        .append('<tr>')
                        .append('<td>' + value.payment_method + '</td>')
                        .append('<td>' + value.pos_paid + '</td>')
                        .append('<td>' + value.reference + '</td>')
                        .append('</tr>');
                });

                $('#myModal').modal('show');
            }
        });
    }

    $('#close').on('click', function() {
        $('#myModal').modal('hide');
        $('#name').text('');
        $('#vendedor').text('');
        $('#total').text('');
        $('#date').text('');
        $('#forma_pago').text('');
        $('#note').text('');
        $('#tax').text('');
        $('#discount').text('');

        $('#productstbl #productstbody').empty();
        $('#paymentstbl #paymentstbody').empty();

        $('#total1').text('');
    })
</script>
@endSection
