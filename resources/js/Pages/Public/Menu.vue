<script setup>
import { ref, computed, onMounted } from 'vue';
import { Head } from '@inertiajs/vue3';
import axios from 'axios';

const props = defineProps({
    restaurant: Object,
    categories: Array,
    items: Array,
    featured: Array,
});

const activeCategory = ref(null);
const showVideoModal = ref(false);
const currentVideo = ref(null);
const showStory = ref(false);
const currentStoryIndex = ref(0);

// Filter items by category
const filteredItems = computed(() => {
    if (!activeCategory.value) return props.items;
    return props.items.filter(item => item.category_id === activeCategory.value);
});

const formatPrice = (price) => {
    return new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' }).format(price);
};

// Track events
const trackEvent = (eventType, menuItemId) => {
    axios.post(`/${props.restaurant.slug}/track`, {
        event_type: eventType,
        menu_item_id: menuItemId,
    }).catch(() => {});
};

// Open video modal
const openVideo = (item) => {
    currentVideo.value = item;
    showVideoModal.value = true;
    trackEvent('video_play', item.id);
};

const closeVideo = () => {
    showVideoModal.value = false;
    currentVideo.value = null;
};

// Stories
const openStory = (index) => {
    currentStoryIndex.value = index;
    showStory.value = true;
    trackEvent('video_play', props.featured[index].id);
};

const nextStory = () => {
    if (currentStoryIndex.value < props.featured.length - 1) {
        currentStoryIndex.value++;
        trackEvent('video_play', props.featured[currentStoryIndex.value].id);
    } else {
        showStory.value = false;
    }
};

const prevStory = () => {
    if (currentStoryIndex.value > 0) {
        currentStoryIndex.value--;
    }
};

const closeStory = () => {
    showStory.value = false;
};

const getVideoUrl = (item) => {
    if (!item.video_url) return null;
    if (item.video_url.startsWith('http')) return item.video_url;
    return '/storage/' + item.video_url;
};

const getImageUrl = (path) => {
    if (!path) return null;
    if (path.startsWith('http')) return path;
    return '/storage/' + path;
};

// Dynamic primary color
const primaryColor = computed(() => props.restaurant.primary_color || '#E63B2E');
</script>

<template>
    <div class="min-h-screen" :style="{ backgroundColor: props.restaurant.secondary_color || '#0d0d0d' }">
        <Head :title="restaurant.name + ' - Cardápio Digital'" />

        <!-- Header -->
        <header class="sticky top-0 z-20 backdrop-blur-lg border-b"
                :style="{ backgroundColor: (restaurant.secondary_color || '#0d0d0d') + 'ee', borderColor: '#333' }">
            <div class="max-w-lg mx-auto px-4 py-3 flex items-center gap-3">
                <div v-if="restaurant.logo" class="w-10 h-10 rounded-full overflow-hidden flex-shrink-0">
                    <img :src="getImageUrl(restaurant.logo)" :alt="restaurant.name" class="w-full h-full object-cover" />
                </div>
                <div>
                    <h1 class="font-display font-bold text-white text-lg leading-tight">{{ restaurant.name }}</h1>
                    <p v-if="restaurant.description" class="text-xs text-gray-400 truncate max-w-[200px]">
                        {{ restaurant.description }}
                    </p>
                </div>
                <div class="ml-auto flex gap-2">
                    <a v-if="restaurant.whatsapp"
                       :href="`https://wa.me/${restaurant.whatsapp}`" target="_blank"
                       class="w-8 h-8 rounded-full flex items-center justify-center"
                       :style="{ backgroundColor: primaryColor + '20', color: primaryColor }">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                        </svg>
                    </a>
                    <a v-if="restaurant.instagram"
                       :href="`https://instagram.com/${restaurant.instagram.replace('@','')}`" target="_blank"
                       class="w-8 h-8 rounded-full flex items-center justify-center"
                       :style="{ backgroundColor: primaryColor + '20', color: primaryColor }">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/>
                        </svg>
                    </a>
                </div>
            </div>
        </header>

        <div class="max-w-lg mx-auto px-4 pb-8">
            <!-- Stories / Featured -->
            <div v-if="featured.length > 0" class="py-4 -mx-4 px-4 overflow-x-auto">
                <div class="flex gap-3">
                    <button v-for="(item, index) in featured" :key="item.id"
                            @click="openStory(index)"
                            class="flex-shrink-0 text-center">
                        <div class="w-16 h-16 rounded-full p-0.5 mb-1"
                             :style="{ background: `linear-gradient(135deg, ${primaryColor}, #ff8c00)` }">
                            <div class="w-full h-full rounded-full overflow-hidden border-2"
                                 :style="{ borderColor: restaurant.secondary_color || '#0d0d0d' }">
                                <img v-if="item.image" :src="getImageUrl(item.image)" :alt="item.name"
                                     class="w-full h-full object-cover" />
                                <div v-else class="w-full h-full bg-dark-700 flex items-center justify-center text-xs">
                                    ▶
                                </div>
                            </div>
                        </div>
                        <p class="text-[10px] text-gray-400 w-16 truncate">{{ item.name }}</p>
                    </button>
                </div>
            </div>

            <!-- Categories filter -->
            <div class="flex gap-2 py-3 overflow-x-auto -mx-4 px-4 scrollbar-hide">
                <button @click="activeCategory = null"
                        :class="!activeCategory ? 'text-white' : 'text-gray-400'"
                        :style="!activeCategory ? { backgroundColor: primaryColor } : { backgroundColor: '#333' }"
                        class="flex-shrink-0 px-4 py-2 rounded-full text-sm font-medium transition-colors">
                    Todos
                </button>
                <button v-for="cat in categories" :key="cat.id"
                        @click="activeCategory = cat.id"
                        :class="activeCategory === cat.id ? 'text-white' : 'text-gray-400'"
                        :style="activeCategory === cat.id ? { backgroundColor: primaryColor } : { backgroundColor: '#333' }"
                        class="flex-shrink-0 px-4 py-2 rounded-full text-sm font-medium transition-colors">
                    {{ cat.name }}
                </button>
            </div>

            <!-- Menu Items -->
            <div class="space-y-3 mt-4">
                <div v-for="item in filteredItems" :key="item.id"
                     @click="trackEvent('click', item.id)"
                     class="flex gap-3 p-3 rounded-xl transition-colors hover:bg-white/5 cursor-pointer"
                     :style="{ backgroundColor: '#1a1a1a' }">
                    <!-- Image with video indicator -->
                    <div class="w-20 h-20 rounded-lg overflow-hidden flex-shrink-0 relative">
                        <img v-if="item.image" :src="getImageUrl(item.image)" :alt="item.name"
                             class="w-full h-full object-cover" loading="lazy" />
                        <div v-else class="w-full h-full bg-dark-700 flex items-center justify-center text-2xl">
                            🍽️
                        </div>
                        <!-- Play button overlay -->
                        <button v-if="item.video_url" @click.stop="openVideo(item)"
                                class="absolute inset-0 flex items-center justify-center bg-black/40 hover:bg-black/30 transition-colors">
                            <div class="w-8 h-8 rounded-full flex items-center justify-center"
                                 :style="{ backgroundColor: primaryColor }">
                                <svg class="w-4 h-4 text-white ml-0.5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M8 5v14l11-7z"/>
                                </svg>
                            </div>
                        </button>
                    </div>

                    <!-- Info -->
                    <div class="flex-1 min-w-0 py-1">
                        <h3 class="font-semibold text-white text-sm">{{ item.name }}</h3>
                        <p v-if="item.description" class="text-xs text-gray-400 mt-0.5 line-clamp-2">
                            {{ item.description }}
                        </p>
                        <p class="font-bold mt-1.5 text-sm" :style="{ color: primaryColor }">
                            {{ formatPrice(item.price) }}
                        </p>
                    </div>
                </div>

                <p v-if="filteredItems.length === 0" class="text-center text-gray-500 py-8 text-sm">
                    Nenhum item nesta categoria
                </p>
            </div>

            <!-- Footer -->
            <div class="mt-8 pt-4 border-t border-gray-800 text-center">
                <p class="text-xs text-gray-500">
                    Powered by
                    <span class="font-display font-bold">
                        <span :style="{ color: primaryColor }">Clica</span>Food
                    </span>
                </p>
            </div>
        </div>

        <!-- Video Modal -->
        <teleport to="body">
            <div v-if="showVideoModal && currentVideo" class="fixed inset-0 z-50 bg-black/90 flex items-center justify-center p-4"
                 @click.self="closeVideo">
                <div class="relative max-w-lg w-full">
                    <button @click="closeVideo"
                            class="absolute -top-10 right-0 text-white/60 hover:text-white text-xl">
                        ✕
                    </button>
                    <video :src="getVideoUrl(currentVideo)" controls autoplay
                           class="w-full rounded-xl max-h-[80vh]"
                           playsinline></video>
                    <div class="mt-3">
                        <h3 class="font-bold text-white">{{ currentVideo.name }}</h3>
                        <p class="text-sm text-gray-400">{{ currentVideo.description }}</p>
                        <p class="font-bold mt-1" :style="{ color: primaryColor }">
                            {{ formatPrice(currentVideo.price) }}
                        </p>
                    </div>
                </div>
            </div>
        </teleport>

        <!-- Stories Fullscreen -->
        <teleport to="body">
            <div v-if="showStory && featured.length > 0" class="fixed inset-0 z-50 bg-black flex items-center justify-center">
                <div class="relative w-full max-w-lg h-full">
                    <!-- Progress bars -->
                    <div class="absolute top-2 left-2 right-2 flex gap-1 z-10">
                        <div v-for="(_, i) in featured" :key="i" class="flex-1 h-0.5 rounded-full bg-white/30">
                            <div class="h-full rounded-full bg-white transition-all duration-300"
                                 :style="{ width: i < currentStoryIndex ? '100%' : (i === currentStoryIndex ? '100%' : '0%') }">
                            </div>
                        </div>
                    </div>

                    <!-- Close -->
                    <button @click="closeStory" class="absolute top-6 right-4 z-10 text-white/80 hover:text-white text-xl">
                        ✕
                    </button>

                    <!-- Content -->
                    <div class="h-full flex items-center justify-center">
                        <video v-if="featured[currentStoryIndex]?.video_url"
                               :src="getVideoUrl(featured[currentStoryIndex])"
                               autoplay playsinline
                               @ended="nextStory"
                               class="max-h-full max-w-full object-contain">
                        </video>
                        <img v-else-if="featured[currentStoryIndex]?.image"
                             :src="getImageUrl(featured[currentStoryIndex].image)"
                             class="max-h-full max-w-full object-contain" />
                    </div>

                    <!-- Item info -->
                    <div class="absolute bottom-0 left-0 right-0 p-6 bg-gradient-to-t from-black/80 to-transparent">
                        <h3 class="font-bold text-white text-lg">{{ featured[currentStoryIndex]?.name }}</h3>
                        <p class="text-sm text-gray-300">{{ featured[currentStoryIndex]?.description }}</p>
                        <p class="font-bold text-lg mt-1" :style="{ color: primaryColor }">
                            {{ formatPrice(featured[currentStoryIndex]?.price) }}
                        </p>
                    </div>

                    <!-- Navigation areas -->
                    <div class="absolute inset-0 flex">
                        <div class="w-1/3 h-full" @click="prevStory"></div>
                        <div class="w-1/3 h-full"></div>
                        <div class="w-1/3 h-full" @click="nextStory"></div>
                    </div>
                </div>
            </div>
        </teleport>
    </div>
</template>

<style scoped>
.scrollbar-hide {
    -ms-overflow-style: none;
    scrollbar-width: none;
}
.scrollbar-hide::-webkit-scrollbar {
    display: none;
}
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
