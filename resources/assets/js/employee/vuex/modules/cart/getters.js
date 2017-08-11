const count = state => state.counter;

const cartItemsCount = state => {
  const { cartItems } = state;
  const count = Object.keys(cartItems).reduce((total, date) => {
      const subTotal = cartItems[date].reduce((tot, item) => tot + item.qty, 0);

      return subTotal + total;
  }, 0)

  return count;
};

const groupedCartItems = state => {
  const { cartItems } = state;

  const groupedItems = Object.keys(cartItems).map(date => ({
    date,
    items: cartItems[date],
  }))

  return groupedItems.reverse();
};

const cartTotal = state => {
  const { cartItems } = state;
  const total = Object.keys(cartItems).reduce((total, date) => {
    const subTotal = cartItems[date].reduce((tot, item) => tot + (item.price * item.qty), 0);

    return subTotal + total;
  }, 0)

  return total;
};

const allCartItemIds = state => {
  const { cartItems } = state;
  let allIds = [];

  Object.keys(cartItems).forEach(date => {
    const itemsByDate = cartItems[date];

    allIds = allIds.concat(itemsByDate.map(item => item.id));
  });

  return allIds;
};

export default {
  count,
  cartTotal,
  cartItemsCount,
  allCartItemIds,
  groupedCartItems,
};
