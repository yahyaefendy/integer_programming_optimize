<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product;
use App\Field;
use App\Form;
use App\Data;
use App\ProductData;
use Illuminate\Support\Collection;
use Illuminate\Support\Arr;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CountController extends Controller
{
    public function total() {
        $products = Product::all();
        $totalVariable = $products->count();

        $fields = Field::select('name', 'value_purpose')->get();

        $index = 0;
        $z = collect($fields)->map(function($value, $key){ 
            return -$value['value_purpose'];
        })->toArray();
        $tableBaru = array(
            'Z' => $z
        );

        $value_purpose = collect($fields)->map(function($value, $key) use ($tableBaru){ 
            return array($value['name'] => -$value['value_purpose']);
        })->toArray();

        for($i = 1; $i <= $totalVariable + 2; $i++) {
            array_push($value_purpose, ['s'.$i => 0]);
            array_push($tableBaru['Z'], 0);
        }

        $keyColumn = null;
        $keyIndex = null;
        $valueColumn = 0;
        foreach($value_purpose as $key => $value) {
            if (array_values($value)[0] < $valueColumn) {
                $keyIndex = $key;
                $keyColumn = $value;
                $valueColumn = array_values($value)[0];
            }
        }
        $dataActive = Field::where('name', key($keyColumn))->first();
        
        foreach($products as $key => $product) {
            $data = $product->data->where('field_id', $dataActive->id)->first();
            $rasio = $product->constraint->value_2 / (int) $data->value;
            $product->rasio = $rasio;
            $product->update();

            $listValue = [];
            foreach($product->data as $data) {
                array_push($listValue, (int) $data->value);
            }

            for($i = 1; $i <= $products->count(); $i++) {
                if($i == $product->slack) {
                    array_push($listValue, 1);
                } else {
                    array_push($listValue, 0);
                }
            }
            array_push($listValue, $product->constraint->value_2);
            array_push($listValue, $product->rasio);

            $tableBaru[$product->name] = $listValue;
        }

        $minRasio = Product::min('rasio');
        $barisBaruKunci = Product::where('rasio', $minRasio)->first();
        $nilaiKunci = $barisBaruKunci->data->where('field_id', $dataActive->id)->first();
        
        // dump($barisBaruKunci->slack, $barisBaruKunci->name, $barisBaruKunci->data, $nilaiKunci, $tableBaru, $dataActive);
        
        // dump($keyIndex);
        // dump($tableBaru, $barisBaruKunci->slack);
        
        $this->perhitungan($tableBaru, $barisBaruKunci, $nilaiKunci, $keyIndex);

        // $tableBaruIterasiIndexKunci = 0;
        // $i = 0;
        // do {
        //     if ($tableBaruIterasi['Z'][$i] < $tableBaruIterasiIndexKunci) {
        //         $tableBaruIterasiIndexKunci = $tableBaruIterasi['Z'][$i];
        //     }

        //     $i++;
        // } while ($i < count($tableBaruIterasi['Z']) || $tableBaruIterasiIndexKunci <= 0);
    }

    public function perhitungan($tableBaru, $barisBaruKunci, $nilaiKunci, $keyIndex) {
        // dump($tableBaru);

        $kolomKunci = $this->kolomKunci($tableBaru);
        $barisKunci = $this->barisKunci($tableBaru);
        $nilaiKunci = $this->nilaiKunci($tableBaru, $kolomKunci['kolom_kunci'], $barisKunci['baris_kunci']);
        dump($kolomKunci, $barisKunci, $barisBaruKunci, $nilaiKunci);
        // dump($kolomKunci['kolom_kunci'], $barisBaruKunci->name);
        
        foreach($tableBaru as $keyRow => $valueRow) {
            if ($keyRow == $barisKunci['baris_kunci']) {
                foreach($valueRow as $keyColumn => $valueColumn) {
                    if ($keyColumn != array_key_last($valueRow)) {
                        $tableBaru[$keyRow][$keyColumn] = $valueColumn / (int) $nilaiKunci;
                    }
                }
            }
        }
        dump($tableBaru);

        // dump($tableBaru, $barisBaruKunci, $nilaiKunci, $this->barisKunci($tableBaru), $this->kolomKunci($tableBaru['Z']));

        // $tableBaruIterasi = $tableBaru;
        // foreach($tableBaru as $keyRow => $valueRow) {
        //     if ($keyRow != $barisBaruKunci->name) {
        //         foreach($valueRow as $keyColumn => $valueColumn) {
        //             if ($keyColumn != array_key_last($valueRow)) {
        //                 $nilaiKali = $tableBaru[$keyRow][$keyIndex] * $tableBaru[$barisBaruKunci->name][$keyColumn];
        //                 $tableBaruIterasi[$keyRow][$keyColumn] = $valueColumn - $nilaiKali;
        //             }
        //         }
        //     }
        // }

        // return false;
        // $loopAgain = false;
        // foreach($tableBaru['Z'] as $iterasiBaru) {
        //     if ($iterasiBaru < 0) {
        //         $loopAgain = true;
        //     }
        // }

        // if ($loopAgain) {
        //     $this->perhitungan($tableBaru, $barisBaruKunci, $nilaiKunci, $keyIndex);
        // }
    }

    public function barisKunci($data) {
        $barisKunci = 0;
        $nilaiBarisKunci = 0;

        foreach ($data as $key1 => $value1) {
            $length = count($data[$key1]) - 1;
            
            if ($key1 !== 'Z') {
                if ($nilaiBarisKunci > $value1[$length] && $value1[$length] != 0) {
                    $nilaiBarisKunci= $value1[$length];
                    $barisKunci     = $key1;
                } else if ($nilaiBarisKunci == 0) {
                    $nilaiBarisKunci= $value1[$length];
                    $barisKunci     = $key1;
                }
            }
        }

        return array(
            'baris_kunci'       => $barisKunci,
            'nilai_baris_kunci' => $nilaiBarisKunci
        );
    }

    public function kolomKunci($data) {
        $kolomKunci = 0;
        $nilaiKolomKunci = 0;

        foreach ($data['Z'] as $key => $value) {
            if ($nilaiKolomKunci > $value && $value != 0) {
                $nilaiKolomKunci = $value;
                $kolomKunci = $key;
            }
        }

        return array(
            'kolom_kunci'       => $kolomKunci,
            'nilai_kolom_kunci' => $nilaiKolomKunci
        );
    }

    public function nilaiKunci($data, $x, $y) {
        return $data[$y][$x];
    }

    public function edit() {
        $fields = Field::all();
        
        return view('fields.maksimize', [
            'fields' => $fields
        ]);
    }

    public function updateMaximize(Request $request) {
        foreach($request->values as $value) {
            Field::where('id', $value['id_field'])->update(['value_purpose' => (int) $value['value']]);
        }

        return redirect()->back();
    }
}
