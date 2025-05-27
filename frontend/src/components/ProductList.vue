<template>
  <div>
    <h2>Products</h2>
    <table border="1" cellpadding="8" cellspacing="0">
      <thead>
        <tr>
          <th>Name</th>
          <th>Price (R$)</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="p in products" :key="p.id">
          <td>{{ p.name }}</td>
          <td>{{ parseFloat(p.price).toFixed(2) }}</td>
          <td>
            <button @click="$emit('edit', p)">Edit</button>
            <button @click="$emit('addToCart', p)">Add to Cart</button>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script>
export default {
  props: {
    products: Array
  }
}
</script>

<style scoped>
table {
  width: 100%;
  margin-bottom: 1rem;
}
th {
  background: #f0f0f0;
}
button {
  padding: 0.3rem 0.6rem;
  cursor: pointer;
}
</style>
