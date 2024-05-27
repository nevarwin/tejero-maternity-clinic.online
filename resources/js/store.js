import axios from "axios";
import Vue from "vue";
import Vuex from "vuex";
import createPersistedState from "vuex-persistedstate";
import province from "./province.json";
import municipality from "./municipality.json";
import barangay from "./barangay.json";

Vue.use(Vuex);
const persistedData = new createPersistedState({
    key: "clinic_system_vuex",
    storage: localStorage,
    reducer: (state) => ({
        loggedInUser: state.loggedInUser,
        systemData: state.systemData,
        case_no: state.case_no,
        user_id: state.user_id,
    }),
});

export default new Vuex.Store({
    state: {
        case_no: null,
        user_id: null,
        loggedInUser: null,
        systemData: null,
        province: province,
        municipality: municipality,
        barangay: barangay,
        allUsers: [],
        rules: {
            required: [(v) => !!v || "Field is required"],
            email: [
                (v) =>
                    !v ||
                    /^\w+([.-]?\w+)*@\w+([.-]?\w+)*(\.\w{2,3})+$/.test(v) ||
                    "E-mail must be valid",
            ],
            password: [
                (v) => !!v || "Field is required",
                // (v) => !v || /[0-9]/.test(v) || "Must contain number",
                // (v) => !v || /[a-zA-Z]/.test(v) || "Must contain letter",
                // v => !v || /[@$!%*?&]/.test(v) || 'Must contain symbol',
                (v) =>
                    (v || "").length >= 8 ||
                    "Must contain atlest 8 characters.",
            ],
            hex: [
                (v) => !v || /[0-9A-Fa-f]{6}/.test(v) || "Must be a hex value",
            ],
            confirmpassword(temp, actual) {
                return [(v) => temp === actual || "Password must match"];
            },
            uniqueRole(list) {
                return [
                    (v) => !!v || "Field is required",
                    (v) =>
                        // console.log(typeof _.find(list, {name : v}) === 'object', list, v)
                        typeof _.find(list, { name: v }) === "undefined" ||
                        "Role is already exists.",
                ];
            },
            uniquePermission(list) {
                return [
                    (v) => !!v || "Field is required",
                    (v) =>
                        // console.log(typeof _.find(list, {name : v}) === 'object', list, v)
                        typeof _.find(list, { name: v }) === "undefined" ||
                        "Permission is already exists.",
                ];
            },
            array: [(v) => !v.length == 0 || "Field is required"],
            higher_number(min, max) {
                return [
                    (v) => !!v || "Field is required",
                    (v) => v > min || "Must Higher",
                    (v) => v <= max || "Too much",
                ];
            },
            numberRule: [
                // (v) => {
                //     if (!isNaN(v) && v >= 0 && v <= 99) return true;
                //     return "Field is required";
                // },
                (v) => !!v || "Field is required",
                (v) =>
                    (!isNaN(v) && v >= 1 && v <= 99) || "Must be between 1-99",
            ],
            contactNo: [
                (v) => !!v || "Field is required",
                (v) => !v || /[0-9]{11}/.test(v) || "Must be a 11 number",
            ],
        },
        provinceMaster: [],
        religionData: [],
        doctorData: [],
        selectSex: [
            { name: "Female", id: 1 },
            { name: "Male", id: 2 },
        ],
        selectCivil: [
            { name: "Single", id: 1 },
            { name: "Married", id: 2 },
            { name: "Widow", id: 3 },
        ],
        selectRoomType: [
            { id: 1, name: "Private" },
            { id: 2, name: "Ward" },
        ],
        patientData: [],
        roomData: [],
        caseData: [],
        admmisionData: [],
        case_VitalSign: [],
        case_MedicalSheet: [],
        case_NewBorn: [],
        case_Partograph: [],
        case_Files: [],
        medcertData: [],
    },

    actions: {
        set_case_no(context, val) {
            context.commit("set_case_no", val);
        },
        set_id(context, val) {
            context.commit("set_id", val);
        },
        login({ commit }) {
            axios({
                method: "GET",
                url: "get_user",
            }).then((response) => {
                commit("login", response.data);
            });
        },

        logout(context) {
            context.commit("logout");
        },

        getUsers({ commit }) {
            axios({
                method: "GET",
                url: "get_users",
            }).then((response) => {
                commit("getUsers", response.data);
            });
        },
        getSystemData({ commit }) {
            axios({
                method: "get",
                url: "get_system_data",
            })
                .then((res) => {
                    commit("getSystemData", res.data);
                })
                .catch((err) => {
                    console.log(err);
                });
        },
        getProvince({ commit }) {
            axios({
                method: "get",
                url: "get_province",
            })
                .then((res) => {
                    commit("getProvince", res.data);
                })
                .catch((err) => {
                    console.log(err);
                });
        },
        getReligion({ commit }) {
            axios({
                method: "get",
                url: "get_religion",
            })
                .then((res) => {
                    commit("getReligion", res.data);
                })
                .catch((err) => {
                    console.log(err);
                });
        },
        getDoctor({ commit }) {
            axios({
                method: "get",
                url: "get_doctor",
            })
                .then((res) => {
                    commit("getDoctor", res.data);
                })
                .catch((err) => {
                    console.log(err);
                });
        },
        getPatient({ commit }) {
            axios({
                method: "get",
                url: "get_patient",
            })
                .then((res) => {
                    commit("getPatient", res.data);
                })
                .catch((err) => {
                    console.log(err);
                });
        },
        getRoom({ commit }) {
            axios({
                method: "get",
                url: "get_room",
            })
                .then((res) => {
                    commit("getRoom", res.data);
                })
                .catch((err) => {
                    console.log(err);
                });
        },
        getCase({ commit }) {
            axios({
                method: "get",
                url: "get_case",
            })
                .then((res) => {
                    commit("getCase", res.data);
                    console.log(res.data);
                })
                .catch((err) => {
                    console.log(err);
                });
        },
        getAdmision({ commit }) {
            axios({
                method: "get",
                url: "get_case",
            })
                .then((res) => {
                    let admmision = res.data.filter((item) => {
                        if (!item.discharge_date) {
                            return item;
                        }
                    });
                    commit("getAdmision", admmision);
                    console.log(admmision, "245");
                })
                .catch((err) => {
                    console.log(err);
                });
        },
        getMedCert({ commit }) {
            axios({
                method: "get",
                url: "get_medcert", // Update the URL to fetch medcert data
            })
                .then((res) => {
                    // Commit the medcert data to the store using the correct mutation
                    commit("setMedCertData", res.data);
                    console.log(res.data, "MedCert data");
                    console.log(setMedCertData, "MedCert data");
                })
                .catch((err) => {
                    console.log(err);
                });
        },
        getVitalSign({ commit, state }) {
            axios({
                method: "post",
                data: { case_no: state.case_no },
                url: "vital_sign",
            })
                .then((res) => {
                    commit("setVitalSign", res.data); // Commit mutation with received data
                })
                .catch((err) => {
                    console.log(err);
                });
        },
        getPartograph({ commit, state }) {
            axios({
                method: "post",
                url: "partograph",
                data: { case_no: state.case_no },
            })
                .then((res) => {
                    commit("setPartoGraph", res.data);
                })
                .catch((err) => {
                    console.log(err);
                });
        },
        getMedicalSheet({ commit, state }) {
            axios({
                method: "post",
                data: { case_no: state.case_no },
                url: "medication_sheet",
            })
                .then((res) => {
                    commit("setMedicalSheet", res.data); // Commit mutation with received data
                })
                .catch((err) => {
                    console.log(err);
                });
        },
        getNewBorn({ commit, state }) {
            axios({
                method: "post",
                data: { case_no: state.case_no },
                url: "new_born",
            })
                .then((res) => {
                    commit("setNewBorn", res.data); // Commit mutation with received data
                    // console.log(res.data)
                })
                .catch((err) => {
                    console.log(err);
                });
        },
        getFiles({ commit, state }) {
            axios({
                method: "post",
                data: { case_no: state.case_no },
                url: "getFiles",
            })
                .then((res) => {
                    commit("setFile", res.data);
                    console.log(res.data);
                })
                .catch((err) => {
                    console.log(err);
                });
        },
    },

    mutations: {
        set_id(state, val) {
            state.user_id = val;
        },
        set_case_no(state, val) {
            console.log(val, "254");
            state.case_no = val;
            state.case_NewBorn = null;
            state.case_MedicalSheet = null;
            state.case_VitalSign = null;
            state.case_Partograph = null;
            state.case_Files = null;
            this.commit("getPartograph");
            this.commit("getVitalSign");
            this.commit("getMedicalSheet");
            this.commit("getNewBorn");
            this.commit("getFiles");
        },
        login(state, payload) {
            state.loggedInUser = payload;
            // window.location.href='/';
            // window.location.reload()
        },

        logout(state) {
            state.loggedInUser = {};
            state.isLoggedin = false;
        },

        getUsers(state, data) {
            console.log("hehaw", data);
            state.allUsers = data;
        },
        getSystemData(state, payload) {
            state.systemData = payload;
        },
        getProvince(state, payload) {
            state.provinceMaster = payload;
        },
        getReligion(state, payload) {
            state.religionData = payload;
        },
        getDoctor(state, payload) {
            state.doctorData = payload;
        },
        getPatient(state, payload) {
            state.patientData = payload;
        },
        getRoom(state, payload) {
            state.roomData = payload;
        },
        getCase(state, payload) {
            state.caseData = payload;
        },
        getAdmision(state, payload) {
            state.admmisionData = payload;
        },
        getVitalSign(state) {
            axios({
                method: "post",
                data: { case_no: state.case_no },
                url: "vital_sign",
            })
                .then((res) => {
                    state.case_VitalSign = res.data.filter((rec) => {
                        if (state.case_no == rec.case_no) {
                            return rec;
                        }
                    });
                })
                .catch((err) => {
                    console.log(err);
                });
        },
        getPartograph(state) {
            axios({
                method: "post",
                url: "partograph",
                data: { case_no: state.case_no },
            })
                .then((res) => {
                    state.case_Partograph = res.data.filter((rec) => {
                        if (state.case_no == rec.case_no) {
                            return rec;
                        }
                    });
                })
                .catch((err) => {
                    console.log(err);
                });
        },
        getMedicalSheet(state) {
            axios({
                method: "post",
                data: { case_no: state.case_no },
                url: "medication_sheet",
            })
                .then((res) => {
                    state.case_MedicalSheet = res.data.filter((rec) => {
                        if (state.case_no == rec.case_no) {
                            return rec;
                        }
                    });
                    // state.case_MedicalSheet = res.data;
                    // console.log(res.data)
                })
                .catch((err) => {
                    console.log(err);
                });
        },
        getNewBorn(state) {
            axios({
                method: "post",
                data: { case_no: state.case_no },
                url: "new_born",
            })
                .then((res) => {
                    state.case_NewBorn = res.data.filter((rec) => {
                        if (state.case_no == rec.case_no) {
                            return rec;
                        }
                    });
                    // state.case_NewBorn = res.data;
                    // console.log(res.data)
                })
                .catch((err) => {
                    console.log(err);
                });
        },
        getFiles(state) {
            axios({
                method: "post",
                data: { case_no: state.case_no },
                url: "getFiles",
            })
                .then((res) => {
                    state.case_Files = res.data.filter((rec) => {
                        if (state.case_no == rec.case_no) {
                            return rec;
                        }
                    });
                    // state.case_Files = res.data;
                    // console.log(res.data);
                })
                .catch((err) => {
                    console.log(err);
                });
        },
        setVitalSign(state, payload) {
            state.case_VitalSign = payload;
        },
        setPartoGraph(state, payload) {
            state.case_Partograph = payload;
        },
        setMedicalSheet(state, payload) {
            state.case_MedicalSheet = payload;
        },
        setNewBorn(state, payload) {
            state.case_NewBorn = payload;
        },
        setFile(state, payload) {
            state.case_Files = payload;
        },
        setMedCertData(state, payload) {
            state.medcertData = payload;
        },
    },
    plugins: [persistedData],
});
