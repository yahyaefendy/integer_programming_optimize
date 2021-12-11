<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests; 
use Illuminate\Routing\Controller as BaseController;
use App\Product;
use App\Field;
use App\Form;
use App\Data;
use App\ProductData;
use Illuminate\Http\Request;
use View;
use Illuminate\Support\Arr;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function __construct() {
        $products = Product::all();

        View::share('products', $products);
    }

    public function index() {
        $products = Product::all();

        // $values = array();
        // $pembagi = 1;
        // $telah_dibagi = false;
        // foreach($products as $i => $product) {
        //     foreach($product->data as $key => $data) {
        //         $constraint = (int) $data->constraint->value_2;

        //         $value = $data->value;
        //         if ((int) $data->value != 1) {
        //             if ($telah_dibagi == false) {
        //                 $telah_dibagi = true;
        //                 $pembagi = (int) $data->value;
        //             }
        //             $value = $data->value / $pembagi;
        //             $constraint = (int) $data->constraint->value_2 / $pembagi;
        //         }

        //         if (!empty($data->constraint->value_2)) {
        //             $values[$i][$key]['id']         = $data->field_id;
        //             $values[$i][$key]['value']      = (int) $value;
        //             $values[$i][$key]['constraint'] = (int) $constraint;
        //         }
        //     }
        // }

        // $fixValue = array();

        // foreach($values as $key => $value) {
        //     $constraint = 0;
        //     foreach($value as $i => $data) {
        //         $fixValue[0][0]['constraint'] = 0;
                
        //         if (isset($fixValue[0][0]['constraint']) && $data['constraint'] !== 0) {
        //             if ($constraint !== 0 && $constraint > $data['constraint']) {
        //                 $constraint = $constraint - $data['constraint'];
        //             } else {
        //                 $constraint = $data['constraint'] - $constraint;
        //             }
        //         }

        //         $fixValue[0][0]['id']          = $data['id'];
        //         $fixValue[0][0]['value']       = isset($fixValue[0][0]['value']) ? abs($fixValue[0][0]['value'] - $data['value']) : $data['value'];
        //         $fixValue[0][0]['constraint']  = $constraint;
        //     }
        // }
        
        // // $y = isset($fixValue[0][0]) ? $fixValue[0][0]['constraint'] / $fixValue[0][0]['value'] : 0;
        // $y = 0;

        // foreach($values as $key => $value_array) {
        //     $array = Arr::first($value_array);
        //     $x = $array['value'] * $y;
        //     $x = $array['constraint'] - $x;
        // }

        // $number = 0;
        // $x = 0;
        // foreach($products as $i => $product) {
        //     foreach($product->data as $key => $data) {
        //         // $constraint = (int) $data->constraint->value_2;
        //         if (empty($data->constraint->value_1) && empty($data->constraint->value_2)) {
        //             $number++;
        //             if ($number == 2) {
        //                 $y = $y * (int) $data->value;
        //             } else if ($number == 1) {
        //                 $x = $x * (int) $data->value;
        //             }
        //         }
        //     }
        // }

        // $total = $y + $x;

        return view('home', [ 
            'products' => $products,
            // 'total' => $total,
            // 'x' => $x,
            // 'y' => $y,
        ]);
    }

    public function create() {
        return view('create');
    }

    public function store(Request $request) {
        $validated = $request->validate([
            'name' => 'required|unique:products|max:255',
        ]);

        $slack = 1;
        if ($latest = Product::latest()->first()) {
            $slack = $latest->slack + 1;
        }

        $product = new Product;
        $product->name = $request->name;
        $product->slack = $slack;
        $product->save();

        return redirect()->route('controller.index');
    }

    public function addItem() {
        $product = Product::first();
        $fields = Field::all();
        $form = Form::all();
        $allData = Data::all();

        return view('add', [
            'product'   => $product,
            'fields'    => $fields,
            'form'      => $form,
            'allData'   => $allData
        ]);
    }

    public function saveItem(Request $request) {
        $validated = $request->validate([
            'name' => 'required|max:255',
        ]);

        $field = new Field;
        $field->name = $request->name;
        $field->save();

        return redirect()->route('controller.addItem', $request->id_product);
    }

    public function editItem($id, $id_product) {
        $field = Field::find($id);
        $product = Product::find($id_product);

        return view('editfield', [
            'field'     => $field,
            'product'   => $product,
            'id'        => $id_product
        ]);
    }

    public function updateItem(Request $request) {
        $validated = $request->validate([
            'name' => 'required|max:255',
        ]);

        $field = Field::find($request->id_field);
        $field->name = $request->name;
        $field->update();

        return redirect()->route('controller.addItem', $request->id_product);
    }

    public function deleteItem($id, $id_product) {
        $field = Field::find($id);
        $field->delete();

        return redirect()->route('controller.addItem', $id_product);
    }

    public function saveData(Request $request) {
        $fields = Field::select('name')->get();
        $fieldNames = Arr::pluck($fields->toArray(), 'name');
        $fieldValues = Arr::only($request->all(), $fieldNames);
        
        $productData = new ProductData;
        $productData->id_product = $request->id_product;
        $productData->save();
        
        foreach($fieldValues as $value) {
            $data = new Data;
            $data->product_id       = $request->id_product;
            $data->product_data_id  = $productData->id;
            $data->field_id         = $value['id_field'];
            $data->value            = $value['value'];
            $data->save();
        }

        return redirect()->route('controller.index');
    }

    public function formData($id) {
        $fields = Field::all();
        
        return view('form-data', [
            'fields' => $fields,
            'id' => $id
        ]);
    }

    public function editData($id) {
        $fields = Field::addSelect([
            'data_id' => Data::select('id')->whereColumn('field_id', 'fields.id')->whereIn('product_id', [$id]),
            'value' => Data::select('value')->whereColumn('field_id', 'fields.id')->whereIn('product_id', [$id])
        ])->get();
        
        return view('form-edit', [
            'fields' => $fields,
            'id'    => $id
        ]);
    }

    public function updateData(Request $request, $id) {
        $fields = Field::select('name')->get();
        $fieldNames = Arr::pluck($fields->toArray(), 'name');
        $fieldValues = Arr::only($request->all(), $fieldNames);

        foreach($fieldValues as $value) {
            if (Data::where('id', $value['id_data'])->exists()) {
                $data           = Data::find($value['id_data']);
                $data->value    = $value['value'];
                $data->update();
            } else {
                $data                   = new Data;
                $data->product_id       = $request->id_product;
                $data->product_data_id  = $request->id_product;
                $data->field_id         = $value['id_field'];
                $data->value            = $value['value'];
                $data->save();
            }
        }

        return redirect()->route('controller.index');
    }

    public function deleteProduct($id) {
        $product = Product::find($id);
        $product->delete();
        $product->constraint()->delete();
        $product->data()->delete();

        return redirect()->route('controller.index', $id);
    }
}
