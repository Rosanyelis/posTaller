<div class="btn-group dropstart btn-group-sm">
    <button type="button" class="btn btn-info waves-effect waves-light dropdown-toggle show" data-bs-toggle="dropdown" aria-expanded="true">
        <i class="mdi mdi-chevron-left"></i> Acción
    </button>
    <div class="dropdown-menu " data-popper-placement="left-start" style="position: absolute; inset: 0px 0px auto auto; margin: 0px; transform: translate3d(-96.2264px, 0px, 0px);">

        <a class="dropdown-item text-info" href="#"  onclick="viewRecord({{ $data->id }})">
            <i class="mdi mdi-eye"></i>
            Ver Cotizacion
        </a>
        @if ($data->status != 'Facturado' && $data->status != 'Pagado')
        <a class="dropdown-item text-primary" href="{{ route('cotizaciones.edit', $data->id) }}">
            <i class="mdi mdi-square-edit-outline"></i>
            Editar Cotizacion
        </a>
        <a class="dropdown-item text-danger" href="#" onclick="deleteRecord({{ $data->id }})" >
            <i class="mdi mdi-delete"></i>
            Eliminar Cotizacion
        </a>
        @endif
        <a class="dropdown-item text-warning " href="{{ route('cotizaciones.quotepdf', $data->id) }}"
            target="_blank">
            <i class="mdi mdi-file-pdf"></i> Cotizacion PDF
        </a>
        <a class="dropdown-item text-success" href="{{ route('cotizaciones.sendEmailQuotepdf', $data->id) }}">
            <i class="mdi mdi-email"></i>
            Enviar Cotizacion
        </a>
    </div>
</div>



