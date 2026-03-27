<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue';
import { Head } from '@inertiajs/vue3';

const props = defineProps({
    whatsapp: Object,
});

// Scroll-based header opacity
const scrolled = ref(false);
const handleScroll = () => {
    scrolled.value = window.scrollY > 40;
};
onMounted(() => window.addEventListener('scroll', handleScroll));
onUnmounted(() => {
    window.removeEventListener('scroll', handleScroll);
});

// Word reveal animation
const heroReady = ref(false);
onMounted(() => {
    setTimeout(() => { heroReady.value = true; }, 200);
});

// Mockup products (Nonna Titina)
const mockupProducts = [
    { name: 'Grano Duro - Piccolo', desc: 'Massa tradicional italiana de trigo duro. Porção individual (250g a 350g). Escolha 1 proteína, 4 ingredientes e 1 molho.', price: '24,90', video: 'https://pub-18e3929bc28243d48bdae811aaf40664.r2.dev/videos/a60d0462-57f9-4a1e-b224-29a6685946f0.mp4', thumb: 'https://pub-18e3929bc28243d48bdae811aaf40664.r2.dev/thumbnails/mCIHrtUL5z9JvJ8PBSAHPx05dbJW1C3I1JQ6dxaA.jpg' },
    { name: 'Artesanal - Piccolo', desc: 'Massa artesanal feita à mão diariamente. Porção individual (250g a 350g). Escolha 1 proteína, 4 ingredientes e 1 molho.', price: '29,90', video: 'https://pub-18e3929bc28243d48bdae811aaf40664.r2.dev/videos/556dccb1-8e40-4dc9-84a0-31f5912cce32.mp4', thumb: 'https://pub-18e3929bc28243d48bdae811aaf40664.r2.dev/thumbnails/gZbj5t2bLouzuZPtoHSPCfydRTtcnfduUqGNz9cT.jpg' },
    { name: 'Nero di Sepia - Piccolo', desc: 'Massa negra com tinta de lula. Porção individual (250g a 350g). Escolha 1 proteína, 4 ingredientes e 1 molho.', price: '34,90', video: 'https://pub-18e3929bc28243d48bdae811aaf40664.r2.dev/videos/58f6b049-b315-4123-b8f5-6d747cca019e.mp4', thumb: 'https://pub-18e3929bc28243d48bdae811aaf40664.r2.dev/thumbnails/Xsi740k5SeGeTSydRwOQHcZhNon8WHjFHHlkr62F.jpg' },
    { name: 'Pasta Alla Contadina', desc: 'Massa al dente envolvida em azeite extravirgem com alho, pimentão e tomate fresco salteados. Molho pesto com frango desfiado.', price: '18,90', video: 'https://pub-18e3929bc28243d48bdae811aaf40664.r2.dev/videos/7442ec33-f591-4a34-9f03-c671ade2a71f.mp4', thumb: 'https://pub-18e3929bc28243d48bdae811aaf40664.r2.dev/thumbnails/kTWxTzYfqnHkIca6stHNJRXgxH3bTN9LMpncwLfF.jpg' },
    { name: 'Fettuccine Alfredo', desc: 'Fettuccine perfeitamente al dente envolvido em molho clássico de manteiga de alta qualidade e queijo parmesão.', price: '17,90', video: 'https://pub-18e3929bc28243d48bdae811aaf40664.r2.dev/videos/d528ec63-ef5f-470a-b50b-b08a48814248.mp4', thumb: 'https://pub-18e3929bc28243d48bdae811aaf40664.r2.dev/thumbnails/Q5UzFKj3EwU9D95p0eBfjLcJN2vKWriwLimeBfhe.jpg' },
    { name: 'Carbonara', desc: 'Massa al dente com molho cremoso de ovos, queijo parmesão, bacon crocante e pimenta preta. Um clássico italiano irresistível.', price: '19,90', video: 'https://pub-18e3929bc28243d48bdae811aaf40664.r2.dev/videos/3ab920cc-73c5-4b1c-8671-dc0e2a3c058b.mp4', thumb: 'https://pub-18e3929bc28243d48bdae811aaf40664.r2.dev/thumbnails/DwUBQ2Rk83Z31ofUsUDdpJyAS6W0gEcCSWkLTmJj.jpg' },
];
const mockupIndex = ref(0);
const prevMockupIndex = ref(null);
let mockupTimer = null;

const advanceMockup = () => {
    prevMockupIndex.value = mockupIndex.value;
    mockupIndex.value = (mockupIndex.value + 1) % mockupProducts.length;
};

onMounted(() => { mockupTimer = setInterval(advanceMockup, 5000); });
onUnmounted(() => { if (mockupTimer) clearInterval(mockupTimer); });

// Animated counters
const counters = ref([
    { target: 73, current: 0, suffix: '%', label: 'Mais Pedidos com Vídeo', prefix: '+' },
    { target: 2.4, current: 0, suffix: 'x', label: 'Mais Atenção no Cardápio', prefix: '' },
    { target: 89, current: 0, suffix: '%', label: 'Taxa de Engajamento', prefix: '' },
    { target: 35, current: 0, suffix: '%', label: 'Aumento no Ticket Médio', prefix: '+' },
    { target: 45, current: 0, suffix: '%', label: 'Menos Indecisão', prefix: '-' },
]);

const animateCounters = () => {
    counters.value.forEach((counter, i) => {
        const duration = 2000;
        const steps = 60;
        const increment = counter.target / steps;
        let current = 0;
        const timer = setInterval(() => {
            current += increment;
            if (current >= counter.target) {
                counters.value[i].current = counter.target;
                clearInterval(timer);
            } else {
                counters.value[i].current = Math.round(current * 10) / 10;
            }
        }, duration / steps);
    });
};

// Scroll reveal + counters
onMounted(() => {
    const counterObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) { animateCounters(); counterObserver.disconnect(); }
        });
    });
    const numbersEl = document.getElementById('numbers');
    if (numbersEl) counterObserver.observe(numbersEl);

    const revealObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => { if (entry.isIntersecting) entry.target.classList.add('revealed'); });
    }, { threshold: 0.1 });
    document.querySelectorAll('.reveal-section').forEach(el => revealObserver.observe(el));
});

// Features
const features = [
    { icon: 'videocam', title: 'Vídeos que Vendem', desc: 'Substitua fotos estáticas por vídeos imersivos que despertam o desejo imediato do cliente.' },
    { icon: 'auto_stories', title: 'Stories dos Pratos', desc: 'Experiência de navegação familiar. Seus clientes já sabem usar antes mesmo de abrir.' },
    { icon: 'analytics', title: 'Métricas Real Time', desc: 'Saiba exatamente qual vídeo está gerando mais pedidos em tempo real no seu dashboard.' },
    { icon: 'qr_code_2', title: 'QR Code Ilimitado', desc: 'Gerencie múltiplas mesas e pontos de venda com códigos dinâmicos e inteligentes.' },
    { icon: 'category', title: 'Categorias com Imagem', desc: 'Organize seu cardápio com categorias visuais que guiam o cliente direto para o pedido.' },
    { icon: 'tune', title: 'Gestão Poderosa', desc: 'Controle total do seu cardápio em poucos cliques. Edite, organize, domine.' },
];

// Testimonials
const testimonials = [
    { name: 'Carlos Mendes', role: 'CEO, Meat & Co.', text: 'O Clicafood mudou a forma como nossos clientes interagem com o menu. O aumento nas vendas de sobremesas foi instantâneo.', img: 'https://lh3.googleusercontent.com/aida-public/AB6AXuAnyl_ej5aU1dWChtiH83guDZdC9XE8md0qeM-w6wemmsy61D7i1F9mX4B5dh6OtrCaHWfNskLPkXQp8uNc6gTACUPv0zykQnVHECsXL9b1Kqncf67Qmv5SmYLNSJVUfEYrrhjRpUvMO-izuuU6Bi8wN7sc1MUxkcg6zIYVwwS176I6CSpmwNPVOg18ce-l4oiuik3bQPZifVMMPv6tgB1u0LqfqNE01rDI8Epq0Bs6gm10mbpOpjXfiifpfKgdP77Up6CrOJMikaAM' },
    { name: 'Ana Luiza', role: 'Fundadora, Sushi Flow', text: 'A facilidade de atualizar os pratos e as métricas em tempo real nos deram um controle que nunca tivemos antes.', img: 'https://lh3.googleusercontent.com/aida-public/AB6AXuAhPnhLZ-GraQvX8ZNnHpI3Ro9uYj7x2Xc-wK_NeS-_szCKPxcROiNZCp5xjAMZlrjKojcFOzEnWlilOK6R5g4Tqgrpva97oQf0lK68zIkBEDcLMo2adj2Ukdd4d0NsSoTRJZDYqHgzQz0i6wqnHkIPBysxfncEjAKFL4C6izg_XA0qDEkC-28dwInHx2XvmyqpuITkeOExSg0LvzQFRldJ10kDxbxBN6QSYOr3gtI2QWIyabaqInzPJ9UbUB44KybnK4YV5u3wFzAW' },
    { name: 'Roberto Silva', role: 'Chef Executivo, Bistro 22', text: 'O layout video-first é viciante. Os clientes passam muito mais tempo explorando o cardápio e pedindo mais.', img: 'https://lh3.googleusercontent.com/aida-public/AB6AXuDyKQNO2pa98DFxYdijHY1X33ZduhN_xup0xcYiCBCP3VG1PBvaeTfuz-zHN25Z6fwc-cDf18ojDF7XKNh-iaqaDbl2VBCsqXnd2TmoCjrvy_SRI9dpJphbysBGQGMGe8H2TVYjok5aptVYxL-dUnlTh3DMzuZsz1iYNHlOcujkPdtlSbe16AetsAC9ZPK0kaKhk0je2B7kFIz_mQ98FUAPyRVIwO10cMyVVL1S-Jqsyce4b3Mt90VcGAHdkH6vxEZ2_KJi2FQwMAKK' },
];

// Before/After
const beforeItems = [
    'Cardápio de papel desatualizado e caro',
    'Cliente indeciso sem saber o que pedir',
    'Zero dados sobre preferências',
    'Impressão cara, trabalhosa e lenta',
    'Sem destaque dos pratos mais lucrativos',
    'Concorrência te engolindo dia após dia',
];
const afterItems = [
    'Cardápio digital sempre atualizado em tempo real',
    'Vídeos que vendem o prato por você',
    'Métricas detalhadas de comportamento',
    'QR Code ilimitado e gratuito',
    'Stories para destacar o que dá mais lucro',
    'Você domina o mercado local',
];

// Target audiences
const audiences = [
    'Restaurantes', 'Hamburguerias', 'Pizzarias', 'Bares e Pubs',
    'Cafeterias', 'Food Trucks', 'Steakhouses',
    'Sorveterias', 'Padarias', 'Açaiterias', 'Eventos e Buffets',
];

// Pricing
const pricingFeatures = [
    'Cardápio Video-First Ilimitado',
    'QR Codes Dinâmicos Ilimitados',
    'Dashboard de Métricas Avançadas',
    'Suporte Prioritário 24/7',
    'Integração com WhatsApp',
    'Vídeos dos pratos',
];

// FAQ
const faqs = [
    { q: 'Preciso gravar vídeos profissionais?', a: 'Não. Vídeos simples feitos com celular já convertem muito. O importante é mostrar seu prato de forma real e atrativa.' },
    { q: 'O QR Code já vem pronto?', a: 'Sim. QR Codes ilimitados gerados automaticamente, personalizáveis com suas cores. Baixe em PNG ou SVG para imprimir.' },
    { q: 'Posso editar categorias e imagens?', a: 'Total controle. Adicione, edite ou remova categorias, imagens e vídeos quando quiser. Tudo em tempo real.' },
    { q: 'Quantos pratos posso adicionar?', a: 'No plano Pro, itens ilimitados. Adicione quantos pratos quiser, com foto e vídeo para cada um.' },
    { q: 'Preciso instalar algo?', a: 'Nada. ClicaFood é 100% web. Funciona no navegador do celular e computador. Sem app necessário.' },
    { q: 'Tem métricas detalhadas?', a: 'Sim. Visualizações do cardápio, itens mais clicados, vídeos mais assistidos. Dados em tempo real para decisões com base em números.' },
];
const openFaq = ref(0);
const toggleFaq = (i) => { openFaq.value = openFaq.value === i ? null : i; };
</script>

<template>
    <div class="min-h-screen bg-[#f8f9ff] font-body text-[#121c2a] antialiased scroll-smooth">
        <Head title="ClicaFood - Cardápio Digital com Vídeo | Domine o Mercado" />

        <!-- NAV -->
        <nav class="fixed top-0 left-0 right-0 z-50 transition-all duration-300"
             :class="scrolled ? 'landing-glass shadow-sm' : ''">
            <div class="flex justify-between items-center px-6 py-4 max-w-7xl mx-auto">
                <a href="/" class="text-2xl font-black text-red-600 tracking-tighter font-headline">Clicafood</a>
                <div class="hidden md:flex items-center gap-8">
                    <a class="text-white/80 hover:text-white transition-colors font-headline text-sm" href="#recursos">Benefícios</a>
                    <a class="text-white/80 hover:text-white transition-colors font-headline text-sm" href="#precos">Planos</a>
                </div>
                <div class="flex items-center gap-4">
                    <a href="/login" class="hidden sm:inline text-white/80 hover:text-white text-sm font-semibold font-headline transition-colors">Entrar</a>
                    <a href="/register" class="landing-btn-primary text-sm py-2.5 px-6 font-bold font-headline">Começar Agora</a>
                </div>
            </div>
        </nav>

        <!-- HERO -->
        <section class="relative pt-32 pb-20 overflow-hidden min-h-[90vh] flex items-center">
            <div class="absolute inset-0 z-0">
                <div class="absolute inset-0 bg-[#121c2a]/75 z-10"></div>
                <video autoplay muted loop playsinline class="w-full h-full object-cover"
                    poster="https://lh3.googleusercontent.com/aida-public/AB6AXuBtxc_nKsRJhDhtw_RmM3t-RToqEr8KY7SfQGhuQtnfQKiCwt6j5f2jjlAj1J2jaDsefqnG9eU0m-w8nWenRGVXVPng1btsue8awpHmJNLDJ-tdMMyu_uMxRztBhzj02QpEWTizIhg0VohBgw3xvTBBc5j8TAV8BL07bOkRtE6GVFsE9ZOtokyJan-bqPxAqj3UgqPB8ZAxhG4y-oG4R5AWnwmaEshEm6ipva99B3UlWCKqfTfRbeOkfJ8u4TaSqFnnV3cQIZduQkkl">
                    <source src="https://pub-18e3929bc28243d48bdae811aaf40664.r2.dev/videos/a60d0462-57f9-4a1e-b224-29a6685946f0.mp4" type="video/mp4" />
                </video>
            </div>
            <div class="max-w-4xl mx-auto px-6 relative z-20 text-center">
                <h1 class="font-headline text-5xl sm:text-6xl md:text-7xl lg:text-8xl font-extrabold leading-[1.05] tracking-tighter mb-8">
                    <span class="word-reveal inline-block" :class="{ 'word-revealed': heroReady }" style="--delay: 0">Domine</span>
                    <span class="word-reveal inline-block" :class="{ 'word-revealed': heroReady }" style="--delay: 1">a</span>
                    <span class="word-reveal inline-block" :class="{ 'word-revealed': heroReady }" style="--delay: 2">fome,</span>
                    <br class="hidden sm:block" />
                    <span class="word-reveal inline-block text-[#db3327]" :class="{ 'word-revealed': heroReady }" style="--delay: 3">controle</span>
                    <span class="word-reveal inline-block text-[#db3327]" :class="{ 'word-revealed': heroReady }" style="--delay: 4">o</span>
                    <span class="word-reveal inline-block text-[#db3327]" :class="{ 'word-revealed': heroReady }" style="--delay: 5">pedido,</span>
                    <br class="hidden sm:block" />
                    <span class="word-reveal inline-block" :class="{ 'word-revealed': heroReady }" style="--delay: 6">destrua</span>
                    <span class="word-reveal inline-block" :class="{ 'word-revealed': heroReady }" style="--delay: 7">a</span>
                    <span class="word-reveal inline-block" :class="{ 'word-revealed': heroReady }" style="--delay: 8">concorrência.</span>
                </h1>
                <p class="text-lg md:text-xl text-white/80 max-w-2xl mx-auto mb-10 leading-relaxed">
                    Seu cardápio deixa de ser um catálogo sem graça. Vídeos despertam desejo imediato e transformam visualizações em pedidos.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center mb-10">
                    <a href="/register" class="landing-btn-primary text-lg py-4 px-10 font-bold shadow-lg shadow-[#b71511]/25">
                        Quero dominar meu cardápio
                    </a>
                    <a href="#explorar" class="bg-white/10 backdrop-blur-md text-white border border-white/20 px-10 py-4 rounded-full font-headline font-bold text-lg hover:bg-white/20 transition-all inline-flex items-center justify-center">
                        Ver demonstração
                    </a>
                </div>

                <!-- Social proof -->
                <div class="flex flex-col sm:flex-row items-center justify-center gap-6">
                    <div class="flex items-center gap-3">
                        <div class="flex -space-x-3">
                            <img v-for="t in testimonials" :key="t.name" :src="t.img" :alt="t.name" class="w-10 h-10 rounded-full border-2 border-white/20 object-cover" />
                        </div>
                        <span class="text-sm text-white/70"><strong class="text-white">5.000+</strong> empresas já usam</span>
                    </div>
                    <div class="flex items-center gap-2 text-sm text-white/70">
                        <div class="flex gap-0.5">
                            <span class="material-symbols-outlined text-amber-400 text-base" style="font-variation-settings: 'FILL' 1;" v-for="n in 5" :key="n">star</span>
                        </div>
                        <span><strong class="text-white">4,8/5</strong> baseado em <strong class="text-white">1.247</strong> avaliações</span>
                    </div>
                </div>
            </div>
        </section>

        <!-- EXPERIENCE SECTION (Mobile Mockup) -->
        <section class="py-24 landing-dark-gradient overflow-hidden relative reveal-section" id="explorar">
            <div class="max-w-7xl mx-auto px-6 grid md:grid-cols-2 gap-16 items-center">
                <div class="order-2 md:order-1 flex justify-center">
                    <div class="relative w-80 h-[650px] bg-slate-900 rounded-[3rem] border-[10px] border-slate-800 landing-iphone overflow-hidden scale-110 md:scale-100 lg:scale-110">
                        <div class="absolute top-0 left-1/2 -translate-x-1/2 w-36 h-7 bg-slate-800 rounded-b-3xl z-30"></div>
                        <div class="h-full w-full relative overflow-hidden">
                            <div v-for="(product, idx) in mockupProducts" :key="product.name"
                                 class="absolute inset-0 h-full w-full transition-transform duration-500 ease-in-out"
                                 :class="idx === mockupIndex ? 'translate-y-0 z-[2]' : idx === prevMockupIndex ? '-translate-y-full z-[2]' : 'translate-y-full z-[1]'">
                                <video autoplay muted loop playsinline class="h-full w-full object-cover" :poster="product.thumb" :src="product.video"></video>
                                <div class="absolute inset-0 bg-gradient-to-b from-black/30 via-transparent to-black/70 pointer-events-none"></div>
                                <div class="absolute bottom-0 left-0 right-0">
                                    <div class="bg-gradient-to-t from-black via-black/70 to-transparent pt-16 px-5 pb-6">
                                        <h2 class="font-bold text-white text-2xl leading-tight drop-shadow-lg">{{ product.name }}</h2>
                                        <p class="text-sm text-gray-200/80 mt-2 leading-relaxed line-clamp-3">{{ product.desc }}<span class="text-white/40 text-xs ml-1">...mais</span></p>
                                        <div class="flex items-center gap-3 mt-3">
                                            <span class="font-bold text-xl text-[#db3327]">R$ {{ product.price }}</span>
                                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-medium bg-white/10 text-white/60">Massas</span>
                                            <span class="px-2 py-0.5 rounded-full text-[10px] font-medium flex items-center gap-1 bg-[#db3327]/30 text-[#db3327]">
                                                <svg class="w-2.5 h-2.5" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg> Vídeo
                                            </span>
                                        </div>
                                        <div class="flex items-center justify-center gap-4 mt-3 text-[10px] text-white/25">
                                            <span>↕ deslize para mais</span><span>·</span><span>↔ deslize para categorias</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="absolute top-12 right-4 z-20 w-8 h-8 rounded-full bg-black/40 backdrop-blur-sm flex items-center justify-center text-white/60">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M16.5 12c0-1.77-1.02-3.29-2.5-4.03v2.21l2.45 2.45c.03-.2.05-.41.05-.63zm2.5 0c0 .94-.2 1.82-.54 2.64l1.51 1.51A8.796 8.796 0 0021 12c0-4.28-2.99-7.86-7-8.77v2.06c2.89.86 5 3.54 5 6.71zM4.27 3L3 4.27 7.73 9H3v6h4l5 5v-6.73l4.25 4.25c-.67.52-1.42.93-2.25 1.18v2.06a8.99 8.99 0 003.69-1.81L19.73 21 21 19.73l-9-9L4.27 3zM12 4L9.91 6.09 12 8.18V4z"/></svg>
                            </div>
                            <div class="absolute right-4 bottom-48 z-20">
                                <span class="text-xs text-white/30 font-mono">{{ mockupIndex + 1 }}/{{ mockupProducts.length }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="order-1 md:order-2 text-white">
                    <span class="text-[#db3327] font-bold tracking-[0.2em] uppercase text-sm mb-4 block">Formato Imersivo</span>
                    <h2 class="font-headline text-4xl md:text-6xl font-extrabold mb-8 leading-tight">A Experiência que seus Clientes Amam</h2>
                    <div class="space-y-8">
                        <div class="flex gap-6">
                            <div class="flex-shrink-0 w-12 h-12 rounded-full bg-white/10 flex items-center justify-center text-[#db3327]">
                                <span class="material-symbols-outlined">bolt</span>
                            </div>
                            <div>
                                <h4 class="font-headline font-bold text-xl mb-2">Engajamento Instantâneo</h4>
                                <p class="text-white/60 leading-relaxed">Utilizamos o mesmo gatilho mental das redes sociais mais populares do mundo para prender a atenção do seu cliente.</p>
                            </div>
                        </div>
                        <div class="flex gap-6">
                            <div class="flex-shrink-0 w-12 h-12 rounded-full bg-white/10 flex items-center justify-center text-[#db3327]">
                                <span class="material-symbols-outlined">touch_app</span>
                            </div>
                            <div>
                                <h4 class="font-headline font-bold text-xl mb-2">Navegação Intuitiva</h4>
                                <p class="text-white/60 leading-relaxed">Scroll vertical infinito. Seus clientes já são especialistas no Clicafood antes mesmo do primeiro pedido.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- FEATURES -->
        <section class="py-24 bg-[#eff4ff] reveal-section" id="recursos">
            <div class="max-w-7xl mx-auto px-6">
                <div class="text-center mb-16">
                    <span class="text-[#b71511] font-bold tracking-widest uppercase text-xs">Por que o Clicafood domina</span>
                    <h2 class="font-headline text-4xl md:text-5xl font-extrabold mt-3 text-[#121c2a]">Tudo que um cardápio comum não consegue ser.</h2>
                </div>
                <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-8">
                    <div v-for="feature in features" :key="feature.title"
                         class="bg-white p-8 rounded-3xl transition-transform hover:-translate-y-2 duration-300">
                        <div class="w-12 h-12 rounded-2xl bg-[#b71511]/10 flex items-center justify-center text-[#b71511] mb-6">
                            <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">{{ feature.icon }}</span>
                        </div>
                        <h3 class="font-headline font-bold text-xl mb-3">{{ feature.title }}</h3>
                        <p class="text-[#5c6274] text-sm leading-relaxed">{{ feature.desc }}</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- STATS (Resultados Insanos) -->
        <section id="numbers" class="py-24 bg-[#121c2a] text-white overflow-hidden relative reveal-section">
            <div class="absolute top-0 right-0 w-1/3 h-full bg-[#b71511]/5 blur-[120px] rounded-full"></div>
            <div class="max-w-7xl mx-auto px-6 relative z-10">
                <div class="text-center mb-16">
                    <span class="text-[#db3327] font-bold tracking-widest uppercase text-xs">Resultados Insanos</span>
                    <h2 class="font-headline text-4xl md:text-5xl font-extrabold mt-3">Números que falam por si.</h2>
                </div>
                <div class="grid grid-cols-2 md:grid-cols-5 gap-8 text-center">
                    <div v-for="c in counters" :key="c.label">
                        <div class="font-headline text-5xl md:text-6xl font-black text-[#db3327] mb-2">{{ c.prefix }}{{ c.current }}{{ c.suffix }}</div>
                        <p class="text-white/50 font-headline uppercase tracking-wider text-xs">{{ c.label }}</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- PÚBLICO ALVO -->
        <section class="py-20 bg-[#f8f9ff] reveal-section">
            <div class="max-w-4xl mx-auto px-6 text-center">
                <span class="text-[#b71511] font-bold tracking-widest uppercase text-xs">Público-Alvo</span>
                <h2 class="font-headline text-3xl md:text-4xl font-extrabold mt-3 mb-4">Feito para quem quer dominar</h2>
                <p class="text-[#5c6274] mb-10 text-lg">Não importa o tamanho. Se você vende comida, o ClicaFood é para você.</p>
                <div class="flex flex-wrap gap-3 justify-center">
                    <span v-for="a in audiences" :key="a"
                          class="px-5 py-2.5 bg-white border border-[#e5beb8]/20 rounded-full text-sm text-[#5c6274] font-medium hover:border-[#b71511]/40 hover:text-[#b71511] transition-all duration-200 cursor-default">
                        {{ a }}
                    </span>
                </div>
            </div>
        </section>

        <!-- ANTES vs DEPOIS -->
        <section class="py-24 bg-[#eff4ff] reveal-section">
            <div class="max-w-4xl mx-auto px-6">
                <div class="text-center mb-14">
                    <h2 class="font-headline text-3xl md:text-4xl font-extrabold">
                        <span class="text-[#5c6274]">Sem ClicaFood</span> vs <span class="text-[#db3327]">Com ClicaFood</span>
                    </h2>
                </div>
                <div class="grid md:grid-cols-2 gap-8">
                    <div class="bg-white p-8 rounded-3xl border border-red-200/50">
                        <h4 class="font-headline font-bold text-red-400 text-lg mb-5 flex items-center gap-2">
                            <span class="material-symbols-outlined">close</span> Sem ClicaFood
                        </h4>
                        <ul class="space-y-3">
                            <li v-for="item in beforeItems" :key="item" class="flex items-start gap-3 text-sm text-[#5c6274]">
                                <span class="material-symbols-outlined text-red-400 text-base mt-0.5 shrink-0">close</span>
                                <span>{{ item }}</span>
                            </li>
                        </ul>
                    </div>
                    <div class="bg-white p-8 rounded-3xl border border-emerald-200/50">
                        <h4 class="font-headline font-bold text-emerald-500 text-lg mb-5 flex items-center gap-2">
                            <span class="material-symbols-outlined">check</span> Com ClicaFood
                        </h4>
                        <ul class="space-y-3">
                            <li v-for="item in afterItems" :key="item" class="flex items-start gap-3 text-sm text-[#121c2a]">
                                <span class="material-symbols-outlined text-emerald-500 text-base mt-0.5 shrink-0">check</span>
                                <span>{{ item }}</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>

        <!-- TESTIMONIALS -->
        <section class="py-24 bg-[#f8f9ff] reveal-section">
            <div class="max-w-7xl mx-auto px-6">
                <div class="text-center mb-16">
                    <span class="text-[#b71511] font-bold tracking-widest uppercase text-xs">Depoimentos</span>
                    <h2 class="font-headline text-4xl font-extrabold text-[#121c2a] mt-3">O que os restaurantes dizem.</h2>
                    <p class="text-[#5c6274] mt-4">Resultados reais de donos de restaurantes como você.</p>
                </div>
                <div class="grid md:grid-cols-3 gap-8">
                    <div v-for="t in testimonials" :key="t.name" class="bg-white p-10 rounded-3xl shadow-sm border border-[#e5beb8]/10">
                        <div class="flex gap-1 text-[#b71511] mb-6">
                            <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;" v-for="n in 5" :key="n">star</span>
                        </div>
                        <p class="text-[#121c2a] text-lg italic mb-8 font-body leading-relaxed">"{{ t.text }}"</p>
                        <div class="flex items-center gap-4">
                            <img :alt="t.name" :src="t.img" class="w-12 h-12 rounded-full bg-slate-200 object-cover" />
                            <div>
                                <p class="font-bold text-[#121c2a]">{{ t.name }}</p>
                                <p class="text-xs text-[#5c6274]">{{ t.role }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- PRICING -->
        <section class="py-24 bg-[#e6eeff] reveal-section" id="precos">
            <div class="max-w-7xl mx-auto px-6">
                <div class="max-w-3xl mx-auto text-center mb-16">
                    <h2 class="font-headline text-4xl font-extrabold text-[#121c2a]">O único plano que você precisa para dominar.</h2>
                    <p class="text-[#5c6274] mt-4">Preço transparente. Sem surpresas. Cancele quando quiser.</p>
                </div>
                <div class="max-w-lg mx-auto bg-white rounded-[2.5rem] p-12 shadow-xl relative overflow-hidden">
                    <div class="absolute top-0 right-0 landing-btn-primary px-8 py-2 rounded-bl-3xl font-bold text-sm pointer-events-none">-20% OFF</div>
                    <h3 class="font-headline font-bold text-3xl mb-2">Plano Pro</h3>
                    <p class="text-[#5c6274] mb-8">Tudo o que você precisa para dominar.</p>
                    <div class="flex items-baseline gap-2 mb-2">
                        <span class="text-sm text-[#5c6274] line-through">R$ 197/mês</span>
                    </div>
                    <div class="flex items-baseline gap-2 mb-10">
                        <span class="text-2xl font-bold text-[#121c2a]">R$</span>
                        <span class="text-6xl font-black text-[#121c2a] tracking-tighter">97</span>
                        <span class="text-[#5c6274] font-medium">/mês</span>
                    </div>
                    <ul class="space-y-4 mb-10">
                        <li v-for="feature in pricingFeatures" :key="feature" class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-[#b71511]" style="font-variation-settings: 'FILL' 1;">check_circle</span>
                            <span class="text-[#121c2a] font-medium">{{ feature }}</span>
                        </li>
                    </ul>
                    <a href="/register" class="landing-btn-primary w-full py-5 rounded-2xl text-xl font-bold shadow-lg shadow-[#b71511]/20 block text-center">
                        Quero dominar
                    </a>
                    <p class="text-xs text-[#5c6274] mt-4 text-center">7 dias grátis. Cancele quando quiser. Sem fidelidade.</p>
                </div>
            </div>
        </section>

        <!-- FAQ -->
        <section class="py-24 bg-[#f8f9ff] reveal-section">
            <div class="max-w-3xl mx-auto px-6">
                <div class="text-center mb-14">
                    <span class="text-[#b71511] font-bold tracking-widest uppercase text-xs">Dúvidas Frequentes</span>
                    <h2 class="font-headline text-3xl font-extrabold mt-3">Perguntas diretas. Respostas diretas.</h2>
                </div>
                <div class="space-y-4">
                    <div v-for="(faq, i) in faqs" :key="i" class="bg-white rounded-2xl cursor-pointer shadow-sm" @click="toggleFaq(i)">
                        <div class="flex justify-between items-center p-6">
                            <span class="font-headline font-bold text-[#121c2a]">{{ faq.q }}</span>
                            <span class="material-symbols-outlined transition-transform duration-300 text-[#5c6274]" :class="{ 'rotate-180': openFaq === i }">expand_more</span>
                        </div>
                        <transition enter-active-class="transition-all duration-300 ease-out" enter-from-class="max-h-0 opacity-0"
                                    enter-to-class="max-h-40 opacity-100" leave-active-class="transition-all duration-200 ease-in"
                                    leave-from-class="max-h-40 opacity-100" leave-to-class="max-h-0 opacity-0">
                            <div v-if="openFaq === i" class="px-6 pb-6 text-[#5c6274] leading-relaxed overflow-hidden">{{ faq.a }}</div>
                        </transition>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA FINAL -->
        <section class="py-24 bg-[#121c2a] relative overflow-hidden reveal-section">
            <div class="absolute inset-0 bg-gradient-to-b from-[#b71511]/10 via-[#b71511]/5 to-transparent pointer-events-none"></div>
            <div class="max-w-3xl mx-auto text-center px-6 relative z-10">
                <h2 class="font-headline text-4xl md:text-5xl lg:text-6xl font-extrabold text-white mb-6 leading-tight">
                    Pare de perder vendas.<br/>
                    <span class="text-[#db3327]">Comece a dominar agora.</span>
                </h2>
                <p class="text-white/60 mb-10 text-lg max-w-xl mx-auto">
                    Junte-se a mais de 5.000 restaurantes que já transformaram seus cardápios em máquinas de venda.
                </p>
                <a href="/register" class="landing-btn-primary text-lg py-5 px-12 font-bold shadow-lg shadow-[#b71511]/25 inline-block">
                    Começar a dominar agora
                </a>
                <div class="flex justify-center gap-10 mt-12 text-sm text-white/40">
                    <div>
                        <strong class="text-white text-2xl font-headline font-black block">5k+</strong>
                        <span>usuários ativos</span>
                    </div>
                    <div>
                        <strong class="text-white text-2xl font-headline font-black block">4.8/5</strong>
                        <span>avaliação média</span>
                    </div>
                    <div>
                        <strong class="text-white text-2xl font-headline font-black block">98%</strong>
                        <span>satisfação</span>
                    </div>
                </div>
            </div>
        </section>

        <!-- FOOTER -->
        <footer class="bg-[#f5f5f4] border-t border-gray-200">
            <div class="max-w-7xl mx-auto px-8 pt-12 pb-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-10 mb-10">
                    <!-- Brand -->
                    <div>
                        <a href="/" class="text-lg font-black tracking-tighter font-headline text-[#121c2a] uppercase">Clicafood</a>
                        <p class="text-xs text-[#5c6274] mt-3 leading-relaxed max-w-xs uppercase">
                            A maior plataforma de cardápios cinéticos do Brasil. Transformando seu cardápio em espetáculo.
                        </p>
                        <div class="flex gap-3 mt-4">
                            <!-- TikTok -->
                            <a href="#" class="text-[#5c6274] hover:text-[#121c2a] transition-colors">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M19.59 6.69a4.83 4.83 0 01-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 01-2.88 2.5 2.89 2.89 0 01-2.89-2.89 2.89 2.89 0 012.89-2.89c.28 0 .54.04.79.1V9.01a6.27 6.27 0 00-.79-.05 6.34 6.34 0 00-6.34 6.34 6.34 6.34 0 006.34 6.34 6.34 6.34 0 006.33-6.34V8.75a8.18 8.18 0 004.77 1.52V6.84a4.85 4.85 0 01-1-.15z"/></svg>
                            </a>
                            <!-- Instagram -->
                            <a href="#" class="text-[#5c6274] hover:text-[#121c2a] transition-colors">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>
                            </a>
                        </div>
                    </div>
                    <!-- Recursos -->
                    <div>
                        <h4 class="font-headline font-bold text-xs uppercase tracking-widest text-[#121c2a] mb-4">Recursos</h4>
                        <ul class="space-y-2 text-sm text-[#5c6274]">
                            <li><a href="#recursos" class="hover:text-[#121c2a] transition-colors">Features</a></li>
                            <li><a href="#precos" class="hover:text-[#121c2a] transition-colors">Pricing</a></li>
                            <li><a href="#explorar" class="hover:text-[#121c2a] transition-colors">Solutions</a></li>
                        </ul>
                    </div>
                    <!-- Legal -->
                    <div>
                        <h4 class="font-headline font-bold text-xs uppercase tracking-widest text-[#121c2a] mb-4">Legal</h4>
                        <ul class="space-y-2 text-sm text-[#5c6274]">
                            <li><a href="#" class="hover:text-[#121c2a] transition-colors">Privacy Policy</a></li>
                            <li><a href="#" class="hover:text-[#121c2a] transition-colors">Terms of Service</a></li>
                        </ul>
                    </div>
                </div>
                <!-- Bottom bar -->
                <div class="border-t border-gray-200 pt-6 flex flex-col md:flex-row justify-between items-center gap-4">
                    <p class="text-xs text-[#5c6274] uppercase">© 2024 Clicafood. Kinetic Editorial Design.</p>
                    <div class="flex gap-6 text-xs text-[#5c6274] uppercase">
                        <a href="#" class="hover:text-[#121c2a] transition-colors">Instagram</a>
                        <a href="#" class="hover:text-[#121c2a] transition-colors">LinkedIn</a>
                    </div>
                </div>
            </div>
        </footer>
    </div>
</template>

<style scoped>
.landing-glass {
    background: rgba(18, 28, 42, 0.8);
    backdrop-filter: blur(12px);
}

.landing-btn-primary {
    @apply inline-flex items-center justify-center text-white rounded-full transition-all duration-300;
    background: linear-gradient(135deg, #b71511 0%, #db3327 100%);
}
.landing-btn-primary:hover {
    opacity: 0.9;
    transform: scale(1.05);
}
.landing-btn-primary:active {
    transform: scale(0.95);
}

.landing-iphone {
    box-shadow: 0px 40px 80px rgba(0, 0, 0, 0.3);
}

.landing-dark-gradient {
    background: radial-gradient(circle at top right, #1a2638, #121c2a);
}

/* Word reveal animation */
.word-reveal {
    color: white;
    background: linear-gradient(to right, currentColor 50%, rgba(255,255,255,0.2) 50%);
    background-size: 200% 100%;
    background-position: 100% 0;
    -webkit-background-clip: text;
    background-clip: text;
    -webkit-text-fill-color: transparent;
    transition: background-position 0.6s cubic-bezier(0.65, 0, 0.35, 1);
    transition-delay: calc(var(--delay) * 0.15s);
    margin-right: 0.2em;
}
.word-reveal.word-revealed {
    background-position: 0 0;
}

.reveal-section {
    opacity: 0;
    transform: translateY(30px);
    transition: opacity 0.8s ease-out, transform 0.8s ease-out;
}

.reveal-section.revealed {
    opacity: 1;
    transform: translateY(0);
}
</style>
