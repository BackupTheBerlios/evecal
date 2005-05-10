<?php
/**
 *
 * @author       Carola 'Sammy' Kummert <sammywg@gmx.de>
 * @package      Event Calendar
 * @subpackage   Main
 * @version      $Id: index.php,v 1.1 2005/05/10 14:36:30 sammywg Exp $
 *
 */


// create system path to include configuration
$aCrntPath = explode('/htdocs',dirname(__FILE__));
unset($aCrntPath[count($aCrntPath) - 1]);
$sBasePath = (count($aCrntPath) > 1)
    ? implode('/htdocs', $aCrntPath)
    : $aCrntPath[0];

include_once(realpath($sBasePath . '/htdata/include/config.inc.php'));



// define valid commands
$aValCmd = array(
    'adm'   => array(
        'inc'   => 'admin',
        'func'  => 'fAdmin',
    ),
    'imp'   => array(
        'inc'   => 'imprint',
        'func'  => 'fImprint',
    ),
    'li'    => array(
        'inc'   => 'login',
        'func'  => 'fLogin',
    ),
    'pwd'   => array(
        'inc'   => 'pwd',
        'func'  => 'fPwdLost',
    ),
    'reg'   => array(
        'inc'   => 'register',
        'func'  => 'fRegister',
    ),
);


// validate input command, include command dependent function library and
// call library main function
if(isset($_REQUEST['cmd']))
{
    if(isset($aValCmd[$_REQUEST['cmd']]))
    {
        include_once($aValCmd[$_REQUEST['cmd']]['inc'] . INC);
        call_user_func($aValCmd[$_REQUEST['cmd']]['func']);
        exit;
    }
    else
    {
        $sAction = 'fake';
    }
}
else
{
    $sAction = 'default';
}

// if there is no known library, check for alternatives
switch ($sAction)
{
    // if user tried to fake command parameter, log fake event and display
    // a message
    case 'fake':
        include_once('fake' . INC);
        fFake();
        break;
    // if there is no command parameter deliver the default start page
    default:
        include_once('startpage' . INC);
        fStartpage();
        break;
}

exit;

?>