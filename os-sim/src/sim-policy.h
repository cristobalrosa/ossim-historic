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

#ifndef __SIM_POLICY_H__
#define __SIM_POLICY_H__ 1

#include <glib.h>
#include <glib-object.h>
#include <gnet.h>
#include <libgda/libgda.h>

#include "sim-enums.h"
#include "sim-util.h"
#include "sim-inet.h"
#include "sim-event.h"	//SimRole

#ifdef __cplusplus
extern "C" {
#endif /* __cplusplus */

#define SIM_TYPE_POLICY                  (sim_policy_get_type ())
#define SIM_POLICY(obj)                  (G_TYPE_CHECK_INSTANCE_CAST (obj, SIM_TYPE_POLICY, SimPolicy))
#define SIM_POLICY_CLASS(klass)          (G_TYPE_CHECK_CLASS_CAST (klass, SIM_TYPE_POLICY, SimPolicyClass))
#define SIM_IS_POLICY(obj)               (G_TYPE_CHECK_INSTANCE_TYPE (obj, SIM_TYPE_POLICY))
#define SIM_IS_POLICY_CLASS(klass)       (G_TYPE_CHECK_CLASS_TYPE ((klass), SIM_TYPE_POLICY))
#define SIM_POLICY_GET_CLASS(obj)        (G_TYPE_INSTANCE_GET_CLASS ((obj), SIM_TYPE_POLICY, SimPolicyClass))

G_BEGIN_DECLS

//SimPolicy is each one of the "lines" in the policy. It has one or more sources, one or more destinations, a time range, and so on.
typedef struct _SimPolicy        SimPolicy;
typedef struct _SimPolicyClass   SimPolicyClass;
typedef struct _SimPolicyPrivate SimPolicyPrivate;

struct _SimPolicy {
  GObject parent;

  SimPolicyPrivate *_priv;
};

struct _SimPolicyClass {
  GObjectClass parent_class;
};


GType             sim_policy_get_type                        (void);

SimPolicy*        sim_policy_new                             (void);
SimPolicy*        sim_policy_new_from_dm                     (GdaDataModel     *dm,
							      gint              row);

gint              sim_policy_get_id                          (SimPolicy        *policy);
void              sim_policy_set_id                          (SimPolicy        *policy,
							      gint              id);

gint              sim_policy_get_priority                    (SimPolicy        *policy);
void              sim_policy_set_priority                    (SimPolicy        *policy,
							      gint              priority);

gint              sim_policy_get_begin_day                   (SimPolicy        *policy);
void              sim_policy_set_begin_day                   (SimPolicy        *policy,
							      gint              begin_day);

gint              sim_policy_get_end_day                     (SimPolicy        *policy);
void              sim_policy_set_end_day                     (SimPolicy        *policy,
							      gint              end_day);

gint              sim_policy_get_begin_hour                  (SimPolicy        *policy);
void              sim_policy_set_begin_hour                  (SimPolicy        *policy,
							      gint              begin_hour);

gint              sim_policy_get_end_hour                    (SimPolicy        *policy);
void              sim_policy_set_end_hour                    (SimPolicy        *policy,
							      gint              end_hour);

gboolean          sim_policy_get_store                       (SimPolicy        *policy);
void              sim_policy_set_store                       (SimPolicy        *policy, gboolean store);

/* Sources Inet Address */
void              sim_policy_append_src                   (SimPolicy        *policy,
																		 								       SimInet	        *src);
void              sim_policy_remove_src                   (SimPolicy        *policy,
																											     SimInet  	      *src);
GList*            sim_policy_get_src    	                (SimPolicy        *policy);
void              sim_policy_free_src 		                (SimPolicy        *policy);

/* Destination Inet Address */
void              sim_policy_append_dst                   (SimPolicy        *policy,
																										       SimInet  	      *dst);
void              sim_policy_remove_dst                   (SimPolicy        *policy,
																										       SimInet	        *dst);
GList*            sim_policy_get_dst                     	(SimPolicy        *policy);
void              sim_policy_free_dst                    	(SimPolicy        *policy);

/* Ports */
void              sim_policy_append_port                     (SimPolicy        *policy,
							      SimPortProtocol  *pp);
void              sim_policy_remove_port                     (SimPolicy        *policy,
							      SimPortProtocol  *pp);
GList*            sim_policy_get_ports                       (SimPolicy        *policy);
void              sim_policy_free_ports                      (SimPolicy        *policy);

/* Sensors */
GList*            sim_policy_get_sensors                     (SimPolicy        *policy);
void              sim_policy_free_sensors                    (SimPolicy        *policy);

gboolean          sim_policy_match                           (SimPolicy        *policy,
																												      gint              date,
																												      GInetAddr        *src_ia,
																												      GInetAddr        *dst_ia,
																												      SimPortProtocol  *pp,
																															gchar							*sensor,
																															guint							plugin_id,
																															guint							plugin_sid);

/* Plugin_ids */
void              sim_policy_append_plugin_id							     (SimPolicy        *policy,
																													     guint            *plugin_id);
void              sim_policy_remove_plugin_id								   (SimPolicy        *policy,
																													     guint            *plugin_id);
GList*            sim_policy_get_plugin_ids										 (SimPolicy        *policy);
void              sim_policy_free_plugin_ids		               (SimPolicy        *policy);

/* Plugin_sids */
void              sim_policy_append_plugin_sid							   (SimPolicy        *policy,
																													     guint            *plugin_sid);
void              sim_policy_remove_plugin_sid                 (SimPolicy        *policy,
																													     guint            *plugin_sid);
GList*            sim_policy_get_plugin_sids			             (SimPolicy        *policy);
void              sim_policy_free_plugin_sids				           (SimPolicy        *policy);


/* Plugin groups */
void              sim_policy_append_plugin_group	             (SimPolicy        *policy,
																														    Plugin_PluginSid	*plugin_group);
void              sim_policy_remove_plugin_group               (SimPolicy        *policy,
																														    Plugin_PluginSid	*plugin_group);
GList*            sim_policy_get_plugin_groups                 (SimPolicy        *policy);
void              sim_policy_free_plugin_groups                (SimPolicy        *policy);


void							sim_policy_debug_print_policy									(SimPolicy				*policy);

SimRole*					sim_policy_get_role														(SimPolicy					*policy);
void		          sim_policy_set_role                           (SimPolicy          *policy,
																																	SimRole						*role);


G_END_DECLS

#ifdef __cplusplus
}
#endif /* __cplusplus */

#endif /* __SIM_POLICY_H__ */
// vim: set tabstop=2:
