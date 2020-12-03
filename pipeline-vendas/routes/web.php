<?php

Route::get('/', 'PipelineController@show');
Route::get('/create', 'PipelineController@show');
Route::get('/find', 'PipelineController@findBySituacao');
Route::post('/create', 'PipelineController@create');
Route::post('/update', 'PipelineController@update');
Route::post('/pipeline/delete', 'PipelineController@delete');

Route::get('/situacao', 'SituacaoController@show');
Route::post('/situacao/create', 'SituacaoController@create');

Route::get('/fechamento/detalhes', 'FechamentoController@getResumo');
Route::get('/fechamento', 'FechamentoController@show');
Route::post('/fechamento/find', 'FechamentoController@find');
Route::post('/fechamento/detalhes', 'FechamentoController@getDetalhes');
Route::post('/fechamento/detalhes-fato', 'FechamentoController@getDetalhesFato');
Route::post('/fechamento/confirmar-fechamento', 'FechamentoController@confirmarFechamento');
