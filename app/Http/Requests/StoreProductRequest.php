<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'code' => 'required',
            'name' => 'required',
            'category_id' => 'required',
            'type' => 'required',
            'barcode_symbology' => 'required',
            'quantity' => 'required',
            'price' => 'required',
            'cost' => 'required',
            'image' => 'nullable|image|max:2048'
        ];
    }

    public function messages(): array
    {
        return [
            'code.required' => 'El campo Codigo de producto es obligatorio',
            'name.required' => 'El campo Nombre del Producto es obligatorio',
            'category_id.required' => 'El campo Categoría del Producto es obligatorio',
            'type.required' => 'El campo Tipo de Producto es obligatorio',
            'barcode_symbology.required' => 'El campo Simbología de Código de Barras es obligatorio',
            'quantity.required' => 'El campo Cantidad es obligatorio',
            'price.required' => 'El campo Precio es obligatorio',
            'cost.required' => 'El campo Costo es obligatorio',
            'image.required' => 'El campo Imagen es obligatorio',
            'image.image' => 'El campo Imagen debe ser una imagen',
            'image.max' => 'El campo Imagen no debe ser mayor a 2 MB',
        ];
    }
}
