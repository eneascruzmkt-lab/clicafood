<script setup>
import { ref, onMounted } from 'vue';
import { Head } from '@inertiajs/vue3';
</script>

<template>
    <div class="sales-page min-h-screen bg-white antialiased">
        <Head title="ClicaFood - Cardápio Digital com Vídeo e Realidade Aumentada" />

        <!-- Background Glow -->
        <div class="fixed inset-0 pointer-events-none overflow-hidden z-0">
            <div class="absolute -top-[200px] -left-[200px] w-[800px] h-[800px] rounded-full opacity-30"
                 style="background: radial-gradient(circle, #60B1FF 0%, transparent 70%); filter: blur(120px);"></div>
            <div class="absolute top-[100px] left-[100px] w-[600px] h-[600px] rounded-full opacity-20"
                 style="background: radial-gradient(circle, #319AFF 0%, transparent 70%); filter: blur(100px);"></div>
        </div>

        <!-- Navbar (Liquid Glass) -->
        <div class="sticky top-[30px] z-50 flex justify-center px-4">
            <nav class="glass-nav flex items-center gap-1 px-2 py-2 rounded-[16px]"
                 style="backdrop-filter: blur(50px); -webkit-backdrop-filter: blur(50px); background: rgba(255,255,255,0.3); border: 1px solid rgba(0,0,0,0.1); box-shadow: inset 0px 4px 4px 0px rgba(255,255,255,0.25);">
                <!-- Logo -->
                <a href="/" class="flex items-center gap-2 px-4 py-2">
                    <img src="/images/logo-clicafood.png" alt="ClicaFood" class="h-7" />
                </a>

                <!-- Nav Links -->
                <div class="hidden md:flex items-center gap-1">
                    <a href="#features" class="nav-link px-4 py-2 rounded-[12px] text-[14px] font-medium text-gray-600 hover:text-gray-900 hover:bg-white/40 transition-all duration-200">Benefícios</a>
                    <a href="#ar" class="nav-link px-4 py-2 rounded-[12px] text-[14px] font-medium text-gray-600 hover:text-gray-900 hover:bg-white/40 transition-all duration-200">Realidade Aumentada</a>
                    <a href="#pricing" class="nav-link px-4 py-2 rounded-[12px] text-[14px] font-medium text-gray-600 hover:text-gray-900 hover:bg-white/40 transition-all duration-200">Planos</a>
                </div>

                <!-- CTA -->
                <a href="/register" class="glass-signup ml-2 flex items-center gap-2 px-5 py-2.5 rounded-[12px] text-[14px] font-medium text-gray-700 transition-all duration-200 hover:bg-white/50"
                   style="background: rgba(255,255,255,0.4); border: 1px solid rgba(0,0,0,0.08); box-shadow: inset 0px 2px 3px 0px rgba(255,255,255,0.3);">
                    Começar Agora
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12h15m0 0l-6.75-6.75M19.5 12l-6.75 6.75"/></svg>
                </a>
            </nav>
        </div>

        <!-- Hero Section -->
        <section class="relative z-10 max-w-[1600px] mx-auto px-6 pt-16 md:pt-24 pb-20">
            <div class="grid md:grid-cols-2 gap-12 items-center">
                <!-- Hero Left -->
                <div class="max-w-[640px]">
                    <!-- Social Proof Badge -->
                    <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full mb-8"
                         style="background: rgba(255,255,255,0.5); border: 1px solid rgba(0,0,0,0.06); box-shadow: inset 0px 2px 3px rgba(255,255,255,0.3);">
                        <div class="flex gap-0.5">
                            <svg v-for="n in 5" :key="n" class="w-4 h-4 text-[#FF801E]" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                        </div>
                        <span class="text-[13px] font-medium text-gray-600">Avaliado 4.9/5 por <strong class="text-gray-800">2.700+</strong> restaurantes</span>
                    </div>

                    <!-- Headline -->
                    <h1 class="hero-headline text-[42px] sm:text-[56px] md:text-[68px] lg:text-[75px] font-bold leading-[1.05] tracking-[-2px] text-gray-900 mb-6">
                        Seu cardápio em<br/>
                        <span class="text-transparent bg-clip-text" style="background-image: linear-gradient(135deg, #0084FF, #319AFF, #60B1FF);">
                            outro nível.
                        </span>
                    </h1>

                    <!-- Subheadline -->
                    <p class="text-[17px] md:text-[18px] leading-[1.7] tracking-[-0.5px] text-gray-500 mb-10 max-w-[520px]">
                        Transforme fotos dos seus pratos em experiências 3D e vídeos imersivos. Cardápio digital com realidade aumentada, métricas em tempo real e QR Codes inteligentes.
                    </p>

                    <!-- Primary CTA -->
                    <div class="flex flex-col sm:flex-row gap-4 items-start">
                        <a href="/register"
                           class="cta-primary group inline-flex items-center gap-3 px-8 py-4 rounded-[16px] text-white text-[16px] font-semibold transition-all duration-300 hover:scale-[1.02]"
                           style="background: rgba(0,132,255,0.8); backdrop-filter: blur(2px); box-shadow: inset 0px 4px 4px 0px rgba(255,255,255,0.35), 0 8px 32px rgba(0,132,255,0.25);">
                            Começar Agora — Grátis
                            <span class="w-8 h-8 rounded-full bg-white/20 flex items-center justify-center group-hover:bg-white/30 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12h15m0 0l-6.75-6.75M19.5 12l-6.75 6.75"/></svg>
                            </span>
                        </a>
                        <a href="#ar" class="inline-flex items-center gap-2 px-6 py-4 rounded-[16px] text-[15px] font-medium text-gray-600 hover:text-gray-900 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 7.5l-9-5.25L3 7.5m18 0l-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9"/>
                            </svg>
                            Ver demo em AR
                        </a>
                    </div>
                </div>

                <!-- Hero Right (Glassy Orb) -->
                <div class="relative flex justify-center items-center min-h-[400px] md:min-h-[500px]">
                    <div class="absolute inset-0 flex items-center justify-center">
                        <video
                            autoplay
                            loop
                            muted
                            playsinline
                            class="w-full max-w-[600px] scale-125 mix-blend-screen"
                            style="filter: hue-rotate(-55deg) saturate(250%) brightness(1.2) contrast(1.1);"
                            src="https://future.co/images/homepage/glassy-orb/orb-purple.webm"
                        ></video>
                    </div>
                    <!-- Floating elements over orb -->
                    <div class="relative z-10 flex flex-col items-center gap-4">
                        <!-- 3D cube icon -->
                        <div class="glass-float w-16 h-16 rounded-2xl flex items-center justify-center"
                             style="background: rgba(255,255,255,0.25); backdrop-filter: blur(20px); border: 1px solid rgba(255,255,255,0.4); box-shadow: inset 0 2px 4px rgba(255,255,255,0.3), 0 8px 32px rgba(0,0,0,0.08);">
                            <svg class="w-8 h-8 text-[#0084FF]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 7.5l-9-5.25L3 7.5m18 0l-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9"/>
                            </svg>
                        </div>
                        <p class="text-[13px] font-medium text-gray-400 tracking-wide uppercase">Realidade Aumentada</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Trusted By -->
        <section class="relative z-10 max-w-[1600px] mx-auto px-6 pb-20">
            <div class="text-center mb-10">
                <p class="text-[13px] font-medium text-gray-400 tracking-[0.15em] uppercase">Utilizado por restaurantes de todo o Brasil</p>
            </div>
            <div class="flex flex-wrap justify-center items-center gap-[60px] md:gap-[100px] opacity-40 grayscale">
                <!-- Placeholder logos -->
                <div class="flex items-center gap-2 text-gray-400">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/></svg>
                    <span class="text-[15px] font-bold tracking-tight">Nonna Titina</span>
                </div>
                <div class="flex items-center gap-2 text-gray-400">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/></svg>
                    <span class="text-[15px] font-bold tracking-tight">Burger Station</span>
                </div>
                <div class="flex items-center gap-2 text-gray-400">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><rect width="20" height="20" x="2" y="2" rx="4"/></svg>
                    <span class="text-[15px] font-bold tracking-tight">Sushi Flow</span>
                </div>
                <div class="flex items-center gap-2 text-gray-400">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><polygon points="12,2 22,22 2,22"/></svg>
                    <span class="text-[15px] font-bold tracking-tight">La Trattoria</span>
                </div>
                <div class="flex items-center gap-2 text-gray-400">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                    <span class="text-[15px] font-bold tracking-tight">Bistro 22</span>
                </div>
            </div>
        </section>
    </div>
</template>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

.sales-page {
    font-family: 'Inter', sans-serif;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
}

.hero-headline {
    font-family: 'Inter', sans-serif;
    font-weight: 700;
}

/* Floating animation for glass elements */
.glass-float {
    animation: float 6s ease-in-out infinite;
}

@keyframes float {
    0%, 100% { transform: translateY(0px); }
    50% { transform: translateY(-12px); }
}

/* Subtle glow pulse on CTA */
.cta-primary {
    position: relative;
}
.cta-primary::after {
    content: '';
    position: absolute;
    inset: -2px;
    border-radius: 18px;
    background: linear-gradient(135deg, rgba(0,132,255,0.2), rgba(49,154,255,0.1));
    z-index: -1;
    opacity: 0;
    transition: opacity 0.3s;
}
.cta-primary:hover::after {
    opacity: 1;
}
</style>
