import { defineStore } from 'pinia'

export const useCrudOptionsStore = defineStore('crudOptions', {
  state: () => ({
    page: 0
  }),
  getters: {
    getCurrentPage: (state) => state.page + 1
  },
  actions: {}
})
