<?php

namespace App\Http\Controllers;

use App\Situacao;
use Illuminate\Http\Request;

class SituacaoController extends Controller {
  public function show() {
    $situacao = Situacao::all();
    return view('situacao', ['sit' => $situacao]);
  }

  public function create(Request $request) {
    $situacao = $request->input('situacao');
  }

  public static function find($id) {
    $situacao = Situacao::where('ID_TAB_SITUACAO', '=', $id)->get();
    return $situacao;
  }

  public static function findByTipo($tipo) {
    $situacao = Situacao::where('TIPO', '=', $tipo)->get();
    return $situacao;
  }

  public static function findBySituacao($situacao, $tipo) {
    $situacao = Situacao::where('TIPO', '=', $tipo)
      ->where('SITUACAO', '=', $situacao)
      ->get();
    return $situacao;
  }

  public static function getAll() {
    $situacao = Situacao::all();
    return $situacao;
  }

  public function getSituacoesPipeline() {
    $situacao = Situacao::where('TIPO', '=', 1)->get();
    return $situacao;
  }
}
