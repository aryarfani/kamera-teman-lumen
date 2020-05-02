<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->group(['prefix' => 'api'], function () use ($router) {
    // admin route
    $router->get('/admin', 'AdminController@index');
    $router->get('/admin/{id}', 'AdminController@show');
    $router->post('/admin', 'AdminController@create');
    $router->post('/admin/{id}', 'AdminController@update');
    $router->delete('/admin/{id}', 'AdminController@destroy');

    // member route
    $router->get('/member', 'MemberController@index');
    $router->get('/member/{id}', 'MemberController@show');
    $router->post('/member', 'MemberController@create');
    $router->post('/member/{id}', 'MemberController@update');
    $router->delete('/member/{id}', 'MemberController@destroy');
    $router->post('/saveTokenMember/{id}', 'MemberController@saveToken');

    // barang route
    $router->get('/barang', 'BarangController@index');
    $router->get('/barang/{id}', 'BarangController@show');
    $router->post('/barang', 'BarangController@create');
    $router->post('/barang/{id}', 'BarangController@update');
    $router->delete('/barang/{id}', 'BarangController@destroy');

    // keranjang route
    $router->get('/keranjang/{id}', 'MemberController@showBarangKeranjang');
    $router->post('/tambahBarangKeranjang/{id}', 'MemberController@tambahBarangKeranjang');
    $router->post('/hapusBarangKeranjang/{id}', 'MemberController@hapusBarangKeranjang');
    $router->get('/jumlahHargaKeranjang/{id}', 'MemberController@jumlahHargaKeranjang');
    $router->get('/jumlahBarangKeranjang/{id}', 'MemberController@jumlahBarangKeranjang');

    // riwayat route for client
    $router->post('/checkOutPesanan/{id}', 'MemberController@checkOutPesanan');
    $router->get('/getAllMemberRiwayat/{id}', 'MemberController@getAllMemberRiwayat');
    $router->get('/getUncofirmedMemberRiwayat/{id}', 'MemberController@getUncofirmedMemberRiwayat');
    $router->get('/getBorrowedMemberRiwayat/{id}', 'MemberController@getBorrowedMemberRiwayat');
    $router->get('/getDoneAndCancelledMemberRiwayat/{id}', 'MemberController@getDoneAndCancelledMemberRiwayat');

    // riwayat route for admin
    $router->get('/getAllRiwayat/', 'RiwayatController@getAllRiwayat');
    $router->get('/getUnconfirmedRiwayat/', 'RiwayatController@getUnconfirmedRiwayat');
    $router->get('/getBorrowedRiwayat/', 'RiwayatController@getBorrowedRiwayat');
    $router->get('/confirmBarang/{id}', 'RiwayatController@confirmBarang');
    $router->get('/cancelBarang/{id}', 'RiwayatController@cancelBarang');

    // chat route
    $router->get('/getConversationByUserId/{id}', 'ChatController@getConversationByUserId');
    $router->post('/addMessageToConversation/{id}', 'ChatController@addMessageToConversation');
    $router->get('/getAllConversation', 'ChatController@getAllConversation');

    // auth route
    $router->post('/loginMember', 'AuthController@loginMember');
    $router->post('/loginAdmin', 'AuthController@loginAdmin');
});
