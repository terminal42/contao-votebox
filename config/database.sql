-- ********************************************************
-- *                                                      *
-- * IMPORTANT NOTE                                       *
-- *                                                      *
-- * Do not import this file manually but use the Contao  *
-- * install tool to create and maintain database tables! *
-- *                                                      *
-- ********************************************************


-- --------------------------------------------------------

-- 
-- Table `tl_votebox_archives`
-- 

CREATE TABLE `tl_votebox_archives` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `tstamp` int(10) unsigned NOT NULL default '0',
  `title` varchar(255) NOT NULL default '',
  `numberOfVotes` smallint(5) unsigned NOT NULL default '0',
  `moderate` char(1) NOT NULL default '',
  `receiver_mail` varchar(255) NOT NULL default '',
  `allowComments` char(1) NOT NULL default '',
  `notify` varchar(32) NOT NULL default '',
  `sortOrder` varchar(32) NOT NULL default '',
  `perPage` smallint(5) unsigned NOT NULL default '0',
  `comments_moderate` char(1) NOT NULL default '',
  `bbcode` char(1) NOT NULL default '',
  `disableCaptcha` char(1) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
