<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        DB::table('TAB_SITUACAO')->insert([
            'SITUACAO' => "Antiga"
        ]);
        DB::table('TAB_SITUACAO')->insert([
            'SITUACAO' => "Antiga"
        ]);        
        DB::table('TAB_SITUACAO')->insert([
            'SITUACAO' => "Declinada"
        ]);
        DB::table('TAB_SITUACAO')->insert([
            'SITUACAO' => "Fechada"
        ]);         
        DB::table('TAB_SITUACAO')->insert([
            'SITUACAO' => "Nova"
        ]);                        
        // $this->call(UsersTableSeeder::class);
    }
}
