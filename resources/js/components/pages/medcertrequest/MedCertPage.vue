<template>
    <v-container class="container-main pt-0 pl-0" fluid>
        <toolbar :toolbar="toolbar"></toolbar>
        <v-row>
            <v-col>
                <v-text-field v-model="search" label="Search" outlined dense />
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
                        <v-icon color="white" @click="closeDialog()"
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
                            @click="closeDialog()"
                            >Close</v-btn
                        >
                        <v-btn
                            color="blue darken-1"
                            text
                            :disabled="dialogBtn"
                            @click="toggleSave()"
                            >Update</v-btn
                        >
                    </v-card-actions>
                </v-card>
            </v-form>
        </v-dialog>
    </v-container>
</template>

<script>
import ToolbarComponent from "../../includes/Toolbar";
import SnackBar from "../../includes/SnackBar.vue";
import { mapState, mapActions } from "vuex";
import axios from "axios";

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
    },
    created() {
        this.getMedCert();
        axios.defaults.headers.common["X-CSRF-TOKEN"] = document
            .querySelector('meta[name="csrf-token"]')
            .getAttribute("content");
    },
};
</script>
