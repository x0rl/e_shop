<?php

namespace App\Http\Controllers;

use AmoCRM\Client\AmoCRMApiClient;
use App\Mail\Test;
use App\Models\Token;
use Client\ApiClient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
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
		//$apiClient = new ApiClient;
		//$apiClient->setAccessTokenByCode('def502007dd6c8063edd77dfde14724064c6aead3f41ea3e58c087257d5e3e938ce1b5e689c5b9e8acc4bd1b0c14636340a01ae15b0421c354793df3aad370ce3f08bc292a9ea23406a13b2f85850648435db11c35495dbe863d1bd2b753e93d6cad0c153a974c673c49f830c4dfca85052acd68c4529238d2a34fd54488db6b92be51eb172be06aaf2ec09f3f226172129c7245bd95a22713807450d926a0bde2aa54e186036f5636a090f7a9bfaf135f4fafb97bb9f5b83950b5abfcf7d97200bd3ef3ff264f92c44a85b5f2a32ca87ccbd6f82bea377f07b75f4cad4664c4863cbeccd0221900c658730f3905b102bee64c96d94af69e39f0189f13a7f54bdca4a78fd5ae4f8cb7f0777f9a50ecef7a017cf167d63f7e35375d8d6586fc2fe1803b1ad3501d60a1ce5319a7cf546d3df30dcd7fd2f4806183b5f446282e799bf2b01e1b5554aee64ca8779b65b35428fdb9072519f7383c86bd9ac3a0dd6eef6826e6aa43ea55dc561fcbfaffc8e1643dda3908973700b70ad5c1b0ebe25923e0f6c116110cc754cce619fa085a20db937688a46f8c2fb1d963414736ba987cbcf0d1e1eca1f6c9fec5f002e1f05b14b3cdc68b505315a35e6c');
		//var_dump($apiClient->contacts()->all());
		//return var_dump(json_decode(Token::findOrFail(1)->token, true));
		Mail::to('tbhas@mail.ru')->send(new Test());
		return '1';
	}
}
