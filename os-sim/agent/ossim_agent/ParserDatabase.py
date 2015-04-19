'''
Parser Database
TODO:
TRANSLATIONS support
'''

import os, sys, time, re, socket

from Detector import Detector
from Event import Event, EventOS, EventMac, EventService, EventHids
from Logger import Logger
logger = Logger.logger
#from TailFollow import TailFollow
from time import sleep
from Config import Plugin

try:
    import MySQLdb
except ImportError:
    logger.critical("You need python mysqldb module installed")
try:
    import pymssql
except ImportError:
    logger.critical("You need python pymssql module installed")

class ParserDatabase(Detector):
        def __init__(self, conf, plugin, conn):
                self._conf = conf
                self._plugin = plugin
                self.rules = []          # list of RuleMatch objects
                self.conn = conn
                Detector.__init__(self, conf, plugin, conn)

        def process(self):
                logger.info("Started")
                rules = self._plugin.rules()
                #logger.info(rules['start_query']['query'])
                #if rules['start_query']['query']
                test = None
                if self._plugin.get("config", "source_type") == "mysql":
                        #Test Connection
                        try:
                                cursor = self.connectMysql()
                                logger.info("Connection OK")
                                test = 1
                        except:
                                logger.info("Can't connect to database")
                if self._plugin.get("config", "source_type") == "mssql":
                        try:
                                cursor = self.connectMssql()
                                logger.info("Connection OK")
                                test = 1
                        except:
                                logger.info("Can't connect to database")
                if test:
                        sql = rules['start_query']['query']
                        logger.info(sql)
                        cursor.execute(sql)
                        rows= cursor.fetchone()
                        if not rows:
                            logger.warning("Initial query empty, please double-check")
                            return
                        cVal = str(int(rows[0]))
                        logger.info(cVal)
                        #logger.info(cVal)
                        tSleep = self._plugin.get("config", "sleep")
                        sql = rules['query']['query']
                        #logger.info(sql)
                        ref = int(rules['query']['ref'])
                        logger.info(ref)
                        while 1:
                                logger.info("Querying Database")
                                sql = rules['query']['query']
                                sql = sql.replace("$1", str(cVal))
                                logger.info(sql)
                                cursor.execute(sql)
                                ret = cursor.fetchall()
                                if len(ret) > 0:
                                        cVal = ret[len(ret) - 1][0]
                                        for e in ret:
                                                self.generate(e)
                                time.sleep(int(tSleep))

        def connectMysql(self):
                logger.info("here")
                host = self._plugin.get("config", "source_ip")
                user = self._plugin.get("config", "user")
                passwd = self._plugin.get("config", "password")
                db = self._plugin.get("config", "db")
                db=MySQLdb.connect(host=host,user=user, passwd=passwd,db=db)
                cursor=db.cursor()
                return cursor

        def connectMssql(self):
                host = self._plugin.get("config", "source_ip")
                user = self._plugin.get("config", "user")
                passwd = self._plugin.get("config", "password")
                db = self._plugin.get("config", "db")
                db = pymssql.connect(host=host, user=user, password=passwd, database=db)
                cursor=db.cursor()
                return cursor

        def connectOracle():
                pass

        def generate(self, groups):
                event = Event()
                rules = self._plugin.rules()
                for key, value in rules['query'].iteritems():
                        if key != "query" and key != "regexp" and key != "ref":
                                #logger.info("Request")
                                event[key] = self._plugin.get_replace_array_value(value, groups)
                                #event[key] = self.get_replace_value(value, groups)
                                #self.plugin.get_replace_value
                if event is not None:
                        self.send_message(event)

