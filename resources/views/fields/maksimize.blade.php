@extends('welcome')

@section('title', 'Tugas Integer Programming')

@section('sidebar')
    @parent
    @include('list')
@endsection

@section('content')
    <div>
        <h5 class="mt-4 font-weight-bold">Atur Nilai Maksimum</h5>

        @if($errors->any())
            <div class="alert alert-danger shadow">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('counts.updateMaximize') }}">
            @csrf
            @method('PUT')
            @if(!empty($fields))
                @foreach($fields as $key => $field)
                    <div class="form-group shadow p-3 rounded bg-warning">
                        <label for="{{ $field->name }}">Nama {{ $field->name }}</label>
                        <input type="text" name="values[{{ $key }}][value]" class="form-control" id="field" 
                            placeholder="Enter {{ $field->name }} maximize" 
                            value="{{ $field->value_purpose }}">
                        <input type="hidden" name="values[{{ $key }}][id_field]" value="{{ $field->id }}">
                    </div>
                @endforeach
            @endif
            <button type="submit" class="btn btn-outline-primary shadow">Simpan Nilai Maksimum</button>
        </form>
    </div>
@endsection