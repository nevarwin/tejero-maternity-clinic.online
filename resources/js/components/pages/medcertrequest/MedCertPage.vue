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
        </v-data-table>
        <snackbar :snackbar="snackbar"></snackbar>
    </v-container>
</template>

<script>
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
            headers: [
                { text: "ID", value: "id" },
                { text: "Full Name", value: "full_name" },
                { text: "Doctor's Name", value: "doctors_name" },
                { text: "Contact Number", value: "contact_number" },
                { text: "Case Number", value: "case_number" },
                { text: "Description", value: "description" },
                { text: "Created At", value: "created_at" },
                { text: "Updated At", value: "updated_at" },
                { text: "Action", value: "action", sortable: false },
            ],
        };
    },
    computed: {
        ...mapState(["medcertData"]),
    },
    methods: {
        ...mapActions(["getMedCert"]),
        openInsertDialog() {
            // Logic to open insert dialog
        },
        confirmDelete(item) {
            // Logic to confirm delete
        },
    },
    created() {
        this.getMedCert();
        console.log("Created:", this.medcertData);
    },
    mounted() {
        console.log("Mounted:", this.medcertData);
    },
};
</script>
