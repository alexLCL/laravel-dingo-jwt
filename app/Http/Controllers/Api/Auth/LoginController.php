<?php

namespace App\Http\Controllers\Api\Auth;

use App\Models\User;
use Dingo\Api\Routing\Helpers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\UnauthorizedException;
use Tymon\JWTAuth\Facades\JWTAuth;

class LoginController extends Controller
{
    use AuthenticatesUsers;
    use Helpers;


    /**
     * Notes:登录
     * User: Administrator
     * Date: 2019/7/17
     * Time: 11:50
     * @param Request $request
     * @throws \Illuminate\Validation\ValidationException
     */
    public function login(Request $request){

        /*
         * 按照email去数据表查找
         */
        $user = User::where('email',$request->email)
            ->orWhere('name',$request->email)->first();

        /*
         * 利用Hash::check()验证密码，如果成功，返回结果
         */
        if($user && Hash::check($request->get('password'), $user->password)){
            $token = JWTAuth::fromUser($user);
            return $this->sendLoginResponse($request,$token);
        }

        /*
         * 返回失败结果
         */
        return$this->sendFailedLoginResponse($request);
    }

    /**
     * Notes:登陆成功时的返回函数
     * User: Administrator
     * Date: 2019/7/17
     * Time: 11:52
     * @param Request $request
     * @param $token
     * @return mixed
     */
    public function sendLoginResponse(Request $request,$token)
    {
        $this->clearLoginAttempts($request);

        return $this->authenticated($token);
    }


    /**
     * Notes:登录失败时的返回函数
     * User: Administrator
     * Date: 2019/7/17
     * Time: 11:53
     * @param Request $request
     */
    public function sendFailedLoginResponse(Request $request)
    {
        throw new UnauthorizedException('bad credentials');
    }

    /**
     * Notes:登录成功时返回的数据格式
     * User: Administrator
     * Date: 2019/7/17
     * Time: 11:53
     * @param $token
     * @return mixed
     */
    public function authenticated($token)
    {
        return $this->response->array([
            'token'=>$token,
            'status_code'=>200,
            'message'=>'验证成功'
        ]);
    }

    /**
     * 退出
     */
    public function logout()
    {
        $this->guard()->logout();
    }
}
