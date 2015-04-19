-- VsFTP
-- plugin_id: 1576

delete from plugin where id=1576;
delete from plugin_sid where plugin_id=1576;

insert into plugin values (1576, 1, 'vsftp', 'VSFTP');
INSERT INTO `plugin_sid` (`plugin_id`, `sid`, `category_id`, `class_id`, `reliability`, `priority`, `name`) 
	VALUES (1576, 1, NULL, NULL, 1, 3, 'VsFTP Command ABOR - abort a file transfer');
INSERT INTO `plugin_sid` (`plugin_id`, `sid`, `category_id`, `class_id`, `reliability`, `priority`, `name`) 
	VALUES (1576, 2, NULL, NULL, 1, 3, 'VsFTP Command CWD - change working directory');
INSERT INTO `plugin_sid` (`plugin_id`, `sid`, `category_id`, `class_id`, `reliability`, `priority`, `name`) 
	VALUES (1576, 3, NULL, NULL, 1, 3, 'VsFTP Command DELE - delete a remote file');
INSERT INTO `plugin_sid` (`plugin_id`, `sid`, `category_id`, `class_id`, `reliability`, `priority`, `name`) 
	VALUES (1576, 4, NULL, NULL, 1, 3, 'VsFTP Command LIST - list remote files');
INSERT INTO `plugin_sid` (`plugin_id`, `sid`, `category_id`, `class_id`, `reliability`, `priority`, `name`) 
	VALUES (1576, 5, NULL, NULL, 1, 3, 'VsFTP Command MDTM - return the modification time of a file');
INSERT INTO `plugin_sid` (`plugin_id`, `sid`, `category_id`, `class_id`, `reliability`, `priority`, `name`) 
	VALUES (1576, 6, NULL, NULL, 1, 3, 'VsFTP Command MKD - make a remote directory');
INSERT INTO `plugin_sid` (`plugin_id`, `sid`, `category_id`, `class_id`, `reliability`, `priority`, `name`) 
	VALUES (1576, 7, NULL, NULL, 1, 3, 'VsFTP Command NLST - name list of remote directory');
INSERT INTO `plugin_sid` (`plugin_id`, `sid`, `category_id`, `class_id`, `reliability`, `priority`, `name`) 
	VALUES (1576, 8, NULL, NULL, 1, 3, 'VsFTP Command PASS - send password');
INSERT INTO `plugin_sid` (`plugin_id`, `sid`, `category_id`, `class_id`, `reliability`, `priority`, `name`) 
	VALUES (1576, 9, NULL, NULL, 1, 3, 'VsFTP Command PASV - enter passive mode');
INSERT INTO `plugin_sid` (`plugin_id`, `sid`, `category_id`, `class_id`, `reliability`, `priority`, `name`) 
	VALUES (1576, 10, NULL, NULL, 1, 3, 'VsFTP Command PORT - open a data port');
INSERT INTO `plugin_sid` (`plugin_id`, `sid`, `category_id`, `class_id`, `reliability`, `priority`, `name`) 
	VALUES (1576, 11, NULL, NULL, 1, 3, 'VsFTP Command PWD - print working directory');
INSERT INTO `plugin_sid` (`plugin_id`, `sid`, `category_id`, `class_id`, `reliability`, `priority`, `name`) 
	VALUES (1576, 12, NULL, NULL, 1, 3, 'VsFTP Command QUIT - terminate the connection');
INSERT INTO `plugin_sid` (`plugin_id`, `sid`, `category_id`, `class_id`, `reliability`, `priority`, `name`) 
	VALUES (1576, 13, NULL, NULL, 1, 3, 'VsFTP Command REST - Set transfer start point');
INSERT INTO `plugin_sid` (`plugin_id`, `sid`, `category_id`, `class_id`, `reliability`, `priority`, `name`) 
	VALUES (1576, 14, NULL, NULL, 1, 3, 'VsFTP Command RETR - retrieve a remote file');
INSERT INTO `plugin_sid` (`plugin_id`, `sid`, `category_id`, `class_id`, `reliability`, `priority`, `name`) 
	VALUES (1576, 15, NULL, NULL, 1, 3, 'VsFTP Command RMD - remove a remote directory');
INSERT INTO `plugin_sid` (`plugin_id`, `sid`, `category_id`, `class_id`, `reliability`, `priority`, `name`) 
	VALUES (1576, 16, NULL, NULL, 1, 3, 'VsFTP Command RNFR - rename from');
INSERT INTO `plugin_sid` (`plugin_id`, `sid`, `category_id`, `class_id`, `reliability`, `priority`, `name`) 
	VALUES (1576, 17, NULL, NULL, 1, 3, 'VsFTP Command RNTO - rename to');
INSERT INTO `plugin_sid` (`plugin_id`, `sid`, `category_id`, `class_id`, `reliability`, `priority`, `name`) 
	VALUES (1576, 18, NULL, NULL, 1, 3, 'VsFTP Command SITE - site-specific VsFTP Commands');
INSERT INTO `plugin_sid` (`plugin_id`, `sid`, `category_id`, `class_id`, `reliability`, `priority`, `name`) 
	VALUES (1576, 19, NULL, NULL, 1, 3, 'VsFTP Command SIZE - return the size of a file');
INSERT INTO `plugin_sid` (`plugin_id`, `sid`, `category_id`, `class_id`, `reliability`, `priority`, `name`) 
	VALUES (1576, 20, NULL, NULL, 1, 3, 'VsFTP Command STOR - store a file on the remote host');
INSERT INTO `plugin_sid` (`plugin_id`, `sid`, `category_id`, `class_id`, `reliability`, `priority`, `name`) 
	VALUES (1576, 21, NULL, NULL, 1, 3, 'VsFTP Command TYPE - set transfer type');
INSERT INTO `plugin_sid` (`plugin_id`, `sid`, `category_id`, `class_id`, `reliability`, `priority`, `name`) 
	VALUES (1576, 22, NULL, NULL, 1, 3, 'VsFTP Command USER - send username');
INSERT INTO `plugin_sid` (`plugin_id`, `sid`, `category_id`, `class_id`, `reliability`, `priority`, `name`) 
	VALUES (1576, 23, NULL, NULL, 1, 3, 'VsFTP Command ACCT - send account information');
INSERT INTO `plugin_sid` (`plugin_id`, `sid`, `category_id`, `class_id`, `reliability`, `priority`, `name`) 
	VALUES (1576, 24, NULL, NULL, 1, 3, 'VsFTP Command APPE - append to a remote file');
INSERT INTO `plugin_sid` (`plugin_id`, `sid`, `category_id`, `class_id`, `reliability`, `priority`, `name`) 
	VALUES (1576, 25, NULL, NULL, 1, 3, 'VsFTP Command CDUP - CWD to the parent of the current directory');
INSERT INTO `plugin_sid` (`plugin_id`, `sid`, `category_id`, `class_id`, `reliability`, `priority`, `name`) 
	VALUES (1576, 26, NULL, NULL, 1, 3, 'VsFTP Command HELP - return help on using the server');
INSERT INTO `plugin_sid` (`plugin_id`, `sid`, `category_id`, `class_id`, `reliability`, `priority`, `name`) 
	VALUES (1576, 27, NULL, NULL, 1, 3, 'VsFTP Command MODE - set transfer mode');
INSERT INTO `plugin_sid` (`plugin_id`, `sid`, `category_id`, `class_id`, `reliability`, `priority`, `name`) 
	VALUES (1576, 28, NULL, NULL, 1, 3, 'VsFTP Command NOOP - do nothing');
INSERT INTO `plugin_sid` (`plugin_id`, `sid`, `category_id`, `class_id`, `reliability`, `priority`, `name`) 
	VALUES (1576, 29, NULL, NULL, 1, 3, 'VsFTP Command REIN - reinitialize the connection');
INSERT INTO `plugin_sid` (`plugin_id`, `sid`, `category_id`, `class_id`, `reliability`, `priority`, `name`) 
	VALUES (1576, 30, NULL, NULL, 1, 3, 'VsFTP Command STAT - return server status');
INSERT INTO `plugin_sid` (`plugin_id`, `sid`, `category_id`, `class_id`, `reliability`, `priority`, `name`) 
	VALUES (1576, 31, NULL, NULL, 1, 3, 'VsFTP Command STOU - store a file uniquely');
INSERT INTO `plugin_sid` (`plugin_id`, `sid`, `category_id`, `class_id`, `reliability`, `priority`, `name`) 
	VALUES (1576, 32, NULL, NULL, 1, 3, 'VsFTP Command STRU - set file transfer structure');
INSERT INTO `plugin_sid` (`plugin_id`, `sid`, `category_id`, `class_id`, `reliability`, `priority`, `name`) 
	VALUES (1576, 33, NULL, NULL, 1, 3, 'VsFTP Command SYST - return system type');
INSERT INTO `plugin_sid` (`plugin_id`, `sid`, `category_id`, `class_id`, `reliability`, `priority`, `name`) 
	VALUES (1576, 110, NULL, NULL, 1, 3, 'VsFTP Response - Restart marker reply');
INSERT INTO `plugin_sid` (`plugin_id`, `sid`, `category_id`, `class_id`, `reliability`, `priority`, `name`) 
	VALUES (1576, 120, NULL, NULL, 1, 3, 'VsFTP Response - Service ready in (n) minutes');
INSERT INTO `plugin_sid` (`plugin_id`, `sid`, `category_id`, `class_id`, `reliability`, `priority`, `name`) 
	VALUES (1576, 125, NULL, NULL, 1, 3, 'VsFTP Response - Data connection already open, transfer starting');
INSERT INTO `plugin_sid` (`plugin_id`, `sid`, `category_id`, `class_id`, `reliability`, `priority`, `name`) 
	VALUES (1576, 150, NULL, NULL, 1, 3, 'VsFTP Response - File status okay, about to open data connection');
INSERT INTO `plugin_sid` (`plugin_id`, `sid`, `category_id`, `class_id`, `reliability`, `priority`, `name`) 
	VALUES (1576, 200, NULL, NULL, 1, 3, 'VsFTP Response - Command okay');	
INSERT INTO `plugin_sid` (`plugin_id`, `sid`, `category_id`, `class_id`, `reliability`, `priority`, `name`) 
	VALUES (1576, 202, NULL, NULL, 1, 3, 'VsFTP Response - Command not implemented');
INSERT INTO `plugin_sid` (`plugin_id`, `sid`, `category_id`, `class_id`, `reliability`, `priority`, `name`) 
	VALUES (1576, 211, NULL, NULL, 1, 3, 'VsFTP Response - System status, or system help reply');
INSERT INTO `plugin_sid` (`plugin_id`, `sid`, `category_id`, `class_id`, `reliability`, `priority`, `name`) 
	VALUES (1576, 212, NULL, NULL, 1, 3, 'VsFTP Response - Directory status');
INSERT INTO `plugin_sid` (`plugin_id`, `sid`, `category_id`, `class_id`, `reliability`, `priority`, `name`) 
	VALUES (1576, 213, NULL, NULL, 1, 3, 'VsFTP Response - File status');
INSERT INTO `plugin_sid` (`plugin_id`, `sid`, `category_id`, `class_id`, `reliability`, `priority`, `name`) 
	VALUES (1576, 214, NULL, NULL, 1, 3, 'VsFTP Response - Help message');
INSERT INTO `plugin_sid` (`plugin_id`, `sid`, `category_id`, `class_id`, `reliability`, `priority`, `name`) 
	VALUES (1576, 215, NULL, NULL, 1, 3, 'VsFTP Response - NAME system type');
INSERT INTO `plugin_sid` (`plugin_id`, `sid`, `category_id`, `class_id`, `reliability`, `priority`, `name`) 
	VALUES (1576, 220, NULL, NULL, 1, 3, 'VsFTP Response - Service ready for new user');
INSERT INTO `plugin_sid` (`plugin_id`, `sid`, `category_id`, `class_id`, `reliability`, `priority`, `name`) 
	VALUES (1576, 221, NULL, NULL, 1, 3, 'VsFTP Response - Service closing control connection');
INSERT INTO `plugin_sid` (`plugin_id`, `sid`, `category_id`, `class_id`, `reliability`, `priority`, `name`) 
	VALUES (1576, 225, NULL, NULL, 1, 3, 'VsFTP Response - Data connection open, no transfer in progress');
INSERT INTO `plugin_sid` (`plugin_id`, `sid`, `category_id`, `class_id`, `reliability`, `priority`, `name`) 
	VALUES (1576, 226, NULL, NULL, 1, 3, 'VsFTP Response - Closing data connection. Requested file action successful');
INSERT INTO `plugin_sid` (`plugin_id`, `sid`, `category_id`, `class_id`, `reliability`, `priority`, `name`) 
	VALUES (1576, 227, NULL, NULL, 1, 3, 'VsFTP Response - Entering Passive Mode');
INSERT INTO `plugin_sid` (`plugin_id`, `sid`, `category_id`, `class_id`, `reliability`, `priority`, `name`) 
	VALUES (1576, 230, NULL, NULL, 1, 3, 'VsFTP Response - User logged in, proceed');
INSERT INTO `plugin_sid` (`plugin_id`, `sid`, `category_id`, `class_id`, `reliability`, `priority`, `name`) 
	VALUES (1576, 250, NULL, NULL, 1, 3, 'VsFTP Response - Requested file action okay, completed');
INSERT INTO `plugin_sid` (`plugin_id`, `sid`, `category_id`, `class_id`, `reliability`, `priority`, `name`) 
	VALUES (1576, 257, NULL, NULL, 1, 3, 'VsFTP Response - PATHNAME created');
INSERT INTO `plugin_sid` (`plugin_id`, `sid`, `category_id`, `class_id`, `reliability`, `priority`, `name`) 
	VALUES (1576, 331, NULL, NULL, 1, 3, 'VsFTP Response - Username okay, need password');	
INSERT INTO `plugin_sid` (`plugin_id`, `sid`, `category_id`, `class_id`, `reliability`, `priority`, `name`) 
	VALUES (1576, 332, NULL, NULL, 1, 3, 'VsFTP Response - Need account for login');
INSERT INTO `plugin_sid` (`plugin_id`, `sid`, `category_id`, `class_id`, `reliability`, `priority`, `name`) 
	VALUES (1576, 350, NULL, NULL, 1, 3, 'VsFTP Response - Requested file action pending further information');
INSERT INTO `plugin_sid` (`plugin_id`, `sid`, `category_id`, `class_id`, `reliability`, `priority`, `name`) 
	VALUES (1576, 421, NULL, NULL, 1, 3, 'VsFTP Response - Service not available, closing control connection');
INSERT INTO `plugin_sid` (`plugin_id`, `sid`, `category_id`, `class_id`, `reliability`, `priority`, `name`) 
	VALUES (1576, 425, NULL, NULL, 1, 3, 'VsFTP Response - Cannot open data connection');
INSERT INTO `plugin_sid` (`plugin_id`, `sid`, `category_id`, `class_id`, `reliability`, `priority`, `name`) 
	VALUES (1576, 426, NULL, NULL, 1, 3, 'VsFTP Response - Connection closed, transfer aborted');	
INSERT INTO `plugin_sid` (`plugin_id`, `sid`, `category_id`, `class_id`, `reliability`, `priority`, `name`) 
	VALUES (1576, 450, NULL, NULL, 1, 3, 'VsFTP Response - Requested file action not taken. File unavailable (e.g., file busy)');
INSERT INTO `plugin_sid` (`plugin_id`, `sid`, `category_id`, `class_id`, `reliability`, `priority`, `name`) 
	VALUES (1576, 451, NULL, NULL, 1, 3, 'VsFTP Response - Requested action aborted, local error in processing');
INSERT INTO `plugin_sid` (`plugin_id`, `sid`, `category_id`, `class_id`, `reliability`, `priority`, `name`) 
	VALUES (1576, 452, NULL, NULL, 1, 3, 'VsFTP Response - Requested action not taken. Insufficient storage space in system');
INSERT INTO `plugin_sid` (`plugin_id`, `sid`, `category_id`, `class_id`, `reliability`, `priority`, `name`) 
	VALUES (1576, 500, NULL, NULL, 1, 3, 'VsFTP Response - Syntax error, command unrecognized');
INSERT INTO `plugin_sid` (`plugin_id`, `sid`, `category_id`, `class_id`, `reliability`, `priority`, `name`) 
	VALUES (1576, 501, NULL, NULL, 1, 3, 'VsFTP Response - Syntax error in parameters or arguments');
INSERT INTO `plugin_sid` (`plugin_id`, `sid`, `category_id`, `class_id`, `reliability`, `priority`, `name`) 
	VALUES (1576, 502, NULL, NULL, 1, 3, 'VsFTP Response - Command not implemented');
INSERT INTO `plugin_sid` (`plugin_id`, `sid`, `category_id`, `class_id`, `reliability`, `priority`, `name`) 
	VALUES (1576, 503, NULL, NULL, 1, 3, 'VsFTP Response - Bad sequence of commands');
INSERT INTO `plugin_sid` (`plugin_id`, `sid`, `category_id`, `class_id`, `reliability`, `priority`, `name`) 
	VALUES (1576, 504, NULL, NULL, 1, 3, 'VsFTP Response - Command not implemented for that parameter');
INSERT INTO `plugin_sid` (`plugin_id`, `sid`, `category_id`, `class_id`, `reliability`, `priority`, `name`) 
	VALUES (1576, 530, NULL, NULL, 1, 3, 'VsFTP Response - User not logged in');
INSERT INTO `plugin_sid` (`plugin_id`, `sid`, `category_id`, `class_id`, `reliability`, `priority`, `name`) 
	VALUES (1576, 532, NULL, NULL, 1, 3, 'VsFTP Response - Need account for storing files');
INSERT INTO `plugin_sid` (`plugin_id`, `sid`, `category_id`, `class_id`, `reliability`, `priority`, `name`) 
	VALUES (1576, 550, NULL, NULL, 1, 3, 'VsFTP Response - Requested action not taken. File unavailable-or-I/O Error: Socket Closed');
INSERT INTO `plugin_sid` (`plugin_id`, `sid`, `category_id`, `class_id`, `reliability`, `priority`, `name`) 
	VALUES (1576, 552, NULL, NULL, 1, 3, 'VsFTP Response - Requested file action aborted, storage allocation exceeded');
INSERT INTO `plugin_sid` (`plugin_id`, `sid`, `category_id`, `class_id`, `reliability`, `priority`, `name`) 
	VALUES (1576, 553, NULL, NULL, 1, 3, 'VsFTP Response - Requested action not taken. Illegal file name');

