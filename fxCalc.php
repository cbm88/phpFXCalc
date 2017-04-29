<?php
    //Begin the Session if it Isn't Already Running
    if(!isset($_SESSION)){
        session_start();
    }
    require_once 'LoginDataModel.php';
    require_once 'FxDataModel.php';
    
    //If the Username Key Isn't Registered in the Session, Exit the Script and Redirect to Login.php
    if(!isset($_SESSION[LoginDataModel::USER_KEY])){
        include FxDataModel::LOGINPAGE;
        exit();
    }
    else if(session_status()==PHP_SESSION_ACTIVE){
        echo "<p style='position: absolute; bottom: 10px; right: 10px;'>You are logged in</p>";
    }
?>

<!DOCTYPE html>

<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->

<html>
    <head>
        <meta charset="UTF-8">
        <title>F/X Calculator</title>
        <link href="fxCalcStyle.css" rel="stylesheet" type="text/css"/>
    </head>
    
    <body>
        <?php
        require_once 'FxDataModel.php';
        
        //Initialize the Class Object
        if(!isset($_SESSION[FxDataModel::CLASSKEY])){
            $fxData = new FxDataModel();
        }
        
        //Initialize a Variable to Access the .INI File inside the FXDataModel Class
        //Initialize a Variable to Access the Currency Codes inside the FXDataModel Class           
        $fxIni = $fxData->getIniArray();
        $currencies = $fxData->getFxCurrencies();
        
        //Gather User Inputs from the TextField and Respective Dropdown Menus
        $srcAmt = filter_input(INPUT_POST, $fxIni[FxDataModel::AMOUNT], FILTER_VALIDATE_FLOAT);
        $srcCucy = filter_input(INPUT_POST, $fxIni[FXDataModel::SOURCEKEY]);
        $dstCucy = filter_input(INPUT_POST, $fxIni[FXDataModel::DESTINATIONKEY]);
        
        //Generate an Error Message Should Users Enter Invalid Data
        $error = "Please Enter a Valid Number and Continue.";
        
        //Perform Calculation
        $result = number_format($fxData->getFxRates($srcCucy, $dstCucy) * $srcAmt,2);

        //Perform Error Handling
        if(isset($_POST[FxDataModel::CONVERT_BTN])){
            if(!is_numeric($srcAmt) || empty($srcAmt)){
                $result = "";
                echo "<script type='text/javascript'>alert('$error');</script>";
            }
        }
        
        if(isset($_POST[FxDataModel::LOGOUT_BTN])){
            session_destroy();
            include FxDataModel::LOGINPAGE;
            exit();
        }
        
        ?>

        <header>
        <h1>Money Banks F/X Calculator</h1>
        <h4>Welcome <?php echo $_SESSION[LoginDataModel::USER_KEY]?></h4>
        </header>
        <hr>
        
        <input type="button" name="<?php echo FxDataModel::LOGOUT_BTN;?>" id="logoutButton" value="LOGOUT" onclick="window.location.href='login.php'"/>
        
        <div id="container">
            
            <form name="" action="<?php echo FxDataModel::FORM_NAME?>" method="post">
                
                <select name="<?php echo $fxIni[FxDataModel::SOURCEKEY]; ?>">
                <?php
                foreach($currencies as $fxCurrency)
                {
                ?>
                <option value="<?php echo $fxCurrency ?>"
                <?php
                if($fxCurrency === $srcCucy)
                {
                ?>
                selected
                <?php
                }
                ?>
                ><?php echo $fxCurrency ?></option>
                <?php
                }
                ?>
                </select>
                
                <input type="text" name="<?php echo $fxIni[FxDataModel::AMOUNT];?>" value="<?php 
                if(!is_numeric($srcAmt)){
                    echo " ";
                } 
                else{
                    echo $srcAmt;
                }
                ?>"/>
                
                <select name="<?php echo $fxIni[FxDataModel::DESTINATIONKEY]; ?>">
                <?php
                foreach($currencies as $newcurrency)
                {
                ?>
                <option value="<?php echo $newcurrency ?>"

                <?php
                if($newcurrency === $dstCucy)
                {
                ?>
                selected
                <?php
                }
                ?>
                ><?php echo $newcurrency ?></option>
                <?php
                }
                ?>
                </select>
                             
                <input type="text" readonly name="<?php echo $fxIni[FxDataModel::DESTAMOUNT];?>" id="outputText" value="<?php if(isset($_POST[FxDataModel::CONVERT_BTN])){
                echo $result;}?>"/>
             
                <input type="submit" name="<?php echo FxDataModel::CONVERT_BTN;?>" id="convertButton" value= "CONVERT"/>
                <!-- When the User Presses Reset, the User Will Be Redirected to the Default Version of FXCalc.php !-->
                <input type="reset" name="reset" id="resetButton" value= "RESET" onclick="window.location.href='fxCalc.php'"/>
                
            </form>
        </div>
        
    </body>
</html>