import { defineStore } from 'pinia'
import axios from '@/resources/axios.config'
import { useCrudOptionsStore } from '@/store/useCrudOptions'
const crudOptionsStore = useCrudOptionsStore()
import { useStatusStore } from '@/store/useStatusCrud'
const statusStore = useStatusStore()
import { useCategoriesStore } from '@/store/useCategoriesCrud'
const categoriesStore = useCategoriesStore()

export const useProductUrlsStore = defineStore('productUrlsCrud', {
  state: () => ({
    activeContent: [],
    archivedContent: [],
    filteredContent: [],
    statuses: [],
    categories: [],
    id: null,
    url: null,
    status: {},
    product: {},
    params: {
      paginate: null,
      filter: {
        status_id: null
      }
    }
  }),
  getters: {
    productUrls: (state) => state.activeProductUrls,
    archProductUrls: (state) => state.archivedProductUrls,
    getVariables: (state) => {
      const variables = {}
      if (state.url !== null) {
        variables.url = state.url
      }
      if (state.status.id !== null) {
        variables.status = state.status.id
      }
      if (state.product.code !== null) {
        variables.product = state.product.code
      }
      return variables
    }
  },
  actions: {
    //#region GetContent

    async getActiveContent() {
      try {
        const data = await axios.get(
          `http://localhost:8000/api/product/url?page=${crudOptionsStore.getCurrentPage}`,
          { params: this.params }
        )
        this.activeContent = data.data
      } catch (error) {
        throw JSON.stringify(error.response.data.message)
      }
    },
    async getArchivedContent() {
      try {
        const data = await axios.get(
          `http://localhost:8000/api/product/url?page=${crudOptionsStore.getCurrentPage}&filter[trashed]=only`
        )
        this.archivedContent = data.data
      } catch (error) {
        throw JSON.stringify(error.response.data.message)
      }
    },
    async getFilteredContent() {
      try {
        const data = await axios.get(
          `http://localhost:8000/api/product/url?page=${crudOptionsStore.getCurrentPage}`,
          { params: this.params }
        )
        this.filteredContent = data.data
      } catch (error) {
        throw JSON.stringify(error.response.data.error)
      }
    },

    //#endregion

    //#region GetOptions

    async getCategories() {
      await categoriesStore.getActiveContent()
      if (!this.categories.length) {
        for (let category of categoriesStore.activeContent.data) {
          const products =
            category?.product?.map((product) => ({ label: product.name, code: product.id })) || []
          this.categories.push({
            label: category.name,
            code: category.id,
            items: products
          })
        }
      }
    },
    async getStatus() {
      await statusStore.getActiveContent()
      if (!this.statuses.length) {
        for (let status of statusStore.activeContent.data) {
          this.statuses.push({ name: status.name, id: status.id })
        }
      }
    },

    //#endregion

    //#region Actions

    async postContent() {
      try {
        await axios.post('http://localhost:8000/api/product/url', this.getVariables)
      } catch (error) {
        throw JSON.stringify(error.response.data.message)
      }
    },
    async putContent(id) {
      if (!id) {
        throw JSON.stringify('User ID is necessary!')
      }
      try {
        await axios.patch(`http://localhost:8000/api/product/url/${id}`, this.getVariables)
      } catch (error) {
        throw JSON.stringify(error.response.data.message)
      }
    },
    async deleteContent(url) {
      try {
        await axios.delete(`http://localhost:8000/api/product/url/${url}`)
      } catch (error) {
        throw JSON.stringify(error.response.data.message)
      }
    },
    async restoreContent(id) {
      try {
        await axios.post(`http://localhost:8000/api/restore/product/url/${id}`)
      } catch (error) {
        throw JSON.stringify(error.response.data.message)
      }
    },
    async forceDeleteContent(id) {
      try {
        await axios.post(`http://localhost:8000/api/force-delete/product/url/${id}`)
      } catch (error) {
        throw JSON.stringify(error.response.data.message)
      }
    }

    //#endregion
  }
})
