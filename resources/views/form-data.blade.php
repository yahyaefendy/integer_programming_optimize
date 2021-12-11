@extends('welcome')

@section('title', 'Tugas Integer Programming')

@section('sidebar')
    @parent
    @include('list')
@endsection

@section('content')
    <div>
        <h5 class="mt-4 font-weight-bold">Tambah Nilai Field</h5>

        @if($errors->any())
            <div class="alert alert-danger shadow">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('controller.saveData') }}">
            @csrf
            @if(!empty($fields))
                <input type="hidden" name="id_product" value="{{ $id }}">
                @foreach($fields as $field)
                    <div class="form-group shadow p-3 rounded bg-warning mt-2">
                        <label for="{{ $field->name }}">Nama {{ $field->name }}</label>
                        <input type="text" name="{{ $field->name }}[value]" class="form-control" id="field" placeholder="Enter {{ $field->name }} field">
                        <input type="hidden" name="{{ $field->name }}[id_field]" value="{{ $field->id }}">
                    </div>
                @endforeach
            @endif
            <button type="submit" class="btn btn-primary shadow">Simpan data field</button>
        </form>
    </div>
@endsection