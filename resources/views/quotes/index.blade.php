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
                    <div class="col-md-6">
                        <h4 class="card-title">Listado de Cotizaciones</h4>
                    </div>
                    <div class="col-md-6 text-end">
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
                    <table id="datatable" class="table table-bordered dt-responsive nowrap w-100">
                        <thead>
                            <tr>
                                <th>Fecha</th>
                                <th>Cliente</th>
                                <th>Rut Cliente</th>
                                <th>Total</th>
                                <th>Descuento</th>
                                <th>Gran Total</th>
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
                                <div class="row">
                                    <div class="col-md-12">
                                        <strong>Cliente:</strong> <span id="name"></span>
                                    </div>
                                    <div class="col-md-6">
                                        <strong>Fecha de Cotización:</strong> <span id="date"></span>
                                    </div>
                                    <div class="col-md-6">
                                        <strong>Total :</strong> <span id="total"></span>
                                    </div>
                                    <div class="col-md-12">
                                        <strong>Notas:</strong> <span id="note"></span>
                                    </div>
                                    <hr>
                                    <div class="col-md-12 text-center">
                                        <h4>Productos</h4>
                                    </div>
                                    <hr>
                                    <div class="col-md-12">
                                        <table class="table table-bordered nowrap w-100">
                                            <thead>
                                                <tr class="text-center">
                                                    <th>Código</th>
                                                    <th>Articulo</th>
                                                    <th>Cantidad</th>
                                                    <th>Precio</th>
                                                    <th>Descuento</th>
                                                    <th>Total</th>
                                                </tr>
                                            </thead>
                                            <tbody id="details" class="text-center">
                                            </tbody>
                                            <tfoot>
                                                <tr></tr>
                                                    <td colspan="5" class="text-end">Total</td>
                                                    <td id="total2"></td>
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
    $('#datatable').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('cotizaciones.index') }}",
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
                data: 'customer',
                name: 'customer'
            },
            {
                data: 'rut',
                name: 'rut'
            },
            {
                data: 'total',
                name: 'total'
            },
            {
                data: 'total_discount',
                name: 'total_discount'
            },
            {
                data: 'grand_total',
                name: 'grand_total'
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
            render: function (data) {
                return moment(data).format('DD/MM/YYYY hh:mm A');
            }
        }]
    });


    function viewRecord(id) {
        $.ajax({
            url: "{{ route('cotizaciones.show', ':id') }}"
                .replace(':id', id),
            type: 'GET',
            success: function(res) {
                console.log(res);
                $('#name').text(res.customer_name);
                $('#date').text(moment(res.created_at).format('DD/MM/YYYY hh:mm A'));
                $('#total').text(res.grand_total);
                $('#note').text(res.note);
                $('#total2').text(res.grand_total);

                $('#details').empty();

                res.items.forEach((value, index) => {
                    $('#details')
                        .append('<tr>')
                        .append('<td>' + value.product_code + '</td>')
                        .append('<td>' + value.product_name + '</td>')
                        .append('<td>' + value.quantity + '</td>')
                        .append('<td>' + value.unit_price + '</td>')
                        .append('<td>' + value.discount + '</td>')
                        .append('<td>' + value.subtotal + '</td>')
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

    $('#close').on('click', function() {
        $('#myModal').modal('hide');
        $('#name').text('');
        $('#date').text('');
        $('#total').text('');
        $('#note').text('');
        $('#total2').text('');
        $('#details').empty();
    })
</script>
@endSection