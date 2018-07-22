<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    /**
     * API ENDPOINT: GET /ips/authors
     *
     * TODO: переписать на many to many отношения и переделать структуру таблицы, сделать таблицу user_ips
     * Но сейчас я написал через sql просто потому, что могу
     *
     *
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function listGroupByIp(){
        $ips = DB::table('posts')
            ->select('user_ip')
            ->groupBy('user_ip')
            ->havingRaw('COUNT(DISTINCT user_id) > 1');
        $userId = DB::table('posts')
            ->select('user_id', 'posts.user_ip')
            ->joinSub($ips, 'ips', function($join) {
                $join->on('posts.user_ip', '=', 'ips.user_ip');
            });
        $users = DB::table('users')
            ->select('login', 'posts.user_ip')
            ->joinSub($userId, 'posts', function($join){
                $join->on('users.id', '=', 'posts.user_id');
            })
            ->get();

      $ipCollection = $users->groupBy('user_ip', true);
      foreach ($ipCollection as &$ip){
          foreach ($ip as &$user){
              unset($user->user_ip);
          }
      }
      if($ipCollection->count()) return response()->json($ipCollection, self::OK);

      return response()->json(null, self::NO_DATA);
    }
}
