<script setup>
import { useForm } from '@inertiajs/vue3';
import AuthLayout from '@/Layouts/AuthLayout.vue';

const form = useForm({
    name: '',
    email: '',
    cpf_cnpj: '',
    phone: '',
    password: '',
    password_confirmation: '',
    restaurant_name: '',
});

const submit = () => {
    form.post('/register', {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <AuthLayout title="Criar Conta">
        <h2 class="text-2xl font-display font-bold text-white mb-1">Crie sua conta</h2>
        <p class="text-dark-400 text-sm mb-6">Comece a dominar seu cardápio digital agora</p>

        <form @submit.prevent="submit" class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-dark-300 mb-1">Nome completo</label>
                <input v-model="form.name" type="text" class="input-field" placeholder="Seu nome"
                       required autofocus />
                <p v-if="form.errors.name" class="mt-1 text-sm text-red-400">{{ form.errors.name }}</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-dark-300 mb-1">Email</label>
                <input v-model="form.email" type="email" class="input-field" placeholder="seu@email.com"
                       required />
                <p v-if="form.errors.email" class="mt-1 text-sm text-red-400">{{ form.errors.email }}</p>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-sm font-medium text-dark-300 mb-1">CPF/CNPJ</label>
                    <input v-model="form.cpf_cnpj" type="text" class="input-field" placeholder="000.000.000-00" />
                    <p v-if="form.errors.cpf_cnpj" class="mt-1 text-sm text-red-400">{{ form.errors.cpf_cnpj }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-dark-300 mb-1">Telefone</label>
                    <input v-model="form.phone" type="text" class="input-field" placeholder="(11) 99999-9999" />
                    <p v-if="form.errors.phone" class="mt-1 text-sm text-red-400">{{ form.errors.phone }}</p>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-dark-300 mb-1">Nome do Restaurante</label>
                <input v-model="form.restaurant_name" type="text" class="input-field"
                       placeholder="Nome do seu restaurante" required />
                <p v-if="form.errors.restaurant_name" class="mt-1 text-sm text-red-400">{{ form.errors.restaurant_name }}</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-dark-300 mb-1">Senha</label>
                <input v-model="form.password" type="password" class="input-field" placeholder="Mínimo 8 caracteres"
                       required />
                <p v-if="form.errors.password" class="mt-1 text-sm text-red-400">{{ form.errors.password }}</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-dark-300 mb-1">Confirmar Senha</label>
                <input v-model="form.password_confirmation" type="password" class="input-field"
                       placeholder="Repita a senha" required />
            </div>

            <button type="submit" :disabled="form.processing" class="btn-primary w-full text-lg py-4">
                <span v-if="form.processing">Criando conta...</span>
                <span v-else>Criar conta</span>
            </button>

            <p class="text-center text-dark-400 text-sm">
                Já tem conta?
                <a href="/login" class="text-brand-500 hover:text-brand-400 font-semibold">Entrar</a>
            </p>
        </form>
    </AuthLayout>
</template>
