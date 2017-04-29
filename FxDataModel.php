<?php
define("FXINI", "fxCalc.ini");

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of FXDataModel
 *
 * @author Mark Rustad
 */
class FxDataModel {
    
    //Form Keys
    const SOURCEKEY = 'src.cucy';
    const DESTINATIONKEY = 'dst.cucy';
    const AMOUNT = 'src.amt';
    const DESTAMOUNT = 'dst.amt';
    const CLASSKEY = 'FxDataModel.php';
    const LOGINPAGE = 'login.php';
    const FORM_NAME = "fxCalc.php";
    const CONVERT_BTN = 'convert';
    const LOGOUT_BTN = 'logout';
    
    //PDO Keys
    const HANDLE = "db.handle";
    const DBUSER = "db.user";
    const DBPW = "db.pw";
    const SEL_RATE_STMT = 'rate.stmt';
    const RATE_ROW = 'rate.row';
    
    //Class Variables
    private $fxCurrencies;
    private $fxRates;
    private $fxIni;
    private $rate_stmt;
   
    function __construct() {
       $this->fxIni = parse_ini_file(FXINI); 
       try{
       $fxPDO = new PDO($this->fxIni[self::HANDLE], $this->fxIni[self::DBUSER], $this->fxIni[self::DBPW]);
       $fxPDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
       }
       catch(PDOException $ex){
           $ex->getMessage();
       }
       $this->rate_stmt = $fxPDO->prepare($this->fxIni[self::SEL_RATE_STMT]);
       $this->rate_stmt->execute();
       while ($result = $this->rate_stmt->fetch()){
           $srcCucy = $result['srcCucy'];
           $this->fxCurrencies[] = $srcCucy;
           $dstCucy = $result["dstCucy"];
           $rate = $result["fxRate"];
           $this->fxRates[$srcCucy.$dstCucy] = $rate;
       }
       $this->rate_stmt->closeCursor();
       $fxPDO=null;
    }
    
    public function getIniArray(){
        return $this->fxIni;
    }
    
    public function getFxCurrencies(){
        return $this->fxCurrencies;
    }
    
    public function getFxRates($srcCucy,$dstCucy){
        if($srcCucy===$dstCucy){
            return 1;
        }
        else{
            return $this->fxRates[$srcCucy.$dstCucy];
        }
    }
}