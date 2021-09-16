<?php
namespace EntitiesServices;

use Models\Company;

class Companies extends BaseEntity 
{
    protected $method = 'api/v4/companies';
    protected $model = 'Models\Company';
}