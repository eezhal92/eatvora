import { TYPES } from './actions';

export default {

  [TYPES.RECEIVE_CART_ITEMS](state, payload) {
    state.cartItems = payload.cartItems;
  },

  [TYPES.ADD_CART_ITEM](state, payload) {
    const itemsGroup = state.cartItems[payload.date];

    if (itemsGroup) {
      const isInGroup = itemsGroup.find(item => item.id === payload.meal.id);

      if (isInGroup) {
        state.cartItems[payload.date] = state.cartItems[payload.date].map((meal) => {
          if (meal.id === payload.meal.id) {
            meal.qty += 1;
          }

          return meal;
        });
      } else {
        state.cartItems[payload.date] = state.cartItems[payload.date].concat(payload.meal);
      }
    } else {
      // @see https://vuejs.org/v2/guide/reactivity.html#Change-Detection-Caveats
      state.cartItems = { ...state.cartItems, [payload.date]: [payload.meal] };
    }
  },

};
