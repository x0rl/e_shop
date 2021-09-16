<?php
namespace EntitiesServices;

use Models\Lead;

class Leads extends BaseEntity 
{
    protected $method = 'api/v4/leads';
    protected $model = 'Models\Lead';
}