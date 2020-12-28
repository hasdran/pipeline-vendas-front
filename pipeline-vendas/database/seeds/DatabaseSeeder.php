<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder {
  /**
   * Seed the application's database.
   *
   * @return void
   */
  public function run() {

    DB::table('TAB_FECHAMENTO')->insert([
        'DT_FECHAMENTO' => '2020-11-01 00:00:00',
        'DT_REFERENCIA' => '2020-11-01 00:00:00',
        'ID_TAB_SITUACAO' => 1,
        'TOT_REC_EST' => 10,
        'TOT_REC_ESP' => 10,
        'TOT_IMPACTO' => 10
    ]);

    // DB::table('TAB_SITUACAO')->insert([
    //     'SITUACAO' => "Ativa"
    // ]);
    // DB::table('TAB_SITUACAO')->insert([
    //     'SITUACAO' => "Antiga"
    // ]);
    // DB::table('TAB_SITUACAO')->insert([
    //     'SITUACAO' => "Declinada"
    // ]);
    // DB::table('TAB_SITUACAO')->insert([
    //     'SITUACAO' => "Fechada"
    //     ]);
    //     DB::table('TAB_SITUACAO')->insert([
    //         'SITUACAO' => "Aberto",
    //         'TIPO' => 0
    //     ]);
    //     DB::table('TAB_SITUACAO')->insert([
    //         'SITUACAO' => "Processado",
    //         'TIPO' => 0
    //     ]);
    //     DB::table('TAB_SITUACAO')->insert([
    //         'SITUACAO' => "Cancelado",
    //         'TIPO' => 0
    //     ]);         ]);
    // DB::table('TAB_SITUACAO')->insert([
    //     'SITUACAO' => "Aberto",
    //     'TIPO' => 0
    // ]);
    // DB::table('TAB_SITUACAO')->insert([
    //     'SITUACAO' => "Processado",
    //     'TIPO' => 0
    // ]);
    // DB::table('TAB_SITUACAO')->insert([
    //     'SITUACAO' => "Cancelado",
    //     'TIPO' => 0
    // ]);
    // $this->call(UsersTableSeeder::class);
  }
}
