<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    // public function testBasicTest()
    // {
    //     $this->assertTrue(true);
    // }
    public function testBuscaTotalReceitasMesAtual() {
        // $response = $this->call('POST', "/receitas/mes-atual", ["mes"=> 12, "ano" =>2020]);
        $response = $this->get('/teste');
        $response->assertStatus(200);
      }
}
