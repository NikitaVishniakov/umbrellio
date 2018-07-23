<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's  database using controllers code.
     *
     * VERY BIG KOSTYL!!
     *
     * @return void
     */
    public function run()
    {
        \Illuminate\Database\Eloquent\Model::unguard();

        if ($this->command->confirm('Обновим базу данных? Снесем все таблицы и накатим заново, без лишних данных(конечно же да, но на всякий случай..) ?', true)) {

            $this->command->call('migrate:fresh');

            $this->command->line("База данных отчищена, можем продолжать.");
        }

        $numberOfUsers = $this->command->ask('Сколько пользователей создадим?', 100);
        $numberOfPosts = $this->command->ask('Сколько постов создадим?', 200000);
        if($numberOfPosts < $numberOfUsers){
            $this->command->warn("Постов не может быть меньше, чем пользователей. Будет создано {$numberOfPosts} постов");
            $numberOfPosts = $numberOfUsers;
        }
        $numberOfIps = $this->command->ask('Сколько айпи адресов создадим?', 50);
        $numberOfReviews = $numberOfPosts/2;
        $users = [];
        $ips = [];
        $reviews = [];
        $faker = \Faker\Factory::create();

        for($i = 0; $i < $numberOfUsers; $i++){
            $users[] = $faker->lastName;
        }

        for($a = 0; $a < $numberOfIps; $a++){
            $ips[] = $faker->ipv4;
        }
        for($c = 0; $c < $numberOfPosts/2; $c++){
            $reviews[] = [
                'rating' => rand(1,5),
                'post_id' => rand(1, $numberOfPosts),
            ];
        }

        $this->command->info("Теперь немного ждем, пока это все будет создано...");

        for($b = 0; $b < $numberOfPosts; $b++){
            $post = [
                'content' => $faker->paragraph,
                'header' => $faker->text(100),
                'login' => array_random($users),
                'ip' => array_random($ips),
            ];
            $request = new \App\Http\Requests\AddPost($post);
            $controller = new \App\Http\Controllers\PostController();
            $controller->add($request);
        }
        $this->command->info("Посты сгенерированы, теперь создадим отзывы..");

        foreach ($reviews as $review){
            $post = \App\Models\Post::find($review['post_id']);
            $request = new \App\Http\Requests\AddReview($review);
            $controller = new \App\Http\Controllers\ReviewController();
            $controller->add($post, $request);
        }
        $this->command->info("Готово");
    }
}
