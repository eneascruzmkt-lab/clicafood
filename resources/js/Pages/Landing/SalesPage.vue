<script setup>
import { ref } from 'vue';
import { Head } from '@inertiajs/vue3';

// No scroll-driven video — simple autoplay loop background

const mockupVideos = [
    { name: 'Grano Duro - Piccolo', desc: 'Massa tradicional italiana de trigo duro.', price: '24,90', video: 'https://pub-18e3929bc28243d48bdae811aaf40664.r2.dev/videos/a60d0462-57f9-4a1e-b224-29a6685946f0.mp4', thumb: 'https://pub-18e3929bc28243d48bdae811aaf40664.r2.dev/thumbnails/mCIHrtUL5z9JvJ8PBSAHPx05dbJW1C3I1JQ6dxaA.jpg' },
    { name: 'Artesanal - Piccolo', desc: 'Massa artesanal feita à mão diariamente.', price: '29,90', video: 'https://pub-18e3929bc28243d48bdae811aaf40664.r2.dev/videos/556dccb1-8e40-4dc9-84a0-31f5912cce32.mp4', thumb: 'https://pub-18e3929bc28243d48bdae811aaf40664.r2.dev/thumbnails/gZbj5t2bLouzuZPtoHSPCfydRTtcnfduUqGNz9cT.jpg' },
    { name: 'Carbonara', desc: 'Molho cremoso de ovos, queijo parmesão e bacon.', price: '19,90', video: 'https://pub-18e3929bc28243d48bdae811aaf40664.r2.dev/videos/3ab920cc-73c5-4b1c-8671-dc0e2a3c058b.mp4', thumb: 'https://pub-18e3929bc28243d48bdae811aaf40664.r2.dev/thumbnails/DwUBQ2Rk83Z31ofUsUDdpJyAS6W0gEcCSWkLTmJj.jpg' },
    { name: 'Fettuccine Alfredo', desc: 'Molho clássico de manteiga e parmesão.', price: '17,90', video: 'https://pub-18e3929bc28243d48bdae811aaf40664.r2.dev/videos/d528ec63-ef5f-470a-b50b-b08a48814248.mp4', thumb: 'https://pub-18e3929bc28243d48bdae811aaf40664.r2.dev/thumbnails/Q5UzFKj3EwU9D95p0eBfjLcJN2vKWriwLimeBfhe.jpg' },
];

const salesFeatures = [
    { icon: 'videocam', title: 'Vídeos que Vendem', desc: 'Substitua fotos estáticas por vídeos imersivos que despertam o desejo imediato do cliente.' },
    { icon: 'auto_stories', title: 'Stories dos Pratos', desc: 'Experiência de navegação familiar. Seus clientes já sabem usar antes mesmo de abrir.' },
    { icon: 'analytics', title: 'Métricas Real Time', desc: 'Saiba exatamente qual vídeo está gerando mais pedidos em tempo real no seu dashboard.' },
    { icon: 'qr_code_2', title: 'QR Code Ilimitado', desc: 'Gerencie múltiplas mesas e pontos de venda com códigos dinâmicos e inteligentes.' },
    { icon: 'view_in_ar', title: 'Pratos em 3D e AR', desc: 'Seus clientes veem o prato em tamanho real na mesa antes de pedir. Gerado automaticamente por IA.' },
];

const salesTestimonials = [
    { name: 'Carlos Mendes', role: 'CEO, Meat & Co.', text: 'O Clicafood mudou a forma como nossos clientes interagem com o menu. O aumento nas vendas de sobremesas foi instantâneo.', img: 'https://lh3.googleusercontent.com/aida-public/AB6AXuAnyl_ej5aU1dWChtiH83guDZdC9XE8md0qeM-w6wemmsy61D7i1F9mX4B5dh6OtrCaHWfNskLPkXQp8uNc6gTACUPv0zykQnVHECsXL9b1Kqncf67Qmv5SmYLNSJVUfEYrrhjRpUvMO-izuuU6Bi8wN7sc1MUxkcg6zIYVwwS176I6CSpmwNPVOg18ce-l4oiuik3bQPZifVMMPv6tgB1u0LqfqNE01rDI8Epq0Bs6gm10mbpOpjXfiifpfKgdP77Up6CrOJMikaAM' },
    { name: 'Ana Luiza', role: 'Fundadora, Sushi Flow', text: 'A facilidade de atualizar os pratos e as métricas em tempo real nos deram um controle que nunca tivemos antes.', img: 'https://lh3.googleusercontent.com/aida-public/AB6AXuAhPnhLZ-GraQvX8ZNnHpI3Ro9uYj7x2Xc-wK_NeS-_szCKPxcROiNZCp5xjAMZlrjKojcFOzEnWlilOK6R5g4Tqgrpva97oQf0lK68zIkBEDcLMo2adj2Ukdd4d0NsSoTRJZDYqHgzQz0i6wqnHkIPBysxfncEjAKFL4C6izg_XA0qDEkC-28dwInHx2XvmyqpuITkeOExSg0LvzQFRldJ10kDxbxBN6QSYOr3gtI2QWIyabaqInzPJ9UbUB44KybnK4YV5u3wFzAW' },
    { name: 'Roberto Silva', role: 'Chef Executivo, Bistro 22', text: 'O layout video-first é viciante. Os clientes passam muito mais tempo explorando o cardápio e pedindo mais.', img: 'https://lh3.googleusercontent.com/aida-public/AB6AXuDyKQNO2pa98DFxYdijHY1X33ZduhN_xup0xcYiCBCP3VG1PBvaeTfuz-zHN25Z6fwc-cDf18ojDF7XKNh-iaqaDbl2VBCsqXnd2TmoCjrvy_SRI9dpJphbysBGQGMGe8H2TVYjok5aptVYxL-dUnlTh3DMzuZsz1iYNHlOcujkPdtlSbe16AetsAC9ZPK0kaKhk0je2B7kFIz_mQ98FUAPyRVIwO10cMyVVL1S-Jqsyce4b3Mt90VcGAHdkH6vxEZ2_KJi2FQwMAKK' },
];

const salesPricingFeatures = [
    'Cardápio Video-First Ilimitado',
    'QR Codes Dinâmicos Ilimitados',
    'Dashboard de Métricas Avançadas',
    'Suporte Prioritário 24/7',
    'Integração com WhatsApp',
    'Realidade Aumentada 3D',
];

const salesFaqs = [
    { q: 'Preciso de equipamentos profissionais para os vídeos?', a: 'Não! Um smartphone moderno é tudo o que você precisa. Nossa plataforma otimiza automaticamente seus vídeos para garantir o carregamento instantâneo em qualquer conexão.' },
    { q: 'Como funciona a Realidade Aumentada?', a: 'Você sobe a foto do prato e nossa IA gera automaticamente um modelo 3D. Seus clientes podem ver o prato em tamanho real na mesa deles, direto pelo celular.' },
    { q: 'Posso cancelar a qualquer momento?', a: 'Sim. Não temos contratos de fidelidade. Você paga mensalmente e pode cancelar quando quiser sem multas.' },
    { q: 'O QR Code já vem pronto?', a: 'Sim. QR Codes ilimitados gerados automaticamente, personalizáveis com suas cores. Baixe em PNG ou SVG para imprimir.' },
    { q: 'Quantos pratos posso adicionar?', a: 'No plano Pro, itens ilimitados. Adicione quantos pratos quiser, com foto, vídeo e modelo 3D para cada um.' },
];
const openSalesFaq = ref(null);
</script>

<template>
    <div class="sales-page min-h-screen bg-white antialiased scroll-smooth">
        <Head title="ClicaFood - Cardápio Digital com Vídeo e Realidade Aumentada" />

        <!-- Hero Section -->
        <section class="relative z-10 overflow-hidden pb-20">
            <!-- Video Background (contained in hero only) -->
            <div class="absolute inset-0 z-0">
                <video autoplay muted loop playsinline preload="auto" class="w-full h-full object-cover opacity-[0.20]"
                       src="https://d8j0ntlcm91z4.cloudfront.net/user_3BjsSiNrO0Qi7gnNljguAwYXV5J/hf_20260402_015757_3dca0a31-8103-4bf5-afc6-56bd58e76662.mp4"></video>
            </div>

            <!-- Navbar (Liquid Glass) -->
            <div class="sticky top-[10px] z-50 flex justify-center px-4 pt-4">
                <nav class="glass-nav flex items-center gap-1 px-2 py-2 rounded-[16px]"
                     style="backdrop-filter: blur(50px); -webkit-backdrop-filter: blur(50px); background: rgba(255,255,255,0.3); border: 1px solid rgba(0,0,0,0.1); box-shadow: inset 0px 4px 4px 0px rgba(255,255,255,0.25);">
                    <a href="/" class="flex items-center gap-2 px-4 py-2">
                        <img src="/images/logo-clicafood-full.png" alt="ClicaFood" class="h-8" />
                    </a>
                    <div class="hidden md:flex items-center gap-1">
                        <a href="#features" class="nav-link px-4 py-2 rounded-[12px] text-[14px] font-medium text-gray-600 hover:text-gray-900 hover:bg-white/40 transition-all duration-200">Benefícios</a>
                        <a href="#ar" class="nav-link px-4 py-2 rounded-[12px] text-[14px] font-medium text-gray-600 hover:text-gray-900 hover:bg-white/40 transition-all duration-200">Realidade Aumentada</a>
                        <a href="#pricing" class="nav-link px-4 py-2 rounded-[12px] text-[14px] font-medium text-gray-600 hover:text-gray-900 hover:bg-white/40 transition-all duration-200">Planos</a>
                    </div>
                    <a href="/register" class="ml-2 flex items-center gap-2 px-5 py-2.5 rounded-[12px] text-[14px] font-semibold text-white transition-all duration-200 hover:opacity-90"
                       style="background: #E63B2E;">
                        Começar Agora
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12h15m0 0l-6.75-6.75M19.5 12l-6.75 6.75"/></svg>
                    </a>
                </nav>
            </div>

            <div class="relative z-10 max-w-[1100px] mx-auto px-6 pt-4 md:pt-6">
            <div class="grid md:grid-cols-2 gap-6 md:gap-10 items-end">
                <!-- Hero Left -->
                <div class="max-w-[520px] mx-auto md:mx-0">
                    <!-- Big Logo -->
                    <img src="/images/logo-clicafood-full.png" alt="ClicaFood" class="h-[70px] sm:h-[90px] md:h-[110px] lg:h-[130px] mb-0" />

                    <!-- Headline -->
                    <h1 class="hero-headline text-[28px] sm:text-[36px] md:text-[44px] font-bold leading-[1.15] tracking-[-1.5px] text-gray-900 mb-4">
                        Venda Mais E Aumente Seu Ticket Médio Com Um Cardápio Com <span class="text-[#E63B2E]">Vídeos</span> E Seus Pratos Em <span class="text-[#E63B2E]">3D</span>
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

            </div> <!-- close relative z-10 wrapper -->

            <!-- Social Proof -->
            <div class="relative z-10 flex justify-center mt-12">
                <div class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full"
                     style="background: #E63B2E; box-shadow: 0 4px 16px rgba(230,59,46,0.3);">
                    <div class="flex gap-0.5">
                        <svg v-for="n in 5" :key="n" class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                    </div>
                    <span class="text-[13px] font-medium text-white">Avaliado 4.9/5 por <strong class="text-white">2.700+</strong> restaurantes</span>
                </div>
            </div>
        </section>

        <!-- Features Section -->
        <section class="relative z-10 bg-gray-50 py-20 md:py-28" id="features">
            <div class="max-w-[1100px] mx-auto px-6">
                <div class="mb-16">
                    <span class="text-[#E63B2E] font-bold tracking-widest uppercase text-xs">Tecnologia Cinética</span>
                    <h2 class="text-3xl md:text-5xl font-bold mt-2 text-gray-900 tracking-tight">Destrua a Concorrência.</h2>
                </div>
                <div class="grid sm:grid-cols-2 lg:grid-cols-5 gap-6">
                    <div v-for="f in salesFeatures" :key="f.title" class="bg-white p-8 rounded-2xl transition-all hover:-translate-y-1 hover:shadow-lg duration-300">
                        <div class="w-12 h-12 rounded-xl bg-[#E63B2E]/10 flex items-center justify-center text-[#E63B2E] mb-5">
                            <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">{{ f.icon }}</span>
                        </div>
                        <h3 class="font-bold text-lg mb-2 text-gray-900">{{ f.title }}</h3>
                        <p class="text-gray-500 text-sm leading-relaxed">{{ f.desc }}</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- AR Before/After Section -->
        <section class="relative z-10 bg-white py-20 md:py-28" id="ar">
            <div class="max-w-[1100px] mx-auto px-6">
                <div class="text-center mb-14">
                    <span class="text-[#E63B2E] font-bold tracking-widest uppercase text-xs">Realidade Aumentada</span>
                    <h2 class="text-3xl md:text-5xl font-bold mt-2 text-gray-900 tracking-tight">Da foto ao 3D em segundos.</h2>
                    <p class="text-gray-500 mt-4 max-w-xl mx-auto">Nossa IA transforma a foto do seu prato em um modelo 3D interativo. Seus clientes podem girar, dar zoom e ver em realidade aumentada.</p>
                </div>
                <div class="grid md:grid-cols-2 gap-8 items-center">
                    <!-- Before: Photo -->
                    <div class="text-center">
                        <div class="inline-block rounded-2xl overflow-hidden shadow-lg mb-4">
                            <img src="https://pub-18e3929bc28243d48bdae811aaf40664.r2.dev/menu-items/TzLsRKUIiv11fGpyPFm4KDWevOY1j1WC7RlQLf1I.jpg"
                                 alt="Foto do prato" class="w-full max-w-[360px] h-auto" />
                        </div>
                        <p class="text-sm font-semibold text-gray-400 uppercase tracking-wider">Foto original</p>
                    </div>
                    <!-- After: 3D Model -->
                    <div class="text-center">
                        <div class="inline-block mb-4">
                            <model-viewer
                                src="/models/demo-pasta.glb"
                                ar
                                ar-modes="webxr scene-viewer quick-look"
                                camera-controls
                                auto-rotate
                                rotation-per-second="20deg"
                                shadow-intensity="0.5"
                                environment-image="neutral"
                                exposure="1.1"
                                alt="Prato em 3D"
                                style="width: 360px; height: 300px; --poster-color: transparent;"
                                camera-orbit="45deg 70deg 5.5m"
                                field-of-view="30deg"
                            >
                                <button slot="ar-button"
                                        class="absolute bottom-3 left-1/2 -translate-x-1/2 px-5 py-2.5 rounded-full text-white text-[13px] font-semibold flex items-center gap-2"
                                        style="background: #E63B2E; box-shadow: 0 4px 16px rgba(230,59,46,0.3);">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 7.5l-9-5.25L3 7.5m18 0l-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9"/>
                                    </svg>
                                    Ver em AR
                                </button>
                            </model-viewer>
                        </div>
                        <p class="text-sm font-semibold text-[#E63B2E] uppercase tracking-wider">Modelo 3D interativo</p>
                        <p class="text-xs text-gray-400 mt-1">Gire, dê zoom e veja em realidade aumentada</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Stats Section -->
        <section class="relative z-10 bg-gray-900 py-20 md:py-24 overflow-hidden">
            <div class="absolute top-0 right-0 w-1/3 h-full bg-[#E63B2E]/5 blur-[120px] rounded-full"></div>
            <div class="max-w-[1100px] mx-auto px-6 relative z-10">
                <div class="grid grid-cols-3 gap-8 text-center">
                    <div>
                        <div class="text-5xl md:text-7xl font-black text-[#E63B2E] mb-2">+73%</div>
                        <p class="text-white/50 uppercase tracking-widest text-xs md:text-sm">Mais Pedidos com Vídeo</p>
                    </div>
                    <div>
                        <div class="text-5xl md:text-7xl font-black text-[#E63B2E] mb-2">2.4x</div>
                        <p class="text-white/50 uppercase tracking-widest text-xs md:text-sm">Aumento no Ticket Médio</p>
                    </div>
                    <div>
                        <div class="text-5xl md:text-7xl font-black text-[#E63B2E] mb-2">89%</div>
                        <p class="text-white/50 uppercase tracking-widest text-xs md:text-sm">Taxa de Engajamento</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Testimonials Section -->
        <section class="relative z-10 bg-white py-20 md:py-28">
            <div class="max-w-[1100px] mx-auto px-6">
                <div class="text-center mb-14">
                    <h2 class="text-3xl md:text-5xl font-bold text-gray-900 tracking-tight">Quem usa, domina.</h2>
                    <p class="text-gray-500 mt-4">Líderes do mercado gastronômico que já viraram a chave.</p>
                </div>
                <div class="grid md:grid-cols-3 gap-6">
                    <div v-for="t in salesTestimonials" :key="t.name" class="bg-gray-50 p-8 rounded-2xl">
                        <div class="flex gap-1 text-[#E63B2E] mb-5">
                            <svg v-for="n in 5" :key="n" class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        </div>
                        <p class="text-gray-700 text-[15px] italic mb-6 leading-relaxed">"{{ t.text }}"</p>
                        <div class="flex items-center gap-3">
                            <img :src="t.img" :alt="t.name" class="w-10 h-10 rounded-full object-cover" />
                            <div>
                                <p class="font-bold text-gray-900 text-sm">{{ t.name }}</p>
                                <p class="text-xs text-gray-400">{{ t.role }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Pricing Section -->
        <section class="relative z-10 bg-gray-50 py-20 md:py-28" id="pricing">
            <div class="max-w-[1100px] mx-auto px-6">
                <div class="max-w-3xl mx-auto text-center mb-14">
                    <h2 class="text-3xl md:text-5xl font-bold text-gray-900 tracking-tight">Investimento Simples.</h2>
                    <p class="text-gray-500 mt-4">Preço transparente para escalar seu negócio sem surpresas.</p>
                </div>
                <div class="max-w-lg mx-auto bg-white rounded-3xl p-10 md:p-12 shadow-xl relative overflow-hidden">
                    <div class="absolute top-0 right-0 px-6 py-2 rounded-bl-2xl font-bold text-sm text-white" style="background: #E63B2E;">-20% OFF</div>
                    <h3 class="font-bold text-2xl md:text-3xl mb-2 text-gray-900">Plano Pro</h3>
                    <p class="text-gray-500 mb-6">Tudo o que você precisa para dominar.</p>
                    <div class="mb-2"><span class="text-sm text-gray-400 line-through">R$ 197/mês</span></div>
                    <div class="flex items-baseline gap-2 mb-8">
                        <span class="text-2xl font-bold text-gray-900">R$</span>
                        <span class="text-6xl font-black text-gray-900 tracking-tighter">97</span>
                        <span class="text-gray-500 font-medium">/mês</span>
                    </div>
                    <ul class="space-y-3 mb-8">
                        <li v-for="pf in salesPricingFeatures" :key="pf" class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-[#E63B2E] text-xl" style="font-variation-settings: 'FILL' 1;">check_circle</span>
                            <span class="text-gray-700 font-medium text-sm">{{ pf }}</span>
                        </li>
                    </ul>
                    <a href="/register" class="block w-full py-4 rounded-2xl text-white font-bold text-lg text-center transition-all hover:scale-[1.02] active:scale-95"
                       style="background: #E63B2E; box-shadow: 0 8px 24px rgba(230,59,46,0.3);">
                        Começar Agora
                    </a>
                    <p class="text-xs text-gray-400 mt-4 text-center">7 dias grátis. Cancele quando quiser. Sem fidelidade.</p>
                </div>
            </div>
        </section>

        <!-- FAQ Section -->
        <section class="relative z-10 bg-white py-20 md:py-28">
            <div class="max-w-[700px] mx-auto px-6">
                <h2 class="text-3xl md:text-4xl font-bold text-center mb-12 text-gray-900 tracking-tight">Dúvidas Frequentes</h2>
                <div class="space-y-3">
                    <div v-for="(faq, i) in salesFaqs" :key="i"
                         class="bg-gray-50 rounded-2xl cursor-pointer transition-all hover:bg-gray-100"
                         @click="openSalesFaq = openSalesFaq === i ? null : i">
                        <div class="flex justify-between items-center p-5 md:p-6">
                            <span class="font-bold text-gray-900 text-sm md:text-base pr-4">{{ faq.q }}</span>
                            <span class="material-symbols-outlined text-gray-400 transition-transform duration-300 flex-shrink-0"
                                  :class="{ 'rotate-180': openSalesFaq === i }">expand_more</span>
                        </div>
                        <transition enter-active-class="transition-all duration-300 ease-out" enter-from-class="max-h-0 opacity-0"
                                    enter-to-class="max-h-40 opacity-100" leave-active-class="transition-all duration-200 ease-in"
                                    leave-from-class="max-h-40 opacity-100" leave-to-class="max-h-0 opacity-0">
                            <div v-if="openSalesFaq === i" class="px-5 md:px-6 pb-5 md:pb-6 text-gray-500 text-sm leading-relaxed overflow-hidden">{{ faq.a }}</div>
                        </transition>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA Final -->
        <section class="relative z-10 py-20 md:py-28" style="background: #E63B2E;">
            <div class="max-w-[700px] mx-auto px-6 text-center">
                <h2 class="text-3xl md:text-5xl font-bold text-white mb-6 tracking-tight leading-tight">
                    Pare de perder vendas.<br/>Comece a dominar agora.
                </h2>
                <p class="text-white/70 mb-10 text-lg">Junte-se a mais de 5.000 restaurantes que já transformaram seus cardápios.</p>
                <a href="/register" class="inline-flex items-center gap-3 px-10 py-5 rounded-2xl text-[#E63B2E] text-lg font-bold bg-white hover:scale-[1.02] transition-all shadow-lg">
                    Começar Agora — Grátis
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12h15m0 0l-6.75-6.75M19.5 12l-6.75 6.75"/></svg>
                </a>
            </div>
        </section>

        <!-- Footer -->
        <footer class="relative z-10 bg-gray-900 py-12">
            <div class="max-w-[1100px] mx-auto px-6 flex flex-col md:flex-row justify-between items-center gap-6">
                <div class="flex flex-col items-center md:items-start gap-2">
                    <img src="/images/logo-clicafood.png" alt="ClicaFood" class="h-7" />
                    <p class="text-xs text-gray-500">© 2024 ClicaFood. Todos os direitos reservados.</p>
                </div>
                <div class="flex gap-8 text-xs text-gray-500">
                    <a href="#" class="hover:text-white transition-colors">Privacidade</a>
                    <a href="#" class="hover:text-white transition-colors">Termos de Uso</a>
                    <a href="#" class="hover:text-white transition-colors">Contato</a>
                </div>
            </div>
        </footer>
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
