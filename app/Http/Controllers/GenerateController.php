<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product;
use App\Form;

class GenerateController extends Controller
{
    public function generateForm() {
        $form = new Form;
        $form->id_product = $id;
        $form->save();

        return redirect()->route('controller.addItem', $id);
    }

    public function generateCancel($id) {
        $form = Form::where('id_product', $id)->delete();

        return redirect()->route('controller.addItem', $id);
    }
}
