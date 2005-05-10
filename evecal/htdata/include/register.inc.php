<?php
/**
 *
 * @author       Carola 'Sammy' Kummert <sammywg@gmx.de>
 * @package      Event Calendar
 * @subpackage   Login
 * @version      $Id: register.inc.php,v 1.1 2005/05/10 14:36:30 sammywg Exp $
 *
 */


// solve dependencies - include basic modules if not included still
include_once('logging.inc.php');
include_once('mysql.inc.php');
include_once('tpl_replace.inc.php');


/**
 * main part
 *
 * @param   void
 *
 * @return  bool
 */
function fRegister()
{
    // only accept POST as request method to ask for a password
    // GET delivers password form exclusively
    if ($_SERVER['REQUEST_METHOD'] != 'POST')
    {
    	$bOk = fRegDisplay('register');
    	exit;
    }
    
    // validate input
    $aError = fRegBaseVal($_POST);
        
    if(!empty($aError))
    {
        $bOk = fRegDisplay($aError);
        exit;
    }
        
    $aRegData = fRegVal($_POST);
    
    if(isset($aPwdData['error']))
    {
        $bOk = fPwdLostNoUserLog($aPwdData);
        $bOk = fPwdDisplay('no_nick', $aPwdData);
        exit;
    }
    
    // get new password by function
	$aPwdData['new_pwd'] = fPwdGen();

	// update password to new password and
	// send an email to user
	$bOk = fDbPwdUpdate($aPwdData);
	$bOk = fPwdMail($aPwdData);
	
	$bOk = fPwdDisplay('send', $aPwdData);
	
	exit;
}



function fRegBaseVal($aInput)
{
    $aError = array();
    
    // nick basic validation
    if(!isset($aInput['nick'])
        OR strlen($aInput['nick']) == 0)
        $aError['nick'] = TRUE;
        
    // password basic validation
    if(!isset($aInput['password'])
        OR !isset($aInput['password2'])
        OR strlen($aInput['password']) == 0
        OR $aInput['password'] != $aInput['password2'])
        $aError['pwd'] = TRUE;
        
    // email basic validation
    if(!isset($aInput['email'])
        OR strlen($aInput['email']) == 0)
        $aError['email'] = TRUE;

    return($aError);        
}


/**
 * validates given nick and returns user environment
 *
 * @param   string  $sNick
 *
 * @return  array
 */
function fPwdLostVal($sNick)
{
    // Keep in mind you MUST disable magic_quotes_runtime and magic_quotes_sybase
    // at configuration because of strange side effects if they are enabled.
    // Because of magic_quotes_gpc is default enabled and can only be changed by
    // .htaccess or php.ini, we have to check and strip slashes if enabled.
	if (ini_get('magic_quotes_gpc') === TRUE)
		$sNick = stripslashes($sNick);

	// validate nick and get user environment if nick exists
	$aPwdData = fDbPwdLost($sNick);
    if(empty($aPwdData))
    {
        $aPwdData = array(
            'nick'  => $sNick,
            'error' => TRUE,
        );
    }
	
    return($aPwdData);
}



/**
 * generates new user password
 *
 * @param   void
 *
 * @return  string
 */
function fPwdGen()
{
	$aEntities = array_merge(
                   range('a', 'z'), 
                   range('A', 'Z'), 
                   range(0, 9)
	             );
	$bOk = shuffle($aEntities);
	$sNewPwd = substr(implode('', $aEntities), 
	                  0, 
	                  PWD_GEN_LEN);
	
	return($sNewPwd);
}



/**
 * creates user interfaces
 *
 * @todo    WARNING! this is still a dummy function
 *
 * @param   string  $sType      interface type
 * @param   array   $aData      user environment
 *
 * @return  void
 */
function fPwdDisplay($sType, $aData = array())
{
    $aValTpl = array(
        'pwd_lost'  => 'scr.pwdlost.tpl',
        'no_input'  => 'scr.pwdlost.tpl',
        'no_nick'   => 'scr.pwdlost.tpl',
        'send'      => 'scr.pwdlost_sd.tpl',
    );
    
    $aTpl = array(
        0   => file_get_contents(TPL_DIR . 'head.tpl'),
        30  => file_get_contents(TPL_DIR . $aValTpl[$sType]),
        100 => file_get_contents(TPL_DIR . 'footer.tpl'),
    );

    
    $aLang = parse_ini_file(LANG_DIR . 'pwdlost.' . LANG . '.lang', TRUE);
    $aLang[$sType]['PRE_URL'] = $_SERVER['PHP_SELF'] . '?cmd=pwd';
    $aTpl[0] = fTplReplace($aLang['head'], $aTpl[0]);
    $aTpl[30] = fTplReplace($aLang[$sType], $aTpl[30]);
    
    $bOk = fTplPrint($aTpl);
    exit;
}



/**
 * writes a log entry if unknown user's password was requested
 *
 * @param   array   $aData      user environment
 *
 * @return  bool
 */
function fPwdLostNoUserLog($aData)
{
    $sTime = date('y-m-d H:i:s');
    
    $aLogData = array(
        $sTime,
        $_SERVER['REMOTE_ADDR'],
        $aData['nick'],
        'nu',
    );
    
    $sFormat = "%1\$s %4\$-3s %2\$-15s %3\$s\n";
    $sLog = 'pwd_lost.log';
    
    $bOk = fLog($sFormat, $aLogData, $sLog);
    
	return($bOk);
}



/**
 * sends an email to requesting user including new password
 *
 * @param   array   $aData      user environment
 *
 * @return  bool
 */
function fPwdMail($aData)
{
	$sTpl = file_get_contents(TPL_DIR . 'mail.pwdlost.' . $aData['lang'] . '.tpl');
	$aReplace = array(
	   'PRE_FIRSTNAME' => $aData['fname'],
	   'PRE_LASTNAME'  => $aData['lname'],
	   'PRE_NICK'      => $aData['nick'],
	   'PRE_PWD'       => $aData['new_pwd'],
	   'PRE_IP'        => $_SERVER['REMOTE_ADDR'],
	   'PRE_APPL'      => BASE_URL,
	   'PRE_DATE'      => date('y-m-d'),
	   'PRE_TIME'      => date('H:i:s'),
	);
	$sTpl = fTplReplace($aReplace, $sTpl);
	
    $sHeader = 	    
        'From: ' . MAIL_FROM . MAIL_HBR .
        'Reply-To: ' . MAIL_RPATH . MAIL_HBR .
        'X-Mailer: PHP/Calendar' . MAIL_HBR;

	$bOk = mail($aData['email'], 'Login', $sTpl, $sHeader);
	
    return($bOk);
}

?>