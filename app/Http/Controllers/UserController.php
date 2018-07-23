<?php

namespace App\Http\Controllers;

use App\Models\UserIp;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    /**
     * API ENDPOINT: GET /ips/authors
     *
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function listGroupByIp(){
        $ips = DB::table('user_ips')
            ->select('ip')
            ->groupBy('ip')
            ->havingRaw('COUNT(user_id) > 1');
        $userId = DB::table('user_ips')
            ->select('user_id', 'ips.ip')
            ->joinSub($ips, 'ips', function($join) {
                $join->on('user_ips.ip', '=', 'ips.ip');
            });
        $users = DB::table('users')
            ->select('id', 'login', 'u_ips.ip')
            ->joinSub($userId, 'u_ips', function($join){
                $join->on('users.id', '=', 'u_ips.user_id');
            })
            ->get();

      $ipCollection = $users->groupBy('ip');
      $arrResult = [];
      foreach ($ipCollection as $key => $ip){
          $tmp['ip'] = $key;
          foreach ($ip as $user){
              $tmp['authors'][] = ['id' => $user->id, 'login' => $user->login];
          }
          $arrResult['data'][] = $tmp;
      }

    if(!empty($arrResult)) return response()->json($arrResult, self::OK);

    return response()->json(null, self::NO_DATA);
    }
}
