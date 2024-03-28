<?php

namespace App\Controllers\v1;

use App\Controllers\v1\BaseController;
use CodeIgniter\API\ResponseTrait;
use App\Models\UserModel;
use Stevenmaguire\OAuth2\Client\Provider\Keycloak;

class UserController extends BaseController
{
    use ResponseTrait;
    
    /**
     * [POST] /api/v1/user/login
     * 使用者登入
     * 
     */
    public function login()
    {
        // 建立 Keycloak 提供者實例
        $provider = new Keycloak([
            'authServerUrl'         => 'https://keycloak.sdpmlab.org/auth',
            'realm'                 => 'ZT',
            'clientId'              => 'exp_userservice',
            'clientSecret'          => 'UrPnxgyzZj1waUnF0wYYupUB87pafbcW',
            'redirectUri'           => 'https://gitlab.sdpmlab.org/'
        ]);

        // 取得授權 URL
        $authUrl = $provider->getAuthorizationUrl();

        // 導向 Keycloak 登入頁面
        return redirect()->to($authUrl);
    }


    /**
     * [GET] /api/v1/user
     * 使用者驗證與取得使用者資訊
     * 
     */
    public function verify()
    {
        // 取得 Authorization 標頭
        $authorizationHeader = $this->request->getHeader("Authorization");

        // 檢查 Authorization 標頭是否存在
        if (!$authorizationHeader) {
            return $this->respond(['error' => 'No token provided.'], 401);
        }

        $accessToken = null;

        // 檢查標頭是否包含 Bearer token
        if (preg_match('/Bearer\s(\S+)/', $authorizationHeader->getValue(), $matches)) {
            $accessToken = $matches[1];
        } else {
            return $this->respond(['error' => 'Invalid authorization header.'], 401);
        }

        // 使用 Keycloak PHP Adapter 解析並驗證令牌
        try {
            $keycloak = new Keycloak([
                'authServerUrl' => 'https://keycloak.sdpmlab.org/auth',
                'realm' => 'ZT',
                'clientId' => 'exp_userservice',
                'clientSecret' => 'UrPnxgyzZj1waUnF0wYYupUB87pafbcW',
                'redirectUri' => 'https://testuserservice.sdpmlab.org/'
            ]);
            $token = new \League\OAuth2\Client\Token\AccessToken([
                'access_token' => $accessToken,
            ]);
            $resourceOwner = $keycloak->getResourceOwner($token);
            $decodedToken = $resourceOwner->toArray();
        } catch (\Exception $ex) {
            return $this->respond(['error' => 'Invalid token provided.'], 401);
        }

        $response = [
            'message' => 'Token is valid',
            'data' => $decodedToken
        ];

        return $this->respond($response, 200);
    }
}
