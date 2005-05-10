<?php
/**
 *
 * @author       Carola 'Sammy' Kummert <sammywg@gmx.de>
 * @package      Event Calendar
 * @subpackage   Templating
 * @version      $Id: tpl_replace.inc.php,v 1.1 2005/05/10 14:36:30 sammywg Exp $
 *
 */


/**
 * - strips off CVS-Id-Lines from the templates
 * - replaces tags with real content
 *
 * @param   array           $aTplReplace    associative array including all substitutions
 * @param   array|string    $varContent     template(set)
 *
 * @return  mixed(array|string)
 */
function fTplReplace($aTplReplace, $varContent)
{
    // if $varContent is array use fTplReplace recursively
    if (is_array($varContent))
    {
        foreach ($varContent as $kContent => $sContent)
        {
            $varContent[$kContent] = fTplReplace($aTplReplace, $sContent);
        }
        return($varContent);    
    }
    
    // strips off CVS-Id lines enclosed by XHTML comment structure
    $varContent = preg_replace('|(<!-- \$).*( //-->)\n+|', '', $varContent);
    
    // substitute template tags by content, respecting tag prefixes
    foreach ($aTplReplace as $key => $value)
    {
        $aAction = explode('_', $key, 2);
        
        // prefixes used by templates and should be respected by substitution:
        // - 'INC' marks included (and still processed) templates
        // - 'PRE' marks preformatted tags which must NOT be processed
        if ($aAction[0] == 'INC'
            or $aAction[0] == 'PRE')
        {
            $varContent = str_replace(TPL_TAGOPEN . $key . TPL_TAGCLOSE, $value, $varContent);
        }
        else
        {
            $varContent = str_replace(TPL_TAGOPEN . $key . TPL_TAGCLOSE,
                                  htmlentities($value, ENT_NOQUOTES, 'ISO8859-15'),
                                  $varContent);
        }
    }

    // return template
    return($varContent);
}



/**
 * prints out all templates
 *
 * @param   mixed   $sUsedTemplate  expects a string or an array with numeric keys
 *
 * @return  bool
 */
function fTplPrint(&$varUsedTemplate)
{
    // if type isn't string or array, return FALSE (expects error handling!)
    // and exit function
    if(!(is_array($varUsedTemplate)
        XOR is_string($varUsedTemplate)))
    {
        return(FALSE);
    }
    
    // initalize output string
    $sOutput = '';
    
    // sorts given array by numeric keys and add sorted items to output string
    if (is_array($varUsedTemplate))
    {
        $bOk = ksort($varUsedTemplate);
        if ($bOk === FALSE)
            return(FALSE);
            
        foreach ($varUsedTemplate as $sTpl) 
        {
        	$sOutput .= $sTpl;
        }
    }
    // alternatively declare given string as output
    else
    {
    	$sOutput =& $varUsedTemplate;
    }
    
    // prints out all content to user
    print $sOutput;
    
    return(TRUE);
}

?>