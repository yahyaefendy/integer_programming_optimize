
<a href="{{ route('controller.create') }}" class="btn btn-outline-primary mb-2 shadow">
    Tambah Produk
</a>
<a href="{{ route('controller.addItem') }}" class="btn btn-outline-primary mb-2 shadow">
    Tambah Field
</a>
<a href="{{ route('constraint.constraint') }}" class="btn btn-outline-primary mb-2 shadow">
    Tambah Batasan
</a>
<a href="{{ route('counts.edit') }}" class="btn btn-outline-primary mb-2 shadow">
    Maksimumkan
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
                <a href="{{ route('controller.deleteProduct', $product->id) }}" class="btn btn-outline-danger mb-2 shadow-sm float-right">
                    Hapus Produk
                </a>
                <a href="{{ route('constraint.edit', $product->id) }}" class="btn btn-outline-info mb-2 mr-2 shadow-sm float-right">
                    Atur Batasan
                </a>
            </h5>
            <p class="card-text">
                @foreach($product->productData as $productData)
                    @foreach($productData->data as $data)
                        {{ $data->field->name }} = {{ $data->value }},
                    @endforeach
                @endforeach
            </p>
        </div>
    </div>
@endforeach

<a href="{{ route('counts.total') }}" class="btn btn-block btn-outline-primary mt-3 mb-2 shadow float-right">
    Hitung
</a>