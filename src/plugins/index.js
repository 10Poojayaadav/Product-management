// Plugins
import vuetify from './vuetify'
// import socket from './socket-io'
import router from '../router'

export function registerPlugins (app) {
  app
    .use(vuetify)
    .use(router)
    
}
