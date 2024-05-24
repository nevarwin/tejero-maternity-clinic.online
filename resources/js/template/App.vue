<template>
    <v-app id="inspire">
        <drawer-component :drawer="drawer" @toggleDrawer="toggleDrawer"></drawer-component>
        <app-bar-component :drawer="drawer" @toggleDrawer="toggleDrawer($event)"></app-bar-component>

        <v-main class="ma-2">
            <router-view></router-view>
        </v-main>
    </v-app>
</template>

<script>
import AppBarComponent from '../components/includes/AppBar'
import DrawerComponent from '../components/includes/Drawer'
import {mapState} from 'vuex';
    export default {
        components:{
                'app-bar-component':AppBarComponent,
                'drawer-component':DrawerComponent,
        },
        data: () => ({
            drawer: null,
            items:[
                {text:'Home', icon:'mdi-home', to:'home'},
                {text:'Hello', icon:'mdi-hand-wave', to:'hello'},
                {text: 'Test', icon: 'mdi-account', to : 'test'},
                {text: 'Registration', icon: 'mdi-account', to : 'registration'},
            ],
            csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),

        }),
        methods:{
            toggleDrawer(val){
                this.drawer = val
            }
        },

        computed: {
            ...mapState([
                'loggedInUser'
            ])

        },
    }
</script>