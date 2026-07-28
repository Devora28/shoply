export const endpoints  = {
  home: '/',
  categories: '/categories',
  loginOtpRequest: '/auth/otp/request',
  authOtp: '/auth/login/otp',
  authPassword: '/auth/login/password',
  authUser: '/auth/user',
  logout: '/auth/logout',
  product: (id) => `/products/${id}`,
}
