<?php
header('Access-Control-Allow-Origin: http://localhost:8080');
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//})

$api = app('Dingo\Api\Routing\Router');

$api->version('v1', function ($api) {
    
    $api->get('/', function () {
        //return view('api');
    });
    
    $api->get('/docs', function () {
        return view('vendor.l5-swagger.index');
    });
    
    /**
     * Users routes
     */
    $api->get('users', 'App\Http\Controllers\Api\UsersController@index');
    $api->get('users/{id}', 'App\Http\Controllers\Api\UsersController@show');
    $api->post('users/create', 'App\Http\Controllers\Api\UsersController@store');
    $api->put('users/{id}', 'App\Http\Controllers\Api\UsersController@update');  
    $api->delete('users/{id}', 'App\Http\Controllers\Api\UsersController@destroy'); 
    
    /**
     * gmail routes
     */
    $api->get('gmail', 'App\Http\Controllers\Api\GmailController@index');


    /**
     * message routes
    */
    $api->get('message', 'App\Http\Controllers\Api\MessageController@index');


    /**
     * client routes
    */
    $api->get('client', 'App\Http\Controllers\Api\ClientController@index');
    $api->get('client/{id}/message', 'App\Http\Controllers\Api\ClientMessageController@index');
    
});



