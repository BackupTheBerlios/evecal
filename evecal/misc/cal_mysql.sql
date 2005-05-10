# --------------------------------------------------------
# Database : `mysql`
# 

USE mysql;

# --------------------------------------------------------
# Add calendar users
#

INSERT INTO `user` 
    SET `Host`          = 'localhost', 
        `User`          = 'cal_ro', 
        `Password`      = PASSWORD('ro'), 
        `Select_priv`   = 'N', 
        `Insert_priv`   = 'N', 
        `Update_priv`   = 'N', 
        `Delete_priv`   = 'N', 
        `Create_priv`   = 'N', 
        `Drop_priv`     = 'N', 
        `Reload_priv`   = 'N', 
        `Shutdown_priv` = 'N', 
        `Process_priv`  = 'N', 
        `File_priv`     = 'N', 
        `Grant_priv`    = 'N', 
        `References_priv`   = 'N', 
        `Index_priv`    = 'N', 
        `Alter_priv`    = 'N';
INSERT INTO `user` 
    SET `Host`          = 'localhost', 
        `User`          = 'cal_rw', 
        `Password`      = PASSWORD('rw'), 
        `Select_priv`   = 'N', 
        `Insert_priv`   = 'N', 
        `Update_priv`   = 'N', 
        `Delete_priv`   = 'N', 
        `Create_priv`   = 'N', 
        `Drop_priv`     = 'N', 
        `Reload_priv`   = 'N', 
        `Shutdown_priv` = 'N', 
        `Process_priv`  = 'N', 
        `File_priv`     = 'N', 
        `Grant_priv`    = 'N', 
        `References_priv`   = 'N', 
        `Index_priv`    = 'N', 
        `Alter_priv`    = 'N';

# --------------------------------------------------------
# Set calendar user restrictions
#

INSERT INTO `db` 
    SET `Host`          = 'localhost', 
        `Db`            = 'cal', 
        `User`          = 'cal_ro', 
        `Select_priv`   = 'Y', 
        `Insert_priv`   = 'N', 
        `Update_priv`   = 'N', 
        `Delete_priv`   = 'N', 
        `Create_priv`   = 'N', 
        `Drop_priv`     = 'N',  
        `Grant_priv`    = 'N',
        `References_priv`   = 'N',  
        `Index_priv`    = 'N', 
        `Alter_priv`    = 'N';
INSERT INTO `db` 
    SET `Host`          = 'localhost', 
        `Db`            = 'cal', 
        `User`          = 'cal_rw', 
        `Select_priv`   = 'Y', 
        `Insert_priv`   = 'Y', 
        `Update_priv`   = 'Y', 
        `Delete_priv`   = 'Y', 
        `Create_priv`   = 'N', 
        `Drop_priv`     = 'N',  
        `Grant_priv`    = 'N',
        `References_priv`   = 'N',  
        `Index_priv`    = 'N', 
        `Alter_priv`    = 'N',
        `Lock_tables_priv`  = 'Y';

# --------------------------------------------------------
FLUSH PRIVILEGES;
        
        
# --------------------------------------------------------
# Database : `cal`

CREATE DATABASE IF NOT EXISTS cal;
USE cal;


# --------------------------------------------------------
# Table structure for table `user`

CREATE TABLE `user` (
  `uid` int(10) unsigned NOT NULL auto_increment,
  `nick` varchar(20) NOT NULL default '',
  `fn` varchar(50) NOT NULL default '',
  `ln` varchar(50) NOT NULL default '',
  `pwd` varchar(32) NOT NULL default '',
  `email` varchar(80) NOT NULL default '',
  `lang` char(2) NOT NULL default '',
  `zid` int(10) unsigned NOT NULL default '0',
  `status` set('r','rw','a','b','np') NOT NULL default 'r',
  PRIMARY KEY  (`uid`),
  UNIQUE KEY `nick` (`nick`)
) TYPE=MyISAM COMMENT='primary user data' AUTO_INCREMENT=1 ;


# --------------------------------------------------------
# Table structure for table `events`

CREATE TABLE `events` (
  `eid` int(10) unsigned NOT NULL auto_increment,
  `egid` int(10) unsigned NOT NULL default '0',
  `ecid` int(10) unsigned NOT NULL default '0',
  `date` int(10) unsigned NOT NULL default '0',
  `local` varchar(50) NOT NULL default '',
  `snid` int(10) unsigned NOT NULL default '0',
  `sdid` int(10) unsigned NOT NULL default '0',
  `ldid` int(10) unsigned NOT NULL default '0',
  `pid` int(10) unsigned NOT NULL default '0',
  `ctime` int(10) unsigned NOT NULL default '0',
  `cuid` int(10) unsigned NOT NULL default '0',
  `mtime` int(10) unsigned NOT NULL default '0',
  `muid` int(10) unsigned NOT NULL default '0',
  `ftime` int(10) unsigned NOT NULL default '0',
  `fuid` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`eid`),
  KEY `egid` (`egid`,`ecid`,`date`,`local`)
) TYPE=MyISAM COMMENT='event environment' AUTO_INCREMENT=1 ;


# --------------------------------------------------------
# Table structure for table `event_groups`

CREATE TABLE `event_groups` (
  `egid` int(10) unsigned NOT NULL auto_increment,
  `snid` int(10) unsigned NOT NULL default '0',
  `sdid` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`egid`),
  UNIQUE KEY `desc` (`snid`,`sdid`)
) TYPE=MyISAM COMMENT='event group environment' AUTO_INCREMENT=1 ;


# --------------------------------------------------------
# Table structure for table `timezones`

CREATE TABLE `timezones` (
  `snid` int(10) unsigned NOT NULL auto_increment,
  `zdid` int(10) unsigned NOT NULL default '0',
  `zv` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`snid`),
  UNIQUE KEY `zdid` (`zdid`)
) TYPE=MyISAM COMMENT='time zone environment' AUTO_INCREMENT=1 ;


# --------------------------------------------------------
# Table structure for table `texts_sn`

CREATE TABLE `texts_sn` (
  `snid` int(10) unsigned NOT NULL auto_increment,
  `de` varchar(100) NOT NULL default '',
  `en` varchar(100) NOT NULL default '',
  PRIMARY KEY  (`snid`),
  FULLTEXT KEY `de` (`de`,`en`)
) TYPE=MyISAM COMMENT='short names (event titles, event group titles, time zones)' AUTO_INCREMENT=1 ;


# --------------------------------------------------------
# Table structure for table `texts_sd`

CREATE TABLE `texts_sd` (
  `sdid` int(10) unsigned NOT NULL auto_increment,
  `de` text,
  `en` text,
  PRIMARY KEY  (`sdid`),
  FULLTEXT KEY `de` (`de`,`en`)
) TYPE=MyISAM COMMENT='short descriptions (events)' AUTO_INCREMENT=1 ;


# --------------------------------------------------------
# Table structure for table `texts_ld`

CREATE TABLE `texts_ld` (
  `ldid` int(10) unsigned NOT NULL auto_increment,
  `de` text NOT NULL,
  `en` text NOT NULL,
  PRIMARY KEY  (`ldid`),
  FULLTEXT KEY `de` (`de`,`en`)
) TYPE=MyISAM COMMENT='long texts (events)' AUTO_INCREMENT=1 ;


# --------------------------------------------------------
# Table structure for table `pictures`

CREATE TABLE `pictures` (
  `pid` int(10) unsigned NOT NULL auto_increment,
  `pname` varchar(20) NOT NULL default '',
  `purl` varchar(50) NOT NULL default '',
  `pheight` smallint(5) unsigned NOT NULL default '0',
  `pwidth` smallint(5) unsigned NOT NULL default '0',
  `palt` varchar(100) NOT NULL default '',
  PRIMARY KEY  (`pid`),
  KEY `pname` (`pname`,`purl`)
) TYPE=MyISAM COMMENT='picture catalogue' AUTO_INCREMENT=1 ;
