@extends('layouts.app')

@section('title') Tareas @endsection

@section('css')
    <!-- DataTables -->
    <link href="{{ asset('assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- Responsive datatable examples -->
    <link href="{{ asset('assets/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
    <style>
        .tachado {
            text-decoration: line-through;
            color: gray;
            font-style: italic;
            font-weight: 400;
            padding-top: 0px;
            padding-bottom: 0px;
            margin-top: 0px;
            margin-bottom: 0px;
        }
    </style>
@endsection

@section('content')

<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Tareas </h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item active">Tareas</li>
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
                    <div class="col-md-10">
                        <input type="text" class="form-control form-control-sm" id="task" name="task"
                               placeholder="Agregar Tarea">
                    </div>
                    <div class="col-md-2">
                        <button type="button" class="btn btn-primary btn-sm " onclick="addTask()">
                            Añadir Tarea</button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="datatable" class="table table-bordered dt-responsive nowrap w-100">
                        <thead>
                        <tr>
                            <th style="width: 10px"></th>
                            <th>Tarea</th>
                            <th style="width: 10px"></th>
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
    <script src="{{ asset('assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <!-- Buttons examples -->
    <script src="{{ asset('assets/libs/datatables.net-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('assets/libs/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/libs/jszip/jszip.min.js') }}"></script>
    <script src="{{ asset('assets/libs/pdfmake/build/pdfmake.min.js') }}"></script>
    <script src="{{ asset('assets/libs/pdfmake/build/vfs_fonts.js') }}"></script>
    <script src="{{ asset('assets/libs/datatables.net-buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('assets/libs/datatables.net-buttons/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('assets/libs/datatables.net-buttons/js/buttons.colVis.min.js') }}"></script>

    <!-- Responsive examples -->
    <script src="{{ asset('assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('assets/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js') }}"></script>

    <!-- Datatable init js -->
    <script>
        const basepath = "{{ asset('assets/images/') }}";
        const baseStorage = "{{ asset('') }}";
        $('#datatable').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('tareas.index') }}",
            dataType: 'json',
            type: "POST",
            lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
            responsive: true,
            language: {
                url: "https://cdn.datatables.net/plug-ins/2.0.8/i18n/es-ES.json",
            },
            columns: [
                {data: 'id', name: 'id'},
                {data: 'task', name: 'task',},
                {data: 'actions', name: 'actions', orderable: false, searchable: false},
            ],
            columnDefs: [
                {targets: 0,
                    render: function (data, type, row, meta) {
                        return `<div class="form-check font-size-16">
                                <input class="form-check-input" type="checkbox" id="check${data}"
                                onclick="changeStatus(${data},${row.status})" value="${data}"
                                ${row.status == 1 ? 'checked' : ''}>
                                <label class="form-check-label" for="check${data}"></label>
                            </div>`
                    }

                },
                {targets: 1,
                    render: function (data, type, row, meta) {
                        if(row.status == 1){
                            return `<p class=" tachado">${data}</p>`
                        }
                        return data;
                    }
                }
            ]
        });
        function addTask() {
            const task = $('#task').val();
            $.ajax({
                url: "{{ route('tareas.store') }}",
                type: "POST",
                data: {
                    task: task,
                    _token: "{{ csrf_token() }}"
                },
                dataType: 'json',
                success: function (data) {
                    if (data.status == 'success') {
                        $('#datatable').DataTable().ajax.reload();
                        Swal.fire({
                            position: 'top-center',
                            icon: 'success',
                            title: 'Tarea agregada correctamente',
                        })
                        $('#task').val('');
                    }

                    if (data.status == 'error') {
                        Swal.fire({
                            position: 'top-center',
                            icon: 'error',
                            title: 'Error al agregar la tarea',
                        });
                    }
                }
            });
        }

        function changeStatus(id, status) {
            $.ajax({
                url: "{{ route('tareas.changeStatus') }}",
                type: "POST",
                data: {
                    id: id,
                    status: status,
                    _token: "{{ csrf_token() }}"
                },
                dataType: 'json',
                success: function (data) {
                    if (data.status == 'success') {
                        $('#datatable').DataTable().ajax.reload();
                    }
                }
            });

        }
        function deleteRecord(id) {
            Swal.fire({
                title: '¿Esta seguro de eliminar esta Tarea?',
                text: "No podra recuperar la información!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Si, eliminar!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('tareas.destroy') }}",
                        type: "POST",
                        data: {
                            id: id,
                            _token: "{{ csrf_token() }}"
                        },
                        dataType: 'json',
                        success: function (data) {
                            if (data.status == 'success') {
                                $('#datatable').DataTable().ajax.reload();
                                Swal.fire({
                                    position: 'top-center',
                                    icon: 'success',
                                    title: 'Tarea eliminada correctamente',
                                })
                            }
                        }
                    })
                }
            })
        }
    </script>
@endSection
