<?php
namespace View;

use \View\ResourceObject;

class JSONAPI {
    private $data;
    private $included;

    public function __construct($json = null) {
        if ($json != null) $this->set($json);
        else $this->init();   
    }

    private function init() {
        $this->data = new ResourceObject();
    }

    public function get() {
        $json = array();
        foreach($this as $k => $v) {
            if (is_a($v, ResourceObject::class)) {
                $json[$k] = $v->get();
            } else {
                foreach($v as $ro){
                    // var_export($ro->get());
                    $json[$k][] = $ro->get();
                }
            }
        }
        return $json;
    }

    public function set($json) {
        if (isset($json['data'][0])) {
            foreach ($json['data'] as $d) {
                $this->data[] = new ResourceObject($d);
            }
        } else {
            $this->data = new ResourceObject($json['data']);
        }
        foreach ($json['included'] as $i) {
            $this->included[] = new ResourceObject($i);
        }
    }


    // helper functions
    public function addData($data) {
        if (!is_a($data, ResourceObject::class)) {
            $data = new ResourceObject($data);
        }
        if (is_a($this->data, ResourceObject::class)) {
            $this->data = array();
        }
        $this->data[] = $data;
    }

    public function addIncluded($included) {
        if (!is_a($included, ResourceObject::class)) {
            foreach ($included as $i) {
                $this->included[] = new ResourceObject($i);
            }
        } else {
            $this->included[] = $included;
        }
    }

    public function newItem() {
        return new ResourceObject();
    }

    // getters and setters
    public function getData() {
        return $this->data;
    }
    public function setData($data) {
        $this->data = $data;
    }

    public function getIncluded() {
        return $this->included;
    }
    public function setIncluded($included) {
        $this->includeds = $included;
    }
}