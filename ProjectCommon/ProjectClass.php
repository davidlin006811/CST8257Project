<?php

class User{
    private $id;
    private $name;
    private $phone;
    
    public function __construct($id, $name, $phone){
        $this->id = $id;
        $this->name = $name;
        $this->phone = $phone;
        $this->courses = array();
    }
   
    public function getId(){
        return $this->id;
    }
    public function getName(){
        return $this->name;
    }
    public function getPhone() {
        return $this->phone;
    }
}
