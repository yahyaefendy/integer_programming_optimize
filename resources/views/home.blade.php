@extends('welcome')

@section('title', 'Tugas Integer Programming')

@section('sidebar')
    <div class="alert alert-success" role="alert">
        <h4 class="alert-heading">Perhatian!</h4>
        <p>Untuk alur tambahkan (Produk) > (Field) > (Batasan)</p>
        <hr>
        <p class="mb-0">Semoga berjalan dengan baik.</p>
    </div>
    @parent
    @include('list')
@endsection

@section('content')
@endsection