import { defineStore } from 'pinia'
import axios from '@/resources/axios.config'

export const useStatusStore = defineStore('statusCrud', {
  state: () => ({
    activeContent: []
  }),
  getters: {
    status: (state) => state.activeStatus
  },
  actions: {
    async getActiveContent() {
      try {
        const data = await axios.get(`http://localhost:8000/api/status`)
        this.activeContent = data.data
      } catch (error) {
        throw JSON.stringify(error.response.data.error)
      }
    }
  }
})
