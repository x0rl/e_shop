<?php
namespace EntitiesServices;

use Client\ApiRequest;

abstract class BaseEntity
{
  protected $apiRequest;
  protected $curlParams;

  abstract function getMethod();
  abstract function getBaseDomain();
  abstract function getEntity();

  public function __construct(ApiRequest $request) 
  {
    $this->apiRequest = $request;
  }
  public function getCurlParams() 
  {
    return $this->curlParams;
  }
  public function all() 
  {
    $request = $this->apiRequest->sendRequest($this->getMethod(), $this->getCurlParams(), $this->getBaseDomain());
    return json_decode($request, true);
  }
  public function get(int $id, string $with = null) 
  {
    // ['catalog_elements', 'contacts']
    if ($with)
      $method = $this->method . "/$id?with=$with";
    else
      $method = $this->method . "/$id";
    $request = $this->apiRequest->sendRequest($method, $this->getCurlParams(), $this->getBaseDomain());
    return json_decode($request);
  }
  public function add($object) 
  {
    $customCurlParams = array(
      CURLOPT_HEADER => false,
      CURLOPT_CUSTOMREQUEST => 'POST',
      CURLOPT_POSTFIELDS => json_encode($object->toApi())
    );
    $request = $this->apiRequest->sendRequest($this->getMethod(), $customCurlParams + $this->getCurlParams(), $this->getBaseDomain());
    return json_decode($request, true)['_embedded'][$this->getEntity()][0];
  }
  public function getNote($entityId) 
  {
    $request = $this->apiRequest->sendRequest("api/v4/".$this->getEntity()."/$entityId/notes", $this->getCurlParams(), $this->getBaseDomain());
    return json_decode($request, true);
  }
  public function addNote(int $entityId, string $text) 
  {
    $data = [
      [
        'note_type' => 'common',
        'params' => [
          'text' => $text
        ]
      ]
    ];
    $customCurlParams = array(
      CURLOPT_HEADER => false,
      CURLOPT_CUSTOMREQUEST => 'POST',
      CURLOPT_POSTFIELDS => json_encode($data)
    );
    $request = $this->apiRequest->sendRequest("api/v4/".$this->getEntity()."/$entityId/notes", $this->getCurlParams() + $customCurlParams, $this->getBaseDomain());
    return json_decode($request, true);
  }
  public function addTask($entityId, $taskText, $completeSeconds) 
  {
    $data = [
      [
        'entity_id' => $entityId,
        'entity_type' => $this->entity,
        'text' => $taskText,
        'complete_till' => time() + $completeSeconds
      ]
    ];
    $customCurlParams = array(
      CURLOPT_HEADER => false,
      CURLOPT_CUSTOMREQUEST => 'POST',
      CURLOPT_POSTFIELDS => json_encode($data)
    );
    $request = $this->apiRequest->sendRequest('/api/v4/tasks', $this->getCurlParams() + $customCurlParams, $this->getBaseDomain());
    return json_decode($request);
  }
  public function updateOne($data) 
  {
    foreach ($data->custom_fields_values as $key => $value) { // крч нулл в объекте рисует когда его получаю, а обратно его же принимать НЕ ХОЧЕТ
      foreach ($value->values as $item) {
        if (property_exists($item, 'enum_code'))
          unset($item->enum_code);
      }
    }
    //echo '<h1>updateOne $data</h1>';
    //var_dump($data);
    $customCurlParams = array(
      CURLOPT_HEADER => false,
      CURLOPT_CUSTOMREQUEST => 'PATCH',
      CURLOPT_POSTFIELDS => json_encode([$data])
    );
    //var_dump($data->custom_fields_values[0]->values);
    //var_dump($customCurlParams);
    $request = $this->apiRequest->sendRequest($this->getMethod(), $customCurlParams + $this->getCurlParams(), $this->getBaseDomain());
    return json_decode($request, true);
  }
}