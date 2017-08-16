import actions from './actions';
import getters from './getters';
import mutations from './mutations';

const state = {
  counter: 0,
  cartItems: {},
  alreadyPlacedOrder: false,
};

export default {
  state,
  actions,
  getters,
  mutations,
};
