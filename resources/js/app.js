require("./bootstrap");
// import Router from "vue-router";
import App from "./App.vue";
import router from "./router";
import VueFormGenerator from "vue-form-generator";
import VueSweetalert2 from "vue-sweetalert2";
import VueToast from "vue-toast-notification";
// Import one of available themes
import "vue-toast-notification/dist/theme-default.css";

window.Vue = require("vue");

/** Vue Form Generator */
Vue.use(VueFormGenerator);

const sweetAlertOptions = {
    confirmButtonColor: "#41b882",
    cancelButtonColor: "#ff7674"
};

/** Vue Sweet Alert */
Vue.use(VueSweetalert2, sweetAlertOptions);

/** Vue Tost */
Vue.use(VueToast);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
const app = new Vue({
    el: "#app",
    components: { App },
    router
});
