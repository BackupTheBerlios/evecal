<?php
/**
 *
 * @author       Carola 'Sammy' Kummert <sammywg@gmx.de>
 * @package      Event Calendar
 * @subpackage   DB - MySQL
 * @version      $Id: mysql.inc.php,v 1.1 2005/05/10 14:36:30 sammywg Exp $
 *
 */


/**
 * connects to database or dies with errormessage
 *
 * @todo    error handling if connect or database selection fails
 *
 * @param   bool        $bRW    indicates which database user is needed (ro/rw)
 *
 * @return  resource
 */
function fDbConnect($bRW=FALSE)
{
    // sets needed database user and password as declared in configuration
    if ($bRW === TRUE)
    {
        $sUser = DB_USER_RW;
        $sPass = DB_PWD_RW;
    }
    else
    {
        $sUser = DB_USER_RO;
        $sPass = DB_PWD_RO;
    }
    
    // connect to database server and log in
    $hDb = mysql_connect (DB_HOST, $sUser, $sPass)
            or die ("keine Verbindung moeglich: ".mysql_error());
            
    // select database
    mysql_select_db(DB_NAME, $hDb)
            or die ("Datenbank nicht verfuegbar: ".mysql_error());
            
    // return connection handle
    return ($hDb);
}



/**
 * realizes a query to selected database or dies with error message
 *
 * @todo    error handling if query fails
 *
 * @param   string      $sQuery     query string
 * @param   resource    $hDb        connection handle
 *
 * @return  resource
 */
function fDbQuery($sQuery,$hDb)
{
    $hResult = mysql_query($sQuery, $hDb)
        OR die ("Queryfehler: ".mysql_errno().' - '.mysql_error().HBR
                ."Query: ".$sQuery);
            
    return ($hResult);
}



/**
 * frees result set
 *
 * @param   resource    $hResult    result handle
 *
 * @return  bool
 */
function fDbFreeResult($hResult)
{
    $bOk = mysql_free_result($hResult);
    
    return($bOk);
}



/**
 * closes the database connection
 *
 * @param   resource    $hDb        connection handle
 *
 * @return  bool
 */
function fDbClose($hDb)
{
    $bOk = mysql_close($hDb);
    
    return($bOk);
}



/**
 * gets user data needed for resetting password and
 * generating an email to user within it
 *
 * @todo    - error handling if result freeing or connection close fails
 *
 * @param   string  $sNick
 *
 * @return  array
 */
function fDbPwdLost($sNick)
{
    $hDb = fDbConnect();

    $sQuery = 
    'SELECT uid, nick, email, fn, ln, lang '
     .'FROM user '
    .'WHERE nick = "' . mysql_real_escape_string($sNick) . '"';
         
    $hResult = fDbQuery($sQuery, $hDb);
    
    $aRow = mysql_fetch_row($hResult);
    $bOk = fDbFreeResult($hResult);
    $bOk = fDbClose($hDb);
    
    $aPwdData = (!$aRow)
        ? array()
        : array(
            'uid'   => (int)$aRow[0],
            'nick'  => (string)$aRow[1],
            'email' => (string)$aRow[2],
            'fname' => (string)$aRow[3],
            'lname' => (string)$aRow[4],
            'lang'  => (string)$aRow[5],
          );
    
    return($aPwdData);
}



/**
 * updates user password
 *
 * @todo    - error handling if connection close fails
 *
 * @param   array   $aPwdData   user environment
 *
 * @return  bool
 */
function fDbPwdUpdate($aPwdData)
{
    $hDb = fDbConnect(TRUE);

    $sQuery = 
    'UPDATE user '
      .'SET pwd = "' . md5($aPwdData['new_pwd']) . '" '
    .'WHERE uid = ' . $aPwdData['uid'];

    $bResult = fDbQuery($sQuery, $hDb);
    $bOk = fDbClose($hDb);
    
    return($bResult);
}



/**
 * validates user login and returns user environment
 *
 * @todo    - error handling if result freeing or connection close fails
 *
 * @param   string  $sNick
 * @param   string  $sPwd
 *
 * @return  array
 */
function fDbValLogin($sNick, $sPwd)
{
    $sQuery = 
    'SELECT u.uid, u.fn, u.ln, u.email, u.lang, u.status, t.zv '
     .'FROM user u, JOIN timezones t '
       .'ON u.zid = t.zid '
    .'WHERE u.nick = "' . mysql_real_escape_string($sNick) . '" '
      .'AND u.pwd = "' . md5($sPwd) . '"';
         
    $hDb = fDbConnect();
    $hResult = fDbQuery($sQuery, $hDb);
    
    $aRow = mysql_fetch_row($hResult);
    $bOk = fDbFreeResult($hResult);
    $bOk = fDbClose($hDb);
    
    $aPwdData = (!$aRow)
        ? array()
        : array(
            'uid'   => (int)$aRow[0],
            'fname' => (string)$aRow[1],
            'lname' => (string)$aRow[2],
            'email' => (string)$aRow[3],
            'lang'  => (string)$aRow[4],
            'status'=> (string)$aRow[5],
            'zv'    => (string)$aRow[6],
          );
    
    return($aPwdData);
}

?>