
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

const moment = require('moment');
moment.locale('ID');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('example', require('./components/Example.vue'));
Vue.component('meal-list', require('./components/MealList.vue'));
Vue.component('meal-filter', require('./components/MealFilter.vue'));
Vue.component('cart', require('./components/Cart.vue'));
Vue.component('cart-count', require('./components/CartCount.vue'));

Vue.filter('date',  value => moment(value).format('DD MMMM YYYY'));
Vue.filter('rupiah',  value => `Rp. ${value.toLocaleString()}`);

const app = new Vue({
    el: '#app',
});
