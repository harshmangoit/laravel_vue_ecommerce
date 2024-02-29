import { defineStore } from "pinia";

export const useAuthStore = defineStore("auth", {
    state: () => ({
        accessToken: localStorage.getItem("accessToken") || null,
        userData: JSON.parse(localStorage.getItem("userData")) || null,
        isLoggedIn: localStorage.getItem("accessToken") !== null,
        locale: localStorage.getItem("locale") || 'EN'
    }),
    getters: {
        // user: (state) => state.authUser,
        getToken: (state) => state.accessToken,
        getUser: (state) => state.userData,
    },
    actions: {
        setToken(accessToken) {
            localStorage.setItem("accessToken", accessToken);
            this.accessToken = accessToken;
            this.isLoggedIn = true;
        },
        removeToken() {
            localStorage.removeItem("accessToken");
            this.accessToken = null;
            this.isLoggedIn = false;
        },
        setUser(userData) {
            localStorage.setItem("userData", JSON.stringify(userData));
            this.userData = userData;
        },
        removeUser() {
            localStorage.removeItem("userData");
            this.userData = null;
        },
    },
});
