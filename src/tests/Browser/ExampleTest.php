<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class ExampleTest extends DuskTestCase
{
    /**
     * A basic browser test example.
     */
    public function testBasicExample(): void
    {
        try {
            $this->browse(function (Browser $browser) {
                $browser->visit('/login')
                    ->assertSee('ログイン');
            });
        } catch (\Exception $e) {
            echo "\n";
            echo $e->getMessage();
            echo "\n";
            throw $e;
        }
    }
}
