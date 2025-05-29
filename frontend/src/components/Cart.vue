<template>
  <div>
    <h2>Shopping Cart</h2>
    <div v-if="cart.state.items.length === 0">Cart is empty</div>
    <table v-else border="1" cellpadding="6" cellspacing="0">
      <thead>
        <tr>
          <th>Product ID</th>
          <th>Variation</th>
          <th>Qty</th>
          <th>Price (R$)</th>
          <th>Subtotal</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(item, index) in cart.state.items" :key="index">
          <td>{{ item.id }}</td>
          <td>{{ item.variation }}</td>
          <td>{{ item.quantity }}</td>
          <td>{{ parseFloat(item.price).toFixed(2) }}</td>
          <td>{{ (item.price * item.quantity).toFixed(2) }}</td>
          <td><button @click="cart.removeItem(index)">Remove</button></td>
        </tr>
      </tbody>
    </table>

    <div>
      <label>Coupon Code:</label>
      <input v-model="couponCode" />
      <button @click="applyCouponCode">Apply</button>
      <div v-if="couponMessage" style="color: red">{{ couponMessage }}</div>
    </div>

    <div>
      <p>Subtotal: R$ {{ formatCurrency(cart.subtotal) }}</p>
      <p>Shipping: R$ {{ formatCurrency(cart.shipping) }}</p>
      <p><strong>Total: R$ {{ formatCurrency(cart.total) }}</strong></p>
    </div>

    <div>
      <label>Address (CEP + street):</label><br />
      <input v-model="cep" placeholder="CEP (e.g., 01001-000)" @blur="fetchAddress" />
      <textarea v-model="address" rows="3" placeholder="Street, number, neighborhood, city, state"></textarea>
    </div>

    <button @click="finalizeOrder" :disabled="cart.state.items.length === 0">Finalize Order</button>

    <div v-if="orderMessage" style="color: green">{{ orderMessage }}</div>
  </div>
</template>

<script>
import { ref } from 'vue'
import { useCart } from '../composables/useCart'
import axios from 'axios'

export default {
  setup() {
    const cart = useCart()
    const couponCode = ref('')
    const couponMessage = ref('')
    const cep = ref('')
    const address = ref('')
    const orderMessage = ref('')
    const apiUrl = import.meta.env.VITE_API_URL

    function formatCurrency(amountRef) {
      const val = amountRef?.value
      return typeof val === 'number' ? val.toFixed(2) : '0.00'
    }

    async function applyCouponCode() {
      const res = await cart.applyCoupon(couponCode.value)
      if (res.success) {
        couponMessage.value = `Coupon applied! Discount: R$ ${res.discount.toFixed(2)}`
      } else {
        couponMessage.value = res.message
      }
    }

    async function fetchAddress() {
      if (!cep.value) return
      const sanitizedCep = cep.value.replace(/\D/g, '')
      try {
        const res = await axios.get(`https://viacep.com.br/ws/${sanitizedCep}/json/`)
        if (res.data.erro) {
          address.value = ''
          alert('CEP not found')
        } else {
          const { logradouro, bairro, localidade, uf } = res.data
          address.value = `${logradouro}, ${bairro}, ${localidade} - ${uf}`
        }
      } catch {
        alert('Error fetching CEP info')
      }
    }

    async function finalizeOrder() {
      if (!address.value) {
        alert('Please fill in the address')
        return
      }
      
      const orderPayload = {
        status: 'pending',
        subtotal: cart.subtotal.value,
        discount: cart.couponDiscount.value,
        total: cart.total.value,
        address: address.value,
        cep: cep.value,
        shipping: cart.shipping.value,
        products: cart.state.items
      }

      try {
        await axios.post(`${apiUrl}/orders`, orderPayload)
        orderMessage.value = 'Order placed successfully!'
        cart.clearCart()
        couponCode.value = ''
        couponMessage.value = ''
        cep.value = ''
        address.value = ''
      } catch (error) {
          error.response?.data?.message ||
          error.message ||
          'Failed to place order'
        alert('Failed to place order: ${message}')
      }
    }

    return {
      cart,
      couponCode,
      couponMessage,
      cep,
      address,
      orderMessage,
      applyCouponCode,
      fetchAddress,
      finalizeOrder,
      formatCurrency
    }
  }
}
</script>

<style scoped>
table {
  width: 100%;
  margin-bottom: 1rem;
}
input, textarea {
  width: 100%;
  margin-bottom: 0.5rem;
  padding: 0.3rem;
}
button {
  padding: 0.5rem 1rem;
  cursor: pointer;
}
</style>
