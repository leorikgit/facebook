import Vue from "vue";
import router from './Router';
import App from './components/App.vue';
import store from './store';
require('./bootstrap');



const app = new Vue({
    el: '#app',
    components:{
        App
    },
    router,
    store
});
