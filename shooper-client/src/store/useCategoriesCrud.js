import { defineStore } from 'pinia'
import axios from '@/resources/axios.config'
import { useCrudOptionsStore } from '@/store/useCrudOptions'
const crudOptionsStore = useCrudOptionsStore()

export const useCategoriesStore = defineStore('categoriesCrud', {
  state: () => ({
    activeContent: [],
    archivedContent: [],
    filteredCategories: [],
    id: null,
    name: null,
    desc: null,
    filename: null,
    category: {},
    params: {
      paginate: null,
      paginate: null,
      include: null,
      filter: {
        'product.status_id': null,
        'productUrl.status_id': null
      },
      trashed: null
    }
  }),
  getters: {
    categories: (state) => state.activeCategories,
    archCategories: (state) => state.archivedCategories,
    filterUnauCategories: (state) => {
      try {
        return state.filteredCategories.data.slice(0, 4)
      } catch (error) {
        return []
      }
    },
    getImgFile: (state) => state.imgFile,
    getVariables: (state) => {
      const variables = {}
      if (state.name != null) {
        variables.name = state.name
      }
      if (state.desc != null) {
        variables.desc = state.desc
      }
      if (state.filename != null) {
        variables.filename = state.filename
      }

      return variables
    }
  },
  actions: {
    //#region GetContent

    async getActiveContent() {
      try {
        const data = await axios.get(
          `http://localhost:8000/api/customer/product/category?page=${crudOptionsStore.getCurrentPage}`,
          { params: this.params }
        )
        this.activeContent = data.data
      } catch (error) {
        throw JSON.stringify(error)
      }
    },
    async getArchivedContent() {
      try {
        const data = await axios.get(
          `http://localhost:8000/api/product/category?filter[trashed]=only&page=${crudOptionsStore.getCurrentPage}`,
          { params: this.params }
        )
        this.archivedContent = data.data
      } catch (error) {
        throw JSON.stringify(error.response.data.error)
      }
    },
    async getCategory() {
      try {
        const data = await axios.get(`http://localhost:8000/api/product/category/${this.id}`, {
          params: this.params
        })
        this.category = data.data.data
      } catch (error) {
        throw JSON.stringify(error.response.data.error)
      }
    },

    //#endregion

    async getCustomerContent() {
      try {
        const data = await axios.get(`http://localhost:8000/api/customer/product/category`, {
          params: this.params
        })
        this.filteredCategories = data.data
      } catch (error) {
        throw JSON.stringify(error.response.data.error)
      }
    },

    //#region Actions

    async postContent() {
      try {
        await axios.post('http://localhost:8000/api/product/category', this.getVariables)
      } catch (error) {
        console.log(error)
        throw JSON.stringify(error.response.data.message)
      }
    },
    async putContent(id) {
      if (!id) {
        throw new JSON.stringify('User ID is necessary!')
      }
      try {
        await axios.patch(`http://localhost:8000/api/product/category/${id}`, this.getVariables)
      } catch (error) {
        throw JSON.stringify(error.response.data.message)
      }
    },
    async deleteContent(id) {
      try {
        await axios.delete(`http://localhost:8000/api/product/category/${id}`)
      } catch (error) {
        throw JSON.stringify(error.response.data.message)
      }
    },
    async restoreContent(id) {
      try {
        await axios.post(`http://localhost:8000/api/restore/product/category/${id}`)
      } catch (error) {
        throw JSON.stringify(error.response.data.message)
      }
    },
    async forceDeleteContent(id) {
      try {
        await axios.post(`http://localhost:8000/api/force-delete/product/category/${id}`)
      } catch (error) {
        throw JSON.stringify(error.response.data.message)
      }
    }

    //#endregion
  }
})
