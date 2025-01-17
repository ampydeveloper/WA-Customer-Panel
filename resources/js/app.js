require("./bootstrap");
// import Router from "vue-router";
import App from "./App.vue";
import router from "./router";
import VueFormGenerator from "vue-form-generator";
import VueSweetalert2 from "vue-sweetalert2";
import VueToast from "vue-toast-notification";
// Import one of available themes
import "vue-toast-notification/dist/theme-default.css";
import "vuetify/dist/vuetify.min.css";

import VueMapbox from "vue-mapbox";
import Mapbox from "mapbox-gl";
import Vuelidate from "vuelidate";
import Vuetify from "vuetify";
import Chat from "vue-beautiful-chat";
import VueSocketIO from "vue-socket.io";
import { authorizationMixin } from "./role";
import fieldCancel from "./forms/custom-fields/fieldCancel.vue";
import fieldFilepond from "./forms/custom-fields/fieldFilepond.vue";
import fieldVueGoogleAutocomplete from "./forms/custom-fields/fieldVueGoogleAutocomplete.vue";

window.Vue = require("vue");

Vue.use(Chat);
Vue.use(Vuelidate);
Vue.use(Vuetify);

/** Vue Form Generator */
Vue.component("fieldCancel", fieldCancel);
Vue.component("fieldFilepond", fieldFilepond);
Vue.component("fieldVueGoogleAutocomplete", fieldVueGoogleAutocomplete);
Vue.use(VueFormGenerator);

Vue.use(VueMapbox, { mapboxgl: Mapbox });

const sweetAlertOptions = {
  confirmButtonColor: "#41b882",
  cancelButtonColor: "#ff7674"
};

/** Vue Sweet Alert */
Vue.use(VueSweetalert2, sweetAlertOptions);

/** Vue Tost */
Vue.use(VueToast);

Vue.mixin(authorizationMixin);
var siteUrl = 'http://wa.customer.leagueofclicks.com/';

// Vue.use(
//   new VueSocketIO({
//     debug: true,
//     connection: `http://${process.env.MIX_SOCKET_SERVER_IP}:${process.env.MIX_SOCKET_SERVER_PORT}`
//   })
// );

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
const app = new Vue({
  el: "#app",
  components: { App },
  router,
  vuetify: new Vuetify(),
  render: h => h(App)
});
