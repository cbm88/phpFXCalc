<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->

<html>
    <head>
        <meta charset="UTF-8">
        <title>FX Calculator Login</title>
        <link href="loginStyleSheet.css" rel="stylesheet" type="text/css"/>
    </head>
    
    <body>
        <?php
        //Call the LoginDataModel Class
        require_once "LoginDataModel.php";
       
        //Initialize the Object of the LoginDataModel Class
        //Initialize the Variable to Retrieve the Login.INI File
        $fxlogin = new LoginDataModel();
        $usersIni = $fxlogin->getLoginIni();
        
        //Ensure the Username Key Appears in Login.INI and $_POST Superglobal
        if(array_key_exists(LoginDataModel::PASSWORD, $usersIni)){
            
            //Get Username and Password from the User
            $username= filter_input(INPUT_POST, LoginDataModel::USER_KEY);
            $password= filter_input(INPUT_POST, $usersIni[LoginDataModel::PASSWORD]);
            
            if(isset($_POST[LoginDataModel::LOGIN_BTN])){
            //Perform Error Handling
            //Execute User Login and Bind the Username to Session
                if(empty($username) || empty($password)){
                    echo "<script type='text/javascript'>alert('Username and/or Password Cannot Be Blank.')</script>";
                }
                else if(($fxlogin->validateUser($username, $password))==TRUE){
                    session_start();
                    $_SESSION[LoginDataModel::USER_KEY] = $username;
                    include LoginDataModel::FX_CALC;
                    exit();
                }
                else{
                    echo "<script type='text/javascript'>alert('Invalid Username and/or Password.')</script>";
                }
            }
        }
        
        ?>

    <header id="head">
        <h1>Money Banks F/X Calculator</h1>
        <hr>
    </header>        
       
        <div id="loginContainer">
        <form action= "" method="post" name="<?php echo $usersIni[LoginDataModel::LOGIN_FORM]?>">
            <p>*Username: <input type="text" name="<?php echo LoginDataModel::USER_KEY;?>" value="<?php if(isset($_POST[LoginDataModel::LOGIN_BTN])){echo $username;}?>"></p>
            <p>*Password: <input type="text" name="<?php echo $usersIni[LoginDataModel::PASSWORD]?>" value="<?php echo $password;?>"></p>
            <p><b>*Indicates required field.</b></p>
            <div id="buttonsDiv">
                <button name="<?php echo LoginDataModel::LOGIN_BTN;?>" id="loginButton">LOGIN</button> 
            <input type="reset" name="reset" value="RESET" onclick="window.location.href='login.php'" id="resetButton"/>
            </div>
        </form>
        </div>
        
    </body>
</html>