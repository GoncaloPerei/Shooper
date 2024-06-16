<script setup>
import { onMounted, ref, computed } from 'vue'
import Alerts from '@/components/Alerts.vue'
import { useProductsStore } from '@/store/useProductsCrud'
import { useProductListsStore } from '@/store/useProductListsCrud'
import CrudLabel from '@/components/CrudStatusLabel.vue'
import { useRoute } from 'vue-router'
import ActLink from '@/components/ActLinks.vue'
import CategoryLabel from '@/components/CategoryLabel.vue'
import Dialog from 'primevue/dialog'
import Button from 'primevue/button'
import InputText from 'primevue/inputtext'
import Dropdown from 'primevue/dropdown'
import Chart from 'primevue/chart'
import AddProduct from '@/components/AddProduct.vue'
import ModData from '@/components/ModData.vue'
import { format } from 'date-fns'
import changeDialogState from '@/resources/changeDialogState.js'

const route = useRoute()

const alerts = ref({})
const store = useProductsStore()
const productListsStore = useProductListsStore()
const visible = ref(false)
const visible2 = ref(false)
const visible3 = ref(false)

const value = ref(0)

const minValue = computed({
  get: () => value.value,
  set: (newValue) => {
    value.value = Math.max(newValue, 0)
  }
})

const chartData = ref()
const chartOptions = ref()

const setChartData = () => {
  const date = store.selectedProduct?.priceHistory.map((item) =>
    format(new Date(item.createdAt), 'MMMM dd, yyyy')
  )
  const avgPrice = store.selectedProduct?.priceHistory.map((item) => parseFloat(item.avgPrice))
  const minPrice = store.selectedProduct?.priceHistory.map((item) => parseFloat(item.minPrice))
  return {
    labels: date,
    datasets: [
      {
        label: 'Average Price',
        data: avgPrice,
        fill: false,
        borderColor: '#F36F3F',
        backgroundColor: '#1E1E1E',
        tension: 0.4,
        borderWidth: 2
      },
      {
        label: 'Minimum Price',
        data: minPrice,
        fill: false,
        borderColor: '#6694FE',
        backgroundColor: '#1E1E1E',
        tension: 0.4,
        borderWidth: 2
      }
    ]
  }
}
const setChartOptions = () => {
  const documentStyle = getComputedStyle(document.documentElement)
  const textColor = '#FFFFFF'
  const textColorSecondary = '#FFFFFF'
  const surfaceBorder = documentStyle.getPropertyValue('--surface-border')

  return {
    maintainAspectRatio: false,
    aspectRatio: 0.6,
    radius: 6,
    hoverRadius: 5,
    plugins: {
      legend: {
        labels: {
          color: textColor,
          font: {
            family: 'Montserrat',
            weight: '700'
          },
          usePointStyle: true
        }
      },
      tooltip: {
        titleFont: {
          family: 'Montserrat'
        },
        bodyFont: {
          family: 'Montserrat'
        }
      }
    },
    scales: {
      x: {
        ticks: {
          color: textColorSecondary,
          padding: 15,
          font: {
            family: 'Montserrat',
            weight: '700'
          }
        },
        grid: {
          color: surfaceBorder
        }
      },
      y: {
        ticks: {
          color: textColorSecondary,
          padding: 10,
          font: {
            family: 'Montserrat'
          }
        },
        grid: {
          color: surfaceBorder
        }
      }
    }
  }
}

const history_price = ref()

const showScrollInto = () => {
  history_price.value.scrollIntoView({ behavior: 'smooth' })
}

const addList = async (visible, disable) => {
  try {
    await productListsStore.addList()
    await productListsStore.getActiveContent()
    changeDialogState(visible, disable)
    alerts.value.success('Request Done Sucessfully!', 'List Created Sucessfully!')
  } catch (error) {
    alerts.value.error('Request Failed!', error)
  }
}

const addProduct = async () => {
  try {
    productListsStore.productId = route.params.id
    await productListsStore.addProduct()
    await store.isFavorite()
    visible3.value = false
    alerts.value.success('Request Done Sucessfully!', 'Product Added To List Sucessfully!')
  } catch (error) {
    alerts.value.error('Request Failed!', error)
  }
}

const removeProduct = async (id) => {
  try {
    productListsStore.listId = id
    productListsStore.productId = route.params.id
    await productListsStore.removeProduct()
    await store.isFavorite()
    alerts.value.success('Request Done Sucessfully!', 'Product Removed From List Sucessfully!')
  } catch (error) {
    alerts.value.error('Request Failed!', error)
  }
}

const addAlert = async () => {
  try {
    store.id = route.params.id
    await store.addAlert()
    await store.hasAlert()
    visible2.value = false
    alerts.value.success('Request Done Sucessfully!', 'Alert Added Sucessfully!')
  } catch (error) {
    alerts.value.error('Request Failed!', error)
  }
}
const removeAlert = async (id) => {
  try {
    store.alertId = id
    await store.removeAlert()
    await store.hasAlert()
    alerts.value.success('Request Done Sucessfully!', 'Alert Removed Sucessfully!')
  } catch (error) {
    alerts.value.error('Request Failed!', error)
  }
}

onMounted(async () => {
  try {
    store.$reset()
    store.$patch((state) => {
      state.id = route.params.id
      state.params.filter['productUrl.status_id'] = '1'
    })
    await store.getProduct(),
      await store.isFavorite(),
      await store.hasAlert(),
      await productListsStore.getActiveContent()
    chartData.value = setChartData()
    chartOptions.value = setChartOptions()
  } catch (error) {
    console.log(error)
  }
})
</script>

<template>
  <Alerts ref="alerts" />
  <div
    class="relative w-auto min-h-screen h-auto flex flex-col m-16 text-white font-montserrat box-border gap-10"
  >
    <div class="flex flex-col gap-8">
      <div class="relative w-auto h-auto flex gap-2.5">
        <CategoryLabel link="/" :name="'Home'" />
        <CategoryLabel :link="'/'" :name="store.selectedProduct?.category" />
      </div>
      <hr class="w-auto h-px bg-white/40 border-0" />
      <div class="w-full h-auto flex gap-7 overflow-hidden">
        <img
          class="ml-2.5 w-max max-w-96 rounded-lg cursor-pointer"
          :src="store.selectedProduct?.filename"
          alt=""
        />
        <hr class="w-px h-auto mx-8 bg-white/40 border-0" />
        <div class="w-full h-auto flex flex-col justify-center">
          <div class="flex flex-col gap-3">
            <span class="font-bold tracking-wider text-lg">
              {{ store.selectedProduct?.name }}
            </span>
            <div class="w-auto h-auto flex gap-3.5">
              <ActLink @click="visible = true" iconName="b-icon-share-fill"> Share </ActLink>
              <Dialog
                v-model:visible="visible"
                modal
                :draggable="false"
                dismissableMask
                header="Share Product"
                class="font-montserrat tracking-wider overflow-hidden"
                :style="{ width: '25rem' }"
              >
                <form @submit.prevent="handleSubmit">
                  <div class="h-auto max-h-96 overflow-y-auto flex flex-col scroll-pr-3 gap-2.5">
                    <div class="flex gap-2.5">
                      <InputText
                        id="testUrl"
                        class="flex-auto p-3 border border-solid border-white/40 focus:outline-[#F36F3F] text-sm"
                        v-model="route.fullPath"
                        autocomplete="off"
                      />
                      <Button
                        type="button"
                        class="font-montserrat capitalize tracking-wider bg-[#F36F3F] px-2.5 py-1.5 text-[#242424]"
                        label="Copy Url"
                      ></Button>
                    </div>
                  </div>
                </form>
              </Dialog>
              <hr class="w-px h-auto bg-[#6694fe] border-0" />
              <ActLink @click="showScrollInto" iconName="b-icon-activity"> History Price </ActLink>
              <hr class="w-px h-auto bg-[#6694fe] border-0" />
              <ActLink
                v-if="store?.alert?.data"
                @click="removeAlert(store?.alert?.alert?.id)"
                iconName="b-icon-bell-fill"
              >
                Remove Alert
              </ActLink>
              <ActLink v-else @click="visible2 = true" iconName="b-icon-bell">
                Price Alert
              </ActLink>
              <Dialog
                v-model:visible="visible2"
                modal
                :draggable="false"
                dismissableMask
                header="Share Product"
                class="font-montserrat tracking-wider overflow-hidden"
                :style="{ width: '30rem' }"
              >
                <form @submit.prevent="handleSubmit">
                  <span class="text-sm block text-[#F7926E]"
                    >Put your desired value and we warn you.</span
                  >
                  <div
                    class="h-auto max-h-96 overflow-y-auto flex flex-col scroll-pr-3 gap-5 items-end"
                  >
                    <div class="w-full flex flex-col gap-3.5">
                      <span class="text-[#F36F3F] font-bold text-center">Desired Value</span>
                      <div class="w-full flex gap-2.5">
                        <button
                          class="w-full max-w-10 aspect-square rounded-md border border-[#F7926E]"
                          @click="value--"
                        >
                          -
                        </button>
                        <InputText
                          id="value"
                          class="w-full flex-auto p-3 border border-solid border-white/40 focus:outline-[#F36F3F] text-sm"
                          v-model="store.desiredPrice"
                          autocomplete="off"
                        />
                        <button
                          class="w-full max-w-10 aspect-square rounded-md border border-[#F7926E]"
                          @click="value++"
                        >
                          +
                        </button>
                      </div>
                      <span class="text-xs"
                        >Get an email when the product reaches your desired value or when it gets
                        even lower.</span
                      >
                    </div>
                    <Button
                      type="button"
                      class="w-fit font-montserrat capitalize tracking-wider bg-[#F36F3F] px-2.5 py-1.5 text-[#242424]"
                      label="Add Alert"
                      @click="addAlert()"
                    ></Button>
                  </div>
                </form>
              </Dialog>
              <hr class="w-px h-auto bg-[#6694fe] border-0" />
              <ActLink
                v-if="store?.favourite?.data"
                @click="removeProduct(store?.favourite?.list?.id)"
                iconName="b-icon-patch-minus-fill"
              >
                Remove From {{ store?.favourite?.list?.name }}
              </ActLink>
              <ActLink v-else @click="visible3 = true" iconName="b-icon-patch-plus">
                Add to List
              </ActLink>
              <Dialog
                v-model:visible="visible3"
                modal
                :draggable="false"
                dismissableMask
                header="Add To List"
                class="font-montserrat tracking-wider overflow-hidden"
                :style="{ width: '25rem' }"
              >
                <form @submit.prevent="addProduct()">
                  <span class="text-sm block text-[#F7926E]"
                    >Add a product to one of your lists.</span
                  >
                  <div class="h-auto max-h-96 overflow-y-auto flex flex-col scroll-pr-3 gap-2.5">
                    <div class="flex flex-col align-items-center gap-3 mb-5">
                      <label for="List" class="font-semibold w-6rem"
                        >List <span class="text-[#F36F3F]">*</span></label
                      >
                      <Dropdown
                        v-model="productListsStore.listId"
                        :options="productListsStore.lists"
                        optionLabel="name"
                        placeholder="Select a list"
                        class="w-full p-3 border border-solid border-white/40"
                      />
                      <ModData label="Add" type="Label" name="Product" @handle-submit="addList">
                        <template #header>Can't find your list?</template>
                        <template #subhead>Add a new list.</template>
                        <template #main>
                          <div class="flex flex-col align-items-center gap-3 mb-3">
                            <label for="name" class="font-semibold w-6rem"
                              >Name <span class="text-[#F36F3F]">*</span></label
                            >
                            <InputText
                              id="name"
                              class="flex-auto p-3 border border-solid border-white/40 focus:outline-[#F36F3F]"
                              v-model="productListsStore.name"
                              autocomplete="off"
                            />
                          </div>
                          <div class="flex flex-col align-items-center gap-3 mb-3">
                            <label for="desc" class="font-semibold w-6rem">Description </label>
                            <InputText
                              id="desc"
                              class="flex-auto p-3 border border-solid border-white/40 focus:outline-[#F36F3F]"
                              v-model="productListsStore.desc"
                              autocomplete="off"
                            />
                          </div>
                        </template>
                      </ModData>
                    </div>
                  </div>
                  <div class="mt-2.5 flex w-full justify-end gap-5">
                    <Button
                      type="button"
                      @click="addProduct"
                      class="w-fit font-montserrat capitalize tracking-wider bg-[#F36F3F] px-2.5 py-1.5 text-[#242424]"
                      label="Add To List"
                    ></Button>
                  </div>
                </form>
              </Dialog>
            </div>
          </div>
          <hr class="w-auto h-px my-8 bg-white/40 border-0" />
          <div class="flex gap-6">
            <span class="font-bold tracking-wider leading-loose">
              Average Price <br />
              <span class="text-3xl text-[#F36F3F] tracking-wider"
                >{{ parseFloat(store.selectedProduct?.avgPrice ?? 0).toFixed(2) }} €</span
              >
            </span>
            <hr class="w-px h-auto bg-white/40 border-0" />
            <span class="font-bold tracking-wider leading-loose">
              Minimum Price <br />
              <span class="text-3xl text-[#F36F3F] tracking-wider"
                >{{ parseFloat(store.selectedProduct?.minPrice ?? 0).toFixed(2) }} €</span
              >
            </span>
          </div>
        </div>
      </div>
    </div>
    <hr class="w-auto h-px bg-white/40 border-0" />
    <div class="w-full h-auto flex flex-col gap-8 overflow-hidden">
      <div class="flex flex-col gap-3">
        <CrudLabel>Stores</CrudLabel>
        <span class="tracking-wider">Check the price of this product in different stores!</span>
      </div>
      <div class="flex flex-col gap-10">
        <div
          class="w-full h-auto bg-[#242424] rounded-xl px-12 py-5 flex justify-between"
          v-for="data in store.selectedProduct?.urls"
        >
          <div class="w-auto h-auto flex gap-5 items-center">
            <div class="w-fit h-fit p-5 bg-[#F36F3F] rounded-xl">
              <img :src="data?.store?.filename" class="w-max max-w-20" />
            </div>
            <span class="font-semibold tracking-wider leading-loose"
              ><a :href="data?.store?.websiteUrl" class="underline tracking-wider font-normal">{{
                data?.store?.name
              }}</a
              ><br />{{ data?.name }}</span
            >
          </div>
          <div class="w-auto h-auto flex gap-12 items-center">
            <div class="h-full flex flex-col gap-2 items-end justify-center">
              <div v-if="data?.scratchedPrice">
                <span class="text-xl font-bold tracking-wider text-[#F7926E]"
                  >{{ parseFloat(data?.price).toFixed(2) }} €</span
                >&nbsp;&nbsp;
                <span class="text-base tracking-wider text-white/40 line-through"
                  >{{ parseFloat(data?.scratchedPrice).toFixed(2) }} €</span
                >
              </div>
              <div v-else>
                <span class="text-xl font-bold tracking-wider text-[#F7926E]"
                  >{{ parseFloat(data?.price).toFixed(2) }} €</span
                >
              </div>
            </div>
            <a
              :href="data?.url"
              class="w-fit h-fit bg-[#F36F3F] font-bold rounded-lg tracking-wider text-[#242424] px-7 py-4"
            >
              See Shop
            </a>
          </div>
        </div>
      </div>
    </div>
    <hr class="w-auto h-px my-8 bg-white/40 border-0 snap-start scroll-mt-20" ref="history_price" />
    <div class="scroll-mt-20 w-full h-auto flex flex-col gap-8 overflow-hidden">
      <div class="flex flex-col gap-3">
        <CrudLabel>History Price</CrudLabel>
        <span class="tracking-wider">Check the history price of this product!</span>
      </div>
      <span></span>
      <div class="card">
        <Chart type="line" :data="chartData" :options="chartOptions" class="h-[30rem]" />
      </div>
    </div>
    <hr class="w-auto h-px my-8 bg-white/40 border-0" />
    <AddProduct />
  </div>
</template>
