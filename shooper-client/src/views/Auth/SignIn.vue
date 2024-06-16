<script setup>
import AuthLayout from '@/layouts/Views/AuthLayout.vue'
import TextField from '@/components/TextField.vue'
import Button1 from '@/components/Button1.vue'
import Alerts from '@/components/Alerts.vue'
import { useAuthStore } from '@/store/auth'
</script>

<script>
export default {
  components: {
    TextField,
    Button1,
    Alerts
  },
  data() {
    return {
      email: '',
      password: '',
      toast: null,
      disableSubmit: false,
      authStore: null
    }
  },
  created() {
    this.authStore = useAuthStore()
  },
  methods: {
    checkFields() {
      if (this.email === '' && this.password === '') {
        this.$refs.alerts.warn('Fields are empty!', 'Fields should not be empty!')
        return false
      }
      if (this.email === '') {
        this.$refs.alerts.warn('Email field is empty!', 'Email field should not be empty!')
        return false
      }
      if (this.password === '') {
        this.$refs.alerts.warn('Password field is empty!', 'Password field should not be empty!')
        return false
      }
      return true
    },
    async handleSubmit() {
      this.disableSubmit = true

      setTimeout(() => {
        this.disableSubmit = false
      }, 3500)

      if (!this.checkFields()) {
        return false
      }

      try {
        const response = await this.authStore.signIn(this.email, this.password)
        if (response) {
          this.$router.replace({ path: '/admin/dashboard' })
        } else {
          this.$router.replace({ path: '/' })
        }
      } catch (error) {
        this.$refs.alerts.error('Sign In failed!', error)
      }
    }
  }
}
</script>

<template>
  <AuthLayout>
    <Alerts ref="alerts" />
    <div
      class="w-full h-screen max-md:h-fit flex flex-row max-md:flex-col gap-12 px-16 py-32 max-sm:px-10"
    >
      <form
        @submit.prevent="handleSubmit"
        class="w-full h-full md:max-h-max bg-[#242424] px-16 max-md:py-16 max-sm:p-10 flex flex-col gap-12 justify-center items-end rounded-xl overflow-hidden"
      >
        <span
          class="w-full capitalize text-4xl max-md:text-3xl max-sm:text-2xl tracking-wider font-montserrat font-bold text-white"
          >sign in to your
          <span class="font-montserrat font-black leading-relaxed uppercase text-[#F36F3F]">
            sho<span class="text-white">oper</span>
          </span>
          account!
        </span>
        <div class="w-full flex flex-col gap-5">
          <TextField name="email" placeholder="email" v-model="email" label="email" type="text"
            >Email</TextField
          >
          <TextField name="password" placeholder="password" v-model="password" type="password"
            >Password</TextField
          >
          <div
            class="flex items-center justify-between max-md:flex-col max-md:items-start max-md:gap-2.5"
          >
            <a
              href=""
              class="capitalize font-montserrat max-md:text-sm max-sm:text-xs underline text-[#6694FE] leading-relaxed"
              >forgot your password?</a
            >
            <div class="inline-flex gap-2.5 items-center">
              <label class="relative flex items-center rounded-full cursor-pointer" htmlFor="check">
                <input
                  type="checkbox"
                  class="before:content[''] peer relative h-5 w-5 max-md:w-4 max-md:h-4 cursor-pointer appearance-none rounded-md border border-white transition-all before:absolute before:top-2/4 before:left-2/4 before:block before:-translate-y-2/4 before:-translate-x-2/4 before:rounded-full before:transition-opacity"
                  id="check"
                />
                <span
                  class="absolute text-white transition-opacity opacity-0 pointer-events-none top-2/4 left-2/4 -translate-y-2/4 -translate-x-2/4 peer-checked:opacity-100"
                >
                  <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="h-3.5 w-3.5 max-md:h-2.5 max-2.5"
                    viewBox="0 0 20 20"
                    overflow-hidden
                    fill="currentColor"
                    stroke="currentColor"
                    stroke-width="1"
                  >
                    <path
                      fill-rule="evenodd"
                      d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                      clip-rule="evenodd"
                    ></path>
                  </svg>
                </span>
              </label>
              <label
                class="mt-px font-montserrat max-md:text-sm max-sm:text-xs text-white cursor-pointer select-none"
                htmlFor="check"
              >
                Remember Me
              </label>
            </div>
          </div>
        </div>
        <Button1 :disabled="disableSubmit">sign in</Button1>
      </form>
      <div
        class="w-full h-full max-md:max-h-max bg-[#242424] px-16 max-md:py-16 max-sm:p-10 flex flex-col gap-12 justify-center rounded-xl overflow-hidden"
      >
        <span
          class="w-full capitalize text-4xl max-md:text-3xl max-sm:text-2xl tracking-wider leading-normal font-montserrat font-bold text-white"
          >still don't have an account? sign up now!
        </span>
        <div
          class="flex flex-col gap-2.5 font-montserrat text-white tracking-wider leading-relaxed"
        >
          <span class="text-xl max-md:text-lg max-sm:text-normal font-medium text-[#F36F3F] mb-2">
            Why choose shooper?
          </span>
          <ul
            class="list-disc list-inside leading-relaxed tracking-wider flex flex-col gap-2.5 max-md:text-sm max-sm:text-xs"
          >
            <li>Get notifications when a product reaches your desired price.</li>
            <li>Compare product prices between different shops.</li>
            <li>See history price of a product.</li>
            <li>Thousands of supported products.</li>
          </ul>
        </div>
        <router-link class="w-fit" to="/auth/signup">
          <Button1>sign up</Button1>
        </router-link>
      </div>
    </div>
  </AuthLayout>
</template>
