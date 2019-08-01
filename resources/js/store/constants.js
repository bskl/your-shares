export const ITEM_DETAILS = {
  0:{
    key: 'total_sale_amount',
    link: 'transactions/sale'
  },
  1: {
    key: 'total_purchase_amount',
    link: 'transactions/buying'
  },
  2: {
    key: 'paid_amount',
  },
  3: {
    key: 'gain_loss',
    change_color: true,
  },
  4: {
    key: 'total_commission_amount',
  },
  5: {
    key: 'total_dividend_gain',
    change_color: true,
    link: 'transactions/dividend'
  },
  6: {
    key: 'total_bonus_share',
    change_color: true,
    link: 'transactions/bonus'
  },
  7: {
    key: 'total_gain',
    change_color: true,
  },
  8: {
    key: 'instant_gain',
    change_color: true,
  },
};

export const TRANSACTION_TYPES = [
  'Buying',
  'Sale',
  'Dividend',
  'Bonus',
];
