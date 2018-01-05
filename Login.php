<?php
session_start();
include 'ProjectCommon/Header.php';
include 'ProjectCommon/Functions.php';

extract($_POST);
$loginError = $idError = $passwordError = "";
$idVal = $passwordVal = "";
if (isset($btnLogin)) {
    $validateSuccess = TRUE;
    // validate student id
    $idValidateSuccess = ValidateStudentId($studentId);
    if (!$idValidateSuccess){
        $idError = "Student ID cannot be blank";
        $validateSuccess = FALSE;
    }
    else {
        $idVal = $studentId;
    }
    
    //validate password
    $passwordValidate = ValidatePassword($password);
    if ($passwordValidate == "blank error"){
        $passwordError = "Password cannot be blank;";
        $validateSuccess = FALSE;
    }
    else{
        $passwordVal = $password;
    }
    if ($validateSuccess) {
        $student = StudentLogin($studentId, $password);
        
        if ($student != NULL){
           
            $_SESSION["loginStudent"] = serialize($student);
             if (isset($_SESSION['attemptAccessPage'])) {
                $accesslink = "location:".$_SESSION['attemptAccessPage'];
                $_SESSION['attemptAccessPage'] = NULL;
                header($accesslink);
                
            }
            else{
                header("location:CourseSelection.php");
            }
            
            exit();
        }
        else{
            $loginError = "Incorrect student ID and/or Password!";
        }
    }
}
?>
<div class="login-page">
    <h1 class="login-title">Log In</h1>
    <p style="padding-left: 70px">You need to <a href="NewUser.php">sign up</a> if you are a new user</p>
    <span class="loginError"><?php echo $loginError ?></span>
    <form action="Login.php" role="form" method="post">
        <div class="row horizontal-margin vertical-margin">
            <div class ="col-sm-2 label-padding highlight">Student ID:</div>
            <div class = "col-sm-2">
                <input type = "text" class = "form-control" name = "studentId" placeholder="Enter studentId" <?php echo "value = '$idVal'" ?> style="background-color:lightyellow"/>
            </div>
            <div class="error col-sm-6"><?php echo $idError ?></div>
        </div>
        
        <div class="row horizontal-margin vertical-margin">
            <div class ="col-sm-2 label-padding highlight">Password:</div>
            <div class = "col-sm-2">
                <input type = "password" class = "form-control" name = "password" placeholder="Confirm password" <?php echo "value = '$passwordVal'"  ?> style="background-color:lightyellow"/>
            </div>
            <div class="error col-sm-6"><?php echo $passwordError ?></div>
        </div>
        <br/>
        <div class="row  h-margin v-margin">
            <div class="col-sm-1"><button type = "submit" name = "btnLogin" class = "btn btn-success" >Submit</button></div>
            <div class="col-sm-1"><button type ="submit" class = "btn btn-warning" name = "btnclear">Clear</button></div>
        </div>
    </form>
</div>
<?php include 'ProjectCommon/Footer.php' ?>
