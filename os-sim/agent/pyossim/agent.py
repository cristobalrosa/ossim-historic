#!/usr/bin/python2.3

import util, time, os, signal, sys, string, errno
from optparse import OptionParser

from Agent import Agent
from __init__ import VERSION

def main():

    # parse command line options
    options = parse_options()

    # config file
    if options.config_file is None:
        options.config_file = util.CONFIG

    if not os.path.exists(options.config_file):
        util.debug (__name__, "\n" +\
                "   Unable to locate configuration file at default \n" +\
                "   location (/etc/ossim/agent/config.xml)\n" +\
                "   Specify an alternate configuration file with -c option",
                "**", "RED")
        sys.exit()

    # Check if there is already a running instance
    if options.force is None:
        check_pid()

    # Install a handler for the terminate and hangup signals
    signal.signal(signal.SIGTERM, terminate)
    if os.name != "nt":
        signal.signal(signal.SIGHUP, hangup)

    # Init agent and read config
    agent = Agent()
    agent.parseConfig(options.config_file)

    # connect to server
    if agent.connect() is not None:
        
        # Redirect standard file descriptors
        if not os.path.isdir(agent.logdir):
            os.mkdir(agent.logdir, 0755)
        if os.name != "nt":
            sys.stdin  = open('/dev/null', 'r')
        sys.stdout = open(os.path.join(agent.logdir, 'agent.log'), 'w')
        util.watchlog = \
            open(os.path.join(agent.logdir, 'agent_watch.log'), 'w')
        util.debug_watchlog('Agent startup')

        # daemonize
        if options.daemon is not None:
            daemonize()
            sys.stderr = open(os.path.join(agent.logdir, 'agent_error.log'), 'a+')

        # check services
        agent.watchdog()
        
        # monitors (watch-rules)
        agent.server()
        agent.scheduler()

        # detectors
        agent.append_plugins()
        agent.parser()

    else:
        os.remove(os.path.join(util.RUN_DIR, 'ossim_agent.pid'))
        sys.exit(1)


# Check if there is already a running instance
def check_pid():

    if os.path.isfile(os.path.join(util.RUN_DIR, 'ossim_agent.pid')):
        f = open(os.path.join(util.RUN_DIR, 'ossim_agent.pid'), 'r')
        pid = string.atoi(string.strip(f.readline()))
        f.close()
        util.debug (__name__, 'There is already a running instance ' + \
                    '(%s)' % (os.path.join(util.RUN_DIR, 'ossim_agent.pid')), 
                    '**', 'RED')
        sys.exit(1)
    else:
        # Write our process id into the pid file
        f = open(os.path.join(util.RUN_DIR, 'ossim_agent.pid'), 'w')
        f.write("%d" % os.getpid())
        f.close()


def parse_options():
    """Parse command line options"""

    parser = OptionParser(
        usage = "%prog [-v] [-q] [-d] [-f] [-c config_file]", 
        version = "OSSIM (Open Source Security Information Management) " + \
                  "- Agent " + VERSION)

    parser.add_option("-v", "--verbose", dest="verbose", action="store_true",
                      help="make lots of noise")
    parser.add_option("-q", "--quiet", dest="quiet", action="store_true",
                      help="don't show debug messages [default]")
    parser.add_option("-d", "--daemon", dest="daemon", action="store_true",
                      help="Run agent in daemon mode")
    parser.add_option("-f", "--force", dest="force", action="store_true",
                      help = "Force startup overriding pidfile")
    parser.add_option("-c", "--config", dest="config_file", action="store",
                      help = "read config from FILE", metavar="FILE")
    (options, args) = parser.parse_args()

    if len(args) > 1:
        parser.error("incorrect number of arguments")

    if options.verbose and options.daemon:
        parser.error("incompatible options -v -d")

    if options.quiet:
        util.VERBOSE = False
    elif options.verbose:
        util.VERBOSE = True
    else:
        util.VERBOSE = False

    return options


def daemonize():
    """Run agent in daemon mode"""

    try:
        util.debug (__name__, 'Forking into background...', '**', 'GREEN')
        pid = os.fork()
        if pid > 0:
            sys.exit(0)
    except OSError, e:
        print >>sys.stderr, "fork failed: %d (%s)" % (e.errno, e.strerror)
        sys.exit(1)


def hangup(sig, params):
    pass


def terminate(sig, params):

    try:
        # Remove the pid file
        os.remove(os.path.join(util.RUN_DIR, 'ossim_agent.pid'))
    except:
        # Ignore any errors
        pass

    pid = os.getpid()
    os.kill(pid, signal.SIGKILL)


def waitforever():
    """Wait for a Control-C and kill all threads"""

    while 1:
        try:
            time.sleep(1)
        except KeyboardInterrupt:
            pid = os.getpid()
            os.kill(pid, signal.SIGTERM)


if __name__ == '__main__':
    main()
    waitforever()

