<?php
namespace DBO\Users;
use \DBO\DBO;

// Example of a real class
// Address is a database table
class AddressDBO extends DBO {
    
    // private variables can be read or not,
    // but would never be exported
    // only visible by the DBO class
    private $created;
    private $modified;

    // public variables are visible to everyone
    // in the get action are exported as the class attributes
    public $user;

    public $cep; 
    public $type; 
    public $street; 
    public $number; 
    public $complement; 
    public $neighborhood; 
    public $city; 
    public $state; 
    public $country;
    
    public function __construct($db) {
        // call the parent constructor (hierarchy - extends)
        parent::__construct($db);

        // set the table name in the Database
        $this->setTableName("address");

        // set the class type to be exported in the JSON API
        // can be a different name from tbl to increase security
        // but in this case the controller would not be able to implement the reading functions in a generically current way
        $this->setType("address");

        // set the columns of the table that are foreign keys (FK)
        // so the table name has to be equal to the column name (sql naming convention)
        $this->setFK(["user"]);
    }
    
   
}

