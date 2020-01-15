<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\Models\User;
use App\Models\Post;
use App\Models\Category;
use App\Models\Comment;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(User::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'email_verified_at' => now(),
        'password' => bcrypt('123456'),
        'bio' => $faker->realText($maxNbChars = 150, $indexSize = 2),
        // 'avatar' => $faker->imageUrl($width = 640, $height = 640),
        'avatar' => 'https://miro.medium.com/fit/c/160/160/2*qtsXztfxKoghRmsA-R3qVQ.jpeg',
        'remember_token' => Str::random(10),
    ];
});


$factory->define(Category::class, function (Faker $faker) {
    return [
        'name' => $faker->word,
        'description' => $faker->realText($maxNbChars = 30, $indexSize = 2),
        // 'main_photo' => $faker->imageUrl($width = 640, $height = 480),
        'main_photo' => 'https://miro.medium.com/max/6120/0*qdqEWekIW_KHiu6P',
        'created_at' => now(),
        'updated_at' => now()
    ];
});

$factory->define(Post::class, function (Faker $faker) {
    $created_at = now()->subDays(mt_rand(1, 30))->toDateTimeString();
    return [
        'title' => $faker->realText($maxNbChars = 15, $indexSize = 2),
        'description' => $faker->realText($maxNbChars = 220, $indexSize = 2),
        'content' => $faker->realText($maxNbChars = 5000, $indexSize = 2),
        // 'main_photo' => $faker->imageUrl($width = 640, $height = 480),
        'main_photo' => 'https://miro.medium.com/max/6120/0*qdqEWekIW_KHiu6P',
        'created_at' => $created_at,
        'updated_at' => $created_at
    ];
});

$factory->define(Comment::class, function (Faker $faker) {
    return [
        'content' => $faker->realText($maxNbChars = 350, $indexSize = 2),
    ];
});
