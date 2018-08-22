/**
 * @desc Initialize
 */
import Vue from "vue";
import Vuex from "vuex";
import App from "./shared/App.vue"; //Bundle App.vue 
import {
    ROUTING
} from "./config/router/Router.js";
import VueAxios from "vue-axios";
import axios from "axios";
import VueRouter from "vue-router";
import VueI18n from "vue-i18n";
import messages from "./static/language/translations.js"; //multi Language Pack
import Es6Promise from "es6-promise";
import Babel from "babel-polyfill";
import VuexPersist from "vuex-persist";
import VueLodash from "vue-lodash";
import VueMoment from "vue-moment";
import moment from "moment-timezone";
import VueSweetalert2 from 'vue-sweetalert2';

/**
 * @desc modal Validator
 */
import Vuelidate from "vuelidate";
import VeeValidate from "vee-validate";

/**
 * @desc UI Librarys
 */
import Vuetify from "vuetify";
import BootstrapVue from "bootstrap-vue";
import colors from "vuetify/es5/util/colors"
import Vuesax from "vuesax";
import VueCharts from "vue-chartjs";
import VueAwesomeSwiper from "vue-awesome-swiper";
import FullCalendar from "vue-full-calendar";
import draggable from "vuedraggable";
import * as VueGoogleMaps from 'vue2-google-maps';

/**
 * @desc CSS files 
 */
import "vuetify/dist/vuetify.css";
// import "bootstrap-vue/dist/bootstrap-vue.css";
// import "bootstrap/dist/css/bootstrap.css";

import "vuesax/dist/vuesax.css";
import "swiper/dist/css/swiper.css";
import "bootstrap-vue/dist/bootstrap-vue.css";
import "bootstrap/dist/css/bootstrap.css";
import "fullcalendar/dist/fullcalendar.min.css";

var googleMapSetting = {
    key: 'AIzaSyC2Ttp9JXn_j4U_1oYQmEaTfYQkrwS-OL4',
    libraries: "places",
    language: "ja"
}

Vue.config.productionTip = false;
//get polyfills 
Es6Promise.polyfill();
// Babel.polyfill();
Vue.use(Vuex);
Vue.use(VueSweetalert2);
Vue.use(draggable);
Vue.use(FullCalendar);
Vue.use(VueCharts);
Vue.use(VueMoment, {
    moment
});
// 구글 맵스 키 값
Vue.use(VueGoogleMaps, {
    load: googleMapSetting
});
// Vue.use(Babel);
// Vue.use(Es6Promise);
Vue.use(VueLodash, {
    name: "lodash"
});
Vue.use(BootstrapVue);
Vue.use(VueAwesomeSwiper);
Vue.use(Vuelidate);
Vue.use(VeeValidate, {
    fieldsBagName: 'formFields'
});
Vue.use(VueI18n);
Vue.use(Vuesax);
Vue.use(VueRouter); //Set Routing
Vue.use(VueAxios, axios); //Vue Ajax Setting
Vue.use(Vuetify, {
    theme: {
        //primary: colors.red.base,
        secondary: colors.lightBlue.base,
        // main : ,
        // sub : ,
        //accent: colors.blueGrey.base,
        grey: '#E0E0E0',
        success: '#008080',
        dahong: '#ff8400',
        //info: colors.blue.darken1,
        //warning: colors.yellow.darken1,
        //error: colors.red.base,
    }
});


// Set global eventBus
Vue.prototype.$eventBus = new Vue();

const vuexLocalStorage = new VuexPersist({
    key: "vuex",
    storage: window.localStorage,
    expires: 7 * 24 * 60 * 60 * 1e3
});

//Set Vuex
const store = new Vuex.Store({
    plugins: [vuexLocalStorage.plugin],
    state: {
        classification: null,
        identification: null,
        affiliate: null,
        accessToken: null,
        currentDate: null,
        authority: null,
        token: null,

        push_config: {

            apiKey: "AIzaSyC7G8aNaGKTDDelDjUJZSNCPH2j9LoJssI",
            authDomain: "lightourfuture-4d145.firebaseapp.com",
            databaseURL: "https://lightourfuture-4d145.firebaseio.com",
            projectId: "lightourfuture-4d145",
            storageBucket: "",
            messagingSenderId: "1078786632026"
        },
    },
    getters: {
        currentDate: function (state) {
            return state.currentDate;
        },

        identification: function (state) {
            return state.identification;
        },

        classification: function (state) {
            return state.classification;
        },

        accessToken: function (state) {
            return state.accessToken;
        },

        accessData: function (state) {
            let accessData = {
                id: state.identification,
                token: state.accessToken,
                classification: state.classification
            }

            return accessData;
        },

        token: function (state) {
            return state.token;
        },

        push_config: function (state) {
            let push_config = {
                apiKey: "AAAAVeUCuy0:APA91bEPTsq9CoEvGaBpUD2DlG-T8wJZqnuA58FM9jQ4_DxIGQUn3z-AJ-llML1EhHpWaTGTpmZs2vEpwW48pKx318IAi3Cgrh67iZ_PbgvicMWyay9PvVnMf47iV-fHJH9_GFxIgZAp",
                authDomain: "push-alam-test.firebaseapp.com",
                databaseURL: "https://push-alam-test.firebaseio.com",
                projectId: "push-alam-test",
                storageBucket: "push-alam-test.appspot.com",
                messagingSenderId: "368914381613"
            }
            return push_config;
        }
    },
    mutations: {
        setClassification: (state, payload) => {
            state.classification = payload.classification;
        },

        clearState: (state) => {
            for (let key in state) {
                state[key] = null;
            }
        },

        setDate: (state) => {
            state.currentDate = new Date(fullDate);
        },

        logout: (state) => {

            state.classification = null;
            state.identification = null;
            state.accessToken = null;
            delete localStorage.accessToken;
        },

        setAccessInfo: (state, payload) => {

            state.identification = payload.id;
            state.classification = payload.classify;
            state.accessToken = payload.accessToken;

            localStorage.setItem("accessToken", payload.accessToken);
        }

    },
    actions: {
        logout: (context) => {
            // axios.defaults.headers.commit["Authoriztion"] = undefined;\
            context.commit('logout');
        },

        login: (context, payload) => {
            context.commit("setAccessInfo", payload);
        },

        me: function (context, payload) {
            let reqHttpAddr = "/api/auth/me";

            let sendData = {
                id: payload.id,
                token: payload.accessToken,
                classification: payload.classification
            }

            axios.post(reqHttpAddr, sendData).then(res => {
                if (!res.data) {
                    alert("please Login");
                    this.$router.push({
                        name: index
                    });
                    return false;
                }
                return true;
            }).catch(err => {
                console.log(err.message)
            })
        },

        setCurrentDate: function (context) {
            context.commit("setDate");
        },

        initStates: (context) => {
            context.commit("clearState")
        },

        setCurrentUserClassification: (context, payload) => {
            context.commit("setClassification", payload);
        }
    }
});


//Set Routes
const router = new VueRouter({
    routes: ROUTING,
    hashbang: false,
    saveScrollPosition: true,
});

//Set multiLanguage 
const i18n = new VueI18n({
    locale: "ja",
    messages
});

new Vue(Vue.util.extend({
    i18n,
    router,
    store
}, App)).$mount('#app');