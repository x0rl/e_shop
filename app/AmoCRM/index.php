<?php

  namespace App\AmoCRM;

  use Client\ApiClient;
  use Models\Company;
  use Models\Lead;

  define('TOKEN_FILE', 'token_info_new.json');
  $clientId = '828adff5-ce83-4f59-8f3b-6e93b9388ee5';
  $secretKey = 'yl3u0DLb77kIIRz1IrBhF436Gcj1cTWeezXIdldymv2tMIr9kACEjqCs9Wjf0ofC';
  $redirectURI = 'https://loca.ru/';

  $client = new ApiClient($clientId, $secretKey, $redirectURI);
  $client->setAccountBaseDomain('tbhas2'); // default - tbhas

  //для первичного получения токена
  // $token = $client->getAccessTokenByCode('def50200db2a1d56dd5a0135e723ea490ad6b4c021cf81c6026ad997af7f0a9bba4740fa66d5544cdcf18cd2d4527de63be4fb90e2a871983c8f6c870cea03e622ad823ea80a86844fd263cc08103ee038412d6334f51b68e955cd492ddefb77c98f37ee6bae569ddb8da4258ca48bdd9551560f6dde204c11cf0cdd2c32e8923d00520a8fdfee96dc218dc9fdb67a639fe39becc6b2efa4a16f84472623864051e0fe92bbaa48204ab22ee1c6a4aec5f957a791d6864a8ae556166ad847ff070ab4a520fd4736fd118bd046b4c12ea012d4d7a6886e2046327540f9380fdb79b2e469fbf05bdea3a89845438a964ba376e434a31d54f856cd02eba41500ab9a36f2884fee71b1d2b80b558a3ef040551246be2bcff7eee0ac853ba0ed4ae57f806c15f5486b5f95bc770f51a15b1f3c8bfdf2925d71ad59c6bd24c0510be5920772293e3ad9c591f72d601cdf2c010fcaeb8195dfde156e7ccb4f72142db5a5fc7f852260d2617f76960e327fb43118d7725b8ace9856b3ad7cf37d5dc13be5fdb00f0075d81fc57f80a027bd982826f9faf4d95b2016c35b65858926f66b78a0fddc263e3e0443c1db31f27a4b4d8f47f72917211ab041afc881');
  // var_dump($token);
  // saveToken($token);

  $client->onAccessTokenRefresh(
    function($accessToken) 
    {
      saveToken([
        'access_token' => $accessToken['access_token'],
        'refresh_token' => $accessToken['refresh_token'],
        'expires_in' => $accessToken['expires_in'],
        'base_domain' => $accessToken['base_domain']
      ]);
    }
  )->setAccessToken(getToken());
  $leadsService = $client->leads();
  $contactsService = $client->contacts();
  $companiesService = $client->companies();

  $company = new Company();
  $company->name = 'Новая компания';
  $company = $companiesService->add($company);
  //контакт 3283161. 652199 мультисписок ремонт / диагностика
  //652469 прост поле
  //5292663 Контакт. 661725 мультисписок yes / no. 661727 прост поле
  
  //создание новой задачи
  $lead = new Lead();
  $lead->setName('Диагностика');
  $lead->addCustomField(['id' => '652199', 'value' => 'Диагностика']);
  $lead->addCustomField(['id' => '652469', 'value' => 'Просто текстик']);
  $lead->price = 5000;
  $lead = $leadsService->add($lead);
  $leadsService->addNote($lead['id'], 'Текстовое примечание');
  $leadsService->addTask($lead['id'], 'ТЕКСТ НОВОЙ ЗАДАЧИ СРОЧНО', 30 * 60);

  //найдем созданную задачу, изменим кастомное поле
  $lead = $leadsService->get($lead['id']);
  $lead->editCustomField(652469, 'Измененный текст в поле сделки');
  $leadsService->updateOne($lead);

  // те же методы и с другими сущностями (контакты, компании), например
  // Поиск и редактирование найденного контакта
  $contact = $contactsService->get(5292663);
  $contact->first_name = 'Дмитрий';
  $contact->editCustomField(661725, 'no!');
  $contact->editCustomField(661727, 'Отредактированный текст');
  $contact->last_name = 'Павлов';
  $contactsService->updateOne($contact);
  
  echo '<h1>well done</h1>';
  function saveToken($accessToken)
  {
    if (
      isset($accessToken)
      && isset($accessToken['access_token'])
      && isset($accessToken['refresh_token'])
      && isset($accessToken['expires_in'])
      && isset($accessToken['base_domain'])
    ) {
      $data = [
        'access_token' => $accessToken['access_token'],
        'expires_in' => time() + $accessToken['expires_in'],
        'refresh_token' => $accessToken['refresh_token'],
        'base_domain' => $accessToken['base_domain'],
      ];

      file_put_contents(TOKEN_FILE, json_encode($data));
    } else {
      exit('Invalid access token ' . var_export($accessToken, true));
    }
  }
  function getToken()
  {
    if (!file_exists(TOKEN_FILE)) {
      exit('Access token file not found');
    }

    $accessToken = json_decode(file_get_contents(TOKEN_FILE), true);

    if (
      isset($accessToken)
      && isset($accessToken['access_token'])
      && isset($accessToken['refresh_token'])
      && isset($accessToken['expires_in'])
      && isset($accessToken['base_domain'])
    ) {
      return [
        'access_token' => $accessToken['access_token'],
        'refresh_token' => $accessToken['refresh_token'],
        'expires_in' => $accessToken['expires_in'],
        'base_domain' => $accessToken['base_domain'],
        ];
    } else {
      exit('Invalid access token ' . var_export($accessToken, true));
    }
  }