<?php
include_once 'ProjectClass.php';
function ValidateUserId($id){
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

function ValidatePassword($password){
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

function ValidatePasswordMatch($password1, $password2){
    return $password1 == $password2 ? TRUE:FALSE;
}

function SaveUserRecord($userId, $userName, $phoneNumber, $Password){
    
    $dbConnection = parse_ini_file("db_connection.ini");
    extract($dbConnection);
    $myPdo = new PDO($dsn, $user, $password);
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

function UserLogin($userId, $loginPassword){
    $hashedLoginPassword = sha1($loginPassword);
    $dbConnection = parse_ini_file("db_connection.ini");
    extract($dbConnection);
    $myPdo = new PDO($dsn, $user, $password);
    $sqlStatement = 'SELECT * FROM User WHERE UserId= :userId AND Password= :password';
    $pStmt = $myPdo->prepare($sqlStatement);
    $pStmt->execute(['userId'=>$userId, 'password'=>$hashedLoginPassword]);
    foreach ($pStmt as $row){
        
        if ($row['UserId'] != NULL) {
            $loginUser = new User($row['UserId'], $row['Name'], $row['Phone']);
            return $loginUser;
        }
    }
    return NULL;
}

