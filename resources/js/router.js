import Vue from "vue";
import VueRouter from "vue-router";
import About from "./pages/About";
import Home from "./pages/Home";
import Contact from "./pages/Contact.vue";
import Services from "./pages/Services.vue";
import FAQ from "./pages/FAQ.vue";
import SignUp from "./pages/SignUp.vue";
import SignIn from "./pages/SignIn.vue";
import ForgotPassword from "./pages/ForgotPassword.vue";
import ChangePassword from "./pages/ChangePassword.vue";
import CreateFarm from "./pages/UserPanel/Farms/CreateFarm.vue";

Vue.use(VueRouter);

const router = new VueRouter({
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
        },
        {
            path: "/sign-in",
            name: "signin",
            component: SignIn
        },
        {
            path: "/forgot-password",
            name: "forgotPassword",
            component: ForgotPassword
        },
        {
            path: "/change-password/:token",
            name: "changePassword",
            component: ChangePassword
        },
        {
            path: "/create-farm",
            name: "createFarm",
            component: CreateFarm,
            beforeEnter(to, from, next) {
                const token = window.localStorage.getItem("token");
                if (token !== undefined && token !== null) {
                    next();
                } else {
                    window.location.href = "/sign-in";
                }
            }
        }
    ]
});

export default router;
