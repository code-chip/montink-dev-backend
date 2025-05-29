import { reactive, computed } from 'vue'
import axios from 'axios'

const state = reactive({
  items: [], // { productId, variation, quantity, price }
  coupon: null
})

export function useCart() {
  function addItem(product, variation, quantity = 1) {
    const price = parseFloat(product.price) // Corrige erro de tipo

    const existing = state.items.find(
      i => i.id === product.id && i.variation === variation
    )

    if (existing) {
      existing.quantity += quantity
      existing.subtotal = parseFloat((existing.price * existing.quantity).toFixed(2))
    } else {
      state.items.push({
        id: product.id,
        variation,
        quantity,
        price,
        subtotal: parseFloat((price * quantity).toFixed(2))
      })
    }
  }

  function removeItem(index) {
    state.items.splice(index, 1)
  }

  function clearCart() {
    state.items = []
    state.coupon = null
  }

  const subtotal = computed(() =>
    state.items.reduce((sum, i) => sum + i.price * i.quantity, 0)
  )

  const shipping = computed(() => {
    if (subtotal.value >= 200) return 0
    if (subtotal.value >= 52 && subtotal.value <= 166.59) return 15
    return 20
  })

  const total = computed(() => {
    const rawTotal = subtotal.value + shipping.value
  
    if (state.coupon) {
      const discount = parseFloat(state.coupon.discount)
      return Math.max(rawTotal - discount, 0)
    }
  
    return rawTotal
  })
  

  const apiUrl = import.meta.env.VITE_API_URL

  async function applyCoupon(code) {
    try {
      const res = await axios.get(`${apiUrl}/coupons`)
  
      const found = res.data.find(c => {
        const expDate = new Date(c.expires_at)
        const now = new Date()
        const minValue = parseFloat(c.min_value)
  
        return (
          c.code === code &&
          expDate >= now &&
          subtotal.value >= minValue
        )
      })
  
      if (found) {
        state.coupon = found
        return { success: true, discount: parseFloat(found.discount) }
      }
  
      return { success: false, message: 'Coupon invalid or not applicable' }
    } catch (err) {
      console.error('Coupon validation error:', err)
      return { success: false, message: 'Error validating coupon' }
    }
  }
  
  const couponDiscount = computed(() => {
    return state.coupon ? parseFloat(state.coupon.discount) : 0
  })

  return {
    state,
    addItem,
    removeItem,
    clearCart,
    subtotal,
    shipping,
    total,
    applyCoupon,
    couponDiscount
  }
}
