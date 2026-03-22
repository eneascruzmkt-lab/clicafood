<script setup>
import { ref, computed, onMounted, onUnmounted, watch } from 'vue';
import { Head } from '@inertiajs/vue3';
import Icon from '@/Components/Icon.vue';
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
const storyProgress = ref(0);
let storyTimer = null;
const STORY_DURATION = 5000;

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
const startStoryTimer = () => {
    clearInterval(storyTimer);
    storyProgress.value = 0;
    const currentItem = props.featured[currentStoryIndex.value];
    if (currentItem?.video_url) return;
    const step = 100 / (STORY_DURATION / 50);
    storyTimer = setInterval(() => {
        storyProgress.value += step;
        if (storyProgress.value >= 100) {
            nextStory();
        }
    }, 50);
};

const openStory = (index) => {
    currentStoryIndex.value = index;
    showStory.value = true;
    trackEvent('click', props.featured[index].id);
    startStoryTimer();
};

const nextStory = () => {
    if (currentStoryIndex.value < props.featured.length - 1) {
        currentStoryIndex.value++;
        trackEvent('click', props.featured[currentStoryIndex.value].id);
        startStoryTimer();
    } else {
        closeStory();
    }
};

const prevStory = () => {
    if (currentStoryIndex.value > 0) {
        currentStoryIndex.value--;
        startStoryTimer();
    }
};

const closeStory = () => {
    showStory.value = false;
    clearInterval(storyTimer);
    storyProgress.value = 0;
};

const onStoryVideoEnded = () => {
    nextStory();
};

const onStoryVideoPlay = () => {
    clearInterval(storyTimer);
    storyProgress.value = 0;
    const currentItem = props.featured[currentStoryIndex.value];
    if (currentItem) {
        trackEvent('video_play', currentItem.id);
    }
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
const secondaryColor = computed(() => props.restaurant.secondary_color || '#0d0d0d');

onUnmounted(() => {
    clearInterval(storyTimer);
});
</script>

<template>
    <div class="min-h-screen" :style="{ backgroundColor: secondaryColor }">
        <Head :title="restaurant.name + ' - Cardapio Digital'" />

        <!-- Header -->
        <header class="sticky top-0 z-20 backdrop-blur-lg border-b"
                :style="{ backgroundColor: secondaryColor + 'ee', borderColor: '#333' }">
            <div class="max-w-lg mx-auto px-4 py-3 flex items-center gap-3">
                <div v-if="restaurant.logo" class="w-10 h-10 rounded-full overflow-hidden flex-shrink-0 ring-2"
                     :style="{ ringColor: primaryColor + '40' }">
                    <img :src="getImageUrl(restaurant.logo)" :alt="restaurant.name" class="w-full h-full object-cover" />
                </div>
                <div v-else class="w-10 h-10 rounded-full flex items-center justify-center flex-shrink-0"
                     :style="{ backgroundColor: primaryColor + '20' }">
                    <Icon name="restaurant" class="w-5 h-5" :style="{ color: primaryColor }" />
                </div>
                <div class="flex-1 min-w-0">
                    <h1 class="font-display font-bold text-white text-lg leading-tight">{{ restaurant.name }}</h1>
                    <p v-if="restaurant.description" class="text-xs text-gray-400 truncate">
                        {{ restaurant.description }}
                    </p>
                </div>
                <div class="flex gap-2 flex-shrink-0">
                    <a v-if="restaurant.whatsapp"
                       :href="`https://wa.me/${restaurant.whatsapp}`" target="_blank" rel="noopener"
                       class="w-9 h-9 rounded-full flex items-center justify-center transition-colors"
                       :style="{ backgroundColor: primaryColor + '20', color: primaryColor }">
                        <Icon name="phone" class="w-4 h-4" />
                    </a>
                    <a v-if="restaurant.instagram"
                       :href="`https://instagram.com/${restaurant.instagram.replace('@','')}`" target="_blank" rel="noopener"
                       class="w-9 h-9 rounded-full flex items-center justify-center transition-colors"
                       :style="{ backgroundColor: primaryColor + '20', color: primaryColor }">
                        <Icon name="instagram" class="w-4 h-4" />
                    </a>
                </div>
            </div>
        </header>

        <div class="max-w-lg mx-auto px-4 pb-8">
            <!-- Stories / Featured -->
            <div v-if="featured.length > 0" class="py-4 -mx-4 px-4 overflow-x-auto scrollbar-hide">
                <div class="flex gap-3">
                    <button v-for="(item, index) in featured" :key="item.id"
                            @click="openStory(index)"
                            class="flex-shrink-0 text-center group">
                        <div class="w-16 h-16 rounded-full p-0.5 mb-1"
                             :style="{ background: `linear-gradient(135deg, ${primaryColor}, #ff8c00)` }">
                            <div class="w-full h-full rounded-full overflow-hidden border-2"
                                 :style="{ borderColor: secondaryColor }">
                                <img v-if="item.image" :src="getImageUrl(item.image)" :alt="item.name"
                                     class="w-full h-full object-cover" />
                                <div v-else class="w-full h-full flex items-center justify-center"
                                     :style="{ backgroundColor: primaryColor + '15' }">
                                    <Icon name="play" class="w-4 h-4" :style="{ color: primaryColor }" />
                                </div>
                            </div>
                        </div>
                        <p class="text-[10px] text-gray-400 w-16 truncate group-hover:text-gray-300 transition-colors">{{ item.name }}</p>
                    </button>
                </div>
            </div>

            <!-- Categories filter -->
            <div class="flex gap-2 py-3 overflow-x-auto -mx-4 px-4 scrollbar-hide">
                <button @click="activeCategory = null"
                        :class="!activeCategory ? 'text-white' : 'text-gray-400 hover:text-gray-300'"
                        :style="!activeCategory ? { backgroundColor: primaryColor } : { backgroundColor: '#333' }"
                        class="flex-shrink-0 px-4 py-2 rounded-full text-sm font-medium transition-colors">
                    Todos
                </button>
                <button v-for="cat in categories" :key="cat.id"
                        @click="activeCategory = cat.id"
                        :class="activeCategory === cat.id ? 'text-white' : 'text-gray-400 hover:text-gray-300'"
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
                        <div v-else class="w-full h-full bg-dark-700 flex items-center justify-center">
                            <Icon name="image" class="w-8 h-8 text-dark-500" />
                        </div>
                        <!-- Play button overlay -->
                        <button v-if="item.video_url" @click.stop="openVideo(item)"
                                class="absolute inset-0 flex items-center justify-center bg-black/40 hover:bg-black/30 transition-colors">
                            <div class="w-8 h-8 rounded-full flex items-center justify-center"
                                 :style="{ backgroundColor: primaryColor }">
                                <Icon name="play" class="w-4 h-4 text-white ml-0.5" />
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
            <Transition
                enter-active-class="transition-opacity duration-200 ease-out"
                enter-from-class="opacity-0"
                enter-to-class="opacity-100"
                leave-active-class="transition-opacity duration-150 ease-in"
                leave-from-class="opacity-100"
                leave-to-class="opacity-0"
            >
                <div v-if="showVideoModal && currentVideo"
                     class="fixed inset-0 z-50 bg-black/90 flex items-center justify-center p-4"
                     @click.self="closeVideo">
                    <div class="relative max-w-lg w-full">
                        <button @click="closeVideo"
                                class="absolute -top-10 right-0 text-white/60 hover:text-white transition-colors">
                            <Icon name="close" class="w-6 h-6" />
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
            </Transition>
        </teleport>

        <!-- Stories Fullscreen -->
        <teleport to="body">
            <Transition
                enter-active-class="transition-opacity duration-200 ease-out"
                enter-from-class="opacity-0"
                enter-to-class="opacity-100"
                leave-active-class="transition-opacity duration-150 ease-in"
                leave-from-class="opacity-100"
                leave-to-class="opacity-0"
            >
                <div v-if="showStory && featured.length > 0"
                     class="fixed inset-0 z-50 bg-black flex items-center justify-center">
                    <div class="relative w-full max-w-lg h-full">
                        <!-- Progress bars -->
                        <div class="absolute top-2 left-2 right-2 flex gap-1 z-10">
                            <div v-for="(_, i) in featured" :key="i"
                                 class="flex-1 h-0.5 rounded-full bg-white/30 overflow-hidden">
                                <div class="h-full rounded-full bg-white transition-all"
                                     :style="{
                                         width: i < currentStoryIndex ? '100%' : (i === currentStoryIndex ? storyProgress + '%' : '0%'),
                                         transitionDuration: i === currentStoryIndex ? '50ms' : '300ms'
                                     }">
                                </div>
                            </div>
                        </div>

                        <!-- Close button -->
                        <button @click="closeStory"
                                class="absolute top-6 right-4 z-10 text-white/80 hover:text-white transition-colors">
                            <Icon name="close" class="w-6 h-6" />
                        </button>

                        <!-- Content -->
                        <div class="h-full flex items-center justify-center">
                            <video v-if="featured[currentStoryIndex]?.video_url"
                                   :key="'story-video-' + currentStoryIndex"
                                   :src="getVideoUrl(featured[currentStoryIndex])"
                                   autoplay playsinline
                                   @ended="onStoryVideoEnded"
                                   @play="onStoryVideoPlay"
                                   class="max-h-full max-w-full object-contain">
                            </video>
                            <img v-else-if="featured[currentStoryIndex]?.image"
                                 :key="'story-img-' + currentStoryIndex"
                                 :src="getImageUrl(featured[currentStoryIndex].image)"
                                 :alt="featured[currentStoryIndex].name"
                                 class="max-h-full max-w-full object-contain" />
                            <div v-else class="flex flex-col items-center justify-center text-gray-500">
                                <Icon name="image" class="w-16 h-16 mb-2" />
                                <span class="text-sm">Sem midia</span>
                            </div>
                        </div>

                        <!-- Item info overlay -->
                        <div class="absolute bottom-0 left-0 right-0 p-6 bg-gradient-to-t from-black/80 to-transparent">
                            <h3 class="font-bold text-white text-lg">{{ featured[currentStoryIndex]?.name }}</h3>
                            <p class="text-sm text-gray-300 line-clamp-2">{{ featured[currentStoryIndex]?.description }}</p>
                            <p class="font-bold text-lg mt-1" :style="{ color: primaryColor }">
                                {{ formatPrice(featured[currentStoryIndex]?.price) }}
                            </p>
                        </div>

                        <!-- Navigation areas (tap left=prev, tap right=next) -->
                        <div class="absolute inset-0 flex z-[5]">
                            <div class="w-1/3 h-full cursor-pointer" @click="prevStory"></div>
                            <div class="w-1/3 h-full"></div>
                            <div class="w-1/3 h-full cursor-pointer" @click="nextStory"></div>
                        </div>
                    </div>
                </div>
            </Transition>
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
