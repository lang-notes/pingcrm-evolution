<template>
    <div>
      <Head :title="`${form.name} ${form.description}`" />
      <h1 class="mb-8 text-3xl font-bold">
        <Link class="text-indigo-400 hover:text-indigo-600" href="/products">Products</Link>
        <span class="text-indigo-400 font-medium">/</span>
        {{ form.name }} {{ form.description }}
      </h1>
      <trashed-message v-if="product.deleted_at" class="mb-6" @restore="restore"> This product has been deleted. </trashed-message>
      <div class="max-w-3xl bg-white rounded-md shadow overflow-hidden">
        <form @submit.prevent="update">
          <div class="flex flex-wrap -mb-8 -mr-6 p-8">
            <text-input v-model="form.name" :error="form.errors.name" class="pb-8 pr-6 w-full lg:w-1/2" label="Name" />
            <text-input v-model="form.description" :error="form.errors.description" class="pb-8 pr-6 w-full lg:w-1/2" label="Description" />
          </div>
          <div class="flex items-center px-8 py-4 bg-gray-50 border-t border-gray-100">
            <button v-if="!product.deleted_at" class="text-red-600 hover:underline" tabindex="-1" type="button" @click="destroy">Delete Product</button>
            <loading-button :loading="form.processing" class="btn-indigo ml-auto" type="submit">Update Product</loading-button>
          </div>
        </form>
      </div>
    </div>
  </template>
  
  <script>
  import { Head, Link } from '@inertiajs/inertia-vue3'
  import Layout from '@/Shared/Layout'
  import TextInput from '@/Shared/TextInput'
  import SelectInput from '@/Shared/SelectInput'
  import LoadingButton from '@/Shared/LoadingButton'
  import TrashedMessage from '@/Shared/TrashedMessage'
  
  export default {
    components: {
      Head,
      Link,
      LoadingButton,
      SelectInput,
      TextInput,
      TrashedMessage,
    },
    layout: Layout,
    props: {
      product: Object,
    },
    remember: 'form',
    data() {
      return {
        form: this.$inertia.form({
          name: this.product.name,
          description: this.product.description,
        }),
      }
    },
    methods: {
      update() {
        this.form.put(`/products/${this.product.id}`)
      },
      destroy() {
        if (confirm('Are you sure you want to delete this product?')) {
          this.$inertia.delete(`/products/${this.product.id}`)
        }
      },
      restore() {
        if (confirm('Are you sure you want to restore this product?')) {
          this.$inertia.put(`/products/${this.product.id}/restore`)
        }
      },
    },
  }
  </script>
  