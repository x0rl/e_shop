<?php
namespace EntitiesServices;

use Client\ApiClient;

abstract class BaseEntity
{
    protected $apiClient;
    protected $curlParams;

    public function __construct(ApiClient $apiClient) 
    {
        $this->apiClient = $apiClient;
        $this->curlParams = array(CURLOPT_HTTPHEADER => [
            "Content-Type: application/json"
        ]);
    }
    public function getModel() 
    {
        return $this->model;
    }
    public function getMethod()
    {
        return $this->method;
    }
    public function getEntity()
    {
        $class_parts = explode('\\', get_class($this));
        return lcfirst(end($class_parts));
    }
    public function getCurlParams() 
    {
        return $this->curlParams;
    }
    public function all() 
    {
        $customCurlParams = array(CURLOPT_HTTPHEADER => [
            'Authorization: Bearer ' . $this->apiClient->getAccessToken()['access_token']
        ]);
        $request = $this->apiClient->sendRequest($this->getMethod(), $customCurlParams + $this->getCurlParams());
        return json_decode($request, true);
    }
    public function get(int $id, string $with = null) 
    {
        if ($with) {
            $method = $this->method . "/$id?with=$with";
        } else {
            $method = $this->method . "/$id";
        }
        $customCurlParams = array(CURLOPT_HTTPHEADER => [
            'Authorization: Bearer ' . $this->apiClient->getAccessToken()['access_token']
        ]);
        $request = $this->apiClient->sendRequest($method, $customCurlParams + $this->getCurlParams());
        $model = $this->getModel();
        return new $model(json_decode($request));
    }
    public function add($object) 
    {
        $customCurlParams = array(
            CURLOPT_HEADER => false,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($object->toApi()),
            CURLOPT_HTTPHEADER => [
                'Authorization: Bearer ' . $this->apiClient->getAccessToken()['access_token']
            ]
        );
        $request = $this->apiClient->sendRequest($this->getMethod(), $customCurlParams + $this->getCurlParams());
        return json_decode($request, true)['_embedded'][$this->getEntity()][0];
    }
    public function updateOne($data) 
    {
        foreach ($data->custom_fields_values as $key => $value) { 
            foreach ($value->values as $item) {
                if (property_exists($item, 'enum_code')) {
                    unset($item->enum_code);
                }
            }
        }
        $customCurlParams = array(
            CURLOPT_HEADER => false,
            CURLOPT_CUSTOMREQUEST => 'PATCH',
            CURLOPT_POSTFIELDS => json_encode([$data]),
            CURLOPT_HTTPHEADER => [
                'Authorization: Bearer ' . $this->apiClient->getAccessToken()['access_token']
            ]
        );
        $request = $this->apiClient->sendRequest($this->getMethod(), $customCurlParams + $this->getCurlParams());
        return json_decode($request, true);
    }
    public function getNote($entityId) 
    {
        $customCurlParams = array(CURLOPT_HTTPHEADER => [
            'Authorization: Bearer ' . $this->apiClient->getAccessToken()['access_token']
        ]);
        $request = $this->apiClient->sendRequest(
            "api/v4/" . $this->getEntity() . "/$entityId/notes", 
            $customCurlParams + $this->getCurlParams()
        );
        return json_decode($request, true)['_embedded']['notes'][0];
    }
    public function addNote(int $entityId, string $text) 
    {
        $data = [
            [
                'note_type' => 'common',
                'params' => ['text' => $text]
            ]
        ];
        $customCurlParams = array(
            CURLOPT_HEADER => false,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => [
                'Authorization: Bearer ' . $this->apiClient->getAccessToken()['access_token']
            ]
        );
        $request = $this->apiClient->sendRequest(
            "api/v4/" . $this->getEntity() . "/$entityId/notes",
            $this->getCurlParams() + $customCurlParams
        );
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
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => [
                'Authorization: Bearer ' . $this->apiClient->getAccessToken()['access_token']
            ]
        );
        $request = $this->apiClient->sendRequest('/api/v4/tasks', $this->getCurlParams() + $customCurlParams);
        return json_decode($request);
    }
}