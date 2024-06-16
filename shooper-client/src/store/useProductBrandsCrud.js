import { defineStore } from 'pinia'
import axios from '@/resources/axios.config'
import { useCrudOptionsStore } from '@/store/useCrudOptions'
const crudOptionsStore = useCrudOptionsStore()

export const useProductBrandsStore = defineStore('productBrandsCrud', {
  state: () => ({
    activeContent: [],
    archivedContent: [],
    name: null,
    slogan: null,
    params: {
      paginate: null,
      filter: {}
    }
  }),
  getters: {
    getVariables: (state) => {
      const variables = {}
      if (state.name !== null) {
        variables.name = state.name
      }
      if (state.slogan !== null) {
        variables.slogan = state.slogan
      }
      return variables
    }
  },
  actions: {
    //#region GetContent

    async getActiveContent() {
      try {
        const data = await axios.get(
          `http://localhost:8000/api/product/brand?page=${crudOptionsStore.getCurrentPage}`,
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
          `http://localhost:8000/api/product/brand?page=${crudOptionsStore.getCurrentPage}&filter[trashed]=only`,
          { params: this.params }
        )
        this.archivedContent = data.data
      } catch (error) {
        throw JSON.stringify(error.response.data.message)
      }
    },

    //#endregion

    //#region Actions

    async postContent() {
      try {
        await axios.post(`http://localhost:8000/api/product/brand`, this.getVariables)
      } catch (error) {
        throw JSON.stringify(error.response.data.message)
      }
    },
    async patchContent(id) {
      if (!id) {
        throw JSON.stringify('User ID is necessary!')
      }
      try {
        await axios.patch(`http://localhost:8000/api/product/brand/${id}`, this.getVariables)
      } catch (error) {
        throw JSON.stringify(error.response.data.message)
      }
    },
    async deleteContent(id) {
      if (!id) {
        throw JSON.stringify('User ID is necessary!')
      }
      try {
        await axios.delete(`http://localhost:8000/api/product/brand/${id}`)
      } catch (error) {
        throw JSON.stringify(error.response.data.message)
      }
    },
    async restoreContent(id) {
      if (!id) {
        throw JSON.stringify('User ID is necessary!')
      }
      try {
        await axios.post(`http://localhost:8000/api/restore/product/brand/${id}`)
      } catch (error) {
        throw JSON.stringify(error.response.data.message)
      }
    },
    async forceDeleteContent(id) {
      if (!id) {
        throw JSON.stringify('User ID is necessary!')
      }
      try {
        await axios.post(`http://localhost:8000/api/force-delete/product/brand/${id}`)
      } catch (error) {
        throw JSON.stringify(error.response.data.message)
      }
    }

    //#endregion
  }
})
