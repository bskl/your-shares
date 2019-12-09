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
    change_color: true,
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
    key: 'total_gain',
    change_color: true,
  },
  {
    key: 'instant_gain',
    change_color: true,
  },
];

export const TRANSACTION_TYPES = [
  'Buying',
  'Sale',
  'Dividend',
  'Bonus',
  'Rights'
];
