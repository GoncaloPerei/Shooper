import { defineStore } from 'pinia'
import axios from '@/resources/axios.config'
import { useCrudOptionsStore } from '@/store/useCrudOptions'
const crudOptionsStore = useCrudOptionsStore()
import { useStatusStore } from '@/store/useStatusCrud'
const statusStore = useStatusStore()

export const useProductsStoresStore = defineStore('storesCrud', {
  state: () => ({
    activeContent: [],
    archivedContent: [],
    statuses: [],
    name: null,
    website_url: null,
    filename: null,
    title_tag: null,
    price_tag: null,
    scratched_price_tag: null,
    status: {},
    params: {
      page: crudOptionsStore.page + 1,
      filter: {
        status_id: null
      }
    }
  }),
  getters: {
    getVariables: (state) => {
      const variables = {}
      if (state.name != null) {
        variables.name = state.name
      }
      if (state.website_url != null) {
        variables.website = state.website_url
      }
      if (state.title_tag != null) {
        variables.titleTag = state.title_tag
      }
      if (state.price_tag != null) {
        variables.priceTag = state.price_tag
      }
      if (state.scratched_price_tag != null) {
        variables.scratchedPriceTag = state.scratched_price_tag
      }
      if (state.status.id != null) {
        variables.status = state.status.id
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
        const data = await axios.get(`http://localhost:8000/api/stores`, {
          params: this.params
        })
        this.activeContent = data.data
      } catch (error) {
        throw JSON.stringify(error.response.data.error)
      }
    },
    async getArchivedContent() {
      try {
        const data = await axios.get(`http://localhost:8000/api/stores?filter[trashed]=only`, {
          params: this.params
        })
        this.archivedContent = data.data
      } catch (error) {
        throw JSON.stringify(error.response.data.error)
      }
    },

    //#endregion

    //#region GetOptions

    async getStatus() {
      await statusStore.getActiveContent()
      if (!this.statuses.length) {
        for (let status of statusStore.status?.data) {
          this.statuses.push({ name: status?.name, id: status?.id })
        }
      }
    },

    //#endregion

    //#region Actions

    async postContent() {
      try {
        await axios.post('http://localhost:8000/api/stores', this.getVariables)
      } catch (error) {
        throw JSON.stringify(error.response.data.message)
      }
    },
    async putContent(id) {
      if (!id) {
        throw JSON.stringify('User ID is necessary!')
      }
      try {
        await axios.patch(`http://localhost:8000/api/stores/${id}`, this.getVariables)
      } catch (error) {
        throw JSON.stringify(error.response.data.message)
      }
    },
    async deleteContent(id) {
      try {
        await axios.delete(`http://localhost:8000/api/stores/${id}`)
      } catch (error) {
        throw JSON.stringify(error.response.data.message)
      }
    },
    async restoreContent(id) {
      try {
        await axios.post(`http://localhost:8000/api/restore/stores/${id}`)
      } catch (error) {
        throw JSON.stringify(error.response.data.message)
      }
    },
    async forceDeleteContent(id) {
      try {
        await axios.post(`http://localhost:8000/api/force-delete/stores/${id}`)
      } catch (error) {
        throw JSON.stringify(error.response.data.message)
      }
    }

    //#endregion
  }
})
