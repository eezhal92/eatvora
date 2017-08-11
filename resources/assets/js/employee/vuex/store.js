import Vuex from 'vuex';
import cart from './modules/cart';

const store = new Vuex.Store({
  strict: true,
  modules: {
    cart,
  },
});

export default store;
