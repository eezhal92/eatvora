import axios from 'axios';

// Action Types
const RECEIVE_CART_ITEMS = '@cart/RECEIVE_CART_ITEMS';
const ADD_CART_ITEM = '@cart/ADD_CART_ITEM';

// Async Actions
const fetchCartItems = (context) => {
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
};

export default {
  fetchCartItems,
};

export const TYPES = {
  RECEIVE_CART_ITEMS,
  ADD_CART_ITEM,
};
