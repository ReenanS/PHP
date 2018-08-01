<?php
namespace Controller;

// Users
use \DBO\Users\TeacherDBO as Teacher;


class ModelController {
    private $db;
    public function __construct($db) {
        $this->db = $db;
    }
    // Use this class to declare all DBOs
    // This way it is not necessary to declare in any other place of the code
    // call classes using
    // $modelController->{'teacher'}() ou $modelController->teacher()

    public function teacher() {
        return new Teacher($this->db);        
    }

}