@extends('welcome')

@section('title', 'Tugas Integer Programming')

@section('sidebar')
    @parent
    @include('list')
@endsection

@section('content')
    <div>
        <form method="POST" 
            @foreach($fields as $key => $field)
                @if(isset($field->constraint))
                    action="{{ route('constraint.updateConstraint') }}"
                @else
                    action="{{ route('constraint.saveConstraint') }}"
                @endif
            @endforeach
        >
            @csrf
            <h5 class="mt-4 font-weight-bold">Tentukan batasan/constraint field</h5>

            @if ($errors->any())
                <div class="alert alert-danger shadow">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @foreach($fields as $key => $field)
            <input type="hidden" name="data[{{ $key }}][id_field]" value="{{ $field->id }}">
            <div class="form-group shadow p-3 rounded bg-warning mt-2 row">
                <div class="col">
                    <label for="value_1">
                        Value 1 <span class="text-danger">*</span>
                    </label>
                    <input type="number" name="data[{{ $key }}][value_1]" class="form-control mb-2" id="value_1" placeholder="Enter value 1 field" value="{{ isset($field->constraint) ? $field->constraint->value_1 : old('value_1') }}">
                </div>
                <div class="col">
                    <label>
                        Operator 1<span class="text-danger">*</span>
                    </label>
                    <select name="data[{{ $key }}][operator_1]" class="custom-select" id="inlineFormCustomSelectPref">
                        <option value="<" 
                            @if(isset($field->constraint))
                                @if($field->constraint->operator_1 == '<' || old('operator') == '<')
                                    selected
                                @endif
                            @endif
                        ><</option>
                        <option value=">"
                            @if(isset($field->constraint))
                                @if($field->constraint->operator_1 == '>' || old('operator') == '>')
                                    selected
                                @endif
                            @endif
                        >></option>
                        <option value="<="
                            @if(isset($field->constraint))
                                @if($field->constraint->operator_1 == '<=' || old('operator') == '<=')
                                    selected
                                @endif
                            @endif
                        ><=</option>
                        <option value=">="
                            @if(isset($field->constraint))
                                @if($field->constraint->operator_1 == '>=' || old('operator') == '>=')
                                    selected
                                @endif
                            @endif
                        >>=</option>
                    </select>
                </div>
                <div class="col text-center">
                    <label class="mt-3 font-weight-bold " for="">{{ $field->name }}</label>
                </div>
                <div class="col">
                    <label>
                        Operator 2<span class="text-danger">*</span>
                    </label>
                    <select name="data[{{ $key }}][operator_2]" class="custom-select" id="inlineFormCustomSelectPref">
                        <option value="<" 
                            @if(isset($field->constraint))
                                @if($field->constraint->operator_2 == '<' || old('operator') == '<')
                                    selected
                                @endif
                            @endif
                        ><</option>
                        <option value=">"
                            @if(isset($field->constraint))
                                @if($field->constraint->operator_2 == '>' || old('operator') == '>')
                                    selected
                                @endif
                            @endif
                        >></option>
                        <option value="<="
                            @if(isset($field->constraint))
                                @if($field->constraint->operator_2 == '<=' || old('operator') == '<=')
                                    selected
                                @endif
                            @endif
                        ><=</option>
                        <option value=">="
                            @if(isset($field->constraint))
                                @if($field->constraint->operator_2 == '>=' || old('operator') == '>=')
                                    selected
                                @endif
                            @endif
                        >>=</option>
                    </select>
                </div>
                <div class="col">
                    <label for="value_2">
                        Value 2 <span class="text-danger">*</span>
                    </label>
                    <input type="number" name="data[{{ $key }}][value_2]" class="form-control" id="value_2" placeholder="Enter value 2 field" value="{{ isset($field->constraint) ? $field->constraint->value_2 : old('value_2') }}">
                </div>
            </div>
            @endforeach
            
            <button type="submit" class="btn btn-primary shadow">Set constraint</button>
        </form>
    </div>
@endsection