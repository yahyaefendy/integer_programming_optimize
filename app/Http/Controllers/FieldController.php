<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Field;
use Illuminate\Foundation\Validation\ValidatesRequests;

class FieldController extends Controller
{
    public function edit($id) {
        $field = Field::find($id);

        return view('fields.edit', [
            'field' => $field
        ]);
    }

    public function update($id, Request $request) {
        $validated = $request->validate([
            'name' => 'required|max:255',
        ]);

        $field          = Field::find($id);
        $field->name    = $request->name;
        $field->update();

        return redirect()->route('controller.index');
    }
}
