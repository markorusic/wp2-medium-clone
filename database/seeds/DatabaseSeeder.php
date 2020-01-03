<?php

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Post;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker\Factory::create();

        for ($i = 0; $i < 100; $i++) {
            $user = User::create([
                'name' => $faker->name,
                'email' => $faker->unique()->safeEmail,
                'password' => bcrypt(123456)
            ]);
            $posts = [];
            for ($k = 0; $k < 30; $k++) {
                array_push($posts, [
                    'title' => $faker->realText($maxNbChars = 30, $indexSize = 2),
                    'content' => $faker->realText($maxNbChars = 5000, $indexSize = 2),
                    'main_photo' => $faker->imageUrl($width = 640, $height = 480)
                ]);
            }
            $user->posts()->createMany($posts);
        }

    }
}
