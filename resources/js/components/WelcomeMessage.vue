<template>
  <div class="vue-component-container">
    <h2 class="text-xl font-semibold mb-2">¡Hola desde Vue!</h2>
    <p>Este es un componente Vue renderizado en tu Blade.</p>
    <button
      @click="increment"
      class="mt-4 px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition-colors"
    >
      Contador: {{ count }}
    </button>
  </div>
</template>

<script setup>
import { ref } from 'vue';

const count = ref(0);

const increment = () => {
  count.value++;
};
</script>

<style scoped>
.vue-component-container {
  border: 1px solid #ccc;
  padding: 1rem;
  border-radius: 8px;
  background-color: #f9f9f9;
  text-align: center;
  margin-top: 2rem; /* Just for spacing in the example */
}

/* Dark mode styles */
@media (prefers-color-scheme: dark) {
  .vue-component-container {
    background-color: #1a1a1a;
    border-color: #444;
    color: #eee;
  }
}
</style>