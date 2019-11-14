/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */
 
require('./bootstrap');

window.Vue = require('vue');
// window.Vue = require('bootstrap-vue');

import BootstrapVue from 'bootstrap-vue';
import feather from 'vue-icon';
var SocialSharing = require('vue-social-sharing');
import { library } from '@fortawesome/fontawesome-svg-core';
import { faFacebookSquare } from '@fortawesome/free-brands-svg-icons';
import { faTwitterSquare } from '@fortawesome/free-brands-svg-icons';
import { faTelegram } from '@fortawesome/free-brands-svg-icons';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import AsyncComputed from 'vue-async-computed'

library.add(faFacebookSquare);
library.add(faTwitterSquare);
library.add(faTelegram);

Vue.component('font-awesome-icon', FontAwesomeIcon);

Vue.use(SocialSharing);
Vue.use(feather, 'v-icon');
Vue.use(BootstrapVue);
Vue.use(AsyncComputed);

import 'bootstrap/dist/css/bootstrap.css';
import 'bootstrap-vue/dist/bootstrap-vue.css';

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('products', require('./components/ProductsComponent.vue').default);
Vue.component('product', require('./components/ProductComponent.vue').default);
Vue.component('orders', require('./components/OrdersComponent.vue').default);
Vue.component('admin-products', require('./components/AdminProductsComponent.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
});
