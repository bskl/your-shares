export const ITEM_DETAILS = {
  0:{
    key: 'total_sale_amount',
    type: 'currency',
    change_color: false,
  },
  1: {
    key: 'total_purchase_amount',
    type: 'currency',
    change_color: false,
  },
  2: {
    key: 'paid_amount',
    type: 'currency',
    change_color: false,
  },
  3: {
    key: 'gain_loss',
    type: 'currency',
    change_color: true,
  },
  4: {
    key: 'total_commission_amount',
    type: 'currency',
    change_color: false,
  },
  5: {
    key: 'total_dividend_gain',
    type: 'currency',
    change_color: true,
  },
  6: {
    key: 'total_bonus_share',
    type: 'decimal',
    change_color: true,
  },
  7: {
    key: 'total_gain',
    type: 'currency',
    change_color: true,
  },
  7: {
    key: 'instant_gain',
    type: 'currency',
    change_color: true,
  },
};

export const TRANSACTION_TYPES = [
  'Buying',
  'Sale',
  'Dividend',
  'Bonus Share',
];
