@extends('layouts.app')

@section('title') Productos @endsection

@section('css')

@endsection

@section('content')

<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Productos </h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('productos.index') }}">Productos</a>
                    </li>
                    <li class="breadcrumb-item active">Editar Producto</li>
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
                <h4 class="card-title">Editar Producto</h4>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('productos.update', $product->id) }}" method="POST"
                    enctype="multipart/form-data" class="needs-validation @if ($errors->any()) was-validated @endif"
                    novalidate>
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-6">
                            <div class="mb-3">
                                <label for="code" class="form-label">Código de Producto</label>
                                <input class="form-control" type="text" name="code" id="code" required
                                    value="{{ $product->code }}" >
                                @if($errors->has('code'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('code') }}
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-4 col-sm-6">
                            <div class="mb-3">
                                <label for="name" class="form-label">Nombre de Producto</label>
                                <input class="form-control" type="text" name="name" id="name" required
                                    value="{{ $product->name }}">
                                @if($errors->has('name'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('name') }}
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-4 col-sm-6">
                            <div class="mb-3">
                                <label for="image" class="form-label">Imagen</label>
                                <input class="form-control" type="file" name="image" id="image">
                                @if($errors->has('image'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('image') }}
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-4 col-sm-6">
                            <div class="mb-3">
                                <label class="form-label">Simbología de Código de Barras</label>
                                <select class="form-control" name="barcode_symbology" required>
                                    <option value="">-- Seleccionar --</option>
                                    <option value="code25" {{ $product->barcode_symbology == 'code25' ? 'selected' : '' }}>Code25</option>
                                    <option value="code39" {{ $product->barcode_symbology == 'code39' ? 'selected' : '' }}>Code39</option>
                                    <option value="code128" {{ $product->barcode_symbology == 'code128' ? 'selected' : '' }}>Code128</option>
                                    <option value="ean8" {{ $product->barcode_symbology == 'ean8' ? 'selected' : '' }}>EAN8</option>
                                    <option value="ean13" {{ $product->barcode_symbology == 'ean13' ? 'selected' : '' }}>EAN13</option>
                                    <option value="upca" {{ $product->barcode_symbology == 'upca' ? 'selected' : '' }}>UPC-A</option>
                                    <option value="upce" {{ $product->barcode_symbology == 'upce' ? 'selected' : '' }}>UPC-E</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-4 col-sm-6">
                            <div class="mb-3">
                                <label class="form-label">Categorías</label>
                                <select class="form-control" name="category_id">
                                    <option value="">-- Seleccionar --</option>
                                    @foreach($category as $item)
                                        <option value="{{ $item->id }}" {{ $product->category_id == $item->id ? 'selected' : '' }} >{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-4 col-sm-6">
                            <div class="mb-3">
                                <label class="form-label">Tipo de Producto</label>
                                <select class="form-control" name="type" required>
                                    <option value="">-- Seleccionar --</option>
                                    @foreach($typeproduct as $item)
                                        <option value="{{ $item->name }}" {{ $product->type == $item->name ? 'selected' : '' }}>{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-4 col-sm-6">
                            <div class="mb-3">
                                <label for="cost" class="form-label">Costo de Producto</label>
                                <input class="form-control" type="number" name="cost" id="cost" required
                                    value="{{ number_format($product->cost, 0, '.', '') }}" >
                                @if($errors->has('cost'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('cost') }}
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-4 col-sm-6">
                            <div class="mb-3">
                                <label for="price" class="form-label">Precio de Producto</label>
                                <input class="form-control" type="number" name="price" id="price" required
                                    value="{{ number_format($product->price, 0, '.', '') }}">
                                @if($errors->has('price'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('price') }}
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-4 col-sm-6">
                            <div class="mb-3">
                                <label for="quantity" class="form-label">Cantidad de Producto</label>
                                <input class="form-control" type="number" name="quantity" id="quantity" required
                                    value="{{ $product->storeqty->quantity }}">
                                @if($errors->has('quantity'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('quantity') }}
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-4 col-sm-6">
                            <div class="mb-3">
                                <label for="alert_quantity" class="form-label">Cantidad Mínima de Producto</label>
                                <input class="form-control" type="number" name="alert_quantity" id="alert_quantity" required
                                    value="{{ $product->alert_quantity }}">
                                @if($errors->has('alert_quantity'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('alert_quantity') }}
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-4 col-sm-6">
                            <div class="mb-3">
                                <label for="max_quantity" class="form-label">Cantidad Máxima de Producto</label>
                                <input class="form-control" type="number" name="max_quantity" id="max_quantity" required
                                    value="{{ $product->max_quantity }}">
                                @if($errors->has('max_quantity'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('max_quantity') }}
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-4 col-sm-6">
                            <div class="mb-3">
                                <label for="description" class="form-label">Detalles</label>
                                <input class="form-control" type="text" name="description" id="description" required
                                    value="{{ $product->description }}">
                                @if($errors->has('description'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('description') }}
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="w-100"></div>

                        <hr>
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <div class="mb-3">
                                <h4>Ubicación en Bodega (Opcional)</h4>
                            </div>
                        </div>

                        <hr>
                        <div class="col-lg-3 col-md-3 col-sm-6">
                            <div class="mb-3">
                                <label for="cellar" class="form-label">Bodega</label>
                                <input type="text" class="form-control" name="cellar" id="cellar" value="{{ $product->cellar }}">
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-3 col-sm-6">
                            <div class="mb-3">
                                <label for="hail" class="form-label">Pasillo</label>
                                <input type="text" class="form-control" name="hail" id="hail" value="{{ $product->hail }}">
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-3 col-sm-6">
                            <div class="mb-3">
                                <label for="rack" class="form-label">Estante o rack</label>
                                <input type="text" class="form-control" name="rack" id="rack" value="{{ $product->rack }}">
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-3 col-sm-6">
                            <div class="mb-3">
                                <label for="position" class="form-label">Posicion</label>
                                <input type="text" class="form-control" name="position" id="position" value="{{ $product->position }}">
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary w-md float-end">Actualizar Producto</button>
                </form>
            </div>
        </div>
    </div>
</div>

@endSection

@section('scripts')
<script>

</script>
@endSection
