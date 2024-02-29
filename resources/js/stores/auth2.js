import { defineStore } from 'pinia'
import {ref} from 'vue'

export const useAuthStore = defineStore('auth', () => {
  const accessToken = localStorage.getItem('accessToken')
  const userData = localStorage.getItem('userData')
  const isLoggedIn = ref(!!accessToken)

  function setToken(token) {
    accessToken.value = token
    isLoggedIn.value = true
  }

  function removeToken() {
    accessToken.value = null
    isLoggedIn.value = false
  }

  function setUser(user) {
    userData.value = user
  }

  function removeUser() {
    userData.value = null
  }

  return {
    accessToken,
    userData,
    isLoggedIn,
    setToken,
    removeToken,
    setUser,
    removeUser,
  }
})
