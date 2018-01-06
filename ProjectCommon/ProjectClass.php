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
class Album{
    private $id;
    private $title;
    private $updateDate;
    private $numberOfPictures;
    private $code;    
    private $owenId;


    public function __construct($id, $title, $updateDate, $owenId, $code) {
        $this->id = $id;
        $this->title = $title;
        $this->updateDate = $updateDate;
        $this->owenId = $owenId;
        $this->code = $code;
    }
    
    public function setPictureNumbers($pictureNumber){
        $this->numberOfPictures = $pictureNumber;
    }
    
     public function getAlbumId(){
        return $this->id;
    }
    public function getTitle(){
        return $this->title;
    }
    
    public function getDate(){
        return $this->updateDate;
    }
    
    public  function getOwenId(){
        return $this->owenId;
    }
    public function getCode(){
        return $this->code;
    }

    public function getNumberOfPictures(){
        return $this->numberOfPictures;
    }
}

class Picture {
    private $fileName;
    private $id;
    
    public static function getPictures() {
        $pictures = array();
        $files = scandir(ALBUM_THUMBNAILS_DIR);
        $numFiles = count($files);
        if ( $numFiles > 2 ) {
            for ($i = 2; $i < $numFiles; $i++) {
                $ind = strripos($files[$i], "/");
                $fileName = substr($files[$i], $ind);
                $picture = new Picture($fileName, $i);
                $pictures[$i] = $picture;
            }
        }
        return $pictures;
    }
    
    public function __construct($fileName, $id) {
        $this->fileName = $fileName;
        $this->id = $id;
    }
    
    public function getId() {
        return $this->id;
    }
    
    public function getName() {
        $ind = strripos($this->fileName, '.');
        $name = substr($this->fileName, 0, $ind);
        return $name;
    }
    
    public function getAlbumFilePath() {
        return ALBUM_PICTURES_DIR."/".$this->fileName;
    }
    
    public function getThumbnailFilePath() {
        return ALBUM_THUMBNAILS_DIR."/".$this->fileName;
    }
    
    public  function getOriginalFilePath() {
        return ORIGINAL_PICTURES_DIR."/".$this->fileName;
    }
}

class Accessibility {
    private $code;
    private $description;
    
    public function __construct($code, $description) {
        $this->code = $code;
        $this->description = $description;
    }
    public function getCode(){
        return $this->code;
    }
    public function getDescription(){
        return $this->description;
    }
}
