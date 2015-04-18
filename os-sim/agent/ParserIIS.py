import re
import sys
import time

import Parser
import util

class ParserIIS(Parser.Parser):

    def process(self):
        
        if self.plugin["source"] == 'syslog':
            self.__processSyslog()
            
        else:
            print "log type " + self.plugin["source"] +\
                  " unknown for IIS..."
            sys.exit()


    def __processSyslog(self):
        
        util.debug (__name__, 'plugin started (syslog)...', '--')
        
        pattern = '(\d\d\d\d)-(\d\d)-(\d\d) (\d\d):(\d\d):(\d\d)\S+ (\S+) (\S+) (\S+) (\d+) (\w+) (\S+) \S+ (\d+)'
            
        location = self.plugin["location"]
        fd = open(location, 'r')
            
        # Move to the end of file
        fd.seek(0, 2)
            
        while 1:
            where = fd.tell()
            line = fd.readline()
            if not line: # EOF reached
                time.sleep(1)
                fd.seek(where)
            else:
                result = re.findall(str(pattern), line)
                try: 
                    (year, month, day, hour, minute, second, source, user, 
                     to, port, method, document, result) = result[0]

                    datestring = "%s %s %s %s %s %s" % \
                        (year, month, day, hour, minute, second)
                    
                    date = time.strftime('%Y-%m-%d %H:%M:%S', 
                                         time.strptime(datestring, 
                                                       "%Y %b %d %H %M %S"))

                    # TODO: adjust priority depending of the result ?
                    
                    self.agent.sendMessage(type     = 'detector',
                                     date       = date,
                                     sensor     = self.plugin["sensor"],
                                     interface  = self.plugin["interface"],
                                     plugin_id  = self.plugin["id"],
                                     plugin_sid = result,
                                     priority   = 1,
                                     protocol   = 'TCP',
                                     src_ip     = source,
                                     src_port   = '',
                                     dst_ip     = to,
                                     dst_port   = port)
                    
                except IndexError: 
                    pass
        fd.close()

