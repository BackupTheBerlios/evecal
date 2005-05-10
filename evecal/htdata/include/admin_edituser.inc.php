<?php
/**
 *
 * @author       Carola 'Sammy' Kummert <sammywg@gmx.de>
 * @package      Event Calendar
 * @subpackage   Admin
 * @version      $Id: admin_edituser.inc.php,v 1.1 2005/05/10 14:36:30 sammywg Exp $
 *
 */



function fAdmin()
{
    $aValSub = array(
        'ausr'  => array(
            'inc'   => 'adduser',
            'func'  => 'fAddUser',
        ),
        'dusr'  => array(
            'inc'   => 'deluser',
            'func'  => 'fDelUser',
        ),
        'eusr'  => array(
            'inc'   => 'edituser',
            'func'  => 'fEditUser',
        ),
    );
    
	if(isset($_REQUEST['sub']))
    {
        if(isset($aValSub[$_REQUEST['sub']]))
        {
            include_once('admin_' . $aValSub[$_REQUEST['sub']]['inc'] . INC);
            call_user_func($aValSub[$_REQUEST['sub']]['func']);
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
    
    switch ($sAction)
    {
        case 'fake':
            include_once('fake' . INC);
            fFake();
            break;
        default:
            include_once('admin_startpage' . INC);
            fStartpage();
            break;
    }
    
    exit;
}

?>