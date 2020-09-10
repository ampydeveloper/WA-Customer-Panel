import Vue from "vue";
import Router from "vue-router";
import About from "./pages/About";
import Home from "./pages/Home";
import Contact from "./pages/Contact.vue";
import Services from "./pages/Services.vue";
import FAQ from "./pages/FAQ.vue";
import SignUp from "./pages/SignUp.vue";

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
        },
        {
            path: "/contact",
            name: "contact",
            component: Contact
        },
        {
            path: "/services",
            name: "services",
            component: Services
        },
        {
            path: "/faq",
            name: "faq",
            component: FAQ
        },
        {
            path: "/sign-up",
            name: "signup",
            component: SignUp
        }
    ]
});

export default router;
