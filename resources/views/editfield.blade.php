@extends('welcome')

@section('title', 'Tugas Integer Programming')

@section('sidebar')
    @parent
    @include('list')
@endsection

@section('content')
    <div>
        <form method="POST" action="{{ route('controller.updateItem') }}">
            @csrf
            <h5 class="mt-4 font-weight-bold">Ubah field {{ $field->name }}</h5>

            @if ($errors->any())
                <div class="alert alert-danger shadow">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }} </li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="form-group shadow p-3 rounded bg-warning mt-2">
                <label for="field">Nama Field</label>
                <input type="text" name="name" class="form-control" id="field" placeholder="Enter nama field" value="{{ $field->name }}">
                <input type="hidden" name="id_product" value="{{ $product->id }}">
                <input type="hidden" name="id_field" value="{{ $field->id }}">
            </div>
            <button type="submit" class="btn btn-primary shadow">Simpan field</button>
        </form>
    </div>
@endsection