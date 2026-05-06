<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    analyses: Object,
});

const statusLabel = {
    pending: 'Bekliyor',
    processing: 'İşleniyor',
    completed: 'Tamamlandı',
    failed: 'Hata',
};

const statusClass = {
    pending: 'bg-yellow-100 text-yellow-800',
    processing: 'bg-blue-100 text-blue-800',
    completed: 'bg-green-100 text-green-800',
    failed: 'bg-red-100 text-red-800',
};

function formatDate(dateStr) {
    return new Date(dateStr).toLocaleDateString('tr-TR', {
        day: 'numeric',
        month: 'long',
        year: 'numeric',
    });
}
</script>

<template>
    <Head title="Tarif Geçmişim" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold leading-tight text-gray-800">
                    Tarif Geçmişim
                </h2>
                <Link
                    :href="route('recipes.create')"
                    class="rounded-md bg-indigo-600 px-3 py-1.5 text-sm font-semibold text-white hover:bg-indigo-500"
                >
                    Yeni Analiz
                </Link>
            </div>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-4xl sm:px-6 lg:px-8">
                <div v-if="analyses.data.length === 0" class="overflow-hidden bg-white shadow-sm sm:rounded-lg p-8 text-center text-gray-500">
                    Henüz hiç tarif analizi yapmadınız.
                    <br />
                    <Link :href="route('recipes.create')" class="mt-4 inline-block text-indigo-600 hover:underline">
                        İlk tarifinizi çıkarın
                    </Link>
                </div>

                <div v-else class="overflow-hidden bg-white shadow-sm sm:rounded-lg divide-y divide-gray-100">
                    <Link
                        v-for="item in analyses.data"
                        :key="item.id"
                        :href="route('recipes.show', item.id)"
                        class="flex items-center gap-4 p-4 hover:bg-gray-50 transition-colors"
                    >
                        <div class="flex-1 min-w-0">
                            <p class="font-medium text-gray-900 truncate">
                                {{ item.food_name ?? 'Analiz ediliyor...' }}
                            </p>
                            <p class="text-sm text-gray-500 mt-0.5">
                                {{ formatDate(item.created_at) }}
                            </p>
                        </div>
                        <span
                            class="shrink-0 rounded-full px-2.5 py-0.5 text-xs font-medium"
                            :class="statusClass[item.status]"
                        >
                            {{ statusLabel[item.status] }}
                        </span>
                    </Link>
                </div>

                <!-- Sayfalama -->
                <div v-if="analyses.last_page > 1" class="mt-6 flex justify-center gap-2">
                    <Link
                        v-for="link in analyses.links"
                        :key="link.label"
                        :href="link.url ?? '#'"
                        class="px-3 py-1 text-sm rounded border"
                        :class="link.active ? 'bg-indigo-600 text-white border-indigo-600' : 'text-gray-700 border-gray-300 hover:bg-gray-50'"
                        v-html="link.label"
                    />
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
