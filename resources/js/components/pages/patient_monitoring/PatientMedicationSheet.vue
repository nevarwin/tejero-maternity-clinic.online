<template>
    <v-container class="container-main pt-0 pl-0" fluid>
        <v-card>
            <v-simple-table dense :height="tableHeight">
                <thead>
                    <tr>
                        <th
                            v-show="editMode"
                            class="text-left"
                            style="width: 20px"
                        >
                            Edit
                        </th>
                        <th
                            v-show="editMode"
                            class="text-left"
                            style="width: 20px"
                        >
                            ID
                        </th>
                        <th>Case No</th>
                        <th>Date</th>
                        <th>Medication - Dosage</th>
                        <th>Hours To Be Given</th>
                        <th>Stat Medication</th>
                    </tr>
                </thead>
                <tbody>
                    <tr
                        v-for="(medical_sheet, index) in case_MedicalSheet"
                        :key="index"
                    >
                        <td v-show="editMode">
                            <v-icon color="success" @click="Edit(medical_sheet)"
                                >mdi-pencil</v-icon
                            >
                        </td>
                        <td v-show="editMode">{{ medical_sheet.id }}</td>
                        <td>{{ medical_sheet.case_no }}</td>
                        <td>{{ medical_sheet.date }}</td>
                        <td>{{ medical_sheet.medication_dosage }}</td>
                        <td>{{ medical_sheet.hours }}</td>
                        <td>{{ medical_sheet.stat_medication }}</td>
                    </tr>
                </tbody>
            </v-simple-table>
            <insert-dialog
                :medication_sheet="medication_sheet"
                :dialog="insertDialog"
                @closeDialog="insertDialog = false"
                :dialogSetting="insertDialogSetting"
                @toggleSave="Insert()"
            ></insert-dialog>
            <edit-dialog
                :medication_sheet="tempEditMedicalsheet"
                :dialog="editDialog"
                :dialogSetting="editDialogSetting"
                @closeDialog="editDialog = false"
                @toggleSave="Update()"
            ></edit-dialog>
            <float-action
                @toggleInsertDialog="toggleInsertDialog($event)"
                @toggleEditMode="toggleEditMode(editMode)"
                :editMode="editMode"
                :floatbtn="floatbtn"
            ></float-action>
            <snackbar :snackbar="snackbar"></snackbar>
        </v-card>
    </v-container>
</template>

<script>
import FloatAction from "../../includes/FloatAction.vue";
import PatientMedicationSheetAdd from "./PatientMedicationSheetAdd.vue";
import SnackBar from "../../includes/SnackBar.vue";
import moment from "moment";
import { mapActions, mapState } from "vuex";

export default {
    props: {
        case_data: {
            type: Object,
        },
    },
    components: {
        "float-action": FloatAction,
        "insert-dialog": PatientMedicationSheetAdd,
        "edit-dialog": PatientMedicationSheetAdd,
        snackbar: SnackBar,
    },
    data() {
        return {
            floatbtn: {
                add: true,
                edit: true,
                delete: true,
            },
            insertDialogSetting: {
                title: "Insert Medical Sheet",
                submitBtn: "Save",
            },
            editDialogSetting: {
                title: "Update Medical Sheet",
                submitBtn: "Update",
            },
            snackbar: {
                show: false,
                color: "success",
                text: null,
            },
            medication_sheet: this.getInitialMedicationSheet(),
            insertDialog: false,
            editDialog: false,
            editMode: false,
            tableHeight: window.innerHeight - 180,
            tempEditMedicalsheet: {},
        };
    },
    methods: {
        ...mapActions([]),

        getInitialMedicationSheet() {
            return {
                date: moment().format("YYYY-MM-DD"),
                case_no: this.case_data.case_no,
                medication_dosage: "",
                hours: "",
                stat_medication: "",
            };
        },

        toggleInsertDialog() {
            this.medication_sheet = this.getInitialMedicationSheet();
            this.insertDialog = true;
        },
        toggleEditMode(val) {
            this.editMode = !this.editMode;
        },
        Edit(data) {
            this.tempEditMedicalsheet = structuredClone(data);
            this.editDialog = true;
        },
        Insert() {
            axios({
                method: "post",
                url: "medication_sheet_insert",
                data: this.medication_sheet,
            })
                .then((res) => {
                    console.log(res.data);
                    this.snackbar.show = true;
                    this.snackbar.text = "Success Insert";
                    this.snackbar.color = "success";
                    this.$store.dispatch("getMedicalSheet");

                    // Reset medication_sheet to its initial state
                    this.medication_sheet = this.getInitialMedicationSheet();

                    this.insertDialog = false;
                })
                .catch((err) => {
                    console.log(err);
                });
        },
        Update() {
            axios({
                method: "post",
                url: "medication_sheet_update",
                data: this.tempEditMedicalsheet,
            })
                .then((res) => {
                    console.log(res.data);
                    this.snackbar.show = true;
                    this.snackbar.text = "Success Update";
                    this.snackbar.color = "success";
                    this.$store.dispatch("getMedicalSheet");
                    this.editDialog = false;
                })
                .catch((err) => {
                    console.log(err);
                });
        },
    },
    computed: {
        ...mapState(["case_MedicalSheet", "case_no"]),
    },
    mounted() {
        console.log(this.case_no);
    },
};
</script>
