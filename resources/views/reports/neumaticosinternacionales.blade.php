@extends('layouts.app')

@section('title') Reportes @endsection

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
            <h4 class="mb-sm-0 font-size-18">Reportes </h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item active">Reportes</li>
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
                        <h4 class="card-title">Listado de Neumaticos Internacionales Comprados y su Peso</h4>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
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
                    <div class="col-md-4">
                        <button type="button" class="btn btn-primary mt-4" id="filter">
                            <i class="mdi mdi-filter"></i> Filtrar
                        </button>
                        <button type="button" class="btn btn-danger mt-4" id="removefilter"
                            title="Eliminar filtro">
                            <i class="mdi mdi-filter-remove"></i>
                        </button>
                        <button type="button" class="btn btn-success mt-4" id="informe">
                            <i class="mdi mdi-file-pdf"></i>
                            Generar Informe
                        </button>
                        <form id="formfilter" action="{{ route('reportes.pdfNeumaticosInternacionales') }}" method="post" target="_blank">
                            @csrf
                            <input type="hidden" name="start" id="startfilter">
                            <input type="hidden" name="end" id="endfilter">
                        </form>
                    </div>
                </div>
                <div class="table-responsive">
                    <table id="datatable" class="table table-bordered dt-responsive nowrap w-100">
                        <thead>
                            <tr>
                                <th>Fecha</th>
                                <th>Producto</th>
                                <th>Tipo</th>
                                <th>Cant.</th>
                                <th>Precio Compra</th>
                                <th>SubTotal</th>
                                <th>Peso</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
                <!-- sample modal content -->
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
            url: "{{ route('reportes.datatableNeumaticosInternacionales') }}",
            data: function(d) {
                d.start = $('#startday').val();
                d.end = $('#endday').val();
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
                data: 'product_name',
                name: 'product_name'
            },
            {
                data: 'type',
                name: 'type'
            },
            {
                data: 'quantity',
                name: 'quantity'
            },
            {
                data: 'price_purchase',
                name: 'price_purchase'
            },
            {
                data: 'subtotal',
                name: 'subtotal'
            },
            {
                data: 'weight',
                name: 'weight'
            }
        ],
        columnDefs: [{
                targets: 0,
                render: function(data) {
                    return moment(data).format('DD/MM/YYYY ');
                }
            },
            {
                targets: [4, 5],
                render: function(data) {
                    return '$ ' + numberFormat2.format(data);
                }
            },
        ],

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

        table.draw();
    });

    $('#removefilter').on('click', function() {
        $('#startday').val('').trigger('change');
        $('#endday').val('').trigger('change');
        table.draw();
    });

    $('#informe').on('click', function() {
        if ($('#startday').val() != '' && $('#endday').val() != '' ) {
            if ($('#startday').val() > $('#endday').val()) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'La fecha inicial no puede ser mayor que la fecha final',
                    timer: 1500
                });
                return false;
            }
            $('#startfilter').val($('#startday').val());
            $('#endfilter').val($('#endday').val());
            $('#formfilter').submit();

        } else {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Por favor, seleccione las fechas',
                timer: 1500
            });
            return false;
        }

    });

</script>
@endSection
