<?php

namespace Tests\Feature;

use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * La raíz no renderiza nada propio: redirige al dashboard.
     */
    public function test_the_root_redirects_to_the_dashboard(): void
    {
        $response = $this->get('/');

        $response->assertRedirect(route('dashboard'));
    }

    /**
     * El dashboard vive detrás del middleware 'auth': un visitante
     * sin sesión termina en el login.
     */
    public function test_the_dashboard_requires_authentication(): void
    {
        $response = $this->get('/dashboard');

        $response->assertRedirect(route('login'));
    }
}
