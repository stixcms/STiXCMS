CREATE TABLE locale (
  locale tinyint(4) NOT NULL auto_increment,
  name varchar(32) NOT NULL default '',
  iso varchar(16) NOT NULL default '',
  PRIMARY KEY (locale)
);


CREATE TABLE cat (
  cat_id int(11) NOT NULL auto_increment,
  parent_id int(11) NOT NULL default '0',
  cat_order int(11) default '100',
  cat_param tinyint(4) default '0',
  status char(1) NOT NULL default '',
  createdate int(11) NOT NULL default '0',
  createdby int(11) NOT NULL default '0',
  lastupdated int(11) NOT NULL default '0',
  lastupdatedby int(11) NOT NULL default '0',
  PRIMARY KEY (cat_id)
);



CREATE TABLE cat_loc (
  cat_id int(11) NOT NULL,
  locale tinyint(4) NOT NULL default '1',
  name varchar(128) NOT NULL default '',
  temp_id int(11) NOT NULL,
  PRIMARY KEY (cat_id,locale)
);

CREATE TABLE users (
  user_id int(11) NOT NULL auto_increment,
  group_id int(11) NOT NULL default '0',
  username varchar(16) NOT NULL default '',
  password varchar(32) NOT NULL default '',
  name varchar(128) default NULL,
  email varchar(64) default NULL,
  status char(1) NOT NULL default '',
  maxartstatus char(1) NOT NULL default '3',
  UNIQUE uu (username),
  PRIMARY KEY (user_id)
);



CREATE TABLE usergroup (
  group_id int(11) NOT NULL auto_increment,
  name varchar(64) default NULL,
  perms varchar(64) default NULL,
  startcat int(11) default NULL,
  PRIMARY KEY (group_id)
);



CREATE TABLE articles (
  art_id int(11) NOT NULL auto_increment,
  title varchar(255) default NULL,
  shortdesc varchar(255) default NULL,
  fulltxt text,
  startdate int(11) default NULL,
  enddate int(11) default NULL,
  art_order smallint(6) default '10',
  locale tinyint(4) NOT NULL default '1',
  status char(1) NOT NULL default 'I',
  createdate int(11) NOT NULL default '0',
  createdby int(11) NOT NULL default '0',
  lastupdated int(11) NOT NULL default '0',
  lastupdatedby int(11) NOT NULL default '0',
  OwnerUserID int(11) NOT NULL default '0',
  OwnerGroupID int(11) NOT NULL default '0',
  RowPerms char(3) NOT NULL default '',
  PRIMARY KEY (art_id)
);


CREATE TABLE attachment (
  att_id int(11) NOT NULL auto_increment,
  mime varchar(128) default NULL,
  URI varchar(255) NOT NULL default '',
  title varchar(255) NOT NULL default '',
  width int(11) default NULL,
  height int(11) default NULL,
  filesize int(11) default NULL,
  typ tinyint(4) NOT NULL default '2',
  attorder smallint(6) NOT NULL default '0',
  status char(1) NOT NULL default 'I',
  createdate int(11) NOT NULL default '0',
  createdby int(11) NOT NULL default '0',
  lastupdated int(11) NOT NULL default '0',
  lastupdatedby int(11) NOT NULL default '0',
  OwnerUserID int(11) NOT NULL default '0',
  OwnerGroupID int(11) NOT NULL default '0',
  RowPerms char(3) NOT NULL default '',
  PRIMARY KEY (att_id)
);


CREATE TABLE ads (
  ad_id int(11) NOT NULL auto_increment,
  name varchar(32) NOT NULL default '',
  att_id int(11) default NULL,
  adtext varchar(255) default NULL,
  aorder int(11) NOT NULL default '0',
  status char(1) NOT NULL default '',
  createdate int(11) NOT NULL default '0',
  createdby int(11) NOT NULL default '0',
  lastupdated int(11) NOT NULL default '0',
  lastupdatedby int(11) NOT NULL default '0',
  OwnerUserID int(11) NOT NULL default '0',
  OwnerGroupID int(11) NOT NULL default '0',
  RowPerms char(3) NOT NULL default '',
  hlink varchar(255) default NULL,
  PRIMARY KEY (ad_id)
);



CREATE TABLE related (
  pri_id int(11) NOT NULL default '0',
  rel_id int(11) NOT NULL default '0',
  rel_type tinyint(4) NOT NULL default '1',
  PRIMARY KEY (pri_id,rel_id,rel_type)
);



CREATE TABLE ads_cat (
  ad_id int(11) NOT NULL default '0',
  cat_id int(11) NOT NULL default '0',
  PRIMARY KEY (ad_id,cat_id)
);



CREATE TABLE art_cat (
  cat_id int(11) NOT NULL default '0',
  art_id int(11) NOT NULL default '0',
  PRIMARY KEY (cat_id,art_id)
);


CREATE TABLE att_cat (
  cat_id int(11) NOT NULL default '0',
  att_id int(11) NOT NULL default '0',
  PRIMARY KEY (cat_id,att_id)
);


CREATE TABLE module (
  mod_id int(11) NOT NULL auto_increment,
  cat_id int(11) NOT NULL default '0',
  URI varchar(255) NOT NULL default '',
  title varchar(255) NOT NULL default '',
  modorder smallint(6) NOT NULL default '0',
  place char(1) NOT NULL default '',
  locale tinyint(4) NOT NULL default '0',
  status char(1) NOT NULL default 'I',
  createdate int(11) NOT NULL default '0',
  createdby int(11) NOT NULL default '0',
  lastupdated int(11) NOT NULL default '0',
  lastupdatedby int(11) NOT NULL default '0',
  OwnerUserID int(11) NOT NULL default '0',
  OwnerGroupID int(11) NOT NULL default '0',
  RowPerms char(3) NOT NULL default '',
  PRIMARY KEY (mod_id)
);

CREATE TABLE template (
  temp_id int(11) NOT NULL auto_increment,
  title varchar(255) NOT NULL default '',
  html text NOT NULL default '',
  status char(1) NOT NULL default 'I',
  createdate int(11) NOT NULL default '0',
  createdby int(11) NOT NULL default '0',
  lastupdated int(11) NOT NULL default '0',
  lastupdatedby int(11) NOT NULL default '0',
  OwnerUserID int(11) NOT NULL default '0',
  OwnerGroupID int(11) NOT NULL default '0',
  RowPerms char(3) NOT NULL default '',
  PRIMARY KEY (temp_id)
);

CREATE TABLE keywords (
  key_id int(11) NOT NULL auto_increment,
  keyword varchar(80) default NULL,
  locale tinyint(4) NOT NULL default '0',  
  createdate int(11) NOT NULL default '0',
  createdby int(11) NOT NULL default '0',
  lastupdated int(11) NOT NULL default '0',
  lastupdatedby int(11) NOT NULL default '0',
  PRIMARY KEY (key_id)
);

CREATE TABLE art_key (
  key_id int(11) NOT NULL default '0',
  art_id int(11) NOT NULL default '0',
  PRIMARY KEY (key_id,art_id)
);

CREATE TABLE member (
  member_id int(11) NOT NULL auto_increment,
  date_created int(11) NOT NULL default '0',
  date_updated int(11) NOT NULL default '0',
  name varchar(80) default NULL,
  lastname varchar(50) default NULL,
  username varchar(16) default NULL,  
  password varchar(16) default NULL,
  address varchar(255) default NULL,
  zip varchar(10) default NULL,
  city varchar(50) default NULL,
  country varchar(50) default NULL,
  phone varchar(50) default NULL,
  fax varchar(50) default NULL,
  email varchar(128) NOT NULL default '',
  company varchar(50) default '',
  afm varchar(10) default '',
  doy varchar(30) default '',
  mailinglist char(1) default NULL,
  status char(1) NOT NULL default '',
  UNIQUE mu (username),
  PRIMARY KEY (member_id)
);

CREATE TABLE config (
  def_locale tinyint not null,
  sr_temp int not null,
  pub_cont char(1) not null
);

CREATE TABLE gallery (
gal_id int NOT NULL auto_increment,
photos_per_page int NULL,
photos_per_line int NULL,
thumbnail_width int NULL,
thumbnail_height int NULL,
title varchar(60) NULL,
status char(1) NULL,
createdate int NULL,
createdby int NULL,
lastupdated int NULL,
lastupdatedby int NULL,
OwnerUserID int NULL,
OwnerGroupID int NULL,
RowPerms int NULL,
cat_id int NULL,
PRIMARY KEY (gal_id)
);


CREATE TABLE `gallery_att` (
galatt_id INT NOT NULL AUTO_INCREMENT ,
gal_id INT NOT NULL ,
att_id1 INT NOT NULL ,
att_id2 INT NOT NULL ,
attorder INT NOT NULL ,
PRIMARY KEY ( `galatt_id` ) 
);

CREATE TABLE `cat_photos` (
  cat_id int(11) NOT NULL default '0',
  locale tinyint(11) NOT NULL default '0',
  att_id int(11) NOT NULL default '0',
  aorder int(11) NOT NULL default '0',
  PRIMARY KEY  (cat_id,locale,att_id)
); 

INSERT INTO locale (locale,name,iso) VALUES (1,'Greek','ISO-8859-7');
INSERT INTO locale (locale,name,iso) VALUES (2,'English','ISO-8859-1');
INSERT INTO usergroup VALUES (1,'Administrators','777777777777',0);
INSERT INTO users VALUES (1,1,'root','st','Master of the Universe','-','A','4');
INSERT INTO config (def_locale,sr_temp,pub_cont) VALUES (1,0,'N');