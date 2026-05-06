<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const form = useForm({ photo: null });
const preview = ref(null);
const dragOver = ref(false);

function onFileSelect(event) {
    const file = event.target.files[0];
    setFile(file);
}

function onDrop(event) {
    dragOver.value = false;
    const file = event.dataTransfer.files[0];
    if (file) setFile(file);
}

function setFile(file) {
    form.photo = file;
    preview.value = URL.createObjectURL(file);
}

function submit() {
    form.post(route('recipes.store'), {
        forceFormData: true,
    });
}
</script>

<template>
    <Head title="Fotoğraf Yükle" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Yemek Fotoğrafı Yükle
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-2xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <form @submit.prevent="submit" class="space-y-6">
                            <div
                                class="flex flex-col items-center justify-center w-full h-64 border-2 border-dashed rounded-lg cursor-pointer transition-colors"
                                :class="dragOver ? 'border-indigo-500 bg-indigo-50' : 'border-gray-300 hover:bg-gray-50'"
                                @dragover.prevent="dragOver = true"
                                @dragleave="dragOver = false"
                                @drop.prevent="onDrop"
                                @click="$refs.fileInput.click()"
                            >
                                <img
                                    v-if="preview"
                                    :src="preview"
                                    class="max-h-56 object-contain rounded"
                                    alt="Seçilen fotoğraf"
                                />
                                <div v-else class="text-center text-gray-500 select-none">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                    <p class="mt-2 text-sm">Fotoğrafı buraya sürükleyin veya <span class="text-indigo-600 font-medium">dosya seçin</span></p>
                                    <p class="text-xs text-gray-400 mt-1">JPEG, PNG, WebP — maks. 8MB</p>
                                </div>
                            </div>

                            <input
                                ref="fileInput"
                                type="file"
                                class="hidden"
                                accept="image/jpeg,image/png,image/webp"
                                @change="onFileSelect"
                            />

                            <div v-if="form.errors.photo" class="text-sm text-red-600">
                                {{ form.errors.photo }}
                            </div>

                            <button
                                type="submit"
                                :disabled="!form.photo || form.processing"
                                class="w-full rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-500 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
                            >
                                {{ form.processing ? 'Yükleniyor...' : 'Tarifi Çıkar' }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
