-- BIND (DNS)
-- plugin_id: 1577

delete from plugin where id=1577;
delete from plugin_sid where plugin_id=1577;

insert into plugin values (1577, 1, 'bind', 'BIND');	
INSERT INTO `plugin_sid` (`plugin_id`, `sid`, `category_id`, `class_id`, `reliability`, `priority`, `name`) 
	VALUES (1577, 1, NULL, NULL, 1, 3, 'DNS (Bind) - Succesful Zone Transfer (AFXR)');
INSERT INTO `plugin_sid` (`plugin_id`, `sid`, `category_id`, `class_id`, `reliability`, `priority`, `name`) 
	VALUES (1577, 2, NULL, NULL, 1, 3, 'DNS (Bind) - Bad Referal');
INSERT INTO `plugin_sid` (`plugin_id`, `sid`, `category_id`, `class_id`, `reliability`, `priority`, `name`) 
	VALUES (1577, 3, NULL, NULL, 1, 3, 'DNS (Bind) - Bad Response to SOA Query');
INSERT INTO `plugin_sid` (`plugin_id`, `sid`, `category_id`, `class_id`, `reliability`, `priority`, `name`) 
	VALUES (1577, 4, NULL, NULL, 1, 3, 'DNS (Bind) - Interface Deleted due to listenig error');
INSERT INTO `plugin_sid` (`plugin_id`, `sid`, `category_id`, `class_id`, `reliability`, `priority`, `name`) 
	VALUES (1577, 5, NULL, NULL, 1, 3, 'DNS (Bind) - Denied AXFR - equivalent to unapprouved');
INSERT INTO `plugin_sid` (`plugin_id`, `sid`, `category_id`, `class_id`, `reliability`, `priority`, `name`) 
	VALUES (1577, 6, NULL, NULL, 1, 3, 'DNS (Bind) - Denied Update Command - equivalent to unapprouved');
INSERT INTO `plugin_sid` (`plugin_id`, `sid`, `category_id`, `class_id`, `reliability`, `priority`, `name`) 
	VALUES (1577, 7, NULL, NULL, 1, 3, 'DNS (Bind) - Drop source port zero packet');
INSERT INTO `plugin_sid` (`plugin_id`, `sid`, `category_id`, `class_id`, `reliability`, `priority`, `name`) 
	VALUES (1577, 8, NULL, NULL, 1, 3, 'DNS (Bind) - Update error due to existing record');
INSERT INTO `plugin_sid` (`plugin_id`, `sid`, `category_id`, `class_id`, `reliability`, `priority`, `name`) 
	VALUES (1577, 9, NULL, NULL, 1, 3, 'DNS (Bind) - TCP based query or zone transfer');
INSERT INTO `plugin_sid` (`plugin_id`, `sid`, `category_id`, `class_id`, `reliability`, `priority`, `name`) 
	VALUES (1577, 10, NULL, NULL, 1, 3, 'DNS (Bind) - Lame server: remote name server not authoritative for domain');
INSERT INTO `plugin_sid` (`plugin_id`, `sid`, `category_id`, `class_id`, `reliability`, `priority`, `name`) 
	VALUES (1577, 11, NULL, NULL, 1, 3, 'DNS (Bind) - Malformed Response');
INSERT INTO `plugin_sid` (`plugin_id`, `sid`, `category_id`, `class_id`, `reliability`, `priority`, `name`) 
	VALUES (1577, 12, NULL, NULL, 1, 3, 'DNS (Bind) - Query for unknown class');
INSERT INTO `plugin_sid` (`plugin_id`, `sid`, `category_id`, `class_id`, `reliability`, `priority`, `name`) 
	VALUES (1577, 13, NULL, NULL, 1, 3, 'DNS (Bind) - NOTIFY(SOA): propably domain name is not the good one for the specified zone');
INSERT INTO `plugin_sid` (`plugin_id`, `sid`, `category_id`, `class_id`, `reliability`, `priority`, `name`) 
	VALUES (1577, 14, NULL, NULL, 1, 3, 'DNS (Bind) - NOTIFY(SOA): local name server not slave for specified zone: unexpected message');
INSERT INTO `plugin_sid` (`plugin_id`, `sid`, `category_id`, `class_id`, `reliability`, `priority`, `name`) 
	VALUES (1577, 15, NULL, NULL, 1, 3, 'DNS (Bind) - NOTIFY(SOA): remote IP is not master server for the specified zone: unexpected message');
INSERT INTO `plugin_sid` (`plugin_id`, `sid`, `category_id`, `class_id`, `reliability`, `priority`, `name`) 
	VALUES (1577, 16, NULL, NULL, 1, 3, 'DNS (Bind) - NS Operation: Network Unreachable');
INSERT INTO `plugin_sid` (`plugin_id`, `sid`, `category_id`, `class_id`, `reliability`, `priority`, `name`) 
	VALUES (1577, 17, NULL, NULL, 1, 3, 'DNS (Bind) - NS Operation: Operation not permitted');
INSERT INTO `plugin_sid` (`plugin_id`, `sid`, `category_id`, `class_id`, `reliability`, `priority`, `name`) 
	VALUES (1577, 18, NULL, NULL, 1, 3, 'DNS (Bind) - NS Operation: Resource temporarily unavailable');
INSERT INTO `plugin_sid` (`plugin_id`, `sid`, `category_id`, `class_id`, `reliability`, `priority`, `name`) 
	VALUES (1577, 19, NULL, NULL, 1, 3, 'DNS (Bind) - NS Operation: Connection refused');
INSERT INTO `plugin_sid` (`plugin_id`, `sid`, `category_id`, `class_id`, `reliability`, `priority`, `name`) 
	VALUES (1577, 20, NULL, NULL, 1, 3, 'DNS (Bind) - NS Operation: No buffer space available');
INSERT INTO `plugin_sid` (`plugin_id`, `sid`, `category_id`, `class_id`, `reliability`, `priority`, `name`) 
	VALUES (1577, 21, NULL, NULL, 1, 3, 'DNS (Bind) - NS Operation: All possible Records are Lame');
INSERT INTO `plugin_sid` (`plugin_id`, `sid`, `category_id`, `class_id`, `reliability`, `priority`, `name`) 
	VALUES (1577, 22, NULL, NULL, 1, 3, 'DNS (Bind) - NS Operation: Remote server already cached as unexisting');
INSERT INTO `plugin_sid` (`plugin_id`, `sid`, `category_id`, `class_id`, `reliability`, `priority`, `name`) 
	VALUES (1577, 23, NULL, NULL, 1, 3, 'DNS (Bind) - NS Operation: Bogus Loopback');
INSERT INTO `plugin_sid` (`plugin_id`, `sid`, `category_id`, `class_id`, `reliability`, `priority`, `name`) 
	VALUES (1577, 24, NULL, NULL, 1, 3, 'DNS (Bind) - NS Operation: no possible A record');
INSERT INTO `plugin_sid` (`plugin_id`, `sid`, `category_id`, `class_id`, `reliability`, `priority`, `name`) 
	VALUES (1577, 25, NULL, NULL, 1, 3, 'DNS (Bind) - Remote Name server known by multiple domains');
INSERT INTO `plugin_sid` (`plugin_id`, `sid`, `category_id`, `class_id`, `reliability`, `priority`, `name`) 
	VALUES (1577, 26, NULL, NULL, 1, 3, 'DNS (Bind) - TCP packet truncated');
INSERT INTO `plugin_sid` (`plugin_id`, `sid`, `category_id`, `class_id`, `reliability`, `priority`, `name`) 
	VALUES (1577, 27, NULL, NULL, 1, 3, 'DNS (Bind) - Remote Name server restricting zone transfer');
INSERT INTO `plugin_sid` (`plugin_id`, `sid`, `category_id`, `class_id`, `reliability`, `priority`, `name`) 
	VALUES (1577, 28, NULL, NULL, 1, 3, 'DNS (Bind) - Query received on non-query socket');
INSERT INTO `plugin_sid` (`plugin_id`, `sid`, `category_id`, `class_id`, `reliability`, `priority`, `name`) 
	VALUES (1577, 29, NULL, NULL, 1, 3, 'DNS (Bind) - Response received from a Name Server not queried');
INSERT INTO `plugin_sid` (`plugin_id`, `sid`, `category_id`, `class_id`, `reliability`, `priority`, `name`) 
	VALUES (1577, 30, NULL, NULL, 1, 3, 'DNS (Bind) - Response from remote server out of time (more than 10 min');
INSERT INTO `plugin_sid` (`plugin_id`, `sid`, `category_id`, `class_id`, `reliability`, `priority`, `name`) 
	VALUES (1577, 31, NULL, NULL, 1, 3, 'DNS (Bind) - Zone transfer denied due to local ACL');
INSERT INTO `plugin_sid` (`plugin_id`, `sid`, `category_id`, `class_id`, `reliability`, `priority`, `name`) 
	VALUES (1577, 32, NULL, NULL, 1, 3, 'DNS (Bind) - Zone transfer denied because local server is not authoritative for the specified zone');
INSERT INTO `plugin_sid` (`plugin_id`, `sid`, `category_id`, `class_id`, `reliability`, `priority`, `name`) 
	VALUES (1577, 33, NULL, NULL, 1, 3, 'DNS (Bind) - Zone transfer denied because remote domain is not top of the zone');
INSERT INTO `plugin_sid` (`plugin_id`, `sid`, `category_id`, `class_id`, `reliability`, `priority`, `name`) 
	VALUES (1577, 34, NULL, NULL, 1, 3, 'DNS (Bind) - Zone transfer denied because of a syntax error or an illegal domain name');
INSERT INTO `plugin_sid` (`plugin_id`, `sid`, `category_id`, `class_id`, `reliability`, `priority`, `name`) 
	VALUES (1577, 35, NULL, NULL, 1, 3, 'DNS (Bind) - Unapproved Recursive Query');
INSERT INTO `plugin_sid` (`plugin_id`, `sid`, `category_id`, `class_id`, `reliability`, `priority`, `name`) 
	VALUES (1577, 36, NULL, NULL, 1, 3, 'DNS (Bind) - Unapproved Update Query');
INSERT INTO `plugin_sid` (`plugin_id`, `sid`, `category_id`, `class_id`, `reliability`, `priority`, `name`) 
	VALUES (1577, 37, NULL, NULL, 1, 3, 'DNS (Bind) - Unrelated additional info for domain in response');
INSERT INTO `plugin_sid` (`plugin_id`, `sid`, `category_id`, `class_id`, `reliability`, `priority`, `name`) 
	VALUES (1577, 38, NULL, NULL, 1, 3, 'DNS (Bind) - Zone Transfer Successful');
INSERT INTO `plugin_sid` (`plugin_id`, `sid`, `category_id`, `class_id`, `reliability`, `priority`, `name`) 
	VALUES (1577, 39, NULL, NULL, 1, 3, 'DNS (Bind) - Zone Transfer Timeout');
INSERT INTO `plugin_sid` (`plugin_id`, `sid`, `category_id`, `class_id`, `reliability`, `priority`, `name`) 
	VALUES (1577, 40, NULL, NULL, 1, 3, 'DNS (Bind) - Zone Transfer - Master server unreachable');
INSERT INTO `plugin_sid` (`plugin_id`, `sid`, `category_id`, `class_id`, `reliability`, `priority`, `name`) 
	VALUES (1577, 41, NULL, NULL, 1, 3, 'DNS (Bind) - Secondary zone expired - unable to refresh zone data');

