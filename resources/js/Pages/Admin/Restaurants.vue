<script setup>
import { ref } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import Icon from '@/Components/Icon.vue';

const props = defineProps({
    restaurants: Object,
    search: String,
});

const searchQuery = ref(props.search || '');

const doSearch = () => {
    router.get('/admin/restaurants', { search: searchQuery.value }, { preserveState: true });
};

const deleteRestaurant = (id) => {
    if (confirm('Tem certeza? Esta acao e irreversivel.')) {
        router.delete(`/admin/restaurants/${id}`);
    }
};
</script>

<template>
    <AppLayout title="Admin - Restaurantes">
        <div class="flex items-center justify-between mb-8">
            <h1 class="text-2xl font-display font-bold text-white">Restaurantes</h1>
            <form @submit.prevent="doSearch" class="flex gap-2">
                <div class="relative">
                    <Icon name="search" class="w-4 h-4 text-dark-400 absolute left-3 top-1/2 -translate-y-1/2" />
                    <input v-model="searchQuery" type="text" class="input-field w-64 pl-9" placeholder="Buscar por nome ou email..." />
                </div>
                <button type="submit" class="btn-secondary text-sm">Buscar</button>
            </form>
        </div>

        <div class="card overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-dark-400 border-b border-dark-700">
                        <th class="pb-3 font-medium">Nome</th>
                        <th class="pb-3 font-medium">Dono</th>
                        <th class="pb-3 font-medium">Itens</th>
                        <th class="pb-3 font-medium">Categorias</th>
                        <th class="pb-3 font-medium">Acoes</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="r in restaurants.data" :key="r.id"
                        class="border-b border-dark-800 last:border-0">
                        <td class="py-3 text-white font-medium">{{ r.name }}</td>
                        <td class="py-3 text-dark-300">{{ r.user?.email }}</td>
                        <td class="py-3 text-dark-400">{{ r.menu_items_count }}</td>
                        <td class="py-3 text-dark-400">{{ r.categories_count }}</td>
                        <td class="py-3">
                            <div class="flex items-center gap-3">
                                <Link :href="`/admin/restaurants/${r.id}`"
                                      class="text-brand-400 hover:text-brand-300 inline-flex items-center gap-1 text-xs">
                                    <Icon name="eye" class="w-3.5 h-3.5" />
                                    Ver
                                </Link>
                                <button @click="deleteRestaurant(r.id)"
                                        class="text-red-400 hover:text-red-300 inline-flex items-center gap-1 text-xs">
                                    <Icon name="trash" class="w-3.5 h-3.5" />
                                    Excluir
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
            <p v-if="restaurants.data.length === 0" class="text-dark-400 text-sm text-center py-4">
                Nenhum restaurante encontrado
            </p>
        </div>
    </AppLayout>
</template>
