import {createRouter, createWebHistory} from "vue-router"

import notFound from "../components/notFound.vue";

import manageTask from "../components/tasks/ManageTask.vue"
import taskDescription from "../components/tasks/TaskDescription.vue"

const routes = [
  {
    path: '/manageTask',
    name: 'tasks/manageTask',
    component: manageTask
  },
  {
    path: '/taskDescription/:id/:user_id',
    name: 'tasks/taskDescription',
    component: taskDescription,
    props: true
  },
  {
    path:'/exemple',
    alias: '/',
    name: 'tasks/manageTask',
    component: manageTask
  },
  {
    path:'/:pathMatch(.*)*',
    name:'notfound',
    component: notFound
  }  
]

// const base = process.env.BASE_URL || '/';
const base = import.meta.env.BASE_URL || '/';

// const base = '/';


const router = createRouter({
  // history: createWebHistory(),
  history: createWebHistory(base),
  routes
})

export default router