require('./bootstrap');
require('./dialog_movable');
import Vue from 'vue';
import Vuetify from 'vuetify';
import Router from './router';
import store from './store';
import App from './template/App';
import Login from './template/LoginApp';
import RequestDocument from './template/RequestDocument';
import VueHtmlToPaper from "vue-html-to-paper";

Vue.use(Vuetify);

const options = {
    name: "_blank",
    specs: ["fullscreen=yes", "titlebar=yes", "scrollbars=yes"],
    styles: [
        "https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css",
    ],
};

new Vue({
    el: '#app',
    store,
    router: Router,
    vuetify: new Vuetify(),
    components: {
        App,
        Login,
        RequestDocument
    },
    mounted() {
        Vue.use(VueHtmlToPaper, options);
    },
});
