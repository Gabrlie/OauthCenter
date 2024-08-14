<?php

namespace App\Http\Controllers\Auth;

use App\Models\CheckedClient;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Client;
use Laravel\Passport\ClientRepository;
use Illuminate\Support\Facades\Auth;
use Laravel\Passport\Token;

class ClientController extends Controller
{
    public function list(Request $request)
    {
        $request->validate([
            'current' => 'integer|nullable',
            'pageSize' => 'integer|nullable',
        ]);

        // 获取当前页码，默认为 1
        $current = $request->input('current', 1);

        // 获取每页显示的记录数，默认为 10
        $pageSize = $request->input('pageSize', 10);

        // 分页查询
        $user = Auth::user();
        $clients = null;
        if ($user->type === 'admin') {
            $clients = Client::where('revoked', '!=', 1)
                ->paginate($pageSize, ['*'], 'page', $current);
        } else {
            $clients = Client::where('revoked', '!=', 1)
                ->where('user_id', $user->id)
                ->paginate($pageSize, ['*'], 'page', $current);
        }

        $req = $clients->map(function ($client) {
            $created_user = User::find($client->user_id);
            if (!$created_user) {
                $created_by = '';
            } else {
                $created_by = $created_user->name;
            }
            return [
                'id' => $client->id,
                'secret' => $client->secret,
                'created_by' => $created_by,
                'name' => $client->name,
                'redirect' => $client->redirect,
                'notes' => $client->notes,
                'created_at' => $client->created_at->format('Y-m-d H:i:s'),
                'updated_at' => $client->updated_at->format('Y-m-d H:i:s'),
            ];
        });

            // 返回用户列表和分页信息
        return $this->success([
            'data' => $req,
            'total' => $clients->total(),
            'current' => $clients->currentPage(),
            'pageSize' => $clients->perPage(),
        ]);
    }

    // 创建一个新的客户端
    public function create(Request $request)
    {
        // 验证字段
        $request->validate([
            'name' => 'required',
            'redirect' => 'required|url',
            'notes' => 'string',
        ]);

        if ($request->notes) {
            $notes = $request->notes;
        } else {
            $notes = '';
        }

        // 管理员可以直接创建客户端，而开发者需要等待审核
        if ($request->user('api')->type == 'admin') {
            $clientRepository = app(ClientRepository::class);
            $client = $clientRepository->create(
                $request->user('api')->id, $request->name, $request->redirect
            );

            $client->notes = $notes;
            $client->save();
        } else {
            $client = CheckedClient::create([
                'user_id' => $request->user('api')->id,
                'name' => $request->name,
                'redirect' => $request->redirect,
                'notes' => $notes,
            ]);
        }

        return $this->success($client);
    }

    // 更新客户端
    public function update(Request $request)
    {
        // 验证字段
        $request->validate([
            'id' => 'required|uuid', // 确保 id 是 UUID 格式
            'name' => 'required',
            'redirect' => 'required|url',
        ]);

        // 获取 Client 实例
        $clientRepository = app(ClientRepository::class);
        $client = $clientRepository->find($request->id);

        if (!$client) {
            return response()->json(['error' => 'Client not found'], 404);
        }

        // 更新 Client
        $updatedClient = $clientRepository->update(
            $client, $request->name, $request->redirect
        );

        if ($request->notes) {
            $updatedClient->notes = $request->notes;
        } else {
            $updatedClient->notes = '';
        }
        $updatedClient->save();

        return $this->success($updatedClient);
    }

    // 删除客户端
    public function delete(Request $request)
    {
        $request->validate([
            'id' => 'required|array',
            'id.*' => 'uuid',
        ]);
        // 获取 Client 实例
        $clientRepository = app(ClientRepository::class);
        foreach ($request->id as $id) {
            $client = $clientRepository->find($id);
            if ($client) {
                $clientRepository->delete($client);
            }
        }
        return $this->success(['msg' => '删除成功']);

    }

    // 审核客户端列表
    public function checkList(Request $request)
    {
        $request->validate([
            'current' => 'integer|nullable',
            'pageSize' => 'integer|nullable',
        ]);

        // 获取当前页码，默认为 1
        $current = $request->input('current', 1);

        // 获取每页显示的记录数，默认为 10
        $pageSize = $request->input('pageSize', 10);

        // 分页查询
        $user = Auth::user();
        $clients = null;
        if ($user->type === 'admin') {
            $clients = CheckedClient::paginate($pageSize, ['*'], 'page', $current);
        } else {
            $clients = CheckedClient::where('user_id', $user->id)
                ->paginate($pageSize, ['*'], 'page', $current);
        }

        $req = $clients->map(function ($client) {
            $created_user = User::find($client->user_id);
            if (!$created_user) {
                $created_by = '';
            } else {
                $created_by = $created_user->name;
            }
            return [
                'id' => $client->id,
                'created_by' => $created_by,
                'name' => $client->name,
                'redirect' => $client->redirect,
                'notes' => $client->notes,
                'checked' => $client->checked,
                'created_at' => $client->created_at->format('Y-m-d H:i:s'),
                'updated_at' => $client->updated_at->format('Y-m-d H:i:s'),
            ];
        });

        // 返回用户列表和分页信息
        return $this->success([
            'data' => $req,
            'total' => $clients->total(),
            'current' => $clients->currentPage(),
            'pageSize' => $clients->perPage(),
        ]);
    }

    // 更新客户端
    public function checkUpdate(Request $request)
    {
        // 验证字段
        $request->validate([
            'id' => 'required|uuid', // 确保 id 是 UUID 格式
            'checked' => 'required|int',
            'name' => 'required',
            'redirect' => 'required|url',
        ]);

        $checkedClient = CheckedClient::find($request->id);
        if (!$checkedClient) {
            return $this->error('客户端不存在', 404);
        }
        if ($checkedClient->checked === 2 || $checkedClient->checked === 1) {
            return $this->error('禁止修改该客户端', 404);
        }

        // 获取修改后的数据
        $name = $request->name;
        $redirect = $request->redirect;
        $notes = $request->notes;
        $checked = $request->checked;

        // 修改审核表中的客户端
        $checkedClient->name = $name;
        $checkedClient->redirect = $redirect;
        $checkedClient->notes = $notes;

        if ($request->user('api')->type === 'admin') {
            $checkedClient->checked = $checked;
            if ($request->checked === 1) {
                // 将客户端从审核表中移动到正式表中
                $clientRepository = app(ClientRepository::class);
                $client = $clientRepository->create(
                    $checkedClient->user_id, $name, $redirect
                );

                $client->notes = $notes;
                $client->save();
            }
        }
        $checkedClient->save();

        return $this->success($checkedClient);
    }

    public function index(Request $request)
    {
        $tokens = $request->user()->tokens->load('client');

        return view('dashboard.oauth-applications', [
            'tokens' => $tokens->filter(function ($token) {
                return $token->client->name !== 'Laravel Personal Access Client';
            }),
        ]);
    }

    public function revoke($tokenId)
    {
        $token = Token::findOrFail($tokenId);
        $this->authorize('delete', $token);

        $token->revoke();

        return redirect()->route('oauth.applications')->with('status', '授权已撤销');
    }

}
