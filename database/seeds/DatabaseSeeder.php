<?php

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Post;
use App\Models\Category;
use App\Models\Comment;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        $categories = factory(Category::class, 15)->create();

        factory(User::class, 10)->create()->each(function ($user) use ($categories) {
            // User posts
            $user->posts()->saveMany(
                factory(Post::class, 3)->make()
            );

            // User categories
            $categoryIds = $categories
                ->random(5)
                ->map(function ($category) { return $category->id; })
                ->toArray();
            $user->categories()->sync($categoryIds);

            $user->posts->each(function ($post) use($user, $categoryIds) {
                // Post categories
                $post->categories()->sync($categoryIds);

                // Post comments
                $post->comments()->saveMany(
                    factory(Comment::class, 2)->make([
                        'user_id' => $user->id
                    ])
                );
            });
        });

    }
}
