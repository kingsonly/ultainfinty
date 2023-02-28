<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Faker\Factory as Faker;

class ArticleTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */

     protected $faker ;

     use RefreshDatabase;
    
     public function test_to_see_if_all_article_could_be_fetched(){
        $this->generateArticleAndComments(20,20,5);
        
        $response = $this->postJson('/api/articles');

        $response->assertStatus(200)->assertJsonStructure(
            [   "status",
                "message",
                "data"=>[
                    'current_page',
                    "data"=>[
                        '*' => [
                            "id",
                            "image",
                            "title",
                            "details",
                            "views",
                            "like",
                            "created_at",
                            "updated_at",
                            "short_description"
                       ]
                    ]
                    
                ],
            ]
        )->assertJsonFragment(
            [
                'message'=>'Article fetched with pagination',
                "status" => "success"
            ]
        );
     }

     public function test_to_fetch_a_single_article(){
       $this->generateArticleAndComments(1,1,1);
        $response = $this->getJson('/api/articles/21');

        $response->assertStatus(201)->assertJsonStructure(
            [   "status",
                "message",
                "data"=>[
                    "id",
                    "image",
                    "title",
                    "details",
                    "views",
                    "like",
                    "created_at",
                    "updated_at",
                    "short_description"
                    
                ],
            ]
        )->assertJsonFragment(
            [
                "message"=> "Article fetched, and view counter updated ",
                "status" => "success"
            ]
        );
     }

     public function test_to_fetch_commets_of_a_perticular_article(){
        // first lets run a factory which would create a single article with 10 comments
        $this->generateArticleAndComments(1,1,1);
        $response = $this->getJson('/api/articles/22/comment');
        // lets hit the url for article comment and see if we were able to fetch multiple comments 
        $response->assertStatus(200)->assertJsonStructure(
            [   "status",
                "message",
                "data"=>[
                    '*' => [
                        "id",
                        "article_id",
                        "subject",
                        "body",
                    ]
                    
                ],
            ]
        )->assertJsonFragment(
            [
                "message"=> "Comment fetched",
                "status" => "success"
            ]
        );
        
     }

     public function test_to_fetch_total_like_of_a_perticular_article(){
        // first lets run a factory which would create a single article with 10 comments
        $this->generateArticleAndComments(1,1,1);
        $response = $this->getJson('/api/articles/23/like');
        // lets hit the url for article comment and see if we were able to fetch multiple comments 
        $response->assertStatus(200)->assertJsonStructure(
            [   "status",
                "message",
                "data"
            ]
        )->assertJsonFragment(
            [
                "message"=> "Likes fetched",
                "status" => "success"
            ]
        );
        
     }

     public function test_to_fetch_total_views_of_a_perticular_article(){
        // first lets run a factory which would create a single article with 10 comments
        $this->generateArticleAndComments(1,1,1);
        $response = $this->getJson('/api/articles/24/like');
        // lets hit the url for article comment and see if we were able to fetch multiple comments 
        $response->assertStatus(200)->assertJsonStructure(
            [   "status",
                "message",
                "data"
            ]
        )->assertJsonFragment(
            [
                "message"=> "Likes fetched",
                "status" => "success"
            ]
        );
        
     }

     public function generateArticleAndComments($numberOfArticles,$numberOfComments,$numberOfTags){
        $this->faker = Faker::create();
        \App\Models\Article::factory($numberOfArticles)->create()
        ->each(function ($u) use($numberOfComments,$numberOfTags)  {
            // save both comments and tags
            $u->comment()->saveMany(\App\Models\Comment::factory($numberOfComments)->make([
                "article_id" => $u->id,
            ]));

            $u->tag()->saveMany(\App\Models\Tag::factory($numberOfTags)->make([
                "article_id" => $u->id,
            ]));
        });
     } 
}
