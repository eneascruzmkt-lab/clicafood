<script setup>
import { ref, onMounted } from 'vue';
import { Head } from '@inertiajs/vue3';

const mockupVideos = [
    { name: 'Grano Duro - Piccolo', desc: 'Massa tradicional italiana de trigo duro.', price: '24,90', video: 'https://pub-18e3929bc28243d48bdae811aaf40664.r2.dev/videos/a60d0462-57f9-4a1e-b224-29a6685946f0.mp4', thumb: 'https://pub-18e3929bc28243d48bdae811aaf40664.r2.dev/thumbnails/mCIHrtUL5z9JvJ8PBSAHPx05dbJW1C3I1JQ6dxaA.jpg' },
    { name: 'Artesanal - Piccolo', desc: 'Massa artesanal feita à mão diariamente.', price: '29,90', video: 'https://pub-18e3929bc28243d48bdae811aaf40664.r2.dev/videos/556dccb1-8e40-4dc9-84a0-31f5912cce32.mp4', thumb: 'https://pub-18e3929bc28243d48bdae811aaf40664.r2.dev/thumbnails/gZbj5t2bLouzuZPtoHSPCfydRTtcnfduUqGNz9cT.jpg' },
    { name: 'Carbonara', desc: 'Molho cremoso de ovos, queijo parmesão e bacon.', price: '19,90', video: 'https://pub-18e3929bc28243d48bdae811aaf40664.r2.dev/videos/3ab920cc-73c5-4b1c-8671-dc0e2a3c058b.mp4', thumb: 'https://pub-18e3929bc28243d48bdae811aaf40664.r2.dev/thumbnails/DwUBQ2Rk83Z31ofUsUDdpJyAS6W0gEcCSWkLTmJj.jpg' },
    { name: 'Fettuccine Alfredo', desc: 'Molho clássico de manteiga e parmesão.', price: '17,90', video: 'https://pub-18e3929bc28243d48bdae811aaf40664.r2.dev/videos/d528ec63-ef5f-470a-b50b-b08a48814248.mp4', thumb: 'https://pub-18e3929bc28243d48bdae811aaf40664.r2.dev/thumbnails/Q5UzFKj3EwU9D95p0eBfjLcJN2vKWriwLimeBfhe.jpg' },
];
</script>

<template>
    <div class="sales-page min-h-screen bg-white antialiased">
        <Head title="ClicaFood - Cardápio Digital com Vídeo e Realidade Aumentada" />

        <!-- Video Background (low opacity, scrolls with page) -->
        <div class="absolute inset-0 z-0" style="min-height: 100vh;">
            <video autoplay muted loop playsinline class="w-full h-full object-cover opacity-[0.15]"
                   src="https://d8j0ntlcm91z4.cloudfront.net/user_3BjsSiNrO0Qi7gnNljguAwYXV5J/hf_20260401_053538_3e40a761-15e6-4df1-af65-100566363fe4.mp4"></video>
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

        <!-- Social Proof (centered below navbar) -->
        <div class="relative z-20 flex justify-center mt-10 md:mt-14 px-4">
            <div class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full"
                 style="background: rgba(255,255,255,0.7); border: 1px solid rgba(0,0,0,0.06); box-shadow: inset 0px 2px 3px rgba(255,255,255,0.3); backdrop-filter: blur(20px);">
                <div class="flex gap-0.5">
                    <svg v-for="n in 5" :key="n" class="w-4 h-4 text-[#FF801E]" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                </div>
                <span class="text-[13px] font-medium text-gray-700">Avaliado 4.9/5 por <strong class="text-gray-900">2.700+</strong> restaurantes</span>
            </div>
        </div>

        <!-- Hero Section -->
        <section class="relative z-10 max-w-[1100px] mx-auto px-6 pt-6 md:pt-10 pb-20 min-h-[calc(100vh-160px)]">
            <div class="grid md:grid-cols-2 gap-6 md:gap-10 items-end">
                <!-- Hero Left -->
                <div class="max-w-[520px] mx-auto md:mx-0">
                    <!-- Big Logo -->
                    <img src="/images/logo-clicafood-full.png" alt="ClicaFood" class="h-[100px] sm:h-[130px] md:h-[160px] lg:h-[200px] mb-0" />

                    <!-- Headline -->
                    <h1 class="hero-headline text-[32px] sm:text-[40px] md:text-[48px] font-bold leading-[1.1] tracking-[-1.5px] text-gray-900 mb-4">
                        Seu cardápio em
                        <span class="text-[#E63B2E]">outro nível.</span>
                    </h1>

                    <!-- Subheadline -->
                    <p class="text-[16px] md:text-[17px] leading-[1.7] tracking-[-0.3px] text-gray-600 mb-8 max-w-[460px]">
                        Transforme fotos dos seus pratos em experiências 3D e vídeos imersivos. Cardápio digital com realidade aumentada, métricas em tempo real e QR Codes inteligentes.
                    </p>

                    <!-- Primary CTA -->
                    <div class="flex flex-col sm:flex-row gap-4 items-start">
                        <a href="/register"
                           class="cta-primary group inline-flex items-center gap-3 px-8 py-4 rounded-[16px] text-white text-[16px] font-semibold transition-all duration-300 hover:scale-[1.02]"
                           style="background: #E63B2E; box-shadow: inset 0px 4px 4px 0px rgba(255,255,255,0.25), 0 8px 32px rgba(230,59,46,0.35);">
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

                <!-- Hero Right (AR + Mockup Reels + Mockup Card) -->
                <div class="relative flex items-center justify-center md:justify-end min-h-[380px] md:min-h-[500px] mt-8 md:mt-0">

                    <!-- Label: Estilo TikTok -->
                    <div class="absolute top-0 right-[30%] md:right-[220px] z-40 flex flex-col items-center">
                        <p class="text-[12px] md:text-[20px] font-bold text-[#E63B2E]">Estilo TikTok</p>
                    </div>

                    <!-- Label: Card flutuante -->
                    <div class="absolute top-0 right-0 md:right-2 z-40 flex flex-col items-center">
                        <p class="text-[12px] md:text-[20px] font-bold text-[#E63B2E]">Card flutuante</p>
                    </div>

                    <!-- 3D Model + label -->
                    <div class="relative z-30 flex-shrink-0" style="margin-right: -60px;">
                        <!-- Gire o prato label (positioned above-left of the model) -->
                        <div class="absolute -top-2 -left-2 z-40 flex flex-col items-center gap-0.5">
                            <p class="text-[11px] md:text-[18px] font-bold text-[#E63B2E]">Gire o prato</p>
                            <svg class="w-4 h-4 md:w-5 md:h-5 text-[#E63B2E] animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 12c0-1.232-.046-2.453-.138-3.662a4.006 4.006 0 00-3.7-3.7 48.678 48.678 0 00-7.324 0 4.006 4.006 0 00-3.7 3.7c-.017.22-.032.441-.046.662M19.5 12l3-3m-3 3l-3-3m-12 3c0 1.232.046 2.453.138 3.662a4.006 4.006 0 003.7 3.7 48.656 48.656 0 007.324 0 4.006 4.006 0 003.7-3.7c.017-.22.032-.441.046-.662M4.5 12l3 3m-3-3l-3 3"/>
                            </svg>
                        </div>
                        <model-viewer
                            src="/models/demo-pasta.glb"
                            ar
                            ar-modes="webxr scene-viewer quick-look"
                            camera-controls
                            auto-rotate
                            rotation-per-second="25deg"
                            shadow-intensity="0"
                            environment-image="neutral"
                            exposure="1.2"
                            alt="Prato de massa em 3D"
                            class="w-[180px] h-[180px] md:w-[260px] md:h-[260px]"
                            style="--poster-color: transparent;"
                            loading="eager"
                            camera-orbit="45deg 70deg 5.5m"
                            field-of-view="30deg"
                        >
                        </model-viewer>
                    </div>

                    <!-- Phone Mockup 1: Reels -->
                    <div class="relative z-20 flex-shrink-0" style="margin-left: -40px;">
                        <div class="relative w-[150px] h-[310px] md:w-[200px] md:h-[420px] bg-[#0a0a0a] rounded-[24px] md:rounded-[32px] border-[4px] md:border-[5px] border-[#1c1c1e] overflow-hidden"
                             style="box-shadow: 0 30px 60px rgba(0,0,0,0.25);">
                            <div class="absolute top-0 left-1/2 -translate-x-1/2 w-16 md:w-20 h-3 md:h-4 bg-[#0a0a0a] rounded-b-xl z-20"></div>
                            <div class="mockup-scroll h-full w-full overflow-hidden">
                                <div class="mockup-scroll-inner" style="--slide-h: 310px;" :style="{ '--slide-h-md': '420px' }">
                                    <div v-for="(vid, idx) in mockupVideos" :key="'reels-'+idx" class="relative w-full h-[310px] md:h-[420px] flex-shrink-0">
                                        <video autoplay muted loop playsinline class="w-full h-full object-cover" :src="vid.video" :poster="vid.thumb"></video>
                                        <div class="absolute inset-0 bg-gradient-to-b from-black/20 via-transparent to-black/60 pointer-events-none"></div>
                                        <div class="absolute bottom-0 left-0 right-0 p-2 md:p-3">
                                            <h3 class="text-white font-bold text-[10px] md:text-[12px] drop-shadow-lg">{{ vid.name }}</h3>
                                            <p class="text-white/60 text-[8px] md:text-[9px] mt-0.5 line-clamp-2">{{ vid.desc }}</p>
                                            <span class="text-[#E63B2E] font-bold text-[9px] md:text-[11px] mt-0.5 block">R$ {{ vid.price }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Phone Mockup 2: Card layout -->
                    <div class="relative z-10 flex-shrink-0" style="margin-left: -30px;">
                        <div class="relative w-[150px] h-[310px] md:w-[200px] md:h-[420px] bg-[#0a0a0a] rounded-[24px] md:rounded-[32px] border-[4px] md:border-[5px] border-[#1c1c1e] overflow-hidden"
                             style="box-shadow: 0 30px 60px rgba(0,0,0,0.25);">
                            <div class="absolute top-0 left-1/2 -translate-x-1/2 w-16 md:w-20 h-3 md:h-4 bg-[#0a0a0a] rounded-b-xl z-20"></div>
                            <div class="mockup-scroll-card h-full w-full overflow-hidden">
                                <div class="mockup-scroll-card-inner">
                                    <div v-for="(vid, idx) in mockupVideos" :key="'card-'+idx" class="relative w-full h-[310px] md:h-[420px] flex-shrink-0">
                                        <video autoplay muted loop playsinline class="w-full h-full object-cover" :src="vid.video" :poster="vid.thumb"></video>
                                        <div class="absolute inset-0 bg-gradient-to-b from-transparent via-transparent to-black/30 pointer-events-none"></div>
                                        <div class="absolute bottom-2 md:bottom-3 left-2 md:left-3 right-2 md:right-3">
                                            <div class="rounded-lg md:rounded-xl p-2 md:p-2.5 backdrop-blur-md" style="background: rgba(255,255,255,0.9);">
                                                <div class="flex gap-1.5 md:gap-2 items-center">
                                                    <div class="w-8 h-8 md:w-10 md:h-10 rounded-md md:rounded-lg overflow-hidden flex-shrink-0">
                                                        <img :src="vid.thumb" :alt="vid.name" class="w-full h-full object-cover" />
                                                    </div>
                                                    <div class="flex-1 min-w-0">
                                                        <h3 class="text-[#121212] font-bold text-[8px] md:text-[10px] leading-tight truncate">{{ vid.name }}</h3>
                                                        <p class="text-[7px] md:text-[8px] text-gray-500 mt-0.5">ver ingredientes</p>
                                                        <span class="text-[#E63B2E] font-bold text-[8px] md:text-[10px]">R$ {{ vid.price }}</span>
                                                    </div>
                                                    <svg class="w-3.5 h-3.5 md:w-4 md:h-4 text-red-500 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24"><path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Trusted By (Marquee) - separate section -->
        <section class="relative z-10 bg-white py-16 overflow-hidden">
            <div class="text-center mb-8">
                <p class="text-[13px] font-medium text-gray-400 tracking-[0.15em] uppercase">Utilizado por restaurantes de todo o Brasil</p>
            </div>
            <div class="marquee-container">
                <div class="marquee-track">
                    <div v-for="n in 2" :key="n" class="flex items-center gap-16 px-8">
                        <span class="text-[18px] font-bold tracking-tight text-gray-300 whitespace-nowrap">Nonna Titina</span>
                        <span class="text-gray-200">•</span>
                        <span class="text-[18px] font-bold tracking-tight text-gray-300 whitespace-nowrap">Burger Station</span>
                        <span class="text-gray-200">•</span>
                        <span class="text-[18px] font-bold tracking-tight text-gray-300 whitespace-nowrap">Sushi Flow</span>
                        <span class="text-gray-200">•</span>
                        <span class="text-[18px] font-bold tracking-tight text-gray-300 whitespace-nowrap">La Trattoria</span>
                        <span class="text-gray-200">•</span>
                        <span class="text-[18px] font-bold tracking-tight text-gray-300 whitespace-nowrap">Bistro 22</span>
                        <span class="text-gray-200">•</span>
                        <span class="text-[18px] font-bold tracking-tight text-gray-300 whitespace-nowrap">Meat & Co.</span>
                        <span class="text-gray-200">•</span>
                        <span class="text-[18px] font-bold tracking-tight text-gray-300 whitespace-nowrap">Canto Nordestino</span>
                        <span class="text-gray-200">•</span>
                    </div>
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
    background: linear-gradient(135deg, rgba(230,59,46,0.2), rgba(49,154,255,0.1));
    z-index: -1;
    opacity: 0;
    transition: opacity 0.3s;
}
.cta-primary:hover::after {
    opacity: 1;
}

/* Phone mockup auto-scroll (Reels) */
.mockup-scroll-inner {
    display: flex;
    flex-direction: column;
    animation: mockupScrollMobile 20s ease-in-out infinite;
}

/* Phone mockup auto-scroll (Card) - offset timing */
.mockup-scroll-card-inner {
    display: flex;
    flex-direction: column;
    animation: mockupScrollMobile 20s ease-in-out infinite;
    animation-delay: 2.5s;
}

@keyframes mockupScrollMobile {
    0%, 10% { transform: translateY(0); }
    20%, 30% { transform: translateY(-310px); }
    40%, 50% { transform: translateY(-620px); }
    60%, 70% { transform: translateY(-930px); }
    80%, 100% { transform: translateY(0); }
}

@media (min-width: 768px) {
    .mockup-scroll-inner {
        animation-name: mockupScrollDesktop;
    }
    .mockup-scroll-card-inner {
        animation-name: mockupScrollDesktop;
    }
}

@keyframes mockupScrollDesktop {
    0%, 10% { transform: translateY(0); }
    20%, 30% { transform: translateY(-420px); }
    40%, 50% { transform: translateY(-840px); }
    60%, 70% { transform: translateY(-1260px); }
    80%, 100% { transform: translateY(0); }
}

/* Remove model-viewer background */
model-viewer {
    --poster-color: transparent;
}

/* Responsive video background scale - smaller screens = bigger scale for white center */
.hero-bg-video {
    transform: scale(2.2);
}
@media (min-width: 640px) {
    .hero-bg-video { transform: scale(1.8); }
}
@media (min-width: 768px) {
    .hero-bg-video { transform: scale(1.5); }
}
@media (min-width: 1024px) {
    .hero-bg-video { transform: scale(1.3); }
}
@media (min-width: 1280px) {
    .hero-bg-video { transform: scale(1.2); }
}

/* Marquee animation */
.marquee-container {
    width: 100%;
    overflow: hidden;
    mask-image: linear-gradient(to right, transparent 0%, black 10%, black 90%, transparent 100%);
    -webkit-mask-image: linear-gradient(to right, transparent 0%, black 10%, black 90%, transparent 100%);
}
.marquee-track {
    display: flex;
    width: max-content;
    animation: marquee 30s linear infinite;
}
@keyframes marquee {
    0% { transform: translateX(0); }
    100% { transform: translateX(-50%); }
}
</style>
