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
import Farms from "./pages/UserPanel/Farms";
import CreateFarm from "./pages/UserPanel/Farms/CreateFarm.vue";
import FarmList from "./pages/UserPanel/Farms/FarmList.vue";
import ManagerList from "./pages/UserPanel/Farms/Managers/ManagerList.vue";
import CreateManager from "./pages/UserPanel/Farms/Managers/CreateManager.vue";

Vue.use(VueRouter);

const checkAuthentication = (to, from, next) => {
  const token = window.localStorage.getItem("token");
  if (token !== undefined && token !== null) {
    next();
  } else {
    window.location.href = "/sign-in";
  }
};

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
      path: "/farms",
      name: "farmsList",
      component: Farms,
      beforeEnter: checkAuthentication,
      children: [
        {
          path: "",
          name: "Farm",
          component: FarmList
        },
        {
          path: "create",
          name: "createFarm",
          component: CreateFarm
        },
        {
          path: ":farmId/managers",
          name: "managerList",
          component: ManagerList
        },
        {
          path: ":farmId/managers/create",
          name: "createManager",
          component: CreateManager
        }
      ]
    }
  ]
});

export default router;
