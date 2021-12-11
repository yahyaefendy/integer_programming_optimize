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
        $this->perhitungan($tableBaru);
    }

    public function perhitungan($tableBaru, $tahap = 1) {
        $kolomKunci = $this->kolomKunci($tableBaru);
        $barisKunci = $this->barisKunci($tableBaru);
        $nilaiKunci = $this->nilaiKunci($tableBaru, $kolomKunci['kolom_kunci'], $barisKunci['baris_kunci']);
        
        foreach($tableBaru as $keyRow => $valueRow) {
            if ($keyRow == $barisKunci['baris_kunci']) {
                foreach($valueRow as $keyColumn => $valueColumn) {
                    if ($keyColumn != array_key_last($valueRow)) {
                        $tableBaru[$keyRow][$keyColumn] = $valueColumn / $nilaiKunci;
                    }
                }
            }
            
            if ($keyRow !== 'Z') {
                $totalIndex = count($valueRow);
                $tableBaru[$keyRow][$totalIndex - 1] = $tableBaru[$keyRow][$totalIndex - 2] / $tableBaru[$keyRow][$kolomKunci['kolom_kunci']];
            }
        }
        
        echo '<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
            <div class="pr-5 pl-5 pt-5 pb-2">
            <p class="text-muted">
                Tahap '.$tahap.'
            </p>
            <table class="table table-bordered table-sm shadow">';
        foreach($tableBaru as $key => $value) {
            $class = '';
            if ($barisKunci['baris_kunci'] == $key) {
                $class = 'bg-success';
            }
            
            echo "<tr class='". $class ."'>";
            echo "<th scope='row' class='bg-secondary'>". $key ."</th>";
            foreach ($value as $keyChild => $valueChild) {
                $class = '';
                if ($kolomKunci['kolom_kunci'] == $keyChild) {
                    $class = 'bg-warning';
                }
                echo "<td class='". $class ."'><small>". $valueChild ."</small></td>";
            }
            echo "</tr>";
        }
        echo "</table>";
        echo "<br>";

        $tableBaruIterasi = $tableBaru;
        foreach($tableBaru as $keyRow => $valueRow) {
            $lastIndex = count($valueRow) - 1;

            if ($keyRow != $barisKunci['baris_kunci']) {
                foreach($valueRow as $keyColumn => $valueColumn) {
                    if ($keyColumn != array_key_last($valueRow)) {
                        $nilaiKali = $tableBaru[$keyRow][$barisKunci['nilai_baris_kunci']] * $tableBaru[$barisKunci['baris_kunci']][$keyColumn];
                        $tableBaruIterasi[$keyRow][$keyColumn] = $valueColumn - $nilaiKali;
                    }
                }
            }

            $tableBaruIterasi[$keyRow][$totalIndex - 1] = 0;
        }

        echo '<table class="table table-bordered table-sm shadow border-radius">';
        foreach($tableBaruIterasi as $key => $value) {
            echo "<tr>";
            echo "<th scope='row' class='bg-secondary'>". $key ."</th>";
            foreach ($value as $keyChild => $valueChild) {
                echo "<td><small>". $valueChild ."</small></td>";
            }
            echo "</tr>";
        }
        echo "</table></div>";
        echo "<br>";

        $loopAgain = false;
        foreach($tableBaruIterasi['Z'] as $iterasiBaru) {
            if ($iterasiBaru < 0) {
                $loopAgain = true;
            }
        }

        if ($loopAgain) {
            $tahap++;
            $this->perhitungan($tableBaruIterasi, $tahap);
        }
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

    public function nilaiRasio($x, $y) {
        return $x / $y;
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
