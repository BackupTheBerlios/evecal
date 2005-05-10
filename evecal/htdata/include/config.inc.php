<?php
/**
 *
 * @author       Carola 'Sammy' Kummert <sammywg@gmx.de>
 * @package      Event Calendar
 * @subpackage   Configuration
 * @version      $Id: config.inc.php,v 1.1 2005/05/10 14:36:30 sammywg Exp $
 *
 */


// Misc
define('BASE_URL',      'http://www.example.com/');
define('PWD_GEN_LEN',   8);
define('HBR',           "<br />\n");
define('LANG',          fCnfGetLang());


// Includes
define('DSEP',          fCnfGetSep());
ini_set('include_path', '.' . fCnfGetSepIni() 
                        .$sBasePath 
                        .DSEP . 'htdata'
                        .DSEP . 'include');
define('INC',           '.inc.php');
      

// Logging
define('LOG_DIR',       realpath($sBasePath . DSEP .'htlogs') . DSEP);
define('LOG_STDFILE',   'appl_error.log');


// Templating
define('LANG_DIR',      realpath($sBasePath . DSEP . 'htdata' . DSEP . 'lang') 
                        .DSEP);
define('TPL_DIR',       realpath($sBasePath . DSEP . 'htdata' . DSEP . 'templates') 
                        .DSEP);
define('TPL_TAGOPEN',   '[--');
define('TPL_TAGCLOSE',  '--]');


// Database Section
define('DB_HOST',       'localhost');
define('DB_NAME',       'cal');

define('DB_USER_RO',    'cal_ro');
define('DB_PWD_RO',     'ro');

define('DB_USER_RW',    'cal_rw');
define('DB_PWD_RW',     'rw');


// Email
define('MAIL_FROM',     'dummy@example.com');
define('MAIL_RPATH',    'dummy@example.com');
define('MAIL_HBR',      "\r\n");



function fCnfGetLang()
{
    // Declare supported languages
    $aValLang = array (
        'en' => 1,
        'de' => 1
    );
    $sStdLang = 'de';
    
    // Is requested language supported?
    if(!empty($_REQUEST['l']))
    {
        $sUserLang = $_REQUEST['l'];
    }
    elseif(!empty($_SESSION['usr']['lang']))
    {
        $sUserLang = $_SESSION['usr']['lang'];
    }
    // if not or if there is no language requested which is preferred language
    // of user webbrowser?
    elseif(!empty($_SERVER['HTTP_ACCEPT_LANGUAGE']))
    {
        $sUserLang = strtolower(
                         substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2));
    }
    else 
    {
        $sUserLang = $sStdLang;
    }
    
    // is any of the languages supported by application?
    if (!isset($aValLang[$sUserLang]))
        $sUserLang = $sStdLang;
    
    return($sUserLang);
}



function fCnfGetSep()
{
	$sSep = (substr(PHP_OS, 0, 3) == 'WIN')
	   ? '\\' 
	   : '/';
	   
	return($sSep);
}



function fCnfGetSepIni()
{
	$sSep = (substr(PHP_OS, 0, 3) == 'WIN')
	   ? ';' 
	   : ':';
	   
	return($sSep);
}

?>