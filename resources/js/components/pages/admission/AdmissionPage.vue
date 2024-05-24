<template>
    <v-container class="container-main pt-0 pl-0" fluid>
        <toolbar :toolbar="toolbar"></toolbar>
        <v-row>
            <v-col>
                <v-text-field v-model="search" label="search" outlined dense />
            </v-col>
            <v-col>
                <v-btn color="success" dense @click="toggleInsertDialog($event)"
                    >Add</v-btn
                >
            </v-col>
        </v-row>
        <v-data-table
            :headers="headers"
            :items="admmisionData"
            :search="search"
            :items-per-page="10"
            class="elevation-1"
        >
          
            <template v-slot:item.action="{ item }">
                <v-icon @click="Edit(item)">mdi-pencil</v-icon>
                <v-tooltip bottom>
                            <template v-slot:activator="{ on }">
                                <v-icon
                                    color="error"
                                    @click="toggleDelete(item.id,item)"
                                    v-on="on"
                                    >mdi-bed-empty</v-icon
                                >
                            </template>
                            <span>Discharge Patient</span>
                            <!-- Tooltip content -->
                        </v-tooltip>
            </template>
        </v-data-table>
        <insert-dialog
            :data="tempData"
            :dialog="insertDialog"
            :dialogSetting="insertDialogSetting"
            @closeDialog="
                tempData = {};
                insertDialog = false;
            "
            @toggleSave="Insert()"
        ></insert-dialog>
        <edit-dialog
            :data="tempEditData"
            :dialog="editDialog"
            :dialogSetting="editDialogSetting"
            @closeDialog="
                tempEditData = {};
                editDialog = false;
            "
            @toggleSave="Update()"
        ></edit-dialog>

        <agree-dialog
            :agree="agree"
            @closeAgree="closeAgree()"
            @toggleAgree="toggleAgree()"
        ></agree-dialog>
        <snackbar :snackbar="snackbar"></snackbar>
        <!-- NOTE : Float Action -->
        <float-action
            @toggleDelete="toggleDelete"
            @toggleEditMode="toggleEditMode(editMode)"
            :editMode="editMode"
            :selectedRowsCnt="selectedRows.length"
        ></float-action>
    </v-container>
</template>

<script>
import ToolbarComponent from "../../includes/Toolbar";
// import FloatAction from '../../includes/FloatAction.vue'
import AgreeDialog from "../../includes/AgreeDialog.vue";
import AdmissionDialog from "./AdmissionDialog.vue";
import SnackBar from "../../includes/SnackBar.vue";
import auditRecord from "../../includes/auditFunction";

import { mapActions, mapState } from "vuex";
import moment from "moment";
export default {
    name: "PatientRegistration",
    components: {
        toolbar: ToolbarComponent,
        // 'float-action':FloatAction,
        "agree-dialog": AgreeDialog,
        "insert-dialog": AdmissionDialog,
        "edit-dialog": AdmissionDialog,
        snackbar: SnackBar,
    },
    data() {
        return {
            toolbar: {
                title: "Admission",
                subTitle: "Register Case",
            },

            agree: {
                dialog: false,
                title: "Patient Discharge",
                text: "Are you sure you want to discharge?",
            },
            snackbar: {
                show: false,
                color: "success",
                text: null,
            },
            insertDialogSetting: {
                title: "Insert Room Info",
                submitBtn: "Save",
            },
            editDialogSetting: {
                title: "Update Patient Info",
                submitBtn: "Update",
            },
            search: "",
            tableHeight: window.innerHeight - 180,
            insertDialog: false,
            editDialog: false,
            deleteDialog: false,
            editMode: false,
            selectedRows: [],
            tempName: null,
            tempId: null,
            dialogBtn: false,
            bed_id:null,
            tempData: {
                // case_no: moment().format("YYYY")+"-"+Math.floor(Math.random() * 1000).toString().padStart(3, '0')+'-'+Math.floor(Math.random() * 10000).toString().padStart(4, '0'),
                patient_id: null,
                doctor_id: null,
                room_id: null,
                bed_name: null,
                bed_id: null,
            },
            tempEditData: {},
            headers: [
                { text: "ID", value: "id" },
                { text: "Case No", value: "case_no" },
                { text: "Patient", value: "patient_name" },
                { text: "Doctor", value: "doctor_name" },
                { text: "Room", value: "room_name" },
                { text: "Bed", value: "bed_name" },
                { text: "Relationship", value: "relationship" },
                { text: "Guardian", value: "husband_name" },
                { text: "Action", value: "action", sortable: false },
            ],
        };
    },
    methods: {
        ...mapActions(["getAdmision"]),
        closeAgree() {
            this.agree.dialog = false;
        },
        toggleAgree() {
            this.Delete();
        },
        toggleInsertDialog() {
            (this.tempData = {}), (this.insertDialog = true);
        },
        toggleEditMode(val) {
            (this.tempData = {
                room_type: null,
                name: null,
                beds: [],
            }),
                (this.editMode = !this.editMode);
        },
        toggleDelete(id,val) {
            this.bed_id = val.bed_id
            this.tempId = id;
            this.agree.dialog = true;
        },
        Edit(data) {
            console.log(data);
            this.tempEditData = structuredClone(data);
            this.editDialog = true;
        },
        Insert() {
            this.loadMore = true;
            axios({
                method: "post",
                url: "case_insert",
                data: this.tempData,
            })
                .then((res) => {
                    console.log(res.data);
                    if (res.data == "success") {
                        this.snackbar.show = true;
                        this.snackbar.text = "Success Insert";
                        this.snackbar.color = "success";
                        axios({
                            method: "post",
                            url: "update_bed",
                            data: { id: this.tempData.bed_name, vacant:"yes" },
                        });
                        // this.$refs.Insert.resetValidation()
                        this.getAdmision();
                        let audit = {
                            action: "New Patient",
                            description: "New Patient Admit",
                            name: this.loggedInUser.name,
                            position: this.loggedInUser.access,
                            drawerLink: "Admission",
                            date: moment().format("YYYY-MM-DD hh:mm:ss"),
                        };
                        auditRecord.Insert(audit);
                        this.insertDialog = false;
                    } else if (res.data == 1) {
                        this.snackbar.show = true;
                        this.snackbar.text = "Case Number Already Exist";
                        this.snackbar.color = "error";
                    }
                })
                .catch((err) => {
                    console.log(err);
                });
        },
        Update() {
            this.loadMore = true;
            axios({
                method: "post",
                url: "case_update",
                data: this.tempEditData,
            })
                .then((res) => {
                    console.log(res.data);
                    this.snackbar.show = true;
                    this.snackbar.text = "Success Update";
                    this.snackbar.color = "success";
                    // this.$refs.Insert.resetValidation()
                    this.getAdmision();
                    let audit = {
                        action: "Update Patient",
                        description: "Patient Info Updated",
                        name: this.loggedInUser.name,
                        position: this.loggedInUser.access,
                        drawerLink: "Admission",
                        date: moment().format("YYYY-MM-DD hh:mm:ss"),
                    };
                    auditRecord.Insert(audit);
                    this.editDialog = false;
                })
                .catch((err) => {
                    console.log(err);
                });
        },
        Delete() {
            axios({
                method: "post",
                url: "case_delete",
                data: {
                    id: this.tempId,
                },
            })
                .then((res) => {
                    this.getAdmision();
                    this.snackbar.show = true;
                    this.snackbar.text = "Success";
                    this.snackbar.color = "success";
                    this.closeAgree();
                    axios({
                            method: "post",
                            url: "update_bed",
                            data: { id: this.bed_id, vacant:"no"},
                        });
                    let audit = {
                        action: "Discharge Patient",
                        description: "Patient Discharged",
                        name: this.loggedInUser.name,
                        position: this.loggedInUser.access,
                        drawerLink: "Admission",
                        date: moment().format("YYYY-MM-DD"),
                    };
                    auditRecord.Insert(audit);
                })
                .catch((err) => {
                    console.log(err);
                });
        },
    },
    computed: {
        // caseDataSearch() {
        //     const newSearch = this.search.toLowerCase();
        //     return this.admmisionData.filter((item) => {
        //         if (item.deleted_at) {
        //             // Exclude the item if deleted_at is not null
        //             return false;
        //         }
        //         return (
        //             item.case_no.toLowerCase().includes(newSearch) ||
        //             item.patient_name.toLowerCase().includes(newSearch) ||
        //             item.doctor_name.toLowerCase().includes(newSearch) ||
        //             item.room_name.toLowerCase().includes(newSearch) ||
        //             item.bed_name.toLowerCase().includes(newSearch) ||
        //             item.relationship.toLowerCase().includes(newSearch) ||
        //             item.husband_name.toLowerCase().includes(newSearch)
        //             // Add more fields to search if needed
        //         );
        //     });
        // },
        ...mapState([
            "rules",
            "admmisionData",
            "selectRoomType",
            "loggedInUser",
        ]),
    },
    watch: {},

    mounted() {
        this.getAdmision();
        this.tempData.case_no = moment().format("YYYY")+"-"+Math.floor(Math.random() * 1000).toString().padStart(3, '0')+'-'+Math.floor(Math.random() * 10000).toString().padStart(4, '0');
    },
};
</script>

<style></style>
