<?php

namespace App\Http\Controllers;

use App\Barang;
use Illuminate\Auth\Access\Response;
use Illuminate\Http\Request;

class BarangController extends Controller
{
  //* Fungsi menampilkan semua data
  public function index()
  {
    $barangs = Barang::all();
    return response()->json($barangs);
  }

  //* Fungsi menampilkan single data
  public function show($id)
  {
    $barang = Barang::findOrFail($id);
    return response()->json($barang);
  }

  //* Fungsi menambah data
  public function create(Request $request)
  {
    $this->validate($request, [
      'nama' => 'required',
      'stock' => 'required',
      'harga' => 'required',
      'gambar' => 'required',
    ]);

    // Menambah gambar
    $image = $request->file('gambar');

    $new_name = rand() . '.' . $image->getClientOriginalExtension();
    $image->move(base_path('public_html/images'), $new_name);

    $barang = new Barang();
    $barang->nama = $request->nama;
    $barang->stock = $request->stock;
    $barang->harga = $request->harga;

    $gambar = url('images') . '/' . $new_name;

    $barang->gambar = $gambar;
    $barang->save();

    return response()->json($barang);
  }

  //* Fungsi mengupdate data
  public function update(Request $request, $id)
  {
    $barang = Barang::findOrFail($id);
    $barang->nama = $request->nama;
    $barang->stock = $request->stock;
    $barang->harga = $request->harga;

    //* Jika gambar kosong maka query gambar
    //* menggunakan gambar_lama yg berisi gambar lama
    if (is_null($request->gambar)) {
      $barang->gambar = $request->gambar_lama;
    } else {
      //* Jika ada query gambar maka akan diganti
      $image = $request->file('gambar');

      $new_name = rand() . '.' . $image->getClientOriginalExtension();
      $image->move(base_path('public_html/images'), $new_name);
      $gambar = url('images') . '/' . $new_name;

      $barang->gambar = $gambar;
    }

    $barang->save();

    return response()->json($barang);
  }

  //* Fungsi menghapus data
  //* you must add hidden method _method = delete
  // to use this method
  public function destroy($id)
  {
    $barang = Barang::findOrFail($id);
    if ($barang->delete()) {
      return response()->json($barang);
    }
  }
}
