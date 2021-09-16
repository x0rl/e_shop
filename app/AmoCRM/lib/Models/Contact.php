<?php
namespace Models;

class Contact extends BaseModel 
{
    public $id;
    public $name;
    public $first_name;
    public $last_name;
    public $resposible_user_id;
    public $group_id;
    public $created_by;
    public $updated_by;
    public $created_at;
    public $updated_at;
    public $closest_task_at;
    public $is_deleted;
    public $is_unsorted;
    public $custom_fields_values = [];
}