<?php

namespace Client;

use Client\ApiRequest;
use EntitiesServices\Contacts;
use EntitiesServices\Leads;
use EntitiesServices\Companies;
use App\Models\Token;

class ApiClient 
{
  private $clientId;
  private $secretKey;
  private $redirectURI;
  public $accessToken;
  private $baseDomain;
  private $token_file;
  
  private $apiRequest;

  public function __construct ()
  {
    $this->clientId = '828adff5-ce83-4f59-8f3b-6e93b9388ee5';
    $this->secretKey = '0XekCFhLKvJ6DpUD0XOzvnsiyORbn3dCredHp5Y1awzbiOo2kjV8LjCjTkizSl8C';
    $this->redirectURI = 'https://loca.ru/';
    $this->baseDomain = 'tbhas2';
    $this->token_file = $_SERVER['DOCUMENT_ROOT'].'/token_info_new.json';
    $this->apiRequest = new ApiRequest();
    $this->accessToken = json_decode(Token::findOrFail(1)->token, true);
    if ($this->accessToken['expires_in'] <= time())
      $this->refreshToken();
  }
  public function leads() 
  {
    return new Leads($this->accessToken);
  }
  public function contacts() 
  {
    return new Contacts($this->accessToken);
  }
  public function companies() 
  {
    return new Companies($this->accessToken);
  }
  public function setAccountBaseDomain(string $name) 
  {
    $this->baseDomain = $name;
  }
  public function setAccessTokenByCode($code)
  {
    $data = [
      'client_id' => $this->clientId,
      'client_secret' => $this->secretKey,
      'grant_type' => 'authorization_code',
      'code' => $code,
      'redirect_uri' => $this->redirectURI
    ];
    $params = array(
      CURLOPT_USERAGENT => 'amoCRM-oAuth-client/1.0',
      CURLOPT_HTTPHEADER => ['Content-Type:application/json'],
      CURLOPT_HEADER => false,
      CURLOPT_CUSTOMREQUEST => 'POST',
      CURLOPT_POSTFIELDS => json_encode($data)
    );
    $method = 'oauth2/access_token';
    $newAccessToken = json_decode($this->apiRequest->sendRequest($method, $params, $this->baseDomain), true);
    $newAccessToken['base_domain'] = $this->baseDomain;
    $this->accessToken = $newAccessToken;
    $this->saveToken();
  }
  public function getAccessToken() 
  {
    return $this->accessToken;
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
    $token = json_decode($this->apiRequest->sendRequest('oauth2/access_token', $params, $this->baseDomain), true);
    $token['base_domain'] = $this->baseDomain;
    $this->accessToken = $token;
    $this->saveToken();
  }
  private function saveToken()
  {
    $data = [
      'access_token' => $this->accessToken['access_token'],
      'expires_in' => time() + $this->accessToken['expires_in'],
      'refresh_token' => $this->accessToken['refresh_token'],
      'base_domain' => $this->accessToken['base_domain'],
    ];
    $tokenFromBD = Token::find(1);
    $tokenFromBD->token = $data;
    $tokenFromBD->save();
  }
}