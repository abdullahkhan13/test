<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Comment;
use App\Models\Like;
use App\Models\Media;
use App\Models\Tag;
use App\Models\View;
use Faker\Factory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ArticlesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Factory::create();

        DB::transaction(function () use ($faker) {
            // Create 20 articles
            for ($i = 0; $i < 20; $i++) {
                $article = Article::create([
                    'title' => $faker->sentence,
                    'description' => $faker->paragraphs(1, true),
                ]);

                // Add random number of comments to the article
                $numComments = rand(1, 10);
                for ($j = 0; $j < $numComments; $j++) {
                    Comment::create([
                        'visitor' => $faker->ipv4,
                        'articles_id' => $article->id,
                        'subject' => $faker->sentence,
                        'body' => $faker->paragraph,
                    ]);
                }

                // Add random number of likes to the article
                $numLikes = rand(1, 50);
                for ($j = 0; $j < $numLikes; $j++) {
                    Like::create([
                        'articles_id' => $article->id,
                        'visitor' => $faker->ipv4,
                        'status' => $faker->randomElement(['Like', 'Dislike']),
                    ]);
                }

                // Add media (file upload) to the article
                $media = Media::create([
                    'articles_id' => $article->id,
                    'file' => 'https://picsum.photos/200/300',
                ]);

                // Add random number of views to the article
                $numViews = rand(1, 100);
                for ($j = 0; $j < $numViews; $j++) {
                    View::create([
                        'articles_id' => $article->id,
                        'visitor' => $faker->ipv4,
                    ]);
                }

                $numViews = rand(1, 100);
                for ($j = 0; $j < $numViews; $j++) {
                    Tag::create([
                        'articles_id' => $article->id,
                        'tag' => $faker->sentence,
                    ]);
                }
            }
        });
    }
}
