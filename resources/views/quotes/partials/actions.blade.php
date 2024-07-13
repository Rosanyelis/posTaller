<a href="{{ route('cotizaciones.quotepdf', $id) }}" class="btn btn-warning btn-sm"
    title="Cotizacion PDF" target="_blank">
    <i class="mdi mdi-file-pdf"></i>
</a>

<button type="button" class="btn btn-info btn-sm" onclick="viewRecord({{ $id }})">
    <i class="mdi mdi-eye"></i>
</button>

<a href="{{ route('cotizaciones.edit', $id) }}" class="btn btn-primary btn-sm">
    <i class="mdi mdi-square-edit-outline"></i>
</a>

<button type="button" class="btn btn-danger btn-sm" onclick="deleteRecord({{ $id }})" >
    <i class="mdi mdi-delete"></i>
</button>
