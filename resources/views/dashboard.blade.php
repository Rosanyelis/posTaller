@extends('layouts.app')

@section('title') Dashboard @endsection

@section('content')

<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Bienvenido !</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
                    <li class="breadcrumb-item active">Bienvenido !</li>
                </ol>
            </div>

        </div>
    </div>
</div>
<!-- end page title -->
<div class="row">
    <div class="col-12">

        <div class="text-center mb-4">
            <ul class="nav nav-pills card justify-content-center d-inline-block shadow py-1 px-2"
                id="pills-tab" role="tablist">

                <li class="nav-item d-inline-block">
                    <a class="nav-link px-3 rounded monthly" id="Monthly"
                        href="javascript: void(0);" aria-controls="Month"
                        aria-selected="true">
                        POS
                    </a>
                </li>
                <li class="nav-item d-inline-block">
                    <a class="nav-link px-3 rounded yearly" id="Yearly"
                        href="javascript: void(0);" aria-controls="Year"
                        aria-selected="false">
                        Productos
                    </a>
                </li>

                <li class="nav-item d-inline-block">
                    <a class="nav-link px-3 rounded monthly"
                        href="javascript: void(0);" aria-controls="Month"
                        aria-selected="true">
                        Ventas
                    </a>
                </li>
                <li class="nav-item d-inline-block">
                    <a class="nav-link px-3 rounded monthly"
                        href="javascript: void(0);" aria-controls="Month"
                        aria-selected="true">Categorías</a>
                </li>

                <li class="nav-item d-inline-block">
                    <a class="nav-link px-3 rounded monthly"
                        href="javascript: void(0);" aria-controls="Month"
                        aria-selected="true">
                        Clientes
                    </a>
                </li>

                <li class="nav-item d-inline-block">
                    <a class="nav-link px-3 rounded monthly"
                        href="javascript: void(0);" aria-controls="Month"
                        aria-selected="true">
                        Configuraciones
                    </a>
                </li>
                <li class="nav-item d-inline-block">
                    <a class="nav-link px-3 rounded monthly"
                        href="javascript: void(0);" aria-controls="Month"
                        aria-selected="true">
                        Reportes
                    </a>
                </li>
                <li class="nav-item d-inline-block">
                    <a class="nav-link px-3 rounded monthly"
                        href="javascript: void(0);" aria-controls="Month"
                        aria-selected="true">
                        Usuarios
                    </a>
                </li>

                <li class="nav-item d-inline-block">
                    <a class="nav-link px-3 rounded monthly"
                        href="javascript: void(0);" aria-controls="Month"
                        aria-selected="true">
                        Respaldos
                    </a>
                </li>

            </ul>
        </div>
    </div>
</div>


@endsection
@section('scripts')
<script>
    $(document).ready(function() {
        const Toast = Swal.mixin({
            toast: true,
            position: "top-end",
            showConfirmButton: false,
            timer: 5000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.onmouseenter = Swal.stopTimer;
                toast.onmouseleave = Swal.resumeTimer;
            }
        });
        @if (count($productsQty) > 0)
        Toast.fire({
            icon: "error",
            title: "Tienes Productos sin Stock",
            text: "Por favor revisa las notificaciones y reponga el inventario"
        });
        @endif

    });
</script>
@endSection
