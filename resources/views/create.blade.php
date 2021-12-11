@extends('welcome')

@section('title', 'Tugas Integer Programming')

@section('sidebar')
    @parent
    @if ($errors->any())
        <div class="alert alert-danger shadow">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('controller.store') }}">
        @csrf
        <a href="{{ route('controller.index') }}" class="btn btn-light shadow mb-3">Kembali</a>

        <div class="form-group shadow p-3 rounded">
            <label for="name">Nama Produk</label>
            <input type="text" name="name" class="form-control" id="name" placeholder="Enter nama produk">
        </div>
        <button type="submit" class="btn btn-primary shadow">Simpan produk</button>
    </form>
@endsection

@section('content')
@endsection