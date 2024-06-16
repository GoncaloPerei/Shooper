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
      name: '',
      email: '',
      password: '',
      toast: null,
      emailRegex: /^[^\s@]+@[^\s@]+\.[^\s@]+$/,
      passwordRegex: /^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#\$%\^&\*+\_-])(?=.{8,})/,
      nameRegex: /^[a-zA-Z ]+$/,
      authStore: null
    }
  },
  created() {
    this.authStore = useAuthStore()
  },
  methods: {
    checkFields() {
      if (this.name === '' && this.email === '' && this.password === '') {
        this.$refs.alerts.warn('Fields are empty!', 'Fields should not be empty!')
        return false
      }
      if (this.name === '') {
        this.$refs.alerts.warn('Name field is empty!', 'Name field should not be empty!')
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
      if (!this.nameRegex.exec(this.name)) {
        this.$refs.alerts.warn('Name is not valid!', 'Enter a valid name!')
        return false
      }
      if (!this.emailRegex.exec(this.email)) {
        this.$refs.alerts.warn('Email is not valid!', 'Enter a valid email!')
        return false
      }
      if (!this.passwordRegex.exec(this.password)) {
        this.$refs.alerts.warn('Password is not valid!', 'Enter a valid password!')
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
        await this.authStore.signUp(this.name, this.email, this.password)
        this.$router.replace({ path: '/auth/signin' })
      } catch (error) {
        this.$refs.alerts.error('Sign Up failed!', error)
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
        class="w-full h-full max-md:max-h-max bg-[#242424] px-16 max-md:py-16 max-sm:p-10 flex flex-col gap-6 justify-center items-end rounded-xl overflow-hidden"
      >
        <span
          class="w-full capitalize text-4xl max-md:text-3xl max-sm:text-2xl tracking-wider font-montserrat font-bold text-white"
          >sign up to
          <span class="font-montserrat font-black leading-relaxed uppercase text-[#F36F3F]">
            sho<span class="text-white">oper</span>
          </span>
          !
        </span>
        <div class="w-full flex flex-col gap-5">
          <TextField name="name" placeholder="name" v-model="name" type="text" label="name" />
          <TextField
            name="up_email"
            placeholder="email"
            v-model="email"
            type="text"
            label="email"
          />
          <TextField
            name="password"
            placeholder="password"
            v-model="password"
            label="password"
            type="password"
          />
          <div
            class="text-white font-montserrat text-sm max-sm:text-xs tracking-wider leading-relaxed overflow-hidden"
          >
            Password needs to meet the following 3 requirements:
            <ul class="list-disc list-inside leading-relaxed tracking-wider">
              <li>Uppercase character</li>
              <li>Special character</li>
              <li>Number</li>
            </ul>
          </div>
        </div>
        <Button1>sign up</Button1>
      </form>
      <div
        class="w-full h-full md:max-h-max bg-[#242424] px-16 max-md:py-16 max-sm:p-10 flex flex-col gap-12 justify-center rounded-xl overflow-hidden"
      >
        <span
          class="w-full capitalize text-4xl max-md:text-3xl max-sm:text-2xl tracking-wider leading-normal font-montserrat font-bold text-white"
          >already have an account? sign in now!
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
        <router-link class="w-fit" to="/auth/signin">
          <Button1>sign in</Button1>
        </router-link>
      </div>
    </div>
  </AuthLayout>
</template>
