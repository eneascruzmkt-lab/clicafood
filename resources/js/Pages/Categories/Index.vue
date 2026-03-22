<script setup>
import { ref } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({
    categories: Array,
});

const deleting = ref(null);

const deleteCategory = (id) => {
    if (confirm('Tem certeza que deseja excluir esta categoria?')) {
        router.delete(`/categories/${id}`);
    }
};

const toggleActive = (category) => {
    router.put(`/categories/${category.id}`, {
        name: category.name,
        active: !category.active,
    }, { preserveScroll: true });
};
</script>

<template>
    <AppLayout title="Categorias">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-2xl lg:text-3xl font-display font-bold text-white">Categorias</h1>
                <p class="text-dark-400 mt-1">Organize seu cardápio em categorias</p>
            </div>
            <Link href="/categories/create" class="btn-primary">
                + Nova Categoria
            </Link>
        </div>

        <div v-if="categories.length === 0" class="card text-center py-12">
            <p class="text-dark-400 text-lg mb-4">Nenhuma categoria criada ainda</p>
            <Link href="/categories/create" class="btn-primary">
                Criar primeira categoria
            </Link>
        </div>

        <div v-else class="space-y-3">
            <div v-for="category in categories" :key="category.id"
                 class="card flex items-center gap-4 hover:border-dark-600 transition-colors">
                <!-- Image -->
                <div class="w-16 h-16 rounded-lg overflow-hidden flex-shrink-0 bg-dark-700">
                    <img v-if="category.image" :src="'/storage/' + category.image" :alt="category.name"
                         class="w-full h-full object-cover" />
                    <div v-else class="w-full h-full flex items-center justify-center text-dark-500 text-xl">
                        📁
                    </div>
                </div>

                <!-- Info -->
                <div class="flex-1 min-w-0">
                    <h3 class="font-semibold text-white">{{ category.name }}</h3>
                    <p class="text-sm text-dark-400">{{ category.menu_items_count }} itens</p>
                </div>

                <!-- Status -->
                <button @click="toggleActive(category)"
                        :class="category.active ? 'bg-green-500/20 text-green-400' : 'bg-dark-700 text-dark-400'"
                        class="px-3 py-1 rounded-full text-xs font-medium transition-colors">
                    {{ category.active ? 'Ativo' : 'Inativo' }}
                </button>

                <!-- Actions -->
                <div class="flex items-center gap-2">
                    <Link :href="`/categories/${category.id}/edit`"
                          class="text-dark-400 hover:text-white transition-colors p-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                    </Link>
                    <button @click="deleteCategory(category.id)"
                            class="text-dark-400 hover:text-red-400 transition-colors p-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
