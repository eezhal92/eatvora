require('../bootstrap');

const Vue = require('vue');

Vue.component('employee-list', require('./modules/company/EmployeeList.vue'));
Vue.component('employee-list-button', require('./modules/company/EmployeeListButton.vue'));
Vue.component('add-employee-form', require('./modules/company/AddEmployeeForm.vue'));

new Vue({
    el: '#app',
});
