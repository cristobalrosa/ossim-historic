import threading, time

from Logger import Logger
logger = Logger.logger
from Output import Output
import Config
import Event

class Detector(threading.Thread):

    def __init__(self, conf, plugin):

        self._conf = conf
        self._plugin = plugin
        self.os_hash = {}
        logger.info("Starting detector %s (%s).." % \
                    (self._plugin.get("config", "name"),
                     self._plugin.get("config", "plugin_id")))
        threading.Thread.__init__(self)

    def _event_os_cached(self, event):

        if isinstance(event, Event.EventOS):
            import string
            current_os = string.join(string.split(event["os"]), ' ')
            previous_os = self.os_hash.get(event["host"], '')
            if current_os == previous_os:
                return True
            else:
                # Fallthrough and add to cache
                self.os_hash[event["host"]] = \
                    string.join(string.split(event["os"]), ' ')

        return False

    def _exclude_event(self, event):
        
        if self._plugin.has_option("config", "exclude_sids"):
            exclude_sids = self._plugin.get("config", "exclude_sids")
            if event["plugin_sid"] in Config.split_sids(exclude_sids):
                logger.debug("Excluding event with " +\
                    "plugin_id=%s and plugin_sid=%s" %\
                    (event["plugin_id"], event["plugin_sid"]))
                return True

        return False

    def _thresholding(self):
        #
        # This section should contain:
        # - Absolute thresholding by plugin, src, etc...
        # - Time based thresholding
        # - Consolidation
        #
        pass

    def send_message(self, event):

        if self._event_os_cached(event):
            return

        if self._exclude_event(event):
            return

        self._thresholding()

        # get default values from config
        #
        if self._conf.has_section("plugin-defaults"):

        # 1) date
            default_date_format = self._conf.get("plugin-defaults",
                                                 "date_format")
            if event["date"] is None and default_date_format and \
               'date' in event.EVENT_ATTRS:
                event["date"] = time.strftime(default_date_format, 
                                              time.localtime(time.time()))

        # 2) sensor
            default_sensor = self._conf.get("plugin-defaults", "sensor")
            if event["sensor"] is None and default_sensor and \
               'sensor' in event.EVENT_ATTRS:
                event["sensor"] = default_sensor

        # 3) interface
            default_iface = self._conf.get("plugin-defaults", "interface")
            if event["interface"] is None and default_iface and \
               'interface' in event.EVENT_ATTRS:
                event["interface"] = default_iface

        # 4) source ip
            if event["src_ip"] is None and 'src_ip' in event.EVENT_ATTRS:
                event["src_ip"] = event["sensor"]

        # the type of this event should always be 'detector'
        if event["type"] is None and 'type' in event.EVENT_ATTRS:
            event["type"] = 'detector'

        Output.event(event)

    # must be overriden in child classes
    def process(self):
        pass

    def run(self):
        self.process()


class ParserSocket(Detector):

    def process(self):
        self.process()


class ParserDatabase(Detector):

    def process(self):
        self.process()

# vim:ts=4 sts=4 tw=79 expandtab:
