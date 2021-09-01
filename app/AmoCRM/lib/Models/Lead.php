<?php
namespace Models;

class Lead extends BaseModel
{
  public $id;
  public $name;
  public $price = 0;
  public $responsible_user_id = 0;
  public $group_by;
  public $status_id;
  public $pipeline_id;
  public $loss_reason_id;
  public $created_by = 0;
  public $updated_by = 0;
  public $created_at = 0;
  public $updated_at = 0;
  public $closed_at = null;
  public $closest_task_at = null;
  public $is_deleted = false;
  public $custom_fields_values = [];
  public $scope = null;
  public $account_id;
  public $_links = null;
  public $_embedded = [];
  public $customFields = [];

  public function attachContact ($id, bool $isMain = false)
  {
    //$this->_embedded->contacts[0]->id = $id;
    $this->_embedded['contacts'][0]['id'] = $id;
    //$this->_embedded->contacts[0]->is_main = $isMain;
    $this->_embedded['contacts'][0]['is_main'] = $isMain;
    return $this;
  }
}