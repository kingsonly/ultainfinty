<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    protected $faker ;
   
    public function run()
    {
        $this->faker = Faker::create();
        \App\Models\Article::factory(20)->create()
        ->each(function ($u) {
            // save both comments and tags
            $u->comment()->saveMany(\App\Models\Comment::factory(20)->make([
                "article_id" => $u->id,
            ]));

            $u->tag()->saveMany(\App\Models\Tag::factory(5)->make([
                "article_id" => $u->id,
            ]));
        });

    }
}
