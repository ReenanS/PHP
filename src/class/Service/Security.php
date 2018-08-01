<?php
namespace Service;

use \DBO\Users\UserDBO as User;

class Security {
    private $db;
    private $user;
    private $controller;

    public function __construct($db, $id = null) {
        $this->user = new User($db);
    }
    
    public function userLogged($uid) {
        // TODO
        return true;
    } 

    public function validateUser($uid) {
        return ($this->user->getUID() == $uid);
    }
    
     // Generic way of giving the access control directly to the object that is going to be accessed
     // Parameters:
     // itemId = id of the item you want to access
     // itemType = type of item you want to access
     // method = access method to daos database
    public function userAccess($itemId, $itemType, $method) {
        $dbo = $this->controller->{$itemType}();
        return $dbo->allowAccess($this->user->getId(), $this->user->getType(), $itemId, $method);
    }

    // Specific resources
    public function adminOnly($userId) {
        return ($this->user->getType() == 'admin');
    }

    public function teacherOnly($id) {
        return ($this->user->getType() == 'teacher');
    }

    public function studentOnly($id) {
        return ($this->user->getType() == 'student');
    }

}