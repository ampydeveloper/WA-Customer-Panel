import Vue from "vue";
import Router from "vue-router";
import About from "./pages/About.vue";
import Home from "./pages/Home";

Vue.use(Router);

const router = new Router({
    mode: "history",
    routes: [
        {
            path: "/",
            name: "home",
            component: Home
        },
        {
            path: "/about",
            name: "about",
            component: About
        }
    ]
});

export default router;
