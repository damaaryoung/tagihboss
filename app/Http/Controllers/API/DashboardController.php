<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Assigments\ActivityAssigment;
use DB;
class DashboardController extends Controller
{
  public function chart()
  {
      $filter = request()->year . '-' . request()->month; //GET DATA BULAN & TAHUN YANG DIKIRIMKAN SEBAGAI PARAMETER

      $parse = Carbon::parse($filter); //UBAH FORMATNYA MENJADI FORMAT CARBON
      //BUAT RANGE TANGGAL PADA BULAN TERKAIT
      $array_date = range($parse->startOfMonth()->format('d'), $parse->endOfMonth()->format('d'));

      //GET DATA TRANSAKSI BERDASARKAN BULAN & TANGGAL YANG DIMINTA.
      //GROUP / KELOMPOKKAN BERDASARKAN TANGGALNYA
      //SUM DATA AMOUNT DAN SIMPAN KE NAMA BARU YAKNI TOTAL
      $transaction = ActivityAssigment::select(DB::raw('date(created_at) as date,sum(task_code) as total'))
          ->where('created_at', 'LIKE', '%' . $filter . '%')
          ->groupBy(DB::raw('date(created_at)'))->get();

      $data = [];
      //LOOPING RANGE TANGGAL BULAN SAAT INI
      foreach ($array_date as $row) {
          //KITA CEK TANGGALNYA, JIKA HANYA 1 ANGKA (1-9) MAKA TAMBAHKAN 0 DIDEPANNYA
          $f_date = strlen($row) == 1 ? 0 . $row:$row;
          //BUAT FORMAT TANGGAL YYYY-MM-DD
          $date = $filter . '-' . $f_date;
          //CARI DATA BERDASARKAN $DATE PADA COLLECTION HASIL QUERY
          $total = $transaction->firstWhere('date', $date);

          //SIMPAN DATANYA KE DALAM VARIABLE $DATA
          $data[] = [
              'date' =>$date,
              'total' => $total ? $total->total:0
          ];
      }
      return $data;
  }
}
