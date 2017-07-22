require('../bootstrap');

const Vue = require('vue');

Vue.component('employee-list', require('./modules/company/EmployeeList.vue'));
Vue.component('employee-list-button', require('./modules/company/EmployeeListButton.vue'));

new Vue({
    el: '#app',
});
