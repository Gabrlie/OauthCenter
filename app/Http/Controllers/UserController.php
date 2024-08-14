<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /*
     * 注册
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request): \Illuminate\Http\JsonResponse
    {
        $vaildator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'type' => 'required|string',
            'password' => 'required|string|min:6',
        ]);

        if ($vaildator->fails()) {
            return $this->error($vaildator->errors(), 422);
        }

        $user = User::create($request->only('name', 'email', 'password'));
        $user->type = $request->input('type');
        $user->save();

        return $this->success('创建成功');
    }

    /*
     * 登录
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request): \Illuminate\Http\JsonResponse
    {
        $vaildator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if ($vaildator->fails()) {
            return $this->error($vaildator->errors(), 422);
        }

        if (auth()->attempt($request->only('email', 'password'))) {
            $user = auth()->user();
            // 检查用户权限
            $type = $user->type;
            if ($type == 'admin' || $type == 'developer') {
                $token = $user->createToken('token')->accessToken;

                return $this->success([
                    'user' => $user,
                    'token' => $token,
                ]);
            }
            return $this->error('用户权限不足', 403);
        }

        return $this->error('用户名或密码错误', 401);
    }

    /*
     * 用户信息
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function info(Request $request): \Illuminate\Http\JsonResponse
    {
        $user = $request->user('api');
        if (!$user) {
            return $this->error('用户不存在', 404);
        }
        return $this->success($user);
    }

    /*
     * 退出登录
     * @param $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->user()->token()->revoke();
        return $this->success('退出成功');
    }

    public function list(Request $request)
    {
        $request->validate([
            'current' => 'integer|nullable',
            'pageSize' => 'integer|nullable',
        ]);

        $current = $request->input('current', 1);
        $pageSize = $request->input('pageSize', 10);

        // 查询用户列表
        $users = User::paginate($pageSize, ['*'], 'page', $current);

        // 返回用户列表和分页信息
        return $this->success([
            'data' => $users->items(),
            'current' => $users->currentPage(),
            'pageSize' => $users->perPage(),
            'total' => $users->total(),
        ]);

    }

    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'name' => 'string',
            'email' => 'email',
            'password' => 'string',
            'type' => 'string',
        ]);
        $user = User::find($request->id);
        if ($request->name) {
            $user->name = $request->name;
        }
        if ($request->email) {
            $user->email = $request->email;
        }
        if ($request->type) {
            $user->type = $request->type;
        }
        if ($request->password) {
            $user->password = Hash::make($request->password);
        }
        $user->save();
        return $this->success(['msg' => '更新成功']);
    }

    public function delete(Request $request)
    {
        // 数据为id列表
        $request->validate([
            'id' => 'required|array',
        ]);
        $ids = $request->input('id');
        User::destroy($ids);
        return $this->success(['msg' => '删除成功']);
    }

    public function userInfo(Request $request)
    {
        $request->validate([
            'access_token' => 'required|string',
        ]);

        $accessToken = $request->input('access_token');
        $user = User::where('oauth_access_token', $accessToken)->first();
        if (!$user) {
            return $this->error('用户不存在', 404);
        }
        return $this->success($user);
    }
}
