<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Like;
use App\Models\View;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class ArticleController extends Controller
{

    /**
     * @OA\Get(
     *     path="/api/articles",
     *     operationId="listArticles",
     *     tags={"Articles"},
     *     summary="List all articles",
     *     description="Returns a list of all articles",
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="object",
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="message",
     *                 type="string"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="message",
     *                 type="string"
     *             )
     *         )
     *     )
     *   )
     * )
     */

    public function list() {
        $articles = Article::orderBy('id', 'desc')->paginate(10);

        return $articles;
    }

    /**
     * @OA\Get(
     *     path="/api/articles/{id}",
     *     operationId="getArticleById",
     *     tags={"Articles"},
     *     summary="Get a specific article by ID",
     *     description="Returns a specific article based on ID",
     *     @OA\Parameter(
     *         name="id",
     *         description="ID of the article to retrieve",
     *         required=true,
     *         in="path",
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="object",
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="message",
     *                 type="string"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="message",
     *                 type="string"
     *             )
     *         )
     *     )
     *   )
     * )
     */

    public function getById($articleId)
    {
        $article = Article::where('id', $articleId)
            ->with(['likes', 'views', 'comments', 'tags', 'media'])
            ->first();

        return $article;
    }

    /**
     * @OA\Get(
     *     path="/api/articles/{id}/like",
     *     summary="Get the likes of an article",
     *     description="Get the likes of an article by ID",
     *     operationId="getArticleLikesById",
     *     tags={"Articles"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the article to retrieve likes for",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="object",
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="message",
     *                 type="string"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="message",
     *                 type="string"
     *             )
     *         )
     *     )
     *   )
     * )
     */

    public function likes($articleId)
    {
        Like::firstOrCreate(
            ['visitor' => $_SERVER['REMOTE_ADDR'], 'articles_id' => $articleId],
            ['status' => 'Like', 'created_at' => now(), 'updated_at' => now()]
        );

        $result = Cache::remember('likes_count', 1, function () use ($articleId) {
            return DB::table('likes')->where('status', 'Like')->where('articles_id', $articleId)->count();
        });

        return $result;
    }

    /**
     * @OA\Get(
     *     path="/api/articles/{id}/comment",
     *     summary="Get comments for an article",
     *     description="Returns the comments for a specific article by ID",
     *     tags={"Articles"},
     *     @OA\Parameter(
     *         name="id",
     *         description="ID of article to get comments for",
     *         required=true,
     *         in="path",
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="object",
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="message",
     *                 type="string"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="message",
     *                 type="string"
     *             )
     *         )
     *     )
     *   )
     * )
     */

    public function comments($articleId)
    {
        $result = Cache::remember('comments', 1, function () use ($articleId) {

            return DB::table('comments')->where('articles_id', $articleId)->get();

        });

        return $result;
    }

    /**
     * @OA\Get(
     *      path="/api/articles/{id}/view",
     *      operationId="views",
     *      tags={"Articles"},
     *      summary="Get the views of a specific article",
     *      description="Returns the number of views for a specific article",
     *      @OA\Parameter(
     *          name="id",
     *          description="Article ID",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer",
     *              format="int64"
     *          )
     *      ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="object",
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="message",
     *                 type="string"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="message",
     *                 type="string"
     *             )
     *         )
     *     )
     *   )
     * )
     */

    public function views($articleId)
    {
        View::firstOrCreate(
            ['visitor' => $_SERVER['REMOTE_ADDR'], 'articles_id' => $articleId],
            ['created_at' => now(), 'updated_at' => now()]
        );

        $result = Cache::remember('views', 1, function () use ($articleId) {
            return DB::table('views')->where('articles_id', $articleId)->count();
        });

        return $result;
    }
}
