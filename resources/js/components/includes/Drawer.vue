<template>
    <v-navigation-drawer
        v-model="drawerVal"
        app
        clipped
        @transitionend="toggleDrawer(drawerVal)"
    >
        <v-list dense>
            <v-list-item
                v-for="(item, i) in items"
                :key="i"
                :to="item.to"
                class="drawer-list"
            >
                <v-list-item-icon class="mr-1">
                    <v-icon v-text="item.icon"></v-icon>
                </v-list-item-icon>
                <v-list-item-content>
                    <v-list-item-title v-text="item.text"></v-list-item-title>
                </v-list-item-content>
            </v-list-item>
        </v-list>
    </v-navigation-drawer>
</template>

<script>
import { mapState, mapActions } from "vuex";
export default {
    name: "MainApp",
    props: {
        drawer: {
            type: Boolean,
        },
    },
    data: () => ({
        drawerVal: true,
        // items:[
        //     // {text:'Cad Plan Monitoring', icon:'mdi-home', to:'cad_plan_monitoring'},
        //     // {text:'Required Document', icon:'mdi-hand-wave', to:'required_document'},
        //     {text:'Permit Set', icon:'mdi-hand-wave', to:'permit_set'},
        //     {text:'Child Document', icon:'mdi-hand-wave', to:'child_document'},
        //     {text:'Cut Off', icon:'mdi-hand-wave', to:'cutOff'},
        //     {text:'Cut Off3', icon:'mdi-hand-wave', to:'cutOff3'},
        //     {text:'Employee', icon:'mdi-hand-wave', to:'employee'},
        //     {text:'Task', icon:'mdi-hand-wave', to:'task'},
        //     {text:'Type', icon:'mdi-hand-wave', to:'type_page'},
        //     {text:'Status', icon:'mdi-hand-wave', to:'status_page'},
        //     {text:'Main Topic', icon:'mdi-hand-wave', to:'topic_page'},
        //     {text:'Birthday', icon:'mdi-hand-wave', to:'birthday'},
        //     {text:'Training', icon:'mdi-hand-wave', to:'training'},
        //     {text:'Screening', icon:'mdi-hand-wave', to:'screening'},
        //     {text:'Examination', icon:'mdi-hand-wave', to:'examination'},
        //     {text:'Notepad', icon:'mdi-hand-wave', to:'notepad'},
        //     {text:'Plot', icon:'mdi-hand-wave', to:'plot'},
        //     // {text:'Separated Document', icon:'mdi-hand-wave', to:'separated_document'},
        // ],
        items: [
            { text: "Dashboard", icon: "mdi-monitor-dashboard", to: "home" },
            // {text:'Hello', icon:'mdi-hand-wave', to:'hello'},
            // {text: 'Test', icon: 'mdi-account', to : 'test'},
            {
                text: "Patient Registration",
                icon: "mdi-account",
                to: "patient_registration",
            },
            // {text: 'Religion', icon: 'mdi-church', to : 'religion'},
            { text: "Doctor", icon: "mdi-doctor", to: "doctor" },
            { text: "Room", icon: "mdi-bed", to: "room" },
            {
                text: "Admission",
                icon: "mdi-hospital-box-outline",
                to: "admission",
            },
            {
                text: "Patient Monitoring",
                icon: "mdi-account-injury-outline",
                to: "patient_monitoring",
            },
            // {text: 'Documents', icon: 'mdi-text-box', to : 'file'},
            { text: "Audit Trail", icon: "mdi-view-list", to: "audit_trail" },
            {
                text: "Patient History",
                icon: "mdi-view-list",
                to: "patient_history",
            },
            {
                text: "Registration",
                icon: "mdi-account-plus",
                to: "registration",
            },
        ],
    }),
    methods: {
        toggleDrawer(val) {
            this.$emit("toggleDrawer", val);
        },
        toggleDelete() {
            this.$emit("toggleDelete");
        },
    },
    computed: {
        ...mapState([
            "loggedInUser",
            "auth_name",
            "deviceIp",
            "adminUser",
            "birthDayUser",
        ]),
    },
    watch: {
        drawer(val) {
            this.drawerVal = val;
        },
    },
    created() {},
    mounted() {
        if (this.loggedInUser.status == "Inactive") {
            const indicesToRemove = this.items.reduce((acc, item, index) => {
                if (![].includes(item.text)) {
                    acc.push(index);
                }
                return acc;
            }, []);

            // Remove items from the array
            indicesToRemove.reverse().forEach((index) => {
                this.items.splice(index, 1);
            });
        }
        if (this.loggedInUser.access == "Admin") {
            const indicesToRemove = this.items.reduce((acc, item, index) => {
                if (
                    ![
                        "Dashboard",
                        "Patient Registration",
                        "Admission",
                        "Patient Monitoring",
                        "Documents",
                    ].includes(item.text)
                ) {
                    acc.push(index);
                }
                return acc;
            }, []);

            // Remove items from the array
            indicesToRemove.reverse().forEach((index) => {
                this.items.splice(index, 1);
            });
        } else if (this.loggedInUser.access == "Staff") {
            const indicesToRemove = this.items.reduce((acc, item, index) => {
                if (!["Dashboard", "Patient Monitoring"].includes(item.text)) {
                    acc.push(index);
                }
                return acc;
            }, []);

            // Remove items from the array
            indicesToRemove.reverse().forEach((index) => {
                this.items.splice(index, 1);
            });
        } else if (this.loggedInUser.access == "Doctor") {
            const indicesToRemove = this.items.reduce((acc, item, index) => {
                if (!["Patient Monitoring"].includes(item.text)) {
                    acc.push(index);
                }
                return acc;
            }, []);

            // Remove items from the array
            indicesToRemove.reverse().forEach((index) => {
                this.items.splice(index, 1);
            });
        }
    },
};
</script>
