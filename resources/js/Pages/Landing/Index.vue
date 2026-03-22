<script setup>
import { ref, onMounted } from 'vue';
import { Head } from '@inertiajs/vue3';

const props = defineProps({
    whatsapp: Object,
});

// Animated counters
const counters = ref([
    { target: 73, current: 0, suffix: '%', label: 'mais pedidos com vídeo' },
    { target: 2.4, current: 0, suffix: 'x', label: 'aumento no ticket médio' },
    { target: 89, current: 0, suffix: '%', label: 'engajamento no cardápio' },
    { target: 45, current: 0, suffix: '%', label: 'menos tempo de indecisão', prefix: '-' },
]);

onMounted(() => {
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                animateCounters();
                observer.disconnect();
            }
        });
    });
    const el = document.getElementById('numbers');
    if (el) observer.observe(el);
});

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

// FAQ
const faqs = [
    { q: 'Preciso gravar vídeos profissionais?', a: 'Não! Vídeos feitos com celular funcionam perfeitamente. O importante é mostrar seu prato de forma real e atrativa. Nossos clientes que usam vídeos simples já têm resultados incríveis.' },
    { q: 'Funciona para delivery?', a: 'Sim! O cardápio digital funciona perfeitamente para delivery. Seus clientes podem acessar de qualquer lugar, ver os pratos em vídeo e decidir o pedido mais rápido.' },
    { q: 'O QR Code já vem pronto?', a: 'Sim! QR Codes ilimitados gerados automaticamente, personalizáveis com suas cores e logo. Baixe em PNG ou SVG para imprimir.' },
    { q: 'Posso editar categorias e imagens?', a: 'Total controle! Adicione, edite ou remova categorias, imagens e vídeos quando quiser. Tudo em tempo real.' },
    { q: 'Quantos pratos posso adicionar?', a: 'No plano Pro, itens ilimitados! Adicione quantos pratos quiser, com foto e vídeo para cada um.' },
    { q: 'Preciso instalar algo?', a: 'Nada! ClicaFood é 100% web. Funciona no navegador do celular e computador. Seus clientes não precisam instalar nenhum app.' },
    { q: 'Tem métricas detalhadas?', a: 'Sim! Veja visualizações do cardápio, itens mais clicados, vídeos mais assistidos e muito mais. Dados em tempo real para você tomar decisões.' },
];
const openFaq = ref(null);
const toggleFaq = (i) => { openFaq.value = openFaq.value === i ? null : i; };

// Testimonials
const testimonials = [
    { name: 'Carlos Mendes', role: 'Dono, Burger Station', text: 'Desde que coloquei vídeos no cardápio, meu ticket médio subiu 40%. Os clientes pedem mais quando veem o prato pronto.' },
    { name: 'Ana Luíza', role: 'Gerente, Sushi Kai', text: 'O QR Code nas mesas mudou tudo. Zero impressão de cardápio e os clientes adoram ver os pratos em vídeo.' },
    { name: 'Roberto Silva', role: 'Chef, La Trattoria', text: 'As métricas me mostram exatamente o que meus clientes querem. Nunca tive esse nível de informação.' },
];

const whyCards = [
    { icon: '🎬', title: 'Vídeos que vendem', desc: 'Mostre seus pratos em movimento. Vídeo vende 3x mais que foto.' },
    { icon: '📱', title: 'Stories dos pratos', desc: 'Destaque seus melhores pratos em formato Stories, igual Instagram.' },
    { icon: '📊', title: 'Métricas em tempo real', desc: 'Saiba exatamente o que seus clientes mais olham e pedem.' },
    { icon: '🏷️', title: 'Categorias com imagem', desc: 'Organize seu cardápio com categorias visuais e atrativas.' },
    { icon: '📲', title: 'QR Code ilimitado', desc: 'Gere quantos QR Codes precisar. Mesa, balcão, delivery.' },
    { icon: '⚡', title: 'Gestão poderosa', desc: 'Controle total do seu cardápio em poucos cliques.' },
];

const audiences = [
    'Restaurantes executivos', 'Bares e pubs', 'Hamburguerias', 'Steakhouses',
    'Delivery', 'Eventos e buffets', 'Pizzarias', 'Cafeterias', 'Food trucks',
];
</script>

<template>
    <div class="min-h-screen bg-dark-950 text-white">
        <Head title="ClicaFood - Cardápio Digital com Vídeo" />

        <!-- HEADER -->
        <header class="fixed top-0 w-full z-50 bg-dark-950/80 backdrop-blur-lg border-b border-dark-800">
            <div class="max-w-6xl mx-auto px-4 py-3 flex items-center justify-between">
                <h1 class="text-2xl font-display font-black">
                    <span class="text-brand-500">Clica</span><span>Food</span>
                </h1>
                <div class="flex items-center gap-3">
                    <a href="/login" class="text-dark-300 hover:text-white text-sm font-medium transition-colors">Entrar</a>
                    <a href="/register" class="btn-primary text-sm py-2 px-4">Começar Agora</a>
                </div>
            </div>
        </header>

        <!-- SOCIAL PROOF BAR -->
        <div class="pt-16 bg-brand-500/10 border-b border-brand-500/20">
            <div class="max-w-6xl mx-auto px-4 py-2 text-center">
                <p class="text-sm font-medium text-brand-400">
                    🔥 <strong>5.000+</strong> empresas já usam o ClicaFood para vender mais
                </p>
            </div>
        </div>

        <!-- HERO -->
        <section class="pt-20 pb-16 px-4">
            <div class="max-w-4xl mx-auto text-center">
                <h2 class="text-4xl md:text-6xl font-display font-black leading-tight mb-6">
                    Domine a fome,<br>
                    <span class="text-brand-500">controle o pedido,</span><br>
                    destrua a concorrência
                </h2>
                <p class="text-lg md:text-xl text-dark-300 max-w-2xl mx-auto mb-8">
                    O cardápio digital com <strong class="text-white">vídeo</strong> que transforma
                    visualizações em pedidos. Seus pratos em movimento, suas vendas em crescimento.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center mb-8">
                    <a href="/register" class="btn-primary text-lg py-4 px-8">
                        Começar Grátis →
                    </a>
                    <a href="#demo" class="btn-secondary text-lg py-4 px-8">
                        Ver Demo
                    </a>
                </div>
                <div class="flex items-center justify-center gap-2 text-sm text-dark-400">
                    <div class="flex text-yellow-400">★★★★★</div>
                    <span><strong class="text-white">4,8/5</strong> baseado em 1.247 avaliações</span>
                </div>
            </div>
        </section>

        <!-- POR QUE -->
        <section class="py-16 px-4 bg-dark-900/50">
            <div class="max-w-6xl mx-auto">
                <h3 class="text-3xl md:text-4xl font-display font-bold text-center mb-12">
                    Por que o ClicaFood é uma <span class="text-brand-500">máquina de conversão</span>?
                </h3>
                <div class="grid md:grid-cols-3 gap-6">
                    <div v-for="card in whyCards" :key="card.title"
                         class="card hover:border-brand-500/30 transition-colors text-center">
                        <div class="text-4xl mb-4">{{ card.icon }}</div>
                        <h4 class="font-bold text-white text-lg mb-2">{{ card.title }}</h4>
                        <p class="text-dark-400 text-sm">{{ card.desc }}</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- NÚMEROS -->
        <section id="numbers" class="py-16 px-4">
            <div class="max-w-4xl mx-auto">
                <h3 class="text-3xl font-display font-bold text-center mb-12">
                    Números que <span class="text-brand-500">dominam</span>
                </h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                    <div v-for="c in counters" :key="c.label" class="text-center">
                        <p class="text-4xl md:text-5xl font-display font-black text-brand-500">
                            {{ c.prefix || '+' }}{{ c.current }}{{ c.suffix }}
                        </p>
                        <p class="text-sm text-dark-400 mt-2">{{ c.label }}</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- PÚBLICO ALVO -->
        <section class="py-16 px-4 bg-dark-900/50">
            <div class="max-w-4xl mx-auto text-center">
                <h3 class="text-3xl font-display font-bold mb-8">
                    Feito para quem quer <span class="text-brand-500">dominar</span>
                </h3>
                <div class="flex flex-wrap gap-3 justify-center">
                    <span v-for="a in audiences" :key="a"
                          class="px-4 py-2 bg-dark-800 border border-dark-600 rounded-full text-sm text-dark-300">
                        {{ a }}
                    </span>
                </div>
            </div>
        </section>

        <!-- ANTES vs DEPOIS -->
        <section class="py-16 px-4">
            <div class="max-w-3xl mx-auto">
                <h3 class="text-3xl font-display font-bold text-center mb-12">
                    <span class="text-dark-400">Sem ClicaFood</span> vs
                    <span class="text-brand-500">Com ClicaFood</span>
                </h3>
                <div class="grid md:grid-cols-2 gap-6">
                    <div class="card border-red-500/20 space-y-3">
                        <h4 class="font-bold text-red-400 text-lg">❌ Sem ClicaFood</h4>
                        <ul class="space-y-2 text-sm text-dark-400">
                            <li>• Cardápio de papel desatualizado</li>
                            <li>• Cliente não sabe o que pedir</li>
                            <li>• Zero dados sobre preferências</li>
                            <li>• Impressão cara e trabalhosa</li>
                            <li>• Sem destaque dos pratos lucrativos</li>
                            <li>• Concorrência te engolindo</li>
                        </ul>
                    </div>
                    <div class="card border-green-500/20 space-y-3">
                        <h4 class="font-bold text-green-400 text-lg">✅ Com ClicaFood</h4>
                        <ul class="space-y-2 text-sm text-dark-300">
                            <li>• Cardápio digital sempre atualizado</li>
                            <li>• Vídeos que vendem por você</li>
                            <li>• Métricas em tempo real</li>
                            <li>• QR Code ilimitado e gratuito</li>
                            <li>• Stories para destacar pratos</li>
                            <li>• Você domina o mercado</li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>

        <!-- DEPOIMENTOS -->
        <section class="py-16 px-4 bg-dark-900/50">
            <div class="max-w-4xl mx-auto">
                <h3 class="text-3xl font-display font-bold text-center mb-12">
                    Quem usa, <span class="text-brand-500">domina</span>
                </h3>
                <div class="grid md:grid-cols-3 gap-6">
                    <div v-for="t in testimonials" :key="t.name" class="card">
                        <div class="text-yellow-400 text-sm mb-3">★★★★★</div>
                        <p class="text-dark-300 text-sm mb-4 italic">"{{ t.text }}"</p>
                        <div>
                            <p class="font-bold text-white text-sm">{{ t.name }}</p>
                            <p class="text-xs text-dark-500">{{ t.role }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- PLANO -->
        <section class="py-16 px-4">
            <div class="max-w-md mx-auto">
                <h3 class="text-3xl font-display font-bold text-center mb-8">
                    Um plano, <span class="text-brand-500">poder total</span>
                </h3>
                <div class="card border-brand-500/30 text-center relative overflow-hidden">
                    <div class="absolute top-0 left-0 right-0 bg-brand-500 text-white text-xs font-bold py-1">
                        MAIS POPULAR
                    </div>
                    <div class="pt-6">
                        <h4 class="font-display font-bold text-xl text-white">Plano Pro</h4>
                        <div class="mt-4 mb-6">
                            <span class="text-dark-400 text-sm line-through">R$ 197/mês</span>
                            <p class="text-4xl font-display font-black text-white">
                                R$ <span class="text-brand-500">97</span><span class="text-lg">/mês</span>
                            </p>
                        </div>
                        <ul class="text-sm text-dark-300 space-y-2 text-left mb-6">
                            <li class="flex items-center gap-2"><span class="text-green-400">✓</span> Itens ilimitados</li>
                            <li class="flex items-center gap-2"><span class="text-green-400">✓</span> Vídeos ilimitados</li>
                            <li class="flex items-center gap-2"><span class="text-green-400">✓</span> QR Codes ilimitados</li>
                            <li class="flex items-center gap-2"><span class="text-green-400">✓</span> Stories de destaque</li>
                            <li class="flex items-center gap-2"><span class="text-green-400">✓</span> Métricas avançadas</li>
                            <li class="flex items-center gap-2"><span class="text-green-400">✓</span> Cores personalizadas</li>
                            <li class="flex items-center gap-2"><span class="text-green-400">✓</span> Suporte prioritário</li>
                            <li class="flex items-center gap-2"><span class="text-green-400">✓</span> Sem marca d'água</li>
                        </ul>
                        <a href="/register" class="btn-primary w-full text-lg py-4">
                            Começar agora →
                        </a>
                        <p class="text-xs text-dark-500 mt-3">7 dias grátis. Cancele quando quiser.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA FINAL -->
        <section class="py-16 px-4 bg-gradient-to-b from-brand-500/10 to-transparent">
            <div class="max-w-3xl mx-auto text-center">
                <h3 class="text-3xl md:text-4xl font-display font-bold mb-4">
                    Pare de perder vendas.<br>
                    <span class="text-brand-500">Comece a dominar agora.</span>
                </h3>
                <p class="text-dark-300 mb-8">
                    Junte-se a mais de 27.000 restaurantes que já transformaram seus cardápios
                </p>
                <a href="/register" class="btn-primary text-lg py-4 px-10">
                    Criar minha conta grátis →
                </a>
                <div class="flex justify-center gap-8 mt-8 text-sm text-dark-400">
                    <div><strong class="text-white text-lg">27k+</strong><br>usuários</div>
                    <div><strong class="text-white text-lg">4.8/5</strong><br>avaliação</div>
                    <div><strong class="text-white text-lg">98%</strong><br>satisfação</div>
                </div>
            </div>
        </section>

        <!-- FAQ -->
        <section class="py-16 px-4">
            <div class="max-w-2xl mx-auto">
                <h3 class="text-3xl font-display font-bold text-center mb-12">
                    Perguntas frequentes
                </h3>
                <div class="space-y-3">
                    <div v-for="(faq, i) in faqs" :key="i"
                         class="card cursor-pointer" @click="toggleFaq(i)">
                        <div class="flex items-center justify-between">
                            <h4 class="font-semibold text-white text-sm">{{ faq.q }}</h4>
                            <span class="text-dark-400 text-xl transition-transform"
                                  :class="{ 'rotate-45': openFaq === i }">+</span>
                        </div>
                        <div v-if="openFaq === i" class="mt-3 text-sm text-dark-400 leading-relaxed">
                            {{ faq.a }}
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- FOOTER -->
        <footer class="border-t border-dark-800 py-8 px-4">
            <div class="max-w-6xl mx-auto flex flex-col md:flex-row items-center justify-between gap-4">
                <div>
                    <h2 class="text-xl font-display font-black">
                        <span class="text-brand-500">Clica</span><span>Food</span>
                    </h2>
                    <p class="text-xs text-dark-500 mt-1">Cardápio digital com vídeo</p>
                </div>
                <div class="flex gap-6 text-sm text-dark-400">
                    <a href="#" class="hover:text-white transition-colors">Privacidade</a>
                    <a href="#" class="hover:text-white transition-colors">Termos</a>
                    <a href="#" class="hover:text-white transition-colors">Contato</a>
                </div>
                <p class="text-xs text-dark-600">© 2024 ClicaFood. Todos os direitos reservados.</p>
            </div>
        </footer>

        <!-- WhatsApp Float -->
        <a :href="`https://wa.me/${whatsapp.number}?text=${encodeURIComponent(whatsapp.message)}`"
           target="_blank"
           class="fixed bottom-6 right-6 z-50 w-14 h-14 bg-green-500 rounded-full flex items-center justify-center shadow-lg hover:bg-green-600 transition-colors hover:scale-110 transform">
            <svg class="w-7 h-7 text-white" fill="currentColor" viewBox="0 0 24 24">
                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
            </svg>
        </a>
    </div>
</template>
