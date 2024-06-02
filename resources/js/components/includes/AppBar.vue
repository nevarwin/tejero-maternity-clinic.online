<template>
    <!-- <v-app-bar app clipped-left dark color="#3377bb">
        <v-tooltip bottom>
            <template v-slot:activator="{ on }">
                <v-app-bar-nav-icon v-on="on" @click="toggleDrawer(!drawer)"/>
            </template>
            <span>{{drawer ? 'Hide' : 'Show'}}</span>
        </v-tooltip>
        <v-toolbar-title>
            <span >Personal</span>
        </v-toolbar-title>
        <v-spacer></v-spacer>
        <v-img :src="pathStorage + '/favicon.ico'" max-height="50" max-width="50"></v-img>
        <v-spacer></v-spacer>
    </v-app-bar> -->
    <v-app-bar app clipped-left dark color="#0e6d3a">
        <v-app-bar-nav-icon @click="toggleDrawer(!drawer)"></v-app-bar-nav-icon>
        <v-toolbar-title>
            <span id="systemName">Tejero Maternity Clinic</span>
        </v-toolbar-title>
        <v-spacer></v-spacer>
        <span class="overline">{{ auth_name }}</span>
        <v-tooltip bottom>
            <template v-slot:activator="{ on: tooltip }">
                <v-menu offset-y max-width="300">
                    <template v-slot:activator="{ on: menu, attrs }">
                        <v-btn
                            icon
                            v-bind="attrs"
                            v-on="{ ...tooltip, ...menu }"
                        >
                            <v-icon>mdi-account-circle</v-icon>
                        </v-btn>
                    </template>
                    <v-list dense>
                        <v-list-item-group>
                            <v-list-item>
                                <v-list-item-content to="login">
                                    <v-form
                                        id="logOut"
                                        method="POST"
                                        action="logout"
                                    >
                                        <input
                                            type="hidden"
                                            name="_token"
                                            :value="csrf"
                                        />
                                        <v-icon>mdi-power</v-icon>
                                        <input
                                            type="submit"
                                            value="Logout"
                                            depressed
                                            class="caption"
                                        />
                                    </v-form>
                                </v-list-item-content>
                            </v-list-item>
                        </v-list-item-group>
                    </v-list>
                </v-menu>
            </template>
            <span>Menu</span>
        </v-tooltip>
    </v-app-bar>
</template>

<script>
import { mapState } from "vuex";
export default {
    name: "AppBar",
    props: {
        drawer: {
            type: Boolean,
        },
    },
    data: () => ({
        items: [
            { text: "Home", icon: "mdi-home", to: "home" },
            { text: "Hello", icon: "mdi-hand-wave", to: "hello" },
            { text: "Test", icon: "mdi-account", to: "test" },
        ],
        pathStorage: window.base_dir,
        csrf: document
            .querySelector('meta[name="csrf-token"]')
            .getAttribute("content"),
    }),
    methods: {
        toggleDrawer(val) {
            this.$emit("toggleDrawer", val);
        },
    },
    computed: {
        ...mapState(["loggedInUser", "auth_name", "systemData"]),
    },
};
</script>
