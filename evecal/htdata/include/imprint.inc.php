<?php
/**
 *
 * @author       Carola 'Sammy' Kummert <sammywg@gmx.de>
 * @package      Event Calendar
 * @subpackage   Imprint
 * @version      $Id: imprint.inc.php,v 1.1 2005/05/10 14:36:30 sammywg Exp $
 *
 */



// solve dependencies - include basic modules if not included still
include_once('tpl_replace.inc.php');


/**
 * main part
 *
 * @param   void
 *
 * @return  void
 */
function fImprint()
{
    fImpDisplay();
    exit;    
}



/**
 * creates user interfaces
 *
 * @param   void
 *
 * @return  void
 */
function fImpDisplay()
{
    // load templates
    $aTpl = array(
        0   => file_get_contents(TPL_DIR . 'head.tpl'),
        10  => file_get_contents(TPL_DIR . 'scr.menu_head.tpl'),
        20  => file_get_contents(TPL_DIR . 'scr.menu_main.tpl'),
        30  => file_get_contents(TPL_DIR . 'scr.imprint.tpl'),
        100 => file_get_contents(TPL_DIR . 'footer.tpl'),
    );

    // load language environment
    $aLang = parse_ini_file(LANG_DIR . 'imprint' . '.lang', TRUE);
    $aLang['menu'] = parse_ini_file(LANG_DIR . 'menu.' . LANG . '.lang', TRUE);
    
    // replace template placeholder tags
    $aTpl[0] = fTplReplace($aLang['head'], $aTpl[0]);
    $aTpl[10] = fTplReplace($aLang['menu']['head'], $aTpl[10]);
    $aTpl[20] = fTplReplace($aLang['menu']['main'], $aTpl[20]);
    
    // output to screen
    $bOk = fTplPrint($aTpl);
    exit;
}

?>