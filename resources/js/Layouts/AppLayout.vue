<script setup>
import { ref, computed } from 'vue';
import { Head, Link, usePage, router } from '@inertiajs/vue3';
import Icon from '@/Components/Icon.vue';

defineProps({
    title: String,
});

const page = usePage();
const user = computed(() => page.props.auth?.user);
const restaurant = computed(() => page.props.auth?.restaurant);
const sidebarOpen = ref(false);

const isAdmin = computed(() => user.value?.role === 'admin');

const navigation = computed(() => {
    if (isAdmin.value) {
        return [
            { name: 'Dashboard', href: '/admin', icon: 'dashboard' },
            { name: 'Usuários', href: '/admin/users', icon: 'user' },
            { name: 'Restaurantes', href: '/admin/restaurants', icon: 'restaurant' },
            { name: 'Planos', href: '/admin/plans', icon: 'dollar' },
            { name: 'Minha Conta', href: '/account', icon: 'user' },
        ];
    }
    return [
        { name: 'Dashboard', href: '/dashboard', icon: 'dashboard' },
        { name: 'Cardapio', href: '/menu-items', icon: 'menu' },
        { name: 'Categorias', href: '/categories', icon: 'tag' },
        { name: 'Stories', href: '/stories', icon: 'star' },
        { name: 'QR Codes', href: '/qr-codes', icon: 'qrcode' },
        { name: 'Metricas', href: '/analytics', icon: 'chart' },
        { name: 'Configuracoes', href: '/settings', icon: 'settings' },
        { name: 'Minha Conta', href: '/account', icon: 'user' },
        { name: 'Planos', href: '/plans', icon: 'dollar' },
    ];
});

const isActive = (href) => {
    return page.url.startsWith(href);
};

const logout = () => {
    router.post('/logout');
};

const userInitials = computed(() => {
    const name = user.value?.name || '';
    const parts = name.trim().split(/\s+/);
    if (parts.length >= 2) {
        return (parts[0].charAt(0) + parts[parts.length - 1].charAt(0)).toUpperCase();
    }
    return name.charAt(0).toUpperCase();
});
</script>

<template>
    <div class="min-h-screen bg-dark-950">
        <Head :title="title" />

        <!-- Mobile sidebar overlay -->
        <Transition
            enter-active-class="transition-opacity duration-300 ease-out"
            enter-from-class="opacity-0"
            enter-to-class="opacity-100"
            leave-active-class="transition-opacity duration-200 ease-in"
            leave-from-class="opacity-100"
            leave-to-class="opacity-0"
        >
            <div v-if="sidebarOpen" class="fixed inset-0 z-40 bg-black/60 lg:hidden" @click="sidebarOpen = false"></div>
        </Transition>

        <Transition
            enter-active-class="transition-transform duration-300 ease-out"
            enter-from-class="-translate-x-full"
            enter-to-class="translate-x-0"
            leave-active-class="transition-transform duration-200 ease-in"
            leave-from-class="translate-x-0"
            leave-to-class="-translate-x-full"
        >
            <div v-if="sidebarOpen" class="fixed inset-y-0 left-0 z-50 w-72 lg:hidden">
                <div class="flex flex-col h-full bg-dark-900 border-r border-dark-700/60 shadow-2xl">
                    <!-- Mobile sidebar header -->
                    <div class="flex items-center justify-between px-6 py-5 border-b border-dark-700/40">
                        <h1 class="text-2xl font-display font-black tracking-tight">
                            <span class="text-brand-500">Clica</span><span class="text-white">Food</span>
                        </h1>
                        <button
                            @click="sidebarOpen = false"
                            class="p-1.5 rounded-lg text-dark-400 hover:text-white hover:bg-dark-800 transition-colors duration-150"
                        >
                            <Icon name="close" class="w-5 h-5" />
                        </button>
                    </div>

                    <!-- Restaurant name for owners -->
                    <div v-if="restaurant && !isAdmin" class="px-6 py-3 border-b border-dark-700/40">
                        <div class="flex items-center gap-2">
                            <Icon name="restaurant" class="w-4 h-4 text-brand-500/70 flex-shrink-0" />
                            <p class="text-dark-400 text-xs font-medium truncate">{{ restaurant.name }}</p>
                        </div>
                    </div>

                    <!-- Mobile navigation -->
                    <nav class="flex-1 px-4 py-4 space-y-1 overflow-y-auto">
                        <Link
                            v-for="item in navigation"
                            :key="item.name"
                            :href="item.href"
                            :class="isActive(item.href) ? 'sidebar-link-active' : 'sidebar-link'"
                            @click="sidebarOpen = false"
                        >
                            <Icon :name="item.icon" class="w-5 h-5 flex-shrink-0" />
                            <span>{{ item.name }}</span>
                        </Link>
                    </nav>

                    <!-- Mobile user section -->
                    <div class="border-t border-dark-700/40 p-4">
                        <div class="flex items-center gap-3 px-2 mb-3">
                            <div class="w-9 h-9 bg-brand-500/15 rounded-full flex items-center justify-center ring-1 ring-brand-500/20">
                                <span class="text-brand-500 font-bold text-xs">{{ userInitials }}</span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-white truncate">{{ user?.name }}</p>
                                <p class="text-xs text-dark-400 truncate">{{ user?.email }}</p>
                            </div>
                        </div>
                        <button
                            @click="logout"
                            class="flex items-center gap-2 w-full px-3 py-2 rounded-lg text-sm text-dark-400 hover:text-red-400 hover:bg-red-500/5 transition-all duration-150"
                        >
                            <Icon name="logout" class="w-4 h-4" />
                            <span>Sair</span>
                        </button>
                    </div>
                </div>
            </div>
        </Transition>

        <!-- Desktop sidebar -->
        <aside class="hidden lg:fixed lg:inset-y-0 lg:flex lg:w-72 lg:flex-col">
            <div class="flex flex-col h-full bg-dark-900 border-r border-dark-700/60">
                <!-- Logo -->
                <div class="px-6 py-6 border-b border-dark-700/40">
                    <h1 class="text-2xl font-display font-black tracking-tight">
                        <span class="text-brand-500">Clica</span><span class="text-white">Food</span>
                    </h1>
                    <div v-if="restaurant && !isAdmin" class="flex items-center gap-2 mt-2">
                        <Icon name="restaurant" class="w-4 h-4 text-brand-500/70 flex-shrink-0" />
                        <p class="text-dark-400 text-xs font-medium truncate">{{ restaurant.name }}</p>
                    </div>
                </div>

                <!-- Navigation -->
                <nav class="flex-1 px-4 py-4 space-y-1 overflow-y-auto">
                    <Link
                        v-for="item in navigation"
                        :key="item.name"
                        :href="item.href"
                        :class="isActive(item.href) ? 'sidebar-link-active' : 'sidebar-link'"
                    >
                        <Icon :name="item.icon" class="w-5 h-5 flex-shrink-0" />
                        <span>{{ item.name }}</span>
                    </Link>
                </nav>

                <!-- User info -->
                <div class="border-t border-dark-700/40 p-4">
                    <div class="flex items-center gap-3 px-2 mb-3">
                        <div class="w-10 h-10 bg-brand-500/15 rounded-full flex items-center justify-center ring-1 ring-brand-500/20">
                            <span class="text-brand-500 font-bold text-sm">{{ userInitials }}</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-white truncate">{{ user?.name }}</p>
                            <p class="text-xs text-dark-400 truncate">{{ user?.email }}</p>
                        </div>
                    </div>
                    <button
                        @click="logout"
                        class="flex items-center gap-2 w-full px-3 py-2 rounded-lg text-sm text-dark-400 hover:text-red-400 hover:bg-red-500/5 transition-all duration-150"
                    >
                        <Icon name="logout" class="w-4 h-4" />
                        <span>Sair</span>
                    </button>
                </div>
            </div>
        </aside>

        <!-- Main content -->
        <div class="lg:pl-72">
            <!-- Top bar mobile -->
            <div class="sticky top-0 z-30 bg-dark-950/80 backdrop-blur-xl border-b border-dark-800/60 px-4 py-3 lg:hidden">
                <div class="flex items-center justify-between">
                    <button
                        @click="sidebarOpen = true"
                        class="p-1.5 rounded-lg text-dark-400 hover:text-white hover:bg-dark-800 transition-colors duration-150"
                    >
                        <Icon name="bars" class="w-6 h-6" />
                    </button>
                    <h1 class="text-lg font-display font-bold tracking-tight">
                        <span class="text-brand-500">Clica</span><span class="text-white">Food</span>
                    </h1>
                    <div class="w-9"></div>
                </div>
            </div>

            <!-- Page content -->
            <main class="p-4 lg:p-8">
                <slot />
            </main>
        </div>
    </div>
</template>
