import Vue from 'vue'
import VueHead from 'vue-head';
import VueRouter from 'vue-router'
import VueAxios from 'vue-axios';

import BootstrapVue from 'bootstrap-vue'
import VueNoty from 'vuejs-noty'
import axios from 'axios'
import Vuex from 'vuex'

import { nativeroutes } from "./routes";
import permissions from './permissions';
import StoreData from './store';
import helpers from './helpers'

// Изменение заголовка страницы
Vue.use(VueHead, {
    separator: ' — ',
    complement: 'Votel'
})


require('./bootstrap');

// Роутер spa

Vue.use(VueRouter);
window.routes = nativeroutes;
const router = new VueRouter({
    mode: 'history',
    linkExactActiveClass: 'active',
    routes: nativeroutes
})

//  Помошник в операциях с датой
Vue.use(helpers);

//
const moment = require('moment');
moment.updateLocale("ru");
require('moment/locale/ru');
Vue.use(require('vue-moment'), { moment });


// Передача запросов на сервер
Vue.use(VueAxios, axios);



// Права доступа
Vue.mixin(permissions);

// Уведомления
Vue.use(VueNoty)

// bootstrap стили
Vue.use(BootstrapVue);

// Настройка хранилища данных
Vue.use(Vuex);
const store = new Vuex.Store(StoreData);

// Инициализация корневого компонента
let app = new Vue({
    el: '#app',
    store: store,
    router: router
});
