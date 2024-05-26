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
                                        ref="full_name"
                                        v-model="details.full_name"
                                        label="Full Name"
                                        outlined
                                        dense
                                        :rules="[
                                            (v) =>
                                                !!v || 'Full Name is required',
                                        ]"
                                        required
                                    />
                                    <v-text-field
                                        ref="doctors_name"
                                        v-model="details.doctors_name"
                                        label="Doctors Name"
                                        outlined
                                        dense
                                        :rules="[
                                            (v) =>
                                                !!v ||
                                                'Doctors Name is required',
                                        ]"
                                        required
                                    />
                                    <v-text-field
                                        ref="contact_number"
                                        v-model="details.contact_number"
                                        label="Contact Number"
                                        outlined
                                        dense
                                        :rules="[
                                            (v) =>
                                                !!v ||
                                                'Contact Number is required',
                                        ]"
                                        required
                                    />
                                    <v-text-field
                                        ref="case_number"
                                        v-model="details.case_number"
                                        label="Case Number"
                                        outlined
                                        dense
                                        :rules="[
                                            (v) =>
                                                !!v ||
                                                'Case Number is required',
                                        ]"
                                        required
                                    />
                                    <v-textarea
                                        ref="description"
                                        v-model="details.description"
                                        label="Reason for Request"
                                        outlined
                                        dense
                                        :rules="[
                                            (v) =>
                                                !!v ||
                                                'Reason for Request is required',
                                        ]"
                                        required
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
                                    >
                                        Send request
                                    </v-btn>
                                </v-col>
                            </v-row>
                            <v-row>
                                <v-col>
                                    <v-btn
                                        dense
                                        @click="goBack"
                                        block
                                        color="primary"
                                    >
                                        Back
                                    </v-btn>
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
            details: {
                full_name: "",
                doctors_name: "",
                contact_number: "",
                case_number: "",
                description: "",
            },
            toEmail: "angelitocolina2@gmail.com",
            snackbar: {
                show: false,
                color: "success",
                text: "",
            },
        };
    },

    methods: {
        validate() {
            const requiredFields = [
                "full_name",
                "doctors_name",
                "contact_number",
                "case_number",
                "description",
            ];
            let valid = true;
            for (let field of requiredFields) {
                if (!this.details[field]) {
                    this.$refs[field].validate();
                    valid = false;
                }
            }
            return valid;
        },
        async sendEmail() {
            if (this.validate()) {
                try {
                    const response = await axios.post("api/mail/medcert", {
                        to: this.toEmail,
                        name: this.details.full_name,
                        doctors_name: this.details.doctors_name,
                        contact_number: this.details.contact_number,
                        case_number: this.details.case_number,
                        description: this.details.description,
                    });
                    this.snackbar.show = true;
                    this.snackbar.text = "Email Sent";
                    this.snackbar.color = "success";
                    this.details = {
                        full_name: "",
                        doctors_name: "",
                        contact_number: "",
                        case_number: "",
                        description: "",
                    };
                } catch (error) {
                    console.error("An error occurred:", error);
                }
            } else {
                console.log("Form is invalid");
            }
        },
        goBack() {
            this.$router.go(-1);
        },
    },
};
</script>

<style></style>
