<?php
define("LOGINFILE", "login.ini");

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of LoginDataModel
 *
 * @author Mark Rustad
 */
class LoginDataModel {
    
    private $login;
    private $loginPDO;
    private $prep_stmt;

    const USERNAME = "user.name";
    const PASSWORD = "pass.word";
    const LOGIN_FORM = "login.form";
    const USER_KEY = "username";
    const FX_CALC = "fxCalc.php";
    const LOGIN_BTN = "login";
 
    const HANDLE = "db.handle";
    const DBUSER = "db.user";
    const DBPW = "db.pw";
    
    const SEL_STMT= "select.stmt";
    const BIND_USER=":username";
     
    public function __construct() {
       
        $this->login = parse_ini_file(LOGINFILE);
        
        try{
        $this->loginPDO = new PDO($this->login[self::HANDLE],$this->login[self::DBUSER],$this->login[self::DBPW]);
        $this->loginPDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        catch(PDOException $ex){
            $ex->getMessage();    
        }

        $this->prep_stmt=$this->loginPDO->prepare($this->login[self::SEL_STMT]);
        
    }
    
    public function getLoginIni(){
        return $this->login;
    }
    
    public function validateUser($username, $password){

        $this->prep_stmt->bindParam(self::BIND_USER, $username);
        if($this->prep_stmt->execute() && $row = $this->prep_stmt->fetch()){
            if($row[$this->login[self::PASSWORD]]===$password){
                return true;
            }
        }
       
    }
      
    public function __destruct() {
        $this->loginPDO=NULL;
    }
    
}