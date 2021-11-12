@extends('welcome')

@section('title', 'Tugas Integer Programming')

@section('sidebar')
    @parent
    @include('list')
@endsection

@section('content')
    <div>
        <h5 class="mt-4 font-weight-bold">Tambah Field</h5>

        @if ($errors->any())
            <div class="alert alert-danger shadow">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <ul class="list-group shadow">
            <p class="m-3 font-weight-bold">Semua Field</p>
            @foreach($fields as $field)
                <li class="list-group-item border-0 ">
                    {{ $field->name }}
                    <a href=""></a>
                </li>
            @endforeach
        </ul>

        <form method="POST" action="{{ route('controller.saveItem', $product->id) }}">
            @csrf
            <div class="form-group shadow p-3 rounded bg-warning mt-2">
                <label for="field">Nama Field</label>
                <input type="text" name="name" class="form-control" id="field" placeholder="Enter nama field">
                <input type="hidden" name="id_product" value="{{ $product->id }}">
            </div>
            <button type="submit" class="btn btn-primary shadow">Simpan field</button>
        </form>
    </div>
@endsection