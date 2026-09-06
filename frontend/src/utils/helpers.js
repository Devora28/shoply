export const calcDiscount = (price,discount) => {
  return price - (price * discount / 100);
}
export function formatPrice(n) {
  return '$' + Number(n).toLocaleString('en-US', {
    minimumFractionDigits: 0,
    maximumFractionDigits: 2
  })
}
