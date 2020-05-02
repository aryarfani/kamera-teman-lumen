<?php

namespace App\Http\Controllers;

use App\Riwayat;
use Illuminate\Auth\Access\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RiwayatController extends Controller
{
    //* status
    // 0 = uncofirmed
    // 1 = borrowed
    // 2 = done
    // 3 = cancelled


    //* Fungsi menampilkan semua data
    public function getAllRiwayat()
    {
        return DB::table('riwayats')
      ->join('barangs', 'barangs.id', '=', 'barang_id')
      ->join('members', 'members.id', '=', 'member_id')
      ->select('members.nama AS nama_member', 'barangs.*', 'riwayats.tanggal_tempo', 'riwayats.status', 'riwayats.total_biaya', 'riwayats.durasi', 'riwayats.id as riwayat_id', )
      ->get();
    }
  
    //* Fungsi menampilkan semua data belum di konfirmasi
    public function getUnconfirmedRiwayat()
    {
        return DB::table('riwayats')
      ->where('status', '=', '0')
      ->join('barangs', 'barangs.id', '=', 'barang_id')
      ->join('members', 'members.id', '=', 'member_id')
      ->select('members.nama AS nama_member', 'barangs.*', 'riwayats.tanggal_tempo', 'riwayats.status', 'riwayats.total_biaya', 'riwayats.durasi', 'riwayats.id as riwayat_id', )
      ->get();
    }
  
    //* Fungsi menampilkan semua data belum di konfirmasi
    public function getBorrowedRiwayat()
    {
        return DB::table('riwayats')
      ->where('status', '=', '1')
      ->join('barangs', 'barangs.id', '=', 'barang_id')
      ->join('members', 'members.id', '=', 'member_id')
      ->select('members.nama AS nama_member', 'barangs.*', 'riwayats.tanggal_tempo', 'riwayats.status', 'riwayats.total_biaya', 'riwayats.id as riwayat_id')
      ->get();
    }


    public function confirmBarang($id)
    {
        // mendapatkan durasi dari item
        $riwayat = Riwayat::find($id);
        $durasi = $riwayat->durasi;

        // membuat tanggal tempo = waktu sekarang + 2 hari
        $currentTime = Carbon::now()->addDay($durasi);
        $tanggal_tempo = $currentTime->format('Y-m-d H:i:s');

        //* Update status ke 1 = Borrowed
        Riwayat::where('id', $id)->update(array(
            'status' => 1,
            'tanggal_tempo' => $tanggal_tempo,
          ));
    }

    public function cancelBarang($id)
    {
        //* Update status ke 3 = Cancelled
        Riwayat::where('id', $id)->update(array(
              'status' => 3,
            ));
    }
}
