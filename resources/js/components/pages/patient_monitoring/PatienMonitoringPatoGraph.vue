<template>
    <v-container class="container-main pt-0 pl-0" fluid>
        <v-btn class="mb-5 mt-5" color="primary" @click="dialog = true"
            >ADD RECORD</v-btn
        >
        <v-simple-table dense>
            <thead>
                <tr>
                    <th>Case No</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Dilation (cm)</th>
                    <th>Hours since ruptured membranes</th>
                    <th>Rapid Assessment</th>
                    <th>Vaginal Bleeding(0+++)</th>
                    <th>Amniotic fluid (meconium stained)</th>
                    <th>Contraction in 10 minutes</th>
                    <th>Fetal heart rate(beats/minute)</th>
                    <th>Urine voided</th>
                    <th>T(axiliary)</th>
                    <th>Pulse(beats/minutes)</th>
                    <th>Blood Pressure(systolic/diastolic)</th>
                    <th>Cervical Dilation(cm)</th>
                    <th>Delivery of Placenta(beats/minutes)</th>
                    <th>Oxytocin</th>
                    <th>Problem-note onset/describe below</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(item, index) in case_Partograph" :key="index">
                    <td>{{ item.case_no }}</td>
                    <td>{{ item.date }}</td>
                    <td>{{ item.time }}</td>
                    <td>{{ item.dilation }}</td>
                    <td>{{ item.hours_ruptured_membranes }}</td>
                    <td>{{ item.rapid_assesment }}</td>
                    <td>{{ item.vaginal_bleeding }}</td>
                    <td>{{ item.amniotic_fluid }}</td>
                    <td>{{ item.contraction }}</td>
                    <td>{{ item.fetal_heart_rate }}</td>
                    <td>{{ item.urine_voided }}</td>
                    <td>{{ item.axiliary }}</td>
                    <td>{{ item.pulse }}</td>
                    <td>{{ item.blood_presure }}</td>
                    <td>{{ item.cervical_dilation }}</td>
                    <td>{{ item.delivery_of_placenta }}</td>
                    <td>{{ item.oxytocin }}</td>
                    <td>{{ item.problem_note_onset }}</td>
                </tr>
            </tbody>
        </v-simple-table>

        <v-dialog v-model="dialog">
            <v-card>
                <v-card-title>Record Partograph</v-card-title>
                <v-card-text>
                    <v-row>
                        <v-col class="ml-5">
                            <v-img
                                style="text-align: center"
                                width="1000"
                                :src="imagePath"
                                alt="My Image"
                                contain
                                fill-height
                            ></v-img>
                        </v-col>
                    </v-row>
                    <v-row>
                        <v-menu
                            v-model="menu2"
                            :close-on-content-click="false"
                            :nudge-right="40"
                            transition="scale-transition"
                            offset-y
                            min-width="auto"
                        >
                            <template v-slot:activator="{ on, attrs }">
                                <v-text-field
                                    outlined
                                    dense
                                    v-model="selectedDate"
                                    label="Select Date"
                                    readonly
                                    v-bind="attrs"
                                    v-on="on"
                                ></v-text-field>
                            </template>
                            <v-date-picker
                                v-model="selectedDate"
                                @input="menu2 = false"
                            ></v-date-picker>
                        </v-menu>
                    </v-row>
                    <v-row>
                        <v-text-field
                            outlined
                            dense
                            v-model.number="maxDuration"
                            label="Max duration (hours)"
                            type="number"
                            :rules="[
                                (v) =>
                                    (v > 0 && v <= 12) ||
                                    'Duration must be between 1 and 12 hours',
                            ]"
                        ></v-text-field>
                    </v-row>
                    <v-row>
                        <v-text-field
                            outlined
                            dense
                            v-model.number="dilation"
                            label="Dilation (cm)"
                            type="number"
                            :rules="[
                                (v) =>
                                    (v > 0 && v <= 10) ||
                                    'Dilation must be between 1 and 10 cm',
                            ]"
                        ></v-text-field>
                    </v-row>
                    <v-row>
                        <v-col>Findings</v-col>
                    </v-row>
                    <v-row>
                        <v-col>
                            <v-text-field
                                v-model="partograph.hours_ruptured_membranes"
                                outlined
                                dense
                                label="Hours since ruptured membranes"
                            ></v-text-field>
                        </v-col>
                        <v-col>
                            <v-text-field
                                v-model="partograph.rapid_assesment"
                                outlined
                                dense
                                label="Rapid Assessment"
                            ></v-text-field>
                        </v-col>
                        <v-col>
                            <v-text-field
                                v-model="partograph.vaginal_bleeding"
                                outlined
                                dense
                                label="Vaginal Bleeding(0+++)"
                            ></v-text-field>
                        </v-col>
                    </v-row>
                    <v-row>
                        <v-col>
                            <v-text-field
                                v-model="partograph.amniotic_fluid"
                                outlined
                                dense
                                label="Amniotic fluid (meconium stained)"
                            ></v-text-field>
                        </v-col>
                        <v-col>
                            <v-text-field
                                v-model="partograph.contraction"
                                outlined
                                dense
                                label="Contraction in 10 minutes"
                            ></v-text-field>
                        </v-col>
                        <v-col>
                            <v-text-field
                                v-model="partograph.fetal_heart_rate"
                                outlined
                                dense
                                label="Fetal heart rate(beats/minute)"
                            ></v-text-field>
                        </v-col>
                    </v-row>
                    <v-row>
                        <v-col>
                            <v-text-field
                                v-model="partograph.urine_voided"
                                outlined
                                dense
                                label="Urine voided"
                            ></v-text-field>
                        </v-col>
                        <v-col>
                            <v-text-field
                                v-model="partograph.axiliary"
                                outlined
                                dense
                                label="T(axiliary)"
                            ></v-text-field>
                        </v-col>
                        <v-col>
                            <v-text-field
                                v-model="partograph.pulse"
                                outlined
                                dense
                                label="Pulse(beats/minutes)"
                            ></v-text-field>
                        </v-col>
                    </v-row>
                    <v-row>
                        <v-col>
                            <v-text-field
                                v-model="partograph.blood_presure"
                                outlined
                                dense
                                label="Blood Pressure(systolic/diastolic)"
                            ></v-text-field>
                        </v-col>
                        <v-col>
                            <v-text-field
                                v-model="partograph.cervical_dilation"
                                outlined
                                dense
                                label="Cervical Dilation(cm)"
                            ></v-text-field>
                        </v-col>
                        <v-col>
                            <v-text-field
                                v-model="partograph.delivery_of_placenta"
                                outlined
                                dense
                                label="Delivery of Placenta(beats/minutes)"
                            ></v-text-field>
                        </v-col>
                    </v-row>
                    <v-row>
                        <v-col>
                            <v-text-field
                                v-model="partograph.oxytocin"
                                outlined
                                dense
                                label="Oxytocin"
                            ></v-text-field>
                        </v-col>
                        <v-col>
                            <v-text-field
                                v-model="partograph.problem_note_onset"
                                outlined
                                dense
                                label="Problem-note onset/describe below"
                            ></v-text-field>
                        </v-col>
                    </v-row>
                    <v-row>
                        <v-btn
                            class="ml-2"
                            @click="recordDilation"
                            color="primary"
                            >Record Dilation</v-btn
                        >
                    </v-row>
                </v-card-text>
            </v-card>
        </v-dialog>
    </v-container>
</template>

<script>
import myImage from "../../../../../public/images/partograph.jpg";
import axios from "axios";
import moment from "moment";
import { mapActions, mapState } from "vuex";

export default {
    props: {
        case_data: {
            type: Object,
        },
    },
    data() {
        return {
            dialog: false,
            selectedDate: null,
            maxDuration: null,
            dilation: null,
            imagePath: myImage,
            menu2: false,
            dilationChart: [],
            partograph: {},
            headers: [
                { text: "Date", value: "date" },
                { text: "Time", value: "time" },
                { text: "Dilation (cm)", value: "dilation" },
                {
                    text: "Hours since ruptured membranes",
                    value: "hours_ruptured_membranes",
                },
                { text: "Rapid Assessment", value: "rapid_assesment" },
                { text: "Vaginal Bleeding(0+++)", value: "vaginal_bleeding" },
                {
                    text: "Amniotic fluid (meconium stained)",
                    value: "amniotic_fluid",
                },
                { text: "Contraction in 10 minutes", value: "contraction" },
                {
                    text: "Fetal heart rate(beats/minute)",
                    value: "fetal_heart_rate",
                },
                { text: "Urine voided", value: "urine_voided" },
                { text: "T(axiliary)", value: "axiliary" },
                { text: "Pulse(beats/minutes)", value: "pulse" },
                {
                    text: "Blood Pressure(systolic/diastolic)",
                    value: "blood_presure",
                },
                { text: "Cervical Dilation(cm)", value: "cervical_dilation" },
                {
                    text: "Delivery of Placenta(beats/minutes)",
                    value: "delivery_of_placenta",
                },
                { text: "Oxytocin", value: "oxytocin" },
                {
                    text: "Problem-note onset/describe below",
                    value: "problem_note_onset",
                },
            ],
        };
    },
    methods: {
        ...mapActions([]),
        recordDilation() {
            if (
                this.selectedDate &&
                this.maxDuration > 0 &&
                this.maxDuration <= 12 &&
                this.dilation > 0 &&
                this.dilation <= 10
            ) {
                this.partograph.case_no = this.case_data.case_no;
                this.partograph.dilation = this.dilation;
                this.partograph.maxDuration = this.maxDuration;
                this.partograph.date = this.selectedDate.toLocaleString();
                this.partograph.time = moment().format("HH:mm:ss");
                axios({
                    method: "post",
                    url: "partograph_insert",
                    data: this.partograph,
                });
                this.case_Partograph.push(this.partograph);
                this.partograph = {};
                this.dialog = false;
            } else {
                console.error(
                    "Please select a valid date and ensure max duration is between 1 and 12 hours and dilation is between 1 and 10 cm."
                );
            }
        },
        drawPartograph() {
            console.log("Time\t\tDilation");
            console.log("--------------------------");
            this.dilationChart.forEach((entry) => {
                console.log(`${entry.time}\t${entry.dilation}`);
            });
        },
        checkProgress() {
            for (let i = 1; i < this.dilationChart.length; i++) {
                if (
                    this.dilationChart[i].dilation <=
                    this.dilationChart[i - 1].dilation
                ) {
                    console.log("Labor is not progressing normally.");
                    return;
                }
            }
            console.log("Labor is progressing normally.");
        },
    },
    computed: {
        ...mapState(["case_Partograph", "case_no", "getPartograph"]),
    },
    mounted() {
        console.log(this.case_Partograph, "vital");
        console.log(this.case_no);
    },
};
</script>
