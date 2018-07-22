<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class IpListTest extends TestCase
{
    use RefreshDatabase;
    /**
     * HTTP test for ip list
     *
     * @return void
     */
    public function testEmptyList()
    {

        $response = $this->json(
            'GET',
            '/ip/authors'
        );

        $response
            ->assertStatus(204);
    }

    /**
     * HTTP test for ip list
     *
     * @return void
     */
    public function testList()
    {

        $response = $this->json(
            'GET',
            '/ip/authors'
        );

        $response
            ->assertStatus(204);
    }
}
