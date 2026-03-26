<script setup>
import { Link, router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import Icon from '@/Components/Icon.vue';

const props = defineProps({
    stories: Array,
});

const deleteStory = (id) => {
    if (confirm('Tem certeza que deseja excluir este story?')) {
        router.delete(`/stories/${id}`);
    }
};

const toggleActive = (story) => {
    router.put(`/stories/${story.id}`, {
        ...story,
        active: !story.active,
    }, { preserveScroll: true });
};
</script>

<template>
    <AppLayout title="Stories">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-2xl lg:text-3xl font-display font-bold text-white">Stories / Destaques</h1>
                <p class="text-dark-400 mt-1">Imagens que aparecem nos destaques do seu cardapio</p>
            </div>
            <Link href="/stories/create" class="btn-primary">
                <Icon name="plus" class="w-4 h-4" />
                Novo Story
            </Link>
        </div>

        <!-- Empty -->
        <div v-if="stories.length === 0" class="card text-center py-12">
            <div class="flex justify-center mb-4">
                <div class="bg-dark-800 rounded-full p-4">
                    <Icon name="image" class="w-10 h-10 text-dark-500" />
                </div>
            </div>
            <p class="text-dark-400 text-lg mb-2">Nenhum story ainda</p>
            <p class="text-dark-500 text-sm mb-4">Adicione imagens que aparecerao como destaques no cardapio publico.</p>
            <Link href="/stories/create" class="btn-primary">
                <Icon name="plus" class="w-4 h-4" />
                Adicionar primeiro story
            </Link>
        </div>

        <!-- List -->
        <div v-else class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
            <div v-for="story in stories" :key="story.id"
                 class="card p-0 overflow-hidden group relative">
                <!-- Image -->
                <div class="aspect-[9/16] w-full overflow-hidden bg-dark-800">
                    <img :src="story.image" :alt="story.title || 'Story'"
                         class="w-full h-full object-cover" />
                </div>

                <!-- Info overlay -->
                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-black/30 opacity-0 group-hover:opacity-100 transition-opacity flex flex-col justify-between p-3">
                    <!-- Top actions -->
                    <div class="flex justify-end gap-1">
                        <Link :href="`/stories/${story.id}/edit`"
                              class="w-8 h-8 rounded-full bg-black/50 flex items-center justify-center text-white/80 hover:text-white">
                            <Icon name="edit" class="w-4 h-4" />
                        </Link>
                        <button @click="deleteStory(story.id)"
                                class="w-8 h-8 rounded-full bg-black/50 flex items-center justify-center text-white/80 hover:text-red-400">
                            <Icon name="trash" class="w-4 h-4" />
                        </button>
                    </div>

                    <!-- Bottom info -->
                    <div>
                        <p v-if="story.title" class="text-white text-sm font-semibold truncate">{{ story.title }}</p>
                        <div class="flex items-center gap-2 mt-1">
                            <button @click="toggleActive(story)"
                                    :class="story.active ? 'text-emerald-400' : 'text-dark-400'"
                                    class="text-xs flex items-center gap-1">
                                <Icon name="check" class="w-3 h-3" />
                                {{ story.active ? 'Ativo' : 'Inativo' }}
                            </button>
                            <span class="text-xs text-dark-400 flex items-center gap-1">
                                <Icon name="eye" class="w-3 h-3" />
                                {{ story.views_count }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Active indicator (always visible) -->
                <div class="absolute top-2 left-2">
                    <div :class="story.active ? 'bg-emerald-500' : 'bg-dark-500'"
                         class="w-2.5 h-2.5 rounded-full"></div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
