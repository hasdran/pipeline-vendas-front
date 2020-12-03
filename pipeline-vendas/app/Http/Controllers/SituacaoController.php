<?php

namespace App\Http\Controllers;

use App\Situacao;
use Illuminate\Http\Request;

class SituacaoController extends Controller {
  public function show() {
    $situacao = Situacao::all();
    // echo $situacao;
    return view('situacao', ['sit' => $situacao]);
  }

  public function create(Request $request) {

    $situacao = $request->input('situacao');

  }

  public static function find($id) {
    $situacao = Situacao::where('ID_SITUACAO', '=', $id)->get();
    return $situacao;
  }
  public static function getAll() {
    $situacao = Situacao::all();
    return $situacao;
  }

}
