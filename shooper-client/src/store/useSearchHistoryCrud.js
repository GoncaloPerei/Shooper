import { defineStore } from 'pinia'
import axios from '@/resources/axios.config'

export const useSearchHistoryStore = defineStore('searchHistoryCrud', {
  state: () => ({
    searchHistory: [],
    search: null
  }),
  getters: {
    getVariables: (state) => {
      const variables = {}
      if (state.search !== null) {
        variables.search = state.search
      }
      return variables
    }
  },
  actions: {
    async getContent() {
      try {
        await axios.post(`http://localhost:8000/api/search-history`, this.getVariables)
      } catch (error) {
        throw JSON.stringify(error.response.data.error)
      }
    },
    async postContent() {
      try {
        await axios.post(`http://localhost:8000/api/search-history`, this.getVariables)
      } catch (error) {
        throw JSON.stringify(error.response.data.error)
      }
    },
    async deleteContent(search_history) {
      try {
        await axios.delete(`http://localhost:8000/api/search-history/${search_history}`)
      } catch (error) {
        throw JSON.stringify(error.response.data.error)
      }
    }
  }
})
