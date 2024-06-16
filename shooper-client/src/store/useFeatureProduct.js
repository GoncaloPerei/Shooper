import { defineStore } from 'pinia'
import axios from '@/resources/axios.config'

export const useFeatureProductStore = defineStore('featureProduct', {
  state: () => ({
    featureProduct: null
  }),
  getters: {
    product: (state) => state.featureProduct
  },
  actions: {
    async getFeatureProduct() {
      try {
        const data = await axios.get(`http://localhost:8000/api/customer/featured-product`)
        this.featureProduct = data.data.data
      } catch (error) {
        throw JSON.stringify(error.response.statusText)
      }
    }
  }
})
