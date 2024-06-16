<template>
  <Toast class="font-montserrat tracking-wider" position="bottom-center"></Toast>
</template>

<script>
import Toast from 'primevue/toast'
import { useToast } from 'primevue/usetoast'

export default {
  components: {
    Toast
  },
  data() {
    return {
      toast: null
    }
  },
  created() {
    this.toast = useToast()
  },
  methods: {
    error(summary, detail) {
      this.toast.add({
        severity: 'error',
        summary: summary,
        detail: detail,
        closable: true,
        life: 3500
      })
    },
    success(summary, detail) {
      this.toast.add({
        severity: 'success',
        summary: summary,
        detail: detail,
        closable: true,
        life: 3500
      })
    },
    secondary(summary, detail) {
      this.toast.add({
        severity: 'secondary',
        summary: summary,
        detail: detail,
        closable: true,
        life: 3500
      })
    },
    info(summary, detail) {
      this.toast.add({
        severity: 'info',
        summary: summary,
        detail: detail,
        closable: true,
        life: 3500
      })
    },
    contrast(summary, detail) {
      this.toast.add({
        severity: 'contrast',
        summary: summary,
        detail: detail,
        closable: true,
        life: 3500
      })
    },
    warn(summary, detail) {
      this.toast.add({
        severity: 'warn',
        summary: summary,
        detail: detail,
        closable: true,
        life: 3500
      })
    }
  }
}
</script>
