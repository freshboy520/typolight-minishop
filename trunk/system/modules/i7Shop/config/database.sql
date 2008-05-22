-- **********************************************************
-- *                                                        *
-- * IMPORTANT NOTE                                         *
-- *                                                        *
-- * Do not import this file manually but use the TYPOlight *
-- * install tool to create and maintain database tables!   *
-- *                                                        *
-- **********************************************************


-- --------------------------------------------------------

CREATE TABLE `tl_i7shop` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `sorting` int(10) unsigned NOT NULL default '0',
  `tstamp` int(10) unsigned NOT NULL default '0',
  `name` varchar(255) NOT NULL default '',
  `tax` double unsigned NOT NULL default '0',
	`addImage` char(1) NOT NULL default '',
  `singleSRC` varchar(255) NOT NULL default '',
  `size` varchar(255) NOT NULL default '',
  `alt` varchar(255) NOT NULL default '',
  `imagemargin` varchar(255) NOT NULL default '',
  `fullsize` char(1) NOT NULL default '',
  `maincurrency` varchar(64) NOT NULL default '',
	`currencyformat` varchar(64) NOT NULL default '',
  `tax` double unsigned NOT NULL default '0',
  `discount10` double unsigned NOT NULL default '0',
  `discount100` double unsigned NOT NULL default '0',
  `article_per_page` int(10) unsigned NOT NULL default '0',
  `discount_group_1` varchar(64) NOT NULL default '',
`discount_group_2` varchar(64) NOT NULL default '',
`discount_group_3` varchar(64) NOT NULL default '',
`discount_group_4` varchar(64) NOT NULL default '',
`discount_group_5` varchar(64) NOT NULL default '',
  `info_email_address` varchar(128) NOT NULL default '',
  `show_prices_for_unregistered_user` char(1) NOT NULL default '',
  `shipping_fixed` double unsigned NOT NULL default '0',
  `shipping_till_value` double unsigned NOT NULL default '0',
	`payment_account_id` varchar(255) NOT NULL default '',
	`payment_user_id` varchar(255) NOT NULL default '',
	`payment_transaction_password` varchar(255) NOT NULL default '',
	`payment_adminaction_password` varchar(255) NOT NULL default '',
	
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


CREATE TABLE `tl_i7shop_articletree` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `pid` int(10) unsigned NOT NULL default '0',
  `sorting` int(10) unsigned NOT NULL default '0',
  `tstamp` int(10) unsigned NOT NULL default '0',
  `title` varchar(255) NOT NULL default '',
  `alias` varchar(64) NOT NULL default '',
  `type` varchar(64) NOT NULL default '',
  `protected` char(1) NOT NULL default '',
  `teaser` text NULL,
  `addOptions` char(1) NOT NULL default '',
  `addImage` char(1) NOT NULL default '',
  `maincurrency` varchar(64) NOT NULL default '',
  `tax` double unsigned NOT NULL default '0',
  `discount10` double unsigned NOT NULL default '0',
  `discount100` double unsigned NOT NULL default '0',
  `article_per_page` int(10) unsigned NOT NULL default '0',
  `discount_group_1` varchar(64) NOT NULL default '',
	`discount_group_2` varchar(64) NOT NULL default '',
	`discount_group_3` varchar(64) NOT NULL default '',
	`discount_group_4` varchar(64) NOT NULL default '',
	`discount_group_5` varchar(64) NOT NULL default '',
  `info_email_address` varchar(128) NOT NULL default '',
  `show_prices_for_unregistered_user` char(1) NOT NULL default '',
  `shipping_fixed` double unsigned NOT NULL default '0',
  `shipping_till_value` double unsigned NOT NULL default '0',
  `addImage` char(1) NOT NULL default '',
  `singleSRC` varchar(255) NOT NULL default '',
  `size` varchar(255) NOT NULL default '',
  `alt` varchar(255) NOT NULL default '',
  `imagemargin` varchar(255) NOT NULL default '',
  `fullsize` char(1) NOT NULL default '',
	`url` varchar(255) NOT NULL default '',
	`artnr` varchar(255) NOT NULL default '',
	`text` text NULL,
	`teaser` text NULL,
	`price` varchar(64) NOT NULL default '',
	`options` varchar(255) NOT NULL default '',
	`weight` varchar(64) NOT NULL default '',
	`url` varchar(64) NOT NULL default '',
	`addImages` char(1) NOT NULL default '',
	`images` blob NULL,

  PRIMARY KEY  (`id`),
  KEY `pid` (`pid`),
  KEY `alias` (`alias`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



CREATE TABLE `tl_i7shop_order` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `tstamp` int(10) unsigned NOT NULL default '0',
	`pid` int(10) unsigned NOT NULL default '0',
  `order_date` int(10) unsigned NOT NULL default '0',
  `order_status` char(1) NOT NULL default '',
  `shipping` varchar(64) NOT NULL default '',
  `taxes` varchar(64) NOT NULL default '',
	`basket_total` varchar(64) NOT NULL default '',
	`total` varchar(64) NOT NULL default '',
	`client_name` varchar(64) NOT NULL default '',
	`client_id` varchar(64) NOT NULL default '',
	`client_bill_address` text NULL,
	`client_ship_address` text NULL,
	`basket` text NULL,
	`data` text NULL,
	`payment_booking_number` varchar(64) NOT NULL default '',
	
  PRIMARY KEY  (`id`),
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `tl_i7shop_order_article` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `tstamp` int(10) unsigned NOT NULL default '0',
  `pid` int(10) unsigned NOT NULL default '0',
  `article_id` varchar(64) NOT NULL default '',
  `quantity` varchar(64) NOT NULL default '',
  PRIMARY KEY  (`id`),
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


CREATE TABLE `tl_i7shop_customer_address` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `pid` int(10) unsigned NOT NULL default '0',
  `tstamp` int(10) unsigned NOT NULL default '0',
  `type` int(10) unsigned NOT NULL default '0',
  `company` varchar(64) NOT NULL default '',
  `lastname` varchar(64) NOT NULL default '',
  `firstname` varchar(64) NOT NULL default '',
  `address1` varchar(64) NOT NULL default '',
  `address2` varchar(64) NOT NULL default '',
  `address3` varchar(64) NOT NULL default '',
  `zipcode` varchar(12) NOT NULL default '',
  `location` varchar(64) NOT NULL default '',
  `country` varchar(64) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `pid` (`pid`),
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

CREATE TABLE `tl_module` (
  `i7shop_defineRootCategorie` smallint(5) unsigned NOT NULL default '0',
 	`i7shop_template` varchar(64) NOT NULL default '',
	`i7shop_hide_on_page` blob NULL,
	`i7shop_basket_info_always_visible` smallint(5) NULL default '0',
	`i7shop_shopsystem` int(10) unsigned NOT NULL default '0',
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `tl_content` (
  `i7shop_addtobasket_id` varchar(255) NOT NULL default '',
	`i7shop_addtobasket_alias` varchar(255) NOT NULL default '',
	`i7shop_addtobasket_individual_text` varchar(255) NOT NULL default '',
	`i7shop_addtobasket_individual_url` varchar(255) NOT NULL default '',
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
