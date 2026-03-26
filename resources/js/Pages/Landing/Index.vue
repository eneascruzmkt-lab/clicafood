<script setup>
import { ref, onMounted, onUnmounted } from 'vue';
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
onUnmounted(() => window.removeEventListener('scroll', handleScroll));

// Animated counters
const counters = ref([
    { target: 73, current: 0, suffix: '%', label: 'Mais Pedidos com Video', prefix: '+' },
    { target: 2.4, current: 0, suffix: 'x', label: 'Aumento no Ticket Medio', prefix: '' },
    { target: 89, current: 0, suffix: '%', label: 'Taxa de Engajamento', prefix: '' },
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

// Scroll reveal animation
onMounted(() => {
    const counterObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                animateCounters();
                counterObserver.disconnect();
            }
        });
    });
    const numbersEl = document.getElementById('numbers');
    if (numbersEl) counterObserver.observe(numbersEl);

    const revealObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('revealed');
            }
        });
    }, { threshold: 0.1 });

    document.querySelectorAll('.reveal-section').forEach(el => {
        revealObserver.observe(el);
    });
});

// Features data
const features = [
    { icon: 'videocam', title: 'Videos que Vendem', desc: 'Substitua fotos estaticas por videos imersivos que despertam o desejo imediato do cliente.' },
    { icon: 'auto_stories', title: 'Stories dos Pratos', desc: 'Experiencia de navegacao familiar. Seus clientes ja sabem usar antes mesmo de abrir.' },
    { icon: 'analytics', title: 'Metricas Real Time', desc: 'Saiba exatamente qual video esta gerando mais pedidos em tempo real no seu dashboard.' },
    { icon: 'qr_code_2', title: 'QR Code Ilimitado', desc: 'Gerencie multiplas mesas e pontos de venda com codigos dinamicos e inteligentes.' },
];

// Testimonials
const testimonials = [
    { name: 'Carlos Mendes', role: 'CEO, Meat & Co.', text: 'O Clicafood mudou a forma como nossos clientes interagem com o menu. O aumento nas vendas de sobremesas foi instantaneo.', img: 'https://lh3.googleusercontent.com/aida-public/AB6AXuAnyl_ej5aU1dWChtiH83guDZdC9XE8md0qeM-w6wemmsy61D7i1F9mX4B5dh6OtrCaHWfNskLPkXQp8uNc6gTACUPv0zykQnVHECsXL9b1Kqncf67Qmv5SmYLNSJVUfEYrrhjRpUvMO-izuuU6Bi8wN7sc1MUxkcg6zIYVwwS176I6CSpmwNPVOg18ce-l4oiuik3bQPZifVMMPv6tgB1u0LqfqNE01rDI8Epq0Bs6gm10mbpOpjXfiifpfKgdP77Up6CrOJMikaAM' },
    { name: 'Ana Luiza', role: 'Fundadora, Sushi Flow', text: 'A facilidade de atualizar os pratos e as metricas em tempo real nos deram um controle que nunca tivemos antes.', img: 'https://lh3.googleusercontent.com/aida-public/AB6AXuAhPnhLZ-GraQvX8ZNnHpI3Ro9uYj7x2Xc-wK_NeS-_szCKPxcROiNZCp5xjAMZlrjKojcFOzEnWlilOK6R5g4Tqgrpva97oQf0lK68zIkBEDcLMo2adj2Ukdd4d0NsSoTRJZDYqHgzQz0i6wqnHkIPBysxfncEjAKFL4C6izg_XA0qDEkC-28dwInHx2XvmyqpuITkeOExSg0LvzQFRldJ10kDxbxBN6QSYOr3gtI2QWIyabaqInzPJ9UbUB44KybnK4YV5u3wFzAW' },
    { name: 'Roberto Silva', role: 'Chef Executivo, Bistro 22', text: 'O layout video-first e viciante. Os clientes passam muito mais tempo explorando o cardapio e pedindo mais.', img: 'https://lh3.googleusercontent.com/aida-public/AB6AXuDyKQNO2pa98DFxYdijHY1X33ZduhN_xup0xcYiCBCP3VG1PBvaeTfuz-zHN25Z6fwc-cDf18ojDF7XKNh-iaqaDbl2VBCsqXnd2TmoCjrvy_SRI9dpJphbysBGQGMGe8H2TVYjok5aptVYxL-dUnlTh3DMzuZsz1iYNHlOcujkPdtlSbe16AetsAC9ZPK0kaKhk0je2B7kFIz_mQ98FUAPyRVIwO10cMyVVL1S-Jqsyce4b3Mt90VcGAHdkH6vxEZ2_KJi2FQwMAKK' },
];

// Pricing features
const pricingFeatures = [
    'Cardapio Video-First Ilimitado',
    'QR Codes Dinamicos Ilimitados',
    'Dashboard de Metricas Avancadas',
    'Suporte Prioritario 24/7',
    'Integracao com WhatsApp',
];

// FAQ
const faqs = [
    { q: 'Preciso de equipamentos profissionais para os videos?', a: 'Nao! Um smartphone moderno e tudo o que voce precisa. Nossa plataforma otimiza automaticamente seus videos para garantir o carregamento instantaneo em qualquer conexao.' },
    { q: 'Como funciona a integracao com o WhatsApp?', a: 'O pedido chega formatado e detalhado direto no seu numero, pronto para producao, sem taxas de intermediacao por pedido.' },
    { q: 'Posso cancelar a qualquer momento?', a: 'Sim. Nao temos contratos de fidelidade. Voce paga mensalmente e pode cancelar quando quiser sem multas.' },
    { q: 'Funciona para delivery?', a: 'Sim. O cardapio digital funciona perfeitamente para delivery. Seus clientes podem acessar de qualquer lugar, ver os pratos em video e decidir o pedido mais rapido.' },
    { q: 'Quantos pratos posso adicionar?', a: 'No plano Pro, itens ilimitados. Adicione quantos pratos quiser, com foto e video para cada um. Sem restricoes.' },
    { q: 'Preciso instalar algo?', a: 'Nada. ClicaFood e 100% web. Funciona no navegador do celular e computador. Seus clientes nao precisam instalar nenhum aplicativo.' },
];
const openFaq = ref(0);
const toggleFaq = (i) => { openFaq.value = openFaq.value === i ? null : i; };
</script>

<template>
    <div class="min-h-screen bg-[#f8f9ff] font-body text-[#121c2a] antialiased scroll-smooth">
        <Head title="ClicaFood - Cardapio Digital com Video | Domine o Mercado" />

        <!-- NAV -->
        <nav
            class="fixed top-0 w-full z-50 transition-all duration-300"
            :class="scrolled ? 'landing-glass shadow-sm' : 'bg-transparent'"
        >
            <div class="flex justify-between items-center px-6 py-4 max-w-7xl mx-auto">
                <a href="/" class="text-2xl font-black text-red-600 tracking-tighter font-headline">Clicafood</a>
                <div class="hidden md:flex items-center gap-8">
                    <a class="text-red-600 font-bold border-b-2 border-red-600 pb-1 font-headline text-sm tracking-tight" href="#explorar">Explorar</a>
                    <a class="text-slate-600 hover:text-red-500 transition-colors font-headline text-sm tracking-tight" href="#recursos">Recursos</a>
                    <a class="text-slate-600 hover:text-red-500 transition-colors font-headline text-sm tracking-tight" href="#precos">Precos</a>
                </div>
                <div class="flex items-center gap-4">
                    <a href="/login" class="hidden sm:inline text-slate-600 hover:text-red-500 text-sm font-semibold font-headline transition-colors">Entrar</a>
                    <a href="/register" class="landing-btn-primary text-sm py-2.5 px-6 font-bold font-headline">Comecar Agora</a>
                </div>
            </div>
        </nav>

        <!-- HERO -->
        <section class="relative pt-32 pb-20 overflow-hidden min-h-[80vh] flex items-center">
            <div class="absolute inset-0 z-0">
                <div class="absolute inset-0 bg-[#121c2a]/75 z-10"></div>
                <video
                    autoplay
                    muted
                    loop
                    playsinline
                    class="w-full h-full object-cover"
                    poster="https://lh3.googleusercontent.com/aida-public/AB6AXuBtxc_nKsRJhDhtw_RmM3t-RToqEr8KY7SfQGhuQtnfQKiCwt6j5f2jjlAj1J2jaDsefqnG9eU0m-w8nWenRGVXVPng1btsue8awpHmJNLDJ-tdMMyu_uMxRztBhzj02QpEWTizIhg0VohBgw3xvTBBc5j8TAV8BL07bOkRtE6GVFsE9ZOtokyJan-bqPxAqj3UgqPB8ZAxhG4y-oG4R5AWnwmaEshEm6ipva99B3UlWCKqfTfRbeOkfJ8u4TaSqFnnV3cQIZduQkkl"
                >
                    <source src="https://pub-18e3929bc28243d48bdae811aaf40664.r2.dev/videos/a60d0462-57f9-4a1e-b224-29a6685946f0.mp4" type="video/mp4" />
                </video>
            </div>
            <div class="max-w-7xl mx-auto px-6 relative z-20">
                <div class="max-w-3xl">
                    <h1 class="font-headline text-5xl md:text-7xl font-extrabold text-white leading-tight tracking-tighter mb-6">
                        Domine a Fome,<br/>
                        <span class="text-[#db3327]">Controle o Pedido.</span>
                    </h1>
                    <p class="text-lg text-white/90 mb-10 max-w-lg font-body leading-relaxed">
                        Transforme seu cardapio em uma experiencia cinematografica. O primeiro menu video-first projetado para converter scrolls em vendas instantaneas.
                    </p>
                    <div class="flex flex-wrap gap-4">
                        <a href="/register" class="landing-btn-primary text-lg py-4 px-8 font-bold">
                            Comecar Gratis
                        </a>
                        <a href="#recursos" class="bg-white/10 backdrop-blur-md text-white border border-white/20 px-8 py-4 rounded-full font-headline font-bold text-lg hover:bg-white/20 transition-all inline-flex items-center justify-center">
                            Ver Beneficios
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <!-- EXPERIENCE SECTION (Mobile Mockup Focus) -->
        <section class="py-24 landing-dark-gradient overflow-hidden relative reveal-section" id="explorar">
            <div class="max-w-7xl mx-auto px-6 grid md:grid-cols-2 gap-16 items-center">
                <div class="order-2 md:order-1 flex justify-center">
                    <div class="relative w-80 h-[650px] bg-slate-900 rounded-[3rem] border-[10px] border-slate-800 landing-iphone overflow-hidden scale-110 md:scale-100 lg:scale-110">
                        <div class="absolute top-0 left-1/2 -translate-x-1/2 w-36 h-7 bg-slate-800 rounded-b-3xl z-30"></div>
                        <div class="h-full w-full relative">
                            <!-- Video do produto real (Carbonara - Nonna Titina) -->
                            <video
                                autoplay muted loop playsinline
                                class="h-full w-full object-cover"
                                poster="https://pub-18e3929bc28243d48bdae811aaf40664.r2.dev/thumbnails/mCIHrtUL5z9JvJ8PBSAHPx05dbJW1C3I1JQ6dxaA.jpg"
                            >
                                <source src="https://pub-18e3929bc28243d48bdae811aaf40664.r2.dev/videos/a60d0462-57f9-4a1e-b224-29a6685946f0.mp4" type="video/mp4" />
                            </video>

                            <!-- Gradient overlay (identico ao cardapio real) -->
                            <div class="absolute inset-0 bg-gradient-to-b from-black/30 via-transparent to-black/70 pointer-events-none"></div>

                            <!-- Mute icon (top right, como no real) -->
                            <div class="absolute top-12 right-4 z-20 w-8 h-8 rounded-full bg-black/40 backdrop-blur-sm flex items-center justify-center text-white/60">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M16.5 12c0-1.77-1.02-3.29-2.5-4.03v2.21l2.45 2.45c.03-.2.05-.41.05-.63zm2.5 0c0 .94-.2 1.82-.54 2.64l1.51 1.51A8.796 8.796 0 0021 12c0-4.28-2.99-7.86-7-8.77v2.06c2.89.86 5 3.54 5 6.71zM4.27 3L3 4.27 7.73 9H3v6h4l5 5v-6.73l4.25 4.25c-.67.52-1.42.93-2.25 1.18v2.06a8.99 8.99 0 003.69-1.81L19.73 21 21 19.73l-9-9L4.27 3zM12 4L9.91 6.09 12 8.18V4z"/></svg>
                            </div>

                            <!-- Counter (bottom right, como no real) -->
                            <div class="absolute right-4 bottom-48 z-20">
                                <span class="text-xs text-white/30 font-mono">3/7</span>
                            </div>

                            <!-- Bottom info (identico ao layout reels do cardapio real) -->
                            <div class="absolute bottom-0 left-0 right-0 z-10">
                                <div class="bg-gradient-to-t from-black via-black/70 to-transparent pt-16 px-5 pb-6">
                                    <h2 class="font-bold text-white text-2xl leading-tight drop-shadow-lg">Grano Duro - Piccolo</h2>
                                    <p class="text-sm text-gray-200/80 mt-2 leading-relaxed line-clamp-3">
                                        Massa tradicional italiana de trigo duro. Porcao individual (250g a 350g). Escolha 1 proteina, 4 ingredientes e 1 molho.
                                        <span class="text-white/40 text-xs ml-1">...mais</span>
                                    </p>
                                    <div class="flex items-center gap-3 mt-3">
                                        <span class="font-bold text-xl text-[#db3327]">R$ 24,90</span>
                                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-medium bg-white/10 text-white/60">Massas</span>
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-medium flex items-center gap-1 bg-[#db3327]/30 text-[#db3327]">
                                            <svg class="w-2.5 h-2.5" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg> Video
                                        </span>
                                    </div>
                                    <div class="flex items-center justify-center gap-4 mt-3 text-[10px] text-white/25">
                                        <span>↕ deslize para mais</span><span>·</span><span>↔ deslize para categorias</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="order-1 md:order-2 text-white">
                    <span class="text-[#db3327] font-bold tracking-[0.2em] uppercase text-sm mb-4 block">Formato Imersivo</span>
                    <h2 class="font-headline text-4xl md:text-6xl font-extrabold mb-8 leading-tight">A Experiencia que seus Clientes Amam</h2>
                    <div class="space-y-8">
                        <div class="flex gap-6">
                            <div class="flex-shrink-0 w-12 h-12 rounded-full bg-white/10 flex items-center justify-center text-[#db3327]">
                                <span class="material-symbols-outlined">bolt</span>
                            </div>
                            <div>
                                <h4 class="font-headline font-bold text-xl mb-2">Engajamento Instantaneo</h4>
                                <p class="text-white/60 leading-relaxed">Utilizamos o mesmo gatilho mental das redes sociais mais populares do mundo para prender a atencao do seu cliente.</p>
                            </div>
                        </div>
                        <div class="flex gap-6">
                            <div class="flex-shrink-0 w-12 h-12 rounded-full bg-white/10 flex items-center justify-center text-[#db3327]">
                                <span class="material-symbols-outlined">touch_app</span>
                            </div>
                            <div>
                                <h4 class="font-headline font-bold text-xl mb-2">Navegacao Intuitiva</h4>
                                <p class="text-white/60 leading-relaxed">Scroll vertical infinito. Seus clientes ja sao especialistas no Clicafood antes mesmo do primeiro pedido.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- FEATURES -->
        <section class="py-24 bg-[#eff4ff] reveal-section" id="recursos">
            <div class="max-w-7xl mx-auto px-6">
                <div class="mb-20">
                    <span class="text-[#b71511] font-bold tracking-widest uppercase text-xs">Tecnologia Cinetica</span>
                    <h2 class="font-headline text-4xl md:text-5xl font-extrabold mt-2 text-[#121c2a]">Destrua a Concorrencia.</h2>
                </div>
                <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-8">
                    <div
                        v-for="feature in features"
                        :key="feature.title"
                        class="bg-white p-8 rounded-3xl transition-transform hover:-translate-y-2 duration-300"
                    >
                        <div class="w-12 h-12 rounded-2xl bg-[#b71511]/10 flex items-center justify-center text-[#b71511] mb-6">
                            <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">{{ feature.icon }}</span>
                        </div>
                        <h3 class="font-headline font-bold text-xl mb-3">{{ feature.title }}</h3>
                        <p class="text-[#5c6274] text-sm leading-relaxed">{{ feature.desc }}</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- STATS -->
        <section id="numbers" class="py-24 bg-[#121c2a] text-white overflow-hidden relative reveal-section">
            <div class="absolute top-0 right-0 w-1/3 h-full bg-[#b71511]/5 blur-[120px] rounded-full"></div>
            <div class="max-w-7xl mx-auto px-6 relative z-10">
                <div class="grid md:grid-cols-3 gap-12 text-center">
                    <div v-for="c in counters" :key="c.label">
                        <div class="font-headline text-6xl md:text-7xl font-black text-[#db3327] mb-2">
                            {{ c.prefix }}{{ c.current }}{{ c.suffix }}
                        </div>
                        <p class="text-white/60 font-headline uppercase tracking-widest text-sm">{{ c.label }}</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- TESTIMONIALS -->
        <section class="py-24 bg-[#f8f9ff] reveal-section">
            <div class="max-w-7xl mx-auto px-6">
                <div class="text-center mb-16">
                    <h2 class="font-headline text-4xl font-extrabold text-[#121c2a]">Quem usa, domina.</h2>
                    <p class="text-[#5c6274] mt-4">Lideres do mercado gastronomico que ja viraram a chave.</p>
                </div>
                <div class="grid md:grid-cols-3 gap-8">
                    <div
                        v-for="t in testimonials"
                        :key="t.name"
                        class="bg-white p-10 rounded-3xl shadow-sm border border-[#e5beb8]/10"
                    >
                        <div class="flex gap-1 text-[#b71511] mb-6">
                            <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;" v-for="n in 5" :key="n">star</span>
                        </div>
                        <p class="text-[#121c2a] text-lg italic mb-8 font-body leading-relaxed">"{{ t.text }}"</p>
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-full bg-slate-200 overflow-hidden">
                                <img :alt="t.name" :src="t.img" class="w-full h-full object-cover" />
                            </div>
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
                    <h2 class="font-headline text-4xl font-extrabold text-[#121c2a]">Investimento Simples.</h2>
                    <p class="text-[#5c6274] mt-4">Preco transparente para escalar seu negocio sem surpresas.</p>
                </div>
                <div class="max-w-lg mx-auto bg-white rounded-[2.5rem] p-12 shadow-xl relative overflow-hidden">
                    <div class="absolute top-0 right-0 landing-btn-primary px-8 py-2 rounded-bl-3xl font-bold text-sm pointer-events-none">MAIS VENDIDO</div>
                    <h3 class="font-headline font-bold text-3xl mb-2">Plano Pro</h3>
                    <p class="text-[#5c6274] mb-8">Tudo o que voce precisa para dominar.</p>
                    <div class="flex items-baseline gap-2 mb-10">
                        <span class="text-2xl font-bold text-[#121c2a]">R$</span>
                        <span class="text-6xl font-black text-[#121c2a] tracking-tighter">97</span>
                        <span class="text-[#5c6274] font-medium">/mes</span>
                    </div>
                    <ul class="space-y-4 mb-10">
                        <li v-for="feature in pricingFeatures" :key="feature" class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-[#b71511]" style="font-variation-settings: 'FILL' 1;">check_circle</span>
                            <span class="text-[#121c2a] font-medium">{{ feature }}</span>
                        </li>
                    </ul>
                    <a href="/register" class="landing-btn-primary w-full py-5 rounded-2xl text-xl font-bold shadow-lg shadow-[#b71511]/20 block text-center">
                        Comecar Agora
                    </a>
                    <p class="text-xs text-[#5c6274] mt-4 text-center">7 dias gratis. Cancele quando quiser. Sem fidelidade.</p>
                </div>
            </div>
        </section>

        <!-- FAQ -->
        <section class="py-24 bg-[#f8f9ff] reveal-section">
            <div class="max-w-3xl mx-auto px-6">
                <h2 class="font-headline text-3xl font-extrabold text-center mb-12">Duvidas Frequentes</h2>
                <div class="space-y-4">
                    <div
                        v-for="(faq, i) in faqs"
                        :key="i"
                        class="bg-[#eff4ff] rounded-2xl cursor-pointer"
                        @click="toggleFaq(i)"
                    >
                        <div class="flex justify-between items-center p-6">
                            <span class="font-headline font-bold text-[#121c2a]">{{ faq.q }}</span>
                            <span
                                class="material-symbols-outlined transition-transform duration-300 text-[#5c6274]"
                                :class="{ 'rotate-180': openFaq === i }"
                            >expand_more</span>
                        </div>
                        <transition
                            enter-active-class="transition-all duration-300 ease-out"
                            enter-from-class="max-h-0 opacity-0"
                            enter-to-class="max-h-40 opacity-100"
                            leave-active-class="transition-all duration-200 ease-in"
                            leave-from-class="max-h-40 opacity-100"
                            leave-to-class="max-h-0 opacity-0"
                        >
                            <div v-if="openFaq === i" class="px-6 pb-6 text-[#5c6274] leading-relaxed overflow-hidden">
                                {{ faq.a }}
                            </div>
                        </transition>
                    </div>
                </div>
            </div>
        </section>

        <!-- FOOTER -->
        <footer class="bg-slate-50 w-full rounded-t-3xl mt-20">
            <div class="flex flex-col md:flex-row justify-between items-center px-8 py-12 max-w-7xl mx-auto gap-6">
                <div class="flex flex-col items-center md:items-start gap-2">
                    <a href="/" class="text-xl font-bold text-slate-900 font-headline">Clicafood</a>
                    <p class="text-xs text-slate-500 max-w-xs text-center md:text-left">2024 Clicafood - Gastronomia Cinetica. Todos os direitos reservados.</p>
                </div>
                <div class="flex gap-8">
                    <a class="text-slate-500 hover:text-red-500 transition-colors text-xs" href="#">Termos de Uso</a>
                    <a class="text-slate-500 hover:text-red-500 transition-colors text-xs" href="#">Privacidade</a>
                    <a class="text-slate-500 hover:text-red-500 transition-colors text-xs" href="#">Contato</a>
                    <a class="text-slate-500 hover:text-red-500 transition-colors text-xs" href="#">Trabalhe Conosco</a>
                </div>
            </div>
        </footer>

    </div>
</template>

<style scoped>
/* Landing page light theme styles - scoped to avoid affecting dashboard */
.landing-glass {
    background: rgba(255, 255, 255, 0.8);
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

.landing-video-overlay {
    background: linear-gradient(to top, rgba(18, 28, 42, 0.8) 0%, transparent 100%);
}

.landing-iphone {
    box-shadow: 0px 40px 80px rgba(0, 0, 0, 0.3);
}

.landing-dark-gradient {
    background: radial-gradient(circle at top right, #1a2638, #121c2a);
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
