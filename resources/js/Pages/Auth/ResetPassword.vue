<script setup>
import { useForm } from '@inertiajs/vue3';
import AuthLayout from '@/Layouts/AuthLayout.vue';

const props = defineProps({
    email: String,
    token: String,
});

const form = useForm({
    token: props.token,
    email: props.email,
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post('/reset-password', {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <AuthLayout title="Redefinir Senha">
        <h2 class="text-2xl font-display font-bold text-white mb-1">Nova senha</h2>
        <p class="text-dark-400 text-sm mb-6">Defina sua nova senha abaixo.</p>

        <form @submit.prevent="submit" class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-dark-300 mb-1">Email</label>
                <input v-model="form.email" type="email" class="input-field" required />
                <p v-if="form.errors.email" class="mt-1 text-sm text-red-400">{{ form.errors.email }}</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-dark-300 mb-1">Nova senha</label>
                <input v-model="form.password" type="password" class="input-field"
                       placeholder="Mínimo 8 caracteres" required autofocus />
                <p v-if="form.errors.password" class="mt-1 text-sm text-red-400">{{ form.errors.password }}</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-dark-300 mb-1">Confirmar nova senha</label>
                <input v-model="form.password_confirmation" type="password" class="input-field"
                       placeholder="Repita a nova senha" required />
            </div>

            <button type="submit" :disabled="form.processing" class="btn-primary w-full">
                <span v-if="form.processing">Redefinindo...</span>
                <span v-else>Redefinir senha</span>
            </button>
        </form>
    </AuthLayout>
</template>
