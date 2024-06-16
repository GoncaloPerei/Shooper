import { defineStore } from 'pinia'
import axios from '@/resources/axios.config'
import { useCrudOptionsStore } from '@/store/useCrudOptions'
const crudOptionsStore = useCrudOptionsStore()
import { useStatusStore } from '@/store/useStatusCrud'
const statusStore = useStatusStore()
import { useCategoriesStore } from '@/store/useCategoriesCrud'
const categoriesStore = useCategoriesStore()
import { useProductBrandsStore } from './useProductBrandsCrud'
const productBrandsStore = useProductBrandsStore()

export const useProductsStore = defineStore('productsCrud', {
  state: () => ({
    product: null,
    activeContent: [],
    archivedContent: [],
    filteredContent: [],
    favourite: null,
    alert: null,
    statuses: [],
    categories: [],
    brands: [],
    id: null,
    alertId: null,
    name: null,
    filename: null,
    product_brand_id: [],
    category: {},
    status: {},
    brand: {},
    minPrice: '',
    maxPrice: '',
    desiredPrice: null,
    params: {
      paginate: null,
      filter: {
        name: null,
        status_id: null,
        product_category_id: null,
        product_brand_id: [],
        'productUrl.status_id': null,
        price_range: null
      }
    }
  }),
  getters: {
    products: (state) => state.activeProducts,
    archProducts: (state) => state.archivedProducts,
    selectedProduct: (state) => state.product,
    getVariables: (state) => {
      const variables = {}
      if (state.name !== null) {
        variables.name = state.name
      }
      if (state.status.id !== null) {
        variables.status = state.status.id
      }
      if (state.category.id !== null) {
        variables.category = state.category.id
      }
      if (state.filename !== null) {
        variables.filename = state.filename
      }
      if (state.brand.id !== null) {
        variables.brand = state.brand.id
      }
      return variables
    }
  },
  actions: {
    //#region GetContent

    async getActiveContent() {
      if (Array.isArray(this.product_brand_id)) {
        this.params.filter.product_brand_id = this.product_brand_id.join(',')
      }
      try {
        const data = await axios.get(
          `http://localhost:8000/api/products?page=${crudOptionsStore.getCurrentPage}`,
          {
            params: this.params,
            paramsSerializer: {
              indexes: false
            }
          }
        )
        this.activeContent = data.data
      } catch (error) {
        throw JSON.stringify(error.response.data.error)
      }
    },
    async getArchivedContent() {
      try {
        const data = await axios.get(
          `http://localhost:8000/api/products?page=${crudOptionsStore.getCurrentPage}&filter[trashed]=only`,
          {
            params: this.params
          }
        )
        this.archivedContent = data.data
      } catch (error) {
        throw JSON.stringify(error.response.data.error)
      }
    },
    async getFilteredContent() {
      try {
        const data = await axios.get(
          `http://localhost:8000/api/products?page=${crudOptionsStore.getCurrentPage}`,
          { params: this.params }
        )
        this.filteredContent = data.data
      } catch (error) {
        throw JSON.stringify(error.response.data.error)
      }
    },
    async getProduct() {
      try {
        const data = await axios.get(`http://localhost:8000/api/products/${this.id}`)
        this.product = data.data.data
      } catch (error) {
        throw JSON.stringify(error.response.data.error)
      }
    },

    //#endregion

    //#region GetOptions

    async getStatus() {
      await statusStore.getActiveContent()
      if (!this.statuses.length) {
        for (let status of statusStore.activeContent.data) {
          this.statuses.push({ name: status.name, id: status.id })
        }
      }
    },
    async getCategories() {
      await categoriesStore.getActiveContent()
      if (!this.categories.length) {
        for (let category of categoriesStore.activeContent.data) {
          this.categories.push({ name: category.name, id: category.id })
        }
      }
    },
    async getBrands() {
      await productBrandsStore.getActiveContent()
      if (!this.brands.length) {
        for (let brand of productBrandsStore.activeContent.data) {
          this.brands.push({ name: brand.name, id: brand.id })
        }
      }
    },

    //#endregion

    async hasAlert() {
      try {
        const data = await axios.post('http://localhost:8000/api/auth/has-alert', {
          product_id: this.id
        })
        this.alert = data.data
      } catch (error) {
        throw JSON.stringify(error)
      }
    },
    async addAlert() {
      try {
        await axios.post(`http://localhost:8000/api/price-alert`, {
          price: this.desiredPrice,
          product: this.id
        })
      } catch (error) {
        throw JSON.stringify(error)
      }
    },
    async removeAlert() {
      try {
        await axios.delete(`http://localhost:8000/api/price-alert/${this.alertId}`)
      } catch (error) {
        throw JSON.stringify(error)
      }
    },

    //#region Favorites
    async isFavorite() {
      try {
        const data = await axios.post('http://localhost:8000/api/auth/is-favourite', {
          product_id: this.id
        })
        this.favourite = data.data
      } catch (error) {
        throw JSON.stringify(error)
      }
    },
    //#endregion

    //#region Actions

    async postContent() {
      try {
        await axios.post('http://localhost:8000/api/products', this.getVariables)
      } catch (error) {
        throw JSON.stringify(error.response.data.message)
      }
    },
    async putContent(id) {
      if (!id) {
        throw JSON.stringify('User ID is necessary!')
      }
      try {
        await axios.patch(`http://localhost:8000/api/products/${id}`, this.getVariables)
      } catch (error) {
        throw JSON.stringify(error.response.data.message)
      }
    },
    async deleteContent(id) {
      try {
        await axios.delete(`http://localhost:8000/api/products/${id}`)
      } catch (error) {
        throw JSON.stringify(error.response.data.message)
      }
    },
    async restoreContent(id) {
      try {
        await axios.post(`http://localhost:8000/api/restore/products/${id}`)
      } catch (error) {
        throw JSON.stringify(error.response.data.message)
      }
    },
    async forceDeleteContent(id) {
      if (!id) {
        throw JSON.stringify('Id is necessary!')
      }
      try {
        await axios.post(`http://localhost:8000/api/force-delete/products/${id}`)
      } catch (error) {
        throw JSON.stringify(error.response.data.message)
      }
    }

    //#endregion
  }
})
