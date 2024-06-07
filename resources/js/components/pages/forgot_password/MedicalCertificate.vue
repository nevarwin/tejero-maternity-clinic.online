<template>
    <v-app>
        <v-container fluid fill-height>
            <v-row justify="center" align="center">
                <v-col cols="12" sm="8" md="6">
                    <v-card>
                        <v-card-title>
                            Request For Medical Certificate
                        </v-card-title>
                        <v-card-text>
                            <v-form ref="form" v-model="valid">
                                <v-row>
                                    <v-col>
                                        <v-text-field
                                            v-model="details.full_name"
                                            label="Full Name"
                                            outlined
                                            dense
                                            :rules="[
                                                (v) =>
                                                    !!v ||
                                                    'Full Name is required',
                                            ]"
                                            required
                                        />
                                        <v-text-field
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
                                            v-model="details.contact_number"
                                            label="Contact Number"
                                            outlined
                                            dense
                                            :rules="[
                                                (v) =>
                                                    !!v ||
                                                    'Contact Number is required',
                                                (v) =>
                                                    /^[0-9]{11}$/.test(v) ||
                                                    'Contact Number must be 11 digits and contain only numbers',
                                            ]"
                                            required
                                            maxlength="11"
                                        />
                                        <v-text-field
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
                                            @click="sendEmail"
                                            block
                                            color="success"
                                        >
                                            Send request
                                        </v-btn>
                                    </v-col>
                                </v-row>
                            </v-form>
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
    name: "RequestForm",
    components: {
        snackbar: snackbar,
    },
    data() {
        return {
            valid: false,
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
        resetForm() {
            // Reset the form fields and validation state
            this.details = {
                full_name: "",
                doctors_name: "",
                contact_number: "",
                case_number: "",
                description: "",
            };
            this.$nextTick(() => {
                this.$refs.form.resetValidation();
            });
        },
        async sendEmail() {
            if (this.$refs.form.validate()) {
                try {
                    const emailResponse = await axios.post("api/mail/medcert", {
                        to: this.toEmail,
                        name: this.details.full_name,
                        doctors_name: this.details.doctors_name,
                        contact_number: this.details.contact_number,
                        case_number: this.details.case_number,
                        description: this.details.description,
                    });

                    const dbResponse = await axios.post("api/medcert", {
                        full_name: this.details.full_name,
                        doctors_name: this.details.doctors_name,
                        contact_number: this.details.contact_number,
                        case_number: this.details.case_number,
                        description: this.details.description,
                    });

                    this.snackbar.show = true;
                    this.snackbar.text = "Email Sent and Request Saved";
                    this.snackbar.color = "success";
                    this.resetForm();
                } catch (error) {
                    console.error("An error occurred:", error);
                    this.snackbar.show = true;
                    this.snackbar.text = "Failed to send request";
                    this.snackbar.color = "error";
                }
            } else {
                this.snackbar.show = true;
                this.snackbar.text = "Form is invalid";
                this.snackbar.color = "error";
            }
        },
        goBack() {
            this.$router.go(-1);
        },
    },
};
</script>
