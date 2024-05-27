import Vue from "vue";
import VueRouter from "vue-router";

Vue.use(VueRouter);
import Home from "./components/Home";
import Hello from "./components/Hello";
import Test from "./components/Test";
import Registration from "./components/pages/account_registration/Registration.vue";
import Login from "./auth/Login";
import LoginOption from "./auth/LoginOption";
import RequestDocument from "./auth/RequestDocument";

import PatientRegistrationPage from "./components/pages/patient_registration/PatientRegistrationPage.vue";
import ReligionPage from "./components/pages/masters/ReligionPage.vue";
import DoctorPage from "./components/pages/masters/DoctorPage.vue";
import RoomPage from "./components/pages/masters/RoomPage.vue";
import AdmissionPage from "./components/pages/admission/AdmissionPage.vue";
import PatientMonitoring from "./components/pages/patient_monitoring/PatientMonitoringPage.vue";
import ForgotPassword from "./components/pages/forgot_password/ForgotPassword.vue";
import MedicalCertificate from "./components/pages/forgot_password/MedicalCertificate.vue";
import ChangePassword from "./components/pages/forgot_password/changePassword.vue";
import audit_trail from "./components/pages/audit_trail/audit_trail.vue";
import patient_history from "./components/pages/audit_trail/patient_history.vue";

import file from "./components/pages/file.vue";
import MedCertPage from "./components/pages/medcertrequest/MedCertPage.vue";

export default new VueRouter({
    mode: "history",
    base: "/",
    routes: [
        // NOTE: LOGIN

        {
            path: "/login2",
            name: "login",
            component: Login,
        },
        {
            path: "/",
            name: "login_option",
            component: LoginOption,
        },
        {
            path: "/request_document",
            name: "request_document",
            component: RequestDocument,
        },

        // NOTE: Authenticated
        {
            path: "/home",
            name: "home",
            component: Home,
        },
        {
            path: "/hello",
            name: "hello",
            component: Hello,
        },

        {
            path: "/test",
            name: "test",
            component: Test,
        },
        {
            path: "/registration",
            name: "registration",
            component: Registration,
        },
        {
            path: "/patient_registration",
            name: "patient_registration",
            component: PatientRegistrationPage,
        },
        {
            path: "/admission",
            name: "admission",
            component: AdmissionPage,
        },

        {
            path: "/medcert_request_list",
            name: "medcert_request_list",
            component: MedCertPage,
        },

        // Master

        {
            path: "/religion",
            name: "religion",
            component: ReligionPage,
        },
        {
            path: "/doctor",
            name: "doctor",
            component: DoctorPage,
        },
        {
            path: "/room",
            name: "room",
            component: RoomPage,
        },
        {
            path: "/patient_monitoring",
            name: "patient_monitoring",
            component: PatientMonitoring,
        },
        {
            path: "/forgot_password",
            name: "forgot_password",
            component: ForgotPassword,
        },
        {
            path: "/medcert_form",
            name: "medcert_form",
            component: MedicalCertificate,
        },
        {
            path: "/change_password",
            name: "change_password",
            component: ChangePassword,
        },
        {
            path: "/file",
            name: "file",
            component: file,
        },
        {
            path: "/audit_trail",
            name: "audit_trail",
            component: audit_trail,
        },
        {
            path: "/patient_history",
            name: "patient_history",
            component: patient_history,
        },
    ],
});
