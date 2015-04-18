import xml.sax
import string
import util


class ConfigHandler(xml.sax.handler.ContentHandler):

    serverIp = ''
    serverPort = ''
    plugins = {}

    def __init__(self):
        self.plugin_id = 0
        self.inContent = 0
        self.theContent = ""
        xml.sax.handler.ContentHandler.__init__(self)

    def startElement (self, name, attrs):

        if name == 'serverip' or name == 'serverport' or name == 'path' \
          or name == 'source' or name == 'location':
            self.inContent = 1

        if name == 'plugin':
            plugin = {'id' : '', \
                      'name' : '', \
                      'type' : '', \
                      'start': '', \
                      'enable' : '', \
                      'startup' : '', \
                      'shutdown' : '', \
                      'source' : '', \
                      'interface' : '', \
                      'sensor' : '', \
                      'location' : '', \
                      'frequency': ''}
            plugin["id"] = util.normalizeWhitespace(attrs.get('id', None))
            self.plugin_id = plugin["id"]
            plugin["name"] = util.normalizeWhitespace(attrs.get('name', None))
            plugin["type"] = util.normalizeWhitespace(attrs.get('type', None))
            plugin["start"] = util.normalizeWhitespace(attrs.get('start', None))
            plugin["enable"] = util.normalizeWhitespace(attrs.get('enable', None))
            ConfigHandler.plugins[plugin["id"]] = plugin
        
        
    def endElement (self, name):

        if self.inContent:
            self.theContent = util.normalizeWhitespace(self.theContent)
        if name == 'serverip':
            ConfigHandler.serverIp = self.theContent.encode("UTF-8")
        if name == 'serverport':
            ConfigHandler.serverPort = int(self.theContent.encode("UTF-8"))
        if name == 'startup':
            ConfigHandler.plugins[self.plugin_id]["startup"] = \
                self.theContent.encode("UTF-8")
        if name == 'shutdown':
            ConfigHandler.plugins[self.plugin_id]["shutdown"] = \
                self.theContent.encode("UTF-8")
        if name == 'source':
            ConfigHandler.plugins[self.plugin_id]["source"] = \
                self.theContent.encode("UTF-8")
        if name == 'location':
            ConfigHandler.plugins[self.plugin_id]["location"] = \
                self.theContent.encode("UTF-8")
        if name == 'interface':
            ConfigHandler.plugins[self.plugin_id]["interface"] = \
                self.theContent.encode("UTF-8")
        if name == 'sensor':
            ConfigHandler.plugins[self.plugin_id]["sensor"] = \
                self.theContent.encode("UTF-8")
        if name == 'frequency':
            ConfigHandler.plugins[self.plugin_id]["frequency"] = \
                self.theContent.encode("UTF-8")
 
    def characters (self, string):
        
        # why sax parser break lines when an entity appears?
        # is it a python bug?
        if string == '&':
            self.theContent += string
        
        elif self.inContent:
            self.theContent = string


