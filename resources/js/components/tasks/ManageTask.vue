<script setup>
import axios from 'axios';
import { ref, reactive, onMounted, watch } from 'vue';
import { useRouter, useRoute } from 'vue-router';

const router = useRouter();
const route = useRoute();

// Begin loading tasks

// List of tasks, users, and buildings
let tasks = ref([]);
let buildings = ref([]);
let users = ref([]);

/**
 * Fetches the tasks based on the search query and filter options.
 */

// const getTasks = async () => {
//     let response = await axios.get('/api/task', {
//         params: {
//             searchQuery: searchQuery.value,
//             assignedUser: searchAssignedUser.value,
//             building: searchBuilding.value,
//             startDate: searchstartDate.value,
//             endDate: searchendDate.value
//         }
//     });

//     tasks.value = response.data.tasks;
//     buildings.value = response.data.buildings;
//     users.value = response.data.users;
// };

const getTasks = async () => {

    axios.get('/api/task', {
        params: {
            searchQuery: searchQuery.value,
            assignedUser: searchAssignedUser.value,
            building: searchBuilding.value,
            startDate: searchstartDate.value,
            endDate: searchendDate.value
        }
    })
    .then((response) => {
        if (response.data.success) {
            tasks.value = response.data.tasks;
            buildings.value = response.data.buildings;
            users.value = response.data.users;
            // toast.fire({ icon: "success", title: "Tasks loaded successfully!" });
        } else {
            toast.fire({ icon: "error", title: response.data.message || "Failed to load tasks." });
        }
    })
    .catch((error) => {
        console.error(error);
        toast.fire({ icon: "error", title: "Server error ("+ error.response.status +") while loading tasks." });
    });
};

/**
 * Navigates to the task details page.
 * @param {Object} task - The task object to navigate to.
 */
const detailsTask = (task) => {
    router.push({
        name: 'tasks/taskDescription',
        params: { id: task.id, user_id: form.user_id }
    });
};

// Begin select task
let searchQuery = ref('');
let searchAssignedUser = ref('');
let searchBuilding = ref('');
let searchstartDate = ref('');
let searchendDate = ref('');

/**
 * Reactive form object for creating a new task.
 */
const form = reactive({
    title: "",
    description: "",
    building: "1",
    user_id: "1",
    status: "open"
});

// Begin saving task

/**
 * Submits a new task to the API.
 */
const newTask = () => {
    if (form.title.trim() == "" || form.description.trim() == "") {
        Swal.fire({
            icon: 'warning', // Warning icon
            title: 'Attention!',
            text: 'The title and description must be filled out.',
            confirmButtonText: 'OK',
            confirmButtonColor: '#3085d6',
            timer: 5000 // Auto-close after 5 seconds
        });
        return;
    }

    axios.post('/api/task', form)
        .then((response) => {
            if (response.data.success) {
                router.push('/');
                getTasks();
                clearForm();
                toast.fire({ icon: "success", title: response.data.message });
            } else {
                toast.fire({ icon: "error", title: response.data.message });
            }
        })
        .catch((error) => {
            toast.fire({ icon: "error", title: "Server error ("+ error.response.status +") while loading tasks." });
            console.error(error);
        });
};

/**
 * Clears the form after submitting the task.
 */
const clearForm = () => {
    form.title = "";
    form.description = "";
    form.building = "1";
    form.status = "open";
};

/**
 * Formats a date into a readable string (e.g., "dd Month yyyy").
 * @param {string|Date} date - The date to format.
 * @returns {string} - The formatted date string.
 */
const formatDate = (date) => {
    const d = new Date(date);
    return new Intl.DateTimeFormat('en-GB', {
        year: 'numeric',
        month: 'long',
        day: '2-digit',
        timeZone: 'GMT'
    }).format(d) + ' GMT';
};

/**
 * Returns the appropriate CSS class for the task status.
 * @param {string} status - The status of the task.
 * @returns {string} - The CSS class for the status badge.
 */
const statusBadge = (status) => {
    switch (status) {
        case "open":
            return "badge bg-warning text-dark";
        case "in_progress":
            return "badge bg-primary";
        case "completed":
            return "badge bg-success";
        case "rejected":
            return "badge bg-danger";
        default:
            return "badge bg-secondary";
    }
};

// Fetch tasks on component mount
onMounted(async () => {
    getTasks();
});

// Watch for changes in the search query and fetch tasks accordingly
watch([searchQuery, searchAssignedUser, searchBuilding, searchstartDate, searchendDate], () => {
    getTasks();
});
</script>

<template>
    <div class="container-md">
        <div class="content">
            <div class="container-sm text-center">
                <h3 class="title-h3-sessao">Search Task</h3>
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="row">
                                    <h4 class="title-h4-sessao">Users</h4>
                                    <div class="col-md-2" v-for="user in users" :key="user.id">
                                        <input type="radio" :id="user.id" :value="user.id" v-model="form.user_id">
                                        <label :for="user.id">{{ user.name }}</label>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 mt-1">
                                        <div class="form-floating">
                                            <input type="text" class="form-control" id="title" placeholder="Title" v-model="form.title">
                                            <label for="title">Title:</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mt-1">
                                        <div class="form-floating">
                                            <input type="text" class="form-control" id="description" placeholder="Description" v-model="form.description">
                                            <label for="description">Description:</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mt-1">
                                        <div class="form-floating">
                                            <select class="form-select" name="building" v-model="form.building">
                                                <option v-for="building in buildings" :key="building.id" :value="building.id">{{ building.name }}</option>
                                            </select>
                                            <label for="building">Building: </label>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mt-1">
                                        <div class="form-floating">
                                            <select class="form-select" name="Status" v-model="form.status">
                                                <option value="open">Open</option>
                                                <option value="in_progress">In progress</option>
                                                <option value="completed">Completed</option>
                                                <option value="rejected">Rejected</option>
                                            </select>
                                            <label for="floatingSelect">STATUS:</label>
                                        </div>
                                    </div>
                                </div> <br />

                                <!-- Task action buttons -->
                                <div class="row">
                                    <div class="col-md-6 container d-flex justify-content-center mt-2">
                                        <button class="btn btn-danger flex-grow-1 me-2" @click="clearForm()">Cancel</button>
                                        <button class="btn btn-primary flex-grow-1" @click="newTask()">New Task</button>
                                    </div>
                                </div> <br />

                                <!-- Search functionality -->
                                <div class="row" style="padding-left: 40px;">
                                    <div class="col-12">
                                        <div class="row">
                                            <div class="col-3">
                                                <input v-model="searchQuery" class="form-control" type="text" name="search" id="search" placeholder="Title">
                                            </div>

                                            <div class="col-2">
                                                <select class="form-select" name="user" v-model="searchAssignedUser">
                                                    <option value="">Assigned User</option>
                                                    <option v-for="user in users" :key="user.id" :value="user.id">{{ user.name }}</option>
                                                </select>
                                            </div>

                                            <div class="col-2">
                                                <select class="form-select" name="building" v-model="searchBuilding">
                                                    <option value="">Building</option>
                                                    <option v-for="building in buildings" :key="building.id" :value="building.id">{{ building.name }}</option>
                                                </select>
                                            </div>

                                            <div class="col-4">
                                                <div class="row">
                                                    <div class="col-6">
                                                        <input class="form-control" type="date" v-model="searchstartDate" name="startDate">
                                                    </div>
                                                    <div class="col-6">
                                                        <input class="form-control" type="date" v-model="searchendDate" name="endDate">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-1">
                                                <button class="btn btn-success" @click="getTasks()">Search</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <hr />

                                <!-- Tasks Table -->
                                <div class="row">
                                    <div class="col-md-12">
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th scope="col">ID</th>
                                                    <th scope="col">Title</th>
                                                    <th scope="col">Description</th>
                                                    <th scope="col">Building ID</th>
                                                    <th scope="col">User</th>
                                                    <th scope="col">Status</th>
                                                    <th scope="col">Assigned User</th>
                                                    <th scope="col">Created At</th>
                                                    <th scope="col">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="task in tasks" :key="task.id">
                                                    <td>{{ task.id }}</td>
                                                    <td>{{ task.title }}</td>
                                                    <td>{{ task.description }}</td>
                                                    <td>{{ task.building ? task.building.name : 'Empty' }}</td>
                                                    <td>{{ task.user_created ? task.user_created.name : 'Empty' }}</td>
                                                    <td> <span :class="statusBadge(task.status)">{{ task.status }}</span> </td>
                                                    <td>{{ task.user_updated ? task.user_updated.name : 'Empty' }}</td>
                                                    <td>{{ formatDate(task.created_at) }} </td>
                                                    <td>
                                                        <button class="btn btn-primary" @click="detailsTask(task)">Details</button>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>