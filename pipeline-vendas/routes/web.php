<?php
use Illuminate\Support\Facades\Http;

Route::get('/', 'PipelineController@show');
Route::get('/create', 'PipelineController@show');
Route::post('/create', 'PipelineController@create');
Route::post('/update', 'PipelineController@update');
Route::post('/pipeline/delete', 'PipelineController@delete');

Route::get('/situacao', 'SituacaoController@show');
Route::post('/situacao/create', 'SituacaoController@create');

Route::get('/fechamento', 'FechamentoController@show');
Route::post('/fechamento/create', 'SituacaoController@create');

    



