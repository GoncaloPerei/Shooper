import { defineStore } from 'pinia'
import axios from '@/resources/axios.config'

export const useRolesStore = defineStore('rolesCrud', {
  state: () => ({
    activeRoles: []
  }),
  getters: {
    roles: (state) => state.activeRoles
  },
  actions: {
    async getActiveContent() {
      try {
        const data = await axios.get(`http://localhost:8000/api/roles`)
        this.activeRoles = data.data
      } catch (error) {
        throw JSON.stringify(error.response.data.error)
      }
    }
  }
})
