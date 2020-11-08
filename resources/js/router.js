import Vue from "vue";
import VueRouter from "vue-router";
import About from "./pages/About";
import Home from "./pages/Home";
import Contact from "./pages/Contact.vue";
import Services from "./pages/Services.vue";
import FAQ from "./pages/FAQ.vue";
import SignUp from "./pages/SignUp.vue";
import SignIn from "./pages/SignIn.vue";
import { Role } from "./role";
import PageNotFound from "./pages/PageNotFound.vue";
import ForgotPassword from "./pages/ForgotPassword.vue";
import ChangePassword from "./pages/ChangePassword.vue";
import MapBox from "./pages/UserPanel/MapBox.vue";
import ChatBox from "./pages/UserPanel/ChatBox.vue";
import JobCreate from "./pages/JobCreate.vue";
import JobDashboard from "./pages/JobDashboard.vue";
import Profile from "./pages/Profile.vue";
import TrackJob from "./pages/TrackJob.vue";
import ViewHistory from "./pages/ViewHistory.vue";

/** Farm Imports */
import Farms from "./pages/UserPanel/Farms";
import EditFarm from "./pages/UserPanel/Farms/EditFarm.vue";
import CreateFarm from "./pages/UserPanel/Farms/CreateFarm.vue";
import FarmList from "./pages/UserPanel/Farms/FarmList.vue";
import ManagerList from "./pages/UserPanel/Farms/Managers/ManagerList.vue";
import CreateManager from "./pages/UserPanel/Farms/Managers/CreateManager.vue";

/** Job Imports */
import Jobs from "./pages/UserPanel/Jobs";
import JobList from "./pages/UserPanel/Jobs/JobList.vue";
import CreateJob from "./pages/UserPanel/Jobs/CreateJob.vue";
import EditJob from "./pages/UserPanel/Jobs/EditJob.vue";
import JobsDashboard from "./pages/UserPanel/Jobs/JobsDashboard.vue";
import ViewJob from "./pages/UserPanel/Jobs/ViewJob.vue";

/* Driver Imports */
import Drivers from "./pages/UserPanel/Drivers";
import DriversDashboard from "./pages/UserPanel/Drivers/DriversDashboard.vue";
import CreateDriver from "./pages/UserPanel/Drivers/CreateDriver.vue";
import EditDriver from "./pages/UserPanel/Drivers/EditDriver.vue";

import ProfileSettings from "./pages/UserPanel/ProfileSettings";
import EditProfile from "./pages/UserPanel/ProfileSettings/EditProfile.vue";

import Cards from "./pages/UserPanel/Cards";
import CardList from "./pages/UserPanel/Cards/CardList.vue";
import CreateCard from "./pages/UserPanel/Cards/CreateCard.vue";

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
    routes: [{
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
            path: "/jobcreate",
            name: "jobcreate",
            component: JobCreate
        },
        {
            path: "/view-history",
            name: "viewHistory",
            component: ViewHistory
        },
        {
            path: "/track-job",
            name: "trackJob",
            component: TrackJob
        },
        {
            path: "/profile",
            name: "profile",
            component: Profile
        },
        {
            path: "/job-dashboard",
            name: "jobDashboard",
            component: JobDashboard
        },
        {
            path: "/page-not-found",
            name: "pageNotFound",
            component: PageNotFound
        },
        {
            path: "/:jobId/track",
            name: "MapBox",
            component: MapBox
        },
        {
            path: "/chat",
            name: "ChatBox",
            component: ChatBox
        },
        {
            path: "/farms",
            component: Farms,
            beforeEnter: checkAuthentication,
            children: [{
                    path: "",
                    name: "farmsList",
                    component: FarmList
                },
                {
                    path: "create",
                    name: "createFarm",
                    component: CreateFarm,
                    meta: { requiresAuth: [Role.Customer, Role.Hauler] }
                },
                // {
                //     path: ":farmId/managers",
                //     name: "managerList",
                //     component: ManagerList,
                //     meta: { requiresAuth: [Role.Customer, Role.Hauler] }
                // },
                // {
                //     path: ":farmId/managers/create",
                //     name: "createManager",
                //     component: CreateManager,
                //     meta: { requiresAuth: [Role.Customer, Role.Hauler] }
                // },
                {
                    path: ":farmId/edit",
                    name: "editFarm",
                    component: EditFarm,
                    meta: { requiresAuth: [Role.Customer, Role.Hauler] }
                },
                {
                    path: ":farmId/jobs",
                    name: "jobsList",
                    component: JobList
                },
                {
                    path: ":farmId/jobs/upcoming",
                    name: "upcomingJobsList",
                    component: JobList
                }
            ]
        },
        {
            path: "/jobs",
            component: Jobs,
            beforeEnter: checkAuthentication,
            children: [{
                    path: "",
                    name: "JobsDashboard",
                    component: JobsDashboard
                },
                {
                    path: ":farmId/farm",
                    name: "FarmJobsDashboard",
                    component: JobsDashboard
                },
                {
                    path: ":jobId/view",
                    name: "ViewJob",
                    component: ViewJob
                },
                {
                    path: "upcoming",
                    name: "upcomingJobsDashboard",
                    component: JobsDashboard
                },
                {
                    path: "create",
                    name: "createJob",
                    component: CreateJob
                },
                {
                    path: ":jobId/edit",
                    name: "editJob",
                    component: EditJob
                }
            ]
        },
        {
            path: "/drivers",
            component: Drivers,
            beforeEnter: checkAuthentication,
            children: [{
                    path: "",
                    name: "DriversDashboard",
                    component: DriversDashboard
                },
                {
                    path: "create",
                    name: "createDriver",
                    component: CreateDriver
                },
                {
                    path: ":driverId/edit",
                    name: "editDriver",
                    component: EditDriver
                }
            ]
        },
        {
            path: "/managers",
            component: Drivers,
            beforeEnter: checkAuthentication,
            children: [{
                    path: "",
                    name: "ManagersDashboard",
                    component: ManagerList,
                    meta: { requiresAuth: [Role.Customer, Role.Hauler] }
                },
                {
                    path: "create",
                    name: "createManager",
                    component: CreateManager,
                    meta: { requiresAuth: [Role.Customer, Role.Hauler] }
                },
                {
                    path: ":managerId/edit",
                    name: "editManager",
                    component: CreateManager,
                    meta: { requiresAuth: [Role.Customer, Role.Hauler] }
                }
            ]
        },
        {
            path: "/profile-settings",
            component: ProfileSettings,
            beforeEnter: checkAuthentication,
            children: [{
                path: "",
                name: "profileSettings",
                component: EditProfile
            }]
        },
        {
            path: "/cards",
            component: Cards,
            beforeEnter: checkAuthentication,
            meta: { requiresAuth: [Role.Customer, Role.Hauler] },
            children: [{
                    path: "",
                    name: "cardList",
                    component: CardList,
                    meta: { requiresAuth: [Role.Customer, Role.Hauler] }
                },
                {
                    path: "create",
                    name: "createCard",
                    component: CreateCard,
                    meta: { requiresAuth: [Role.Customer, Role.Hauler] }
                }
            ]
        }
    ]
});

router.beforeEach((to, from, next) => {
    // redirect to login page if not logged in and trying to access a restricted page
    const { requiresAuth } = to.meta;
    const currentUser = JSON.parse(window.localStorage.getItem("user"));

    if (requiresAuth) {
        if (!currentUser) {
            // not logged in so redirect to login page with the return url
            return next({ path: "/page-not-found", query: { returnUrl: to.path } });
        }

        // check if route is restricted by role
        if (requiresAuth.length && !requiresAuth.includes(currentUser.role_id)) {
            localStorage.removeItem("currentUser");
            // role not authorised so redirect to home page
            return next({ path: "/page-not-found" });
        }
    }
    next();
});

export default router;