
<a href="{{ route('controller.create') }}" class="btn btn-outline-primary mb-2 shadow">
    Tambah Produk
</a>
<a href="{{ route('controller.addItem') }}" class="btn btn-outline-primary mb-2 shadow">
    Tambah Field
</a>
<a href="{{ route('constraint.constraint') }}" class="btn btn-outline-primary mb-2 shadow">
    Tambah Batasan
</a>
@foreach($products as $product)
    <div class="card border-0 shadow mt-2 @if(isset($id) && $id == $product->id) bg-warning @endif" >
        <div class="card-body">
            <h5 class="card-title align-text-bottom mb-0 font-weight-bold">
                <a href="@if($product->productData->count() > 0)
                            {{ route('controller.editData', $product->id) }}
                        @else
                            {{ route('controller.formData', $product->id) }}
                        @endif" 
                        class="font-weight-bold mb-0">
                    {{ $product->name }}
                </a>
            </h5>
            <p class="card-text">
                @foreach($product->productData as $productData)
                    @foreach($productData->data as $data)
                        {{ $data->field->name }} : {{ $data->value }},
                    @endforeach
                @endforeach
            </p>
        </div>
    </div>
@endforeach


<div class="card border-0 shadow mt-2 @if(isset($id) && $id == $product->id) bg-warning @endif" >
    <div class="card-body">
        <span text="text-danger">Nilai x adalah = {{ $x ?? '' }}</span></br>
        <span text="text-danger">Nilai y adalah = {{ $y ?? '' }}</span></br>
        <span text="text-danger">Hasil dari optimasi maksimum adalah = {{ $total ?? '' }}</span></br>
    </div>
</div>