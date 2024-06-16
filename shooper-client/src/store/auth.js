import { defineStore } from 'pinia'
import axios from '@/resources/axios.config'

export const useAuthStore = defineStore('auth', {
  state: () => ({
    authUser: null,
    authStatus: false
  }),
  getters: {
    user: (state) => state.authUser,
    status: (state) => state.authStatus
  },
  actions: {
    async getToken() {
      try {
        await axios.get('http://localhost:8000/sanctum/csrf-cookie')
      } catch (error) {
        throw JSON.stringify(error)
      }
    },
    async getUser() {
      try {
        const data = await axios.get('http://localhost:8000/api/auth/user')
        this.authUser = data.data.data
        this.authStatus = true
      } catch (error) {
        throw JSON.stringify(error)
      }
    },
    async signIn(email, password) {
      try {
        await this.getToken()
        await axios.post('http://localhost:8000/api/auth/signin', {
          email: email,
          password: password
        })
        alert('Signed In Successfully!')
        await this.getUser()
        return await this.isAdmin
      } catch (error) {
        throw JSON.stringify(error.response.data.error)
      }
    },
    async signUp(name, email, password) {
      try {
        await axios.post('http://localhost:8000/api/auth/signup', {
          name: name,
          email: email,
          password: password
        })
        alert('Signed Up Successfully!')
      } catch (error) {
        throw JSON.stringify(error.response.data.message)
      }
    },
    async signOut() {
      try {
        await axios.post('http://localhost:8000/api/auth/signout')
        this.authUser = null
        this.authStatus = false
      } catch (error) {}
    }
  }
})
