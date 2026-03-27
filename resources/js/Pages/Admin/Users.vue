<script setup>
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import Icon from '@/Components/Icon.vue';

const props = defineProps({
    users: Array,
});

const editingUser = ref(null);
const editForm = useForm({});

const startEdit = (user) => {
    editingUser.value = user.id;
    editForm.name = user.name;
    editForm.email = user.email;
    editForm.phone = user.phone || '';
    editForm.cpf_cnpj = user.cpf_cnpj || '';
    editForm.role = user.role;
    editForm.subscription_status = user.subscription_status || 'pending';
    editForm.subscription_plan = user.subscription_plan || '';
    editForm.subscription_expires_at = user.subscription_expires_at
        ? new Date(user.subscription_expires_at).toISOString().split('T')[0]
        : '';
};

const cancelEdit = () => {
    editingUser.value = null;
};

const saveEdit = (userId) => {
    editForm.put(`/admin/users/${userId}`, {
        preserveScroll: true,
        onSuccess: () => { editingUser.value = null; },
    });
};

const statusLabel = {
    active: 'Ativo',
    pending: 'Pendente',
    trial: 'Trial',
    canceled: 'Cancelado',
};
const statusColor = {
    active: 'text-emerald-400 bg-emerald-400/10',
    pending: 'text-amber-400 bg-amber-400/10',
    trial: 'text-blue-400 bg-blue-400/10',
    canceled: 'text-red-400 bg-red-400/10',
};

const formatDate = (d) => {
    if (!d) return '-';
    return new Date(d).toLocaleDateString('pt-BR');
};
</script>

<template>
    <AppLayout title="Usuários - Admin">
        <div class="mb-8">
            <h1 class="text-2xl font-display font-bold text-white">Usuários</h1>
            <p class="text-dark-400 mt-1">{{ users.length }} usuários cadastrados</p>
        </div>

        <div class="space-y-3">
            <div v-for="user in users" :key="user.id"
                 class="card"
                 :class="editingUser === user.id ? 'border-brand-500/40' : ''">

                <!-- View mode -->
                <template v-if="editingUser !== user.id">
                    <div class="flex items-center justify-between gap-4">
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-3 mb-1">
                                <h3 class="font-bold text-white text-sm truncate">{{ user.name }}</h3>
                                <span v-if="user.role === 'admin'" class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-purple-500/20 text-purple-400">ADMIN</span>
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold"
                                      :class="statusColor[user.subscription_status] || statusColor.pending">
                                    {{ statusLabel[user.subscription_status] || 'Pendente' }}
                                </span>
                            </div>
                            <div class="flex items-center gap-4 text-xs text-dark-400">
                                <span>{{ user.email }}</span>
                                <span v-if="user.phone">{{ user.phone }}</span>
                                <span v-if="user.subscription_plan" class="capitalize">Plano: {{ user.subscription_plan }}</span>
                                <span v-if="user.subscription_expires_at">Renova: {{ formatDate(user.subscription_expires_at) }}</span>
                                <span v-if="user.restaurant" class="text-brand-400">{{ user.restaurant.name }}</span>
                            </div>
                        </div>
                        <button @click="startEdit(user)" class="btn-secondary text-xs py-1.5 px-3">
                            <Icon name="edit" class="w-3.5 h-3.5" />
                            Editar
                        </button>
                    </div>
                </template>

                <!-- Edit mode -->
                <template v-else>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <h3 class="font-bold text-white text-sm">Editando: {{ user.name }}</h3>
                            <button @click="cancelEdit" class="text-dark-400 hover:text-white text-xs">Cancelar</button>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs text-dark-400 mb-1">Nome</label>
                                <input v-model="editForm.name" type="text" class="input-field text-sm" />
                            </div>
                            <div>
                                <label class="block text-xs text-dark-400 mb-1">Email</label>
                                <input v-model="editForm.email" type="email" class="input-field text-sm" />
                            </div>
                            <div>
                                <label class="block text-xs text-dark-400 mb-1">Telefone</label>
                                <input v-model="editForm.phone" type="text" class="input-field text-sm" />
                            </div>
                            <div>
                                <label class="block text-xs text-dark-400 mb-1">CPF/CNPJ</label>
                                <input v-model="editForm.cpf_cnpj" type="text" class="input-field text-sm" />
                            </div>
                            <div>
                                <label class="block text-xs text-dark-400 mb-1">Role</label>
                                <select v-model="editForm.role" class="input-field text-sm">
                                    <option value="owner">Owner</option>
                                    <option value="admin">Admin</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs text-dark-400 mb-1">Status Assinatura</label>
                                <select v-model="editForm.subscription_status" class="input-field text-sm">
                                    <option value="pending">Pendente</option>
                                    <option value="trial">Trial</option>
                                    <option value="active">Ativo</option>
                                    <option value="canceled">Cancelado</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs text-dark-400 mb-1">Plano</label>
                                <input v-model="editForm.subscription_plan" type="text" class="input-field text-sm" placeholder="pro" />
                            </div>
                            <div>
                                <label class="block text-xs text-dark-400 mb-1">Renovação</label>
                                <input v-model="editForm.subscription_expires_at" type="date" class="input-field text-sm" />
                            </div>
                        </div>

                        <div class="flex gap-2 pt-2">
                            <button @click="saveEdit(user.id)" :disabled="editForm.processing" class="btn-primary text-sm py-2 px-4">
                                {{ editForm.processing ? 'Salvando...' : 'Salvar' }}
                            </button>
                            <button @click="cancelEdit" class="btn-secondary text-sm py-2 px-4">Cancelar</button>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </AppLayout>
</template>
