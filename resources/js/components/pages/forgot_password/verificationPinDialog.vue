<template>
    <v-container class="container-main pt-0 pl-0" fluid>
        <v-dialog v-model="dialog" persistent max-width="1000px">
            <v-card>
                <v-card-title>
                    <v-icon color="white">mdi-lock</v-icon> Pin Number
                    Verification
                    <v-spacer></v-spacer>
                </v-card-title>
                <v-card-text>
                    <v-container class="fill-height">
                        <v-row justify="center">
                            <v-col xs="12" sm="6" md="6" lg="5" xl="4">
                                <v-img
                                    :src="imagePath"
                                    alt="My Image"
                                    contain
                                    fill-height
                                ></v-img>
                            </v-col>
                            <v-col
                                xs="12"
                                sm="6"
                                md="6"
                                lg="5"
                                xl="4"
                                class="d-flex align-center justify-center"
                            >
                                <v-card elevation="5">
                                    <v-card-title
                                        >Enter Pin Number</v-card-title
                                    >
                                    <v-card-text class="mt-3">
                                        <v-otp-input
                                            v-model="pin_number"
                                        ></v-otp-input>
                                    </v-card-text>
                                    <v-card-actions>
                                        <v-btn
                                            color="success"
                                            block
                                            dense
                                            @click="submitPin(pin_number)"
                                            >submit</v-btn
                                        >
                                    </v-card-actions>
                                </v-card>
                            </v-col>
                        </v-row>
                        <snackbar :snackbar="snackbar" />
                    </v-container>
                </v-card-text>
            </v-card>
        </v-dialog>
    </v-container>
</template>

<script>
import axios from "axios";
import myImage from "../../../../../public/images/enterPin.png";
import snackbar from "../../includes/SnackBar.vue";
import { mapActions, mapState } from "vuex";

export default {
    components: {
        snackbar: snackbar,
    },
    props: {
        id: {
            type: Number,
        },
        dialog: {
            type: Boolean,
        },
        email: {
            type: String,
        },
    },
    data: () => ({
        imagePath: myImage,
        pin_number: null,
        snackbar: {
            show: false,
            color: "success",
            text: "",
        },
    }),
    methods: {
        ...mapActions(["set_id"]),

        submitPin(pin_number) {
            axios({
                method: "POST",
                url: "api/verify_account",
                data: { email: this.email },
            }).then((res) => {
                // console.log(res.data)
                if (res.data.pin == pin_number) {
                    this.set_id(this.id);
                    this.snackbar.show = true;
                    this.snackbar.text = "PIN Verified";
                    this.snackbar.color = "success";

                    setTimeout(() => {
                        this.$router.replace("/change_password");
                    }, 2000);
                } else {
                    this.snackbar.show = true;
                    this.snackbar.text = "WRONG PIN PLEASE TRY AGAIN";
                    this.snackbar.color = "error";

                    // setTimeout(() => {
                    //     //this.$router.replace('/login')
                    //     window.location.reload();
                    // }, 2000);
                }
            });
        },
    },
    computed: {},
};
</script>

<style></style>
