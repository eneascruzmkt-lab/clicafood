<script setup>
import { useForm } from '@inertiajs/vue3';
import AuthLayout from '@/Layouts/AuthLayout.vue';

defineProps({
    status: String,
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post('/login', {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <AuthLayout title="Entrar">
        <h2 class="text-2xl font-display font-bold text-white mb-1">Entrar</h2>
        <p class="text-dark-400 text-sm mb-6">Acesse seu painel e domine seu cardápio</p>

        <div v-if="status" class="mb-4 p-3 bg-green-500/10 border border-green-500/20 rounded-lg text-green-400 text-sm">
            {{ status }}
        </div>

        <form @submit.prevent="submit" class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-dark-300 mb-1">Email</label>
                <input v-model="form.email" type="email" class="input-field" placeholder="seu@email.com"
                       required autofocus />
                <p v-if="form.errors.email" class="mt-1 text-sm text-red-400">{{ form.errors.email }}</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-dark-300 mb-1">Senha</label>
                <input v-model="form.password" type="password" class="input-field" placeholder="Sua senha"
                       required />
                <p v-if="form.errors.password" class="mt-1 text-sm text-red-400">{{ form.errors.password }}</p>
            </div>

            <div class="flex items-center justify-between">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input v-model="form.remember" type="checkbox"
                           class="w-4 h-4 rounded border-dark-600 bg-dark-800 text-brand-500 focus:ring-brand-500" />
                    <span class="text-sm text-dark-300">Lembrar-me</span>
                </label>
                <a href="/forgot-password" class="text-sm text-brand-500 hover:text-brand-400">
                    Esqueceu a senha?
                </a>
            </div>

            <button type="submit" :disabled="form.processing" class="btn-primary w-full text-lg py-4">
                <span v-if="form.processing">Entrando...</span>
                <span v-else>Entrar</span>
            </button>

            <p class="text-center text-dark-400 text-sm">
                Não tem conta?
                <a href="/register" class="text-brand-500 hover:text-brand-400 font-semibold">Criar conta grátis</a>
            </p>
        </form>
    </AuthLayout>
</template>
