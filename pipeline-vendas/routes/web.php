<?php
use Illuminate\Support\Facades\Http;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'PipelineController@show');
Route::get('/create', 'PipelineController@show');
Route::post('/create', 'PipelineController@create');

Route::get('/situacao', 'SituacaoController@show');
Route::post('/situacao/create', 'SituacaoController@create');

Route::get('/oi', function () {
    $response = Http::withBasicAuth('admin', 'Aa123456*+')->get('localhost:3000');
    echo $response; 
});

    



