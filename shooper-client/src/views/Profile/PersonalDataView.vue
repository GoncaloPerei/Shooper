<script setup>
import TextField from '@/components/TextField.vue'
import { useAuthStore } from '@/store/auth'
import { useUsersStore } from '@/store/useUsersCrud'
import { twMerge } from 'tailwind-merge'
import Button1 from '@/components/Button1.vue'

const store = useAuthStore()
const usersStore = useUsersStore()
</script>

<template>
  <div class="w-full h-auto flex flex-col gap-8 p-8 bg-[#242424] rounded-lg">
    <span class="font-bold text-xl tracking-wider">Account Data</span>
    <div class="w-full h-auto flex gap-8">
      <TextField
        :class="twMerge('text-sm')"
        name="name"
        placeholder="Full Name"
        v-model="store.user.name"
        >Full Name *</TextField
      >
      <TextField
        :class="twMerge('text-sm')"
        name="email"
        placeholder="Email"
        v-model="store.user.email"
        >Email *</TextField
      >
    </div>
    <div class="w-full h-auto flex gap-8">
      <TextField
        :class="twMerge('text-sm')"
        name="phoneNumber"
        placeholder="Phone Number"
        v-model="store.user.phoneNumber"
        >Phone Number</TextField
      >
    </div>
    <Button1 :class="twMerge('text-sm py-3')">Save</Button1>
  </div>
  <div class="w-full h-auto flex flex-col gap-8 p-8 bg-[#242424] rounded-lg">
    <span class="font-bold text-xl tracking-wider">Change Password</span>
    <div class="w-full h-auto flex gap-8">
      <TextField
        :class="twMerge('text-sm')"
        name="currentPassword"
        placeholder="Current Password"
        v-model="currentPassword"
      >Current Password</TextField>
    </div>
    <div class="w-full h-auto flex gap-8">
      <TextField
        :class="twMerge('text-sm')"
        name="newPassword"
        placeholder="New Password"
        v-model="newPassword"
      >New Password</TextField>
      <TextField
        :class="twMerge('text-sm')"
        name="confirmNewPassword"
        placeholder="Confirm New Password"
        v-model="confirmPassword"
      >Confirm New Password</TextField>
    </div>
    <Button1 :class="twMerge('text-sm py-3')">Save</Button1>
  </div>
</template>
