<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TestController extends Controller
{
    public function index()
    {
        $matriksKeputusan = [
            [78,90,76,75,71,55,76,34,78,76],
            [76,98,45,34,68,30,56,78,98,78],
            [45,87,34,45,66,46,45,54,76,45],
            [35,86,37,78,63,78,87,34,56,67],
            [78,56,90,97,94,52,68,56,47,56],
            [98,78,97,54,71,78,90,78,98,78],
            [58,45,45,36,72,90,87,90,86,43],
            [68,37,67,76,69,76,56,98,58,67],
            [98,86,58,54,64,45,78,86,76,54],
            [87,76,65,86,63,34,45,85,90,57],
            [67,89,87,90,60,78,63,84,87,54],
            [86,47,47,43,55,98,47,98,45,78],
            [45,56,87,40,60,76,97,85,76,90],
            [90,78,75,67,97,30,65,87,98,87],
            [87,98,71,65,95,34,43,45,76,65],
            [89,76,72,86,98,76,46,67,90,79],
            [86,45,83,56,80,97,76,87,45,54],
            [67,67,72,78,78,65,95,56,67,34],
            [45,78,91,54,70,56,65,74,46,56],
            [56,97,56,57,96,79,57,52,86,33]
        ];
        
    $jumlahAlternatif = count($matriksKeputusan);
    $jumlahKriteria = count($matriksKeputusan[0]);

    // create array column with for
    $arrayColumn = [];
    for ($i = 0; $i < $jumlahKriteria; $i++) {
        $arrayColumn[$i] = array_column($matriksKeputusan, $i);
    }

    // create max and min each criteria
    $max = [];
    $min = [];
    for ($i = 0; $i < $jumlahKriteria; $i++) {
        $max[$i] = max($arrayColumn[$i]);
        $min[$i] = min($arrayColumn[$i]);
    }

    // normalisasi
    $normalisasiMatrix = [];
    for ($i = 0; $i < $jumlahAlternatif; $i++) {
        for ($j = 0; $j < $jumlahKriteria; $j++) {
            $normalisasiMatrix[$i][$j] = $matriksKeputusan[$i][$j] / $max[$j];
        }
    }

    // Jumlah kolom matriks normalisasi
    $sumEachCriteria = [];
    for ($i = 0; $i < $jumlahKriteria; $i++) {
        $sumEachCriteria[$i] = array_sum(array_column($normalisasiMatrix, $i));
    }

    // nilai matriks normalisasi / $sumEachCriteria
    $averageValue = [];
    for ($i = 0; $i < $jumlahAlternatif; $i++) {
        for ($j = 0; $j < $jumlahKriteria; $j++) {
            $averageValue[$i][$j] = $normalisasiMatrix[$i][$j] / $sumEachCriteria[$j];
        }
    }

    // $averageValue * LN($averageValue)
    $ln = [];
    for ($i = 0; $i < $jumlahAlternatif; $i++) {
        for ($j = 0; $j < $jumlahKriteria; $j++) {
            $ln[$i][$j] = $averageValue[$i][$j] * log($averageValue[$i][$j]);
        }
    }

    // sum each column of ln
    $sumLn = [];
    for ($i = 0; $i < $jumlahKriteria; $i++) {
        $sumLn[$i] = array_sum(array_column($ln, $i));
    }

    // -1 / LN($jumlahAlternatif) * $sumLn
    $result = array_map(function ($value) use ($jumlahAlternatif) {
        return (-1 / log($jumlahAlternatif)) * $value;
    }, $sumLn);

    // 1 - $result
    $dispresi = array_map(function ($value) {
        return 1 - $value;
    }, $result);

    // sum $dispresi
    $sumDispresi = array_sum($dispresi);
    
    // $dispresi / $sumDispresi
    $resultDispresi = array_map(function ($value) use ($sumDispresi) {
        return $value / $sumDispresi;
    }, $dispresi);


    // matrix ^ 2
    $pow = [];
    for ($i = 0; $i < $jumlahAlternatif; $i++) {
        for ($j = 0; $j < $jumlahKriteria; $j++) {
            $pow[$i][$j] = pow($matriksKeputusan[$i][$j], 2);
        }
    }
    
    // sum each column of pow
    $sumPow = [];
    for ($i = 0; $i < $jumlahKriteria; $i++) {
        $sumPow[$i] = array_sum(array_column($pow, $i));
    }

    // sqrt sumPow
    $sqrt = array_map(function ($value) {
        return sqrt($value);
    }, $sumPow);

    // matrix / sqrt
    $resultMatrix = [];
    for ($i = 0; $i < $jumlahAlternatif; $i++) {
        for ($j = 0; $j < $jumlahKriteria; $j++) {
            $resultMatrix[$i][$j] = $matriksKeputusan[$i][$j] / $sqrt[$j];
        }
    }

    // $resultMatrix * $resultDispresi
    $resultMatrixDispresi = [];
    for ($i = 0; $i < $jumlahAlternatif; $i++) {
        for ($j = 0; $j < $jumlahKriteria; $j++) {
            if ($j == 0 || $j == 1 || $j == 2 || $j == 3 || $j == 6 || $j == 7 || $j == 9) {
                $resultMatrixDispresi[$i][$j] = $resultMatrix[$i][$j] * $resultDispresi[$j];
            } else {
                $resultMatrixDispresi[$i][$j] = -1 * $resultMatrix[$i][$j] * $resultDispresi[$j];
            }
        }
    }
    

    // sum each row of $resultMatrixDispresi
    $sumResultMatrixDispresi = [];
    for ($i = 0; $i < $jumlahAlternatif; $i++) {
        $sumResultMatrixDispresi[$i] = array_sum($resultMatrixDispresi[$i]);
    }


    // sort $sumResultMatrixDispresi with key
    arsort($sumResultMatrixDispresi);

    $rank = $sumResultMatrixDispresi;
    array_multisort($rank, SORT_DESC);

     dd($sumResultMatrixDispresi, $rank);
    }
}

