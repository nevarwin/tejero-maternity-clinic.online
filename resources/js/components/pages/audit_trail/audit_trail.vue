<template>
    <v-container fluid>
        <h1>Transaction Logs</h1>
        <v-row>
            <v-col cols="12" sm="6">
                <v-text-field
                    v-model="search"
                    outlined
                    color="primary"
                    dense
                    label="Search"
                />
            </v-col>
            <v-col cols="12" sm="3">
                <v-menu
                    v-model="startdateMenu"
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
                            v-model="startDate"
                            label="Start Date"
                            prepend-icon="mdi-calendar"
                            readonly
                            v-bind="attrs"
                            v-on="on"
                        ></v-text-field>
                    </template>
                    <v-date-picker
                        v-model="startDate"
                        @input="startdateMenu = false"
                        :max="maxDate"
                    ></v-date-picker>
                </v-menu>
            </v-col>
            <v-col cols="12" sm="3">
                <v-menu
                    v-model="enddateMenu"
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
                            v-model="endDate"
                            label="End Date"
                            prepend-icon="mdi-calendar"
                            readonly
                            v-bind="attrs"
                            v-on="on"
                        ></v-text-field>
                    </template>
                    <v-date-picker
                        v-model="endDate"
                        @input="enddateMenu = false"
                        :max="maxDate"
                    ></v-date-picker>
                </v-menu>
            </v-col>
            <v-col cols="12" sm="12" md="3">
                <v-btn color="primary" @click="filterLog">Filter Date</v-btn>
            </v-col>
        </v-row>

        <v-row>
            <v-col cols="12">
                <v-data-table
                    :search="search"
                    :headers="headers"
                    :items="paginatedItems"
                    class="border"
                    dense
                ></v-data-table>
            </v-col>
        </v-row>
    </v-container>
</template>

<script>
import axios from "axios";
import moment from "moment";

export default {
    data() {
        return {
            search: "",
            startDate: "",
            endDate: "",
            startdateMenu: false,
            enddateMenu: false,
            currentPage: 1,
            itemsPerPage: 5,
            all_products: [],
            maxDate: moment().format("YYYY-MM-DD"),
            headers: [
                { text: "ID", value: "id" },
                { text: "Action", value: "action" },
                { text: "Description", value: "description" },
                { text: "Name", value: "name" },
                { text: "Access / Position", value: "position" },
                { text: "Drawer Link", value: "drawerLink" },
                { text: "Date", value: "created_at" },
            ],
        };
    },
    computed: {
        paginatedItems() {
            const startIndex = (this.currentPage - 1) * this.itemsPerPage;
            const endIndex = startIndex + this.itemsPerPage;
            return this.all_products.slice(startIndex, endIndex);
        },
        numPages() {
            return Math.ceil(this.all_products.length / this.itemsPerPage);
        },
    },
    methods: {
        filterLog() {
            const date = {
                end_date: this.endDate,
                start_date: this.startDate,
            };
            axios.post("audit", date).then((res) => {
                this.all_products = res.data;
            });
        },
        getAllProducts() {
            axios.post("audit").then((res) => {
                this.all_products = res.data;
                if (this.all_products.length > 0) {
                    this.startDate = moment(
                        this.all_products[0].created_at
                    ).format("YYYY-MM-DD");
                    this.endDate = moment(
                        this.all_products[this.all_products.length - 1]
                            .created_at
                    ).format("YYYY-MM-DD");
                }
            });
        },
        changePage(page) {
            this.currentPage = page;
        },
    },
    mounted() {
        this.getAllProducts();
    },
};
</script>
