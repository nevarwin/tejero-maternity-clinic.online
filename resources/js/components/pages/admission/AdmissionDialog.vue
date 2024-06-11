<template>
    <v-container class="container-main pt-0 pl-0" fluid>
        <v-dialog
            v-model="dialog"
            persistent
            max-width="500px"
            @close="resetForm"
        >
            <v-form
                ref="Insert"
                @submit.prevent="toggleSave"
                enctype="multipart/form-data"
            >
                <v-card>
                    <v-card-title>
                        <span>{{ dialogSetting.title }}</span>
                        <v-spacer></v-spacer>
                        <v-icon color="white" @click="closeDialog()"
                            >mdi-close</v-icon
                        >
                    </v-card-title>
                    <v-card-text>
                        <v-container>
                            <v-row>
                                <v-col cols="12">
                                    <v-text-field
                                        label="Case No"
                                        v-model="caseNumber"
                                        class="required uppercase"
                                        dense
                                        :rules="rules.required"
                                        persistent-placeholder
                                        outlined
                                        :readonly="data.id"
                                        name="case_no"
                                        readonly
                                    ></v-text-field>
                                </v-col>
                                <v-col cols="12">
                                    <v-autocomplete
                                        label="Patient"
                                        v-model="data.patient_id"
                                        :items="patientData"
                                        item-value="id"
                                        item-text="name"
                                        class="required"
                                        dense
                                        :rules="rules.required"
                                        persistent-placeholder
                                        outlined
                                        name="patient_id"
                                    ></v-autocomplete>
                                </v-col>
                                <v-col cols="12">
                                    <v-autocomplete
                                        label="Doctor"
                                        v-model="data.doctor_id"
                                        :items="doctorData"
                                        item-value="id"
                                        item-text="name"
                                        class="required"
                                        dense
                                        :rules="rules.required"
                                        persistent-placeholder
                                        outlined
                                        name="doctor_id"
                                    ></v-autocomplete>
                                </v-col>
                                <v-col cols="6">
                                    <v-autocomplete
                                        label="Room"
                                        v-model="data.room_id"
                                        :items="roomData"
                                        item-value="id"
                                        item-text="name"
                                        class="required"
                                        dense
                                        :rules="rules.required"
                                        outlined
                                        name="room_id"
                                    ></v-autocomplete>
                                </v-col>
                                <v-col cols="6">
                                    <v-autocomplete
                                        label="Bed"
                                        v-model="data.bed_id"
                                        :items="selectBed"
                                        :item-text="
                                            (elem) => {
                                                return elem.vacant == 'yes'
                                                    ? 'Occuppied'
                                                    : 'Vacant' +
                                                          ' : ' +
                                                          elem.name;
                                            }
                                        "
                                        :item-value="
                                            (el) => {
                                                return el.id;
                                            }
                                        "
                                        class="required"
                                        dense
                                        :rules="rules.required"
                                        outlined
                                        name="bed_id"
                                    ></v-autocomplete>
                                </v-col>
                                <v-col cols="12">
                                    <v-text-field
                                        label="Guardian Relationship"
                                        v-model="data.relationship"
                                        class="required uppercase"
                                        dense
                                        :rules="rules.required"
                                        persistent-placeholder
                                        outlined
                                        name="relationship"
                                    ></v-text-field>
                                </v-col>
                                <v-col cols="12">
                                    <v-text-field
                                        label="Guardian Name"
                                        v-model="data.husband_name"
                                        class="required uppercase"
                                        dense
                                        :rules="rules.required"
                                        persistent-placeholder
                                        outlined
                                        name="husband_name"
                                    ></v-text-field>
                                </v-col>
                            </v-row>
                        </v-container>
                    </v-card-text>
                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn color="blue darken-1" text @click="closeDialog()"
                            >Close</v-btn
                        >
                        <v-btn
                            color="blue darken-1"
                            text
                            :disabled="dialogBtn"
                            @click="toggleSave()"
                            >{{ dialogSetting.submitBtn }}</v-btn
                        >
                    </v-card-actions>
                </v-card>
            </v-form>
        </v-dialog>
    </v-container>
</template>

<script>
import moment from "moment";
import { mapActions, mapState } from "vuex";

export default {
    props: {
        data: Object,
        dialog: Boolean,
        dialogSetting: Object,
    },
    data() {
        return {
            snackbarTimeout: 3000,
            dialogBtn: false,
            caseNumber: "",
        };
    },
    methods: {
        ...mapActions(["getPatient", "getDoctor", "getRoom"]),
        refresh() {
            this.snackbar.show = false;
            location.reload();
        },
        closeDialog() {
            this.$emit("closeDialog");
        },
        toggleSave() {
            this.data.case_no = this.caseNumber;
            if (this.$refs.Insert.validate()) {
                this.$emit("toggleSave");
            }
        },
        resetForm() {
            this.$refs.Insert.resetValidation();
        },
        generateCaseNumber() {
            return (
                moment().format("YYYY") +
                "-" +
                Math.floor(Math.random() * 1000)
                    .toString()
                    .padStart(3, "0") +
                "-" +
                Math.floor(Math.random() * 10000)
                    .toString()
                    .padStart(4, "0")
            );
        },
    },
    computed: {
        ...mapState([
            "rules",
            "selectRoomType",
            "patientData",
            "doctorData",
            "roomData",
        ]),
        selectBed() {
            if (!this.data.room_id) return [];
            const room = this.roomData.find(
                (room) => room.id === this.data.room_id
            );
            if (!room) return [];
            return room.beds.filter((bed) => bed.vacant !== "yes");
        },
    },
    watch: {
        dialog(val) {
            if (val) {
                this.caseNumber = this.generateCaseNumber();
                this.getPatient();
                this.getDoctor();
                this.getRoom();
            }
        },
    },
};
</script>

<style></style>
