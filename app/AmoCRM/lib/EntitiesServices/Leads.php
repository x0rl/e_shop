<?php
namespace EntitiesServices;

use Client\ApiRequest;
use EntitiesServices\BaseEntity;
use Models\Lead;

class Leads extends BaseEntity 
{
  protected $apiRequest;
  protected $accessToken;
  protected $method = 'api/v4/leads';
  protected $entity = 'leads';

  public function __construct($accessToken) 
  {
    $this->apiRequest = new ApiRequest;
    $this->accessToken = $accessToken;
    $this->curlParams = array(CURLOPT_HTTPHEADER => [
      'Authorization: Bearer ' . $this->accessToken['access_token'],
      "Content-Type: application/json"
    ]);
  }
  public function getEntity() 
  {
    return $this->entity;
  }
  public function getBaseDomain() 
  {
    return $this->accessToken['base_domain'];
  }
  public function getMethod() 
  {
    return $this->method;
  }
  public function get(int $id, string $with = null) 
  {
    if ($with)
      $method = $this->method . "/$id?with=$with";
    else
      $method = $this->method . "/$id";
    $request = $this->apiRequest->sendRequest($method, $this->getCurlParams(), $this->getBaseDomain());
    return new Lead(json_decode($request));
    //return json_decode($request);
  }
}