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

#include <config.h>

#include "sim-plugin-sid.h"

enum
{
  DESTROY,
  LAST_SIGNAL
};

struct _SimPluginSidPrivate {
  gint     plugin_id;
  gint     sid;
  gint     category_id;
  gint     class_id;
  gchar   *name;
};

static gpointer parent_class = NULL;
static gint sim_server_signals[LAST_SIGNAL] = { 0 };

/* GType Functions */

static void 
sim_plugin_sid_impl_dispose (GObject  *gobject)
{
  G_OBJECT_CLASS (parent_class)->dispose (gobject);
}

static void 
sim_plugin_sid_impl_finalize (GObject  *gobject)
{
  SimPluginSid *plugin = SIM_PLUGIN_SID (gobject);

  if (plugin->_priv->name)
    g_free (plugin->_priv->name);

  g_free (plugin->_priv);

  G_OBJECT_CLASS (parent_class)->finalize (gobject);
}

static void
sim_plugin_sid_class_init (SimPluginSidClass * class)
{
  GObjectClass *object_class = G_OBJECT_CLASS (class);

  parent_class = g_type_class_ref (G_TYPE_OBJECT);

  object_class->dispose = sim_plugin_sid_impl_dispose;
  object_class->finalize = sim_plugin_sid_impl_finalize;
}

static void
sim_plugin_sid_instance_init (SimPluginSid *plugin)
{
  plugin->_priv = g_new0 (SimPluginSidPrivate, 1);

  plugin->_priv->plugin_id = 0;
  plugin->_priv->sid = 0;
  plugin->_priv->category_id = 0;
  plugin->_priv->class_id = 0;
  plugin->_priv->name = NULL;
}

/* Public Methods */

GType
sim_plugin_sid_get_type (void)
{
  static GType object_type = 0;
 
  if (!object_type)
  {
    static const GTypeInfo type_info = {
              sizeof (SimPluginSidClass),
              NULL,
              NULL,
              (GClassInitFunc) sim_plugin_sid_class_init,
              NULL,
              NULL,                       /* class data */
              sizeof (SimPluginSid),
              0,                          /* number of pre-allocs */
              (GInstanceInitFunc) sim_plugin_sid_instance_init,
              NULL                        /* value table */
    };
    
    g_type_init ();
                                                                                                                             
    object_type = g_type_register_static (G_TYPE_OBJECT, "SimPluginSid", &type_info, 0);
  }

  return object_type;
}

/*
 *
 *
 *
 *
 */
SimPluginSid*
sim_plugin_sid_new (void)
{
  SimPluginSid *plugin_sid = NULL;

  plugin_sid = SIM_PLUGIN_SID (g_object_new (SIM_TYPE_PLUGIN_SID, NULL));

  return plugin_sid;
}

/*
 *
 *
 *
 *
 */
SimPluginSid*
sim_plugin_sid_new_from_data (gint          plugin_id,
			      gint          sid,
			      gint          category_id,
			      gint          class_id,
			      const gchar  *name)
{
  SimPluginSid *plugin_sid = NULL;

  plugin_sid = SIM_PLUGIN_SID (g_object_new (SIM_TYPE_PLUGIN_SID, NULL));
  plugin_sid->_priv->plugin_id = plugin_id;
  plugin_sid->_priv->sid = sid;
  plugin_sid->_priv->category_id = category_id;
  plugin_sid->_priv->class_id = class_id;
  plugin_sid->_priv->name = g_strdup (name);  

  return plugin_sid;
}

/*
 *
 *
 *
 */
SimPluginSid*
sim_plugin_sid_new_from_dm (GdaDataModel  *dm,
			    gint           row)
{
  SimPluginSid  *plugin_sid;
  GdaValue      *value;

  g_return_val_if_fail (dm, NULL);
  g_return_val_if_fail (GDA_IS_DATA_MODEL (dm), NULL);

  plugin_sid = SIM_PLUGIN_SID (g_object_new (SIM_TYPE_PLUGIN_SID, NULL));

  value = (GdaValue *) gda_data_model_get_value_at (dm, 0, row);
  plugin_sid->_priv->plugin_id = gda_value_get_integer (value);
  
  value = (GdaValue *) gda_data_model_get_value_at (dm, 1, row);
  plugin_sid->_priv->sid = gda_value_get_integer (value);
  
  value = (GdaValue *) gda_data_model_get_value_at (dm, 2, row);
  plugin_sid->_priv->category_id = gda_value_get_integer (value);
  
  value = (GdaValue *) gda_data_model_get_value_at (dm, 3, row);
  plugin_sid->_priv->class_id = gda_value_get_integer (value);
  
  value = (GdaValue *) gda_data_model_get_value_at (dm, 4, row);
  plugin_sid->_priv->name = gda_value_stringify (value);

  return plugin_sid;
}

/*
 *
 *
 *
 *
 */
gint
sim_plugin_sid_get_plugin_id (SimPluginSid  *plugin_sid)
{
  g_return_val_if_fail (plugin_sid, 0);
  g_return_val_if_fail (SIM_IS_PLUGIN_SID (plugin_sid), 0);

  return plugin_sid->_priv->plugin_id;
}

/*
 *
 *
 *
 *
 */
void
sim_plugin_sid_set_plugin_id (SimPluginSid  *plugin_sid,
			      gint           plugin_id)
{
  g_return_if_fail (plugin_sid);
  g_return_if_fail (SIM_IS_PLUGIN_SID (plugin_sid));
  g_return_if_fail (plugin_id > 0);

  plugin_sid->_priv->plugin_id = plugin_id;
}

/*
 *
 *
 *
 *
 */
gint
sim_plugin_sid_get_sid (SimPluginSid  *plugin_sid)
{
  g_return_val_if_fail (plugin_sid, 0);
  g_return_val_if_fail (SIM_IS_PLUGIN_SID (plugin_sid), 0);

  return plugin_sid->_priv->sid;
}

/*
 *
 *
 *
 *
 */
void
sim_plugin_sid_set_sid (SimPluginSid  *plugin_sid,
			gint           sid)
{
  g_return_if_fail (plugin_sid);
  g_return_if_fail (SIM_IS_PLUGIN_SID (plugin_sid));
  g_return_if_fail (sid > 0);

  plugin_sid->_priv->sid = sid;
}

/*
 *
 *
 *
 *
 */
gint
sim_plugin_sid_get_category_id (SimPluginSid  *plugin_sid)
{
  g_return_val_if_fail (plugin_sid, 0);
  g_return_val_if_fail (SIM_IS_PLUGIN_SID (plugin_sid), 0);

  return plugin_sid->_priv->category_id;
}

/*
 *
 *
 *
 *
 */
void
sim_plugin_sid_set_category_id (SimPluginSid  *plugin_sid,
			      gint           category_id)
{
  g_return_if_fail (plugin_sid);
  g_return_if_fail (SIM_IS_PLUGIN_SID (plugin_sid));
  g_return_if_fail (category_id > 0);

  plugin_sid->_priv->category_id = category_id;
}

/*
 *
 *
 *
 *
 */
gint
sim_plugin_sid_get_class_id (SimPluginSid  *plugin_sid)
{
  g_return_val_if_fail (plugin_sid, 0);
  g_return_val_if_fail (SIM_IS_PLUGIN_SID (plugin_sid), 0);

  return plugin_sid->_priv->class_id;
}

/*
 *
 *
 *
 *
 */
void
sim_plugin_sid_set_class_id (SimPluginSid  *plugin_sid,
			      gint           class_id)
{
  g_return_if_fail (plugin_sid);
  g_return_if_fail (SIM_IS_PLUGIN_SID (plugin_sid));
  g_return_if_fail (class_id > 0);

  plugin_sid->_priv->class_id = class_id;
}

/*
 *
 *
 *
 *
 */
gchar*
sim_plugin_sid_get_name (SimPluginSid  *plugin_sid)
{
  g_return_val_if_fail (plugin_sid, NULL);
  g_return_val_if_fail (SIM_IS_PLUGIN_SID (plugin_sid), NULL);

  return plugin_sid->_priv->name;
}

/*
 *
 *
 *
 *
 */
void
sim_plugin_sid_set_name (SimPluginSid  *plugin_sid,
			 gchar         *name)
{
  g_return_if_fail (plugin_sid);
  g_return_if_fail (SIM_IS_PLUGIN_SID (plugin_sid));
  g_return_if_fail (name);

  if (plugin_sid->_priv->name)
    g_free (plugin_sid->_priv->name);

  plugin_sid->_priv->name = name;
}
