<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class GitHubAuthController extends Controller
{
    protected $clientId;
    protected $clientSecret;
    protected $redirectUri;

    public function __construct()
    {
        $this->clientId = config('services.github.client_id');
        $this->clientSecret = config('services.github.client_secret');
        $this->redirectUri = config('services.github.redirect');
    }

    public function redirectToProvider()
    {
        $url = "https://github.com/login/oauth/authorize?" . http_build_query([
                'client_id' => $this->clientId,
                'redirect_uri' => $this->redirectUri,
                'scope' => 'read:user user:email',
                'response_type' => 'code',
                'state' => csrf_token(),
            ]);

        return redirect($url);
    }

    public function handleProviderCallback(Request $request){

        $callbackUrl = 'http://127.0.0.1:8000/user/login';

        $client = new \GuzzleHttp\Client();

        try {
            $response = $client->get("https://github.com/login/oauth/access_token?client_id={$this->clientId}&client_secret={$this->clientSecret}&code={$request->code}");

            $decodedString = urldecode($response->getBody()->getContents());
            parse_str($decodedString, $data);

            // 如果返回值包含error_description字段，说明授权失败，输出错误信息并返回
            if (isset($data['error_description'])) {
                return redirect($callbackUrl.'?error='.$data['error_description']);
            }

            $accessToken = $data['access_token'];

            $userResponse = Http::withHeaders([
                'Authorization' => 'Bearer ' . $accessToken,
            ])->get('https://api.github.com/user');

            $emailResponse = Http::withHeaders([
                'Authorization' => 'Bearer ' . $accessToken,
            ])->get('https://api.github.com/user/emails');

        } catch (\Exception $e) {
            return redirect($callbackUrl.'?error='.$e->getMessage());
        }

        $githubUser = $userResponse->json();
        $githubEmail = collect($emailResponse->json())->firstWhere('primary', true)['email'];

        $user = User::updateOrCreate(
            ['email' => $githubEmail],
            ['name' => $githubUser['login']],
        );

        $user->avatar = $githubUser['avatar_url'];
        $user->save();

        return redirect($callbackUrl.'?token='.$user->createToken('token')->accessToken.'&id='.$user->id);
        }
}
