/* Host
 *
 *
 */

#ifndef __SIM_HOST_H__
#define __SIM_HOST_H__ 1

#include <glib.h>
#include <glib-object.h>
#include "sim-server.h"

#ifdef __cplusplus
extern "C" {
#endif /* __cplusplus */

#define SIM_TYPE_HOST                  (sim_host_get_type ())
#define SIM_HOST(obj)                  (G_TYPE_CHECK_INSTANCE_CAST (obj, SIM_TYPE_HOST, SimHost))
#define SIM_HOST_CLASS(klass)          (G_TYPE_CHECK_CLASS_CAST (klass, SIM_TYPE_HOST, SimHostClass))
#define SIM_IS_HOST(obj)               (G_TYPE_CHECK_INSTANCE_TYPE (obj, SIM_TYPE_HOST))
#define SIM_IS_HOST_CLASS(klass)       (G_TYPE_CHECK_CLASS_TYPE ((klass), SIM_TYPE_HOST))
#define SIM_HOST_GET_CLASS(obj)        (G_TYPE_INSTANCE_GET_CLASS ((obj), SIM_TYPE_HOST, SimHostClass))

G_BEGIN_DECLS

typedef struct _SimHost        SimHost;
typedef struct _SimHostClass   SimHostClass;
typedef struct _SimHostPrivate SimHostPrivate;

struct _SimHost {
  GObject parent;

  SimHostPrivate *_priv;
};

struct _SimHostClass {
  GObjectClass parent_class;
};

GType             sim_host_get_type                        (void);
SimHost*        sim_host_new                             (void);

G_END_DECLS

#ifdef __cplusplus
}
#endif /* __cplusplus */

#endif /* __SIM_HOST_H__ */
