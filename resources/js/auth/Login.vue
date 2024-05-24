<template>
    <v-app>
        <v-main>
            <v-container fluid>
                <v-row align="center" justify="center">
                    <v-col cols="12" sm="10" md="8" lg="6" xl="4">
                        <v-card class="mx-auto mt-5" elevation="5" width="500">
                            <template slot="progress">
                                <v-progress-linear
                                    color="black"
                                    height="5"
                                    indeterminate
                                ></v-progress-linear>
                            </template>
                            <v-card-title
                                class="justify-center py-8"
                                style="font-size: 14; text-align:justify; white-space: normal"
                            >
                                <v-row align="center">
                                    <v-col cols="12" sm="4">
                                        <!-- Adjusted the width of the image for responsiveness -->
                                        <v-img
                                            max-width="200"
                                            :src="imagePath"
                                            alt="My Image"
                                            contain
                                            fill-height
                                        ></v-img>
                                    </v-col>
                                    <v-col cols="12" sm="8">
                                        <!-- Wrapped text in a v-container for responsive text -->
                                        <v-container fluid>
                                            <v-row>
                                                <v-col cols="12">
                                                    <span class="display-1"
                                                        >Tejero Medical
                                                        <br>
                                                        Maternity Clinic</span
                                                    >
                                                </v-col>
                                            </v-row>
                                        </v-container>
                                    </v-col>
                                </v-row>
                            </v-card-title>
                            <v-divider></v-divider>
                            <v-card-text>
                                <v-form
                                    id="LoginForm"
                                    ref="LoginForm"
                                    @submit.prevent="handleLogin"
                                >
                                    <v-text-field
                                        label="Username"
                                        name="username"
                                        v-model="employee.username"
                                        prepend-icon="mdi-account-circle"
                                        required
                                        readonly
                                        autocomplete="off"
                                        @focus="
                                            $event.target.removeAttribute(
                                                'readonly'
                                            )
                                        "
                                        outlined
                                        dense
                                        class="uppercase"
                                        :rules="[...rules.required]"
                                    ></v-text-field>

                                    <v-text-field
                                        v-model="employee.password"
                                        id="hiddenPassword"
                                        label="Password"
                                        autocomplete="off"
                                        prepend-icon="mdi-lock"
                                        :type="
                                            showPassword ? 'text' : 'password'
                                        "
                                        :append-icon="
                                            showPassword
                                                ? 'mdi-eye'
                                                : 'mdi-eye-off'
                                        "
                                        @click:append="
                                            showPassword = !showPassword
                                        "
                                        @focus="
                                            $event.target.removeAttribute(
                                                'readonly'
                                            )
                                        "
                                        required
                                        readonly
                                        outlined
                                        dense
                                        name="password"
                                        :rules="rules.password"
                                    ></v-text-field>
                                    <v-row>
                                        <v-col cols="12" sm="6">
                                            <router-link to="/forgot_password">
                                                <span
                                                    @click="forgetPassword()"
                                                    style="
                                                        color: blue;
                                                        cursor: pointer;
                                                    "
                                                    >Forgot password?</span
                                                >
                                            </router-link>
                                        </v-col>
                                        <v-col cols="12" sm="6">
                                            <router-link to="/medcert_form">
                                                <span
                                                    style="
                                                        color: blue;
                                                        cursor: pointer;
                                                    "
                                                    >Request Medical
                                                    Certificate</span
                                                >
                                            </router-link>
                                        </v-col>
                                    </v-row>

                                    <v-divider></v-divider>

                                    <v-card-actions>
                                        <v-btn
                                            small
                                            color="success"
                                            @click="patientRequest"
                                        >
                                            <v-icon>mdi-arrow-left</v-icon>
                                        </v-btn>
                                        <v-spacer></v-spacer>
                                        <v-btn
                                            small
                                            color="success"
                                            type="submit"
                                        >
                                            <v-icon left>mdi-lock</v-icon>
                                            Login
                                        </v-btn>
                                    </v-card-actions>
                                </v-form>
                            </v-card-text>
                        </v-card>
                    </v-col>
                </v-row>
            </v-container>
        </v-main>
    </v-app>
</template>
<script>
import { mapActions, mapState } from "vuex";
import myImage from "../../../public/images/tejero_maternity_logo1.png";

export default {
    name: "LoginPage",
    data() {
        return {
            showPassword: false,
            disableBtn: false,
            loading: false,
            employee: {},
            tab: null,
            imagePath: myImage,
        };
    },
    computed: {
        ...mapState(["rules", "systemData"]),
    },

    methods: {
        ...mapActions(["login"]),
        forgetPassword() {
            console.log("test");
        },
        patientRequest() {
            // setTimeout(() => {
            // window.location.reload()
            console.log("toto");
            this.$router.push("/");
            // }, 1000);
        },
        resetForm() {
            if (this.$refs.LoginForm) {
                this.$refs.LoginForm.reset();
            }

            if (this.$refs.RegisterForm) {
                this.$refs.RegisterForm.reset();
            }
        },

        handleLogin() {
            this.disableBtn = true;
            if (this.$refs.LoginForm.validate()) {
                const myForm = document.getElementById("LoginForm");
                const formdata = new FormData(myForm);

                axios({
                    method: "POST",
                    url: `login`,
                    data: formdata,
                    config: {
                        headers: { "Content-Type": "multipart/form-data" },
                    },
                })
                    .then((res) => {
                        // console.log(res.data);
                        this.login(res.data);
                        // this.$router.replace('/home')

                        window.location.reload();
                    })
                    .catch((err) => {
                        this.disableBtn = false;
                        console.log(err);
                        alert("Something Went Wrong");
                    });
            }
        },

        handleRegistration() {
            this.disableBtn = true;
            if (this.$refs.RegisterForm.validate()) {
                const myForm = document.getElementById("RegisterForm");
                const formdata = new FormData(myForm);

                axios({
                    method: "POST",
                    url: "register",
                    data: formdata,
                    config: {
                        headers: { "Content-Type": "multipart/form-data" },
                    },
                })
                    .then(() => {
                        //  this.login()
                        // this.$router.replace('/home')
                        window.location.reload();
                    })
                    .catch((err) => {
                        this.disableBtn = false;
                        console.log(err);
                        alert("Something Went Wrong");
                    });
            }
        },
    },

    mounted() {},
    watch: {},
};
</script>
