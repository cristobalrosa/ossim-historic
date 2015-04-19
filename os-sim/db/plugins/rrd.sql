-- rrd
-- type: detector
-- plugin_id: 1507,1508
--
DELETE FROM plugin WHERE id = "1507";
DELETE FROM plugin WHERE id = "1508";
DELETE FROM plugin_sid where plugin_id = "1507";
DELETE FROM plugin_sid where plugin_id = "1508";


INSERT INTO plugin (id, type, name, description) VALUES (1507, 1, 'rrd_threshold', 'RRD Threshold');
INSERT INTO plugin (id, type, name, description) VALUES (1508, 1, 'rrd_anomaly', 'RRD Anomaly');

--
-- RRD Threshold Sids
--
INSERT INTO plugin_sid (plugin_id, sid, category_id, class_id, name) VALUES (1507, 1, NULL, NULL, 'rrd_threshold: ntop global activeHostSendersNum');
INSERT INTO plugin_sid (plugin_id, sid, category_id, class_id, name) VALUES (1507, 2, NULL, NULL, 'rrd_threshold: ntop global arpRarpBytes');
INSERT INTO plugin_sid (plugin_id, sid, category_id, class_id, name) VALUES (1507, 3, NULL, NULL, 'rrd_threshold: ntop global broadcastPkts');
INSERT INTO plugin_sid (plugin_id, sid, category_id, class_id, name) VALUES (1507, 4, NULL, NULL, 'rrd_threshold: ntop global ethernetBytes');
INSERT INTO plugin_sid (plugin_id, sid, category_id, class_id, name) VALUES (1507, 5, NULL, NULL, 'rrd_threshold: ntop global ethernetPkts');
INSERT INTO plugin_sid (plugin_id, sid, category_id, class_id, name) VALUES (1507, 6, NULL, NULL, 'rrd_threshold: ntop global fragmentedIpBytes');
INSERT INTO plugin_sid (plugin_id, sid, category_id, class_id, name) VALUES (1507, 7, NULL, NULL, 'rrd_threshold: ntop global icmpBytes');
INSERT INTO plugin_sid (plugin_id, sid, category_id, class_id, name) VALUES (1507, 8, NULL, NULL, 'rrd_threshold: ntop global igmpBytes');
INSERT INTO plugin_sid (plugin_id, sid, category_id, class_id, name) VALUES (1507, 9, NULL, NULL, 'rrd_threshold: ntop global ipBytes');
INSERT INTO plugin_sid (plugin_id, sid, category_id, class_id, name) VALUES (1507, 10, NULL, NULL, 'rrd_threshold: ntop global IP_DHCP-BOOTPBytes');
INSERT INTO plugin_sid (plugin_id, sid, category_id, class_id, name) VALUES (1507, 11, NULL, NULL, 'rrd_threshold: ntop global IP_DNSBytes');
INSERT INTO plugin_sid (plugin_id, sid, category_id, class_id, name, priority, reliability) VALUES (1507, 12, NULL, NULL, 'rrd_threshold: ntop global IP_eDonkeyBytes', 3, 3);
INSERT INTO plugin_sid (plugin_id, sid, category_id, class_id, name) VALUES (1507, 13, NULL, NULL, 'rrd_threshold: ntop global IP_FTPBytes');
INSERT INTO plugin_sid (plugin_id, sid, category_id, class_id, name, priority, reliability) VALUES (1507, 14, NULL, NULL, 'rrd_threshold: ntop global IP_GnutellaBytes', 3, 3);
INSERT INTO plugin_sid (plugin_id, sid, category_id, class_id, name) VALUES (1507, 15, NULL, NULL, 'rrd_threshold: ntop global IP_HTTPBytes');
INSERT INTO plugin_sid (plugin_id, sid, category_id, class_id, name, priority, reliability) VALUES (1507, 16, NULL, NULL, 'rrd_threshold: ntop global IP_KazaaBytes', 3, 3);
INSERT INTO plugin_sid (plugin_id, sid, category_id, class_id, name) VALUES (1507, 17, NULL, NULL, 'rrd_threshold: ntop global IP_MailBytes');
INSERT INTO plugin_sid (plugin_id, sid, category_id, class_id, name) VALUES (1507, 18, NULL, NULL, 'rrd_threshold: ntop global IP_MessengerBytes');
INSERT INTO plugin_sid (plugin_id, sid, category_id, class_id, name, priority, reliability) VALUES (1507, 19, NULL, NULL, 'rrd_threshold: ntop global IP_NBios-IPBytes', 3, 1);
INSERT INTO plugin_sid (plugin_id, sid, category_id, class_id, name) VALUES (1507, 20, NULL, NULL, 'rrd_threshold: ntop global IP_NFSBytes');
INSERT INTO plugin_sid (plugin_id, sid, category_id, class_id, name) VALUES (1507, 21, NULL, NULL, 'rrd_threshold: ntop global IP_NNTPBytes');
INSERT INTO plugin_sid (plugin_id, sid, category_id, class_id, name) VALUES (1507, 22, NULL, NULL, 'rrd_threshold: ntop global IP_SNMPBytes');
INSERT INTO plugin_sid (plugin_id, sid, category_id, class_id, name) VALUES (1507, 23, NULL, NULL, 'rrd_threshold: ntop global IP_SSHBytes');
INSERT INTO plugin_sid (plugin_id, sid, category_id, class_id, name) VALUES (1507, 24, NULL, NULL, 'rrd_threshold: ntop global IP_TelnetBytes');
INSERT INTO plugin_sid (plugin_id, sid, category_id, class_id, name) VALUES (1507, 25, NULL, NULL, 'rrd_threshold: ntop global ipv6Bytes');
INSERT INTO plugin_sid (plugin_id, sid, category_id, class_id, name, priority, reliability) VALUES (1507, 26, NULL, NULL, 'rrd_threshold: ntop global IP_WinMXBytes', 3, 3);
INSERT INTO plugin_sid (plugin_id, sid, category_id, class_id, name) VALUES (1507, 27, NULL, NULL, 'rrd_threshold: ntop global IP_X11Bytes');
INSERT INTO plugin_sid (plugin_id, sid, category_id, class_id, name) VALUES (1507, 28, NULL, NULL, 'rrd_threshold: ntop global ipxBytes');
INSERT INTO plugin_sid (plugin_id, sid, category_id, class_id, name, priority, reliability) VALUES (1507, 29, NULL, NULL, 'rrd_threshold: ntop global knownHostsNum', 3, 3);
INSERT INTO plugin_sid (plugin_id, sid, category_id, class_id, name) VALUES (1507, 30, NULL, NULL, 'rrd_threshold: ntop global multicastPkts');
INSERT INTO plugin_sid (plugin_id, sid, category_id, class_id, name) VALUES (1507, 31, NULL, NULL, 'rrd_threshold: ntop global otherBytes');
INSERT INTO plugin_sid (plugin_id, sid, category_id, class_id, name) VALUES (1507, 32, NULL, NULL, 'rrd_threshold: ntop global otherIpBytes');
INSERT INTO plugin_sid (plugin_id, sid, category_id, class_id, name) VALUES (1507, 33, NULL, NULL, 'rrd_threshold: ntop global stpBytes');
INSERT INTO plugin_sid (plugin_id, sid, category_id, class_id, name) VALUES (1507, 34, NULL, NULL, 'rrd_threshold: ntop global tcpBytes');
INSERT INTO plugin_sid (plugin_id, sid, category_id, class_id, name) VALUES (1507, 35, NULL, NULL, 'rrd_threshold: ntop global udpBytes');
INSERT INTO plugin_sid (plugin_id, sid, category_id, class_id, name) VALUES (1507, 36, NULL, NULL, 'rrd_threshold: ntop global upTo1024Pkts');
INSERT INTO plugin_sid (plugin_id, sid, category_id, class_id, name) VALUES (1507, 37, NULL, NULL, 'rrd_threshold: ntop global upTo128Pkts');
INSERT INTO plugin_sid (plugin_id, sid, category_id, class_id, name) VALUES (1507, 38, NULL, NULL, 'rrd_threshold: ntop global upTo1518Pkts');
INSERT INTO plugin_sid (plugin_id, sid, category_id, class_id, name) VALUES (1507, 39, NULL, NULL, 'rrd_threshold: ntop global upTo256Pkts');
INSERT INTO plugin_sid (plugin_id, sid, category_id, class_id, name) VALUES (1507, 40, NULL, NULL, 'rrd_threshold: ntop global upTo512Pkts');
INSERT INTO plugin_sid (plugin_id, sid, category_id, class_id, name) VALUES (1507, 41, NULL, NULL, 'rrd_threshold: ntop global upTo64Pkts');
INSERT INTO plugin_sid (plugin_id, sid, category_id, class_id, name) VALUES (1507, 42, NULL, NULL, 'rrd_threshold: ntop host arp_rarpRcvd');
INSERT INTO plugin_sid (plugin_id, sid, category_id, class_id, name) VALUES (1507, 43, NULL, NULL, 'rrd_threshold: ntop host arp_rarpSent');
INSERT INTO plugin_sid (plugin_id, sid, category_id, class_id, name) VALUES (1507, 44, NULL, NULL, 'rrd_threshold: ntop host arpReplyPktsRcvd');
INSERT INTO plugin_sid (plugin_id, sid, category_id, class_id, name) VALUES (1507, 45, NULL, NULL, 'rrd_threshold: ntop host arpReplyPktsSent');
INSERT INTO plugin_sid (plugin_id, sid, category_id, class_id, name) VALUES (1507, 46, NULL, NULL, 'rrd_threshold: ntop host arpReqPktsSent');
INSERT INTO plugin_sid (plugin_id, sid, category_id, class_id, name) VALUES (1507, 47, NULL, NULL, 'rrd_threshold: ntop host bytesBroadcastSent');
INSERT INTO plugin_sid (plugin_id, sid, category_id, class_id, name) VALUES (1507, 48, NULL, NULL, 'rrd_threshold: ntop host bytesRcvdLoc');
INSERT INTO plugin_sid (plugin_id, sid, category_id, class_id, name) VALUES (1507, 49, NULL, NULL, 'rrd_threshold: ntop host bytesRcvd');
INSERT INTO plugin_sid (plugin_id, sid, category_id, class_id, name) VALUES (1507, 50, NULL, NULL, 'rrd_threshold: ntop host bytesSentLoc');
INSERT INTO plugin_sid (plugin_id, sid, category_id, class_id, name) VALUES (1507, 51, NULL, NULL, 'rrd_threshold: ntop host bytesSent');
INSERT INTO plugin_sid (plugin_id, sid, category_id, class_id, name) VALUES (1507, 52, NULL, NULL, 'rrd_threshold: ntop host icmpRcvd');
INSERT INTO plugin_sid (plugin_id, sid, category_id, class_id, name) VALUES (1507, 53, NULL, NULL, 'rrd_threshold: ntop host icmpSent');
INSERT INTO plugin_sid (plugin_id, sid, category_id, class_id, name) VALUES (1507, 54, NULL, NULL, 'rrd_threshold: ntop host ipBytesRcvd');
INSERT INTO plugin_sid (plugin_id, sid, category_id, class_id, name) VALUES (1507, 55, NULL, NULL, 'rrd_threshold: ntop host ipBytesSent');
INSERT INTO plugin_sid (plugin_id, sid, category_id, class_id, name) VALUES (1507, 56, NULL, NULL, 'rrd_threshold: ntop host IP_DNSRcvdBytes');
INSERT INTO plugin_sid (plugin_id, sid, category_id, class_id, name) VALUES (1507, 57, NULL, NULL, 'rrd_threshold: ntop host IP_FTPRcvdBytes');
INSERT INTO plugin_sid (plugin_id, sid, category_id, class_id, name) VALUES (1507, 58, NULL, NULL, 'rrd_threshold: ntop host IP_FTPSentBytes');
INSERT INTO plugin_sid (plugin_id, sid, category_id, class_id, name) VALUES (1507, 59, NULL, NULL, 'rrd_threshold: ntop host IP_HTTPRcvdBytes');
INSERT INTO plugin_sid (plugin_id, sid, category_id, class_id, name) VALUES (1507, 60, NULL, NULL, 'rrd_threshold: ntop host IP_HTTPSentBytes');
INSERT INTO plugin_sid (plugin_id, sid, category_id, class_id, name) VALUES (1507, 61, NULL, NULL, 'rrd_threshold: ntop host IP_MailRcvdBytes');
INSERT INTO plugin_sid (plugin_id, sid, category_id, class_id, name) VALUES (1507, 62, NULL, NULL, 'rrd_threshold: ntop host IP_MailSentBytes');
INSERT INTO plugin_sid (plugin_id, sid, category_id, class_id, name) VALUES (1507, 63, NULL, NULL, 'rrd_threshold: ntop host IP_SNMPRcvdBytes');
INSERT INTO plugin_sid (plugin_id, sid, category_id, class_id, name) VALUES (1507, 64, NULL, NULL, 'rrd_threshold: ntop host IP_SSHRcvdBytes');
INSERT INTO plugin_sid (plugin_id, sid, category_id, class_id, name) VALUES (1507, 65, NULL, NULL, 'rrd_threshold: ntop host IP_SSHSentBytes');
INSERT INTO plugin_sid (plugin_id, sid, category_id, class_id, name) VALUES (1507, 66, NULL, NULL, 'rrd_threshold: ntop host IP_TelnetRcvdBytes');
INSERT INTO plugin_sid (plugin_id, sid, category_id, class_id, name) VALUES (1507, 67, NULL, NULL, 'rrd_threshold: ntop host IP_TelnetSentBytes');
INSERT INTO plugin_sid (plugin_id, sid, category_id, class_id, name) VALUES (1507, 68, NULL, NULL, 'rrd_threshold: ntop host pktBroadcastSent');
INSERT INTO plugin_sid (plugin_id, sid, category_id, class_id, name) VALUES (1507, 69, NULL, NULL, 'rrd_threshold: ntop host pktRcvd');
INSERT INTO plugin_sid (plugin_id, sid, category_id, class_id, name) VALUES (1507, 70, NULL, NULL, 'rrd_threshold: ntop host pktSent');
INSERT INTO plugin_sid (plugin_id, sid, category_id, class_id, name) VALUES (1507, 71, NULL, NULL, 'rrd_threshold: ntop host tcpRcvdLoc');
INSERT INTO plugin_sid (plugin_id, sid, category_id, class_id, name) VALUES (1507, 72, NULL, NULL, 'rrd_threshold: ntop host tcpSentLoc');
INSERT INTO plugin_sid (plugin_id, sid, category_id, class_id, name, priority, reliability) VALUES (1507, 73, NULL, NULL, 'rrd_threshold: ntop host totContactedRcvdPeers', 5, 3);
INSERT INTO plugin_sid (plugin_id, sid, category_id, class_id, name, priority, reliability) VALUES (1507, 74, NULL, NULL, 'rrd_threshold: ntop host totContactedSentPeers', 5, 3);
INSERT INTO plugin_sid (plugin_id, sid, category_id, class_id, name) VALUES (1507, 75, NULL, NULL, 'rrd_threshold: ntop host udpRcvdLoc');
INSERT INTO plugin_sid (plugin_id, sid, category_id, class_id, name, priority, reliability) VALUES (1507, 76, NULL, NULL, 'rrd_threshold: ntop host synPktsSent', 5, 3);
INSERT INTO plugin_sid (plugin_id, sid, category_id, class_id, name, priority, reliability) VALUES (1507, 77, NULL, NULL, 'rrd_threshold: ntop host synPktsRcvd', 5, 3);
INSERT INTO plugin_sid (plugin_id, sid, category_id, class_id, name, priority, reliability) VALUES (1507, 78, NULL, NULL, 'rrd_threshold: ntop host totContactedSentPeers', 5, 3);
INSERT INTO plugin_sid (plugin_id, sid, category_id, class_id, name, priority, reliability) VALUES (1507, 79, NULL, NULL, 'rrd_threshold: ntop host totContactedRcvdPeers', 5, 3);
INSERT INTO plugin_sid (plugin_id, sid, category_id, class_id, name, priority, reliability) VALUES (1507, 80, NULL, NULL, 'rrd_threshold: ntop host web_sessions', 5, 3);
INSERT INTO plugin_sid (plugin_id, sid, category_id, class_id, name, priority, reliability) VALUES (1507, 81, NULL, NULL, 'rrd_threshold: ntop host mail_sessions', 5, 3);
INSERT INTO plugin_sid (plugin_id, sid, category_id, class_id, name, priority, reliability) VALUES (1507, 82, NULL, NULL, 'rrd_threshold: ntop host nb_sessions', 5, 3);


--
-- RRD Anomaly Sids
--

INSERT INTO plugin_sid (plugin_id, sid, category_id, class_id, name, priority, reliability) VALUES (1508, 1, NULL, NULL, 'rrd_anomaly: ntop global activeHostSendersNum', 2, 2);
INSERT INTO plugin_sid (plugin_id, sid, category_id, class_id, name) VALUES (1508, 2, NULL, NULL, 'rrd_anomaly: ntop global arpRarpBytes');
INSERT INTO plugin_sid (plugin_id, sid, category_id, class_id, name, priority, reliability) VALUES (1508, 3, NULL, NULL, 'rrd_anomaly: ntop global broadcastPkts', 2, 2);
INSERT INTO plugin_sid (plugin_id, sid, category_id, class_id, name) VALUES (1508, 4, NULL, NULL, 'rrd_anomaly: ntop global ethernetBytes');
INSERT INTO plugin_sid (plugin_id, sid, category_id, class_id, name) VALUES (1508, 5, NULL, NULL, 'rrd_anomaly: ntop global ethernetPkts');
INSERT INTO plugin_sid (plugin_id, sid, category_id, class_id, name, priority, reliability) VALUES (1508, 6, NULL, NULL, 'rrd_anomaly: ntop global fragmentedIpBytes', 3, 3);
INSERT INTO plugin_sid (plugin_id, sid, category_id, class_id, name, priority, reliability) VALUES (1508, 7, NULL, NULL, 'rrd_anomaly: ntop global icmpBytes', 3, 3);
INSERT INTO plugin_sid (plugin_id, sid, category_id, class_id, name) VALUES (1508, 8, NULL, NULL, 'rrd_anomaly: ntop global igmpBytes');
INSERT INTO plugin_sid (plugin_id, sid, category_id, class_id, name, priority, reliability) VALUES (1508, 9, NULL, NULL, 'rrd_anomaly: ntop global ipBytes', 3, 3);
INSERT INTO plugin_sid (plugin_id, sid, category_id, class_id, name) VALUES (1508, 10, NULL, NULL, 'rrd_anomaly: ntop global IP_DHCP-BOOTPBytes');
INSERT INTO plugin_sid (plugin_id, sid, category_id, class_id, name) VALUES (1508, 11, NULL, NULL, 'rrd_anomaly: ntop global IP_DNSBytes');
INSERT INTO plugin_sid (plugin_id, sid, category_id, class_id, name, priority, reliability) VALUES (1508, 12, NULL, NULL, 'rrd_anomaly: ntop global IP_eDonkeyBytes', 5, 5);
INSERT INTO plugin_sid (plugin_id, sid, category_id, class_id, name, priority, reliability) VALUES (1508, 13, NULL, NULL, 'rrd_anomaly: ntop global IP_FTPBytes', 3, 3);
INSERT INTO plugin_sid (plugin_id, sid, category_id, class_id, name, priority, reliability) VALUES (1508, 14, NULL, NULL, 'rrd_anomaly: ntop global IP_GnutellaBytes', 5, 5);
INSERT INTO plugin_sid (plugin_id, sid, category_id, class_id, name, priority, reliability) VALUES (1508, 15, NULL, NULL, 'rrd_anomaly: ntop global IP_HTTPBytes', 3, 3);
INSERT INTO plugin_sid (plugin_id, sid, category_id, class_id, name, priority, reliability) VALUES (1508, 16, NULL, NULL, 'rrd_anomaly: ntop global IP_KazaaBytes', 5, 5);
INSERT INTO plugin_sid (plugin_id, sid, category_id, class_id, name, priority, reliability) VALUES (1508, 17, NULL, NULL, 'rrd_anomaly: ntop global IP_MailBytes', 5, 5);
INSERT INTO plugin_sid (plugin_id, sid, category_id, class_id, name, priority, reliability) VALUES (1508, 18, NULL, NULL, 'rrd_anomaly: ntop global IP_MessengerBytes', 3, 3);
INSERT INTO plugin_sid (plugin_id, sid, category_id, class_id, name, priority, reliability) VALUES (1508, 19, NULL, NULL, 'rrd_anomaly: ntop global IP_NBios-IPBytes', 5, 5);
INSERT INTO plugin_sid (plugin_id, sid, category_id, class_id, name) VALUES (1508, 20, NULL, NULL, 'rrd_anomaly: ntop global IP_NFSBytes');
INSERT INTO plugin_sid (plugin_id, sid, category_id, class_id, name) VALUES (1508, 21, NULL, NULL, 'rrd_anomaly: ntop global IP_NNTPBytes');
INSERT INTO plugin_sid (plugin_id, sid, category_id, class_id, name) VALUES (1508, 22, NULL, NULL, 'rrd_anomaly: ntop global IP_SNMPBytes');
INSERT INTO plugin_sid (plugin_id, sid, category_id, class_id, name, priority, reliability) VALUES (1508, 23, NULL, NULL, 'rrd_anomaly: ntop global IP_SSHBytes', 3, 3);
INSERT INTO plugin_sid (plugin_id, sid, category_id, class_id, name, priority, reliability) VALUES (1508, 24, NULL, NULL, 'rrd_anomaly: ntop global IP_TelnetBytes', 3, 3);
INSERT INTO plugin_sid (plugin_id, sid, category_id, class_id, name) VALUES (1508, 25, NULL, NULL, 'rrd_anomaly: ntop global ipv6Bytes');
INSERT INTO plugin_sid (plugin_id, sid, category_id, class_id, name) VALUES (1508, 26, NULL, NULL, 'rrd_anomaly: ntop global IP_WinMXBytes');
INSERT INTO plugin_sid (plugin_id, sid, category_id, class_id, name) VALUES (1508, 27, NULL, NULL, 'rrd_anomaly: ntop global IP_X11Bytes');
INSERT INTO plugin_sid (plugin_id, sid, category_id, class_id, name) VALUES (1508, 28, NULL, NULL, 'rrd_anomaly: ntop global ipxBytes');
INSERT INTO plugin_sid (plugin_id, sid, category_id, class_id, name, priority, reliability) VALUES (1508, 29, NULL, NULL, 'rrd_anomaly: ntop global knownHostsNum', 4, 4);
INSERT INTO plugin_sid (plugin_id, sid, category_id, class_id, name, priority, reliability) VALUES (1508, 30, NULL, NULL, 'rrd_anomaly: ntop global multicastPkts', 3, 3);
INSERT INTO plugin_sid (plugin_id, sid, category_id, class_id, name) VALUES (1508, 31, NULL, NULL, 'rrd_anomaly: ntop global otherBytes');
INSERT INTO plugin_sid (plugin_id, sid, category_id, class_id, name, priority, reliability) VALUES (1508, 32, NULL, NULL, 'rrd_anomaly: ntop global otherIpBytes', 3, 3);
INSERT INTO plugin_sid (plugin_id, sid, category_id, class_id, name) VALUES (1508, 33, NULL, NULL, 'rrd_anomaly: ntop global stpBytes');
INSERT INTO plugin_sid (plugin_id, sid, category_id, class_id, name) VALUES (1508, 34, NULL, NULL, 'rrd_anomaly: ntop global tcpBytes');
INSERT INTO plugin_sid (plugin_id, sid, category_id, class_id, name) VALUES (1508, 35, NULL, NULL, 'rrd_anomaly: ntop global udpBytes');
INSERT INTO plugin_sid (plugin_id, sid, category_id, class_id, name) VALUES (1508, 36, NULL, NULL, 'rrd_anomaly: ntop global upTo1024Pkts');
INSERT INTO plugin_sid (plugin_id, sid, category_id, class_id, name) VALUES (1508, 37, NULL, NULL, 'rrd_anomaly: ntop global upTo128Pkts');
INSERT INTO plugin_sid (plugin_id, sid, category_id, class_id, name) VALUES (1508, 38, NULL, NULL, 'rrd_anomaly: ntop global upTo1518Pkts');
INSERT INTO plugin_sid (plugin_id, sid, category_id, class_id, name) VALUES (1508, 39, NULL, NULL, 'rrd_anomaly: ntop global upTo256Pkts');
INSERT INTO plugin_sid (plugin_id, sid, category_id, class_id, name) VALUES (1508, 40, NULL, NULL, 'rrd_anomaly: ntop global upTo512Pkts');
INSERT INTO plugin_sid (plugin_id, sid, category_id, class_id, name) VALUES (1508, 41, NULL, NULL, 'rrd_anomaly: ntop global upTo64Pkts');
INSERT INTO plugin_sid (plugin_id, sid, category_id, class_id, name) VALUES (1508, 42, NULL, NULL, 'rrd_anomaly: ntop host arp_rarpRcvd');
INSERT INTO plugin_sid (plugin_id, sid, category_id, class_id, name) VALUES (1508, 43, NULL, NULL, 'rrd_anomaly: ntop host arp_rarpSent');
INSERT INTO plugin_sid (plugin_id, sid, category_id, class_id, name) VALUES (1508, 44, NULL, NULL, 'rrd_anomaly: ntop host arpReplyPktsRcvd');
INSERT INTO plugin_sid (plugin_id, sid, category_id, class_id, name) VALUES (1508, 45, NULL, NULL, 'rrd_anomaly: ntop host arpReplyPktsSent');
INSERT INTO plugin_sid (plugin_id, sid, category_id, class_id, name) VALUES (1508, 46, NULL, NULL, 'rrd_anomaly: ntop host arpReqPktsSent');
INSERT INTO plugin_sid (plugin_id, sid, category_id, class_id, name, priority, reliability) VALUES (1508, 47, NULL, NULL, 'rrd_anomaly: ntop host bytesBroadcastSent', 3, 3);
INSERT INTO plugin_sid (plugin_id, sid, category_id, class_id, name) VALUES (1508, 48, NULL, NULL, 'rrd_anomaly: ntop host bytesRcvdLoc');
INSERT INTO plugin_sid (plugin_id, sid, category_id, class_id, name) VALUES (1508, 49, NULL, NULL, 'rrd_anomaly: ntop host bytesRcvd');
INSERT INTO plugin_sid (plugin_id, sid, category_id, class_id, name) VALUES (1508, 50, NULL, NULL, 'rrd_anomaly: ntop host bytesSentLoc');
INSERT INTO plugin_sid (plugin_id, sid, category_id, class_id, name) VALUES (1508, 51, NULL, NULL, 'rrd_anomaly: ntop host bytesSent');
INSERT INTO plugin_sid (plugin_id, sid, category_id, class_id, name, priority, reliability) VALUES (1508, 52, NULL, NULL, 'rrd_anomaly: ntop host icmpRcvd', 3, 3);
INSERT INTO plugin_sid (plugin_id, sid, category_id, class_id, name, priority, reliability) VALUES (1508, 53, NULL, NULL, 'rrd_anomaly: ntop host icmpSent', 3, 3);
INSERT INTO plugin_sid (plugin_id, sid, category_id, class_id, name, priority, reliability) VALUES (1508, 54, NULL, NULL, 'rrd_anomaly: ntop host ipBytesRcvd', 3, 3);
INSERT INTO plugin_sid (plugin_id, sid, category_id, class_id, name, priority, reliability) VALUES (1508, 55, NULL, NULL, 'rrd_anomaly: ntop host ipBytesSent', 3, 3);
INSERT INTO plugin_sid (plugin_id, sid, category_id, class_id, name) VALUES (1508, 56, NULL, NULL, 'rrd_anomaly: ntop host IP_DNSRcvdBytes');
INSERT INTO plugin_sid (plugin_id, sid, category_id, class_id, name) VALUES (1508, 57, NULL, NULL, 'rrd_anomaly: ntop host IP_FTPRcvdBytes');
INSERT INTO plugin_sid (plugin_id, sid, category_id, class_id, name) VALUES (1508, 58, NULL, NULL, 'rrd_anomaly: ntop host IP_FTPSentBytes');
INSERT INTO plugin_sid (plugin_id, sid, category_id, class_id, name, priority, reliability) VALUES (1508, 59, NULL, NULL, 'rrd_anomaly: ntop host IP_HTTPRcvdBytes', 2, 2);
INSERT INTO plugin_sid (plugin_id, sid, category_id, class_id, name, priority, reliability) VALUES (1508, 60, NULL, NULL, 'rrd_anomaly: ntop host IP_HTTPSentBytes', 2, 2);
INSERT INTO plugin_sid (plugin_id, sid, category_id, class_id, name, priority, reliability) VALUES (1508, 61, NULL, NULL, 'rrd_anomaly: ntop host IP_MailRcvdBytes', 3, 3);
INSERT INTO plugin_sid (plugin_id, sid, category_id, class_id, name, priority, reliability) VALUES (1508, 62, NULL, NULL, 'rrd_anomaly: ntop host IP_MailSentBytes', 5, 5);
INSERT INTO plugin_sid (plugin_id, sid, category_id, class_id, name) VALUES (1508, 63, NULL, NULL, 'rrd_anomaly: ntop host IP_SNMPRcvdBytes');
INSERT INTO plugin_sid (plugin_id, sid, category_id, class_id, name, priority, reliability) VALUES (1508, 64, NULL, NULL, 'rrd_anomaly: ntop host IP_SSHRcvdBytes', 2, 2);
INSERT INTO plugin_sid (plugin_id, sid, category_id, class_id, name, priority, reliability) VALUES (1508, 65, NULL, NULL, 'rrd_anomaly: ntop host IP_SSHSentBytes', 2, 2);
INSERT INTO plugin_sid (plugin_id, sid, category_id, class_id, name, priority, reliability) VALUES (1508, 66, NULL, NULL, 'rrd_anomaly: ntop host IP_TelnetRcvdBytes', 2, 2);
INSERT INTO plugin_sid (plugin_id, sid, category_id, class_id, name, priority, reliability) VALUES (1508, 67, NULL, NULL, 'rrd_anomaly: ntop host IP_TelnetSentBytes', 2, 2);
INSERT INTO plugin_sid (plugin_id, sid, category_id, class_id, name, priority, reliability) VALUES (1508, 68, NULL, NULL, 'rrd_anomaly: ntop host pktBroadcastSent', 2, 2);
INSERT INTO plugin_sid (plugin_id, sid, category_id, class_id, name) VALUES (1508, 69, NULL, NULL, 'rrd_anomaly: ntop host pktRcvd');
INSERT INTO plugin_sid (plugin_id, sid, category_id, class_id, name) VALUES (1508, 70, NULL, NULL, 'rrd_anomaly: ntop host pktSent');
INSERT INTO plugin_sid (plugin_id, sid, category_id, class_id, name) VALUES (1508, 71, NULL, NULL, 'rrd_anomaly: ntop host tcpRcvdLoc');
INSERT INTO plugin_sid (plugin_id, sid, category_id, class_id, name) VALUES (1508, 72, NULL, NULL, 'rrd_anomaly: ntop host tcpSentLoc');
INSERT INTO plugin_sid (plugin_id, sid, category_id, class_id, name, priority, reliability) VALUES (1508, 73, NULL, NULL, 'rrd_anomaly: ntop host totContactedRcvdPeers', 2, 2);
INSERT INTO plugin_sid (plugin_id, sid, category_id, class_id, name) VALUES (1508, 74, NULL, NULL, 'rrd_anomaly: ntop host totContactedSentPeers');
INSERT INTO plugin_sid (plugin_id, sid, category_id, class_id, name) VALUES (1508, 75, NULL, NULL, 'rrd_anomaly: ntop host udpRcvdLoc');
INSERT INTO plugin_sid (plugin_id, sid, category_id, class_id, name) VALUES (1508, 76, NULL, NULL, 'rrd_anomaly: ntop host synPktsSent');
INSERT INTO plugin_sid (plugin_id, sid, category_id, class_id, name) VALUES (1508, 77, NULL, NULL, 'rrd_anomaly: ntop host synPktsRcvd');
INSERT INTO plugin_sid (plugin_id, sid, category_id, class_id, name) VALUES (1508, 78, NULL, NULL, 'rrd_anomaly: ntop host web_sessions');
INSERT INTO plugin_sid (plugin_id, sid, category_id, class_id, name) VALUES (1508, 79, NULL, NULL, 'rrd_anomaly: ntop host mail_sessions');
INSERT INTO plugin_sid (plugin_id, sid, category_id, class_id, name) VALUES (1508, 80, NULL, NULL, 'rrd_anomaly: ntop host nb_sessions');


