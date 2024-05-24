<template>
    <v-app>
        <v-main>
            <v-container class="fill-height" fluid>
                <v-row justify="center">
                    <v-col xs="12" sm="6" md="6" lg="5" xl="4">
                        <v-img :src="imagePath" alt="My Image" contain fill-height></v-img>
                    </v-col>
                    <v-col
                        xs="12"
                        sm="6"
                        md="6"
                        lg="5"
                        xl="4"
                        class="d-flex align-center justify-center"
                    >
                        <v-card width="60%" elevation="5">
                            <v-card-title>Reset Password</v-card-title>
                            <v-card-text class="mt-3">
                                <v-text-field
                                v-model="emailAddress"
                                outlined
                                dense
                                label="Email"
                                :rules="rules.email"
                                ></v-text-field>
                                <v-btn block color="success" @click="sendEmail()"
                                >Reset my password</v-btn
                                >
                            </v-card-text>
                        </v-card>
                    </v-col>
                </v-row>
                <verificationPinDialog
                    :dialog="openDialog"
                    :email="emailAddress"
                    :id="getID"
                />
                <snackbar
                    :snackbar="snackbar"
                />
                <!-- <div style="display:none;">
                <changePassword
                :id="getID"
                /> -->
            <!-- </div> -->
            </v-container>
        </v-main>
    </v-app>
</template>

<script>
import { mapActions, mapState } from "vuex";
import myImage from "../../../../../public/images/Forgotpassword-amico.png";
import verificationPinDialog from "./verificationPinDialog.vue"
import snackbar from "../../includes/SnackBar.vue"
import changePassword from "./changePassword.vue";
import axios from "axios";
export default {
    name: "LoginPage",
    components:{
        "verificationPinDialog":verificationPinDialog,
        "snackbar":snackbar,
        "changePassword":changePassword
    },
    data() {
        return {
        snackbar:{
                    show:false,
                    color:'success',
                    text:'',
                },
        openDialog:false,
        emailAddress: "",
        imagePath: myImage,
        showPassword: false,
        disableBtn: false,
        loading: false,
        employee: {},
        tab: null,
        getID:null,
        };
    },
    computed: {
        ...mapState(["rules", "systemData"]),
    },

    methods: {
        ...mapActions(["login"]),

        patientRequest() {
        // setTimeout(() => {
        // window.location.reload()
        console.log("toto");
        this.$router.push("/");
        // }, 1000);
        },

        async sendEmail() {
        if(this.emailAddress.includes("@") && this.emailAddress.includes("com")){
            
            await axios({
            method: "POST",
            url: "api/verify_account",
            data: { email: this.emailAddress },
        }).then(async (res) => {
            console.log(res.data)
            // CONDITION FOR SENDING PIN
            if (res.data) {
                this.getID = res.data.id;
            await axios({
                method: "POST",
                url: "api/send_pin_number",
                data: { id: res.data.id, email: this.emailAddress },
            }).then(async (res) => {
                await axios
                .post("api/mail/store", {
                    to: this.emailAddress,
                    pin: res.data,
                })
                .then((response) => {
                    this.openDialog = true;

                    this.snackbar.show=true
                    this.snackbar.text= "Email Sent"
                    this.snackbar.color="success"
                })
                .catch((error) => {
                    console.error("An error occurred:", error);
                });
            });
            } else {
            this.snackbar.show=true
                    this.snackbar.text= "Email not verified"
                    this.snackbar.color="error"
            }
        });
        }
        
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
                console.log(res.data);
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

<style></style>
