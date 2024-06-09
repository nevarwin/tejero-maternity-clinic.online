<template>
    <v-container class="container-main pt-0 pl-0" fluid>
        <toolbar :toolbar="toolbar"></toolbar>
        <v-row>
            <v-col>
                <v-text-field v-model="search" label="Search" outlined dense />
            </v-col>
            <v-col>
                <v-btn color="success" dense @click="toggleInsertDialog"
                    >Add</v-btn
                >
            </v-col>
        </v-row>
        <v-data-table
            :headers="headers"
            :items="medcertData"
            :search="search"
            :items-per-page="10"
            class="elevation-1"
        >
            <template v-slot:item.action="{ item }">
                <v-tooltip bottom>
                    <template v-slot:activator="{ on, attrs }">
                        <v-btn
                            icon
                            @click="EditButton(item)"
                            color="primary"
                            v-bind="attrs"
                            v-on="on"
                        >
                            <v-icon>mdi-pencil</v-icon>
                        </v-btn>
                    </template>
                    <span>Edit</span>
                </v-tooltip>
            </template>
        </v-data-table>
        <snackbar :snackbar="snackbar"></snackbar>

        <v-dialog v-model="dialog" persistent max-width="500px">
            <v-form
                id="Insert"
                ref="Insert"
                @submit.prevent="toggleSave"
                enctype="multipart/form-data"
            >
                <v-card>
                    <v-card-title>
                        <span>Update Med Cert Status</span>
                        <v-spacer></v-spacer>
                        <v-icon color="white" @click="closeDialog"
                            >mdi-close</v-icon
                        >
                    </v-card-title>
                    <v-card-text>
                        <v-container>
                            <v-row>
                                <v-col>
                                    <v-select
                                        outlined
                                        label="Status"
                                        v-model="tempstatus"
                                        :items="[
                                            'Pending',
                                            'Processed',
                                            'Claimed',
                                        ]"
                                        name="status"
                                        dense
                                    ></v-select>
                                </v-col>
                            </v-row>
                        </v-container>
                    </v-card-text>
                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn
                            color="blue darken-1"
                            text
                            :disabled="dialogBtn"
                            @click="closeDialog"
                            >Close</v-btn
                        >
                        <v-btn
                            color="blue darken-1"
                            text
                            :disabled="dialogBtn"
                            @click="toggleSave"
                            >Update</v-btn
                        >
                    </v-card-actions>
                </v-card>
            </v-form>
        </v-dialog>

        <v-overlay :value="loadMore" overlay>
            <v-progress-circular indeterminate size="64"></v-progress-circular>
        </v-overlay>
        <v-dialog v-model="insertDialog" persistent max-width="500px">
            <v-form
                ref="form"
                v-model="valid"
                @submit.prevent="sendEmail"
                enctype="multipart/form-data"
            >
                <v-card>
                    <v-card-title>
                        <span>Request For Medical Certificate</span>
                        <v-spacer></v-spacer>
                        <v-icon color="white" @click="insertDialog = false"
                            >mdi-close</v-icon
                        >
                    </v-card-title>
                    <v-card-text>
                        <v-container fluid fill-height>
                            <v-row>
                                <v-col cols="12">
                                    <v-text-field
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
                                </v-col>
                                <v-col cols="12">
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
                                </v-col>
                                <v-col cols="12">
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
                                </v-col>
                                <v-col cols="12">
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
                                </v-col>
                                <v-col cols="12">
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
                        </v-container>
                    </v-card-text>
                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn
                            color="blue darken-1"
                            text
                            @click="insertDialog = false"
                            >Close</v-btn
                        >
                        <v-btn color="success" text type="submit"
                            >Send request</v-btn
                        >
                    </v-card-actions>
                </v-card>
            </v-form>
            <snackbar :snackbar="snackbar" />
        </v-dialog>
    </v-container>
</template>

<script>
import axios from "axios";
import ToolbarComponent from "../../includes/Toolbar";
import SnackBar from "../../includes/SnackBar.vue";
import { mapState, mapActions } from "vuex";

export default {
    name: "MedCert",
    components: {
        toolbar: ToolbarComponent,
        snackbar: SnackBar,
    },
    data() {
        return {
            editDialogSetting: {
                title: "Update Med Cert Status",
                submitBtn: "Update",
            },
            toolbar: {
                title: "Medical Certificate",
                subTitle: "Request List",
            },
            snackbar: {
                show: false,
                color: "success",
                text: null,
            },
            search: "",
            tempEditData: "",
            dialog: false,
            insertDialog: false,
            loadMore: false,
            dialogBtn: false,
            headers: [
                { text: "Full Name", value: "full_name" },
                { text: "Doctor's Name", value: "doctors_name" },
                { text: "Contact Number", value: "contact_number" },
                { text: "Case Number", value: "case_number" },
                { text: "Description", value: "description" },
                { text: "Created At", value: "created_at" },
                { text: "Updated At", value: "updated_at" },
                { text: "Status", value: "status" },
                { text: "Action", value: "action", sortable: false },
            ],
            tempid: null,
            tempstatus: null,
            valid: false,
            details: {
                full_name: "",
                doctors_name: "",
                contact_number: "",
                case_number: "",
                description: "",
            },
            toEmail: "angelitocolina2@gmail.com",
        };
    },
    computed: {
        ...mapState(["medcertData"]),
    },
    methods: {
        ...mapActions(["getMedCert"]),
        EditButton(item) {
            this.tempstatus = item.status;
            this.tempid = item.id;
            this.dialog = true;
        },

        UpdateStatus() {
            axios
                .post("/medcert_update", {
                    id: this.tempid,
                    status: this.tempstatus,
                })
                .then((res) => {
                    this.snackbar.show = true;
                    this.snackbar.text = "Success Update";
                    this.snackbar.color = "success";
                    this.getMedCert();
                    this.closeDialog();
                })
                .catch((err) => {
                    console.error(err);
                    this.snackbar.show = true;
                    this.snackbar.text = "Update Failed";
                    this.snackbar.color = "error";
                });
        },

        closeDialog() {
            this.dialog = false;
        },

        toggleSave() {
            this.UpdateStatus();
        },

        toggleInsertDialog() {
            this.insertDialog = !this.insertDialog;
        },

        async sendEmail() {
            if (this.$refs.form.validate()) {
                this.loadMore = true;
                try {
                    await axios.post("api/mail/medcert", {
                        to: this.toEmail,
                        name: this.details.full_name,
                        doctors_name: this.details.doctors_name,
                        contact_number: this.details.contact_number,
                        case_number: this.details.case_number,
                        description: this.details.description,
                    });

                    await axios.post("api/medcert", {
                        full_name: this.details.full_name,
                        doctors_name: this.details.doctors_name,
                        contact_number: this.details.contact_number,
                        case_number: this.details.case_number,
                        description: this.details.description,
                    });

                    this.snackbar.show = true;
                    this.snackbar.text = "Email Sent and Request Saved";
                    this.snackbar.color = "success";
                    this.loadMore = false;
                    this.getMedCert();
                    this.resetForm();
                    this.toggleInsertDialog(); // Close the insert dialog
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

        resetForm() {
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
    },
    created() {
        this.getMedCert();
        axios.defaults.headers.common["X-CSRF-TOKEN"] = document
            .querySelector('meta[name="csrf-token"]')
            .getAttribute("content");
    },
};
</script>
