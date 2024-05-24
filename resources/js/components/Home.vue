<template>
    <v-container fluid v-if="loggedInUser.status == 'Active'">
        <v-layout row wrap>
            <v-flex xs12 sm4>
                <v-card class="dpatientDataashboard-card" color="primary">
                    <v-card-title class="text-center white--text title">
                        Mother Patient
                        <v-icon x-large color="white">mdi-human-female</v-icon>
                    </v-card-title>
                    <v-card-text class="text-center">
                        <div class="headline white--text value mt-16">
                            <h1 style="font-size: 80px; padding-bottom: 90px">
                                {{ returnMother(patientData) }}
                            </h1>
                        </div>
                    </v-card-text>
                </v-card>
            </v-flex>
            <v-flex xs12 sm4>
                <v-card class="dashboard-card ml-1" color="info">
                    <v-card-title class="text-center white--text title">
                        Baby Patient
                        <v-icon x-large color="white">mdi-baby</v-icon>
                    </v-card-title>
                    <v-card-text class="text-center">
                        <div class="headline white--text value mt-16">
                            <h1 style="font-size: 80px; padding-bottom: 80px">
                                {{ returnBaby(patientData) }}
                            </h1>
                        </div>
                    </v-card-text>
                </v-card>
            </v-flex>
            <v-flex xs12 sm4>
                <v-card class="dashboard-card ml-2" color="success">
                    <v-card-title class="text-center white--text title">
                        All Patient
                        <v-icon x-large color="white">mdi-account-group</v-icon>
                    </v-card-title>
                    <v-card-text class="text-center">
                        <div class="headline white--text value mt-16">
                            <h1 style="font-size: 80px; padding-bottom: 80px">
                                {{ returnNewBaby(patientData) }}
                            </h1>
                        </div>
                    </v-card-text>
                </v-card>
            </v-flex>
        </v-layout>
        <v-row>
            <v-col xs="12" sm="6" class="equal-height">
                <v-card>
                    <v-card-title>Doctor List</v-card-title>
                    <v-card-text class="mt-5">
                        <v-responsive>
                            <v-simple-table dense height="465">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Position</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr
                                        v-for="(doctor, index) in doctorData"
                                        :key="index"
                                    >
                                        <td>{{ doctor.name }}</td>
                                        <td>{{ doctor.position }}</td>
                                    </tr>
                                </tbody>
                            </v-simple-table>
                        </v-responsive>
                    </v-card-text>
                </v-card>
            </v-col>
            <v-col xs="12" sm="6" class="equal-height">
                <v-card>
                    <v-card-title>Chart</v-card-title>
                    <v-card-text>
                        <div class="small-chart-container" style="width: 100%">
                            <apexchart
                                small
                                type="bar"
                                :options="chartOptions"
                                :series="chartSeries"
                            ></apexchart>
                        </div>
                    </v-card-text>
                </v-card>
            </v-col>
        </v-row>
    </v-container>
    <v-container v-else>
        <div><h1 style="color: red">THIS ACCOUNT IS INACTIVE</h1></div>
    </v-container>
</template>

<style scoped>
.d-flex {
    display: flex;
    flex-wrap: wrap;
}

.equal-height {
    display: flex;
    flex-direction: column;
}

.equal-height .v-card {
    flex: 1;
}
</style>
<script>
import VueApexCharts from "vue-apexcharts";
import { mapActions, mapState } from "vuex";
import moment from "moment";

export default {
    components: {
        apexchart: VueApexCharts,
    },
    data() {
        return {
            headers: [
                { text: "Name", value: "name" },
                { text: "Position", value: "position" },
            ],

            //CHART
            chartOptions: {
                chart: {
                    type: "bar",
                },
                plotOptions: {
                    bar: {
                        horizontal: false,
                    },
                },
                xaxis: {
                    categories: [],
                },
                tooltip: {
                    enabled: true,
                    y: {
                        formatter: function (val) {
                            return val + " patients";
                        },
                    },
                },
            },
            chartSeries: [
                {
                    name: "Patient Count",
                    data: [],
                },
            ],
        };
    },
    methods: {
        ...mapActions(["getDoctor", "getPatient"]),
        returnMother(val) {
            return val.filter((rec) => {
                if (rec.type_of_patient == "2") {
                    return rec;
                }
            }).length;
        },
        returnBaby(val) {
            return val.filter((rec) => {
                if (rec.type_of_patient != "2") {
                    return rec;
                }
            }).length;
        },
        returnNewBaby(val) {
            return val.filter((rec) => {
                return rec;
            }).length;
        },

        returnPatientCountByMonth() {
            const patientData = this.patientData;
            const monthCounts = {};

            // Initialize monthCounts with 0 counts for each month
            moment.months().forEach((month) => {
                monthCounts[month] = 0;
            });

            // Count patients for each month
            patientData.forEach((patient) => {
                const month = moment(patient.created_at).format("MMMM"); // Assuming there's a 'created_at' field in patientData
                monthCounts[month]++;
            });

            // Update chart data
            this.chartOptions.xaxis.categories = Object.keys(monthCounts);
            this.chartSeries = [
                {
                    name: "Patient Count",
                    data: Object.values(monthCounts),
                },
            ];
        },
    },
    computed: {
        ...mapState(["rules", "doctorData", "patientData", "loggedInUser"]),
    },
    mounted() {
        this.getDoctor();
        this.getPatient();
    },
    watch: {
        patientData: {
            handler() {
                this.returnPatientCountByMonth();
            },
            immediate: true,
            deep: true,
        },
    },
};
</script>

<style scoped>
.small-chart-container {
    width: 750px; /* Adjust the width as needed */
}
.dashboard-card {
    height: 250px;
}

.title {
    font-size: 24px; /* Adjust the title font size */
}

.value {
    font-size: 36px; /* Adjust the value font size */
}

/* Optional: Add more styles for responsiveness if needed */
</style>
