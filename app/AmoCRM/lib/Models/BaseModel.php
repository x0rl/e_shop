<?php
namespace Models;

abstract class BaseModel 
{
    public function __construct($object = null) 
    {
        if (isset($object)) {
            foreach ($object as $key => $item) {
                $this->$key = $item;
            }
        }
    }
    public function setId($id) 
    {
        $this->id = $id;
        return $this;
    }
    public function getId() 
    {
        return $this->id;
    }
    public function setName(string $name) 
    {
        $this->name = $name;
        return $this;
    }
    public function getName() 
    {
        return $this->name;
    }
    public function setResponsibleUserId (int $id) 
    {
        $this->responsible_user_id = $id;
        return $this;
    }
    public function getResponsibleUserId() 
    {
        return $this->responsible_user_id;
    }
    public function addCustomField(array $data) 
    {
        $result = [];
        $result['field_id'] = (int) $data['id'];
        $result['values'] = [['value' => $data['value']]];
        array_push($this->custom_fields_values, $result);
        return $this;
    }
    public function getCustomFields() 
    {
        return $this->customFields;
    }
    public function editCustomField(int $field_id, $newValue) 
    {
        foreach ($this->custom_fields_values as $key => $value) {
            if ($value->field_id == $field_id) {
                $this->custom_fields_values[$key]->values[0]->value = $newValue;
            }
        }
    }

    public function toApi() 
    {
        $result = [];
        foreach (get_object_vars($this) as $key => $item) {
            $item !== null ? $result[$key] = $item : false;
        }
        return [$result];
    }
}