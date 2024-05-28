<template>
    <v-container class="container-main pt-0 pl-0" fluid>
        <v-dialog v-model="dialog" persistent max-width="800px">
            <v-form
                id="Insert"
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
                            <v-row style="margin-top: 0px !important">
                                <v-col lg="12" xl="12">
                                    <v-row>
                                        <v-col cols="3">
                                            <v-select
                                                label="Type of patient"
                                                v-model="
                                                    patient.type_of_patient
                                                "
                                                :items="typeOfPatient"
                                                item-text="name"
                                                item-value="id"
                                                dense
                                                persistent-placeholder
                                                outlined
                                                name="password"
                                            ></v-select>
                                        </v-col>
                                        <template
                                            v-if="patient.type_of_patient == 2"
                                        >
                                            <v-col cols="3">
                                                <v-menu
                                                    v-model="lmpMenu"
                                                    :close-on-content-click="
                                                        false
                                                    "
                                                    transition="scale-transition"
                                                    offset-y
                                                    max-width="290px"
                                                    min-width="auto"
                                                >
                                                    <template
                                                        v-slot:activator="{
                                                            on,
                                                            attrs,
                                                        }"
                                                    >
                                                        <v-text-field
                                                            v-model="
                                                                patient.lmp
                                                            "
                                                            label="LMP"
                                                            class="required uppercase"
                                                            persistent-placeholder
                                                            prepend-inner-icon="mdi-calendar"
                                                            readonly
                                                            outlined
                                                            :rules="
                                                                rules.required
                                                            "
                                                            dense
                                                            v-bind="attrs"
                                                            v-on="on"
                                                        ></v-text-field>
                                                    </template>
                                                    <v-date-picker
                                                        v-model="patient.lmp"
                                                        no-title
                                                        :max="maxDate"
                                                        @input="
                                                            lmpMenu = false;
                                                            EDCCompute();
                                                            AOGCompute();
                                                        "
                                                    ></v-date-picker>
                                                </v-menu>
                                            </v-col>
                                            <v-col cols="3">
                                                <v-text-field
                                                    label="EDC"
                                                    v-model="patient.edc"
                                                    class="required uppercase"
                                                    dense
                                                    readonly
                                                    persistent-placeholder
                                                    :rules="rules.required"
                                                    outlined
                                                    name="name"
                                                    @click="EDCCompute()"
                                                ></v-text-field>
                                            </v-col>
                                            <v-col cols="3">
                                                <v-text-field
                                                    label="AOG(weeks)"
                                                    v-model="patient.aog"
                                                    class="required uppercase"
                                                    dense
                                                    persistent-placeholder
                                                    outlined
                                                    name="name"
                                                ></v-text-field>
                                            </v-col>
                                        </template>
                                    </v-row>
                                </v-col>
                                <v-col cols="12" lg="6" md="12" sm="12">
                                    <v-row>
                                        <v-col cols="12">
                                            <v-text-field
                                                label="Name"
                                                v-model="patient.name"
                                                class="required uppercase"
                                                dense
                                                :rules="rules.required"
                                                persistent-placeholder
                                                outlined
                                                name="name"
                                            ></v-text-field>
                                        </v-col>
                                        <template
                                            v-if="patient.type_of_patient == 2"
                                        >
                                            <v-col cols="4">
                                                <v-select
                                                    label="Religion"
                                                    v-model="
                                                        patient.religion_id
                                                    "
                                                    :items="religionData"
                                                    item-value="id"
                                                    item-text="name"
                                                    class="required"
                                                    dense
                                                    :rules="rules.required"
                                                    persistent-placeholder
                                                    outlined
                                                    name="religion"
                                                ></v-select>
                                            </v-col>
                                            <v-col cols="4">
                                                <v-text-field
                                                    label="Nationality"
                                                    v-model="
                                                        patient.nationality
                                                    "
                                                    class="required uppercase"
                                                    dense
                                                    :rules="rules.required"
                                                    persistent-placeholder
                                                    outlined
                                                    name="nationality"
                                                ></v-text-field>
                                            </v-col>
                                        </template>
                                        <v-col cols="4">
                                            <v-text-field
                                                label="Birth Place"
                                                v-model="patient.birth_place"
                                                class="required uppercase"
                                                dense
                                                :rules="rules.required"
                                                persistent-placeholder
                                                outlined
                                                name="birth_place"
                                            ></v-text-field>
                                        </v-col>
                                        <template
                                            v-if="patient.type_of_patient == 2"
                                        >
                                            <v-col cols="12">
                                                <v-text-field
                                                    label="Occupation"
                                                    v-model="patient.occupation"
                                                    class="required uppercase"
                                                    dense
                                                    :rules="rules.required"
                                                    persistent-placeholder
                                                    outlined
                                                    name="name"
                                                ></v-text-field>
                                            </v-col>
                                        </template>
                                        <v-col cols="4">
                                            <v-select
                                                label="Sex"
                                                v-model="patient.sex_id"
                                                :items="selectSex"
                                                item-text="name"
                                                item-value="id"
                                                class="required"
                                                dense
                                                :rules="rules.required"
                                                persistent-placeholder
                                                outlined
                                                name="sex"
                                            ></v-select>
                                        </v-col>
                                        <template
                                            v-if="patient.type_of_patient == 2"
                                        >
                                            <v-col cols="8">
                                                <v-text-field
                                                    label="Contact"
                                                    v-model="patient.contact_no"
                                                    class="required"
                                                    dense
                                                    @keypress="
                                                        filterNumericInput
                                                    "
                                                    :rules="rules.contactNo"
                                                    persistent-placeholder
                                                    outlined
                                                    name="contact"
                                                    maxlength="11"
                                                ></v-text-field>
                                            </v-col>
                                        </template>
                                    </v-row>
                                </v-col>
                                <v-col cols="12" lg="6" md="12" sm="12">
                                    <v-row>
                                        <v-col cols="5">
                                            <v-menu
                                                v-model="birthdayMenu"
                                                :close-on-content-click="false"
                                                transition="scale-transition"
                                                offset-y
                                                max-width="290px"
                                                min-width="auto"
                                            >
                                                <template
                                                    v-slot:activator="{
                                                        on,
                                                        attrs,
                                                    }"
                                                >
                                                    <v-text-field
                                                        v-model="
                                                            patient.birthday
                                                        "
                                                        label="Birth Day"
                                                        class="required uppercase"
                                                        persistent-placeholder
                                                        prepend-inner-icon="mdi-calendar"
                                                        readonly
                                                        outlined
                                                        :rules="rules.required"
                                                        dense
                                                        v-bind="attrs"
                                                        v-on="on"
                                                    ></v-text-field>
                                                </template>
                                                <v-date-picker
                                                    v-model="patient.birthday"
                                                    no-title
                                                    @input="
                                                        birthdayMenu = false;
                                                        AgeCompute();
                                                    "
                                                ></v-date-picker>
                                            </v-menu>
                                        </v-col>
                                        <v-col cols="3">
                                            <v-text-field
                                                label="Age"
                                                v-model="patient.age"
                                                class="required uppercase"
                                                dense
                                                :rules="rules.required"
                                                persistent-placeholder
                                                outlined
                                                readonly
                                                name="name"
                                                @click="AgeCompute()"
                                            ></v-text-field>
                                        </v-col>
                                        <v-col cols="4">
                                            <v-text-field
                                                label="Weight(Kg)"
                                                v-model="patient.weight"
                                                class="required"
                                                dense
                                                :rules="rules.required"
                                                persistent-placeholder
                                                outlined
                                                name="password"
                                            ></v-text-field>
                                        </v-col>
                                        <template
                                            v-if="patient.type_of_patient == 2"
                                        >
                                            <v-col cols="6">
                                                <v-select
                                                    label="Civil Status"
                                                    v-model="
                                                        patient.civil_status_id
                                                    "
                                                    :items="selectCivil"
                                                    item-text="name"
                                                    item-value="id"
                                                    class="required"
                                                    dense
                                                    :rules="rules.required"
                                                    persistent-placeholder
                                                    outlined
                                                    name="password"
                                                ></v-select>
                                            </v-col>
                                            <v-col cols="6">
                                                <v-select
                                                    label="Province"
                                                    v-model="
                                                        patient.province_id
                                                    "
                                                    :items="provinceMaster"
                                                    item-text="name"
                                                    item-value="id"
                                                    dense
                                                    persistent-placeholder
                                                    outlined
                                                    name="password"
                                                ></v-select>
                                            </v-col>

                                            <v-col cols="6">
                                                <v-autocomplete
                                                    label="Municipal"
                                                    v-model="
                                                        patient.municipality_id
                                                    "
                                                    :items="selectMunicipal"
                                                    item-text="name"
                                                    item-value="id"
                                                    dense
                                                    persistent-placeholder
                                                    outlined
                                                    name="password"
                                                ></v-autocomplete>
                                            </v-col>
                                            <v-col cols="6">
                                                <v-autocomplete
                                                    label="Barangay"
                                                    v-model="
                                                        patient.barangay_id
                                                    "
                                                    :items="selectBarangay"
                                                    item-text="name"
                                                    item-value="id"
                                                    dense
                                                    persistent-placeholder
                                                    outlined
                                                    name="password"
                                                ></v-autocomplete>
                                            </v-col>
                                            <v-col cols="6">
                                                <v-text-field
                                                    label="Region"
                                                    v-model="patient.region"
                                                    dense
                                                    class="uppercase"
                                                    persistent-placeholder
                                                    outlined
                                                    name="password"
                                                ></v-text-field>
                                            </v-col>
                                            <v-col cols="6">
                                                <v-text-field
                                                    label="House Address"
                                                    v-model="
                                                        patient.house_address
                                                    "
                                                    class="required uppercase"
                                                    dense
                                                    :rules="rules.required"
                                                    persistent-placeholder
                                                    outlined
                                                    name="name"
                                                ></v-text-field>
                                            </v-col>
                                        </template>
                                    </v-row>
                                </v-col>
                                <template v-if="patient.type_of_patient == 2">
                                    <v-col cols="12" lg="12" md="12" sm="12">
                                        <v-row>
                                            <v-col cols="12">
                                                <v-text-field
                                                    label="Complete Address"
                                                    v-model="tempAddressTxt"
                                                    class="required uppercase"
                                                    dense
                                                    :rules="rules.required"
                                                    persistent-placeholder
                                                    outlined
                                                    readonly
                                                    disabled
                                                    name="name"
                                                ></v-text-field>
                                            </v-col>
                                        </v-row>
                                    </v-col>
                                </template>
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
        patient: {
            type: Object,
        },
        dialog: {
            type: Boolean,
        },
        dialogSetting: {
            type: Object,
        },
    },
    data: () => ({
        snackbarTimeout: 3000,
        birthdayMenu: false,
        dialogBtn: false,
        lmpMenu: false,
        typeOfPatient: [
            { id: 1, name: "Baby" },
            { id: 2, name: "Mother" },
        ],
        maxDate: moment().format("YYYY-MM-DD"),
    }),
    methods: {
        refresh() {
            this.snackbar.show = false;
            location.reload();
        },
        closeDialog() {
            this.$emit("closeDialog");
        },
        toggleSave() {
            if (this.$refs.Insert.validate()) {
                this.$emit("toggleSave");
            }
        },
        AgeCompute() {
            if (this.patient.birthday) {
                let age = moment(this.patient.birthday, "YYYY-MM-DD")
                    .month(0)
                    .from(moment().month(0));
                if (age.split(" ")[1] == "year") {
                    this.patient.age = 1;
                } else if (
                    !["day", "days", "month", "months"].includes(
                        age.split(" ")[1]
                    )
                ) {
                    this.patient.age = age ? age.split(" ")[0] : "";
                } else {
                    this.patient.age = null;
                    console.log("no age");
                }
            }
        },
        filterNumericInput(event) {
            // Remove non-numeric characters
            // if(val != null){
            //     val = val.replace(/[^0-9]/g, '');
            // }

            const charCode = event.charCode;

            // Allow only numeric characters
            if (charCode < 48 || charCode > 57) {
                event.preventDefault();
            }
        },
        EDCCompute() {
            console.log(this.patient.lmp);
            if (this.patient.lmp) {
                let lmp = moment(this.patient.lmp, "YYYY-MM-DD")
                    .add(9, "M")
                    .add(7, "d")
                    .format("YYYY-MM-DD");
                console.log(lmp);
                this.patient.edc = lmp;
            }
        },
        AOGCompute() {
            if (this.patient.lmp) {
                let aog = moment.duration(
                    moment().diff(moment(this.patient.lmp, "YYYY-MM-DD"))
                );
                // let aog = moment(this.patient.lmp, "YYYY-MM-DD").add(9,'M').add(7,'d').format('YYYY-MM-DD')
                console.log(aog.asWeeks());
                this.patient.aog = Math.floor(aog.asWeeks());
            }
        },
    },
    computed: {
        ...mapState([
            "provinceMaster",
            "rules",
            "selectSex",
            "selectCivil",
            "religionData",
        ]),
        selectMunicipal() {
            let arr = [];
            this.provinceMaster.filter((data) => {
                if (data.id === this.patient.province_id) {
                    arr = data.municipality;
                }
            });
            return arr;
        },
        selectBarangay() {
            let arr = [];
            this.provinceMaster.filter((data) => {
                if (data.id === this.patient.province_id) {
                    data.municipality.filter((municipal) => {
                        if (municipal.id === this.patient.municipality_id) {
                            arr = municipal.barangay;
                        }
                    });
                }
            });
            return arr;
        },
        tempAddressTxt() {
            let province = "";
            let municipality = "";
            let barangay = "";
            let region = "";
            let completeAddress = [this.patient.house_address];
            this.provinceMaster.filter((data) => {
                if (data.id === this.patient.province_id) {
                    province = data.name;
                    completeAddress[3] = data.name;
                    data.municipality.filter((municipal) => {
                        if (municipal.id === this.patient.municipality_id) {
                            municipality = municipal.name;
                            completeAddress[2] = municipal.name;
                            municipal.barangay.filter((bar) => {
                                if (bar.id === this.patient.barangay_id) {
                                    barangay = bar.name;
                                    completeAddress[1] = bar.name;
                                }
                            });
                        }
                    });
                }
            });
            if (!this.patient.house_address) {
                completeAddress.shift();
            }
            if (this.patient.region) {
                completeAddress.push(this.patient.region);
            }
            console.log("totok", completeAddress);
            this.patient.address_txt = completeAddress.join(" ");
            return completeAddress.join(" ");
            // return this.tempHouse + ' ' + barangay + ' ' + municipality + ' ' + province
        },
    },
    watch: {
        dialog(val) {
            if (!val) {
                this.$refs.Insert.resetValidation();
            }
        },
    },
};
</script>

<style></style>
