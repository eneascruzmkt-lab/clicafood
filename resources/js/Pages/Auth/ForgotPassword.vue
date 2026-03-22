<script setup>
import { useForm } from '@inertiajs/vue3';
import AuthLayout from '@/Layouts/AuthLayout.vue';

defineProps({
    status: String,
});

const form = useForm({
    email: '',
});

const submit = () => {
    form.post('/forgot-password');
};
</script>

<template>
    <AuthLayout title="Esqueceu a Senha">
        <h2 class="text-2xl font-display font-bold text-white mb-1">Recuperar senha</h2>
        <p class="text-dark-400 text-sm mb-6">
            Informe seu email e enviaremos um link para redefinir sua senha.
        </p>

        <div v-if="status" class="mb-4 p-3 bg-emerald-500/10 border border-emerald-500/20 rounded-lg text-emerald-400 text-sm">
            {{ status }}
        </div>

        <form @submit.prevent="submit" class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-dark-300 mb-1">Email</label>
                <input v-model="form.email" type="email" class="input-field" placeholder="seu@email.com"
                       required autofocus />
                <p v-if="form.errors.email" class="mt-1 text-sm text-red-400">{{ form.errors.email }}</p>
            </div>

            <button type="submit" :disabled="form.processing" class="btn-primary w-full">
                <span v-if="form.processing">Enviando...</span>
                <span v-else>Enviar link de recuperação</span>
            </button>

            <p class="text-center text-dark-400 text-sm">
                <a href="/login" class="text-brand-500 hover:text-brand-400 font-semibold">Voltar para login</a>
            </p>
        </form>
    </AuthLayout>
</template>
