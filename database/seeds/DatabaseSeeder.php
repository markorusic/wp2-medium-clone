<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Post;
use App\Models\Category;

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

        $categories = collect([]);
        for ($j = 0 ; $j < 20 ; $j++) { 
            $categories->push([
                'name' => $faker->word,
                'description' => $faker->realText($maxNbChars = 25, $indexSize = 2),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]);
        }

        Category::insert($categories->toArray());
        $categories = Category::all();

        for ($i = 0; $i < 10; $i++) {
            $user = User::create([
                'name' => $faker->name,
                'email' => $faker->unique()->safeEmail,
                'password' => bcrypt(123456)
            ]);

            $posts = collect([]);
            for ($k = 0; $k < 5; $k++) {
                $posts->push([
                    'title' => $faker->realText($maxNbChars = 15, $indexSize = 2),
                    'content' => $faker->realText($maxNbChars = 5000, $indexSize = 2),
                    'main_photo' => $faker->imageUrl($width = 640, $height = 480)
                ]);
            }
            $posts = $user->posts()->createMany($posts->toArray());

            foreach ($posts as $post) {
                $comments = [];
                for ($t = 0; $t < 3; $t++) { 
                    array_push($comments, [
                        'content' => $faker->realText($maxNbChars = 20, $indexSize = 2),
                        'user_id' => $user->id,
                        'post_id' => $post->id
                    ]);
                }
                $post->comments()->createMany($comments);
                
                $categoryIds = $categories->random(3)->map(function ($category) {
                    return $category->id;
                })->toArray();
                $post->categories()->sync($categoryIds);
                $post->categories()->sync($categoryIds);
            }

            $categoryIds = $categories->random(5)->map(function ($category) {
                return $category->id;
            })->toArray();
            $user->categories()->sync($categoryIds);
        }

    }
}
