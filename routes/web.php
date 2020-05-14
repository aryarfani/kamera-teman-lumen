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
    $router->post('/member/{id}/token', 'MemberController@saveToken');

    // barang route
    $router->get('/barang', 'BarangController@index');
    $router->get('/barang/{id}', 'BarangController@show');
    $router->post('/barang', 'BarangController@create');
    $router->post('/barang/{id}', 'BarangController@update');
    $router->delete('/barang/{id}', 'BarangController@destroy');

    // keranjang route
    $router->get('/keranjang/{id}', 'MemberController@showBarangKeranjang');
    $router->post('/keranjang/{id}', 'MemberController@tambahBarangKeranjang');
    $router->post('/keranjang/{id}/delete', 'MemberController@hapusBarangKeranjang');
    $router->get('/keranjang/{id}/harga', 'MemberController@jumlahHargaKeranjang');
    $router->post('/keranjang/{id}/checkout', 'MemberController@checkOutPesanan');

    // riwayat route for client
    $router->get('/riwayat/{id}', 'MemberController@getAllMemberRiwayat');
    $router->get('/riwayat/{id}/uncofirmed', 'MemberController@getUncofirmedMemberRiwayat');
    $router->get('/riwayat/{id}/borrowed', 'MemberController@getBorrowedMemberRiwayat');
    $router->get('/riwayat/{id}/doneandcancelled', 'MemberController@getDoneAndCancelledMemberRiwayat');

    // riwayat route for admin
    $router->get('/riwayat', 'RiwayatController@getAllRiwayat');
    $router->get('/unconfirmed-riwayat', 'RiwayatController@getUnconfirmedRiwayat');
    $router->get('/borrowed-riwayat', 'RiwayatController@getBorrowedRiwayat');
    $router->get('/barang/{id}/confirm', 'RiwayatController@confirmBarang');
    $router->get('/barang/{id}/cancel', 'RiwayatController@cancelBarang');

    // chat route
    $router->get('/conversation/{id}', 'ChatController@getConversationByUserId');
    $router->post('/message/{id}', 'ChatController@addMessageToConversation');
    $router->get('/conversation', 'ChatController@getAllConversation');

    // auth route
    $router->post('/login/member', 'AuthController@loginMember');
    $router->post('/login/admin', 'AuthController@loginAdmin');
});
