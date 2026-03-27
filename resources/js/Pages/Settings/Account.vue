<script setup>
import { useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import Icon from '@/Components/Icon.vue';

const props = defineProps({
    user: Object,
    subscription: Object,
});

const statusLabel = {
    active: 'Ativo',
    pending: 'Pendente',
    trial: 'Período de teste',
    canceled: 'Cancelado',
};
const statusColor = {
    active: 'text-emerald-400 bg-emerald-400/10',
    pending: 'text-amber-400 bg-amber-400/10',
    trial: 'text-blue-400 bg-blue-400/10',
    canceled: 'text-red-400 bg-red-400/10',
};

const profileForm = useForm({
    name: props.user.name,
    email: props.user.email,
    cpf_cnpj: props.user.cpf_cnpj || '',
    phone: props.user.phone || '',
});

const passwordForm = useForm({
    current_password: '',
    password: '',
    password_confirmation: '',
});

const updateProfile = () => {
    profileForm.put('/account');
};

const updatePassword = () => {
    passwordForm.put('/account/password', {
        onSuccess: () => passwordForm.reset(),
    });
};
</script>

<template>
    <AppLayout title="Minha Conta">
        <div class="max-w-2xl">
            <div class="mb-8">
                <h1 class="text-2xl font-display font-bold text-white">Minha Conta</h1>
                <p class="text-dark-400 mt-1">Gerencie seus dados pessoais</p>
            </div>

            <!-- Subscription Info -->
            <div class="card space-y-4 mb-6">
                <h3 class="font-bold text-white flex items-center gap-2">
                    <Icon name="star" class="w-5 h-5 text-brand-400" />
                    Plano e Assinatura
                </h3>
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-dark-300">Plano atual</p>
                        <p class="text-lg font-bold text-white capitalize">{{ subscription.plan || 'Free' }}</p>
                    </div>
                    <span class="px-3 py-1 rounded-full text-xs font-semibold"
                          :class="statusColor[subscription.status] || statusColor.pending">
                        {{ statusLabel[subscription.status] || 'Pendente' }}
                    </span>
                </div>
                <div v-if="subscription.expires_at" class="flex items-center gap-2 text-sm">
                    <Icon name="calendar" class="w-4 h-4 text-dark-400" />
                    <span class="text-dark-300">Próxima renovação: <strong class="text-white">{{ subscription.expires_at }}</strong></span>
                </div>
                <div v-if="subscription.status !== 'active' && subscription.status !== 'trial'" class="pt-2">
                    <a href="/plans" class="btn-primary text-sm inline-flex items-center gap-2">
                        <Icon name="star" class="w-4 h-4" />
                        Assinar plano
                    </a>
                </div>
            </div>

            <!-- Profile -->
            <form @submit.prevent="updateProfile" class="card space-y-4 mb-6">
                <h3 class="font-bold text-white flex items-center gap-2">
                    <Icon name="user" class="w-5 h-5 text-brand-400" />
                    Dados pessoais
                </h3>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-dark-300 mb-1">Nome</label>
                        <input v-model="profileForm.name" type="text" class="input-field" required />
                        <p v-if="profileForm.errors.name" class="mt-1 text-sm text-red-400">{{ profileForm.errors.name }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-dark-300 mb-1">Email</label>
                        <input v-model="profileForm.email" type="email" class="input-field" required />
                        <p v-if="profileForm.errors.email" class="mt-1 text-sm text-red-400">{{ profileForm.errors.email }}</p>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-dark-300 mb-1">CPF/CNPJ</label>
                        <input v-model="profileForm.cpf_cnpj" type="text" class="input-field"
                               placeholder="000.000.000-00" />
                        <p v-if="profileForm.errors.cpf_cnpj" class="mt-1 text-sm text-red-400">{{ profileForm.errors.cpf_cnpj }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-dark-300 mb-1">Telefone</label>
                        <input v-model="profileForm.phone" type="text" class="input-field"
                               placeholder="(11) 99999-9999" />
                        <p v-if="profileForm.errors.phone" class="mt-1 text-sm text-red-400">{{ profileForm.errors.phone }}</p>
                    </div>
                </div>

                <button type="submit" :disabled="profileForm.processing" class="btn-primary">
                    {{ profileForm.processing ? 'Salvando...' : 'Salvar dados' }}
                </button>
            </form>

            <!-- Password -->
            <form @submit.prevent="updatePassword" class="card space-y-4">
                <h3 class="font-bold text-white flex items-center gap-2">
                    <Icon name="shield" class="w-5 h-5 text-brand-400" />
                    Alterar senha
                </h3>

                <div>
                    <label class="block text-sm font-medium text-dark-300 mb-1">Senha atual</label>
                    <input v-model="passwordForm.current_password" type="password" class="input-field" required />
                    <p v-if="passwordForm.errors.current_password" class="mt-1 text-sm text-red-400">
                        {{ passwordForm.errors.current_password }}
                    </p>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-dark-300 mb-1">Nova senha</label>
                        <input v-model="passwordForm.password" type="password" class="input-field" required />
                        <p v-if="passwordForm.errors.password" class="mt-1 text-sm text-red-400">
                            {{ passwordForm.errors.password }}
                        </p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-dark-300 mb-1">Confirmar nova senha</label>
                        <input v-model="passwordForm.password_confirmation" type="password" class="input-field" required />
                    </div>
                </div>

                <button type="submit" :disabled="passwordForm.processing" class="btn-primary">
                    {{ passwordForm.processing ? 'Alterando...' : 'Alterar senha' }}
                </button>
            </form>
        </div>
    </AppLayout>
</template>
