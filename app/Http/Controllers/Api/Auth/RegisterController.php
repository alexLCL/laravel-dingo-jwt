<?php

namespace App\Http\Controllers\Api\Auth;

use App\Models\User;
use Dingo\Api\Routing\Helpers;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Dingo\Api\Exception\StoreResourceFailedException;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    use RegistersUsers;
    use Helpers;

    public function register(Request $request){

        /**
         * 数据验证步骤
         */
        $validator = $this->validator($request->all());
        if($validator->fails()){
            throw new StoreResourceFailedException('数据验证出错',$validator->errors());
        }

        /**
         *创建用户步骤
         */
        $user = $this->create($request->all());
        if($user->save()){
            $token = JWTAuth::fromUser($user);
            return $this->response->array([
                'token'=>$token,
                'message'=>'注册成功',
                'status_code'=>200
            ]);
        }else{
            return $this->response->errors('注册失败',404);
        }
    }

    /**
     * Notes:数据验证
     * User: Administrator
     * Date: 2019/7/17
     * Time: 11:26
     * @param array $data
     * @return \Illuminate\Contracts\Validation\Validator|\Illuminate\Validation\Validator
     */
    public function validator(array $data){
        return Validator::make($data,[
            'name'=>'required | unique:users',
            'email'=>'required | unique:users | email ',
            'password'=>'required | min:6 | max:10'
        ]);
    }

    /**
     * Notes:创建用户
     * User: Administrator
     * Date: 2019/7/17
     * Time: 11:27
     * @param array $data
     * @return mixed
     */
    public function create(array $data){
        return User::create([
            'name'=>$data['name'],
            'email'=>$data['email'],
            'password'=>bcrypt($data['password'])
        ]);
    }
}
