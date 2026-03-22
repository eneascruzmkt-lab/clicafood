<script setup>
import { ref } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({
    restaurants: Object,
    search: String,
});

const searchQuery = ref(props.search || '');

const doSearch = () => {
    router.get('/admin/restaurants', { search: searchQuery.value }, { preserveState: true });
};

const deleteRestaurant = (id) => {
    if (confirm('Tem certeza? Esta ação é irreversível.')) {
        router.delete(`/admin/restaurants/${id}`);
    }
};
</script>

<template>
    <AppLayout title="Admin - Restaurantes">
        <div class="flex items-center justify-between mb-8">
            <h1 class="text-2xl font-display font-bold text-white">Restaurantes</h1>
            <form @submit.prevent="doSearch" class="flex gap-2">
                <input v-model="searchQuery" type="text" class="input-field w-64" placeholder="Buscar..." />
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
                        <th class="pb-3 font-medium">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="r in restaurants.data" :key="r.id"
                        class="border-b border-dark-800 last:border-0">
                        <td class="py-3 text-white font-medium">{{ r.name }}</td>
                        <td class="py-3 text-dark-300">{{ r.user?.email }}</td>
                        <td class="py-3 text-dark-400">{{ r.menu_items_count }}</td>
                        <td class="py-3 text-dark-400">{{ r.categories_count }}</td>
                        <td class="py-3 flex gap-2">
                            <Link :href="`/admin/restaurants/${r.id}`"
                                  class="text-brand-400 hover:text-brand-300 text-xs">Ver</Link>
                            <button @click="deleteRestaurant(r.id)"
                                    class="text-red-400 hover:text-red-300 text-xs">Excluir</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </AppLayout>
</template>
