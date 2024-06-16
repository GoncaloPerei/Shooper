<script setup>
import { onMounted, ref } from 'vue'
import Alerts from '@/components/Alerts.vue'
import { useUsersStore } from '@/store/useUsersCrud'
import CrudLayout from '@/layouts/Views/CrudLayout.vue'
import CrudStatusLayout from '@/layouts/Views/CrudStatusLayout.vue'
import CrudLabel from '@/components/CrudStatusLabel.vue'
import Table from '@/components/Table.vue'
import SearchBar from '@/components/SearchBar.vue'
import ModData from '@/components/ModData.vue'
import InputText from 'primevue/inputtext'
import Dropdown from 'primevue/dropdown'
import checkIfNull from '@/resources/checkIfNull.js'
import changeDialogState from '@/resources/changeDialogState.js'

const store = useUsersStore()
const alerts = ref({})
const role = ref({})

const handleRequest = async (operation, successMessage, visible, disable) => {
  try {
    await operation()
    await store.getActiveContent()
    if (operation !== store.postContent) {
      await store.getArchivedContent()
    }
    changeDialogState(visible, disable)
    alerts.value.success('Request Done Successfully!', successMessage)
  } catch (error) {
    alerts.value.error('Request Failed!', error)
  }
}

const addContent = async (visible, disable) => {
  await handleRequest(
    async () => {
      await store.postContent()
      store.name = null
      store.email = null
      store.password = null
      store.role = {}
    },
    'User Added Successfully!',
    visible,
    disable
  )
}

const editContent = async (visible, disable, id) => {
  await handleRequest(
    async () => {
      await store.putContent(id)
      store.name = null
      store.email = null
      store.password = null
      store.role = {}
    },
    'User Edited Successfully!',
    visible,
    disable
  )
}

const deleteContent = async (visible, disable, id) => {
  await handleRequest(store.deleteContent(id), 'User Deleted Successfully!', visible, disable)
}

const restoreContent = async (visible, disable, id) => {
  await handleRequest(store.restoreContent(id), 'User Restored Successfully!', visible, disable)
}

const forceDeleteContent = async (visible, disable, id) => {
  await handleRequest(
    store.forceDeleteContent(id),
    'User Force Deleted Successfully!',
    visible,
    disable
  )
}

onMounted(async () => {
  try {
    store.$reset()
    store.$patch((state) => {
      state.params.paginate = '5'
    })
    await Promise.all([store.getActiveContent(), store.getArchivedContent(), store.getRoles()])
  } catch (error) {
    alerts.value.error('An error ocurred!', error)
  }
})
</script>

<template>
  <Alerts ref="alerts" />
  <CrudLayout iconName="b-icon-people-fill">
    <template #header>Users</template>
    <template #main>
      <CrudStatusLayout>
        <template #header>
          <CrudLabel>Active Users</CrudLabel>
        </template>
        <template #actions>
          <SearchBar
            @handle-submit="store.getActiveContent"
            name="user"
            v-model="store.params.filter.name"
          />
          <ModData label="Add" type="Add" name="User" @handle-submit="addContent">
            <template #header>User</template>
            <template #subhead>Add a new user.</template>
            <template #main>
              <div class="flex flex-col align-items-center gap-3 mb-3">
                <label for="name" class="font-semibold w-6rem"
                  >Name <span class="text-[#F36F3F]">*</span></label
                >
                <InputText
                  id="name"
                  class="flex-auto p-3 border border-solid border-white/40 focus:outline-[#F36F3F]"
                  v-model="store.name"
                  autocomplete="off"
                />
              </div>
              <div class="flex flex-col align-items-center gap-3 mb-3">
                <label for="email" class="font-semibold w-6rem"
                  >Email <span class="text-[#F36F3F]">*</span></label
                >
                <InputText
                  id="email"
                  class="flex-auto p-3 border border-solid border-white/40 focus:outline-[#F36F3F]"
                  v-model="store.email"
                  autocomplete="off"
                />
              </div>
              <div class="flex flex-col align-items-center gap-3 mb-5">
                <label for="password" class="font-semibold w-6rem"
                  >Password <span class="text-[#F36F3F]">*</span></label
                >
                <InputText
                  id="password"
                  class="flex-auto p-3 border border-solid border-white/40 focus:outline-[#F36F3F]"
                  v-model="store.password"
                  autocomplete="off"
                />
              </div>
              <div class="flex flex-col align-items-center gap-3 mb-5">
                <label for="option" class="font-semibold w-6rem"
                  >Role <span class="text-[#F36F3F]">*</span></label
                >
                <Dropdown
                  v-model="store.role"
                  :options="store.roles"
                  optionLabel="name"
                  placeholder="Select a Role"
                  class="w-full p-3 border border-solid border-white/40"
                />
              </div>
            </template>
          </ModData>
        </template>
        <template #table>
          <Table
            :storeMeta="store.activeUsers?.meta"
            :storeData="store.activeUsers?.data"
            tableDataPr="8"
            @changePage="
              store.$patch((state) => {
                state.params.filter.trashed = ''
              }),
                store.getActiveContent()
            "
          >
            <template #header>
              <th
                align="left"
                class="w-fit px-5 py-5 border-r border-white/20"
                data-priority="1"
              ></th>
              <th align="left" class="w-full px-5 py-5 text-nowrap" data-priority="2">Full Name</th>
              <th align="left" class="w-full px-5 py-5 text-nowrap" data-priority="3">Email</th>
              <th align="left" class="w-full px-5 py-5 text-nowrap" data-priority="4">Role</th>
              <th align="left" class="w-full px-5 py-5 text-nowrap" data-priority="5">Created</th>
              <th align="left" class="w-full px-5 py-5 text-nowrap" data-priority="6">Updated</th>
              <th
                align="left"
                class="w-fit px-5 py-5 border-l border-white/20"
                data-priority="7"
              ></th>
              <th align="left" class="w-fit px-5 py-5" data-priority="8"></th>
            </template>
            <template #body="{ data }">
              <td align="center" class="w-fit px-5 py-5 border-r border-white/20">
                <div
                  class="w-fit h-fit bg-[#F36F3F] flex items-center justify-center rounded-full p-1.5"
                >
                  <b-icon-person-fill class="text-[#242424] text-lg" />
                </div>
              </td>
              <td class="w-full px-5 py-5 max-w-xs truncate">{{ checkIfNull(data.name) }}</td>
              <td class="w-full px-5 py-5 max-w-xs truncate">{{ checkIfNull(data.email) }}</td>
              <td class="w-full px-5 py-5 max-w-xs truncate">{{ checkIfNull(data.role) }}</td>
              <td class="w-full px-5 py-5 text-nowrap">
                {{ checkIfNull(data.createdAt) }}
              </td>
              <td class="w-full px-5 py-5 text-nowrap">
                {{ checkIfNull(data.updatedAt) }}
              </td>
              <td class="w-fit px-5 py-5 border-l border-white/20" align="center">
                <ModData
                  name="User"
                  @handle-submit="editContent"
                  iconName="b-icon-pencil-square"
                  :id="data.id"
                  label="Edit"
                >
                  <template #subhead
                    >Edit <span class="underline underline-offset-2">{{ data.name }}</span
                    >.</template
                  >
                  <template #main>
                    <div class="flex flex-col align-items-center gap-3 mb-3">
                      <label for="name" class="font-semibold w-6rem">Name</label>
                      <InputText
                        id="name"
                        class="flex-auto p-3 border border-solid border-white/40 focus:outline-[#F36F3F]"
                        v-model="data.name"
                        @update:modelValue="store.name = $event"
                        autocomplete="off"
                      />
                    </div>
                    <div class="flex flex-col align-items-center gap-3 mb-5">
                      <label for="email" class="font-semibold w-6rem">Email</label>
                      <InputText
                        id="email"
                        class="flex-auto p-3 border border-solid border-white/40 focus:outline-[#F36F3F]"
                        v-model="data.email"
                        @update:modelValue="store.email = $event"
                        autocomplete="off"
                      />
                    </div>
                    <div class="flex flex-col align-items-center gap-3 mb-5">
                      <label for="role" class="font-semibold w-6rem">Role</label>
                      <Dropdown
                        v-model="store.role"
                        :options="store.roles"
                        optionLabel="name"
                        @update:modelValue="store.role = $event"
                        class="w-full p-3 border border-solid border-white/40"
                      />
                    </div>
                  </template>
                </ModData>
              </td>
              <td class="w-fit px-5 py-5" align="center">
                <ModData
                  @handle-submit="deleteContent"
                  iconName="b-icon-trash3-fill"
                  :id="data.id"
                  name="User"
                  label="Delete"
                >
                  <template #main>
                    <div class="flex items-center gap-5 mb-3">
                      <b-icon-exclamation-circle-fill class="text-3xl" />
                      <span>Are you sure you want to delete this record?</span>
                    </div>
                  </template>
                </ModData>
              </td>
            </template>
          </Table>
        </template>
      </CrudStatusLayout>
      <CrudStatusLayout>
        <template #header>
          <CrudLabel>Archived Users</CrudLabel>
        </template>
        <template #actions>
          <SearchBar
            @handle-submit="store.getArchivedContent"
            v-model="store.params.filter.name"
            name="user"
          />
        </template>
        <template #table>
          <Table
            :storeMeta="store.archUsers?.meta"
            :storeData="store.archUsers?.data"
            @changePage="store.getArchivedContent()"
            tableDataPr="7"
          >
            <template #header>
              <th
                align="left"
                class="w-fit px-5 py-5 border-r border-white/20"
                data-priority="1"
              ></th>
              <th align="left" class="w-full px-5 py-5" data-priority="2">Full Name</th>
              <th align="left" class="w-full px-5 py-5" data-priority="3">Email</th>
              <th align="left" class="w-full px-5 py-5" data-priority="4">Role</th>
              <th align="left" class="w-full px-5 py-5" data-priority="5">Deleted</th>
              <th
                align="left"
                class="w-fit px-5 py-5 border-l border-white/20"
                data-priority="6"
              ></th>
              <th align="left" class="w-fit px-5 py-5" data-priority="7"></th>
            </template>
            <template #body="{ data }">
              <td align="center" class="w-fit px-5 py-5 border-r border-white/20">
                <div
                  class="w-fit h-fit bg-[#F36F3F] flex items-center justify-center rounded-full p-1.5"
                >
                  <b-icon-person-fill class="text-[#242424] text-lg" />
                </div>
              </td>
              <td class="w-full px-5 py-5 whitespace-nowrap">{{ data.name }}</td>
              <td class="w-full px-5 py-5 whitespace-nowrap">{{ data.email }}</td>
              <td class="w-full px-5 py-5 whitespace-nowrap">{{ data.role }}</td>
              <td class="w-full px-5 py-5 whitespace-nowrap">
                {{ data.deletedAt }}
              </td>
              <td class="w-fit px-5 py-5 border-l border-white/20" align="center">
                <ModData
                  @handle-submit="restoreContent"
                  iconName="b-icon-arrow-repeat"
                  :id="data.id"
                  name="User"
                  label="Restore"
                >
                  <template #main>
                    <div class="flex items-center gap-5 mb-3">
                      <b-icon-exclamation-circle-fill class="text-3xl" />
                      <span>Are you sure you want to restore this record?</span>
                    </div>
                  </template>
                </ModData>
              </td>
              <td class="w-fit px-5 py-5" align="center">
                <ModData
                  @handle-submit="forceDeleteContent"
                  iconName="b-icon-trash3-fill"
                  :id="data.id"
                  name="Category"
                  label="Force Delete"
                >
                  <template #main>
                    <div class="flex items-center gap-5 mb-3">
                      <b-icon-exclamation-circle-fill class="text-3xl" />
                      <span>Are you sure you want to force delete this record?</span>
                    </div>
                  </template>
                </ModData>
              </td>
            </template>
          </Table>
        </template>
      </CrudStatusLayout>
    </template>
  </CrudLayout>
</template>
