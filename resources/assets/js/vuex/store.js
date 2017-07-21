import Vuex from 'vuex';
import axios from 'axios';

const state = {
  counter: 0,
  cartItems: {},
};

const RECEIVE_CART_ITEMS = '@cart/RECEIVE_CART_ITEMS';
const ADD_CART_ITEM = '@cart/ADD_CART_ITEM';

const actions = {
  fetchCartItems(context) {
    return axios.get('/api/v1/cart')
      .then(({ data }) => {
        let cartItems;

        if (Array.isArray(data) && data.length === 0) {
          cartItems = {};
        } else {
          cartItems = data;
        }

        context.commit(RECEIVE_CART_ITEMS, {
          cartItems,
        })
      });
  }
};

const getters = {
  count: state => state.counter,
  cartItemsCount: state => {
    const { cartItems } = state;
    const count = Object.keys(cartItems).reduce((total, date) => {
        const subTotal = cartItems[date].reduce((tot, item) => tot + item.qty, 0);

        return subTotal + total;
    }, 0)

    return count;
  },
  groupedCartItems: state => {
    const { cartItems } = state;

    const groupedItems = Object.keys(cartItems).map(date => ({
      date,
      items: cartItems[date],
    }))

    return groupedItems.reverse();;
  },
  cartTotal: state => {
    const { cartItems } = state;
    const total = Object.keys(cartItems).reduce((total, date) => {
        const subTotal = cartItems[date].reduce((tot, item) => tot + (item.price * item.qty), 0);

        return subTotal + total;
    }, 0)

    return total;
  },
  allCartItemIds: state => {
    const { cartItems } = state;
    let allIds = [];

    Object.keys(cartItems).forEach(date => {
      const itemsByDate = cartItems[date];

      allIds = allIds.concat(itemsByDate.map(item => item.id));
    });

    return allIds;
  },
};

const mutations = {
  [RECEIVE_CART_ITEMS](state, payload) {
    state.cartItems = payload.cartItems;
  },
  [ADD_CART_ITEM](state, payload) {
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
  }
};
const store = new Vuex.Store({
  strict: true,
  state,
  mutations,
  getters,
  actions,
});

export default store;
