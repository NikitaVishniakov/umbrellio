<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddPost;
use App\Models\Post;
use App\Models\User;
use App\Models\UserIp;
use Faker\Factory;

class PostController extends Controller
{

    /**
     * TOP-rated posts default quantity
     * @var int
     */
    private $defaultPostsCount = 5;


    /**
     * Create a new post & create a new author(user) is if not present.
     *
     * API ENDPOINT: POST /post
     *
     * REQUIRED request POST fields:
     *  string(255) |  header
     *  string      |  content
     *  string(255) |  login
     *
     * OPTIONAL POST fields:
     *  ipAddress   |  ip   // if missing, we would try to detect user's ip via incoming request
     *
     * @param AddPost $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function add(AddPost $request) {

        //Collecting array with the post data
        $arrPost = $request->only('header', 'content', 'user_ip');
        $user = $this->getUserInstance($request->only('login'));

        $ip_v = $request->input('user_ip') ?? $request->getClientIp();
        $ip = $this->getUserIpInstance($user, $ip_v);
        $arrPost['user_ip_id'] = $ip->id;

        //Trying to save post (may be some sql/db errors while saving)
        try {
            $post = $user->posts()->create($arrPost);
        } catch (\Exception $e){
            return response()->json([
                'message' => trans('validation.internal_error'),
                'error' => $e->getMessage(),
            ], self::INTERNAL_ERROR);
        }

        return response()->json($post->loadMissing(['user', 'user_ip']), self::CREATED);
    }


    /**
     * Retrieve N rop-rated posts (posts with the highest avg_rating field value)
     *
     * API ENDPOINT: GET /post/popular/{postsCount}
     *
     * @param null $postsCount
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\JsonResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function popular($postsCount = null){

        //If no post count is set - then using default popular posts' quantity
        if(!$postsCount){
            $postsCount = $this->defaultPostsCount;
        }

        try {
            $posts = Post::select('id', 'header', 'content', 'avg_rating')
                ->orderBy('avg_rating', 'desc')
                ->take($postsCount)
                ->get();
        } catch (\Exception $e){
            return response()->json([
                'message' => trans('validation.internal_error'),
                'error' => $e->getMessage(),
            ], self::INTERNAL_ERROR);
        }


        if($posts){
            return response()->json($posts,self::OK);
        }

        return response(null, self::NO_DATA);
    }

    /**
     * Get User obj from db or create a new one if there are no users with data provided
     *
     * @param array $userData
     *
     * @return User
     */
    private function getUserInstance(array $userData): User{
        return User::firstOrCreate($userData);
    }

    /**
     * Get UserIP obj from db or create a new one if there no records with data provided
     *
     * @param array $userData
     *
     * @return User
     */
    private  function getUserIpInstance(User $user, string $ip) : UserIp{
        return UserIp::firstOrCreate(['user_id' => $user->id, 'ip' => $ip]);
    }

}
