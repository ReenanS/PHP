<?php
namespace DBO\Users;
use \DBO\DBO;
use \DBO\Users\UserDBO as User;

class TeacherDBO extends DBO {
    private $created;
    private $modified;

    public $user;

    public $email; 
    public $name; 
    public $lastname;
    
    public function __construct($db) {
        parent::__construct($db);
        $this->setTableName("teacher");
        $this->setType("teacher");
        $this->setFK(["user", "example"]);

        // tables that have relation like this
        // these tables have a teacher column which is an FK (Foreign key) for those tables
        $this->getRelations(["discipline"]);
    }

    protected function getSQL() {
        // call the original parent function
        // use when you do not want to do a full override
        $cols = parent::getSQL();
        // dates should be with "
        $cols['date_birth'] = '"'.$this->formatDate($this->date_birth).'"';
        $cols['date_issue'] = '"'.$this->formatDate($this->date_issue).'"';
        return $cols;
    }

}