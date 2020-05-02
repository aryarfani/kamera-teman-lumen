<?php

namespace App\Http\Controllers;

use App\Admin;
use Illuminate\Auth\Access\Response;
use Illuminate\Http\Request;

class AdminController extends Controller
{
  //* Fungsi menampilkan semua data
  public function index()
  {
    $admins = Admin::all();
    return response()->json($admins);
  }

  //* Fungsi menampilkan single data
  public function show($id)
  {
    $admin = Admin::findOrFail($id);
    return response()->json($admin);
  }

  //* Fungsi menambah data
  public function create(Request $request)
  {
    // Menambah gambar
    $image = $request->file('gambar');

    $new_name = rand() . '.' . $image->getClientOriginalExtension();
    $image->move(base_path('public/images'), $new_name);

    $admin = new Admin();
    $admin->nama = $request->nama;
    $admin->alamat = $request->alamat;
    $admin->email = $request->email;
    $admin->phone = $request->phone;
    $admin->password = $request->password;
    $admin->gambar = $new_name;
    $admin->save();

    return response()->json($admin);
  }

  //* Fungsi mengupdate data
  public function update(Request $request, $id)
  {
    $admin = Admin::findOrFail($id);
    $admin->nama = $request->nama;
    $admin->alamat = $request->alamat;
    $admin->email = $request->email;
    $admin->phone = $request->phone;
    $admin->password = $request->password;

    // * Jika gambar kosong maka query gambar
    // * menggunakan gambar_lama yg berisi gambar lama
    if (is_null($request->gambar)) {
      $admin->gambar = $request->gambar_lama;
    } else {
      //* Jika ada query gambar maka akan diganti
      $image = $request->file('gambar');
      
      $new_name = rand() . '.' . $image->getClientOriginalExtension();
      $image->move(base_path('public_html/images'), $new_name);
      
      $admin->gambar = $new_name;
    }

    $admin->save();

    return response()->json($admin);
  }

  //* Fungsi menghapus data
  //* you must add hidden method _method = delete
  // to use this method
  public function destroy($id)
  {
    $admin = Admin::findOrFail($id);
    if ($admin->delete()) {
      return response()->json($admin);
    }
  }

}
