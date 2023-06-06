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
        //$min = [];
        for ($i = 0; $i < $jumlahKriteria; $i++) {
            $max[$i] = max($arrayColumn[$i]);
           // $min[$i] = min($arrayColumn[$i]); for entropy method we don't use min value
        }

        // create normalisasi matrix by dividing each value with max if benefit (index = 0,1,2) or min if cost (index = 3,4)
        $normalisasiMatrix = [];
        for ($i = 0; $i < $jumlahAlternatif; $i++) {
            for ($j = 0; $j < $jumlahKriteria; $j++) {
                    $normalisasiMatrix[$i][$j] = $matriksKeputusan[$i][$j] / $max[$j];
            /*    } else {
                    $normalisasiMatrix[$i][$j] = $min[$j] / $matriksKeputusan[$i][$j];
            */  
            }
        }
       
       // sum each column of normalisasi matrix
         $sumEachCriteria = [];
         for ($i = 0; $i < $jumlahKriteria; $i++) {
            $sumEachCriteria[$i] = array_sum(array_column($normalisasiMatrix, $i));
         }

    // matriks Aij
          $matriksAij = [];
          for ($i = 0; $i < $jumlahAlternatif; $i++) {
             for ($j = 0; $j < $jumlahKriteria; $j++) {
                    $matriksAij[$i][$j] = $normalisasiMatrix[$i][$j] / $sumEachCriteria[$j];
             }
          }
        

    // matriks entropy - menghitung total - menghitung nilai entropy
            $matriksEntropy = [];
            for ($i = 0; $i < $jumlahAlternatif; $i++) {
             for ($j = 0; $j < $jumlahKriteria; $j++) {
                     $matriksEntropy[$i][$j] = $matriksAij[$i][$j] * log($matriksAij[$i][$j]);
             }
         }
    

    // sum each column of matriks entropy
         $sumEachMatriksEntropy = [];
         for ($i = 0; $i < $jumlahKriteria; $i++) {
             $sumEachMatriksEntropy[$i] = array_sum(array_column($matriksEntropy, $i));
         }
    

    // menghitung nilai entropy
         $entropyValue = [];
         for ($i = 0; $i < $jumlahKriteria; $i++) {
             $entropyValue[$i] = (-1/log($jumlahAlternatif))* $sumEachMatriksEntropy[$i];
         }

    // menghitung nilai dispersi kriteria (Dj)
         $dispersiKriteria = [];
         for ($i = 0; $i < $jumlahKriteria; $i++) {
             $dispersiKriteria[$i] = 1- ($entropyValue[$i]);
         }
         

    // menghitung total nilai dispersi kriteria
              $sumEachDispersiKriteria = array_sum($dispersiKriteria);
            
        
    // normalisasi nilai dispersi (Wj)
           $normalisasiNilaiDispersi = [];
           for ($i = 0; $i < $jumlahKriteria; $i++) {
               $normalisasiNilaiDispersi[$i] =  $dispersiKriteria[$i] / $sumEachDispersiKriteria;
             
           }
    

    // PERHITUNGAN MOORA

     $sumEachCriteriaKuadrat = [];
     for ($i = 0; $i < $jumlahKriteria; $i++) {
        $sumEachCriteriaKuadrat[$i] = array_sum(array_column($matriksKeputusan, $i));
     }

     $CriteriaKuadrat = [];
     for ($i = 0; $i < $jumlahKriteria; $i++) {
         $CriteriaKuadrat[$i] = pow($matriksKeputusan[$i][$j], 2);
     }

    //  $sqrtCriteriaKuadrat = [];
    //  for ($i = 0; $i < $jumlahKriteria; $i++) {
    //      $sqrtCriteriaKuadrat[$i] = sqrt($CriteriaKuadrat[$i]);
    //   }

    // Normalisasi Matriks
        //  $normalisasiMatrixMoora = [];
        //   for ($i = 0; $i < $jumlahAlternatif; $i++) {
        //       for ($j = 0; $j < $jumlahKriteria; $j++) {
        //               $normalisasiMatrixMoora[$i][$j] = $matriksKeputusan[$i][$j]; 
        //       }
        //   }
    //     $CriteriaKuadrat = [];
    //  for ($i = 0; $i < $jumlahKriteria; $i++) {
    //     $CriteriaKuadrat[$i] = pow($matriksKeputusan[$i], 2);
    // }
    //     $sumEachCriteriaKuadrat = [];
    // for ($i = 0; $i < $jumlahKriteria; $i++) {
    //     $sumEachCriteriaKuadrat[$i] = array_sum(array_column($CriteriaKuadrat, $i));
    // }
    // $sqrtCriteriaKuadrat = [];
    // for ($i = 0; $i < $jumlahKriteria; $i++) {
    //     $sqrtCriteriaKuadrat[$i] = sqrt($sumEachCriteriaKuadrat[$i]);
    //     }
          dd($CriteriaKuadrat);

         //(sqrt($matriksKeputusan[$i][$j]^2))+sqrt($matriksKeputusan[$i+1][$j+1]^2)

    //     // Perhitungan pembobotan - total nilai- serta ranking
    //     $matriksPembobotan = [];
    //     for ($i = 0; $i < $normalisasiMatrixMoora; $i++) {
    //         for ($j = 0; $j < $normalisasiMatrixMoora; $j++) {
    //             if ($j == 0 || $j == 1 || $j == 2) {
    //                 $matriksPembobotan[$i][$j] = $matriksKeputusan[$i][$j] * $normalisasiNilaiDispersi[$i];
    //         /*    } else {
    //                 $normalisasiMatrix[$i][$j] = $min[$j] / $matriksKeputusan[$i][$j];
    //         */   }
    //         }
    //     }

    //     // menghitung total pembobotan
    //     $sumEachPembobotan = [];
    //     for ($i = 0; $i < $matriksPembobotan; $i++) {
    //         $sumEachPembobotan[$i] = array_sum(array_column($matriksPembobotan, $i));
    //     }

    //     // perangkingan
    //     arsort($sumEachPembobotan);

    //     dd($sumEachPembobotan);
    // }
    }
}