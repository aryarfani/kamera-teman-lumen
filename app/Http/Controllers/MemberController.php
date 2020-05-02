<?php

namespace App\Http\Controllers;

use App\Member;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MemberController extends Controller
{
    //* Fungsi menampilkan semua data
    public function index()
    {
        $members = Member::all();
        return response()->json($members);
    }

    //* Fungsi menampilkan single data
    public function show($id)
    {
        $member = Member::findOrFail($id);
        return response()->json($member);
    }

    //* Fungsi menambah data
    public function create(Request $request)
    {
        $member = new Member();
        $member->nama = $request->nama;
        $member->email = $request->email;
        $member->alamat = $request->alamat;
        $member->phone = $request->phone;

        $hashPwd = Hash::make($request->password);

        $member->password = $hashPwd;
    
        if ($request->file('gambar')) {
            // Menambah gambar
            $image = $request->file('gambar');

            $new_name = rand() . '.' . $image->getClientOriginalExtension();
            $image->move(base_path('public/images'), $new_name);
            $member->gambar = $new_name;
        }

        $member->save();

        return response()->json($member);
    }

    //* Fungsi mengupdate data
    public function update(Request $request, $id)
    {
        $member = Member::findOrFail($id);
        $member->nama = $request->nama;
        $member->alamat = $request->alamat;
        $member->email = $request->email;
        $member->phone = $request->phone;
        $member->password = $request->password;

        //* Jika gambar kosong maka query gambar
        //* menggunakan gambar_lama yg berisi gambar lama
        if (is_null($request->gambar)) {
            $member->gambar = $request->gambar_lama;
        } else {
            //* Jika ada query gambar maka akan diganti
            $image = $request->file('gambar');
      
            $new_name = rand() . '.' . $image->getClientOriginalExtension();
            $image->move(base_path('public_html/images'), $new_name);
      
            $member->gambar = $new_name;
        }

        $member->save();

        return response()->json($member);
    }

    //* Fungsi menghapus data
    // in case error add hidden method in request _method = delete
    // to use this method
    public function destroy($id)
    {
        $member = Member::findOrFail($id);
        if ($member->delete()) {
            return response()->json($member);
        }
    }

    //* Fungsi menampilkan daftar barang dari keranjang
    // paramater user
    // return array of barang
    public function showBarangKeranjang($id)
    {
        $member = Member::find($id);
        // var_dump(response()->json($member->keranjang));
        return response()->json($member->keranjang);
    }


    //* Fungsi menambah keranjang
    // parameter Request (post) dan id
    // return 200 if error 500
    public function tambahBarangKeranjang(Request $request, $id)
    {
        $member = Member::find($id);

        $member->keranjang()->attach($request->id_barang);

        return response()->json();
    }

    //! Post
    //* Fungsi menghapus keranjang
    // parameter Request (post) dan id
    // return 200 if error 500
    public function hapusBarangKeranjang(Request $request, $id)
    {
        $member = Member::find($id);

        $member->keranjang()->detach($request->id_barang);

        return response()->json();
    }

    //! Get
    //* Fungsi menghitung jumlah barang di keranjang
    // parameter id
    // return 200 if error 500
    public function jumlahBarangKeranjang($id)
    {
        $member = Member::find($id);
        $barangs = $member->keranjang;

        return response()->json($barangs->count());
    }

    //! Get
    //* Fungsi menghitung jumlah harga di keranjang
    // parameter id
    // return 200 if error 500
    public function jumlahHargaKeranjang($id)
    {
        $member = Member::find($id);
        $harga = $member->keranjang->sum('harga');
 
        return response()->json($harga);
    }

    //! Get
    //* Fungsi men-check out pesanan
    // parameter id
    // return 200 if error 500
    public function checkOutPesanan(Request $request, $id)
    {
        $member = Member::find($id);
        $barangs = $member->keranjang;
        $durasi = $request->durasi;

        foreach ($barangs as $barang) {
            $barang_id = $barang->pivot->barang_id;
            $member->riwayat()->attach($barang_id);
            $member->keranjang()->detach($barang_id);
            $harga = $barang->harga;
            $total_biaya = $harga  * $durasi;
            DB::table('riwayats')->where('member_id', '=', $id)->where('barang_id', '=', $barang_id)->update(array('total_biaya' => $total_biaya));
        }
        DB::table('riwayats')->where('member_id', '=', $id)->update(array(
            'durasi' =>  $durasi,
        ));
      
        return response()->json($member->keranjang);
    }

    //! Get
    //* Fungsi mengambil barang = barang di keranjang
    // parameter id
    // return 200 if error 500
    public function getAllMemberRiwayat($id)
    {
        $member = Member::find($id);
        $riwayat = $member->riwayat;

        return $riwayat;
    }
    
    //! Get
    //* Fungsi mengambil barang = barang di keranjang berstatus 0 = uncofirmed
    // parameter id
    // return 200 if error 500
    public function getUncofirmedMemberRiwayat($id)
    {
        $member = Member::find($id);
        $riwayat = $member->riwayat()->wherePivot('status', '=', 0)->get();

        return $riwayat;
    }
  
    //! Get
    //* Fungsi mengambil barang = barang di keranjang berstatus 1 = borrowed
    // parameter id
    // return 200 if error 500
    public function getBorrowedMemberRiwayat($id)
    {
        $member = Member::find($id);
        $riwayat = $member->riwayat()->wherePivot('status', '=', 1)->get();

        return $riwayat;
    }
    
    //! Get
    //* Fungsi mengambil barang = barang di keranjang berstatus 1 = borrowed
    // parameter id
    // return 200 if error 500
    public function getDoneAndCancelledMemberRiwayat($id)
    {
        $member = Member::find($id);
        $riwayat = $member->riwayat()->wherePivot('status', '>', 1)->get();

        return $riwayat;
    }


    public function saveToken(Request $request, $id)
    {
        DB::table('members')->where('id', '=', $id)->update(array('token' => $request->token));
        return response()->json();
    }
}
