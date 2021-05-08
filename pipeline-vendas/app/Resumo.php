<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Resumo extends Model {
  private $situacao;
  private $tot_rec_est;
  private $tot_rec_esp;
  private $tot_imp;

  public function __construct($situacao) {
      $this->situacao = $situacao;
  }
  public function setSituacao($situacao) {
    $this->situacao = $situacao;
  }
  public function setReceitaEst($tot_rec_est) {
    $this->tot_rec_est = $tot_rec_est;
  }
  public function setReceitaEsp($tot_rec_esp) {
    $this->tot_rec_esp = $tot_rec_esp;
  }
  public function setImpacto($tot_imp) {
    $this->tot_imp = $tot_imp;
  }
  public function getSituacao() {
    return $this->situacao;
  }
  public function getReceitaEst() {
    return $this->tot_rec_est;
  }
  public function getReceitaEsp() {
    return $this->tot_rec_esp;
  }
  public function getImpacto() {
    return $this->tot_imp;
  }
}
