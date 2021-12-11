@extends('welcome')

@section('title', 'Tugas Integer Programming')

@section('sidebar')
    @parent
    @include('list')
@endsection

@section('content')
    <div>
        <h5 class="mt-4 font-weight-bold">Ubah Field</h5>

        @if ($errors->any())
            <div class="alert alert-danger shadow">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('fields.update', $field->id) }}">
            @csrf
            @method('PUT')
            <div class="form-group shadow p-3 rounded bg-warning mt-2">
                <label for="field">Nama Field</label>
                <input type="text" name="name" class="form-control" id="field" placeholder="Enter nama field" value="{{ $field->name }}">
            </div>
            <button type="submit" class="btn btn-primary shadow">Ubah field</button>
        </form>
    </div>
@endsection