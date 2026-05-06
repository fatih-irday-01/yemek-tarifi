<script setup>
import { computed } from 'vue';
import { Head, Link, usePage } from '@inertiajs/vue3';

const page = usePage();
const isAuthenticated = computed(() => !!page.props.auth?.user);
const currentYear = computed(() => new Date().getFullYear());

defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
});
</script>

<template>
    <Head title="Yemek Tarifi - AI Destekli Tarif Analizi" />

    <div class="min-h-screen bg-white">
        <!-- Navbar -->
        <nav class="border-b border-accent/30 bg-white/80 backdrop-blur-sm sticky top-0 z-50">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="flex h-16 items-center justify-between">
                    <div class="flex items-center gap-2">
                        <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-primary">
                            <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                        </div>
                        <span class="text-lg font-bold text-primary">Yemek Tarifi</span>
                    </div>

                    <div v-if="canLogin" class="flex items-center gap-3">
                        <template v-if="isAuthenticated">
                            <Link
                                :href="route('dashboard')"
                                class="rounded-lg bg-primary px-4 py-2 text-sm font-medium text-white transition hover:bg-primary-light"
                            >
                                Dashboard'a Git
                            </Link>
                        </template>
                        <template v-else>
                            <Link
                                :href="route('login')"
                                class="rounded-lg px-4 py-2 text-sm font-medium text-primary transition hover:bg-accent/20"
                            >
                                Giriş Yap
                            </Link>
                            <Link
                                v-if="canRegister"
                                :href="route('register')"
                                class="rounded-lg bg-primary px-4 py-2 text-sm font-medium text-white transition hover:bg-primary-light"
                            >
                                Kayıt Ol
                            </Link>
                        </template>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Hero -->
        <section class="relative overflow-hidden bg-gradient-to-br from-white via-accent/10 to-accent/20">
            <div class="mx-auto max-w-7xl px-4 py-24 sm:px-6 sm:py-32 lg:px-8">
                <div class="mx-auto max-w-2xl text-center">
                    <div class="mb-6 inline-flex items-center gap-2 rounded-full bg-accent/30 px-4 py-1.5 text-sm font-medium text-primary">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                        AI Destekli Tarif Analizi
                    </div>
                    <h1 class="text-4xl font-bold tracking-tight text-gray-900 sm:text-5xl lg:text-6xl">
                        Yemeğinin Tarifini
                        <span class="text-primary"> Anında Öğren</span>
                    </h1>
                    <p class="mt-6 text-lg text-gray-600">
                        Yemeğinin fotoğrafını yükle, yapay zeka saniyeler içinde malzemeleri, pişirme adımlarını ve besin değerlerini analiz etsin.
                    </p>
                    <div v-if="canLogin" class="mt-10 flex flex-col items-center gap-4 sm:flex-row sm:justify-center">
                        <Link
                            v-if="canRegister"
                            :href="route('register')"
                            class="w-full rounded-xl bg-primary px-8 py-3.5 text-base font-semibold text-white shadow-lg transition hover:bg-primary-light hover:shadow-primary/25 sm:w-auto"
                        >
                            Ücretsiz Başla
                        </Link>
                        <Link
                            :href="route('login')"
                            class="w-full rounded-xl border-2 border-primary/20 px-8 py-3.5 text-base font-semibold text-primary transition hover:border-primary/50 hover:bg-primary/5 sm:w-auto"
                        >
                            Giriş Yap
                        </Link>
                    </div>
                </div>
            </div>
            <div class="absolute -bottom-1 left-0 right-0 h-16 bg-gradient-to-t from-white to-transparent" />
        </section>

        <!-- Features -->
        <section class="py-24">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="mx-auto max-w-xl text-center">
                    <h2 class="text-3xl font-bold text-gray-900">Nasıl Çalışır?</h2>
                    <p class="mt-4 text-gray-600">Üç basit adımda tarifinizi keşfedin</p>
                </div>

                <div class="mt-16 grid gap-8 sm:grid-cols-3">
                    <div class="group rounded-2xl border border-accent/30 bg-white p-8 text-center shadow-sm transition hover:border-primary/30 hover:shadow-md">
                        <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-2xl bg-accent/20 transition group-hover:bg-primary/10">
                            <svg class="h-7 w-7 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div class="mb-2 text-4xl font-bold text-primary/20">01</div>
                        <h3 class="text-lg font-semibold text-gray-900">Fotoğraf Yükle</h3>
                        <p class="mt-2 text-sm text-gray-500">Yemeğin fotoğrafını çek veya galerinden seç</p>
                    </div>

                    <div class="group rounded-2xl border border-accent/30 bg-white p-8 text-center shadow-sm transition hover:border-primary/30 hover:shadow-md">
                        <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-2xl bg-accent/20 transition group-hover:bg-primary/10">
                            <svg class="h-7 w-7 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                            </svg>
                        </div>
                        <div class="mb-2 text-4xl font-bold text-primary/20">02</div>
                        <h3 class="text-lg font-semibold text-gray-900">AI Analiz Eder</h3>
                        <p class="mt-2 text-sm text-gray-500">Yapay zeka yemeği tanır ve tarifi çıkarır</p>
                    </div>

                    <div class="group rounded-2xl border border-accent/30 bg-white p-8 text-center shadow-sm transition hover:border-primary/30 hover:shadow-md">
                        <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-2xl bg-accent/20 transition group-hover:bg-primary/10">
                            <svg class="h-7 w-7 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="mb-2 text-4xl font-bold text-primary/20">03</div>
                        <h3 class="text-lg font-semibold text-gray-900">Tarifi Al</h3>
                        <p class="mt-2 text-sm text-gray-500">Malzemeler, adımlar ve besin değerlerini gör</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA -->
        <section class="bg-gradient-to-r from-primary to-primary-light py-20">
            <div class="mx-auto max-w-7xl px-4 text-center sm:px-6 lg:px-8">
                <h2 class="text-3xl font-bold text-white">Hemen Deneyin</h2>
                <p class="mt-4 text-lg text-accent">Ücretsiz hesap oluşturun, ilk analizinizi yapın.</p>
                <div v-if="canLogin" class="mt-8 flex flex-col items-center gap-4 sm:flex-row sm:justify-center">
                    <Link
                        v-if="canRegister"
                        :href="route('register')"
                        class="rounded-xl bg-white px-8 py-3.5 text-base font-semibold text-primary shadow transition hover:bg-accent/20"
                    >
                        Kayıt Ol — Ücretsiz
                    </Link>
                    <Link
                        :href="route('login')"
                        class="rounded-xl border-2 border-white/40 px-8 py-3.5 text-base font-semibold text-white transition hover:border-white hover:bg-white/10"
                    >
                        Giriş Yap
                    </Link>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="border-t border-gray-100 py-8">
            <div class="mx-auto max-w-7xl px-4 text-center text-sm text-gray-400 sm:px-6 lg:px-8">
                &copy; {{ currentYear }} Yemek Tarifi. Tüm hakları saklıdır.
            </div>
        </footer>
    </div>
</template>
