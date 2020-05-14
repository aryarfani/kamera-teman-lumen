<?php

namespace App\Http\Controllers;

use App\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

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
    $this->validate($request, [
      'nama' => 'required',
      'email' => 'required',
      'alamat' => 'required',
      'phone' => 'required',
      'gambar' => 'required',
      'password' => 'required',
    ]);

    // Menambah gambar
    $image = $request->file('gambar');

    // get file new name
    $new_name = rand() . '.' . $image->getClientOriginalExtension();

    // move file to location
    $image->move(base_path('public_html/images'), $new_name);

    // save the location
    $gambar = url('images') . '/' . $new_name;

    $hashPwd = Hash::make($request->password);

    $admin = new Admin();
    $admin->nama = $request->nama;
    $admin->alamat = $request->alamat;
    $admin->email = $request->email;
    $admin->phone = $request->phone;
    $admin->password = $hashPwd;

    $admin->gambar = $gambar;
    $admin->save();

    return response()->json($admin);
  }

  //* Fungsi mengupdate data
  public function update(Request $request, $id)
  {

    $hashPwd = Hash::make($request->password);

    $admin = Admin::findOrFail($id);
    $admin->nama = $request->nama;
    $admin->alamat = $request->alamat;
    $admin->email = $request->email;
    $admin->phone = $request->phone;
    $admin->password = $hashPwd;

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
