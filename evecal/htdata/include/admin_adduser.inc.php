<?php
/**
 *
 * @author       Carola 'Sammy' Kummert <sammywg@gmx.de>
 * @package      Event Calendar
 * @subpackage   Admin
 * @version      $Id: admin_adduser.inc.php,v 1.1 2005/05/10 14:36:30 sammywg Exp $
 *
 */



// solve dependencies - include basic modules if not included still
include_once('mysql.inc.php');
include_once('tpl_replace.inc.php');


/**
 * main part
 *
 * @param   void
 *
 * @return  bool
 */
function fAddUser()
{
    // only accept POST as request method to ask for a password
    // GET delivers password form exclusively
    if ($_SERVER['REQUEST_METHOD'] != 'POST')
    {
    	$bOk = fAdmDisplay('adduser');
    	exit;
    }

    exit;
}



/**
 * creates user interfaces
 *
 * @param   string  $sType      interface type
 * @param   array   $aData      user environment
 *
 * @return  void
 */
function fAdmDisplay($sType, $aData = array())
{
    $aValTpl = array(
        'adduser'       => 'scr.adm_adduser.tpl',
        'not_complete'  => 'scr.adm_adduser.tpl',
        'input_error'   => 'scr.adm_adduser.tpl',
        'ok'            => 'scr.adm_adduser_ok.tpl',
    );
    
    $aTpl = array(
        0   => file_get_contents(TPL_DIR . 'head.tpl'),
        30  => file_get_contents(TPL_DIR . $aValTpl[$sType]),
        100 => file_get_contents(TPL_DIR . 'footer.tpl'),
    );

    
    $aLang = parse_ini_file(LANG_DIR . 'admin.' . LANG . '.lang', TRUE);
    $aLang[$sType]['PRE_URL'] = $_SERVER['PHP_SELF'] . '?cmd=adm&sub=ausr';
    $aTpl[0] = fTplReplace($aLang['head'], $aTpl[0]);
    $aTpl[30] = fTplReplace($aLang[$sType], $aTpl[30]);
    
    $bOk = fTplPrint($aTpl);
    exit;
}

?>