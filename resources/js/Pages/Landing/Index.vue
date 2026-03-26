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
                <div class="absolute inset-0 bg-[#121c2a]/40 z-10"></div>
                <img
                    alt="Chef profissional preparando prato gourmet"
                    class="w-full h-full object-cover"
                    src="https://lh3.googleusercontent.com/aida-public/AB6AXuBtxc_nKsRJhDhtw_RmM3t-RToqEr8KY7SfQGhuQtnfQKiCwt6j5f2jjlAj1J2jaDsefqnG9eU0m-w8nWenRGVXVPng1btsue8awpHmJNLDJ-tdMMyu_uMxRztBhzj02QpEWTizIhg0VohBgw3xvTBBc5j8TAV8BL07bOkRtE6GVFsE9ZOtokyJan-bqPxAqj3UgqPB8ZAxhG4y-oG4R5AWnwmaEshEm6ipva99B3UlWCKqfTfRbeOkfJ8u4TaSqFnnV3cQIZduQkkl"
                />
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
                            <img
                                alt="App mostrando cardapio video-first"
                                class="h-full w-full object-cover"
                                src="https://lh3.googleusercontent.com/aida-public/AB6AXuAZgJEEZeeMp8p18PnpjSerN8oJBhn4siDiSsRi16ZmZRqWwpZwxjIXcy5fvLMCj3PwlMW-e6X6TY13anLDx8Jr4BUrO--9BL1kiTwxAq71UtFXfoNrRALtqLQH1MfRHAT3zlJW5I8MsJDLAXty_4dm8WCkCwvvFfgRpUoBbK_ArB3mtN8PvoT-MFnbltG0se2w-RDEKIiDbMzNmAgDr5QMzvRQ0yjcbKhwBFStETvKiJi7WbhvIpkVCTHFzqC04fxCqpPU8LEJ0cMu"
                            />
                            <div class="absolute inset-0 landing-video-overlay flex flex-col justify-end p-8 text-white">
                                <div class="flex items-center gap-2 mb-2">
                                    <span class="bg-red-600 text-[10px] font-bold px-2 py-0.5 rounded animate-pulse">AO VIVO</span>
                                    <div class="flex -space-x-2">
                                        <div class="w-6 h-6 rounded-full border border-white bg-slate-300"></div>
                                        <div class="w-6 h-6 rounded-full border border-white bg-slate-400"></div>
                                    </div>
                                    <span class="text-[10px] opacity-80">42 pessoas vendo agora</span>
                                </div>
                                <h3 class="font-headline font-bold text-2xl mb-2">Burger Artesanal X</h3>
                                <p class="text-sm opacity-90 mb-6">Pao brioche, 180g de carne angus, queijo cheddar derretido e cebola caramelizada.</p>
                                <button class="landing-btn-primary w-full py-4 rounded-xl font-bold text-lg shadow-lg">Adicionar R$ 39,90</button>
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
                        <div class="flex gap-6">
                            <div class="flex-shrink-0 w-12 h-12 rounded-full bg-white/10 flex items-center justify-center text-[#db3327]">
                                <span class="material-symbols-outlined">shopping_cart_checkout</span>
                            </div>
                            <div>
                                <h4 class="font-headline font-bold text-xl mb-2">Checkout Sem Atrito</h4>
                                <p class="text-white/60 leading-relaxed">Do desejo a finalizacao em apenas dois cliques. Menos tempo pensando, mais tempo pedindo.</p>
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

        <!-- WHATSAPP FLOAT -->
        <a
            v-if="whatsapp"
            :href="`https://wa.me/${whatsapp.number}?text=${encodeURIComponent(whatsapp.message)}`"
            target="_blank"
            rel="noopener noreferrer"
            class="fixed bottom-6 right-6 z-50 w-14 h-14 bg-emerald-600 rounded-full flex items-center justify-center shadow-lg shadow-emerald-600/30 hover:bg-emerald-700 hover:shadow-emerald-600/50 transition-all duration-300 hover:scale-110"
            aria-label="Contato via WhatsApp"
        >
            <svg class="w-7 h-7 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/><path d="M12 0C5.373 0 0 5.373 0 12c0 2.625.846 5.059 2.284 7.034L.789 23.492a.5.5 0 00.611.611l4.458-1.495A11.943 11.943 0 0012 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 22c-2.387 0-4.591-.822-6.328-2.2l-.442-.362-3.095 1.038 1.038-3.095-.362-.442A9.935 9.935 0 012 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10z"/></svg>
        </a>
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
