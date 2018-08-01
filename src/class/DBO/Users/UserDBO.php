<?php
namespace DBO\Users;
use \DBO\DBO;

class UserDBO extends DBO {
    private $created;
    private $modified;

    public $uid;
    public $type;
    
    public function __construct($db) {

        // example: see AddressDBO class

        parent::__construct($db);
        $this->setTableName("user");
        $this->setType("user");
        $this->setRelations(["address","teacher"]);

        $this->userType = array(
            0 => 'admin',
            1 => 'teacher',
            2 => 'student'
        );
    }

    // getter and setter
    public function getType() {
        return $this->typeId2Str($this->type);
    }
    public function setTypeByKey($type) {
        $this->type = $this->typeStr2Id($type);
    }
    public function setTipoById($type) {
        $this->type = $type;
    }

    public function typeId2Str($type) {
        return $this->userType[$type];
    }
    private function typeStr2Id($type) {
        return array_search($type, $this->userType);
    }

    public function getUID() {
        return $this->uid;
    }
    public function setUID($uid) {
        $this->uid = $uid;
    }

}
