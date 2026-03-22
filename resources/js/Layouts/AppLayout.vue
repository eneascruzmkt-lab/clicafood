<script setup>
import { ref, computed } from 'vue';
import { Head, Link, usePage, router } from '@inertiajs/vue3';

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
            { name: 'Dashboard', href: '/admin' },
            { name: 'Restaurantes', href: '/admin/restaurants' },
            { name: 'Planos', href: '/admin/plans' },
            { name: 'Minha Conta', href: '/account' },
        ];
    }
    return [
        { name: 'Dashboard', href: '/dashboard' },
        { name: 'Cardápio', href: '/menu-items' },
        { name: 'Categorias', href: '/categories' },
        { name: 'QR Codes', href: '/qr-codes' },
        { name: 'Métricas', href: '/analytics' },
        { name: 'Configurações', href: '/settings' },
        { name: 'Minha Conta', href: '/account' },
    ];
});

const isActive = (href) => {
    return page.url.startsWith(href);
};

const logout = () => {
    router.post('/logout');
};
</script>

<template>
    <div class="min-h-screen bg-dark-950">
        <Head :title="title" />

        <!-- Mobile sidebar overlay -->
        <div v-if="sidebarOpen" class="fixed inset-0 z-40 lg:hidden">
            <div class="fixed inset-0 bg-black/60" @click="sidebarOpen = false"></div>
            <div class="fixed inset-y-0 left-0 w-72 bg-dark-900 border-r border-dark-700 p-6 overflow-y-auto">
                <div class="flex items-center justify-between mb-8">
                    <h1 class="text-2xl font-display font-black">
                        <span class="text-brand-500">Clica</span><span class="text-white">Food</span>
                    </h1>
                    <button @click="sidebarOpen = false" class="text-dark-400 hover:text-white">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
                <nav class="space-y-1">
                    <Link v-for="item in navigation" :key="item.name" :href="item.href"
                          :class="isActive(item.href) ? 'sidebar-link-active' : 'sidebar-link'"
                          @click="sidebarOpen = false">
                        <span>{{ item.name }}</span>
                    </Link>
                </nav>
            </div>
        </div>

        <!-- Desktop sidebar -->
        <aside class="hidden lg:fixed lg:inset-y-0 lg:flex lg:w-72 lg:flex-col">
            <div class="flex flex-col flex-1 bg-dark-900 border-r border-dark-700 p-6">
                <!-- Logo -->
                <div class="mb-8">
                    <h1 class="text-2xl font-display font-black">
                        <span class="text-brand-500">Clica</span><span class="text-white">Food</span>
                    </h1>
                    <p v-if="restaurant" class="text-dark-400 text-xs mt-1 truncate">{{ restaurant.name }}</p>
                </div>

                <!-- Navigation -->
                <nav class="flex-1 space-y-1">
                    <Link v-for="item in navigation" :key="item.name" :href="item.href"
                          :class="isActive(item.href) ? 'sidebar-link-active' : 'sidebar-link'">
                        <span>{{ item.name }}</span>
                    </Link>
                </nav>

                <!-- User info -->
                <div class="border-t border-dark-700 pt-4 mt-4">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-10 h-10 bg-brand-500/20 rounded-full flex items-center justify-center">
                            <span class="text-brand-500 font-bold text-sm">{{ user?.name?.charAt(0) }}</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-white truncate">{{ user?.name }}</p>
                            <p class="text-xs text-dark-400 truncate">{{ user?.email }}</p>
                        </div>
                    </div>
                    <button @click="logout"
                            class="w-full text-left text-sm text-dark-400 hover:text-red-400 transition-colors px-2 py-1">
                        Sair
                    </button>
                </div>
            </div>
        </aside>

        <!-- Main content -->
        <div class="lg:pl-72">
            <!-- Top bar mobile -->
            <div class="sticky top-0 z-30 bg-dark-950/80 backdrop-blur-lg border-b border-dark-800 px-4 py-3 lg:hidden">
                <div class="flex items-center justify-between">
                    <button @click="sidebarOpen = true" class="text-dark-400 hover:text-white">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>
                    <h1 class="text-lg font-display font-bold">
                        <span class="text-brand-500">Clica</span><span class="text-white">Food</span>
                    </h1>
                    <div class="w-6"></div>
                </div>
            </div>

            <!-- Page content -->
            <main class="p-4 lg:p-8">
                <slot />
            </main>
        </div>
    </div>
</template>
