import { defineStore } from 'pinia'
import axios from '@/resources/axios.config'

export const useProductListsStore = defineStore('productListsCrud', {
  state: () => ({
    activeContent: [],
    lists: [],
    list: null,
    listId: null,
    productId: null,
    name: null,
    desc: null
  }),
  getters: {
    getList: (state) => state.list.data,
    getVariables: (state) => {
      const variables = {}
      if (state.name !== null) {
        variables.name = state.name
      }
      if (state.desc !== null) {
        variables.desc = state.desc
      }
      if (state.productId !== null) {
        variables.product_id = state.productId
      }
      return variables
    }
  },
  actions: {
    async getProductList() {
      try {
        const data = await axios.get(`http://localhost:8000/api/product/list/${this.listId}`)
        this.list = data.data
      } catch (error) {
        throw JSON.stringify(error)
      }
    },
    async setListsOptions() {
      try {
        if (!this.lists.length) {
          for (let list of this.activeContent?.data) {
            this.lists.push({ name: list?.name, id: list?.id })
          }
        }
      } catch (error) {
        console.log(error)
      }
    },
    async getActiveContent() {
      try {
        const data = await axios.get(`http://localhost:8000/api/product/list`)
        this.activeContent = data.data
        await this.setListsOptions()
      } catch (error) {
        console.log(error)
        throw JSON.stringify(error)
      }
    },
    async addList() {
      try {
        await axios.post('http://localhost:8000/api/product/list', this.getVariables)
      } catch (error) {
        throw JSON.stringify(error.response.data.message)
      }
    },
    async editList(id) {
      try {
        await axios.patch(`http://localhost:8000/api/product/list/${id}`, this.getVariables)
      } catch (error) {
        throw JSON.stringify(error.response.data.message)
      }
    },
    async deleteList(id) {
      try {
        await axios.delete(`http://localhost:8000/api/product/list/${id}`)
      } catch (error) {
        throw JSON.stringify(error.response.data.message)
      }
    },
    async addProduct() {
      try {
        await axios.post(
          `http://localhost:8000/api/product/list/${this.listId.id}/add-product`,
          this.getVariables
        )
      } catch (error) {
        throw JSON.stringify(error)
      }
    },
    async removeProduct() {
      try {
        await axios.post(
          `http://localhost:8000/api/product/list/${this.listId}/remove-product`,
          this.getVariables
        )
      } catch (error) {
        throw JSON.stringify(error)
      }
    }
  }
})
