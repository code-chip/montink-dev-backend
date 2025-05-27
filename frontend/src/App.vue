<template>
  <div class="container">
    <h1>Product Management</h1>

    <ProductForm
      :product="selectedProduct"
      @saved="handleSaved"
    />

    <ProductList
      :products="products"
      @edit="editProduct"
      @addToCart="addToCart"
    />

    <Cart :products="products" />
  </div>
</template>

<script>
import { ref, onMounted } from 'vue'
import axios from 'axios'
import ProductForm from './components/ProductForm.vue'
import ProductList from './components/ProductList.vue'
import Cart from './components/Cart.vue'
import { useCart } from './composables/useCart'

export default {
  components: { ProductForm, ProductList, Cart },
  setup() {
    const products = ref([])
    const selectedProduct = ref(null)
    const cart = useCart()
    const apiUrl = import.meta.env.VITE_API_URL

    const fetchProducts = async () => {
      const res = await axios.get(`${apiUrl}/products`)
      products.value = res.data
    }

    const handleSaved = () => {
      selectedProduct.value = null
      fetchProducts()
    }

    const editProduct = (product) => {
      selectedProduct.value = product
    }

    const addToCart = (product) => {
      // Exemplo simples: adiciona 1 item com uma variação fictícia "Default"
      cart.addItem(product, 'default', 1)
    }

    onMounted(fetchProducts)

    return {
      products,
      selectedProduct,
      editProduct,
      addToCart,
      handleSaved
    }
  }
}
</script>

<style>
.container {
  max-width: 900px;
  margin: 0 auto;
  padding: 1rem;
  font-family: Arial, sans-serif;
}
</style>
