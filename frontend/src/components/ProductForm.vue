<template>
  <form @submit.prevent="onSubmit">
    <h2>{{ isEditing ? 'Edit' : 'Add' }} Product</h2>

    <div>
      <label>Name:</label>
      <input v-model="name" required />
    </div>

    <div>
      <label>Price (R$):</label>
      <input type="number" v-model.number="price" step="0.01" min="0" required />
    </div>

    <div>
      <label>Variations (comma separated):</label>
      <input v-model="variations" placeholder="e.g. Small, Medium, Large" />
    </div>

    <div v-for="(variation, index) in variationList" :key="index">
      <label>{{ variation }} Stock:</label>
      <input type="number" v-model.number="stock[variation]" min="0" />
    </div>

    <button type="submit">{{ isEditing ? 'Update' : 'Create' }}</button>
  </form>
</template>

<script>
import axios from 'axios'
import { reactive, ref, computed, watch } from 'vue'

export default {
  props: {
    product: Object
  },
  emits: ['saved'],
  setup(props, { emit }) {
    const isEditing = ref(false)
    const productId = ref(null)
    const name = ref('')
    const price = ref(0)
    const variations = ref('')
    const stock = reactive({})
    const apiUrl = import.meta.env.VITE_API_URL

    const variationList = computed(() =>
      variations.value
        .split(',')
        .map(v => v.trim())
        .filter(v => v)
    )

    function resetForm() {
      name.value = ''
      price.value = 0
      variations.value = ''
      Object.keys(stock).forEach(k => delete stock[k])
      isEditing.value = false
      productId.value = null
    }

    // Preenche o formulário quando um produto é recebido
    watch(
      () => props.product,
      (newProduct) => {
        if (newProduct) {
          name.value = newProduct.name
          price.value = newProduct.price
          productId.value = newProduct.id
          isEditing.value = true
          // opcional: carregar variações e estoque se estiverem disponíveis
        } else {
          resetForm()
        }
      },
      { immediate: true }
    )

    async function onSubmit() {
      const productData = {
        name: name.value,
        price: price.value
      }

      let productResponse

      if (!isEditing.value) {
        productResponse = await axios.post(`${apiUrl}/products`, productData)
        productId.value = productResponse.data.id
      } else {
        productResponse = await axios.put(`${apiUrl}/products`, {
          id: productId.value,
          ...productData
        })
      }

      // Criar/atualizar estoques
      for (const v of variationList.value) {
        const qty = stock[v] || 0
        await axios.post(`${apiUrl}/stocks`, {
          product_id: productId.value,
          variation: v,
          quantity: qty
        })
      }

      resetForm()
      emit('saved')
    }

    return {
      isEditing,
      name,
      price,
      variations,
      variationList,
      stock,
      onSubmit
    }
  }
}
</script>

<style scoped>
form {
  border: 1px solid #ccc;
  padding: 1rem;
  margin-bottom: 1rem;
}
form > div {
  margin-bottom: 0.5rem;
}
label {
  display: inline-block;
  width: 120px;
}
input {
  padding: 0.3rem;
  width: 200px;
}
button {
  padding: 0.5rem 1rem;
  cursor: pointer;
}
</style>
