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
        $products = Product::all();

        return view('constraint', [
            'fields'    => $fields,
            'products'  => $products
        ]);
    }

    public function saveConstraint(Request $request) {
        foreach($request->data as $data) {
            $validator = Validator::make($data, [
                'id_field'      => 'required|integer',
                'value_2'       => 'required|integer',
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
            $constraint->operator_2 = $data['operator_2'];
            $constraint->value_2    = $data['value_2'];
            $constraint->save();
        }

        return redirect()->route('controller.index');
    }

    public function updateConstraint(Request $request) {
        foreach($request->data as $data) {
            if (Constraint::where('id_field', $data['id_field'])->exists()) {
                $constraint = Constraint::where('id_field', $data['id_field'])->first();
                $constraint->operator_2 = $data['operator_2'];
                $constraint->value_2    = $data['value_2'];
                $constraint->update();
            } else {
                $constraint = new Constraint;
                $constraint->id_field   = $data['id_field'];
                $constraint->operator_2 = $data['operator_2'];
                $constraint->value_2    = $data['value_2'];
                $constraint->save();
            }
        }

        return redirect()->route('controller.index');
    }

    public function edit($id) {
        $constraint = Constraint::where('id_field', $id)->first();
        
        return view('constraints.edit', [
            'constraint'    => $constraint,
            'id'            => $id
        ]);
    }

    public function update($id, Request $request) {
        $validator = Validator::make($request->all(), [
            'value_2'       => 'required|integer',
            'operator_2'    => 'required|string',
        ]);

        $constraint = Constraint::where('id_field', $id)->update([
            'value_2'       => $request->value_2,
            'operator_2'    => $request->operator_2
        ]);
        
        return redirect()->route('controller.index');
    }
}
