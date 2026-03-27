<script setup>
import { ref, computed, onMounted, onUnmounted, nextTick, watch } from 'vue';
import { Head } from '@inertiajs/vue3';
import Icon from '@/Components/Icon.vue';
import axios from 'axios';

const props = defineProps({
    restaurant: Object,
    categories: Array,
    items: Array,
    stories: Array,
});

const activeCategory = ref(null);

// ====== REELS MODE ======
const showReels = ref(false);
const reelsCategoryIndex = ref(0);
const reelsVisibleIndex = ref(0);
const reelsContainerRef = ref(null);
const videoRefs = ref({});

const expandedDescriptionId = ref(null);

const toggleDescription = (itemId) => {
    expandedDescriptionId.value = expandedDescriptionId.value === itemId ? null : itemId;
};

const reelsCategories = computed(() => [{ id: null, name: 'Todos' }, ...props.categories]);

const reelsItems = computed(() => {
    const cat = reelsCategories.value[reelsCategoryIndex.value];
    if (!cat || cat.id === null) return props.items;
    return props.items.filter(item => item.category_id === cat.id);
});

const currentReelsItem = computed(() => reelsItems.value[reelsVisibleIndex.value] || null);

// Set video ref for each item
const setVideoRef = (el, id) => {
    if (el) videoRefs.value[id] = el;
    else delete videoRefs.value[id];
};

// Detect which slide is visible via IntersectionObserver
let observer = null;
const setupObserver = () => {
    if (observer) observer.disconnect();
    observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            const idx = parseInt(entry.target.dataset.index);
            const itemId = parseInt(entry.target.dataset.itemId);
            if (entry.isIntersecting && entry.intersectionRatio > 0.6) {
                reelsVisibleIndex.value = idx;
                expandedDescriptionId.value = null;
                // Play this video
                const video = videoRefs.value[itemId];
                if (video) {
                    video.currentTime = 0;
                    video.play().catch(() => {});
                    trackEvent('video_play', itemId);
                }
            } else {
                // Pause and reset to first frame
                const video = videoRefs.value[itemId];
                if (video) {
                    video.pause();
                    video.currentTime = 0;
                }
            }
        });
    }, { threshold: [0.6], root: null });
};

const observeSlides = () => {
    nextTick(() => {
        if (!reelsContainerRef.value) return;
        if (observer) observer.disconnect();
        setupObserver();
        reelsContainerRef.value.querySelectorAll('.reels-slide').forEach(el => {
            observer.observe(el);
        });
    });
};

const openReels = (item) => {
    const catIdx = reelsCategories.value.findIndex(c => c.id === (activeCategory.value ?? null));
    reelsCategoryIndex.value = catIdx >= 0 ? catIdx : 0;
    const items = reelsCategoryIndex.value === 0 ? props.items : props.items.filter(i => i.category_id === reelsCategories.value[reelsCategoryIndex.value].id);
    const idx = items.findIndex(i => i.id === item.id);
    reelsVisibleIndex.value = idx >= 0 ? idx : 0;
    showReels.value = true;
    trackEvent('click', item.id);
    document.body.style.overflow = 'hidden';

    nextTick(() => {
        // Scroll to the correct item
        const container = reelsContainerRef.value;
        if (container) {
            const targetSlide = container.querySelector(`[data-index="${reelsVisibleIndex.value}"]`);
            if (targetSlide) targetSlide.scrollIntoView({ behavior: 'instant' });
        }
        observeSlides();
    });
};

const closeReels = () => {
    // Pause all
    Object.values(videoRefs.value).forEach(v => { v.pause(); v.currentTime = 0; });
    showReels.value = false;
    document.body.style.overflow = '';
};

// Horizontal swipe for category change
const hTouchStartX = ref(0);
const hTouchDeltaX = ref(0);
const hSwiping = ref(false);

const onHorizontalTouchStart = (e) => {
    hTouchStartX.value = e.touches[0].clientX;
    hTouchDeltaX.value = 0;
    hSwiping.value = true;
};
const onHorizontalTouchMove = (e) => {
    if (!hSwiping.value) return;
    hTouchDeltaX.value = e.touches[0].clientX - hTouchStartX.value;
};
const onHorizontalTouchEnd = () => {
    if (!hSwiping.value) return;
    hSwiping.value = false;
    const THRESHOLD = 80;
    if (hTouchDeltaX.value < -THRESHOLD && reelsCategoryIndex.value < reelsCategories.value.length - 1) {
        reelsCategoryIndex.value++;
        reelsVisibleIndex.value = 0;
        nextTick(() => {
            if (reelsContainerRef.value) reelsContainerRef.value.scrollTop = 0;
            observeSlides();
        });
    } else if (hTouchDeltaX.value > THRESHOLD && reelsCategoryIndex.value > 0) {
        reelsCategoryIndex.value--;
        reelsVisibleIndex.value = 0;
        nextTick(() => {
            if (reelsContainerRef.value) reelsContainerRef.value.scrollTop = 0;
            observeSlides();
        });
    }
    hTouchDeltaX.value = 0;
};

// Keyboard
const onReelsKeydown = (e) => {
    if (!showReels.value) return;
    const container = reelsContainerRef.value;
    if (!container) return;
    if (e.key === 'ArrowDown') {
        e.preventDefault();
        const next = container.querySelector(`[data-index="${reelsVisibleIndex.value + 1}"]`);
        if (next) next.scrollIntoView({ behavior: 'smooth' });
    } else if (e.key === 'ArrowUp') {
        e.preventDefault();
        const prev = container.querySelector(`[data-index="${reelsVisibleIndex.value - 1}"]`);
        if (prev) prev.scrollIntoView({ behavior: 'smooth' });
    } else if (e.key === 'ArrowRight' && reelsCategoryIndex.value < reelsCategories.value.length - 1) {
        reelsCategoryIndex.value++; reelsVisibleIndex.value = 0;
        nextTick(() => { container.scrollTop = 0; observeSlides(); });
    } else if (e.key === 'ArrowLeft' && reelsCategoryIndex.value > 0) {
        reelsCategoryIndex.value--; reelsVisibleIndex.value = 0;
        nextTick(() => { container.scrollTop = 0; observeSlides(); });
    } else if (e.key === 'Escape') closeReels();
};

// Toggle mute
const toggleMute = () => {
    const item = currentReelsItem.value;
    if (!item) return;
    const video = videoRefs.value[item.id];
    if (video) video.muted = !video.muted;
};

// ====== STORIES ======
// ====== STORIES ======
const showStory = ref(false);
const currentStoryIndex = ref(0);
const storyProgress = ref(0);
let storyTimer = null;
const STORY_DURATION = 5000;

const startStoryTimer = () => {
    clearInterval(storyTimer); storyProgress.value = 0;
    const step = 100 / (STORY_DURATION / 50);
    storyTimer = setInterval(() => { storyProgress.value += step; if (storyProgress.value >= 100) nextStory(); }, 50);
};
const openStory = (index) => {
    const story = props.stories[index];
    // If it's a featured menu item, open in reels mode instead
    if (story?.type === 'item' && story.item_id) {
        const item = props.items.find(i => i.id === story.item_id);
        if (item) { openReels(item); return; }
    }
    currentStoryIndex.value = index; showStory.value = true; startStoryTimer();
};
const navigateStory = (newIndex) => {
    if (newIndex < 0 || newIndex >= props.stories.length) { closeStory(); return; }
    const story = props.stories[newIndex];
    // If navigating to a featured item story, switch to reels
    if (story?.type === 'item' && story.item_id) {
        closeStory();
        const item = props.items.find(i => i.id === story.item_id);
        if (item) { openReels(item); }
        return;
    }
    currentStoryIndex.value = newIndex; startStoryTimer();
};
const nextStory = () => navigateStory(currentStoryIndex.value + 1);
const prevStory = () => navigateStory(currentStoryIndex.value - 1);
const closeStory = () => { showStory.value = false; clearInterval(storyTimer); storyProgress.value = 0; };

// ====== UTILS ======
const filteredItems = computed(() => {
    if (!activeCategory.value) return props.items;
    return props.items.filter(item => item.category_id === activeCategory.value);
});

const formatPrice = (price) => new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' }).format(price);

const trackEvent = (eventType, menuItemId) => {
    if (!menuItemId) return;
    axios.post(`/${props.restaurant.slug}/track`, { event_type: eventType, menu_item_id: menuItemId }).catch(() => {});
};

const getVideoUrl = (item) => {
    if (!item?.video_url) return null;
    // URLs are already resolved by the backend (full https:// URLs)
    if (item.video_url.startsWith('http')) return item.video_url;
    // Fallback for legacy local paths
    return '/storage/' + item.video_url;
};

const getImageUrl = (path) => {
    if (!path) return null;
    if (path.startsWith('http')) return path;
    return '/storage/' + path;
};

// Likes
const likedItems = ref(new Set(JSON.parse(localStorage.getItem(`likes_${props.restaurant.slug}`) || '[]')));
const likeCounts = ref({});

// Initialize like counts from items
props.items.forEach(item => { likeCounts.value[item.id] = item.likes_count || 0; });

const isLiked = (itemId) => likedItems.value.has(itemId);

const likeItem = (itemId) => {
    if (isLiked(itemId)) return;
    likedItems.value.add(itemId);
    likeCounts.value[itemId] = (likeCounts.value[itemId] || 0) + 1;
    localStorage.setItem(`likes_${props.restaurant.slug}`, JSON.stringify([...likedItems.value]));
    axios.post(`/${props.restaurant.slug}/like`, { menu_item_id: itemId }).catch(() => {});
};

const formatLikes = (count) => {
    if (!count) return '0';
    if (count >= 1000) return (count / 1000).toFixed(1).replace('.0', '') + 'k';
    return String(count);
};

const primaryColor = computed(() => props.restaurant.primary_color || '#E63B2E');
const secondaryColor = computed(() => props.restaurant.secondary_color || '#0d0d0d');
const textColor = computed(() => props.restaurant.text_color || '#ffffff');
const textSecColor = computed(() => props.restaurant.text_secondary_color || '#9ca3af');
const borderColor = computed(() => props.restaurant.border_color || '#333333');
const priceColor = computed(() => props.restaurant.price_color || primaryColor.value);
const menuLayout = computed(() => props.restaurant.menu_layout || 'reels');
const selectedCategory = ref(null);
const categoryItems = computed(() => {
    if (!selectedCategory.value) return [];
    return props.items.filter(item => item.category_id === selectedCategory.value);
});
const featuredItems = computed(() => props.items.filter(item => item.featured));
const fontFamily = computed(() => `'${props.restaurant.font_family || 'Inter'}', sans-serif`);
const bgImage = computed(() => props.restaurant.background_image || null);
const bgOpacity = computed(() => (props.restaurant.background_opacity ?? 100) / 100);

const headerCompact = ref(false);
const handleScroll = () => { headerCompact.value = window.scrollY > 120; };

onMounted(() => {
    window.addEventListener('scroll', handleScroll, { passive: true });
    window.addEventListener('keydown', onReelsKeydown);
});

onUnmounted(() => {
    clearInterval(storyTimer);
    if (observer) observer.disconnect();
    window.removeEventListener('scroll', handleScroll);
    window.removeEventListener('keydown', onReelsKeydown);
    document.body.style.overflow = '';
});
</script>

<template>
    <div class="min-h-screen relative" :style="{ backgroundColor: secondaryColor, fontFamily: fontFamily }">
        <!-- Background image -->
        <div v-if="bgImage" class="fixed inset-0 z-0 pointer-events-none">
            <img :src="bgImage" class="w-full h-full object-cover" :style="{ opacity: bgOpacity }" />
        </div>
        <div class="relative z-10">
        <Head :title="restaurant.name + ' - Cardapio Digital'">
            <link rel="preconnect" href="https://fonts.googleapis.com" />
            <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
            <link :href="`https://fonts.googleapis.com/css2?family=${encodeURIComponent(restaurant.font_family || 'Inter')}:wght@400;500;600;700&display=swap`" rel="stylesheet" />
        </Head>

        <!-- Sticky Header -->
        <header class="sticky top-0 z-20 transition-all duration-300"
                :class="headerCompact ? 'translate-y-0 opacity-100' : '-translate-y-full opacity-0 pointer-events-none'"
                :style="{ backgroundColor: secondaryColor + 'f2', borderBottom: '1px solid rgba(255,255,255,0.06)' }">
            <div class="max-w-lg mx-auto px-4 py-2.5 flex items-center gap-2.5 backdrop-blur-xl">
                <div v-if="restaurant.logo" class="w-8 h-8 rounded-full overflow-hidden flex-shrink-0">
                    <img :src="getImageUrl(restaurant.logo)" :alt="restaurant.name" class="w-full h-full object-cover" />
                </div>
                <div v-else class="w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0"
                     :style="{ backgroundColor: primaryColor + '15' }">
                    <Icon name="restaurant" class="w-4 h-4" :style="{ color: primaryColor }" />
                </div>
                <h1 class="font-display font-bold text-sm leading-tight flex-1 truncate" :style="{ color: textColor }">{{ restaurant.name }}</h1>
            </div>
        </header>

        <!-- Hero -->
        <div class="max-w-lg mx-auto px-4">
            <div class="pt-6 pb-5 text-center">
                <div class="flex justify-center mb-4">
                    <div v-if="restaurant.logo" class="w-20 h-20 rounded-2xl overflow-hidden">
                        <img :src="getImageUrl(restaurant.logo)" :alt="restaurant.name" class="w-full h-full object-cover" />
                    </div>
                    <div v-else class="w-20 h-20 rounded-2xl flex items-center justify-center"
                         :style="{ backgroundColor: primaryColor + '12' }">
                        <Icon name="restaurant" class="w-9 h-9" :style="{ color: primaryColor }" />
                    </div>
                </div>
                <h1 class="font-display font-bold text-xl leading-tight" :style="{ color: textColor }">{{ restaurant.name }}</h1>
                <p v-if="restaurant.description" class="text-sm mt-1.5 max-w-xs mx-auto leading-relaxed" :style="{ color: textSecColor }">{{ restaurant.description }}</p>
                <div class="flex items-center justify-center gap-3 mt-4">
                    <a v-if="restaurant.instagram && restaurant.show_instagram" :href="`https://instagram.com/${restaurant.instagram.replace('@','')}`" target="_blank" rel="noopener"
                       class="flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-medium"
                       :style="{ backgroundColor: primaryColor + '12', color: primaryColor }">
                        <Icon name="instagram" class="w-3.5 h-3.5" /><span>Instagram</span>
                    </a>
                    <a v-if="restaurant.whatsapp && restaurant.show_whatsapp" :href="`https://wa.me/${restaurant.whatsapp}`" target="_blank" rel="noopener"
                       class="flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-medium"
                       :style="{ backgroundColor: primaryColor + '12', color: primaryColor }">
                        <Icon name="phone" class="w-3.5 h-3.5" /><span>WhatsApp</span>
                    </a>
                </div>
                <div v-if="restaurant.address && restaurant.show_address" class="flex items-center justify-center gap-1.5 mt-3 text-xs" :style="{ color: textSecColor }">
                    <Icon name="map-pin" class="w-3 h-3 flex-shrink-0" />
                    <span class="truncate max-w-[260px]">{{ restaurant.address }}</span>
                </div>
            </div>
            <div class="h-px w-12 mx-auto rounded-full" :style="{ backgroundColor: primaryColor + '20' }"></div>
        </div>

        <div class="max-w-lg mx-auto px-4 pb-8">

            <!-- ====== LAYOUT: REELS (padrão) ====== -->
            <template v-if="menuLayout === 'reels'">
                <!-- Stories -->
                <div v-if="stories.length > 0" class="py-4 -mx-4 px-4 overflow-x-auto scrollbar-hide">
                    <div class="flex gap-3">
                        <button v-for="(story, index) in stories" :key="story.id" @click="openStory(index)" class="flex-shrink-0 text-center group">
                            <div class="w-16 h-16 rounded-full p-0.5 mb-1" :style="{ background: `linear-gradient(135deg, ${primaryColor}, #ff8c00)` }">
                                <div class="w-full h-full rounded-full overflow-hidden border-2" :style="{ borderColor: secondaryColor }">
                                    <img :src="story.image" :alt="story.title || 'Story'" class="w-full h-full object-cover" />
                                </div>
                            </div>
                            <p class="text-[10px] text-gray-400 w-16 truncate">{{ story.title || '' }}</p>
                        </button>
                    </div>
                </div>

                <!-- Categories -->
                <div class="flex gap-2 py-3 overflow-x-auto -mx-4 px-4 scrollbar-hide">
                    <button @click="activeCategory = null"
                            :style="!activeCategory ? { backgroundColor: primaryColor, color: '#fff' } : { backgroundColor: borderColor, color: textSecColor }"
                            class="flex-shrink-0 px-4 py-2 rounded-full text-sm font-medium transition-colors">Todos</button>
                    <button v-for="cat in categories" :key="cat.id" @click="activeCategory = cat.id"
                            :style="activeCategory === cat.id ? { backgroundColor: primaryColor, color: '#fff' } : { backgroundColor: borderColor, color: textSecColor }"
                            class="flex-shrink-0 px-4 py-2 rounded-full text-sm font-medium transition-colors">{{ cat.name }}</button>
                </div>

                <!-- Menu Items -->
                <div class="space-y-3 mt-4">
                    <div v-for="item in filteredItems" :key="item.id" @click="openReels(item)"
                         class="flex gap-3 p-3 rounded-xl hover:bg-white/5 cursor-pointer"
                         :style="{ backgroundColor: borderColor + '30', border: `1px solid ${borderColor}40` }">
                        <div class="w-20 h-20 rounded-lg overflow-hidden flex-shrink-0 relative">
                            <img v-if="item.image" :src="getImageUrl(item.image)" :alt="item.name" class="w-full h-full object-cover" loading="lazy" />
                            <div v-else class="w-full h-full flex items-center justify-center" style="background-color:#222"><Icon name="image" class="w-8 h-8 text-gray-600" /></div>
                            <div v-if="item.video_url" class="absolute bottom-1 right-1 w-5 h-5 rounded-full flex items-center justify-center" :style="{ backgroundColor: primaryColor }">
                                <Icon name="play" class="w-2.5 h-2.5 text-white ml-px" />
                            </div>
                        </div>
                        <div class="flex-1 min-w-0 py-1">
                            <h3 class="font-semibold text-sm" :style="{ color: textColor }">{{ item.name }}</h3>
                            <p v-if="item.description" class="text-xs mt-0.5 line-clamp-2" :style="{ color: textSecColor }">{{ item.description }}</p>
                            <p class="font-bold mt-1.5 text-sm" :style="{ color: priceColor }">{{ formatPrice(item.price) }}</p>
                        </div>
                    </div>
                    <p v-if="filteredItems.length === 0" class="text-center text-gray-500 py-8 text-sm">Nenhum item nesta categoria</p>
                </div>
            </template>

            <!-- ====== LAYOUT: CATEGORIES (apenas categorias com ícones grandes) ====== -->
            <template v-else-if="menuLayout === 'categories'">
                <!-- Se uma categoria está selecionada, mostra os itens dela -->
                <template v-if="selectedCategory">
                    <button @click="selectedCategory = null" class="flex items-center gap-2 py-4 text-sm font-medium" :style="{ color: primaryColor }">
                        <Icon name="chevron-left" class="w-4 h-4" />
                        <span>Voltar às categorias</span>
                    </button>
                    <h2 class="font-display font-bold text-lg mb-4" :style="{ color: textColor }">
                        {{ categories.find(c => c.id === selectedCategory)?.name }}
                    </h2>
                    <div class="space-y-3">
                        <div v-for="item in categoryItems" :key="item.id" @click="openReels(item)"
                             class="flex gap-3 p-3 rounded-xl hover:bg-white/5 cursor-pointer"
                             :style="{ backgroundColor: borderColor + '30', border: `1px solid ${borderColor}40` }">
                            <div class="w-20 h-20 rounded-lg overflow-hidden flex-shrink-0 relative">
                                <img v-if="item.image" :src="getImageUrl(item.image)" :alt="item.name" class="w-full h-full object-cover" loading="lazy" />
                                <div v-else class="w-full h-full flex items-center justify-center" style="background-color:#222"><Icon name="image" class="w-8 h-8 text-gray-600" /></div>
                                <div v-if="item.video_url" class="absolute bottom-1 right-1 w-5 h-5 rounded-full flex items-center justify-center" :style="{ backgroundColor: primaryColor }">
                                    <Icon name="play" class="w-2.5 h-2.5 text-white ml-px" />
                                </div>
                            </div>
                            <div class="flex-1 min-w-0 py-1">
                                <h3 class="font-semibold text-sm" :style="{ color: textColor }">{{ item.name }}</h3>
                                <p v-if="item.description" class="text-xs mt-0.5 line-clamp-2" :style="{ color: textSecColor }">{{ item.description }}</p>
                                <p class="font-bold mt-1.5 text-sm" :style="{ color: priceColor }">{{ formatPrice(item.price) }}</p>
                            </div>
                        </div>
                        <p v-if="categoryItems.length === 0" class="text-center text-gray-500 py-8 text-sm">Nenhum item nesta categoria</p>
                    </div>
                </template>

                <!-- Grid de categorias grandes -->
                <template v-else>
                    <div class="grid grid-cols-2 gap-3 mt-6">
                        <button v-for="cat in categories" :key="cat.id" @click="selectedCategory = cat.id"
                                class="relative rounded-2xl overflow-hidden aspect-square group active:scale-[0.97] transition-transform">
                            <img v-if="cat.image" :src="getImageUrl(cat.image)" :alt="cat.name" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" />
                            <div v-else class="w-full h-full flex items-center justify-center" :style="{ backgroundColor: borderColor + '60' }">
                                <Icon name="restaurant" class="w-12 h-12" :style="{ color: textSecColor }" />
                            </div>
                            <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent"></div>
                            <div class="absolute bottom-0 left-0 right-0 p-4">
                                <h3 class="font-display font-bold text-white text-base drop-shadow-lg">{{ cat.name }}</h3>
                            </div>
                        </button>
                    </div>
                </template>
            </template>

            <!-- ====== LAYOUT: CATEGORIES + STORIES ====== -->
            <template v-else-if="menuLayout === 'categories_featured'">
                <!-- Se uma categoria está selecionada, mostra os itens dela -->
                <template v-if="selectedCategory">
                    <button @click="selectedCategory = null" class="flex items-center gap-2 py-4 text-sm font-medium" :style="{ color: primaryColor }">
                        <Icon name="chevron-left" class="w-4 h-4" />
                        <span>Voltar</span>
                    </button>
                    <h2 class="font-display font-bold text-lg mb-4" :style="{ color: textColor }">
                        {{ categories.find(c => c.id === selectedCategory)?.name }}
                    </h2>
                    <div class="space-y-3">
                        <div v-for="item in categoryItems" :key="item.id" @click="openReels(item)"
                             class="flex gap-3 p-3 rounded-xl hover:bg-white/5 cursor-pointer"
                             :style="{ backgroundColor: borderColor + '30', border: `1px solid ${borderColor}40` }">
                            <div class="w-20 h-20 rounded-lg overflow-hidden flex-shrink-0 relative">
                                <img v-if="item.image" :src="getImageUrl(item.image)" :alt="item.name" class="w-full h-full object-cover" loading="lazy" />
                                <div v-else class="w-full h-full flex items-center justify-center" style="background-color:#222"><Icon name="image" class="w-8 h-8 text-gray-600" /></div>
                                <div v-if="item.video_url" class="absolute bottom-1 right-1 w-5 h-5 rounded-full flex items-center justify-center" :style="{ backgroundColor: primaryColor }">
                                    <Icon name="play" class="w-2.5 h-2.5 text-white ml-px" />
                                </div>
                            </div>
                            <div class="flex-1 min-w-0 py-1">
                                <h3 class="font-semibold text-sm" :style="{ color: textColor }">{{ item.name }}</h3>
                                <p v-if="item.description" class="text-xs mt-0.5 line-clamp-2" :style="{ color: textSecColor }">{{ item.description }}</p>
                                <p class="font-bold mt-1.5 text-sm" :style="{ color: priceColor }">{{ formatPrice(item.price) }}</p>
                            </div>
                        </div>
                        <p v-if="categoryItems.length === 0" class="text-center text-gray-500 py-8 text-sm">Nenhum item nesta categoria</p>
                    </div>
                </template>

                <!-- Stories no topo + grid de categorias -->
                <template v-else>
                    <!-- Stories -->
                    <div v-if="stories.length > 0" class="py-4 -mx-4 px-4 overflow-x-auto scrollbar-hide">
                        <div class="flex gap-3">
                            <button v-for="(story, index) in stories" :key="story.id" @click="openStory(index)" class="flex-shrink-0 text-center group">
                                <div class="w-16 h-16 rounded-full p-0.5 mb-1" :style="{ background: `linear-gradient(135deg, ${primaryColor}, #ff8c00)` }">
                                    <div class="w-full h-full rounded-full overflow-hidden border-2" :style="{ borderColor: secondaryColor }">
                                        <img :src="story.image" :alt="story.title || 'Story'" class="w-full h-full object-cover" />
                                    </div>
                                </div>
                                <p class="text-[10px] text-gray-400 w-16 truncate">{{ story.title || '' }}</p>
                            </button>
                        </div>
                    </div>

                    <!-- Categorias grandes -->
                    <div class="grid grid-cols-2 gap-3 mt-4">
                        <button v-for="cat in categories" :key="cat.id" @click="selectedCategory = cat.id"
                                class="relative rounded-2xl overflow-hidden aspect-square group active:scale-[0.97] transition-transform">
                            <img v-if="cat.image" :src="getImageUrl(cat.image)" :alt="cat.name" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" />
                            <div v-else class="w-full h-full flex items-center justify-center" :style="{ backgroundColor: borderColor + '60' }">
                                <Icon name="restaurant" class="w-12 h-12" :style="{ color: textSecColor }" />
                            </div>
                            <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent"></div>
                            <div class="absolute bottom-0 left-0 right-0 p-4">
                                <h3 class="font-display font-bold text-white text-base drop-shadow-lg">{{ cat.name }}</h3>
                            </div>
                        </button>
                    </div>
                </template>
            </template>

            <div class="mt-8 pt-4 text-center" :style="{ borderTop: `1px solid ${borderColor}40` }">
                <div class="flex items-center justify-center gap-1.5">
                    <span class="text-xs" :style="{ color: textSecColor + '80' }">Powered by</span>
                    <img src="/images/logo-clicafood.png" alt="ClicaFood" class="h-4" />
                </div>
            </div>
        </div>

        </div><!-- /z-10 wrapper -->

        <!-- ====== REELS FULLSCREEN (scroll-snap native) ====== -->
        <teleport to="body">
            <Transition enter-active-class="reels-enter-active" enter-from-class="reels-enter-from" enter-to-class="reels-enter-to"
                        leave-active-class="reels-leave-active" leave-from-class="reels-leave-from" leave-to-class="reels-leave-to">
                <div v-if="showReels" class="fixed inset-0 z-50 bg-black"
                     @touchstart.passive="onHorizontalTouchStart"
                     @touchmove.passive="onHorizontalTouchMove"
                     @touchend.passive="onHorizontalTouchEnd">

                    <!-- Category pills -->
                    <div class="absolute top-0 left-0 right-0 z-30 safe-top">
                        <div class="flex items-center justify-between px-4 pt-3 pb-2">
                            <div class="flex gap-1.5 overflow-x-auto flex-1 mr-3 scrollbar-hide">
                                <button v-for="(cat, ci) in reelsCategories" :key="ci"
                                      @click="reelsCategoryIndex = ci; reelsVisibleIndex = 0; $nextTick(() => { if(reelsContainerRef) reelsContainerRef.scrollTop = 0; observeSlides(); })"
                                      class="flex-shrink-0 px-2.5 py-1 rounded-full text-[11px] font-semibold transition-all duration-300"
                                      :style="{
                                          backgroundColor: ci === reelsCategoryIndex ? primaryColor : 'rgba(255,255,255,0.1)',
                                          color: ci === reelsCategoryIndex ? '#fff' : 'rgba(255,255,255,0.5)'
                                      }">{{ cat.name }}</button>
                            </div>
                            <button @click="closeReels" class="flex-shrink-0 w-8 h-8 rounded-full bg-black/40 backdrop-blur-sm flex items-center justify-center text-white/80">
                                <Icon name="close" class="w-5 h-5" />
                            </button>
                        </div>
                        <div class="flex gap-0.5 px-4 pb-2">
                            <div v-for="(_, i) in reelsItems" :key="i" class="h-[2px] rounded-full transition-all duration-300"
                                 :style="{ backgroundColor: i === reelsVisibleIndex ? primaryColor : 'rgba(255,255,255,0.2)', flex: i === reelsVisibleIndex ? '3' : '1' }"></div>
                        </div>
                    </div>

                    <!-- Scroll-snap container -->
                    <div ref="reelsContainerRef"
                         class="reels-container h-full w-full overflow-y-scroll snap-y snap-mandatory">

                        <div v-for="(item, index) in reelsItems" :key="item.id"
                             class="reels-slide snap-start h-full w-full relative flex-shrink-0"
                             :data-index="index"
                             :data-item-id="item.id">

                            <!-- Background placeholder (always visible, instant) -->
                            <div class="absolute inset-0"
                                 :style="{ background: `linear-gradient(135deg, ${secondaryColor}, ${primaryColor}15)` }">
                                <img v-if="item.video_thumbnail" :src="item.video_thumbnail"
                                     class="w-full h-full object-cover" loading="lazy" />
                                <img v-else-if="item.image" :src="getImageUrl(item.image)"
                                     class="w-full h-full object-cover blur-md opacity-40" loading="lazy" />
                            </div>

                            <!-- Video (paused at frame 0, plays only when visible) -->
                            <div v-if="item.video_url" class="absolute inset-0">
                                <video :ref="(el) => setVideoRef(el, item.id)"
                                       :src="getVideoUrl(item)"
                                       class="h-full w-full object-cover"
                                       loop playsinline muted preload="auto"
                                       @loadeddata="(e) => { e.target.currentTime = 0; }"></video>
                            </div>

                            <!-- Image (no video) -->
                            <div v-else-if="item.image" class="absolute inset-0">
                                <img :src="getImageUrl(item.image)"
                                     class="w-full h-full object-cover"
                                     :class="{ 'reels-image-zoom': index === reelsVisibleIndex }" />
                            </div>

                            <!-- No media fallback -->
                            <div v-else class="absolute inset-0 flex items-center justify-center"
                                 :style="{ background: `linear-gradient(135deg, ${secondaryColor}, ${primaryColor}30)` }">
                                <Icon name="restaurant" class="w-24 h-24 text-white/10" />
                            </div>

                            <!-- Gradient overlay -->
                            <div class="absolute inset-0 bg-gradient-to-b from-black/30 via-transparent to-black/70 pointer-events-none"></div>

                            <!-- Expanded description overlay (covers full screen when open) -->
                            <Transition
                                enter-active-class="transition-opacity duration-200"
                                enter-from-class="opacity-0"
                                enter-to-class="opacity-100"
                                leave-active-class="transition-opacity duration-150"
                                leave-from-class="opacity-100"
                                leave-to-class="opacity-0">
                                <div v-if="expandedDescriptionId === item.id"
                                     class="absolute inset-0 z-20 bg-black/85 backdrop-blur-sm flex flex-col justify-end"
                                     @click.self="toggleDescription(null)">
                                    <div class="px-5 pb-6 pt-4 max-h-[70vh] overflow-y-auto">
                                        <h2 class="font-bold text-white text-2xl leading-tight">{{ item.name }}</h2>
                                        <p class="text-sm text-gray-100 mt-3 leading-relaxed whitespace-pre-line">{{ item.description }}</p>
                                        <div class="flex items-center gap-3 mt-4">
                                            <span class="font-bold text-xl" :style="{ color: primaryColor }">{{ formatPrice(item.price) }}</span>
                                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-medium bg-white/10 text-white/60">{{ item.category?.name }}</span>
                                        </div>
                                        <button @click="toggleDescription(null)"
                                                class="mt-4 text-xs text-white/40 flex items-center gap-1">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/></svg>
                                            Fechar
                                        </button>
                                    </div>
                                </div>
                            </Transition>

                            <!-- Like button (right side, TikTok style) -->
                            <div class="absolute right-4 bottom-56 z-10 flex flex-col items-center gap-1"
                                 :class="{ 'pointer-events-none opacity-0': expandedDescriptionId === item.id }">
                                <button @click.stop="likeItem(item.id)"
                                        class="w-11 h-11 rounded-full bg-black/30 backdrop-blur-sm flex items-center justify-center transition-all duration-300 active:scale-125"
                                        :class="isLiked(item.id) ? 'text-red-500' : 'text-white/70'">
                                    <svg class="w-6 h-6 transition-transform duration-300" :class="{ 'scale-110': isLiked(item.id) }"
                                         :fill="isLiked(item.id) ? 'currentColor' : 'none'" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                    </svg>
                                </button>
                                <span class="text-[11px] font-semibold text-white/70 drop-shadow">{{ formatLikes(likeCounts[item.id]) }}</span>
                            </div>

                            <!-- Bottom info -->
                            <div class="absolute bottom-0 left-0 right-0 z-10 safe-bottom"
                                 :class="{ 'pointer-events-none opacity-0': expandedDescriptionId === item.id }">
                                <div class="bg-gradient-to-t from-black via-black/70 to-transparent pt-16 px-5 pb-6">
                                    <h2 class="font-bold text-white text-2xl leading-tight drop-shadow-lg">{{ item.name }}</h2>
                                    <p v-if="item.description"
                                       @click.stop="toggleDescription(item.id)"
                                       class="text-sm text-gray-200/80 mt-2 leading-relaxed line-clamp-3 cursor-pointer active:text-white">
                                        {{ item.description }}
                                        <span class="text-white/40 text-xs ml-1">...mais</span>
                                    </p>
                                    <div class="flex items-center gap-3 mt-3">
                                        <span class="font-bold text-xl" :style="{ color: primaryColor }">{{ formatPrice(item.price) }}</span>
                                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-medium bg-white/10 text-white/60">{{ item.category?.name }}</span>
                                        <span v-if="item.video_url" class="px-2 py-0.5 rounded-full text-[10px] font-medium flex items-center gap-1"
                                              :style="{ backgroundColor: primaryColor + '30', color: primaryColor }">
                                            <Icon name="play" class="w-2.5 h-2.5" /> Video
                                        </span>
                                    </div>
                                    <div class="flex items-center justify-center gap-4 mt-3 text-[10px] text-white/25">
                                        <span>↕ deslize para mais</span><span>•</span><span>↔ deslize para categorias</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Unmute -->
                    <button v-if="currentReelsItem?.video_url" @click="toggleMute"
                            class="absolute top-20 right-4 z-30 w-9 h-9 rounded-full bg-black/40 backdrop-blur-sm flex items-center justify-center text-white/60 hover:text-white">
                        <Icon name="video" class="w-4 h-4" />
                    </button>

                    <!-- Counter -->
                    <div class="absolute right-4 bottom-48 z-20 pointer-events-none">
                        <span class="text-xs text-white/30 font-mono">{{ reelsVisibleIndex + 1 }}/{{ reelsItems.length }}</span>
                    </div>

                    <!-- Desktop nav -->
                    <div class="hidden sm:flex absolute right-4 top-1/2 -translate-y-1/2 z-20 flex-col gap-2">
                        <button v-if="reelsVisibleIndex > 0"
                                @click="reelsContainerRef?.querySelector(`[data-index='${reelsVisibleIndex - 1}']`)?.scrollIntoView({ behavior: 'smooth' })"
                                class="w-10 h-10 rounded-full bg-black/30 backdrop-blur-sm flex items-center justify-center text-white/60 hover:text-white hover:bg-black/50 transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/></svg>
                        </button>
                        <button v-if="reelsVisibleIndex < reelsItems.length - 1"
                                @click="reelsContainerRef?.querySelector(`[data-index='${reelsVisibleIndex + 1}']`)?.scrollIntoView({ behavior: 'smooth' })"
                                class="w-10 h-10 rounded-full bg-black/30 backdrop-blur-sm flex items-center justify-center text-white/60 hover:text-white hover:bg-black/50 transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                    </div>
                </div>
            </Transition>
        </teleport>

        <!-- Stories Fullscreen -->
        <teleport to="body">
            <Transition enter-active-class="transition-opacity duration-200" enter-from-class="opacity-0" enter-to-class="opacity-100"
                        leave-active-class="transition-opacity duration-150" leave-from-class="opacity-100" leave-to-class="opacity-0">
                <div v-if="showStory && stories.length > 0" class="fixed inset-0 z-50 bg-black flex items-center justify-center">
                    <div class="relative w-full max-w-lg h-full">
                        <!-- Progress bars -->
                        <div class="absolute top-2 left-2 right-2 flex gap-1 z-10">
                            <div v-for="(_, i) in stories" :key="i" class="flex-1 h-0.5 rounded-full bg-white/30 overflow-hidden">
                                <div class="h-full rounded-full bg-white transition-all" :style="{ width: i < currentStoryIndex ? '100%' : (i === currentStoryIndex ? storyProgress + '%' : '0%'), transitionDuration: i === currentStoryIndex ? '50ms' : '300ms' }"></div>
                            </div>
                        </div>
                        <button @click="closeStory" class="absolute top-6 right-4 z-10 text-white/80 hover:text-white"><Icon name="close" class="w-6 h-6" /></button>
                        <!-- Image -->
                        <div class="h-full flex items-center justify-center">
                            <img v-if="stories[currentStoryIndex]?.image"
                                 :key="'si-' + currentStoryIndex"
                                 :src="stories[currentStoryIndex].image"
                                 class="max-h-full max-w-full object-contain" />
                        </div>
                        <!-- Title overlay -->
                        <div v-if="stories[currentStoryIndex]?.title" class="absolute bottom-0 left-0 right-0 p-6 bg-gradient-to-t from-black/80 to-transparent">
                            <h3 class="font-bold text-white text-lg">{{ stories[currentStoryIndex].title }}</h3>
                        </div>
                        <!-- Nav areas -->
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
.scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
.scrollbar-hide::-webkit-scrollbar { display: none; }
.line-clamp-2 { display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
.line-clamp-3 { display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden; }
.safe-top { padding-top: env(safe-area-inset-top, 0px); }
.safe-bottom { padding-bottom: env(safe-area-inset-bottom, 0px); }

/* Scroll-snap reels */
.reels-container {
    scroll-snap-type: y mandatory;
    -webkit-overflow-scrolling: touch;
    scrollbar-width: none;
    -ms-overflow-style: none;
}
.reels-container::-webkit-scrollbar { display: none; }
.reels-slide {
    scroll-snap-align: start;
    scroll-snap-stop: always;
}

.reels-image-zoom { animation: slowZoom 8s ease-in-out infinite alternate; }
@keyframes slowZoom { from { transform: scale(1); } to { transform: scale(1.08); } }

.reels-enter-active { transition: transform 300ms ease-out, opacity 300ms ease-out; }
.reels-enter-from { transform: translateY(100%); opacity: 0; }
.reels-enter-to { transform: translateY(0); opacity: 1; }
.reels-leave-active { transition: transform 250ms ease-in, opacity 250ms ease-in; }
.reels-leave-from { transform: translateY(0); opacity: 1; }
.reels-leave-to { transform: translateY(100%); opacity: 0; }
</style>
