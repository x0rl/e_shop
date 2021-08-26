<?php
namespace EntitiesServices;

use Client\ApiRequest;
use Models\Contact;

class Contacts extends BaseEntity 
{
  protected $apiRequest;
  protected $accessToken;
  protected $method = 'api/v4/contacts';
  protected $entity = 'contacts';

  public function __construct($accessToken) 
  {
    $this->apiRequest = new ApiRequest;
    $this->accessToken = $accessToken;
    $this->curlParams = array(CURLOPT_HTTPHEADER => [
      'Authorization: Bearer ' . $this->accessToken['access_token'],
      "Content-Type: application/json"
    ]);
  }
  public function isContactExists($fieldId, $query)
  {
    //$method = "$this->method?query=";
    //766597
    $method = "api/v3/contacts?filter[$fieldId]=$query";
    return json_decode($this->apiRequest->sendRequest($method, $this->getCurlParams(), $this->getBaseDomain()), true);
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
    // ['catalog_elements', 'contacts']
    if ($with)
      $method = $this->method . "/$id?with=$with";
    else
      $method = $this->method . "/$id";
    $request = $this->apiRequest->sendRequest($method, $this->getCurlParams(), $this->getBaseDomain());
    //return var_dump(json_decode($request));
    return new Contact(json_decode($request));
  }
}