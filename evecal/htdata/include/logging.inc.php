<?php
/**
 *
 * @author       Carola 'Sammy' Kummert <sammywg@gmx.de>
 * @package      Event Calendar
 * @subpackage   Logging
 * @version      $Id: logging.inc.php,v 1.1 2005/05/10 14:36:30 sammywg Exp $
 *
 */


/**
 * writes log entries to disc
 *
 * @param   string  $sFormat    log format
 * @param   array   $aLogData   all data used for log
 * @param   string  $sFile      log file
 *
 * @return  bool
 */
function fLog($sFormat, $aLogData, $sFile = LOG_STDFILE)
{
	$sLogMsg = vsprintf($sFormat, $aLogData);
	$hLog = fopen(LOG_DIR . $sFile, 'a+');
	$iOk = fwrite($hLog, $sLogMsg);
	fclose($hLog);
	
    $bOk = ($iOk == strlen($sLogMsg))
        ? TRUE
        : FALSE;
        
    return($bOk);
}

?>