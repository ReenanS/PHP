<?php
namespace View;

class ResourceObject {
    private $type;
    private $id;
    private $attributes;
    private $relationships;

    public function __construct($json = null) {
        if ($json != null) $this->set($json);
    }

    public function get() {
        $json = array();
        foreach($this as $k => $v) {
            if (isset($v)) {
                if ($k == 'relationships') {
                    foreach ($v as $rK => $rV) {
                        $json[$k][$rK]['data'] = $rV['data']->get();
                    }
                }
                else $json[$k] = $v;
            }
        }
        return $json;
    }

    public function set($json) {
        foreach($json as $k => $v) {
            $this->$k = $v;
        }
    }

    // getters and setters
    public function getType() {
        return $this->type;
    }
    public function setType($type) {
        $this->type = $type;
    }

    public function getId() {
        return $this->id;
    }
    public function setId($id) {
        $this->id = $id;
    }

    public function getAttributes() {
        return $this->attributes;
    }
    public function setAttributes($attributes) {
        $this->attributes = $attributes;
    }

    public function getRelationships() {
        return $this->relationships;
    }
    public function setRelationships($relationships) {
        $this->relationships = $relationships;
    }
    public function setRelationshipsData($data) {
        $this->relationships['data'] = $data;
    }
    public function addRelationshipsData($data) {
        $this->relationships['data'][] = $data;
    }
    public function addRelationships($data, $type=null) {
        if (!is_a($data, ResourceObject::class)) {
            $data = new ResourceObject($data);
        }
        $type = $type ?? $data->getType();

        if (!isset($this->relationships[$type])) {
            $this->relationships[$type]['data'] = $data;
        } else {   
            if (is_a($this->relationships[$type]['data'], ResourceObject::class)) {
                $d = $this->relationships[$type]['data'];
                $this->relationships[$type]['data'] = array($d);
            }
            $this->relationships[$type]['data'][] = $data;
        }
    }
}