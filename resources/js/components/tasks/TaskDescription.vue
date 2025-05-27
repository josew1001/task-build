<script setup>
import { ref, onMounted, reactive, watch } from 'vue';
import { useRouter, useRoute } from 'vue-router';
import axios from 'axios';

const route = useRoute();

let task = ref([]);
let comments = ref([]);
let users = ref([]);

// Reactive form object for comments
const formComment = reactive({
    content: "",
    user_id: "",
    user_name: "",
    task_id: "",
    task_status: ""
});

// Function to get task description
const getTaskDescrition = async () => {
    const taskId = route.params.id;
    try {
        let response = await axios.get('/api/taskDescription?talkId=' + taskId);
        task.value = response.data.task;
        comments.value = response.data.comments;
        users.value = response.data.users;
        
        // Handle Failed
        if (!response.data.success) {
            console.error("Failed to load task:", response.data.message);
            return;
        }    
        
        // Handle Sucess
        toast.fire({ icon: "success", title: response.data.message || "Task loaded successfully." });
    } catch (error) {
        toast.fire({ icon: "error", title: error.response.data.message || "Error accessing base data." });
    }
}

// Function to add a new comment
const newComment = async () => {
    if (formComment.content.trim() == "") {
        Swal.fire({
            icon: 'warning', // Warning icon
            title: 'Attention!',
            text: 'The description must be filled out.',
            confirmButtonText: 'OK',
            confirmButtonColor: '#3085d6',
            timer: 5000 // Auto-close after 5s
        });
        return;
    }

    try {
        let response = await axios.post('/api/taskDescription', formComment);
        
        getTaskDescrition();
        // Handle Failed
        if (!response.data.success) {
            console.error("Failed to load task:", response.data.message);
            return;
        }  


        toast.fire({ icon: "success", title: "Task Added Successfully" });        
        clearForm();
    } catch (error) {
        toast.fire({ icon: "error", title: error.response.data.message || "Error accessing base data." });
        console.log( error.response.data.error );
    }
}

// Function to load form data
const laodForm = () => {
    const taskId = route.params.id;
    const userId = route.params.user_id;

    formComment.task_id = taskId;
    formComment.task_status = task.value.status;
    formComment.user_id = userId;
    const user = users.value.find(user => user.id == userId);
    formComment.user_name = user.name;
}

// Function to clear the form
const clearForm = () => {
    formComment.content = "";
}

// Function to format date
const formatDate = (date) => {
    const d = new Date(date);
    return d.toUTCString();
};

// Function to get the appropriate CSS class for the task status
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

// Fetch task description and load form on component mount
onMounted(async () => {
    await getTaskDescrition();
    laodForm();
});
</script>

<template>
    <div class="container-md">
        <div class="content">
            <div class="container-sm text-center">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="title-h4-sessao">Task Description</h4>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12 mt-3">
                                        <div class="card shadow-lg">
                                            <div class="card-header bg-primary text-white text-center">
                                                <h4>Detalhes do Chamado</h4>
                                            </div>
                                            <div class="card-body">
                                                <div v-if="task">
                                                    <table class="table table-bordered">
                                                        <tbody>
                                                            <tr>
                                                                <th scope="row" class="bg-light">ID</th>
                                                                <td>{{ task.id }}</td>
                                                            </tr>
                                                            <tr>
                                                                <th scope="row" class="bg-light">Título</th>
                                                                <td>{{ task.title }}</td>
                                                            </tr>
                                                            <tr>
                                                                <th scope="row" class="bg-light">Descrição</th>
                                                                <td>{{ task.description }}</td>
                                                            </tr>
                                                            <tr>
                                                                <th scope="row" class="bg-light">Prédio</th>
                                                                <td>{{ task.building ? task.building.name : 'Sem prédio associado' }}</td>
                                                            </tr>
                                                            <tr>
                                                                <th scope="row" class="bg-light">Criado por</th>
                                                                <td>{{ task.user_created ? task.user_created.name : 'Não informado' }}</td>
                                                            </tr>
                                                            <tr>
                                                                <th scope="row" class="bg-light">Status</th>
                                                                <td>
                                                                    <span :class="statusBadge(task.status)">{{ task.status }}</span>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <th scope="row" class="bg-light">Última Modificação</th>
                                                                <td>{{ task.user_updated ? task.user_updated.name : 'Sem alterações' }}</td>
                                                            </tr>
                                                            <tr>
                                                                <th scope="row" class="bg-light">Criado em</th>
                                                                <td>{{ formatDate(task.created_at) }}</td>
                                                            </tr>
                                                            <tr>
                                                                <th scope="row" class="bg-light">Atualizado em</th>
                                                                <td>{{ formatDate(task.updated_at) }}</td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <div v-else class="text-center">
                                                    <p class="text-muted">Carregando detalhes do chamado...</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div> <br />
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="card shadow-lg">
                                            <div class="card-header bg-secondary text-white text-center">
                                                <h4>Comentários</h4>
                                            </div>
                                            <div class="card-body">
                                                <div v-if="comments.length > 0">
                                                    <div v-for="comment in comments" :key="comment.id" class="card mb-3">
                                                        <div class="card-body">
                                                            <div class="d-flex justify-content-between align-items-center">
                                                                <h5 class="fw-bold m-0">{{ comment.user_created ? comment.user_created.name : 'Usuário desconhecido' }}:</h5>
                                                                <i class="bi bi-person-circle me-2 text-primary" style="font-size: 1.5rem;"></i>
                                                                <p class="mb-0 text-muted">
                                                                    <i class="text-muted m-0"></i> {{ formatDate(comment.created_at) }}
                                                                </p>
                                                            </div>
                                                            <p class="mt-2">{{ comment.content }}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div v-else class="text-center">
                                                    <p class="text-muted">Nenhum comentário encontrado.</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="card shadow-sm border rounded p-3">
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <!-- User Name -->
                                                        <h5 class="fw-bold m-0"> {{ formComment.user_name }}</h5>
                                                        <!-- Task Status -->
                                                        <div class="text-muted m-0">
                                                            <label>
                                                                <select v-model="formComment.task_status" class="form-select">
                                                                    <option value="open">Open</option>
                                                                    <option value="in_progress">In Progress</option>
                                                                    <option value="completed">Completed</option>
                                                                    <option value="rejected">Rejected</option>
                                                                </select>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <!-- Comment Content -->
                                                    <div class="text-center mt-2">
                                                        <textarea class="form-control" id="description" placeholder="Description" v-model="formComment.content"></textarea>
                                                        <button class="btn btn-primary" @click="newComment()">New Comment</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
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