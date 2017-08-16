import axios from 'axios';

// Action Types
const RECEIVE_CART_ITEMS = '@cart/RECEIVE_CART_ITEMS';
const ADD_CART_ITEM = '@cart/ADD_CART_ITEM';
const REMOVE_CART_ITEM = '@cart/REMOVE_CART_ITEM';
const UPDATE_CART_ITEM_QTY = '@cart/UPDATE_CART_ITEM_QTY';
const SET_ALREADY_PLACED_ORDER = '@cart/SET_ALREADY_PLACED_ORDER';
const CLEAR_CART_ITEMS = '@cart/CLEAR_CART_ITEMS';

export const updateCartItemQty = (context, payload) => {
  context.commit(UPDATE_CART_ITEM_QTY, payload);
};

export const removeCartItem = (context, payload) => {
  context.commit(REMOVE_CART_ITEM, payload);
};

export const setAlreadyPlacedOrder = (context, payload) => {
  context.commit(SET_ALREADY_PLACED_ORDER, { alreadyPlacedOrder: payload });

  if (payload) {
    context.commit(CLEAR_CART_ITEMS);
  }
};

// Async Actions
const fetchCartItems = (context) => {
  return axios.get('/api/v1/cart')
    .then(({ data }) => {
      let cartItems;

      if (Array.isArray(data.items) && data.items.length === 0) {
        cartItems = {};
      } else {
        cartItems = data.items;
      }

      context.commit(RECEIVE_CART_ITEMS, { cartItems });
      context.commit(SET_ALREADY_PLACED_ORDER, { alreadyPlacedOrder: data.already_placed_order });
    });
};

export default {
  fetchCartItems,
  updateCartItemQty,
  removeCartItem,
  setAlreadyPlacedOrder,
};

export const TYPES = {
  ADD_CART_ITEM,
  CLEAR_CART_ITEMS,
  REMOVE_CART_ITEM,
  RECEIVE_CART_ITEMS,
  UPDATE_CART_ITEM_QTY,
  SET_ALREADY_PLACED_ORDER,
};
