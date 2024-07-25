@extends('layouts.app')

@section('title') Productos @endsection

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
            <h4 class="mb-sm-0 font-size-18">Productos </h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item active">Productos</li>
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
                        <h4 class="card-title">Listado de Productos</h4>
                    </div>
                    <div class="col-md-6 text-end">
                        <div class="btn-group">
                            <button type="button" class="btn btn-info btn-sm dropdown-toggle"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="mdi mdi-file-pdf"></i>Exportar
                                <i class="mdi mdi-chevron-down"></i>
                            </button>
                            <div class="dropdown-menu" style="">
                                <a class="dropdown-item" target="_blank" href="{{ route('products.allproductpdf') }}">Todos los productos</a>
                            </div>
                        </div>
                        <a href="{{ route('productos.viewimport') }}"
                            class="btn btn-success btn-sm ">
                            <i class="mdi mdi-file-excel"></i>
                            Importar Productos
                        </a>
                        <a href="{{ route('productos.create') }}"
                            class="btn btn-primary btn-sm ">
                            <i class="mdi mdi-plus"></i>
                            Nuevo Producto
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <p>El informe se genera solo si aplica el filtro, sino hay datos no podra generarse
                            el informe.
                        </p>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="category_id">Categorias</label>
                            <select name="category_id" id="category_id" class="form-control">
                                <option value="">Todos</option>
                                @foreach ($categorys as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-1">
                        <button type="button" class="btn btn-primary mt-4" id="filter">Filtrar</button>
                    </div>
                    <div class="col-md-3">
                        <button type="button" class="btn btn-success mt-4" id="informe">Generar Informe</button>
                        <form id="formfilter" action="{{ route('products.generateInformefilter') }}" method="post" target="_blank">
                            @csrf
                            <input type="hidden" name="category_id" id="categoryfilter">
                        </form>
                    </div>
                </div>
                <div class="table-responsive">
                    <table id="datatable" class="table table-bordered nowrap w-100">
                        <thead>
                            <tr>
                                <th width="60px">Imagen</th>
                                <th>Código</th>
                                <th>Producto</th>
                                <th>Categoría</th>
                                <th>Tipo</th>
                                <th>Stock</th>
                                <th>Costo</th>
                                <th>Precio</th>
                                <th width="100px">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
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

<!-- Datatable init js -->
<script>
    const basepath = "{{ asset('assets/images/') }}";
    const baseStorage = "{{ asset('') }}";
    const numberFormat2 = new Intl.NumberFormat('de-DE');
    console.log($('#category_id').val());
    const table = $('#datatable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ route('productos.datatable') }}",
            data: function (d) {
                d.category_id = $('#category_id').val();
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
                data: 'image',
                name: 'image'
            },
            {
                data: 'code',
                name: 'code'
            },
            {
                data: 'name',
                name: 'name'
            },
            {
                data: 'category',
                name: 'category'
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
                data: 'cost',
                name: 'cost'
            },
            {
                data: 'price',
                name: 'price'
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
                    if (data == null) {
                        return '<img class="img-responsive" src="' + basepath + '/no-image.png" width="30px"/>';
                    }else{
                        return '<img ="img-responsive" src="' + baseStorage + '' + data + '" width="50px"/>';
                    }
                }
        },
        {
            targets: [6],
            render: function (data) {
                return '$ ' + numberFormat2.format(data);
            }
        },
        {
            targets: [7],
            render: function (data) {
                return '$ ' + numberFormat2.format(data);
            }
        }]
    });

    function deleteRecord(id) {
        Swal.fire({
            title: '¿Esta seguro de eliminar este Producto?',
            text: "No podra recuperar la información!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si, eliminar!'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href =
                    "{{ route('productos.destroy', ':id') }}"
                    .replace(':id', id);
            }
        })
    }

    $('#filter').on('click', function() {
        table.draw();
    });

    $('#informe').on('click', function() {
        if ($('#category_id').val() != '' ) {
            console.log($('#category_id').val());
            $('#categoryfilter').val($('#category_id').val());
            $('#formfilter').submit();
        }
    });
</script>
@endSection
