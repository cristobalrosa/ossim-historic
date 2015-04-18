DROP TABLE IF EXISTS rrd_conf_global;
CREATE TABLE rrd_conf_global (
active_host_senders_num VARCHAR(40) NOT NULL,
arp_rarp_bytes    VARCHAR(40) NOT NULL,
broadcast_pkts    VARCHAR(40) NOT NULL,
ethernet_bytes    VARCHAR(40) NOT NULL, 
ethernet_pkts     VARCHAR(40) NOT NULL, 
icmp_bytes        VARCHAR(40) NOT NULL, 
igmp_bytes        VARCHAR(40) NOT NULL, 
ip_bytes          VARCHAR(40) NOT NULL, 
ip_dhcp_bootp_bytes VARCHAR(40) NOT NULL, 
ip_dns_bytes      VARCHAR(40) NOT NULL,
ip_edonkey_bytes  VARCHAR(40) NOT NULL, 
ip_ftp_bytes      VARCHAR(40) NOT NULL, 
ip_gnutella_bytes VARCHAR(40) NOT NULL, 
ip_http_bytes     VARCHAR(40) NOT NULL, 
ip_kazaa_bytes    VARCHAR(40) NOT NULL, 
ip_mail_bytes     VARCHAR(40) NOT NULL, 
ip_messenger_bytes VARCHAR(40) NOT NULL,
ip_nbios_ip_bytes VARCHAR(40) NOT NULL, 
ip_nfs_bytes      VARCHAR(40) NOT NULL, 
ip_nttp_bytes     VARCHAR(40) NOT NULL, 
ip_snmp_bytes     VARCHAR(40) NOT NULL, 
ip_ssh_bytes      VARCHAR(40) NOT NULL, 
ip_telnet_bytes   VARCHAR(40) NOT NULL, 
ip_winmx_bytes    VARCHAR(40) NOT NULL, 
ip_x11_bytes      VARCHAR(40) NOT NULL, 
ipx_bytes         VARCHAR(40) NOT NULL,
known_hosts_num   VARCHAR(40) NOT NULL,
multicast_pkts    VARCHAR(40) NOT NULL,
ospf_bytes        VARCHAR(40) NOT NULL,
other_bytes       VARCHAR(40) NOT NULL,
tcp_bytes         VARCHAR(40) NOT NULL,
udp_bytes         VARCHAR(40) NOT NULL,
up_to_1024_pkts   VARCHAR(40) NOT NULL,
up_to_128_pkts    VARCHAR(40) NOT NULL,
up_to_1518_pkts   VARCHAR(40) NOT NULL,
up_to_512_pkts    VARCHAR(40) NOT NULL,
up_to_64_pkts     VARCHAR(40) NOT NULL,
);
INSERT INTO rrd_conf_global VALUES(
'100,5,0.1,0.0035',
'100,5,0.1,0.0035',
'100,5,0.1,0.0035',
'100,5,0.1,0.0035',
'100,5,0.1,0.0035',
'100,5,0.1,0.0035',
'100,5,0.1,0.0035',
'100,5,0.1,0.0035',
'100,5,0.1,0.0035',
'100,5,0.1,0.0035',
'100,5,0.1,0.0035',
'100,5,0.1,0.0035',
'100,5,0.1,0.0035',
'100,5,0.1,0.0035',
'100,5,0.1,0.0035',
'100,5,0.1,0.0035',
'100,5,0.1,0.0035',
'100,5,0.1,0.0035',
'100,5,0.1,0.0035',
'100,5,0.1,0.0035',
'100,5,0.1,0.0035',
'100,5,0.1,0.0035',
'100,5,0.1,0.0035',
'100,5,0.1,0.0035',
'100,5,0.1,0.0035',
'100,5,0.1,0.0035',
'100,5,0.1,0.0035',
'100,5,0.1,0.0035',
'100,5,0.1,0.0035',
'100,5,0.1,0.0035',
'100,5,0.1,0.0035',
'100,5,0.1,0.0035',
'100,5,0.1,0.0035',
'100,5,0.1,0.0035',
'100,5,0.1,0.0035',
'100,5,0.1,0.0035',
'100,5,0.1,0.0035');

DROP TABLE IF EXISTS rrd_anomalies_global;
CREATE TABLE rrd_anomalies_global (
    what                    varchar(100) NOT NULL,
    count                   int NOT NULL,
    anomaly_time            varchar(40) NOT NULL,
    range                   varchar(30) NOT NULL,
    over                    int NOT NULL,
    acked                   int DEFAULT 0
);


