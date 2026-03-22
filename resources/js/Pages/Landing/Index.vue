<script setup>
import { ref, onMounted, onUnmounted } from 'vue';
import { Head } from '@inertiajs/vue3';
import Icon from '@/Components/Icon.vue';

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
    { target: 73, current: 0, suffix: '%', label: 'mais pedidos com video', prefix: '+' },
    { target: 2.4, current: 0, suffix: 'x', label: 'ticket medio', prefix: '' },
    { target: 89, current: 0, suffix: '%', label: 'engajamento', prefix: '' },
    { target: 45, current: 0, suffix: '%', label: 'menos indecisao', prefix: '-' },
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
const observedSections = ref(new Set());

onMounted(() => {
    // Counter observer
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

    // Section reveal observer
    const revealObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('revealed');
                observedSections.value.add(entry.target.id);
            }
        });
    }, { threshold: 0.1 });

    document.querySelectorAll('.reveal-section').forEach(el => {
        revealObserver.observe(el);
    });
});

// Why cards data
const whyCards = [
    { icon: 'video', title: 'Videos que vendem', desc: 'Mostre seus pratos em movimento. Video converte 3x mais que foto estatica. Seu cliente sente fome antes de pedir.' },
    { icon: 'star', title: 'Stories dos pratos', desc: 'Destaque seus melhores pratos em formato Stories, igual Instagram. Engajamento brutal.' },
    { icon: 'chart', title: 'Metricas em tempo real', desc: 'Saiba exatamente o que seus clientes mais olham, clicam e pedem. Dados para decisoes agressivas.' },
    { icon: 'tag', title: 'Categorias com imagem', desc: 'Organize seu cardapio com categorias visuais que guiam o cliente direto para o pedido.' },
    { icon: 'qrcode', title: 'QR Code ilimitado', desc: 'Gere quantos QR Codes precisar. Mesa, balcao, delivery. Personalizaveis com suas cores.' },
    { icon: 'settings', title: 'Gestao poderosa', desc: 'Controle total do seu cardapio em poucos cliques. Edite, organize, domine.' },
];

// Target audiences
const audiences = [
    'Restaurantes executivos', 'Bares e pubs', 'Hamburguerias', 'Steakhouses',
    'Delivery', 'Eventos e buffets', 'Pizzarias', 'Cafeterias', 'Food trucks',
    'Sorveterias', 'Padarias', 'Acaiterias',
];

// Before/After lists
const beforeItems = [
    'Cardapio de papel desatualizado e caro',
    'Cliente indeciso sem saber o que pedir',
    'Zero dados sobre preferencias',
    'Impressao cara, trabalhosa e lenta',
    'Sem destaque dos pratos mais lucrativos',
    'Concorrencia te engolindo dia apos dia',
];

const afterItems = [
    'Cardapio digital sempre atualizado em tempo real',
    'Videos que vendem o prato por voce',
    'Metricas detalhadas de comportamento',
    'QR Code ilimitado e gratuito',
    'Stories para destacar o que da mais lucro',
    'Voce domina o mercado local',
];

// Testimonials
const testimonials = [
    { name: 'Carlos Mendes', role: 'Proprietario, Burger Station', text: 'Desde que coloquei videos no cardapio, meu ticket medio subiu 40%. Os clientes pedem mais quando veem o prato pronto. Resultado absurdo.' },
    { name: 'Ana Luiza', role: 'Gerente, Sushi Kai', text: 'O QR Code nas mesas mudou tudo. Zero impressao de cardapio e os clientes adoram ver os pratos em video antes de decidir.' },
    { name: 'Roberto Silva', role: 'Chef, La Trattoria', text: 'As metricas me mostram exatamente o que meus clientes querem. Nunca tive esse nivel de informacao. Ferramenta indispensavel.' },
];

// Pricing features
const pricingFeatures = [
    'Itens ilimitados',
    'Videos ilimitados',
    'QR Codes ilimitados',
    'Stories de destaque',
    'Metricas avancadas',
    'Cores personalizadas',
    'Suporte prioritario',
    'Sem marca d\'agua',
];

// FAQ
const faqs = [
    { q: 'Preciso gravar videos profissionais?', a: 'Nao. Videos feitos com celular funcionam perfeitamente. O importante e mostrar seu prato de forma real e atrativa. Nossos clientes que usam videos simples ja tem resultados impressionantes.' },
    { q: 'Funciona para delivery?', a: 'Sim. O cardapio digital funciona perfeitamente para delivery. Seus clientes podem acessar de qualquer lugar, ver os pratos em video e decidir o pedido mais rapido.' },
    { q: 'O QR Code ja vem pronto?', a: 'Sim. QR Codes ilimitados gerados automaticamente, personalizaveis com suas cores e logo. Baixe em PNG ou SVG para imprimir onde quiser.' },
    { q: 'Posso editar categorias e imagens a qualquer momento?', a: 'Total controle. Adicione, edite ou remova categorias, imagens e videos quando quiser. Tudo em tempo real, sem depender de ninguem.' },
    { q: 'Quantos pratos posso adicionar?', a: 'No plano Pro, itens ilimitados. Adicione quantos pratos quiser, com foto e video para cada um. Sem restricoes.' },
    { q: 'Preciso instalar algo?', a: 'Nada. ClicaFood e 100% web. Funciona no navegador do celular e computador. Seus clientes nao precisam instalar nenhum aplicativo.' },
    { q: 'Tem metricas detalhadas?', a: 'Sim. Veja visualizacoes do cardapio, itens mais clicados, videos mais assistidos e muito mais. Dados em tempo real para voce tomar decisoes com base em numeros reais.' },
];
const openFaq = ref(null);
const toggleFaq = (i) => { openFaq.value = openFaq.value === i ? null : i; };
</script>

<template>
    <div class="min-h-screen bg-dark-950 text-white antialiased">
        <Head title="ClicaFood - Cardapio Digital com Video | Domine o Mercado" />

        <!-- HEADER -->
        <header
            class="fixed top-0 w-full z-50 transition-all duration-300"
            :class="scrolled ? 'bg-dark-950/90 backdrop-blur-xl border-b border-dark-800 shadow-xl shadow-black/20' : 'bg-transparent'"
        >
            <div class="max-w-6xl mx-auto px-4 sm:px-6 py-4 flex items-center justify-between">
                <a href="/" class="text-2xl font-display font-black tracking-tight">
                    <span class="text-brand-500">Clica</span><span class="text-white">Food</span>
                </a>
                <div class="flex items-center gap-4">
                    <a href="/login" class="text-dark-300 hover:text-white text-sm font-semibold transition-colors duration-200">Entrar</a>
                    <a href="/register" class="btn-primary text-sm py-2.5 px-6 font-semibold">Comecar Agora</a>
                </div>
            </div>
        </header>

        <!-- SOCIAL PROOF BAR -->
        <div class="pt-[72px]">
            <div class="bg-gradient-to-r from-brand-500/10 via-brand-500/5 to-brand-500/10 border-b border-brand-500/10">
                <div class="max-w-6xl mx-auto px-4 py-2.5 text-center">
                    <p class="text-sm font-semibold text-brand-400 tracking-wide">
                        <strong class="text-brand-300">5.000+</strong> empresas ja usam o ClicaFood para vender mais
                    </p>
                </div>
            </div>
        </div>

        <!-- HERO -->
        <section class="pt-16 pb-20 px-4 sm:px-6 relative overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-b from-brand-500/5 via-transparent to-transparent pointer-events-none"></div>
            <div class="max-w-4xl mx-auto text-center relative">
                <h2 class="text-4xl sm:text-5xl md:text-6xl lg:text-7xl font-display font-black leading-[1.05] mb-8 tracking-tight">
                    Domine a fome,<br>
                    <span class="text-brand-gradient">controle o pedido,</span><br>
                    destrua a concorrencia
                </h2>
                <p class="text-lg md:text-xl text-dark-300 max-w-2xl mx-auto mb-10 leading-relaxed font-sans">
                    O cardapio digital com <strong class="text-white">video</strong> que transforma
                    visualizacoes em pedidos. Seus pratos em movimento, suas vendas em crescimento constante.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center mb-10">
                    <a href="/register" class="btn-primary text-lg py-4 px-10 font-bold shadow-lg shadow-brand-500/25 hover:shadow-brand-500/40 transition-all duration-300">
                        Comecar Gratis
                    </a>
                    <a href="#porque" class="btn-secondary text-lg py-4 px-10 font-bold">
                        Ver Beneficios
                    </a>
                </div>
                <div class="flex items-center justify-center gap-3 text-sm text-dark-400">
                    <div class="flex gap-0.5">
                        <Icon name="star" class="w-4 h-4 text-yellow-400 fill-yellow-400" />
                        <Icon name="star" class="w-4 h-4 text-yellow-400 fill-yellow-400" />
                        <Icon name="star" class="w-4 h-4 text-yellow-400 fill-yellow-400" />
                        <Icon name="star" class="w-4 h-4 text-yellow-400 fill-yellow-400" />
                        <Icon name="star" class="w-4 h-4 text-yellow-400 fill-yellow-400" />
                    </div>
                    <span><strong class="text-white">4,8/5</strong> baseado em <strong class="text-white">1.247</strong> avaliacoes</span>
                </div>
            </div>
        </section>

        <!-- POR QUE -->
        <section id="porque" class="py-20 px-4 sm:px-6 bg-dark-900/50 reveal-section">
            <div class="max-w-6xl mx-auto">
                <h3 class="text-3xl md:text-4xl lg:text-5xl font-display font-bold text-center mb-4 tracking-tight">
                    Por que o ClicaFood e uma <span class="text-brand-gradient">maquina de conversao</span>?
                </h3>
                <p class="text-dark-400 text-center mb-14 max-w-2xl mx-auto text-lg font-sans">
                    Cada recurso foi projetado para uma coisa: fazer seu cliente pedir mais.
                </p>
                <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div
                        v-for="card in whyCards"
                        :key="card.title"
                        class="card group hover:border-brand-500/30 transition-all duration-300 hover:-translate-y-1"
                    >
                        <div class="w-12 h-12 rounded-xl bg-brand-500/10 border border-brand-500/20 flex items-center justify-center mb-5 group-hover:bg-brand-500/20 transition-colors duration-300">
                            <Icon :name="card.icon" class="w-6 h-6 text-brand-500" />
                        </div>
                        <h4 class="font-display font-bold text-white text-lg mb-2">{{ card.title }}</h4>
                        <p class="text-dark-400 text-sm leading-relaxed font-sans">{{ card.desc }}</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- NUMEROS -->
        <section id="numbers" class="py-20 px-4 sm:px-6 relative reveal-section">
            <div class="absolute inset-0 bg-gradient-to-b from-transparent via-brand-500/5 to-transparent pointer-events-none"></div>
            <div class="max-w-4xl mx-auto relative">
                <h3 class="text-3xl md:text-4xl font-display font-bold text-center mb-4 tracking-tight">
                    Numeros que <span class="text-brand-gradient">dominam</span>
                </h3>
                <p class="text-dark-400 text-center mb-14 text-lg font-sans">
                    Resultados reais de quem ja usa o ClicaFood.
                </p>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                    <div v-for="c in counters" :key="c.label" class="stat-card text-center">
                        <p class="text-4xl md:text-5xl font-display font-black text-brand-500 mb-2">
                            {{ c.prefix }}{{ c.current }}{{ c.suffix }}
                        </p>
                        <p class="text-sm text-dark-400 font-sans">{{ c.label }}</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- PUBLICO ALVO -->
        <section class="py-20 px-4 sm:px-6 bg-dark-900/50 reveal-section">
            <div class="max-w-4xl mx-auto text-center">
                <h3 class="text-3xl md:text-4xl font-display font-bold mb-4 tracking-tight">
                    Feito para quem quer <span class="text-brand-gradient">dominar</span>
                </h3>
                <p class="text-dark-400 mb-10 text-lg font-sans">
                    Nao importa o tamanho. Se voce vende comida, o ClicaFood e para voce.
                </p>
                <div class="flex flex-wrap gap-3 justify-center">
                    <span
                        v-for="a in audiences"
                        :key="a"
                        class="px-5 py-2.5 bg-dark-800/80 border border-dark-700 rounded-full text-sm text-dark-300 font-medium hover:border-brand-500/40 hover:text-brand-400 transition-all duration-200 cursor-default"
                    >
                        {{ a }}
                    </span>
                </div>
            </div>
        </section>

        <!-- ANTES vs DEPOIS -->
        <section class="py-20 px-4 sm:px-6 reveal-section">
            <div class="max-w-4xl mx-auto">
                <h3 class="text-3xl md:text-4xl font-display font-bold text-center mb-14 tracking-tight">
                    <span class="text-dark-500">Sem ClicaFood</span> vs
                    <span class="text-brand-gradient">Com ClicaFood</span>
                </h3>
                <div class="grid md:grid-cols-2 gap-8">
                    <div class="card border-red-500/20 hover:border-red-500/30 transition-colors duration-300">
                        <h4 class="font-display font-bold text-red-400 text-lg mb-5 flex items-center gap-2">
                            <Icon name="close" class="w-5 h-5 text-red-400" />
                            Sem ClicaFood
                        </h4>
                        <ul class="space-y-3">
                            <li v-for="item in beforeItems" :key="item" class="flex items-start gap-3 text-sm text-dark-400">
                                <Icon name="close" class="w-4 h-4 text-red-500/60 mt-0.5 shrink-0" />
                                <span>{{ item }}</span>
                            </li>
                        </ul>
                    </div>
                    <div class="card border-green-500/20 hover:border-green-500/30 transition-colors duration-300">
                        <h4 class="font-display font-bold text-green-400 text-lg mb-5 flex items-center gap-2">
                            <Icon name="check" class="w-5 h-5 text-green-400" />
                            Com ClicaFood
                        </h4>
                        <ul class="space-y-3">
                            <li v-for="item in afterItems" :key="item" class="flex items-start gap-3 text-sm text-dark-300">
                                <Icon name="check" class="w-4 h-4 text-green-500 mt-0.5 shrink-0" />
                                <span>{{ item }}</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>

        <!-- DEPOIMENTOS -->
        <section class="py-20 px-4 sm:px-6 bg-dark-900/50 reveal-section">
            <div class="max-w-5xl mx-auto">
                <h3 class="text-3xl md:text-4xl font-display font-bold text-center mb-4 tracking-tight">
                    Quem usa, <span class="text-brand-gradient">domina</span>
                </h3>
                <p class="text-dark-400 text-center mb-14 text-lg font-sans">
                    Resultados reais de donos de restaurantes como voce.
                </p>
                <div class="grid md:grid-cols-3 gap-6">
                    <div v-for="t in testimonials" :key="t.name" class="card hover:border-dark-600 transition-all duration-300">
                        <div class="flex gap-0.5 mb-4">
                            <Icon name="star" class="w-4 h-4 text-yellow-400 fill-yellow-400" />
                            <Icon name="star" class="w-4 h-4 text-yellow-400 fill-yellow-400" />
                            <Icon name="star" class="w-4 h-4 text-yellow-400 fill-yellow-400" />
                            <Icon name="star" class="w-4 h-4 text-yellow-400 fill-yellow-400" />
                            <Icon name="star" class="w-4 h-4 text-yellow-400 fill-yellow-400" />
                        </div>
                        <p class="text-dark-300 text-sm mb-6 leading-relaxed italic font-sans">"{{ t.text }}"</p>
                        <div class="border-t border-dark-700 pt-4">
                            <p class="font-display font-bold text-white text-sm">{{ t.name }}</p>
                            <p class="text-xs text-dark-500 mt-0.5 font-sans">{{ t.role }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- PLANO -->
        <section class="py-20 px-4 sm:px-6 reveal-section">
            <div class="max-w-md mx-auto">
                <h3 class="text-3xl md:text-4xl font-display font-bold text-center mb-4 tracking-tight">
                    Um plano, <span class="text-brand-gradient">poder total</span>
                </h3>
                <p class="text-dark-400 text-center mb-10 text-lg font-sans">
                    Sem surpresas. Tudo incluso. Cancele quando quiser.
                </p>
                <div class="card border-brand-500/30 text-center relative overflow-hidden hover:border-brand-500/50 transition-colors duration-300">
                    <div class="absolute top-0 left-0 right-0 bg-gradient-to-r from-brand-600 via-brand-500 to-brand-600 text-white text-xs font-bold py-1.5 uppercase tracking-widest">
                        MAIS POPULAR
                    </div>
                    <div class="pt-8">
                        <h4 class="font-display font-bold text-2xl text-white">Plano Pro</h4>
                        <div class="mt-5 mb-8">
                            <span class="text-dark-500 text-sm line-through">R$ 197/mes</span>
                            <p class="text-5xl font-display font-black text-white mt-1">
                                R$ <span class="text-brand-500">97</span><span class="text-lg text-dark-400 font-semibold">/mes</span>
                            </p>
                        </div>
                        <ul class="text-sm text-dark-300 space-y-3 text-left mb-8 font-sans">
                            <li v-for="feature in pricingFeatures" :key="feature" class="flex items-center gap-3">
                                <Icon name="check" class="w-4 h-4 text-green-400 shrink-0" />
                                <span>{{ feature }}</span>
                            </li>
                        </ul>
                        <a href="/register" class="btn-primary w-full text-lg py-4 font-bold shadow-lg shadow-brand-500/25">
                            Comecar agora
                        </a>
                        <p class="text-xs text-dark-500 mt-4 font-sans">7 dias gratis. Cancele quando quiser. Sem fidelidade.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA FINAL -->
        <section class="py-20 px-4 sm:px-6 relative overflow-hidden reveal-section">
            <div class="absolute inset-0 bg-gradient-to-b from-brand-500/10 via-brand-500/5 to-transparent pointer-events-none"></div>
            <div class="max-w-3xl mx-auto text-center relative">
                <h3 class="text-3xl md:text-4xl lg:text-5xl font-display font-bold mb-6 tracking-tight leading-tight">
                    Pare de perder vendas.<br>
                    <span class="text-brand-gradient">Comece a dominar agora.</span>
                </h3>
                <p class="text-dark-300 mb-10 text-lg font-sans max-w-xl mx-auto">
                    Junte-se a mais de 27.000 restaurantes que ja transformaram seus cardapios em maquinas de venda.
                </p>
                <a href="/register" class="btn-primary text-lg py-4 px-12 font-bold shadow-lg shadow-brand-500/25 hover:shadow-brand-500/40 transition-all duration-300 inline-block">
                    Criar minha conta gratis
                </a>
                <div class="flex justify-center gap-10 mt-12 text-sm text-dark-400">
                    <div>
                        <strong class="text-white text-2xl font-display font-black block">27k+</strong>
                        <span class="font-sans">usuarios ativos</span>
                    </div>
                    <div>
                        <strong class="text-white text-2xl font-display font-black block">4.8/5</strong>
                        <span class="font-sans">avaliacao media</span>
                    </div>
                    <div>
                        <strong class="text-white text-2xl font-display font-black block">98%</strong>
                        <span class="font-sans">satisfacao</span>
                    </div>
                </div>
            </div>
        </section>

        <!-- FAQ -->
        <section class="py-20 px-4 sm:px-6 bg-dark-900/50 reveal-section">
            <div class="max-w-2xl mx-auto">
                <h3 class="text-3xl md:text-4xl font-display font-bold text-center mb-4 tracking-tight">
                    Perguntas frequentes
                </h3>
                <p class="text-dark-400 text-center mb-14 text-lg font-sans">
                    Tudo o que voce precisa saber antes de comecar.
                </p>
                <div class="space-y-3">
                    <div
                        v-for="(faq, i) in faqs"
                        :key="i"
                        class="card cursor-pointer hover:border-dark-600 transition-all duration-200"
                        @click="toggleFaq(i)"
                    >
                        <div class="flex items-center justify-between gap-4">
                            <h4 class="font-semibold text-white text-sm font-display">{{ faq.q }}</h4>
                            <Icon
                                name="chevron-down"
                                class="w-5 h-5 text-dark-400 shrink-0 transition-transform duration-300"
                                :class="{ 'rotate-180': openFaq === i }"
                            />
                        </div>
                        <transition
                            enter-active-class="transition-all duration-300 ease-out"
                            enter-from-class="max-h-0 opacity-0"
                            enter-to-class="max-h-40 opacity-100"
                            leave-active-class="transition-all duration-200 ease-in"
                            leave-from-class="max-h-40 opacity-100"
                            leave-to-class="max-h-0 opacity-0"
                        >
                            <div v-if="openFaq === i" class="mt-4 text-sm text-dark-400 leading-relaxed font-sans overflow-hidden">
                                {{ faq.a }}
                            </div>
                        </transition>
                    </div>
                </div>
            </div>
        </section>

        <!-- FOOTER -->
        <footer class="border-t border-dark-800 py-10 px-4 sm:px-6">
            <div class="max-w-6xl mx-auto flex flex-col md:flex-row items-center justify-between gap-6">
                <div class="text-center md:text-left">
                    <a href="/" class="text-xl font-display font-black">
                        <span class="text-brand-500">Clica</span><span class="text-white">Food</span>
                    </a>
                    <p class="text-xs text-dark-500 mt-1 font-sans">Cardapio digital com video</p>
                </div>
                <div class="flex gap-8 text-sm text-dark-400 font-sans">
                    <a href="#" class="hover:text-white transition-colors duration-200">Privacidade</a>
                    <a href="#" class="hover:text-white transition-colors duration-200">Termos</a>
                    <a href="#" class="hover:text-white transition-colors duration-200">Contato</a>
                </div>
                <p class="text-xs text-dark-600 font-sans">2024 ClicaFood. Todos os direitos reservados.</p>
            </div>
        </footer>

        <!-- WHATSAPP FLOAT -->
        <a
            v-if="whatsapp"
            :href="`https://wa.me/${whatsapp.number}?text=${encodeURIComponent(whatsapp.message)}`"
            target="_blank"
            rel="noopener noreferrer"
            class="fixed bottom-6 right-6 z-50 w-14 h-14 bg-green-500 rounded-full flex items-center justify-center shadow-lg shadow-green-500/30 hover:bg-green-600 hover:shadow-green-500/50 transition-all duration-300 hover:scale-110"
            aria-label="Contato via WhatsApp"
        >
            <Icon name="phone" class="w-7 h-7 text-white" />
        </a>
    </div>
</template>

<style scoped>
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
