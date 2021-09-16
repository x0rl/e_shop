<?php
namespace Client;

use App\Models\Token;
use EntitiesServices\Contacts;
use EntitiesServices\Leads;
use EntitiesServices\Companies;

class ApiClient 
{
    private $clientId;
    private $secretKey;
    private $redirectURI;
    private $accessToken;
    private $baseDomain;

    public function __construct ($code = null) 
    {
        $this->clientId = config('amoCRM.clientId');
        $this->secretKey = config('amoCRM.secretKey');
        $this->redirectURI = config('amoCRM.redirectURI');
        $this->baseDomain = config('amoCRM.baseDomain');
        if ($token = Token::find(1)) {
            $this->accessToken = $token->token;
        } else {
            if ($code == null) {
                abort(404);
            } else {
                $this->setAccessTokenByCode($code);
            }
        }
    }
    public function leads() 
    {
        return new Leads($this);
    }
    public function contacts() 
    {
        return new Contacts($this);
    }
    public function companies() 
    {
        return new Companies($this);
    }  
    public function setAccessTokenByCode($code)
    {
        $data = [
            'client_id' => $this->clientId,
            'client_secret' => $this->secretKey,
            'grant_type' => 'authorization_code',
            'code' => $code,
            'redirect_uri' => $this->redirectURI,
        ];
        $params = array(
            CURLOPT_USERAGENT => 'amoCRM-oAuth-client/1.0',
            CURLOPT_HTTPHEADER => ['Content-Type:application/json'],
            CURLOPT_HEADER => false,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($data)
        );
        $method = 'oauth2/access_token';
        $requestOutput = $this->sendRequest($method, $params, $this->baseDomain);
        $newAccessToken = json_decode($requestOutput, true);
        $this->accessToken = $newAccessToken;
        $this->saveToken();
    }
    public function getAccessToken() 
    {
        if ($this->accessToken['expires_in'] <= time()) {
            $this->refreshToken();
        }
        return $this->accessToken;
    }
    public function setAccessToken($accessToken) 
    {
        $this->accessToken = $accessToken;
        return $this;
    }
    public function refreshToken() 
    {
        $data = [
            'client_id' => $this->clientId,
            'client_secret' => $this->secretKey,
            'grant_type' => 'refresh_token',
            'refresh_token' => $this->accessToken['refresh_token'],
            'redirect_uri' => $this->redirectURI,
        ];
        $params = [
            CURLOPT_USERAGENT => 'amoCRM-oAuth-client/1.0',
            CURLOPT_HTTPHEADER => ['Content-Type:application/json'],
            CURLOPT_HEADER => false,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($data)
        ];
        $token = json_decode($this->sendRequest('oauth2/access_token', $params, $this->baseDomain), true);
        $this->accessToken = $token;
        $this->saveToken();
    }
    private function saveToken() 
    {
        $data = [
            'access_token' => $this->accessToken['access_token'],
            'expires_in' => time() + $this->accessToken['expires_in'],
            'refresh_token' => $this->accessToken['refresh_token']
        ];
        $token = Token::find(1);
        $token->token = $data;
        $token->save();
    }
    public function sendRequest(string $path, array $params)
    {
        $curl = curl_init();
        curl_setopt($curl,CURLOPT_URL, 'https://' . $this->baseDomain . '.amocrm.ru/' . $path);
        curl_setopt($curl,CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl,CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl,CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt_array($curl, $params);
        $out = curl_exec($curl);
        //var_dump($out);
        $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);
        $code = (int)$code;
        $errors = [
            400 => 'Bad request',
            401 => 'Unauthorized',
            403 => 'Forbidden',
            404 => 'Not found',
            500 => 'Internal server error',
            502 => 'Bad gateway',
            503 => 'Service unavailable',
        ];

        try {    
            /** Если код ответа не успешный - возвращаем сообщение об ошибке  */
            if ($code < 200 || $code > 204) {
                echo 'error khm. '.$errors[$code].' '.$code;
            }
        } catch(\Exception $e) {
            die('Ошибка: ' . $e->getMessage() . PHP_EOL . 'Код ошибки: ' . $e->getCode());
        }
        return $out;
    }
}