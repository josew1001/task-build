/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

import './bootstrap';
import { createApp } from 'vue';
import Swal from 'sweetalert2';
window.Swal = Swal
const toast = Swal.mixin({
  toast:true,
  position:'top-end',
  showConfirmButton: false,
  timer: 3000,
  timerProgressBar: true
})
window.toast = toast;


import app from './components/App.vue'

import router from './router'

createApp(app).use(router).mount('#app')

/**
 * Next, we will create a fresh Vue application instance. You may then begin
 * registering components with the application instance so they are ready
 * to use in your application's views. An example is included for you.
 */

// const app = createApp({});

// import ExampleComponent from './components/ExampleComponent.vue';
// app.component('example-component', ExampleComponent);

// app.mount('#app');

