export const calcDiscount = (price,discount) => {
  return price - (price * discount / 100);
}
export function formatPrice(n) {
  return '$' + n.toLocaleString('en-US', { minimumFractionDigits: 0, maximumFractionDigits: 0 })
}
