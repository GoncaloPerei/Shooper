<script setup>
import { useAuthStore } from '@/store/auth'
import { useProductListsStore } from '@/store/useProductListsCrud'
import Alerts from '@/components/Alerts.vue'
import { onUnmounted, onMounted, ref } from 'vue'
import Dialog from 'primevue/dialog'
import InputText from 'primevue/inputtext'
import { twMerge } from 'tailwind-merge'
import Button1 from '@/components/Button1.vue'
import ModData from '@/components/ModData.vue'
import changeDialogState from '@/resources/changeDialogState.js'

const alerts = ref({})

const visible = ref(false)

const store = useAuthStore()
const productListsStore = useProductListsStore()

const getList = async (id) => {
  try {
    productListsStore.$patch((state) => {
      state.listId = id
    })
    await productListsStore.getProductList()
  } catch (error) {
    alerts.value.error('Request Failed!', error)
  }
}

const addList = async () => {
  try {
    await productListsStore.addList()
    await store.getUser()
    visible.value = false
    alerts.value.success('Request Done Sucessfully!', 'List Created Sucessfully!')
  } catch (error) {
    alerts.value.error('Request Failed!', error)
  }
}

const editList = async (visible, disable, id) => {
  try {
    await productListsStore.editList(id)
    await store.getUser()
    changeDialogState(visible, disable)
    alerts.value.success('Request Done Sucessfully!', 'List Edited Sucessfully!')
  } catch (error) {
    alerts.value.error('Request Failed!', error)
  }
}

const deleteList = async (visible, disable, id) => {
  try {
    await productListsStore.deleteList(id)
    await store.getUser()
    productListsStore.list = null
    changeDialogState(visible, disable)
    alerts.value.success('Request Done Sucessfully!', 'List Deleted Sucessfully!')
  } catch (error) {
    alerts.value.error('Request Failed!', error)
  }
}

const removeProduct = async (id) => {
  try {
    productListsStore.$patch((state) => {
      state.productId = id
    })
    await productListsStore.removeProduct()
    await getList()
    alerts.value.success('Request Done Sucessfully!', 'Product Removed Sucessfully!')
  } catch (error) {
    alerts.value.error('Request Failed!', error)
  }
}

onUnmounted(() => {
  try {
    productListsStore.$reset()
  } catch (error) {
    console.log(error)
  }
})
</script>

<template>
  <Alerts ref="alerts" />
  <div class="w-auto h-auto flex gap-5">
    <div
      class="w-full max-w-60 h-full bg-[#242424] rounded-lg p-6 flex justify-between items-start"
    >
      <div class="w-full h-auto flex flex-col gap-5">
        <Button
          @click="visible = true"
          class="w-full text-center font-bold tracking-wider text-white underline underline-offset-2 py-2 rounded-md overflow-hidden flex items-center gap-2.5"
        >
          <b-icon-plus-circle-fill /> Add List
        </Button>
        <Dialog
          v-model:visible="visible"
          modal
          :draggable="false"
          dismissableMask
          header="Add List"
          class="font-montserrat tracking-wider overflow-hidden"
          :style="{ width: '25rem' }"
        >
          <form @submit.prevent="addList">
            <span class="text-sm block text-[#F7926E]">Add a new list of products.</span>
            <div class="h-auto max-h-96 overflow-y-auto flex flex-col scroll-pr-3 gap-2.5">
              <div class="flex flex-col align-items-center gap-3 mb-3">
                <label for="name" class="font-semibold w-6rem">Name</label>
                <InputText
                  id="name"
                  class="flex-auto p-3 border border-solid border-white/40 focus:outline-[#F36F3F]"
                  v-model="productListsStore.name"
                  autocomplete="off"
                />
              </div>
              <div class="flex flex-col align-items-center gap-3 mb-3">
                <label for="desc" class="font-semibold w-6rem">Description</label>
                <InputText
                  id="desc"
                  class="flex-auto p-3 border border-solid border-white/40 focus:outline-[#F36F3F]"
                  v-model="productListsStore.desc"
                  autocomplete="off"
                />
              </div>
            </div>
            <div class="mt-2.5 flex w-full justify-end gap-5">
              <Button
                type="submit"
                class="font-montserrat capitalize tracking-wider bg-[#F36F3F] px-2.5 py-1.5 text-[#242424] rounded-md"
                :disabled="disableSubmit"
                >Save List</Button
              >
              <Button
                type="button"
                class="font-montserrat tracking-wider outline outline-1 outline-[#F36F3F] text-[#F36F3F] px-2.5 py-1.5 rounded-md"
                severity="secondary"
                @click="visible = false"
                >Cancel</Button
              >
            </div>
          </form>
        </Dialog>

        <div v-for="list in store?.user?.productLists" class="w-full h-fit flex flex-col gap-5">
          <Button
            @click="getList(list?.id)"
            class="w-full text-center font-bold tracking-wider bg-[#F36F3F] text-[#242424] text-sm py-4 rounded-md overflow-hidden"
          >
            {{ list?.name }}
          </Button>
          <hr class="w-full h-px bg-white/40 border-0" />
        </div>
      </div>
    </div>
    <div
      v-if="productListsStore.list"
      class="w-full h-auto p-6 bg-[#242424] rounded-lg flex flex-col gap-5"
    >
      <div class="w-full h-auto flex justify-between items-center">
        <div class="w-auto h-full flex gap-5 items-center">
          <div class="w-auto h-auto flex flex-col gap-3.5">
            <ModData
              @handle-submit="editList"
              iconName="b-icon-pencil-square"
              :id="productListsStore?.getList?.id"
              label="Edit"
              :name="productListsStore?.getList?.name"
            >
              <template #subhead
                >Edit
                <span class="underline underline-offset-2">{{
                  productListsStore?.getList?.name
                }}</span
                >.</template
              >
              <template #main>
                <div class="flex flex-col align-items-center gap-3 mb-3">
                  <label for="name" class="font-semibold w-6rem">Name</label>
                  <InputText
                    id="name"
                    class="flex-auto p-3 border border-solid border-white/40 focus:outline-[#F36F3F]"
                    v-model="productListsStore.getList.name"
                    @update:modelValue="productListsStore.name = $event"
                    autocomplete="off"
                  />
                </div>
                <div class="flex flex-col align-items-center gap-3 mb-3">
                  <label for="desc" class="font-semibold w-6rem">Description</label>
                  <InputText
                    id="desc"
                    class="flex-auto p-3 border border-solid border-white/40 focus:outline-[#F36F3F]"
                    v-model="productListsStore.getList.desc"
                    @update:modelValue="productListsStore.desc = $event"
                    autocomplete="off"
                  />
                </div>
              </template>
            </ModData>
            <ModData
              @handle-submit="deleteList"
              iconName="b-icon-trash3-fill"
              :id="productListsStore?.getList?.id"
              :name="productListsStore?.getList?.name"
              type="Delete"
              label="Delete"
            >
              <template #main>
                <div class="flex items-center gap-5 mb-3">
                  <b-icon-exclamation-circle-fill class="text-3xl" />
                  <span>Are you sure you want to delete this record?</span>
                </div>
              </template>
            </ModData>
          </div>
          <hr class="w-px h-full my-4 bg-white/40 border-0" />
          <div>
            <span class="font-bold text-lg tracking-wider">{{
              productListsStore?.getList?.name
            }}</span
            ><br />
            <span class="tracking-wider text-sm">{{ productListsStore?.getList?.desc }}</span>
          </div>
        </div>
        <div class="w-auto h-auto flex flex-col">
          <span class="w-full h-fit flex items-end tracking-wider text-sm"
            >Products
            :&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span
              class="w-fit font-bold text-[#F36F3F]"
              >{{ productListsStore?.getList?.productsCount }}</span
            ></span
          >
          <hr class="w-auto h-px my-4 bg-white/40 border-0" />
          <span class="w-full h-fit justify-between flex items-end tracking-wider text-sm"
            >Total :<span class="w-fit font-bold text-[#F36F3F]"
              >{{
                store?.user?.wishlist?.reduce((acc, item) => {
                  total = total + parseFloat(item.avgPrice)
                  return total
                }, 0)
              }}€</span
            ></span
          >
        </div>
      </div>
      <hr class="w-full h-px bg-white/40 border-0" />
      <div class="flex flex-col gap-12">
        <div
          v-for="product in productListsStore?.getList?.products"
          v-if="productListsStore?.getList?.productsCount > 0"
          class="w-full h-auto flex justify-between"
        >
          <div class="relative w-full h-auto flex items-center gap-5">
            <img
              :src="product?.filename"
              alt="Illustration of an oven."
              class="w-max max-w-36 rounded-lg"
            />
            <hr class="w-px h-full mx-6 bg-white/40 border-0" />
            <div class="relative w-full h-auto">
              <span class="font-bold tracking-wider">{{ product?.name }}</span>
              <hr class="w-auto h-px my-6 bg-white/40 border-0" />
              <div class="flex gap-6">
                <span class="text-sm font-bold tracking-wider leading-loose">
                  Average Price <br />
                  <span class="text-2xl text-[#F36F3F] tracking-wider"
                    >{{ parseFloat(product?.avgPrice).toFixed(2) }} €</span
                  >
                </span>
                <hr class="w-px h-auto bg-white/40 border-0" />
                <span class="text-sm font-bold tracking-wider leading-loose">
                  Minimum Price <br />
                  <span class="text-2xl text-[#F36F3F] tracking-wider"
                    >{{ parseFloat(product?.minPrice).toFixed(2) }} €</span
                  >
                </span>
              </div>
            </div>
            <hr class="w-px h-full mx-6 bg-white/40 border-0" />
            <div class="flex flex-col gap-3.5">
              <RouterLink :to="'/product/' + product?.id + '/' + product?.name">
                <Button1 :class="twMerge('text-xs px-6 py-2 text-nowrap')">See Product</Button1>
              </RouterLink>
              <Button1
                :class="twMerge('text-xs px-6 py-2 text-nowrap')"
                @click="removeProduct(product?.id)"
                >Remove</Button1
              >
            </div>
          </div>
        </div>
        <div v-else class="w-full h-auto">
          <span class="tracking-wider"
            >You don't have any products in {{ productListsStore?.getList?.name }} yet.</span
          >
        </div>
      </div>
    </div>
    <div v-else class="w-full h-auto flex items-center justify-center rounded-lg p-6 bg-[#242424]">
      <span class="tracking-wider font-bold">Select a list...</span>
    </div>
  </div>
</template>
