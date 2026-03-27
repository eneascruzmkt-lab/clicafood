<script setup>
import { Head } from '@inertiajs/vue3';

defineProps({
    title: String,
});
</script>

<template>
    <div class="min-h-screen bg-dark-950 flex items-center justify-center p-4 relative overflow-hidden">
        <Head :title="title" />

        <!-- Geometric background pattern (CSS only) -->
        <div class="absolute inset-0 overflow-hidden">
            <!-- Subtle grid -->
            <div class="absolute inset-0 opacity-[0.03]" style="
                background-image:
                    linear-gradient(rgba(255, 255, 255, 0.1) 1px, transparent 1px),
                    linear-gradient(90deg, rgba(255, 255, 255, 0.1) 1px, transparent 1px);
                background-size: 60px 60px;
            "></div>
            <!-- Diagonal accent lines -->
            <div class="absolute inset-0 opacity-[0.02]" style="
                background-image:
                    repeating-linear-gradient(
                        45deg,
                        transparent,
                        transparent 80px,
                        rgba(230, 59, 46, 0.3) 80px,
                        rgba(230, 59, 46, 0.3) 81px
                    );
            "></div>
            <!-- Radial glow from center -->
            <div class="absolute inset-0" style="
                background: radial-gradient(ellipse at 50% 40%, rgba(230, 59, 46, 0.06) 0%, transparent 70%);
            "></div>
        </div>

        <div class="w-full max-w-md relative z-10">
            <!-- Logo -->
            <div class="text-center mb-8">
                <a href="/" class="inline-block">
                    <img src="/images/logo-clicafood.png" alt="ClicaFood" class="h-10 mx-auto" />
                </a>
                <p class="text-dark-400 mt-2.5 text-sm font-medium tracking-wide">Cardápio digital com vídeo</p>
            </div>

            <!-- Card -->
            <div class="relative">
                <!-- Glass border effect -->
                <div class="absolute -inset-px rounded-2xl bg-gradient-to-b from-dark-600/50 via-dark-700/20 to-dark-700/30"></div>
                <div class="relative bg-dark-900 rounded-2xl p-8 shadow-2xl shadow-black/40">
                    <slot />
                </div>
            </div>

            <!-- Footer text -->
            <p class="text-center text-dark-500 text-xs mt-8">
                ClicaFood - Todos os direitos reservados
            </p>
        </div>
    </div>
</template>
