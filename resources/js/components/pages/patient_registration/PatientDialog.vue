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
                                                :disabled="
                                                    patient.type_of_patient ===
                                                    2
                                                "
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
                                                    v-model="selectedProvince"
                                                    :items="provinceList"
                                                    item-text="name"
                                                    item-value="prov_code"
                                                    dense
                                                    outlined
                                                    @change="onProvinceChange"
                                                ></v-select>
                                            </v-col>
                                            <v-col cols="6">
                                                <v-select
                                                    label="Municipality"
                                                    v-model="
                                                        selectedMunicipality
                                                    "
                                                    :items="
                                                        filteredMunicipalities
                                                    "
                                                    item-text="name"
                                                    item-value="mun_code"
                                                    dense
                                                    outlined
                                                ></v-select>
                                            </v-col>
                                            <v-col cols="6">
                                                <v-select
                                                    label="Barangay"
                                                    v-model="selectedBarangay"
                                                    :items="filteredBarangays"
                                                    item-text="name"
                                                    item-value="brgy_code"
                                                    dense
                                                    outlined
                                                ></v-select>
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
import phil from "phil-reg-prov-mun-brgy";
import moment from "moment";
import { mapActions, mapState } from "vuex";

// var phil = require("phil-reg-prov-mun-brgy");
console.log(phil.getBarangayByMun(140101));

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
        selectedProvince: null,
        selectedMunicipality: null,
        selectedBarangay: null,
        provinceList: [],
        municipalityMaster: [], // All municipalities
        barangayMaster: [], // All barangays
        filteredMunicipalities: [],
        filteredBarangays: [],
    }),
    methods: {
        onProvinceChange() {
            // Filter municipalities based on selected province
            this.filteredMunicipalities = this.municipalityMaster.filter(
                (municipality) =>
                    municipality.prov_code === this.selectedProvince
            );
            // Clear the previously selected municipality and barangay
            this.selectedMunicipality = null;
            this.selectedBarangay = null;
            this.filteredBarangays = [];
        },
        onMunicipalityChange() {
            // Filter barangays based on selected municipality
            this.filteredBarangays = this.barangayMaster.filter(
                (barangay) => barangay.mun_code === this.selectedMunicipality
            );
            // Clear the previously selected barangay
            this.selectedBarangay = null;
        },

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
            const charCode = event.charCode;
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
        tempAddressTxt() {
            let completeAddress = [];

            if (this.patient.house_address) {
                completeAddress.push(this.patient.house_address);
            }

            if (this.selectedBarangay) {
                completeAddress.push(this.selectedBarangay);
            }

            if (this.selectedMunicipality) {
                const municipality = this.filteredMunicipalities.find(
                    (mun) => mun.mun_code === this.selectedMunicipality
                );
                if (municipality) {
                    completeAddress.push(municipality.name);
                }
            }

            if (this.selectedProvince) {
                const province = this.provinceList.find(
                    (prov) => prov.prov_code === this.selectedProvince
                );
                if (province) {
                    completeAddress.push(province.name);
                }
            }

            if (this.patient.region) {
                completeAddress.push(this.patient.region);
            }

            console.log("totok", completeAddress);
            this.patient.address_txt = completeAddress.join(" ");
            return this.patient.address_txt;
        },
    },
    watch: {
        dialog(val) {
            if (!val) {
                this.selectedMunicipality = null;
                this.selectedBarangay = null;
                this.selectedProvince = null;
                this.$refs.Insert.resetValidation();
            }
        },
        "patient.type_of_patient": {
            handler(newVal) {
                if (newVal === 2) {
                    this.patient.sex_id = 1;
                }
            },
            immediate: true,
        },
        selectedMunicipality() {
            this.onMunicipalityChange();
        },
    },
    mounted() {
        this.provinceList = phil.provinces
            .map((province) => ({
                name: province.name,
                prov_code: province.prov_code,
            }))
            .sort((a, b) => a.name.localeCompare(b.name));
        this.municipalityMaster = phil.city_mun;
        this.barangayMaster = phil.barangays;
    },
};
</script>
