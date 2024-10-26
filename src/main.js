import App from "./App.vue"; // Components
import * as Vue from "vue";
import { createApp } from "vue"; //Composables
import ToastPlugin from "vue-toast-notification"; //toast
import "vue-toast-notification/dist/theme-bootstrap.css"; //toast
import "./styles/style.css"; //style
import "./styles/responsive.css"; //style
import { registerPlugins } from "@/plugins"; // Plugins
import store from "./store";
import socket from "./plugins/socket-io.js";

const app = createApp(App);

const SocketPlugin = {
  install(app) {
    app.config.globalProperties.$socket = socket;
  },
};

app.use(SocketPlugin);
app.use(ToastPlugin);
app.use(store);
registerPlugins(app);
app.mount("#app");
