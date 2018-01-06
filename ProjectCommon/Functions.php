<?php
include_once 'ProjectClass.php';
date_default_timezone_set("America/Toronto");
function ValidateUserId($id) {
    return $id != NULL? TRUE:FALSE;
}

function ValidateUserName($name){
   return $name !=NULL? TRUE:FALSE;
}

function ValidatePhone($phone) {

    $phoneRegExp = "/^[2-9][0-9][0-9]-[2-9][0-9][0-9]-[0-9]{4}$/";
    if (preg_match($phoneRegExp, $phone)) {
        return TRUE;
    } else {
        return FALSE;
    }
}

function ValidateTitle($title){
    return $title != NULL? TRUE:FALSE;
}

function ValidatePassword($password) {
    $passwordError = "";
    if ($password == NULL) {
        $passwordError = "blank error";
        return $passwordError;
    }
    if(strlen($password) < 6){
        $passwordError = "length error";
        return $passwordError;
    }
    $containsUpperCase = preg_match('/[A-Z]/', $password);
    $containsLowerCase = preg_match('/[a-z]/', $password);
    $containDigitNumber = preg_match('/\d/', $password);
    if (!$containsUpperCase || !$containsLowerCase || !$containDigitNumber){
        $passwordError = "format error";
    }
    return $passwordError;
}

function ValidatePasswordMatch($password1, $password2) {
    return $password1 == $password2 ? TRUE:FALSE;
}
// connect SQL server function, it returns a connection object
function ConnectSQLServer(){
    $dbConnection = parse_ini_file("db_connection.ini");
    extract($dbConnection);
    $myPdo = new PDO($dsn, $user, $password);
    return $myPdo;
}

// save a new user in User table
function SaveUserRecord($userId, $userName, $phoneNumber, $Password) {
       
    $myPdo = ConnectSQLServer();
    $sqlStatement = 'SELECT * FROM User WHERE UserId= :userId';
    $pStmt = $myPdo->prepare($sqlStatement);
    $pStmt->execute(['userId'=>$userId]);
    foreach ($pStmt as $row) {
        if ($row['UserId'] != NULL) {
            return FALSE;
        }
    }
    $hashedPassword = sha1($Password);
    $insertStudent = "INSERT INTO User VALUES(:id, :name, :phoneNumber, :password)";
    $pSignUp = $myPdo->prepare($insertStudent);
    $pSignUp->execute(['id'=>$userId, 'name'=>$userName, 'phoneNumber'=>$phoneNumber, 'password'=>$hashedPassword]);
    return TRUE;
}

// user login function
function UserLogin($userId, $loginPassword) {
    $hashedLoginPassword = sha1($loginPassword);
    $myPdo = ConnectSQLServer();
    $sqlStatement = 'SELECT * FROM User WHERE UserId= :userId AND Password= :password';
    $pStmt = $myPdo->prepare($sqlStatement);
    $pStmt->execute(['userId'=>$userId, 'password'=>$hashedLoginPassword]);
    $row = $pStmt->fetch(PDO::FETCH_ASSOC);
     if ($row) {
          $loginUser = new User($row['UserId'], $row['Name'], $row['Phone']);
          return $loginUser;
     }
     else {
         return NULL;
     }
    
}

 // get all albums create by a user
function GetMyAlbum($userId) {
    $myAlbums = array();
    $myPdo = ConnectSQLServer();
    $getAlbumStatement = 'SELECT * FROM Album WHERE Owner_Id= :ownerId';
    $pStmt = $myPdo->prepare($getAlbumStatement);
    $pStmt->execute(['ownerId'=>$userId]);
    foreach ($pStmt as $row) {
        $album = new Album($row['Album_Id'], $row['Title'], $row['Date_Updated'], $row['Owner_Id'], $row['Accessibility_Code']);
        array_push($myAlbums, $album);
    }
    if (sizeof($myAlbums) != 0){
        return $myAlbums;
    }
    else {
        return NULL;
    }
}
  // get the number of pictures from an album
function getNumberOfPicturesForAlbum($albumId) {
    $myPdo = ConnectSQLServer();
    $getPicutresNumberStatement = 'SELECT COUNT(Album_Id) AS NumberOfPictures FROM Picture WHERE Album_Id = :albumId';
    $pStmt = $myPdo->prepare($getPicutresNumberStatement);
    $pStmt->execute(['albumId'=>$albumId]);
    foreach ($pStmt as $row){
        if ($row['NumberOfPictures'] != NULL){
            return $row['NumberOfPictures'];
        }
    }
}

// update accessbility code in an album
function UpdateAccessbilityCode($albumId, $code) {
    $myPdo = ConnectSQLServer();
    $updateAccessbilityStatement = 'UPDATE Album SET Accessibility_Code= :code WHERE Album_Id= :albumId';
    $pStmt = $myPdo->prepare($updateAccessbilityStatement);
    $pStmt->execute(['code'=>$code, 'albumId'=>$albumId]);
}

// delete pictures belong to an album
function DeleteAlbumPictures($albumId) {
    $myPdo = ConnectSQLServer();
    $deleteAlbumPicturesStatement = 'DELETE FROM Picture WHERE Album_Id = :albumId';
    $pStmt = $myPdo->prepare($deleteAlbumPicturesStatement);
    $pStmt->execute(['albumId'=>$albumId]);
}

// delete a specified album
function DeleteAlbum($albumId, $ownerId) {
    DeleteAlbumPictures($albumId);
    $myPdo = ConnectSQLServer();
    $removeAlbumStatement = 'DELETE FROM Album WHERE Album_Id = :albumId AND Owner_Id = :ownerId';
    $pStmt = $myPdo->prepare($removeAlbumStatement);
    $pStmt->execute(['albumId'=>$albumId, 'ownerId'=>$ownerId]);
}

// get accessbility codes from Accessibility table
function getAccessCodeFromAccessibility(){
    $codes = array();
    $myPdo = ConnectSQLServer();
    $sqlStatement = 'SELECT * FROM Accessibility';
    $pStmt = $myPdo->prepare($sqlStatement);
    $pStmt->execute();
    foreach($pStmt as $row) {
        $code = new Accessibility($row['Accessibility_Code'], $row['Description']);
        array_push($codes, $code);
    }
    if (sizeof($codes) != 0){
        return $codes;
    }
    else {
        return NULL;
    }
}

// add a new Album to database
function addNewAlbum($title, $description, $ownerId, $accessCode){
    $updateDate = date("Y-m-d");
    $myPdo = ConnectSQLServer();
    $addNewAlbumStatement = 'INSERT INTO Album(Title, Description, Date_Updated, Owner_Id, Accessibility_Code) VALUES (:title, :description, :dateUpdated, :ownerId, :accessCode)';
    $pStmt = $myPdo->prepare($addNewAlbumStatement);
    $pStmt->execute(['title'=>$title, 'description'=>$description, 'dateUpdated'=>$updateDate, 'ownerId'=>$ownerId, 'accessCode'=>$accessCode]);
}

