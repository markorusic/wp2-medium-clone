<?php

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Post;
use App\Models\Category;
use App\Models\Comment;

class DatabaseSeeder extends Seeder
{
    const NUMBER_OF_CATEGORIES = 15;

    const NUMBER_OF_USERS = 10;

    const NUMBER_OF_CATEGORIES_PER_USER = 5;

    const NUMBER_OF_POSTS_PER_USER = 5;

    const NUMBER_OF_COMMENTS_PER_POST = 3;

    const NUMBER_OF_CATEGORIES_PER_POST = 5;

    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        $categories = factory(Category::class, self::NUMBER_OF_CATEGORIES)->create();

        factory(User::class, self::NUMBER_OF_USERS)
            ->create()
            ->each(function ($user) use ($categories) {
                // User posts
                $user->posts()->saveMany(
                    factory(Post::class, self::NUMBER_OF_POSTS_PER_USER)->make()
                );

                // User categories
                $categoryIds = $categories
                    ->random(self::NUMBER_OF_CATEGORIES_PER_USER)
                    ->map(function ($category) { return $category->id; })
                    ->toArray();
                $user->categories()->sync($categoryIds);

                $user->posts->each(function ($post) use($user, $categories) {
                    // Post categories
                    $categoryIds = $categories
                        ->random(self::NUMBER_OF_CATEGORIES_PER_POST)
                        ->map(function ($category) { return $category->id; })
                        ->toArray();
                    $post->categories()->sync($categoryIds);

                    // Post comments
                    $post->comments()->saveMany(
                        factory(Comment::class, self::NUMBER_OF_COMMENTS_PER_POST)->make([
                            'user_id' => $user->id
                        ])
                    );
                });
        });

    }
}
