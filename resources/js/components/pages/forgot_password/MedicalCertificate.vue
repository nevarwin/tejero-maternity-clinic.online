<template>
    <v-app>
    <v-container fluid fill-height>
        <v-row justify="center" align="center">
            <v-col cols="12" sm="8" md="6">
                <v-card>
                    <v-card-title>
                        Request For Medical Certificate
                        <v-icon color="white">mdi-hospital-marker</v-icon>
                    </v-card-title>
                    <v-card-text>
                        <v-row>
                            <v-col>
                                <v-text-field
                                    v-model="details.full_name"
                                    label="Full Name"
                                    outlined
                                    dense
                                />
                                <v-text-field
                                    v-model="details.doctors_name"
                                    label="Doctors Name"
                                    outlined
                                    dense
                                />
                                <v-text-field
                                    v-model="details.contact_number"
                                    label="Contact Number"
                                    outlined
                                    dense
                                />
                                <v-text-field
                                    v-model="details.case_number"
                                    label="Case Number"
                                    outlined
                                    dense
                                />
                                <v-textarea
                                    v-model="details.description"
                                    label="Reason for Request"
                                    outlined
                                    dense
                                ></v-textarea>
                            </v-col>
                        </v-row>
                        <v-row>
                            <v-col>
                                <v-btn
                                    dense
                                    @click="sendEmail()"
                                    block
                                    color="success"
                                    >Send request</v-btn
                                >
                            </v-col>
                        </v-row>
                    </v-card-text>
                </v-card>
            </v-col>
        </v-row>
        <snackbar :snackbar="snackbar" />
    </v-container>
</v-app>
</template>

<script>
import axios from "axios";
import snackbar from "../../includes/SnackBar.vue";

export default {
    name: "LoginPage",
    components: {
        snackbar: snackbar,
    },
    data() {
        return {
            details: {},
            toEmail: "angelitocolina2@gmail.com",
            snackbar: {
                show: false,
                color: "success",
                text: "",
            },
        };
    },

    methods: {
        async sendEmail() {
            await axios
                .post("api/mail/medcert", {
                    to: this.toEmail,
                    name: this.details.full_name,
                    doctors_name: this.details.doctors_name,
                    contact_number: this.details.contact_number,
                    case_number: this.details.case_number,
                    description: this.details.description,
                })
                .then((response) => {
                    this.snackbar.show = true;
                    this.snackbar.text = "Email Sent";
                    this.snackbar.color = "success";
                    this.details = {};
                })
                .catch((error) => {
                    console.error("An error occurred:", error);
                });
        },
    },
};
</script>

<style></style>
