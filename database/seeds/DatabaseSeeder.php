<?php

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Post;
use App\Models\Category;
use App\Models\Comment;
use App\Enums\UserActivityType;

class DatabaseSeeder extends Seeder
{
    const NUMBER_OF_CATEGORIES = 15;

    const NUMBER_OF_USERS = 10;

    const NUMBER_OF_POSTS_PER_USER = 5;

    const NUMBER_OF_COMMENTS_PER_POST = 3;

    private static function mapIds($collection) {
        return $collection
            ->map(function ($item) { return $item->id; })
            ->toArray();
    }

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
                auth()->loginUsingId($user->id, true);

                // User activities
                $user->track([
                    UserActivityType::USER_REGISTER_SUCCESS,
                    UserActivityType::USER_LOGIN_FAIL,
                    UserActivityType::USER_LOGIN_SUCCESS,
                    UserActivityType::USER_LOGOUT_SUCCESS
                ]);

                // User posts
                $user->posts()->saveMany(
                    factory(Post::class, self::NUMBER_OF_POSTS_PER_USER)->make()
                );

                // User categories
                $user->categories()->sync(
                    self::mapIds($categories->shuffle()->take(5))
                );

                $user->posts->each(function ($post) use ($user, $categories) {
                    // Post categories
                    $post->categories()->sync(
                        self::mapIds($categories->shuffle()->take(5))
                    );

                    // Post like
                    $post->like();

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
