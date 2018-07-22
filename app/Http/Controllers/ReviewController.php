<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddReview;
use App\Models\Post;
use Illuminate\Support\Facades\DB;

class ReviewController extends Controller
{
    /**
     * Add a review to the existing post. Insert, select and update queries are wrapped into transaction because db
     * denormalization is used (field avg_rating presence). Transaction tries to be commited for 5 times
     * then the exception will be thrown.
     *
     * API ENDPOINT: POST /post/{post}/review
     *
     * REQUIRED request POST fields:
     *  integer(1-5) |  rating
     *
     * @param Post $post
     * @param AddReview $request
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\JsonResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function add(Post $post, AddReview $request){
       try {
           DB::transaction(function () use (&$post,$request) {
               $post->reviews()->create($request->only('rating'));

               //Get the avg rating after new value added
               $rating = $post->reviews()->avg('rating');

               //Update post record, and set a new value to our denormalized field avg_rating
               $post->update(['avg_rating' => $rating]);

           }, 5);
       } catch (\Exception $e){
           return response(['error' => trans('validatoin.save_failed')], self::TEMP_UNAVAILABLE);
       }

       return response()->json([
           'post_id' => $post->id,
           'avg_rating' => $post->avg_rating,
       ], self::CREATED);
    }
}
