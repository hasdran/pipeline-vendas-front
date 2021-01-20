<?php

//teste
Route::get('/teste', 'PipelineController@showTeste');

// Route::post('/insert', 'SituacaoController@getSituacoesPipeline');
//teste
Auth::routes();
Route::get('/', 'PipelineController@show');
Route::get('/find', 'PipelineController@findBySituacao');
Route::post('/create', 'PipelineController@create');
Route::post('/update', 'PipelineController@update');
Route::post('/pipeline/delete', 'PipelineController@delete');

Route::get('/situacao', 'SituacaoController@show');
Route::get('/situacoes', 'SituacaoController@getSituacoesPipeline');
Route::post('/situacao/create', 'SituacaoController@create');

Route::get('/fechamento', 'FechamentoController@show');
Route::get('/fechamento/resumo', 'FechamentoController@getResumo');
Route::get('/fechamento/resumo-fato', 'FechamentoController@getResumoFato');
Route::post('/fechamento/cancelar', 'FechamentoController@cancelFechamento');
Route::post('/fechamento/find', 'FechamentoController@find');
Route::post('/fechamento/detalhes', 'FechamentoController@getDetalhes');
Route::post('/fechamento/detalhes-fato', 'FechamentoController@getDetalhesFato');
Route::post('/fechamento/confirmar-fechamento', 'FechamentoController@confirmarFechamento');