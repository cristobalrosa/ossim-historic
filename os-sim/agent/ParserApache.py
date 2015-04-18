import re
import sys
import time

import Parser
import util

class ParserApache(Parser.Parser):

    def process(self):

        if self.plugin["source"] == 'syslog':
            self.__processSyslog()
            
        else:
            print "log type " + self.plugin["source"] +\
                  " unknown for Apache..."
            sys.exit()


    def __processSyslog(self):
        
        util.debug ('ParserApache', 'plugin started (syslog)...', '--')
        
        pattern = '(\S+) (\S+) (\S+) \[(\d\d)\/(\w\w\w)\/(\d\d\d\d):(\d\d):(\d\d):(\d\d).+"(.+)" (\d+) (\S+)'
            
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
                    (source, user, authuser, day, monthmmm, year, hour,
                     minute, second, request, result, size) = result[0]

                    datestring = "%s %s %s %s %s %s" % \
                        (year, monthmmm, day, hour, minute, second)
                    
                    date = time.strftime('%Y-%m-%d %H:%M:%S', 
                                         time.strptime(datestring, 
                                                       "%Y %b %d %H %M %S"))

                    # TODO: adjust priority depending of the result ?
                    self.agent.sendMessage(type = 'detector',
                                     date       = date,
                                     sensor     = self.plugin["sensor"],
                                     interface  = self.plugin["interface"],
                                     plugin_id  = self.plugin["id"],
                                     plugin_sid = result,
                                     priority   = 1,
                                     protocol   = 'TCP',
                                     src_ip     = source,
                                     src_port   = '',
                                     dst_ip     = '127.0.0.1', # TODO !!
                                     dst_port   = 80)          # TODO !!

                except IndexError: 
                    pass
        fd.close()

