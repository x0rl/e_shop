<?php
namespace Client;

class ApiRequest 
{
  public function sendRequest(string $path, array $params, string $accountDomain)
  {
    $curl = curl_init();
    curl_setopt($curl,CURLOPT_URL, 'https://' . $accountDomain . '.amocrm.ru/' . $path);
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

    try
    {
      /** Если код ответа не успешный - возвращаем сообщение об ошибке  */
      if ($code < 200 || $code > 204) {
        echo 'error khm. '.$errors[$code].' '.$code;
      }
    }
    catch(\Exception $e)
    {
      die('Ошибка: ' . $e->getMessage() . PHP_EOL . 'Код ошибки: ' . $e->getCode());
    }
    return $out;
  }
}