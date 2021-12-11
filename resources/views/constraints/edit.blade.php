@extends('welcome')

@section('title', 'Tugas Integer Programming')

@section('sidebar')
    @parent
    @include('list')
@endsection

@section('content')
    <div>
        <form method="POST" action="{{ route('constraint.update', $constraint->id_field) }}">
            @csrf
            @method('PUT')
            <h5 class="mt-4 font-weight-bold">Tentukan batasan/constraint</h5>

            @if ($errors->any())
                <div class="alert alert-danger shadow">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <input type="hidden" name="id_field" value="{{ $constraint->id_field }}">
            <div class="form-group shadow p-3 rounded bg-warning mt-2 row">
                <div class="col text-center">
                    <label class="mt-3 font-weight-bold " for="">{{ $constraint->name }}</label>
                </div>
                <div class="col">
                    <label>
                        Operator<span class="text-danger">*</span>
                    </label>
                    <select name="operator_2" class="custom-select" id="inlineFormCustomSelectPref">
                        <option value="<="
                            @if(isset($constraint))
                                @if($constraint->operator_2 == '<=' || old('operator') == '<=')
                                    selected
                                @endif
                            @endif
                        ><=</option>
                        <option value=">="
                            @if(isset($constraint))
                                @if($constraint->operator_2 == '>=' || old('operator') == '>=')
                                    selected
                                @endif
                            @endif
                        >>=</option>
                    </select>
                </div>
                <div class="col">
                    <label for="value_2">
                        Value <span class="text-danger">*</span>
                    </label>
                    <input type="number" name="value_2" class="form-control" id="value_2" placeholder="Enter value 2 field" value="{{ isset($constraint) ? $constraint->value_2 : old('value_2') }}">
                </div>
            </div>
            
            <button type="submit" class="btn btn-primary shadow">Set constraint</button>
        </form>
    </div>
@endsection