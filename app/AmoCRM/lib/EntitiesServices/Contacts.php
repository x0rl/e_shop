<?php
namespace EntitiesServices;

use Models\Contact;

class Contacts extends BaseEntity 
{
    protected $method = 'api/v4/contacts';
    protected $model = 'Models\Contact';
}