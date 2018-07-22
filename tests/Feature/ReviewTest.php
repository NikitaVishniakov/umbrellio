<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ReviewTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Check if review is creating
     */
    public function testWithPostPositive(){
        $post = $this->json(
            'POST',
            '/post',
            [
                'login' => 'user',
                'header' => 'header',
                'content' => 'content',
            ]
        );

        $post = (json_decode($post->getContent(), true));


        $response = $this->json(
            'POST',
            '/post/'. $post['id'].'/review',
            [
                'rating' => 5,
            ]
        );

        $response
            ->assertStatus(201)
            ->assertJson([
                    'avg_rating' => 5,
                ]
            );
    }


    /**
     * Check if review will be created with the out of range rating and for non-existing post
     *
     */
    public function testNonExisting(){
        $response = $this->json(
            'POST',
            '/post/0/review',
            [
                'rating' => 5,
            ]
        );

        $response->assertStatus(404);
    }


    /**
     * Check error if post is non-existing
     *
     * @dataProvider dataProvider
     *
     * @param $rating
     * @param $code
     */
    public function testNegative($rating, $code){
        $post = $this->json(
            'POST',
            '/post',
            [
                'login' => 'user',
                'header' => 'header',
                'content' => 'content',
            ]
        );

        $post = (json_decode($post->getContent(), true));


        $response = $this->json(
            'POST',
            '/post/'. $post['id'].'/review',
            [
                'rating' => $rating,
            ]
        );

        $response->assertStatus($code);
    }


    /**
     * Check validation
     *
     * @return array
     */
    public function dataProvider(){

        return [
            'zero_rating' => [
                0,
                422,
            ],
            'too_big_rating' => [
                6,
                422,
            ],
        ];
    }
}
