<?php

namespace App\Http\Controllers;

use AmoCRM\Client\AmoCRMApiClient;
use Client\ApiClient;
use Illuminate\Http\Request;
use League\OAuth2\Client\Token\AccessToken;

class AmoCRM extends Controller
{
	public function test() {
		// define('TOKEN_FILE', $_SERVER['DOCUMENT_ROOT'].'/token_info_new.json');
		// $clientId = '828adff5-ce83-4f59-8f3b-6e93b9388ee5';
		// $clientSecret = '0XekCFhLKvJ6DpUD0XOzvnsiyORbn3dCredHp5Y1awzbiOo2kjV8LjCjTkizSl8C';
		// $redirectUri = 'https://loca.ru/';
		// //$apiClient = new AmoCRMApiClient($clientId, $clientSecret, $redirectUri);
		// $apiClient = new ApiClient($clientId, $clientSecret, $redirectUri, 'tbhas2');
		// //$apiClient->setAccountBaseDomain('tbhas2.amocrm.ru');
		// //$accessToken = json_decode(file_get_contents(TOKEN_FILE), true);
		// $apiClient->setAccessToken();
		// $contactsService = $apiClient->contacts();
		// $arr = (array) $contactsService->all();
		// var_dump($arr);
	}
}
