# MySQL dump 8.14
#
# Host: localhost    Database: stcmt2
#--------------------------------------------------------
# Server version	3.23.41-log

#
# Table structure for table 'ads'
#

DROP TABLE IF EXISTS ads;
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
  PRIMARY KEY  (ad_id)
) TYPE=MyISAM;

#
# Dumping data for table 'ads'
#

INSERT INTO ads VALUES (1,'small button',1,'',10,'A',1067849882,1,1067849882,1,1,1,'731','http://www.audi.gr/');

#
# Table structure for table 'ads_cat'
#

DROP TABLE IF EXISTS ads_cat;
CREATE TABLE ads_cat (
  ad_id int(11) NOT NULL default '0',
  cat_id int(11) NOT NULL default '0',
  PRIMARY KEY  (ad_id,cat_id)
) TYPE=MyISAM;

#
# Dumping data for table 'ads_cat'
#

INSERT INTO ads_cat VALUES (1,1);
INSERT INTO ads_cat VALUES (1,2);
INSERT INTO ads_cat VALUES (1,3);
INSERT INTO ads_cat VALUES (1,4);
INSERT INTO ads_cat VALUES (1,5);
INSERT INTO ads_cat VALUES (1,6);

#
# Table structure for table 'art_cat'
#

DROP TABLE IF EXISTS art_cat;
CREATE TABLE art_cat (
  cat_id int(11) NOT NULL default '0',
  art_id int(11) NOT NULL default '0',
  PRIMARY KEY  (cat_id,art_id)
) TYPE=MyISAM;

#
# Dumping data for table 'art_cat'
#

INSERT INTO art_cat VALUES (1,1);
INSERT INTO art_cat VALUES (1,6);
INSERT INTO art_cat VALUES (2,2);
INSERT INTO art_cat VALUES (3,3);
INSERT INTO art_cat VALUES (3,4);
INSERT INTO art_cat VALUES (4,5);

#
# Table structure for table 'art_key'
#

DROP TABLE IF EXISTS art_key;
CREATE TABLE art_key (
  key_id int(11) NOT NULL default '0',
  art_id int(11) NOT NULL default '0',
  PRIMARY KEY  (key_id,art_id)
) TYPE=MyISAM;

#
# Dumping data for table 'art_key'
#


#
# Table structure for table 'articles'
#

DROP TABLE IF EXISTS articles;
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
  PRIMARY KEY  (art_id)
) TYPE=MyISAM;

#
# Dumping data for table 'articles'
#

INSERT INTO articles VALUES (1,'Η ΕΤΑΙΡΙΑ ΜΑΣ','Σύντομη περιγραφή της εταιρίας','<P>Η εταιρία μας είναι μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα.</P>\r\n<P>Μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα <STRONG>μπλα μπλα</STRONG> μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα. </P>\r\n<P>Μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα. </P>',0,0,10,1,'3',1067618067,1,1067619005,1,1,1,'731');
INSERT INTO articles VALUES (2,'TA ΠΡΟΪΟΝΤΑ ΜΑΣ','Εισαγωγή στα προϊόντα της εταιρίας','<P>Η εταιρία μας έχει προϊόντα για ιδιώτες και για επιχειρήσεις.</P>\r\n<P>Επιλέξτε μιά από τις υποενότητες στο αριστερό μενού.</P>',0,0,10,1,'3',1067618257,1,1067619025,1,1,1,'731');
INSERT INTO articles VALUES (3,'ΔΕΛΤΙΟ ΤΥΠΟΥ 1','Μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα','<P>Μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα</P>\r\n<P>Μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα </P>\r\n<P>Μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα. </P>',0,0,10,1,'3',1067618350,1,1067850472,1,1,1,'731');
INSERT INTO articles VALUES (4,'ΔΕΛΤΙΟ ΤΥΠΟΥ 2','Μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα','<P>Μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα</P>\r\n<P>Μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα </P>\r\n<P>Μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα μπλα. </P>',0,0,20,1,'3',1067618369,1,1067850479,1,1,1,'731');
INSERT INTO articles VALUES (5,'ΣΤΟΙΧΕΙΑ ΕΠΙΚΟΙΝΩΝΙΑΣ','Στοιχεία επικοινωνίας της εταιρίας','<P>Μπορείτε να μας βρείτε στα τηλέφωνα:<BR>210-555 65656 και 210-555 66666</P>\r\n<P>ή να επικοινωνήσετε με e-mail στη διεύθυνση: <A href=\"mailto:info@nobody.com\">info@nobody.com</A></P>',0,0,10,1,'3',1067850413,1,1067850413,1,1,1,'731');
INSERT INTO articles VALUES (6,'OUR COMPANY','Description of our company','<P>blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah </P>\r\n<P>blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah </P>\r\n<P>blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah</P>\r\n<P>blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah </P>\r\n<P>blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah </P>\r\n<P>blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah blah </P>',0,0,10,2,'3',1067850590,1,1067850590,1,1,1,'731');

#
# Table structure for table 'att_cat'
#

DROP TABLE IF EXISTS att_cat;
CREATE TABLE att_cat (
  cat_id int(11) NOT NULL default '0',
  att_id int(11) NOT NULL default '0',
  PRIMARY KEY  (cat_id,att_id)
) TYPE=MyISAM;

#
# Dumping data for table 'att_cat'
#

INSERT INTO att_cat VALUES (7,1);
INSERT INTO att_cat VALUES (11,2);
INSERT INTO att_cat VALUES (12,3);

#
# Table structure for table 'attachment'
#

DROP TABLE IF EXISTS attachment;
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
  PRIMARY KEY  (att_id)
) TYPE=MyISAM;

#
# Dumping data for table 'attachment'
#

INSERT INTO attachment VALUES (1,'image/pjpeg','JPG/S/1.jpg','Test 1',159,71,5208,2,10,'A',1067846899,1,1067846899,1,1,1,'731');
INSERT INTO attachment VALUES (2,'image/pjpeg','JPG/S/2.JPG','test image',400,300,15726,2,10,'A',1067850012,1,1067850012,1,1,1,'731');
INSERT INTO attachment VALUES (3,'application/x-zip-compressed','ZIP/T/3.zip','Download reference manual',-1,-1,196097,3,10,'A',1067850187,1,1067850187,1,1,1,'731');

#
# Table structure for table 'cat'
#

DROP TABLE IF EXISTS cat;
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
  PRIMARY KEY  (cat_id)
) TYPE=MyISAM;

#
# Dumping data for table 'cat'
#

INSERT INTO cat VALUES (1,0,10,1,'A',0,1,1067616395,1);
INSERT INTO cat VALUES (2,0,20,1,'A',1067616407,1,1067616407,1);
INSERT INTO cat VALUES (3,0,30,1,'A',1067616432,1,1067618100,1);
INSERT INTO cat VALUES (4,0,40,1,'A',1067616473,1,1067616473,1);
INSERT INTO cat VALUES (5,2,10,1,'A',1067616505,1,1067616505,1);
INSERT INTO cat VALUES (6,2,20,1,'A',1067616518,1,1067616518,1);
INSERT INTO cat VALUES (7,0,10,2,'I',1067846841,1,1067846841,1);
INSERT INTO cat VALUES (8,0,10,3,'I',1067846853,1,1067846853,1);
INSERT INTO cat VALUES (9,0,20,3,'I',1067846861,1,1067846861,1);
INSERT INTO cat VALUES (10,0,30,3,'I',1067846867,1,1067846867,1);
INSERT INTO cat VALUES (11,0,20,2,'I',1067849942,1,1067849942,1);
INSERT INTO cat VALUES (12,0,40,3,'I',1067850148,1,1067850148,1);

#
# Table structure for table 'cat_loc'
#

DROP TABLE IF EXISTS cat_loc;
CREATE TABLE cat_loc (
  cat_id int(11) NOT NULL default '0',
  locale tinyint(4) NOT NULL default '1',
  name varchar(128) NOT NULL default '',
  temp_id int(11) NOT NULL default '0',
  PRIMARY KEY  (cat_id,locale)
) TYPE=MyISAM;

#
# Dumping data for table 'cat_loc'
#

INSERT INTO cat_loc VALUES (1,1,'Η εταιρία',1);
INSERT INTO cat_loc VALUES (1,2,'Company',1);
INSERT INTO cat_loc VALUES (2,1,'Προϊόντα',1);
INSERT INTO cat_loc VALUES (2,2,'Products',1);
INSERT INTO cat_loc VALUES (3,2,'News',1);
INSERT INTO cat_loc VALUES (3,1,'Νέα',1);
INSERT INTO cat_loc VALUES (4,1,'Επικοινωνία',1);
INSERT INTO cat_loc VALUES (4,2,'Contact',1);
INSERT INTO cat_loc VALUES (5,1,'Για ιδιώτες',1);
INSERT INTO cat_loc VALUES (5,2,'Private use',1);
INSERT INTO cat_loc VALUES (6,1,'Για επιχειρήσεις',1);
INSERT INTO cat_loc VALUES (6,2,'Corporate use',1);
INSERT INTO cat_loc VALUES (7,1,'Banners',0);
INSERT INTO cat_loc VALUES (8,1,'PDF',0);
INSERT INTO cat_loc VALUES (9,1,'WORD',0);
INSERT INTO cat_loc VALUES (10,1,'EXCEL',0);
INSERT INTO cat_loc VALUES (11,1,'Images',0);
INSERT INTO cat_loc VALUES (12,1,'Downloads',0);

#
# Table structure for table 'keywords'
#

DROP TABLE IF EXISTS keywords;
CREATE TABLE keywords (
  key_id int(11) NOT NULL auto_increment,
  keyword varchar(80) default NULL,
  locale tinyint(4) NOT NULL default '0',
  createdate int(11) NOT NULL default '0',
  createdby int(11) NOT NULL default '0',
  lastupdated int(11) NOT NULL default '0',
  lastupdatedby int(11) NOT NULL default '0',
  PRIMARY KEY  (key_id)
) TYPE=MyISAM;

#
# Dumping data for table 'keywords'
#


#
# Table structure for table 'locale'
#

DROP TABLE IF EXISTS locale;
CREATE TABLE locale (
  locale tinyint(4) NOT NULL auto_increment,
  name varchar(32) NOT NULL default '',
  iso varchar(16) NOT NULL default '',
  PRIMARY KEY  (locale)
) TYPE=MyISAM;

#
# Dumping data for table 'locale'
#

INSERT INTO locale VALUES (1,'Greek','ISO-8859-7');
INSERT INTO locale VALUES (2,'English','ISO-8859-7');

#
# Table structure for table 'mailer'
#

DROP TABLE IF EXISTS mailer;
CREATE TABLE mailer (
  mailer_id int(11) NOT NULL auto_increment,
  fromaddr varchar(255) NOT NULL default '',
  subject varchar(255) NOT NULL default '',
  body text NOT NULL,
  lastsent int(11) NOT NULL default '0',
  lastsentcount int(11) NOT NULL default '0',
  createdate int(11) NOT NULL default '0',
  createdby int(11) NOT NULL default '0',
  lastupdated int(11) NOT NULL default '0',
  lastupdatedby int(11) NOT NULL default '0',
  OwnerUserID int(11) NOT NULL default '0',
  OwnerGroupID int(11) NOT NULL default '0',
  RowPerms char(3) NOT NULL default '',
  PRIMARY KEY  (mailer_id)
) TYPE=MyISAM;

#
# Dumping data for table 'mailer'
#


#
# Table structure for table 'mailqueue'
#

DROP TABLE IF EXISTS mailqueue;
CREATE TABLE mailqueue (
  row_id int(11) NOT NULL auto_increment,
  mailer_id int(11) NOT NULL default '0',
  email varchar(128) NOT NULL default '',
  issent char(1) NOT NULL default '',
  PRIMARY KEY  (row_id)
) TYPE=MyISAM;

#
# Dumping data for table 'mailqueue'
#


#
# Table structure for table 'member'
#

DROP TABLE IF EXISTS member;
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
  PRIMARY KEY  (member_id),
  UNIQUE KEY mu (username)
) TYPE=MyISAM;

#
# Dumping data for table 'member'
#


#
# Table structure for table 'module'
#

DROP TABLE IF EXISTS module;
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
  PRIMARY KEY  (mod_id)
) TYPE=MyISAM;

#
# Dumping data for table 'module'
#


#
# Table structure for table 'related'
#

DROP TABLE IF EXISTS related;
CREATE TABLE related (
  pri_id int(11) NOT NULL default '0',
  rel_id int(11) NOT NULL default '0',
  rel_type tinyint(4) NOT NULL default '1',
  PRIMARY KEY  (pri_id,rel_id,rel_type)
) TYPE=MyISAM;

#
# Dumping data for table 'related'
#

INSERT INTO related VALUES (2,2,2);
INSERT INTO related VALUES (2,3,3);

#
# Table structure for table 'template'
#

DROP TABLE IF EXISTS template;
CREATE TABLE template (
  temp_id int(11) NOT NULL auto_increment,
  title varchar(255) NOT NULL default '',
  html text NOT NULL,
  status char(1) NOT NULL default 'I',
  createdate int(11) NOT NULL default '0',
  createdby int(11) NOT NULL default '0',
  lastupdated int(11) NOT NULL default '0',
  lastupdatedby int(11) NOT NULL default '0',
  OwnerUserID int(11) NOT NULL default '0',
  OwnerGroupID int(11) NOT NULL default '0',
  RowPerms char(3) NOT NULL default '',
  PRIMARY KEY  (temp_id)
) TYPE=MyISAM;

#
# Dumping data for table 'template'
#

INSERT INTO template VALUES (1,'main','<HTML>\r\n<HEAD>\r\n[%CHARSET%]\r\n\r\n<STYLE>\r\n<!--\r\n.node_table {\r\n	background-color: Silver;\r\n	font-family: Verdana, Geneva, Arial, Helvetica, sans-serif;\r\n	font-size: 11px;\r\n	width: 200px;\r\n}\r\nA.node:link,A.node:active,A.node:visited {\r\n	font-family: Verdana, Geneva, Arial, Helvetica, sans-serif;\r\n	font-size: 11px;\r\n	color: Black;	\r\n	text-decoration: none;\r\n}\r\nA.node:hover {\r\n	font-family: Verdana, Geneva, Arial, Helvetica, sans-serif;\r\n	font-size: 11px;\r\n	color: Navy;	\r\n	text-decoration: underline;	\r\n}\r\nA:link,A:active,A:visited {\r\n	font-family: Verdana, Geneva, Arial, Helvetica, sans-serif;\r\n	font-size: 11px;\r\n	color: Black;	\r\n	text-decoration: none;\r\n}\r\nA:hover {\r\n	font-family: Verdana, Geneva, Arial, Helvetica, sans-serif;\r\n	font-size: 11px;\r\n	color: Navy;	\r\n	text-decoration: underline;	\r\n}\r\n.node_off {\r\n	font-family: Verdana, Geneva, Arial, Helvetica, sans-serif;\r\n	font-size: 11px;\r\n	color: Black;	\r\n}\r\n.node_on {\r\n	font-family: Verdana, Geneva, Arial, Helvetica, sans-serif;\r\n	font-size: 11px;\r\n	color: White;	\r\n	background-color: Black;\r\n}	\r\nbody,td {\r\n	font-family: Verdana, Geneva, Arial, Helvetica, sans-serif;\r\n	font-size: 11px;	\r\n	background-color: White;\r\n	color: Black;\r\n}\r\ninput {\r\n	font-family: Verdana, Geneva, Arial, Helvetica, sans-serif;\r\n	font-size: 11px;	\r\n	background-color: White;\r\n	color: Navy;	\r\n	border: 1px inset;\r\n	width: 160px;\r\n}\r\n// -->\r\n</STYLE>\r\n\r\n<TITLE>Demo website with WORKFLOW</TITLE>\r\n</HEAD>\r\n\r\n<BODY>\r\n\r\n<table border=0 width=100%>\r\n<tr>\r\n<td width=20%><b>SITE LOGO</b></td>\r\n<td colspan=2 align=right>&nbsp;<a href=x_switchlocale.php?setlocale=1>ελληνικά</a>-<a href=x_switchlocale.php?setlocale=2>english</a></td>\r\n</tr>\r\n<tr>\r\n<td width=20% valign=top>\r\n	[%MENU%]\r\n	\r\n	<BR>\r\n	Search:\r\n	[%SEARCHFORM%]\r\n	\r\n</td>\r\n<td width=60% valign=top>\r\n	[%PATH%]\r\n	<hr>\r\n	\r\n	<div align=right style=\"position:relative\">[%RELATED_MEDIA_IN(0)%]</div>\r\n	\r\n	[%CONTENT%]\r\n	\r\n</td>\r\n<td width=20% valign=top>\r\n\r\n	[%ADS%]\r\n	\r\n	<BR><BR>\r\n	\r\n	[%RELATED_FILES%]\r\n	\r\n	<BR><BR>\r\n	\r\n	[%RELATED_ARTICLES%]\r\n</td>\r\n</tr>\r\n</table>\r\n\r\n</BODY>\r\n</HTML>','A',1067617924,1,1067617924,1,1,1,'731');

#
# Table structure for table 'usergroup'
#

DROP TABLE IF EXISTS usergroup;
CREATE TABLE usergroup (
  group_id int(11) NOT NULL auto_increment,
  name varchar(64) default NULL,
  perms varchar(64) default NULL,
  startcat int(11) default NULL,
  PRIMARY KEY  (group_id)
) TYPE=MyISAM;

#
# Dumping data for table 'usergroup'
#

INSERT INTO usergroup VALUES (1,'Administrators','777777777777',0);
INSERT INTO usergroup VALUES (2,'Editors','177771071170',0);

#
# Table structure for table 'users'
#

DROP TABLE IF EXISTS users;
CREATE TABLE users (
  user_id int(11) NOT NULL auto_increment,
  group_id int(11) NOT NULL default '0',
  username varchar(16) NOT NULL default '',
  password varchar(32) NOT NULL default '',
  name varchar(128) default NULL,
  email varchar(64) default NULL,
  status char(1) NOT NULL default '',
  maxartstatus char(1) NOT NULL default '3',
  PRIMARY KEY  (user_id),
  UNIQUE KEY uu (username)
) TYPE=MyISAM;

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
)


CREATE TABLE `gallery_att` (
galatt_id INT NOT NULL AUTO_INCREMENT ,
gal_id INT NOT NULL ,
att_id1 INT NOT NULL ,
att_id2 INT NOT NULL ,
attorder INT NOT NULL ,
PRIMARY KEY ( `galatt_id` ) 
);

#
# Dumping data for table 'users'
#

INSERT INTO users VALUES (1,1,'root','steficon','Master of the Universe','-','A','4');
INSERT INTO users VALUES (2,2,'edit','edit','Content Editor','-','A','1');
INSERT INTO users VALUES (3,2,'pub','pub','Content publisher','-','A','4');

