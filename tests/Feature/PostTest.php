<?php

namespace Tests\Feature;

use Tests\TestCase;
use Faker\Generator as Faker;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PostTest extends TestCase
{
    use RefreshDatabase, WithFaker;


    /**
     * HTTP JSON post add test: correct code & having needed values within json returned
     *
     * @dataProvider singleDataProvider
     *
     * @param $user
     * @param $postData
     *
     * @return void
     */
    public function testPositive($user, $postData) {

        $response = $this->json(
            'POST',
            '/post',
            [
                'login' => $user,
                'header' => $postData['header'],
                'content' => $postData['content'],
            ]
        );

        $response
            ->assertStatus(201)
            ->assertJsonStructure([
               'type',
                'attributes' => [
                    'header',
                    'content'
                ]
            ]);
    }


    /**
     * HTTP response code test with the different invalid values
     *
     * @dataProvider postDataProvider
     *
     * @param $login
     * @param $postData
     * @param $code
     */
    public function testNegative($login, $postData, $code){
        $response = $this->json(
            'POST',
            '/post',
            [
                'login' => $login,
                'header' => $postData['header'],
                'content' => $postData['content'],
            ]
        );

        $response
            ->assertStatus($code);
    }


    /**
     * TODO: генерация данных при помощи faker, разобраться, почему он не работает
     */
    public function postDataProvider(){

        return [
            'empty_request' => [
                '',
                [
                    'header' => '',
                    'content' => '',
                ],
                422
            ],
            'no_header' => [
                'login',
                [
                    'header' => '',
                    'content' => 'some content',
                ],
                422
            ],
            'no_content' => [
                'login',
                [
                    'header' => 'header',
                    'content' => '',
                ],
                422
            ],
            'no_login' => [
                '',
                [
                    'header' => 'header',
                    'content' => 'some content',
                ],
                422
            ],
            'too_long_header' => [
                'login',
                [
                    'header' => str_repeat('header', 100), // не удалось подружить фейкер с тестами
                    'content' => 'some content',
                ],
                422
            ],
            'too_long_login' => [
                str_repeat('header', 100),
                [
                    'header' => 'header',
                    'content' => 'some content',
                ],
                422
            ]
        ];
    }


    public function singleDataProvider(){

        return [
           'assert_json' => [
               'asdas',
               [
                'header' => 'asdasd',
                'content' => 'asdasd',
               ],
           ]
        ];
    }
}
