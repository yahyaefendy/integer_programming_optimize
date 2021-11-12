<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Constraint;
use App\Field;
use App\Product;
use Illuminate\Support\Facades\Validator;

class ConstraintController extends Controller
{
    public function constraint() {
        $fields = Field::all();

        return view('constraint', [
            'fields' => $fields
        ]);
    }

    public function saveConstraint(Request $request) {
        foreach($request->data as $data) {
            $validator = Validator::make($data, [
                'id_field'      => 'required|integer',
                'value_1'       => 'required|integer',
                'value_2'       => 'required|integer',
                'operator_1'    => 'required|string',
                'operator_2'    => 'required|string',
            ]);

            if ($validator->fails()) {
                return redirect()
                    ->back()
                    ->withErrors($validator)
                    ->withInput();
            }
        }

        foreach($request->data as $data) {
            $constraint = new Constraint;
            $constraint->id_field   = $data['id_field'];
            $constraint->operator_1 = $data['operator_1'];
            $constraint->operator_2 = $data['operator_2'];
            $constraint->value_1    = $data['value_1'];
            $constraint->value_2    = $data['value_2'];
            $constraint->save();
        }

        return redirect()->route('controller.index');
    }

    public function updateConstraint(Request $request) {
        foreach($request->data as $data) {
            $constraint = Constraint::where('id_field', $data['id_field'])->first();
            $constraint->operator_1 = $data['operator_1'];
            $constraint->operator_2 = $data['operator_2'];
            $constraint->value_1    = $data['value_1'];
            $constraint->value_2    = $data['value_2'];
            $constraint->update();
        }

        return redirect()->route('controller.index');
    }
}
