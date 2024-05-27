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

        items: [
            { text: "Dashboard", icon: "mdi-monitor-dashboard", to: "home" },
            {
                text: "Patient Registration",
                icon: "mdi-account",
                to: "patient_registration",
            },
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
            { text: "Audit Trail", icon: "mdi-view-list", to: "audit_trail" },
            {
                text: "Patient History",
                icon: "mdi-view-list",
                to: "patient_history",
            },
            {
                text: "Medical Certificate Request List",
                icon: "mdi-view-list",
                to: "medcert_request_list",
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
