/*
License:

   Copyright (c) 2003-2006 ossim.net
   Copyright (c) 2007-2009 AlienVault
   All rights reserved.

   This package is free software; you can redistribute it and/or modify
   it under the terms of the GNU General Public License as published by
   the Free Software Foundation; version 2 dated June, 1991.
   You may not use, modify or distribute this program under any other version
   of the GNU General Public License.

   This package is distributed in the hope that it will be useful,
   but WITHOUT ANY WARRANTY; without even the implied warranty of
   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
   GNU General Public License for more details.

   You should have received a copy of the GNU General Public License
   along with this package; if not, write to the Free Software
   Foundation, Inc., 51 Franklin St, Fifth Floor, Boston,
   MA  02110-1301  USA


On Debian GNU/Linux systems, the complete text of the GNU General
Public License can be found in `/usr/share/common-licenses/GPL-2'.

Otherwise you can read it here: http://www.gnu.org/licenses/gpl-2.0.txt
*/

#include <glib.h>
#include <gnet.h>
#include "sim-util.h"
#include "os-sim.h"
#include "sim-config.h"
#include <config.h>
#include "sim-connect.h"
#include <signal.h>

extern SimMain ossim;

//static gpointer  sim_connect_send_alarm      (gpointer data);
static gboolean sigpipe_received = FALSE;

static SimConfig *config=NULL;

// Actually not used
void
pipe_handler(int signum)
{
  sigpipe_received = TRUE;
  g_log (G_LOG_DOMAIN, G_LOG_LEVEL_DEBUG, "sim_connect_send_alarm: Broken Pipe (connection with framework broken). Reseting socket");
  sim_connect_send_alarm(NULL);
}


gpointer
sim_connect_send_alarm(gpointer data)
{
  if(!config)
    if(data)
      config=(SimConfig*)data;

  SimEvent* event=NULL;
  GTcpSocket* socket = NULL;
  GIOChannel* iochannel = NULL;
  GIOError error;

  gchar *buffer = NULL;
  gchar *aux = NULL;

  gsize n;
  GList  *notifies = NULL;

  gint  risk;

  gchar *ip_src = NULL;
  gchar *ip_dst = NULL;

  gchar time[TIMEBUF_SIZE];
  gchar *timestamp;
  timestamp=time;


  gchar *hostname;
  gint port;

  GInetAddr* addr = NULL;
  hostname = g_strdup(config->framework.host);
  port = config->framework.port;
  gint iter=0;

  void* old_action; 


  for(;;) //Pop events for ever
  {
  event=(SimEvent*)sim_container_pop_ar_event(ossim.container);
  // Send max risk
  // i.e., to avoid risk=0 when destination is 0.0.0.0
  if (event->risk_a > event->risk_c)
    risk = event->risk_a;
  else
    risk = event->risk_c;


/* String to be sent */
    if(event->time)
	    strftime (timestamp, TIMEBUF_SIZE, "%Y-%m-%d %H:%M:%S", localtime ((time_t *) &event->time));


  ip_src = gnet_inetaddr_get_canonical_name (event->src_ia);
  ip_dst = gnet_inetaddr_get_canonical_name (event->dst_ia);

  //FIXME? In a future, Policy will substitute this and this won't be neccesary. Also is needed to check
  //if this funcionality is really interesting
  //
  aux = g_strdup_printf("event date=\"%s\" plugin_id=\"%d\" plugin_sid=\"%d\" risk=\"%d\" priority=\"%d\" reliability=\"%d\" event_id=\"%d\" backlog_id=\"%d\" src_ip=\"%s\" src_port=\"%d\" dst_ip=\"%s\" dst_port=\"%d\" protocol=\"%d\" sensor=\"%s\"", timestamp, event->plugin_id, event->plugin_sid, risk, event->priority, event->reliability, event->id, event->backlog_id, ip_src, event->src_port, ip_dst, event->dst_port, event->protocol, event->sensor);

  g_free (ip_src);
  g_free (ip_dst);

  buffer = g_strconcat (aux,
    event->filename  ? " filename=\"" : "", event->filename  ? event->filename  : "", event->filename ? "\"" : "",
    event->username  ? " username=\"" : "", event->username  ? event->username  : "", event->username ? "\"" : "",
    event->password  ? " password=\"" : "", event->password  ? event->password  : "", event->password ? "\"" : "",
    event->userdata1 ? " userdata1=\"" : "",event->userdata1 ? event->userdata1 : "",event->userdata1 ? "\"" : "",
    event->userdata2 ? " userdata2=\"" : "",event->userdata2 ? event->userdata2 : "",event->userdata2 ? "\"" : "",
    event->userdata3 ? " userdata3=\"" : "",event->userdata3 ? event->userdata3 : "",event->userdata3 ? "\"" : "",
    event->userdata4 ? " userdata4=\"" : "",event->userdata4 ? event->userdata4 : "",event->userdata4 ? "\"" : "",
    event->userdata5 ? " userdata5=\"" : "",event->userdata5 ? event->userdata5 : "",event->userdata5 ? "\"" : "",
    event->userdata6 ? " userdata6=\"" : "",event->userdata6 ? event->userdata6 : "",event->userdata6 ? "\"" : "",
    event->userdata7 ? " userdata7=\"" : "",event->userdata7 ? event->userdata7 : "",event->userdata7 ? "\"" : "",
    event->userdata8 ? " userdata8=\"" : "",event->userdata8 ? event->userdata8 : "",event->userdata8 ? "\"" : "",
    event->userdata9 ? " userdata9=\"" : "",event->userdata9 ? event->userdata9 : "",event->userdata9 ? "\"" : "",
    "\n", NULL);

  if (!buffer)
  {
    g_log (G_LOG_DOMAIN, G_LOG_LEVEL_DEBUG, "sim_connect_send_alarm: message error");
    g_free (aux);
    continue;  
  }

  //old way was creating a new socket and giochannel for each alarm.
  //now a persistent giochannel is used.
  //iochannel = gnet_tcp_socket_get_io_channel (socket);


  if(!iochannel||sigpipe_received)
  //Loop to get a connection
  do{
		if(sigpipe_received)
		{
	    		if(socket)
				  gnet_tcp_socket_delete (socket);
			sigpipe_received=FALSE;
			iochannel=FALSE;
		}

	// if not, create socket and iochannel from config and store to get a persistent connection.
		g_log (G_LOG_DOMAIN, G_LOG_LEVEL_DEBUG, "sim_connect_send_alarm: invalid iochannel.(%d)",iter);
		g_log (G_LOG_DOMAIN, G_LOG_LEVEL_DEBUG, "sim_connect_send_alarm: trying to create a new iochannel.(%d)",iter);
		if (!hostname)
		{
			//FIXME: may be that this host hasn't got any frameworkd. If the event is forwarded to other server, it will be sended to the
			//other server framework (supposed it has a defined one).
			g_log (G_LOG_DOMAIN, G_LOG_LEVEL_DEBUG, "sim_connect_send_alarm: Hostname error, reconnecting in 3secs (%d)",iter);
			hostname = g_strdup(config->framework.host);
	    		sleep(3);
			continue;
		}
		if(addr)
			g_free(addr);

		addr = gnet_inetaddr_new_nonblock (hostname, port);
		if (!addr)
		{
			g_log (G_LOG_DOMAIN, G_LOG_LEVEL_DEBUG, "sim_connect_send_alarm: Error creating the address, trying in 3secs(%d)",iter);
	    		sleep(3);
			continue;
	  	}

  		socket = gnet_tcp_socket_new (addr);
	  	if (!socket)
		{
			g_log (G_LOG_DOMAIN, G_LOG_LEVEL_DEBUG, "sim_connect_send_alarm: Error creating socket(1), reconnecting in 3 secs..(%d)",iter);
			iochannel=NULL;
			socket=NULL;
			sleep(3);
			continue;
		}
		else
		{
	  		iochannel = gnet_tcp_socket_get_io_channel (socket);
		  	if (!iochannel)
			{
			  g_log (G_LOG_DOMAIN, G_LOG_LEVEL_DEBUG, "sim_connect_send_alarm: Error creating iochannel, reconnecting in 3 secs..(%d)",iter);
    			  if(socket)
			  gnet_tcp_socket_delete (socket);
			  socket=NULL;
			  iochannel=NULL;
			  sleep(3);
			  continue;
			}
			else
			{
				sigpipe_received=FALSE;
				g_log (G_LOG_DOMAIN, G_LOG_LEVEL_DEBUG, "sim_connect_send_alarm: new iochannel created. Returning %x (%d)", iochannel,iter);
			}
		}
	  
  	iter++;
  } while(!iochannel);

  //g_assert (iochannel != NULL);

  n = strlen(buffer);
  g_log (G_LOG_DOMAIN, G_LOG_LEVEL_DEBUG, "sim_connect_send_alarm: Message to send: %s, (len=%d)", buffer,n); 

//signals actually not used
//  old_action=signal(SIGPIPE, pipe_handler);

  error = gnet_io_channel_writen (iochannel, buffer, n, &n);


  //error = gnet_io_channel_readn (iochannel, buffer, n, &n);
  //fwrite(buffer, n, 1, stdout);

  if (error != G_IO_ERROR_NONE)
  { 
    //back to the queue so we dont loose the action/response
    sim_container_push_ar_event (ossim.container, event);
    g_log (G_LOG_DOMAIN, G_LOG_LEVEL_DEBUG, "sim_connect_send_alarm: message could not be sent.. reseting"); 
    if(buffer)
	    g_free (buffer);

    g_free (aux);
    gnet_tcp_socket_delete (socket);
    iochannel=NULL;
  }else
    g_log(G_LOG_DOMAIN, G_LOG_LEVEL_DEBUG, "sim_connect_send_alarm: message sent succesfully: %s", buffer);

  //Cose conn

  g_free (buffer);
  g_free (aux);

  buffer=NULL;
  aux=NULL;
    gnet_tcp_socket_delete (socket);
    iochannel=NULL;

 	if(event)
	    g_object_unref (event);
  }
  
}

// vim: set tabstop=2:

