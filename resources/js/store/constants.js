export const ITEM_DETAILS = [
  {
    key: 'total_sale_amount',
    link: 'transactions/sale'
  },
  {
    key: 'total_purchase_amount',
    link: 'transactions/buying'
  },
  {
    key: 'paid_amount',
  },
  {
    key: 'gain_loss',
  },
  {
    key: 'total_commission_amount',
  },
  {
    key: 'total_dividend_gain',
    change_color: true,
    link: 'transactions/dividend'
  },
  {
    key: 'total_bonus_share',
    change_color: true,
    link: 'transactions/bonus'
  },
  {
    key: 'total_rights_share',
  },
  {
    key: 'total_gain',
  },
  {
    key: 'instant_gain',
  },
];

export const TRANSACTION_TYPES = {
  Buying: 0,
  Sale: 1,
  Dividend: 2,
  Bonus: 3,
  Rights: 4,
};

export const TRANSACTION_TYPES_MAP = [
  'Buying',
  'Sale',
  'Dividend',
  'Bonus',
  'Rights',
];

export const USER_TYPES = {
  user: 2,
  admin: 9,
};

export const DEFAULT_SNACKBAR = Object.freeze({
  position_y: 'top',
  position_x: null,
  color: 'success',
  mode: '',
  msg: '',
  timeout: 6000,
});
