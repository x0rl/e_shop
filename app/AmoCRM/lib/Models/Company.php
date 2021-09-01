<?php
namespace Models;

class Company extends BaseModel 
{
  public $id;
  public $name;
  public $responsible_user_id;
  public $group_id;
  public $created_by;
  public $updated_by;
  public $created_at;
  public $updated_at;
  public $closest_task_at;
  public $is_deleted;
  public $custom_fields_values = [];
  public $account_id;
  public $_links;
  public $_embedded;
}