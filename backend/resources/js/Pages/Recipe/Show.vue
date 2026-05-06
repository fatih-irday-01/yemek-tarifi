<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import { onMounted, onUnmounted, ref } from 'vue';

const props = defineProps({
    analysis: Object,
});

const current = ref(props.analysis);
let pollInterval = null;

function startPolling() {
    if (['completed', 'failed'].includes(current.value.status)) return;

    pollInterval = setInterval(async () => {
        try {
            const res = await fetch(route('recipes.status', current.value.id), {
                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
            });
            const data = await res.json();
            current.value = { ...current.value, ...data };

            if (['completed', 'failed'].includes(data.status)) {
                clearInterval(pollInterval);
            }
        } catch {
            clearInterval(pollInterval);
        }
    }, 2000);
}

onMounted(startPolling);
onUnmounted(() => clearInterval(pollInterval));
</script>

<template>
    <Head :title="current.food_name ?? 'Analiz Ediliyor...'" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center gap-3">
                <h2 class="text-xl font-semibold leading-tight text-gray-800">
                    {{ current.food_name ?? 'Tarif Analizi' }}
                </h2>
                <span
                    class="rounded-full px-2.5 py-0.5 text-xs font-medium"
                    :class="{
                        'bg-yellow-100 text-yellow-800': current.status === 'pending' || current.status === 'processing',
                        'bg-green-100 text-green-800': current.status === 'completed',
                        'bg-red-100 text-red-800': current.status === 'failed',
                    }"
                >
                    {{ { pending: 'Bekliyor', processing: 'İşleniyor', completed: 'Tamamlandı', failed: 'Hata' }[current.status] }}
                </span>
            </div>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-3xl sm:px-6 lg:px-8 space-y-6">

                <!-- Bekliyor / İşleniyor -->
                <div v-if="current.status === 'pending' || current.status === 'processing'"
                     class="overflow-hidden bg-white shadow-sm sm:rounded-lg p-8 text-center">
                    <div class="mx-auto h-12 w-12 animate-spin rounded-full border-4 border-indigo-500 border-t-transparent"></div>
                    <p class="mt-4 text-gray-600">Yemeğiniz analiz ediliyor, lütfen bekleyin...</p>
                </div>

                <!-- Hata -->
                <div v-else-if="current.status === 'failed'"
                     class="overflow-hidden bg-white shadow-sm sm:rounded-lg p-6">
                    <p class="text-red-600 font-medium">Analiz sırasında bir hata oluştu.</p>
                    <p class="text-sm text-gray-500 mt-1">{{ current.error_message }}</p>
                    <a :href="route('recipes.create')" class="mt-4 inline-block text-sm text-indigo-600 hover:underline">
                        Tekrar dene
                    </a>
                </div>

                <!-- Tamamlandı -->
                <template v-else-if="current.status === 'completed' && current.recipe">
                    <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Malzemeler</h3>
                            <ul class="space-y-1">
                                <li
                                    v-for="(ing, i) in current.recipe.ingredients"
                                    :key="i"
                                    class="flex items-start gap-2 text-sm text-gray-700"
                                >
                                    <span class="mt-1 h-1.5 w-1.5 shrink-0 rounded-full bg-indigo-500"></span>
                                    {{ ing }}
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Yapılış</h3>
                            <ol class="space-y-3">
                                <li
                                    v-for="(step, i) in current.recipe.steps"
                                    :key="i"
                                    class="flex gap-3 text-sm text-gray-700"
                                >
                                    <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-indigo-100 text-indigo-700 font-semibold text-xs">
                                        {{ i + 1 }}
                                    </span>
                                    {{ step }}
                                </li>
                            </ol>
                        </div>
                    </div>

                    <div class="flex gap-4 text-sm text-gray-500">
                        <span>⏱ {{ current.recipe.cooking_time }}</span>
                        <span>👥 {{ current.recipe.servings }} kişilik</span>
                    </div>
                </template>

                <div class="flex gap-4">
                    <a :href="route('recipes.create')" class="text-sm text-indigo-600 hover:underline">
                        Yeni fotoğraf yükle
                    </a>
                    <a :href="route('history')" class="text-sm text-gray-500 hover:underline">
                        Geçmişi görüntüle
                    </a>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
