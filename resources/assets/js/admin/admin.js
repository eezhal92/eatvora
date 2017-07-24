require('../bootstrap');

const Vue = require('vue');

Vue.component('employee-list', require('./modules/company/EmployeeList.vue'));
Vue.component('employee-list-button', require('./modules/company/EmployeeListButton.vue'));
Vue.component('company-show-page-modals', require('./modules/company/CompanyShowPageModals.vue'));
Vue.component('add-employee-modal', require('./modules/company/AddEmployeeModal.vue'));
Vue.component('edit-employee-modal', require('./modules/company/EditEmployeeModal.vue'));
Vue.component('delete-employee-modal', require('./modules/company/DeleteEmployeeModal.vue'));

Vue.component('action-options-popover', require('./components/ActionOptionsPopover.vue'));
Vue.component('action-options-popover-menu', require('./components/ActionOptionsPopoverMenu.vue'));
Vue.component('base-modal', require('./components/BaseModal.vue'));


Vue.directive('click-outside', {
  bind: function(el, binding, vNode) {
    // Provided expression must evaluate to a function.
    if (typeof binding.value !== 'function') {
      const compName = vNode.context.name
      let warn = `[Vue-click-outside:] provided expression '${binding.expression}' is not a function, but has to be`
      if (compName) { warn += `Found in component '${compName}'` }

      console.warn(warn)
    }
    // Define Handler and cache it on the element
    const bubble = binding.modifiers.bubble
    const handler = (e) => {
      if (bubble || (!el.contains(e.target) && el !== e.target)) {
        binding.value(e)
      }
    }
    el.__vueClickOutside__ = handler

    // add Event Listeners
    document.addEventListener('click', handler)
  },

  unbind: function(el, binding) {
    // Remove Event Listeners
    document.removeEventListener('click', el.__vueClickOutside__)
    el.__vueClickOutside__ = null

  }
});

new Vue({
  el: '#app',
});
