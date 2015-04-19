-- bluecoat
-- plugin_id: 1642

DELETE FROM plugin WHERE id = "1642";
DELETE FROM plugin_sid where plugin_id = "1642";

INSERT IGNORE INTO plugin (id, type, name, description) VALUES (1642, 1, 'bluecoat', 'Blue Coat Proxy');

INSERT IGNORE INTO plugin_sid (plugin_id, sid, category_id, class_id, name) VALUES (1642, 200, NULL, NULL, 'Blue Coat: OK');
INSERT IGNORE INTO plugin_sid (plugin_id, sid, category_id, class_id, name) VALUES (1642, 201, NULL, NULL, 'Blue Coat: Created');
INSERT IGNORE INTO plugin_sid (plugin_id, sid, category_id, class_id, name) VALUES (1642, 202, NULL, NULL, 'Blue Coat: Accepted');
INSERT IGNORE INTO plugin_sid (plugin_id, sid, category_id, class_id, name) VALUES (1642, 203, NULL, NULL, 'Blue Coat: Non-Authorative Information');
INSERT IGNORE INTO plugin_sid (plugin_id, sid, category_id, class_id, name) VALUES (1642, 204, NULL, NULL, 'Blue Coat: No Content');
INSERT IGNORE INTO plugin_sid (plugin_id, sid, category_id, class_id, name) VALUES (1642, 205, NULL, NULL, 'Blue Coat: Reset Content');
INSERT IGNORE INTO plugin_sid (plugin_id, sid, category_id, class_id, name) VALUES (1642, 206, NULL, NULL, 'Blue Coat: Partial Content');
INSERT IGNORE INTO plugin_sid (plugin_id, sid, category_id, class_id, name) VALUES (1642, 300, NULL, NULL, 'Blue Coat: Multiple Choices');
INSERT IGNORE INTO plugin_sid (plugin_id, sid, category_id, class_id, name) VALUES (1642, 301, NULL, NULL, 'Blue Coat: Moved Permanently');
INSERT IGNORE INTO plugin_sid (plugin_id, sid, category_id, class_id, name) VALUES (1642, 302, NULL, NULL, 'Blue Coat: Moved Temporarily');
INSERT IGNORE INTO plugin_sid (plugin_id, sid, category_id, class_id, name) VALUES (1642, 303, NULL, NULL, 'Blue Coat: See Other');
INSERT IGNORE INTO plugin_sid (plugin_id, sid, category_id, class_id, name) VALUES (1642, 304, NULL, NULL, 'Blue Coat: Not Modified');
INSERT IGNORE INTO plugin_sid (plugin_id, sid, category_id, class_id, name) VALUES (1642, 305, NULL, NULL, 'Blue Coat: Use Proxy');
INSERT IGNORE INTO plugin_sid (plugin_id, sid, category_id, class_id, name) VALUES (1642, 400, NULL, NULL, 'Blue Coat: Bad Request');
INSERT IGNORE INTO plugin_sid (plugin_id, sid, category_id, class_id, name) VALUES (1642, 401, NULL, NULL, 'Blue Coat: Unauthorized');
INSERT IGNORE INTO plugin_sid (plugin_id, sid, category_id, class_id, name) VALUES (1642, 402, NULL, NULL, 'Blue Coat: Payment Required');
INSERT IGNORE INTO plugin_sid (plugin_id, sid, category_id, class_id, name) VALUES (1642, 403, NULL, NULL, 'Blue Coat: Forbidden');
INSERT IGNORE INTO plugin_sid (plugin_id, sid, category_id, class_id, name) VALUES (1642, 404, NULL, NULL, 'Blue Coat: Not Found');
INSERT IGNORE INTO plugin_sid (plugin_id, sid, category_id, class_id, name) VALUES (1642, 405, NULL, NULL, 'Blue Coat: Method Not Allowed');
INSERT IGNORE INTO plugin_sid (plugin_id, sid, category_id, class_id, name) VALUES (1642, 406, NULL, NULL, 'Blue Coat: Not Acceptable (encoding)');
INSERT IGNORE INTO plugin_sid (plugin_id, sid, category_id, class_id, name) VALUES (1642, 407, NULL, NULL, 'Blue Coat: Proxy Authentication Required');
INSERT IGNORE INTO plugin_sid (plugin_id, sid, category_id, class_id, name) VALUES (1642, 408, NULL, NULL, 'Blue Coat: Request Timed Out');
INSERT IGNORE INTO plugin_sid (plugin_id, sid, category_id, class_id, name) VALUES (1642, 409, NULL, NULL, 'Blue Coat: Conflicting Request');
INSERT IGNORE INTO plugin_sid (plugin_id, sid, category_id, class_id, name) VALUES (1642, 410, NULL, NULL, 'Blue Coat: Gone');
INSERT IGNORE INTO plugin_sid (plugin_id, sid, category_id, class_id, name) VALUES (1642, 411, NULL, NULL, 'Blue Coat: Content Length Required');
INSERT IGNORE INTO plugin_sid (plugin_id, sid, category_id, class_id, name) VALUES (1642, 412, NULL, NULL, 'Blue Coat: Precondition Failed');
INSERT IGNORE INTO plugin_sid (plugin_id, sid, category_id, class_id, name) VALUES (1642, 413, NULL, NULL, 'Blue Coat: Request Entity Too Long');
INSERT IGNORE INTO plugin_sid (plugin_id, sid, category_id, class_id, name) VALUES (1642, 414, NULL, NULL, 'Blue Coat: Request URI Too Long');
INSERT IGNORE INTO plugin_sid (plugin_id, sid, category_id, class_id, name) VALUES (1642, 415, NULL, NULL, 'Blue Coat: Unsupported Media Type');
INSERT IGNORE INTO plugin_sid (plugin_id, sid, category_id, class_id, name) VALUES (1642, 500, NULL, NULL, 'Blue Coat: Internal Server Error');
INSERT IGNORE INTO plugin_sid (plugin_id, sid, category_id, class_id, name) VALUES (1642, 501, NULL, NULL, 'Blue Coat: Not implemented');
INSERT IGNORE INTO plugin_sid (plugin_id, sid, category_id, class_id, name) VALUES (1642, 502, NULL, NULL, 'Blue Coat: Bad Gateway');
INSERT IGNORE INTO plugin_sid (plugin_id, sid, category_id, class_id, name) VALUES (1642, 503, NULL, NULL, 'Blue Coat: Service Unavailable');
INSERT IGNORE INTO plugin_sid (plugin_id, sid, category_id, class_id, name) VALUES (1642, 504, NULL, NULL, 'Blue Coat: Gateway Timeout');
INSERT IGNORE INTO plugin_sid (plugin_id, sid, category_id, class_id, name) VALUES (1642, 505, NULL, NULL, 'Blue Coat: HTTP Version Not Supported');
INSERT IGNORE INTO plugin_sid (plugin_id, sid, category_id, class_id, name) VALUES (1642, 600, NULL, NULL, 'Blue Coat: Proxy SG: NORMAL EVENT');
INSERT IGNORE INTO plugin_sid (plugin_id, sid, category_id, class_id, name) VALUES (1642, 601, NULL, NULL, 'Blue Coat: Proxy SG: NORMAL EVENT');
INSERT IGNORE INTO plugin_sid (plugin_id, sid, category_id, class_id, name) VALUES (1642, 602, NULL, NULL, 'Blue Coat: Proxy SG: NORMAL EVENT');
INSERT IGNORE INTO plugin_sid (plugin_id, sid, category_id, class_id, name) VALUES (1642, 603, NULL, NULL, 'Blue Coat: Proxy SG: NORMAL EVENT');
INSERT IGNORE INTO plugin_sid (plugin_id, sid, category_id, class_id, name) VALUES (1642, 604, NULL, NULL, 'Blue Coat: Proxy SG: SEVERE ERROR');
INSERT IGNORE INTO plugin_sid (plugin_id, sid, category_id, class_id, name) VALUES (1642, 605, NULL, NULL, 'Blue Coat: Generic Traffic');
INSERT IGNORE INTO plugin_sid (plugin_id, sid, category_id, class_id, name) VALUES (1642, 999, NULL, NULL, 'Blue Coat: Capture All');

INSERT IGNORE INTO plugin_sid (plugin_id, sid, category_id, class_id,reliability,priority, name,aro,subcategory_id) VALUES (1642,2000,13,NULL,2,2,'Blue Coat: SMTP: No gateway configured -- could not send e-mail notification(','0.0000',115);
INSERT IGNORE INTO plugin_sid (plugin_id, sid, category_id, class_id,reliability,priority, name,aro,subcategory_id) VALUES (1642,2001,13,NULL,2,2,'Blue Coat: Access Log FTP','0.0000',115);
INSERT IGNORE INTO plugin_sid (plugin_id, sid, category_id, class_id,reliability,priority, name,aro,subcategory_id) VALUES (1642,2002,13,NULL,2,2,'Blue Coat: Access Log','0.0000',115);
INSERT IGNORE INTO plugin_sid (plugin_id, sid, category_id, class_id,reliability,priority, name,aro,subcategory_id) VALUES (1642,2003,13,NULL,2,2,'Blue Coat: Unable to connect to remote server for log uploading','0.0000',115);
INSERT IGNORE INTO plugin_sid (plugin_id, sid, category_id, class_id,reliability,priority, name,aro,subcategory_id) VALUES (1642,2004,13,NULL,2,2,'Blue Coat: Upload completed successfully','0.0000',115);
INSERT IGNORE INTO plugin_sid (plugin_id, sid, category_id, class_id,reliability,priority, name,aro,subcategory_id) VALUES (1642,2005,13,NULL,2,2,'Blue Coat: Error sending PASS/QUIT m_CommandBuffer.','0.0000',115);
INSERT IGNORE INTO plugin_sid (plugin_id, sid, category_id, class_id,reliability,priority, name,aro,subcategory_id) VALUES (1642,2006,13,NULL,2,2,'Blue Coat: Snapshot sysinfo_stats has fetched','0.0000',115);
INSERT IGNORE INTO plugin_sid (plugin_id, sid, category_id, class_id,reliability,priority, name,aro,subcategory_id) VALUES (1642,2007,13,NULL,2,2,'Blue Coat: Health check error','0.0000',115);
INSERT IGNORE INTO plugin_sid (plugin_id, sid, category_id, class_id,reliability,priority, name,aro,subcategory_id) VALUES (1642,2008,13,NULL,2,2,'Blue Coat: Unexpected transaction termination','0.0000',115);
INSERT IGNORE INTO plugin_sid (plugin_id, sid, category_id, class_id,reliability,priority, name,aro,subcategory_id) VALUES (1642,2009,13,NULL,2,2,'Blue Coat: Receive failed Using service drtr/dropped connection','0.0000',115);
INSERT IGNORE INTO plugin_sid (plugin_id, sid, category_id, class_id,reliability,priority, name,aro,subcategory_id) VALUES (1642,2010,13,NULL,2,2,'Blue Coat: AIM6.x inbound ssl profile is not set','0.0000',115);
INSERT IGNORE INTO plugin_sid (plugin_id, sid, category_id, class_id,reliability,priority, name,aro,subcategory_id) VALUES (1642,2011,13,NULL,2,2,'Blue Coat: Services: enable force-bypass/disable force-bypass','0.0000',115);
INSERT IGNORE INTO plugin_sid (plugin_id, sid, category_id, class_id,reliability,priority, name,aro,subcategory_id) VALUES (1642,2012,13,NULL,2,2,'Blue Coat: Health Monitor OK','0.0000',115);
INSERT IGNORE INTO plugin_sid (plugin_id, sid, category_id, class_id,reliability,priority, name,aro,subcategory_id) VALUES (1642,2013,13,NULL,2,2,'Blue Coat: Health Monitor WARNING','0.0000',115);
INSERT IGNORE INTO plugin_sid (plugin_id, sid, category_id, class_id,reliability,priority, name,aro,subcategory_id) VALUES (1642,2014,13,NULL,2,2,'Blue Coat: Administrator login','0.0000',115);
INSERT IGNORE INTO plugin_sid (plugin_id, sid, category_id, class_id,reliability,priority, name,aro,subcategory_id) VALUES (1642,2015,13,NULL,2,2,'Blue Coat: Read/write mode entered','0.0000',115);
INSERT IGNORE INTO plugin_sid (plugin_id, sid, category_id, class_id,reliability,priority, name,aro,subcategory_id) VALUES (1642,2016,13,NULL,2,2,'Blue Coat: Interface health Check up/down','0.0000',115);
INSERT IGNORE INTO plugin_sid (plugin_id, sid, category_id, class_id,reliability,priority, name,aro,subcategory_id) VALUES (1642,2017,13,NULL,2,2,'Blue Coat: Connection SSH','0.0000',115);
INSERT IGNORE INTO plugin_sid (plugin_id, sid, category_id, class_id,reliability,priority, name,aro,subcategory_id) VALUES (1642,2018,13,NULL,2,2,'Blue Coat: Unexpected disposition by','0.0000',115);
INSERT IGNORE INTO plugin_sid (plugin_id, sid, category_id, class_id,reliability,priority, name,aro,subcategory_id) VALUES (1642,2019,13,NULL,2,2,'Blue Coat: disabled syslog','0.0000',115);
INSERT IGNORE INTO plugin_sid (plugin_id, sid, category_id, class_id,reliability,priority, name,aro,subcategory_id) VALUES (1642,2020,13,NULL,2,2,'Blue Coat: Management Console admin','0.0000',115);
INSERT IGNORE INTO plugin_sid (plugin_id, sid, category_id, class_id,reliability,priority, name,aro,subcategory_id) VALUES (1642,2021,13,NULL,2,2,'Blue Coat: NULL character found in the request','0.0000',115);
INSERT IGNORE INTO plugin_sid (plugin_id, sid, category_id, class_id,reliability,priority, name,aro,subcategory_id) VALUES (1642,2022,13,NULL,2,2,'Blue Coat: Bad Host header','0.0000',115);
INSERT IGNORE INTO plugin_sid (plugin_id, sid, category_id, class_id,reliability,priority, name,aro,subcategory_id) VALUES (1642,2023,13,NULL,2,2,'Blue Coat: NTP: Receive timeout','0.0000',115);
INSERT IGNORE INTO plugin_sid (plugin_id, sid, category_id, class_id,reliability,priority, name,aro,subcategory_id) VALUES (1642,2024,13,NULL,2,2,'Blue Coat: Config admin','0.0000',115);
INSERT IGNORE INTO plugin_sid (plugin_id, sid, category_id, class_id,reliability,priority, name,aro,subcategory_id) VALUES (1642,2025,13,NULL,2,2,'Blue Coat: SSH admn','0.0000',115);
INSERT IGNORE INTO plugin_sid (plugin_id, sid, category_id, class_id,reliability,priority, name,aro,subcategory_id) VALUES (1642,2026,13,NULL,2,2,'Blue Coat: Authentication Login','0.0000',115);
INSERT IGNORE INTO plugin_sid (plugin_id, sid, category_id, class_id,reliability,priority, name,aro,subcategory_id) VALUES (1642,2027,13,NULL,2,2,'Blue Coat: SSL_accept failed/error','0.0000',115);
INSERT IGNORE INTO plugin_sid (plugin_id, sid, category_id, class_id,reliability,priority, name,aro,subcategory_id) VALUES (1642,2028,13,NULL,2,2,'Blue Coat: Download of Local database failed','0.0000',115);
INSERT IGNORE INTO plugin_sid (plugin_id, sid, category_id, class_id,reliability,priority, name,aro,subcategory_id) VALUES (1642,2029,13,NULL,2,2,'Blue Coat: Licensing Actions','0.0000',115);
INSERT IGNORE INTO plugin_sid (plugin_id, sid, category_id, class_id,reliability,priority, name,aro,subcategory_id) VALUES (1642,2030,13,NULL,2,2,'Blue Coat: Assertion failed','0.0000',115);
INSERT IGNORE INTO plugin_sid (plugin_id, sid, category_id, class_id,reliability,priority, name,aro,subcategory_id) VALUES (1642,2031,13,NULL,2,2,'Blue Coat: User Deny','0.0000',115);
INSERT IGNORE INTO plugin_sid (plugin_id, sid, category_id, class_id,reliability,priority, name,aro,subcategory_id) VALUES (1642,2000000000,NULL,NULL,2,2,'bluecoat: Generic event','0.0000',NULL);
INSERT IGNORE INTO plugin_sid (plugin_id, sid, category_id, class_id,reliability,priority, name,aro,subcategory_id) VALUES (1642,20000000,NULL,NULL,2,2,'Demo event (limit reached)','0.0000',NULL);

