<script setup>
import { Link, router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import Icon from '@/Components/Icon.vue';

const props = defineProps({
    categories: Array,
});

const mediaUrl = (path) => {
    if (!path) return null;
    if (path.startsWith('http')) return path;
    return '/storage/' + path;
};

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
                <p class="text-dark-400 mt-1">Organize seu cardapio em categorias</p>
            </div>
            <Link href="/categories/create" class="btn-primary">
                <Icon name="plus" class="w-4 h-4" />
                Nova Categoria
            </Link>
        </div>

        <!-- Empty State -->
        <div v-if="categories.length === 0" class="card text-center py-12">
            <div class="flex justify-center mb-4">
                <div class="bg-dark-800 rounded-full p-4">
                    <Icon name="tag" class="w-10 h-10 text-dark-500" />
                </div>
            </div>
            <p class="text-dark-400 text-lg mb-4">Nenhuma categoria criada ainda</p>
            <Link href="/categories/create" class="btn-primary">
                <Icon name="plus" class="w-4 h-4" />
                Criar primeira categoria
            </Link>
        </div>

        <!-- Categories List -->
        <div v-else class="space-y-3">
            <div
                v-for="category in categories"
                :key="category.id"
                class="card flex items-center gap-4 hover:border-dark-600 transition-colors"
            >
                <!-- Image -->
                <div class="w-16 h-16 rounded-lg overflow-hidden flex-shrink-0 bg-dark-700">
                    <img
                        v-if="category.image"
                        :src="mediaUrl(category.image)"
                        :alt="category.name"
                        class="w-full h-full object-cover"
                    />
                    <div v-else class="w-full h-full flex items-center justify-center">
                        <Icon name="tag" class="w-6 h-6 text-dark-500" />
                    </div>
                </div>

                <!-- Info -->
                <div class="flex-1 min-w-0">
                    <h3 class="font-semibold text-white">{{ category.name }}</h3>
                    <p class="text-sm text-dark-400">{{ category.menu_items_count }} itens</p>
                </div>

                <!-- Status Toggle -->
                <button
                    @click="toggleActive(category)"
                    :class="category.active ? 'bg-emerald-500/15 text-emerald-400' : 'bg-dark-700 text-dark-400'"
                    class="px-3 py-1 rounded-full text-xs font-medium transition-colors"
                >
                    {{ category.active ? 'Ativo' : 'Inativo' }}
                </button>

                <!-- Actions -->
                <div class="flex items-center gap-1">
                    <Link
                        :href="`/categories/${category.id}/edit`"
                        class="text-dark-400 hover:text-white transition-colors p-2"
                    >
                        <Icon name="edit" class="w-5 h-5" />
                    </Link>
                    <button
                        @click="deleteCategory(category.id)"
                        class="text-dark-400 hover:text-red-400 transition-colors p-2"
                    >
                        <Icon name="trash" class="w-5 h-5" />
                    </button>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
