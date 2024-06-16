import { defineStore } from 'pinia'
import axios from '@/resources/axios.config'
import { useCrudOptionsStore } from '@/store/useCrudOptions'
const crudOptionsStore = useCrudOptionsStore()
import { useRolesStore } from '@/store/useRolesCrud'
const rolesStore = useRolesStore()

export const useUsersStore = defineStore('usersCrud', {
  state: () => ({
    activeUsers: [],
    archivedUsers: [],
    roles: [],
    name: null,
    email: null,
    password: null,
    role: {},
    product: null,
    params: {
      paginate: null,
      filter: {
        name: null,
        status_id: null,
        product_category_id: null
      }
    }
  }),
  getters: {
    users: (state) => state.activeUsers,
    archUsers: (state) => state.archivedUsers,
    getVariables: (state) => {
      const variables = {}
      if (state.name !== null) {
        variables.name = state.name
      }
      if (state.email !== null) {
        variables.email = state.email
      }
      if (state.password !== null) {
        variables.password = state.password
      }
      if (state.role.id !== null) {
        variables.role = state.role.id
      }
      if (state.product !== null) {
        variables.product_id = state.product
      }
      return variables
    }
  },
  actions: {
    async getRoles() {
      await rolesStore.getActiveContent()
      if (!this.roles.length) {
        for (let role of rolesStore.roles?.data) {
          this.roles.push({ name: role?.name, id: role?.id })
        }
      }
    },
    async getActiveContent() {
      try {
        const data = await axios.get(
          `http://localhost:8000/api/users?page=${crudOptionsStore.getCurrentPage}`,
          {
            params: this.params
          }
        )
        this.activeUsers = data.data
      } catch (error) {
        throw JSON.stringify(error.response.data.error)
      }
    },
    async getArchivedContent() {
      try {
        const data = await axios.get(
          `http://localhost:8000/api/users?page=${crudOptionsStore.getCurrentPage}&filter[trashed]=only`,
          {
            params: this.params
          }
        )
        this.archivedUsers = data.data
      } catch (error) {
        throw JSON.stringify(error.response.data.error)
      }
    },
    async postContent() {
      try {
        await axios.post('http://localhost:8000/api/users/', this.getVariables)
        this.getActiveContent()
      } catch (error) {
        throw JSON.stringify(error.response.data.message)
      }
    },
    async putContent(id) {
      if (!id) {
        throw JSON.stringify('User ID is necessary!')
      }
      try {
        await axios.patch(`http://localhost:8000/api/users/${id}`, this.getVariables)
        this.getActiveContent()
      } catch (error) {
        throw JSON.stringify(error.response.data.message)
      }
    },
    async deleteContent(id) {
      try {
        await axios.delete(`http://localhost:8000/api/users/${id}`)
        this.getActiveContent()
        this.getArchivedContent()
      } catch (error) {
        throw JSON.stringify(error.response.data.message)
      }
    },
    async restoreContent(id) {
      try {
        await axios.post(`http://localhost:8000/api/restore/users/${id}`)
        this.getActiveContent()
        this.getArchivedContent()
      } catch (error) {
        throw JSON.stringify(error.response.data.message)
      }
    },
    async forceDeleteContent(id) {
      try {
        await axios.post(`http://localhost:8000/api/force-delete/users/${id}`)
        this.getArchivedContent()
      } catch (error) {
        throw JSON.stringify(error.response.data.message)
      }
    },
    clearFields() {
      this.$reset()
    }
  }
})
