/* Copyright (c) 2003 ossim.net
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions
 * are met:
 *
 * 1. Redistributions of source code must retain the above copyright
 *    notice, this list of conditions and the following disclaimer.
 *
 * 2. Redistributions in binary form must reproduce the above copyright
 *    notice, this list of conditions and the following disclaimer in the
 *    documentation and/or other materials provided with the distribution.
 *
 * 3. The name of the author may not be used to endorse or promote products
 *    derived from this software without specific prior written permission
 *    from the author.
 *
 * 4. Products derived from this software may not be called "Os-sim" nor
 *    may "Os-sim" appear in their names without specific prior written
 *    permission from the author.
 *
 * THIS SOFTWARE IS PROVIDED ``AS IS'' AND ANY EXPRESS OR IMPLIED WARRANTIES,
 * INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY
 * AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED.  IN NO EVENT SHALL
 * THE AUTHOR BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL,
 * EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO,
 * PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS;
 * OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY,
 * WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR
 * OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF
 * ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 */


#define _GNU_SOURCE

#include <getopt.h>

#include <sys/types.h>
#include <sys/stat.h>
#include <fcntl.h>
#include <unistd.h>
#include <string.h>
#include <signal.h>

#include <libgda/libgda.h>
#include <os-sim.h>

#include "sim-scheduler.h"
#include "sim-organizer.h"
#include "sim-session.h"
#include "sim-server.h"
#include "sim-xml-config.h"
#include "sim-log.h"

#include <config.h>

/* Global Variables */
SimMain        ossim;
GPrivate       *currentgscanner;

void sim_terminate(int mode)
{
  unlink(OS_SIM_RUN_DIR);
  
  if (mode == 1)
    abort(); //core file rulez
	else
	if (mode == 0)
		exit(EXIT_SUCCESS);
}

void on_signal(int signum)
{
  switch(signum)
  {
    case SIGHUP: //FIXME: reload directives, policy, and so on.
        break;
    case SIGFPE:
    case SIGILL:
    case SIGSEGV:
    case SIGABRT: 
    case SIGQUIT:
        sim_terminate(1);
        break;
    case SIGTERM:
    case SIGINT:
        sim_terminate(0);
        break;
    case SIGBUS:
      break;
  }

}

//System signal handlers
void init_signals(void)
{
  signal (SIGINT, on_signal);
  signal (SIGHUP, on_signal);
  signal (SIGQUIT, on_signal);
  signal (SIGABRT, on_signal);
  signal (SIGILL, on_signal);
  signal (SIGBUS, on_signal);
  signal (SIGFPE, on_signal);
  signal (SIGSEGV, on_signal);
  signal (SIGTERM, on_signal);

}

static gpointer
sim_thread_scheduler (gpointer data)
{
  g_message ("sim_thread_scheduler");
	
	SimScheduler * scheduler = (SimScheduler *) data;
  sim_scheduler_run (scheduler);

  return NULL;
}


static gpointer
sim_thread_organizer (gpointer data)
{
  g_message ("sim_thread_organizer");

	SimOrganizer *organizer = (SimOrganizer *) data;
  sim_organizer_run (ossim.organizer);

  return NULL;
}


static gpointer
sim_thread_server (gpointer data)
{
  g_message ("sim_thread_server");

	SimServer *server = (SimServer *) data;	
  sim_server_listen_run (server);

  return NULL;
}

static gpointer
sim_thread_HA_server (gpointer data)
{
  g_message ("sim_thread_HA_server");

  ossim.HA_server = sim_server_HA_new (ossim.config);
  sim_server_listen_run (ossim.HA_server);

  return NULL;
}


/*
 *
 *
 *
 */
static void
options (int argc, char **argv)
{
  int c;
  int digit_optind = 0;

  /* Default Command Line Options */
  simCmdArgs.config = NULL;
  simCmdArgs.daemon = FALSE;
  simCmdArgs.debug = 4;
  simCmdArgs.ip = NULL;
  simCmdArgs.port = 0;

  while (TRUE)
  {
    int this_option_optind = optind ? optind : 1;
    int option_index = 0;
    static struct option options[] =
		{
		  {"config", 1, 0, 'c'}, //name, has_arg, flag, letter
		  {"daemon", 0, 0, 'd'},
		  {"debug", 0, 0, 'D'},
		  {"interfaceip", 1, 0, 'i'},	
		  {"port", 1, 0, 'p'},	
			{0, 0, 0, 0}
		};
      
		c = getopt_long (argc, argv, "dc:D:i:p:", options, &option_index);

		if (c == -1)
			break;

	  switch (c)
		{
			case 'c':
				simCmdArgs.config = g_strdup (optarg);
				break;

			case 'd':
			  simCmdArgs.daemon = TRUE;
				break;
	  
			case 'D':
				if (sim_string_is_number (optarg, 0))									
					simCmdArgs.debug = strtol (optarg, (char **)NULL, 10);
				break;
	
			case 'i':
	      simCmdArgs.ip = g_strdup (optarg);

			case 'p':
				if (sim_string_is_number (optarg, 0))
					simCmdArgs.port = strtol (optarg, (char **)NULL, 10);				
					 
			case '?':
				break;
	  
			default:
			  g_print ("?\? getopt() return the caracter %c ?\?\n", c);
		}
	}

  if (optind < argc)
  {
    g_print ("Elements from ARGV are not option: ");
    while (optind < argc)
			g_print ("%s ", argv[optind++]);
	  g_print ("\n");
  }

  if ((simCmdArgs.config) && !g_file_test (simCmdArgs.config, G_FILE_TEST_EXISTS))
    g_error ("Config XML File %s: Don't exists", simCmdArgs.config);
  
  if ((simCmdArgs.debug < 0) || (simCmdArgs.debug > 6))
    g_error ("Debug level %d: Is invalid", simCmdArgs.debug);

  if (simCmdArgs.daemon) 
  {
    if (fork ())
			exit (0);
    else
			;
  }
}

/*
 *
 * Saves the pid in a hardcoded (brr) place
 *
 */

void
sim_pid_init(void)
{
  int fd_pid;
  if ((fd_pid = open (OS_SIM_RUN_DIR, O_WRONLY|O_CREAT|O_TRUNC, S_IRUSR|S_IWUSR)) < 0)
      g_message ("Can't create %s",OS_SIM_RUN_DIR);
  else
  {
    char pid_str[16];
    if (lockf(fd_pid,F_TLOCK,0) < 0 )
      g_message ("Can't lock pid file; may be that another server process is running?");
    else
    {
      sprintf (pid_str,"%d\n", getpid());
      write (fd_pid, pid_str, strlen(pid_str));
      close(fd_pid);
    }
  }

}

/*
 *
 *
 *
 */
int
main (int argc, char *argv[])
{
  SimXmlConfig	*xmlconfig;
  GMainLoop	*loop;
  GThread	*thread;
  SimConfigDS	*ds;
  

  /* Global variable OSSIM Init */
  ossim.config = NULL;
  ossim.container = NULL;
  ossim.server = NULL;
  ossim.dbossim = NULL;
  ossim.dbsnort = NULL;

  /* Command Line Options */
  options (argc, argv);

  /* Thread Init */
  if (!g_thread_supported ())
    g_thread_init (NULL);

	// Init thread vars to avoid concurrency in GScanner
	sim_command_init_tls();	

  /*GNET Init */
  gnet_init();
  gnet_ipv6_set_policy (GIPV6_POLICY_IPV4_ONLY);
  
  /* GDA Init */
  gda_init ("OSSIM", "0.9.9rc4", argc, argv);

  /* Catch system signals */
  init_signals();

  /* Config Init */
  if (simCmdArgs.config)
  {
    if (!(xmlconfig = sim_xml_config_new_from_file (simCmdArgs.config)))
			g_print ("Config XML File %s is invalid\n", simCmdArgs.config);
    
    if (!(ossim.config = sim_xml_config_get_config (xmlconfig)))
			g_print ("Config is %s invalid\n", simCmdArgs.config);
  }
  else
  {
    if (!g_file_test (OS_SIM_GLOBAL_CONFIG_FILE, G_FILE_TEST_EXISTS))
			g_print ("Config XML File %s: Not Exists\n", OS_SIM_GLOBAL_CONFIG_FILE);
      
    if (!(xmlconfig = sim_xml_config_new_from_file (OS_SIM_GLOBAL_CONFIG_FILE)))
			g_print ("Config XML File %s is invalid\n", OS_SIM_GLOBAL_CONFIG_FILE);
      
    if (!(ossim.config = sim_xml_config_get_config (xmlconfig)))
			g_print ("Config %s is invalid\n", OS_SIM_GLOBAL_CONFIG_FILE);
  }

	/* Log Init */
  sim_log_init ();
  g_log (G_LOG_DOMAIN, G_LOG_LEVEL_DEBUG, "Starting OSSIM server debug with process id: %d",getpid());

  /* Database Options */
  ds = sim_config_get_ds_by_name (ossim.config, SIM_DS_OSSIM);
  if (!ds)
	{
		g_log (G_LOG_DOMAIN, G_LOG_LEVEL_DEBUG, "Failed to load OSSIM DB XML Config");
    g_print ("Failed to load OSSIM DB XML Config\n");
	}
  ossim.dbossim = sim_database_new (ds);

  ds = sim_config_get_ds_by_name (ossim.config, SIM_DS_SNORT);
  if (!ds)
	{
    g_print ("Failed to load SNORT DB XML Config\n");
    g_log (G_LOG_DOMAIN, G_LOG_LEVEL_DEBUG, "Failed to load SNORT DB XML Config");
	}
	ossim.dbsnort = sim_database_new (ds);

	ds = sim_config_get_ds_by_name (ossim.config, SIM_DS_OSVDB);
  if (!ds)
	{
    g_print ("Error / Warning: OSVDB DB XML Config. If you want to use OSVDB please insert data load into config.xml. If you think that you don't need to use it, or you're running a server without DB in multiserver mode, just ignore this error.\n");
    g_log (G_LOG_DOMAIN, G_LOG_LEVEL_DEBUG, "Error / Warning: OSVDB DB XML Config. If you want to use OSVDB please insert data load into config.xml. If you think that you don't need to use it, or you're running a server without DB in multiserver mode, just ignore this error.");
    ossim.dbosvdb = NULL;
	}
  else
	  ossim.dbosvdb = sim_database_new (ds);


  /* pid init */
  sim_pid_init();

  ossim.mutex_directives = g_mutex_new ();
  ossim.mutex_backlogs = g_mutex_new ();
  
  /* Create the main loop before any socket is open. It seems that this fixes some errors.*/
  loop = g_main_loop_new (NULL, FALSE);

/*
GList *list3 = ossim.config->rservers;
 while (list3)
  {
        SimConfigRServer *rserver = (SimConfigRServer*) list3->data;
       sim_config_rserver_debug_print (rserver);
			 list3 = list3->next;
}
*/

  /* Initializes the listening keywords scanner*/
//  sim_command_start_scanner();

	/* Init instances */
  ossim.server = sim_server_new (ossim.config);				//needed to be defined for container remote load.
  ossim.container = sim_container_new (ossim.config);	//Load all the data needed from DB (or from DB in a remote server).
  ossim.scheduler = sim_scheduler_new (ossim.config);
  ossim.organizer = sim_organizer_new (ossim.config);

	/* Server Thread */
  thread = g_thread_create (sim_thread_server, ossim.server, FALSE, NULL);
  g_return_if_fail (thread);
  g_thread_set_priority (thread, G_THREAD_PRIORITY_NORMAL);

	//After DB data loading, we can continue with some config data from DB (or from remote primary master server)
	sim_config_load_database_config (ossim.config, ossim.dbossim);

	/* Server HA Thread: Manage conns to the other HA server*/ 
/*  thread = g_thread_create (sim_thread_HA_server, NULL, FALSE, NULL);
  g_return_if_fail (thread);
  g_thread_set_priority (thread, G_THREAD_PRIORITY_NORMAL);
*/
  /* Scheduler Thread */
  thread = g_thread_create (sim_thread_scheduler, ossim.scheduler, FALSE, NULL);
  g_return_if_fail (thread);
  g_thread_set_priority (thread, G_THREAD_PRIORITY_NORMAL);

  /* Organizer Thread */
  thread = g_thread_create (sim_thread_organizer, ossim.organizer, FALSE, NULL);
  g_return_if_fail (thread);
  g_thread_set_priority (thread, G_THREAD_PRIORITY_NORMAL);

	/* Main Loop */
  g_main_loop_run (loop);

	/* Log Free */
  sim_log_free ();
 
  exit (EXIT_SUCCESS);
  return 0;
}

// vim: set tabstop=2:

