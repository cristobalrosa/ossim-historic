
use snort;

CREATE TABLE IF NOT EXISTS last_update ( `date` TIMESTAMP NOT NULL );
DELETE from last_update;
INSERT IGNORE INTO last_update VALUES ('1970-01-01 00:00:00');
ALTER TABLE reference_system  ADD icon MEDIUMBLOB NOT NULL;
ALTER TABLE reference_system  ADD url VARCHAR(255) NOT NULL;
ALTER TABLE sig_reference CHANGE `sig_id` `plugin_id` INT( 11 ) NOT NULL;
ALTER TABLE sig_reference CHANGE `ref_seq` `plugin_sid` INT( 11 ) NOT NULL;
ALTER TABLE sig_reference DROP PRIMARY KEY , ADD PRIMARY KEY ( `plugin_id` , `plugin_sid` , `ref_id` );

ALTER TABLE `extra_data` CHANGE `filename` `filename` TEXT,  CHANGE `username` `username` TEXT, CHANGE `password` `password` TEXT, CHANGE `userdata1` `userdata1` TEXT, CHANGE `userdata2` `userdata2` TEXT, CHANGE `userdata3` `userdata3` TEXT, CHANGE `userdata4` `userdata4` TEXT, CHANGE `userdata5` `userdata5` TEXT, CHANGE `userdata6` `userdata6` TEXT, CHANGE `userdata7` `userdata7` TEXT, CHANGE `userdata8` `userdata8` TEXT, CHANGE `userdata9` `userdata9` TEXT;
ALTER TABLE extra_data ADD data_payload TEXT DEFAULT NULL AFTER userdata9;

update extra_data left join data on extra_data.sid=data.sid and extra_data.cid=data.cid set extra_data.data_payload=data.data_payload;

DROP TABLE IF EXISTS `acid_event_input`;
CREATE TABLE IF NOT EXISTS `acid_event_input` (
  `sid` int(10) unsigned NOT NULL,
  `cid` int(10) unsigned NOT NULL,
  `timestamp` datetime NOT NULL,
  `ip_src` int(10) unsigned default NULL,
  `ip_dst` int(10) unsigned default NULL,
  `ip_proto` int(11) default NULL,
  `layer4_sport` int(10) unsigned default NULL,
  `layer4_dport` int(10) unsigned default NULL,
  `ossim_type` int(11) default '1',
  `ossim_priority` int(11) default '1',
  `ossim_reliability` int(11) default '1',
  `ossim_asset_src` int(11) default '1',
  `ossim_asset_dst` int(11) default '1',
  `ossim_risk_c` int(11) default '1',
  `ossim_risk_a` int(11) default '1',
  `plugin_id` int(11) default NULL,
  `plugin_sid` int(11) default NULL,
  PRIMARY KEY  (`sid`,`cid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

INSERT INTO acid_event_input SELECT a.sid, a.cid, a.timestamp, a.ip_src, a.ip_dst, a.ip_proto, a.layer4_sport, a.layer4_dport, a.ossim_type, a.ossim_priority, a.ossim_reliability, a.ossim_asset_src, a.ossim_asset_dst, a.ossim_risk_c, a.ossim_risk_a, e.plugin_id, e.plugin_sid FROM acid_event a, ossim_event e WHERE a.sid=e.sid AND a.cid=e.cid;

DROP TABLE IF EXISTS `ac_alerts_ipdst`;
CREATE TABLE IF NOT EXISTS `ac_alerts_ipdst` (
  `day` date NOT NULL,
  `ip_dst` int(10) unsigned NOT NULL,
  `plugin_id` int(11) NOT NULL default '0',
  `plugin_sid` int(11) NOT NULL default '0',
  PRIMARY KEY  (`plugin_id`,`plugin_sid`,`day`,`ip_dst`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `ac_alerts_ipsrc`;
CREATE TABLE IF NOT EXISTS `ac_alerts_ipsrc` (
  `day` date NOT NULL,
  `ip_src` int(10) unsigned NOT NULL,
  `plugin_id` int(11) NOT NULL default '0',
  `plugin_sid` int(11) NOT NULL default '0',
  PRIMARY KEY  (`plugin_id`,`plugin_sid`,`day`,`ip_src`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `ac_alerts_sid`;
CREATE TABLE IF NOT EXISTS `ac_alerts_sid` (
  `day` date NOT NULL,
  `sid` int(10) unsigned NOT NULL,
  `plugin_id` int(11) NOT NULL default '0',
  `plugin_sid` int(11) NOT NULL default '0',
  PRIMARY KEY  (`plugin_id`,`plugin_sid`,`day`,`sid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `ac_alerts_signature`;
CREATE TABLE IF NOT EXISTS `ac_alerts_signature` (
  `day` date NOT NULL,
  `sig_cnt` int(11) NOT NULL,
  `first_timestamp` datetime NOT NULL,
  `last_timestamp` datetime NOT NULL,
  `plugin_id` int(11) NOT NULL default '0',
  `plugin_sid` int(11) NOT NULL default '0',
  PRIMARY KEY  (`plugin_id`,`plugin_sid`,`day`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `ac_dstaddr_ipdst`;
CREATE TABLE IF NOT EXISTS `ac_dstaddr_ipdst` (
  `ip_dst` int(10) unsigned NOT NULL,
  `day` date NOT NULL,
  `cid` int(11) NOT NULL,
  PRIMARY KEY  (`ip_dst`,`day`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `ac_dstaddr_ipsrc`;
CREATE TABLE IF NOT EXISTS `ac_dstaddr_ipsrc` (
  `ip_dst` int(10) unsigned NOT NULL,
  `day` date NOT NULL,
  `ip_src` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`ip_dst`,`day`,`ip_src`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `ac_dstaddr_sid`;
CREATE TABLE IF NOT EXISTS `ac_dstaddr_sid` (
  `ip_dst` int(10) unsigned NOT NULL,
  `day` date NOT NULL,
  `sid` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`ip_dst`,`day`,`sid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `ac_dstaddr_signature`;
CREATE TABLE IF NOT EXISTS `ac_dstaddr_signature` (
  `ip_dst` int(10) unsigned NOT NULL,
  `day` date NOT NULL,
  `plugin_id` int(11) NOT NULL default '0',
  `plugin_sid` int(11) NOT NULL default '0',
  PRIMARY KEY  (`ip_dst`,`day`,`plugin_id`,`plugin_sid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `ac_layer4_dport`;
CREATE TABLE IF NOT EXISTS `ac_layer4_dport` (
  `layer4_dport` int(10) unsigned NOT NULL,
  `ip_proto` int(10) unsigned NOT NULL,
  `day` date NOT NULL,
  `cid` int(10) unsigned NOT NULL,
  `first_timestamp` datetime NOT NULL,
  `last_timestamp` datetime NOT NULL,
  PRIMARY KEY  (`layer4_dport`,`ip_proto`,`day`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `ac_layer4_dport_ipdst`;
CREATE TABLE IF NOT EXISTS `ac_layer4_dport_ipdst` (
  `layer4_dport` int(10) unsigned NOT NULL,
  `ip_proto` int(10) unsigned NOT NULL,
  `day` date NOT NULL,
  `ip_dst` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`layer4_dport`,`ip_proto`,`day`,`ip_dst`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `ac_layer4_dport_ipsrc`;
CREATE TABLE IF NOT EXISTS `ac_layer4_dport_ipsrc` (
  `layer4_dport` int(10) unsigned NOT NULL,
  `ip_proto` int(10) unsigned NOT NULL,
  `day` date NOT NULL,
  `ip_src` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`layer4_dport`,`ip_proto`,`day`,`ip_src`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `ac_layer4_dport_sid`;
CREATE TABLE IF NOT EXISTS `ac_layer4_dport_sid` (
  `layer4_dport` int(10) unsigned NOT NULL,
  `ip_proto` int(10) unsigned NOT NULL,
  `day` date NOT NULL,
  `sid` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`layer4_dport`,`ip_proto`,`day`,`sid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `ac_layer4_dport_signature`;
CREATE TABLE IF NOT EXISTS `ac_layer4_dport_signature` (
  `layer4_dport` int(10) unsigned NOT NULL,
  `ip_proto` int(10) unsigned NOT NULL,
  `day` date NOT NULL,
  `plugin_id` int(11) NOT NULL default '0',
  `plugin_sid` int(11) NOT NULL default '0',
  PRIMARY KEY  (`layer4_dport`,`ip_proto`,`day`,`plugin_id`,`plugin_sid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `ac_layer4_sport`;
CREATE TABLE IF NOT EXISTS `ac_layer4_sport` (
  `layer4_sport` int(10) unsigned NOT NULL,
  `ip_proto` int(10) unsigned NOT NULL,
  `day` date NOT NULL,
  `cid` int(10) unsigned NOT NULL,
  `first_timestamp` datetime NOT NULL,
  `last_timestamp` datetime NOT NULL,
  PRIMARY KEY  (`layer4_sport`,`ip_proto`,`day`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `ac_layer4_sport_ipdst`;
CREATE TABLE IF NOT EXISTS `ac_layer4_sport_ipdst` (
  `layer4_sport` int(10) unsigned NOT NULL,
  `ip_proto` int(10) unsigned NOT NULL,
  `day` date NOT NULL,
  `ip_dst` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`layer4_sport`,`ip_proto`,`day`,`ip_dst`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `ac_layer4_sport_ipsrc`;
CREATE TABLE IF NOT EXISTS `ac_layer4_sport_ipsrc` (
  `layer4_sport` int(10) unsigned NOT NULL,
  `ip_proto` int(10) unsigned NOT NULL,
  `day` date NOT NULL,
  `ip_src` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`layer4_sport`,`ip_proto`,`day`,`ip_src`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `ac_layer4_sport_sid`;
CREATE TABLE IF NOT EXISTS `ac_layer4_sport_sid` (
  `layer4_sport` int(10) unsigned NOT NULL,
  `ip_proto` int(10) unsigned NOT NULL,
  `day` date NOT NULL,
  `sid` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`layer4_sport`,`ip_proto`,`day`,`sid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `ac_layer4_sport_signature`;
CREATE TABLE IF NOT EXISTS `ac_layer4_sport_signature` (
  `layer4_sport` int(10) unsigned NOT NULL,
  `ip_proto` int(10) unsigned NOT NULL,
  `day` date NOT NULL,
  `plugin_id` int(11) NOT NULL default '0',
  `plugin_sid` int(11) NOT NULL default '0',
  PRIMARY KEY  (`layer4_sport`,`ip_proto`,`day`,`plugin_id`,`plugin_sid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `ac_sensor_ipdst`;
CREATE TABLE IF NOT EXISTS `ac_sensor_ipdst` (
  `sid` int(10) unsigned NOT NULL,
  `day` date NOT NULL,
  `ip_dst` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`sid`,`day`,`ip_dst`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `ac_sensor_ipsrc`;
CREATE TABLE IF NOT EXISTS `ac_sensor_ipsrc` (
  `sid` int(10) unsigned NOT NULL,
  `day` date NOT NULL,
  `ip_src` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`sid`,`day`,`ip_src`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `ac_sensor_sid`;
CREATE TABLE IF NOT EXISTS `ac_sensor_sid` (
  `sid` int(10) unsigned NOT NULL,
  `day` date NOT NULL,
  `cid` int(10) unsigned NOT NULL,
  `first_timestamp` datetime NOT NULL,
  `last_timestamp` datetime NOT NULL,
  PRIMARY KEY  (`sid`,`day`),
  KEY `day` (`day`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `ac_sensor_signature`;
CREATE TABLE IF NOT EXISTS `ac_sensor_signature` (
  `sid` int(10) unsigned NOT NULL,
  `day` date NOT NULL,
  `plugin_id` int(11) NOT NULL default '0',
  `plugin_sid` int(11) NOT NULL default '0',
  PRIMARY KEY  (`sid`,`day`,`plugin_id`,`plugin_sid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `ac_srcaddr_ipdst`;
CREATE TABLE IF NOT EXISTS `ac_srcaddr_ipdst` (
  `ip_src` int(10) unsigned NOT NULL,
  `day` date NOT NULL,
  `ip_dst` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`ip_src`,`day`,`ip_dst`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `ac_srcaddr_ipsrc`;
CREATE TABLE IF NOT EXISTS `ac_srcaddr_ipsrc` (
  `ip_src` int(10) unsigned NOT NULL,
  `day` date NOT NULL,
  `cid` int(11) NOT NULL,
  PRIMARY KEY  (`ip_src`,`day`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `ac_srcaddr_sid`;
CREATE TABLE IF NOT EXISTS `ac_srcaddr_sid` (
  `ip_src` int(10) unsigned NOT NULL,
  `day` date NOT NULL,
  `sid` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`ip_src`,`day`,`sid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `ac_srcaddr_signature`;
CREATE TABLE IF NOT EXISTS `ac_srcaddr_signature` (
  `ip_src` int(10) unsigned NOT NULL,
  `day` date NOT NULL,
  `plugin_id` int(11) NOT NULL default '0',
  `plugin_sid` int(11) NOT NULL default '0',
  PRIMARY KEY  (`ip_src`,`day`,`plugin_id`,`plugin_sid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

DROP PROCEDURE IF EXISTS ac_cursor;
DELIMITER ;;
CREATE PROCEDURE ac_cursor()
BEGIN
    DECLARE done BOOLEAN DEFAULT 0;
    DECLARE sd INT;
    DECLARE ts DATE;
    DECLARE ipsrc INT;
    DECLARE ipdst INT;
    DECLARE ipproto INT;
    DECLARE l4sport INT;
    DECLARE l4dport INT;
    DECLARE pluginid INT;
    DECLARE pluginsid INT;
    DECLARE ac CURSOR FOR SELECT sid,timestamp,ip_src,ip_dst,ip_proto,layer4_sport,layer4_dport,plugin_id,plugin_sid FROM acid_event_input;
    DECLARE CONTINUE HANDLER FOR SQLSTATE '02000' SET done=1;
    OPEN ac;
    REPEAT
        FETCH ac INTO sd,ts,ipsrc,ipdst,ipproto,l4sport,l4dport,pluginid,pluginsid;
        INSERT INTO ac_sensor_sid (sid,day,cid,first_timestamp,last_timestamp) VALUES (sd,DATE(ts),1,ts,ts) ON DUPLICATE KEY UPDATE cid=cid+1,last_timestamp=ts;
        INSERT IGNORE INTO ac_sensor_signature (sid,day,plugin_id,plugin_sid) VALUES (sd,DATE(ts),pluginid,pluginsid);
        INSERT IGNORE INTO ac_sensor_ipsrc (sid,day,ip_src) VALUES (sd,DATE(ts),ipsrc);
        INSERT IGNORE INTO ac_sensor_ipdst (sid,day,ip_dst) VALUES (sd,DATE(ts),ipdst);
        INSERT INTO ac_alerts_signature (day,sig_cnt,first_timestamp,last_timestamp,plugin_id,plugin_sid) VALUES (DATE(ts),1,ts,ts,pluginid,pluginsid) ON DUPLICATE KEY UPDATE sig_cnt=sig_cnt+1,last_timestamp=ts;
        INSERT IGNORE INTO ac_alerts_sid (day,sid,plugin_id,plugin_sid) VALUES (DATE(ts),sd,pluginid,pluginsid);
        INSERT IGNORE INTO ac_alerts_ipsrc (day,ip_src,plugin_id,plugin_sid) VALUES (DATE(ts),ipsrc,pluginid,pluginsid);
        INSERT IGNORE INTO ac_alerts_ipdst (day,ip_dst,plugin_id,plugin_sid) VALUES (DATE(ts),ipdst,pluginid,pluginsid);
        INSERT INTO ac_srcaddr_ipsrc (ip_src,day,cid) VALUES (ipsrc,DATE(ts),1) ON DUPLICATE KEY UPDATE cid=cid+1;
        INSERT IGNORE INTO ac_srcaddr_sid (ip_src,day,sid) VALUES (ipsrc,DATE(ts),sd);
        INSERT IGNORE INTO ac_srcaddr_signature (ip_src,day,plugin_id,plugin_sid) VALUES (ipsrc,DATE(ts),pluginid,pluginsid);
        INSERT IGNORE INTO ac_srcaddr_ipdst (ip_src,day,ip_dst) VALUES (ipsrc,DATE(ts),ipdst);
        INSERT INTO ac_dstaddr_ipdst (ip_dst,day,cid) VALUES (ipdst,DATE(ts),1) ON DUPLICATE KEY UPDATE cid=cid+1;
        INSERT IGNORE INTO ac_dstaddr_sid (ip_dst,day,sid) VALUES (ipdst,DATE(ts),sd);
        INSERT IGNORE INTO ac_dstaddr_signature (ip_dst,day,plugin_id,plugin_sid) VALUES (ipdst,DATE(ts),pluginid,pluginsid);
        INSERT IGNORE INTO ac_dstaddr_ipsrc (ip_dst,day,ip_src) VALUES (ipdst,DATE(ts),ipsrc);
        INSERT INTO ac_layer4_sport (layer4_sport,ip_proto,day,cid,first_timestamp,last_timestamp) VALUES (l4sport,ipproto,DATE(ts),1,ts,ts) ON DUPLICATE KEY UPDATE cid=cid+1,last_timestamp=ts;
        INSERT IGNORE INTO ac_layer4_sport_sid (layer4_sport,ip_proto,day,sid) VALUES (l4sport,ipproto,DATE(ts),sd);
        INSERT IGNORE INTO ac_layer4_sport_signature (layer4_sport,ip_proto,day,plugin_id,plugin_sid) VALUES (l4sport,ipproto,DATE(ts),pluginid,pluginsid);
        INSERT IGNORE INTO ac_layer4_sport_ipsrc (layer4_sport,ip_proto,day,ip_src) VALUES (l4sport,ipproto,DATE(ts),ipsrc);
        INSERT IGNORE INTO ac_layer4_sport_ipdst (layer4_sport,ip_proto,day,ip_dst) VALUES (l4sport,ipproto,DATE(ts),ipdst);
        INSERT INTO ac_layer4_dport (layer4_dport,ip_proto,day,cid,first_timestamp,last_timestamp) VALUES (l4dport,ipproto,DATE(ts),1,ts,ts) ON DUPLICATE KEY UPDATE cid=cid+1,last_timestamp=ts;
        INSERT IGNORE INTO ac_layer4_dport_sid (layer4_dport,ip_proto,day,sid) VALUES (l4dport,ipproto,DATE(ts),sd);
        INSERT IGNORE INTO ac_layer4_dport_signature (layer4_dport,ip_proto,day,plugin_id,plugin_sid) VALUES (l4dport,ipproto,DATE(ts),pluginid,pluginsid);
        INSERT IGNORE INTO ac_layer4_dport_ipsrc (layer4_dport,ip_proto,day,ip_src) VALUES (l4dport,ipproto,DATE(ts),ipsrc);
        INSERT IGNORE INTO ac_layer4_dport_ipdst (layer4_dport,ip_proto,day,ip_dst) VALUES (l4dport,ipproto,DATE(ts),ipdst);
    UNTIL done END REPEAT;
    CLOSE ac;
END ;;
DELIMITER ;
CALL ac_cursor();

DROP TABLE IF EXISTS `acid_event`;
CREATE TABLE IF NOT EXISTS `acid_event` (
  `sid` int(10) unsigned NOT NULL,
  `cid` int(10) unsigned NOT NULL,
  `timestamp` datetime NOT NULL,
  `ip_src` int(10) unsigned default NULL,
  `ip_dst` int(10) unsigned default NULL,
  `ip_proto` int(11) default NULL,
  `layer4_sport` int(10) unsigned default NULL,
  `layer4_dport` int(10) unsigned default NULL,
  `ossim_type` int(11) default '1',
  `ossim_priority` int(11) default '1',
  `ossim_reliability` int(11) default '1',
  `ossim_asset_src` int(11) default '1',
  `ossim_asset_dst` int(11) default '1',
  `ossim_risk_c` int(11) default '1',
  `ossim_risk_a` int(11) default '1',
  `plugin_id` int(11) default NULL,
  `plugin_sid` int(11) default NULL,
  PRIMARY KEY  (`sid`,`cid`,`timestamp`),
  KEY `timestamp` (`timestamp`),
  KEY `layer4_sport` (`layer4_sport`),
  KEY `layer4_dport` (`layer4_dport`),
  KEY `ip_src` (`ip_src`,`timestamp`),
  KEY `ip_dst` (`ip_dst`,`timestamp`),
  KEY `acid_event_ossim_priority` (`ossim_priority`,`timestamp`),
  KEY `acid_event_ossim_risk_a` (`ossim_risk_a`,`timestamp`),
  KEY `acid_event_ossim_reliability` (`ossim_reliability`,`timestamp`),
  KEY `acid_event_ossim_risk_c` (`ossim_risk_c`,`timestamp`),
  KEY `sig_name` (`plugin_id`,`plugin_sid`,`timestamp`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

INSERT INTO acid_event SELECT * FROM acid_event_input;

DROP TABLE IF EXISTS ossim_event;
DROP TABLE IF EXISTS event;
DROP TABLE IF EXISTS data;
DROP TABLE IF EXISTS signature;
DROP TABLE IF EXISTS ac_alertsclas_classid;
DROP TABLE IF EXISTS ac_alertsclas_ipdst;
DROP TABLE IF EXISTS ac_alertsclas_ipsrc;
DROP TABLE IF EXISTS ac_alertsclas_sid;
DROP TABLE IF EXISTS ac_alertsclas_signature;


DELETE FROM `schema`;
INSERT INTO `schema` VALUES (200, NOW());
