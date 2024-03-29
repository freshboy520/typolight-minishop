-- **********************************************************
-- *                                                        *
-- * IMPORTANT NOTE                                         *
-- *                                                        *
-- * Do not import this file manually but use the TYPOlight *
-- * install tool to create and maintain database tables!   *
-- *                                                        *
-- **********************************************************

-- 
-- Table `tl_article`
-- 

CREATE TABLE `tl_article` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `pid` int(10) unsigned NOT NULL default '0',
  `sorting` int(10) unsigned NOT NULL default '0',
  `tstamp` int(10) unsigned NOT NULL default '0',
  `author` varchar(255) NOT NULL default '',
  `inColumn` varchar(32) NOT NULL default '',
  `title` varchar(255) NOT NULL default '',
  `alias` varchar(64) NOT NULL default '',
  `teaser` text NULL,
  `showTeaser` char(1) NOT NULL default '',
  `keywords` text NULL,
  `space` varchar(255) NOT NULL default '',
  `cssID` varchar(255) NOT NULL default '',
  `printable` char(1) NOT NULL default '',
  `label` varchar(255) NOT NULL default '',
  `published` char(1) NOT NULL default '',
  `start` varchar(10) NOT NULL default '',
  `stop` varchar(10) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `pid` (`pid`),
  KEY `alias` (`alias`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Table `tl_cache`
-- 

CREATE TABLE `tl_cache` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `pid` int(10) unsigned NOT NULL default '0',
  `url` varchar(255) NOT NULL default '',
  `tstamp` int(10) unsigned NOT NULL default '0',
  `data` mediumtext NULL,
  PRIMARY KEY  (`id`),
  KEY `pid` (`pid`),
  UNIQUE KEY `url` (`url`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Table `tl_content`
-- 

CREATE TABLE `tl_content` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `pid` int(10) unsigned NOT NULL default '0',
  `sorting` int(10) unsigned NOT NULL default '0',
  `tstamp` int(10) unsigned NOT NULL default '0',
  `type` varchar(32) NOT NULL default '',
  `invisible` char(1) NOT NULL default '',
  `headline` varchar(255) NOT NULL default '',
  `text` text NULL,
  `html` text NULL,
  `code` text NULL,
  `highlight` varchar(32) NOT NULL default '',
  `addImage` char(1) NOT NULL default '',
  `useImage` char(1) NOT NULL default '',
  `singleSRC` varchar(255) NOT NULL default '',
  `multiSRC` blob NULL,
  `useHomeDir` char(1) NOT NULL default '',
  `sortBy` varchar(32) NOT NULL default '',
  `size` varchar(255) NOT NULL default '',
  `alt` varchar(255) NOT NULL default '',
  `caption` varchar(255) NOT NULL default '',
  `floating` varchar(32) NOT NULL default '',
  `imagemargin` varchar(255) NOT NULL default '',
  `fullsize` char(1) NOT NULL default '',
  `perRow` smallint(5) unsigned NOT NULL default '0',
  `listtype` varchar(32) NOT NULL default '',
  `listitems` blob NULL,
  `tableitems` blob NULL,
  `summary` varchar(255) NOT NULL default '',
  `thead` char(1) NOT NULL default '',
  `tfoot` char(1) NOT NULL default '',
  `sortable` char(1) NOT NULL default '',
  `sortIndex` smallint(5) unsigned NOT NULL default '0',
  `sortOrder` varchar(32) NOT NULL default '',
  `url` varchar(255) NOT NULL default '',
  `imageUrl` varchar(255) NOT NULL default '',
  `linkTitle` varchar(255) NOT NULL default '',
  `embed` varchar(255) NOT NULL default '',
  `target` char(1) NOT NULL default '',
  `mooType` varchar(32) NOT NULL default '',
  `mooHeadline` varchar(255) NOT NULL default '',
  `mooStyle` varchar(255) NOT NULL default '',
  `mooClasses` varchar(255) NOT NULL default '',
  `perPage` smallint(5) unsigned NOT NULL default '0',
  `cteAlias` smallint(5) unsigned NOT NULL default '0',
  `article` int(10) unsigned NOT NULL default '0',
  `form` smallint(5) unsigned NOT NULL default '0',
  `module` smallint(5) unsigned NOT NULL default '0',
  `protected` char(1) NOT NULL default '',
  `guests` char(1) NOT NULL default '',
  `groups` blob NULL,
  `space` varchar(255) NOT NULL default '',
  `align` varchar(32) NOT NULL default '',
  `cssID` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `pid` (`pid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Table `tl_flash`
-- 

CREATE TABLE `tl_flash` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `tstamp` int(10) unsigned NOT NULL default '0',
  `title` varchar(255) NOT NULL default '',
  `flashID` varchar(64) NOT NULL default '',
  `content` text NULL,
  PRIMARY KEY  (`id`),
  KEY `flashID` (`flashID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Table `tl_form`
-- 

CREATE TABLE `tl_form` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `tstamp` int(10) unsigned NOT NULL default '0',
  `title` varchar(255) NOT NULL default '',
  `formID` varchar(64) NOT NULL default '',
  `method` varchar(12) NOT NULL default '',
  `allowTags` char(1) NOT NULL default '',
  `storeValues` char(1) NOT NULL default '',
  `targetTable` varchar(64) NOT NULL default '',
  `tableless` char(1) NOT NULL default '',
  `sendViaEmail` char(1) NOT NULL default '',
  `recipient` text NULL,
  `subject` varchar(255) NOT NULL default '',
  `format` varchar(32) NOT NULL default '',
  `jumpTo` smallint(5) unsigned NOT NULL default '0',
  `attributes` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Table `tl_form_field`
-- 

CREATE TABLE `tl_form_field` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `pid` int(10) unsigned NOT NULL default '0',
  `sorting` int(10) unsigned NOT NULL default '0',
  `tstamp` int(10) unsigned NOT NULL default '0',
  `type` varchar(32) NOT NULL default '',
  `name` varchar(64) NOT NULL default '',
  `label` varchar(255) NOT NULL default '',
  `value` varchar(255) NOT NULL default '',
  `text` text NULL,
  `html` text NULL,
  `options` blob NULL,
  `multiple` char(1) NOT NULL default '',
  `mSize` smallint(5) unsigned NOT NULL default '0',
  `mandatory` char(1) NOT NULL default '',
  `rgxp` varchar(32) NOT NULL default '',
  `maxlength` int(10) unsigned NOT NULL default '0',
  `extensions` varchar(255) NOT NULL default '',
  `size` varchar(255) NOT NULL default '',
  `accesskey` varchar(2) NOT NULL default '',
  `class` varchar(64) NOT NULL default '',
  `storeFile` char(1) NOT NULL default '',
  `uploadFolder` varchar(255) NOT NULL default '',
  `useHomeDir` char(1) NOT NULL default '',
  `doNotOverwrite` char(1) NOT NULL default '',
  `addSubmit` char(1) NOT NULL default '',
  `imageSubmit` char(1) NOT NULL default '',
  `singleSRC` varchar(255) NOT NULL default '',
  `slabel` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `pid` (`pid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Table `tl_layout`
-- 

CREATE TABLE `tl_layout` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `tstamp` int(10) unsigned NOT NULL default '0',
  `name` varchar(255) NOT NULL default '',
  `fallback` char(1) NOT NULL default '',
  `template` varchar(64) NOT NULL default '',
  `mootools` varchar(64) NOT NULL default '',
  `doctype` varchar(32) NOT NULL default '',
  `urchinId` varchar(32) NOT NULL default '',
  `stylesheet` blob NULL,
  `newsfeeds` blob NULL,
  `calendarfeeds` blob NULL,
  `onload` varchar(255) NOT NULL default '',
  `head` text NULL,
  `cols` varchar(32) NOT NULL default '',
  `widthLeft` varchar(255) NOT NULL default '',
  `widthRight` varchar(255) NOT NULL default '',
  `header` char(1) NOT NULL default '',
  `headerHeight` varchar(255) NOT NULL default '',
  `footer` char(1) NOT NULL default '',
  `footerHeight` varchar(255) NOT NULL default '',
  `static` char(1) NOT NULL default '',
  `width` varchar(255) NOT NULL default '',
  `align` varchar(32) NOT NULL default '',
  `sections` blob NULL,
  `sPosition` varchar(32) NOT NULL default '',
  `modules` blob NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Table `tl_log`
-- 

CREATE TABLE `tl_log` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `tstamp` int(10) unsigned NOT NULL default '0',
  `action` varchar(16) NOT NULL default '',
  `source` varchar(255) NOT NULL default '',
  `username` varchar(64) NOT NULL default '',
  `ip` varchar(15) NOT NULL default '',
  `func` varchar(255) NOT NULL default '',
  `browser` varchar(255) NOT NULL default '',
  `text` text NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Table `tl_member`
-- 

CREATE TABLE `tl_member` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `tstamp` int(10) unsigned NOT NULL default '0',
  `firstname` varchar(255) NOT NULL default '',
  `lastname` varchar(255) NOT NULL default '',
  `gender` varchar(255) NOT NULL default '',
  `dateOfBirth` varchar(10) NOT NULL default '',
  `company` varchar(255) NOT NULL default '',
  `email` varchar(255) NOT NULL default '',
  `website` varchar(255) NOT NULL default '',
  `language` varchar(2) NOT NULL default '',
  `street` varchar(255) NOT NULL default '',
  `postal` varchar(255) NOT NULL default '',
  `city` varchar(255) NOT NULL default '',
  `state` varchar(64) NOT NULL default '',
  `country` varchar(32) NOT NULL default '',
  `phone` varchar(255) NOT NULL default '',
  `mobile` varchar(255) NOT NULL default '',
  `fax` varchar(255) NOT NULL default '',
  `groups` blob NULL,
  `login` char(1) NOT NULL default '',
  `username` varchar(64) NOT NULL default '',
  `password` varchar(40) NOT NULL default '',
  `loginCount` smallint(5) unsigned NOT NULL default '3',
  `locked` int(10) unsigned NOT NULL default '0',
  `assignDir` char(1) NOT NULL default '',
  `homeDir` varchar(255) NOT NULL default '',
  `disable` char(1) NOT NULL default '',
  `start` varchar(10) NOT NULL default '',
  `stop` varchar(10) NOT NULL default '',
  `session` blob NULL,
  PRIMARY KEY  (`id`),
  KEY `username` (`username`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Table `tl_member_group`
-- 

CREATE TABLE `tl_member_group` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `tstamp` int(10) unsigned NOT NULL default '0',
  `name` varchar(255) NOT NULL default '',
  `redirect` char(1) NOT NULL default '',
  `jumpTo` smallint(5) unsigned NOT NULL default '0',
  `disable` char(1) NOT NULL default '',
  `start` varchar(10) NOT NULL default '',
  `stop` varchar(10) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Table `tl_module`
-- 

CREATE TABLE `tl_module` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `tstamp` int(10) unsigned NOT NULL default '0',
  `type` varchar(64) NOT NULL default '',
  `headline` varchar(255) NOT NULL default '',
  `name` varchar(255) NOT NULL default '',
  `singleSRC` varchar(255) NOT NULL default '',
  `multiSRC` blob NULL,
  `imgSize` varchar(255) NOT NULL default '',
  `rootPage` smallint(5) unsigned NOT NULL default '0',
  `pages` blob NULL,
  `showLevel` smallint(5) unsigned NOT NULL default '0',
  `levelOffset` smallint(5) unsigned NOT NULL default '0',
  `hardLimit` char(1) NOT NULL default '',
  `showHidden` char(1) NOT NULL default '',
  `showProtected` char(1) NOT NULL default '',
  `navigationTpl` varchar(64) NOT NULL default '',
  `defineRoot` char(1) NOT NULL default '',
  `includeRoot` char(1) NOT NULL default '',
  `customLabel` varchar(64) NOT NULL default '',
  `queryType` varchar(32) NOT NULL default '',
  `searchType` varchar(32) NOT NULL default '',
  `searchTpl` varchar(64) NOT NULL default '',
  `perPage` smallint(5) unsigned NOT NULL default '0',
  `contextLength` smallint(5) unsigned NOT NULL default '0',
  `totalLength` smallint(5) unsigned NOT NULL default '0',
  `editable` blob NULL,
  `cols` varchar(32) NOT NULL default '',
  `memberTpl` varchar(64) NOT NULL default '',
  `jumpTo` smallint(5) unsigned NOT NULL default '0',
  `form` smallint(5) unsigned NOT NULL default '0',
  `html` text NULL,
  `size` varchar(255) NOT NULL default '',
  `alt` varchar(255) NOT NULL default '',
  `url` varchar(255) NOT NULL default '',
  `source` varchar(32) NOT NULL default '',
  `flashvars` varchar(255) NOT NULL default '',
  `altContent` text NULL,
  `useCaption` char(1) NOT NULL default '',
  `transparent` char(1) NOT NULL default '',
  `interactive` char(1) NOT NULL default '',
  `flashID` varchar(255) NOT NULL default '',
  `version` varchar(255) NOT NULL default '',
  `flashJS` text NULL,
  `inColumn` varchar(32) NOT NULL default '',
  `skipFirst` char(1) NOT NULL default '',
  `searchable` char(1) NOT NULL default '',
  `disableCaptcha` char(1) NOT NULL default '',
  `newsletters` blob NULL,
  `space` varchar(255) NOT NULL default '',
  `align` varchar(32) NOT NULL default '',
  `cssID` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Table `tl_page`
-- 

CREATE TABLE `tl_page` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `pid` int(10) unsigned NOT NULL default '0',
  `sorting` int(10) unsigned NOT NULL default '0',
  `tstamp` int(10) unsigned NOT NULL default '0',
  `title` varchar(255) NOT NULL default '',
  `alias` varchar(64) NOT NULL default '',
  `adminEmail` varchar(128) NOT NULL default '',
  `type` varchar(255) NOT NULL default '',
  `language` varchar(2) NOT NULL default '',
  `pageTitle` varchar(255) NOT NULL default '',
  `description` text NULL,
  `url` varchar(255) NOT NULL default '',
  `target` char(1) NOT NULL default '',
  `redirect` varchar(32) NOT NULL default '',
  `autoforward` char(1) NOT NULL default '',
  `dns` varchar(255) NOT NULL default '',
  `fallback` char(1) NOT NULL default '',
  `jumpTo` smallint(5) unsigned NOT NULL default '0',
  `protected` char(1) NOT NULL default '',
  `groups` blob NULL,
  `includeLayout` char(1) NOT NULL default '',
  `layout` int(10) unsigned NOT NULL default '0',
  `includeCache` char(1) NOT NULL default '',
  `cache` int(10) unsigned NOT NULL default '0',
  `includeChmod` char(1) NOT NULL default '',
  `chmod` varchar(255) NOT NULL default '',
  `cuser` int(10) unsigned NOT NULL default '0',
  `cgroup` int(10) unsigned NOT NULL default '0',
  `createSitemap` char(1) NOT NULL default '',
  `sitemapName` varchar(32) NOT NULL default '',
  `sitemapBase` varchar(255) NOT NULL default '',
  `hide` char(1) NOT NULL default '',
  `guests` char(1) NOT NULL default '',
  `noSearch` char(1) NOT NULL default '',
  `cssClass` varchar(64) NOT NULL default '',
  `tabindex` smallint(5) unsigned NOT NULL default '0',
  `accesskey` varchar(2) NOT NULL default '',
  `published` char(1) NOT NULL default '',
  `start` varchar(10) NOT NULL default '',
  `stop` varchar(10) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `pid` (`pid`),
  KEY `alias` (`alias`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Table `tl_search`
-- 

CREATE TABLE `tl_search` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `pid` int(10) unsigned NOT NULL default '0',
  `tstamp` int(10) unsigned NOT NULL default '0',
  `url` varchar(255) NOT NULL default '',
  `title` varchar(255) NOT NULL default '',
  `text` mediumtext NULL,
  `filesize` double unsigned NOT NULL default '0',
  `checksum` varchar(32) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `pid` (`pid`),
  UNIQUE KEY `url` (`url`),
  FULLTEXT KEY `text` (`text`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


-- --------------------------------------------------------

-- 
-- Table `tl_search_index`
-- 

CREATE TABLE `tl_search_index` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `pid` int(10) unsigned NOT NULL default '0',
  `word` varchar(64) NOT NULL default '',
  `relevance` smallint(5) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `pid` (`pid`),
  KEY `word` (`word`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Table `tl_session`
-- 

CREATE TABLE `tl_session` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `pid` int(10) unsigned NOT NULL default '0',
  `tstamp` int(10) unsigned NOT NULL default '0',
  `name` varchar(255) NOT NULL default '',
  `sessionID` varchar(40) NOT NULL default '',
  `ip` varchar(15) NOT NULL default '',
  `hash` varchar(40) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `pid` (`pid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Table `tl_style`
-- 

CREATE TABLE `tl_style` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `pid` int(10) unsigned NOT NULL default '0',
  `sorting` int(10) unsigned NOT NULL default '0',
  `tstamp` int(10) unsigned NOT NULL default '0',
  `comment` varchar(255) NOT NULL default '',
  `selector` varchar(255) NOT NULL default '',
  `category` varchar(32) NOT NULL default '',
  `size` char(1) NOT NULL default '',
  `width` varchar(255) NOT NULL default '',
  `height` varchar(255) NOT NULL default '',
  `trbl` varchar(255) NOT NULL default '',
  `position` varchar(32) NOT NULL default '',
  `overflow` varchar(32) NOT NULL default '',
  `display` varchar(32) NOT NULL default '',
  `floating` varchar(32) NOT NULL default '',
  `clear` varchar(32) NOT NULL default '',
  `alignment` char(1) NOT NULL default '',
  `margin` varchar(255) NOT NULL default '',
  `padding` varchar(255) NOT NULL default '',
  `align` varchar(32) NOT NULL default '',
  `textalign` varchar(32) NOT NULL default '',
  `verticalalign` varchar(32) NOT NULL default '',
  `background` char(1) NOT NULL default '',
  `bgcolor` varchar(6) NOT NULL default '',
  `bgimage` varchar(255) NOT NULL default '',
  `bgposition` varchar(32) NOT NULL default '',
  `bgrepeat` varchar(32) NOT NULL default '',
  `border` char(1) NOT NULL default '',
  `borderwidth` varchar(255) NOT NULL default '',
  `borderstyle` varchar(32) NOT NULL default '',
  `bordercolor` varchar(6) NOT NULL default '',
  `bordercollapse` varchar(32) NOT NULL default '',
  `font` char(1) NOT NULL default '',
  `fontfamily` varchar(255) NOT NULL default '',
  `fontstyle` varchar(255) NOT NULL default '',
  `fontsize` varchar(255) NOT NULL default '',
  `fontcolor` varchar(6) NOT NULL default '',
  `lineheight` varchar(255) NOT NULL default '',
  `whitespace` char(1) NOT NULL default '',
  `list` char(1) NOT NULL default '',
  `liststyletype` varchar(32) NOT NULL default '',
  `liststyleimage` varchar(255) NOT NULL default '',
  `own` text NULL,
  PRIMARY KEY  (`id`),
  KEY `pid` (`pid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Table `tl_style_sheet`
-- 

CREATE TABLE `tl_style_sheet` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `tstamp` int(10) unsigned NOT NULL default '0',
  `name` varchar(64) NOT NULL default '',
  `media` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Table `tl_task`
-- 

CREATE TABLE `tl_task` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `tstamp` int(10) unsigned NOT NULL default '0',
  `createdBy` int(10) unsigned NOT NULL default '0',
  `title` varchar(128) NOT NULL default '',
  `deadline` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Table `tl_task_status`
-- 

CREATE TABLE `tl_task_status` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `pid` int(10) unsigned NOT NULL default '0',
  `tstamp` int(10) unsigned NOT NULL default '0',
  `assignedTo` int(10) unsigned NOT NULL default '0',
  `status` varchar(32) NOT NULL default '',
  `progress` smallint(5) unsigned NOT NULL default '0',
  `comment` text NULL,
  PRIMARY KEY  (`id`),
  KEY `pid` (`pid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Table `tl_undo`
-- 

CREATE TABLE `tl_undo` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `tstamp` int(10) unsigned NOT NULL default '0',
  `fromTable` varchar(255) NOT NULL default '',
  `query` text NULL,
  `affectedRows` smallint(5) unsigned NOT NULL default '0',
  `data` mediumblob NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Table `tl_user`
-- 

CREATE TABLE `tl_user` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `tstamp` int(10) unsigned NOT NULL default '0',
  `username` varchar(64) NOT NULL default '',
  `password` varchar(40) NOT NULL default '',
  `loginCount` smallint(5) unsigned NOT NULL default '3',
  `locked` int(10) unsigned NOT NULL default '0',
  `language` varchar(2) NOT NULL default '',
  `showHelp` char(1) NOT NULL default '',
  `useRTE` char(1) NOT NULL default '',
  `thumbnails` char(1) NOT NULL default '',
  `name` varchar(255) NOT NULL default '',
  `email` varchar(255) NOT NULL default '',
  `disable` char(1) NOT NULL default '',
  `start` varchar(10) NOT NULL default '',
  `stop` varchar(10) NOT NULL default '',
  `session` blob NULL,
  `admin` char(1) NOT NULL default '',
  `groups` blob NULL,
  `modules` blob NULL,
  `inherit` varchar(32) NOT NULL default '',
  `pagemounts` blob NULL,
  `alpty` blob NULL,
  `filemounts` blob NULL,
  `fop` blob NULL,
  `forms` blob NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Table `tl_user_group`
-- 

CREATE TABLE `tl_user_group` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `tstamp` int(10) unsigned NOT NULL default '0',
  `name` varchar(255) NOT NULL default '',
  `modules` blob NULL,
  `pagemounts` blob NULL,
  `alpty` blob NULL,
  `filemounts` blob NULL,
  `fop` blob NULL,
  `forms` blob NULL,
  `alexf` blob NULL,
  `disable` char(1) NOT NULL default '',
  `start` varchar(10) NOT NULL default '',
  `stop` varchar(10) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Table `tl_version`
-- 

CREATE TABLE `tl_version` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `pid` int(10) unsigned NOT NULL default '0',
  `tstamp` int(10) unsigned NOT NULL default '0',
  `version` int(10) unsigned NOT NULL default '1',
  `fromTable` varchar(255) NOT NULL default '',
  `username` varchar(64) NOT NULL default '',
  `active` char(1) NOT NULL default '',
  `data` mediumblob NULL,
  PRIMARY KEY  (`id`),
  KEY `pid` (`pid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
