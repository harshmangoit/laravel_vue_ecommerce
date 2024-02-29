import { useAuthStore } from '../stores/auth';

// const authStore = useAuthStore();

// export const isUserLoggedIn = () => (authStore.getUser && authStore.getToken)
export const isUserLoggedIn = () => (localStorage.getItem('userData') && localStorage.getItem('accessToken'))