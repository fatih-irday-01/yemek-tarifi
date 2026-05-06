<script setup>
import { ref } from 'vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import { Link } from '@inertiajs/vue3';

const showingNavigationDropdown = ref(false);
</script>

<template>
    <div class="min-h-screen bg-gray-50">
        <nav class="border-b border-accent/30 bg-white shadow-sm">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="flex h-16 justify-between">
                    <div class="flex items-center">
                        <!-- Logo -->
                        <div class="flex shrink-0 items-center">
                            <Link :href="route('dashboard')" class="flex items-center gap-2">
                                <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-primary">
                                    <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                    </svg>
                                </div>
                                <span class="text-lg font-bold text-primary">Yemek Tarifi</span>
                            </Link>
                        </div>

                        <!-- Navigation Links -->
                        <div class="hidden space-x-1 sm:ms-8 sm:flex">
                            <NavLink
                                :href="route('recipes.create')"
                                :active="route().current('recipes.create')"
                                class="rounded-lg px-3 py-2 text-sm font-medium transition"
                                :class="route().current('recipes.create')
                                    ? 'bg-primary/10 text-primary'
                                    : 'text-gray-600 hover:bg-gray-100 hover:text-primary'"
                            >
                                Yeni Tarif
                            </NavLink>
                            <NavLink
                                :href="route('history')"
                                :active="route().current('history')"
                                class="rounded-lg px-3 py-2 text-sm font-medium transition"
                                :class="route().current('history')
                                    ? 'bg-primary/10 text-primary'
                                    : 'text-gray-600 hover:bg-gray-100 hover:text-primary'"
                            >
                                Geçmişim
                            </NavLink>
                        </div>
                    </div>

                    <div class="hidden sm:ms-6 sm:flex sm:items-center">
                        <Dropdown align="right" width="48">
                            <template #trigger>
                                <button
                                    type="button"
                                    class="inline-flex items-center gap-2 rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm font-medium text-gray-700 transition hover:border-primary/30 hover:text-primary focus:outline-none"
                                >
                                    <div class="flex h-6 w-6 items-center justify-center rounded-full bg-primary/10 text-xs font-bold text-primary">
                                        {{ $page.props.auth.user.name.charAt(0).toUpperCase() }}
                                    </div>
                                    {{ $page.props.auth.user.name }}
                                    <svg class="h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </template>

                            <template #content>
                                <DropdownLink :href="route('profile.edit')">
                                    Profil
                                </DropdownLink>
                                <DropdownLink :href="route('logout')" method="post" as="button">
                                    Çıkış Yap
                                </DropdownLink>
                            </template>
                        </Dropdown>
                    </div>

                    <!-- Hamburger -->
                    <div class="-me-2 flex items-center sm:hidden">
                        <button
                            @click="showingNavigationDropdown = !showingNavigationDropdown"
                            class="inline-flex items-center justify-center rounded-md p-2 text-gray-400 transition hover:bg-gray-100 hover:text-primary focus:outline-none"
                        >
                            <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                <path :class="{ hidden: showingNavigationDropdown, 'inline-flex': !showingNavigationDropdown }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                <path :class="{ hidden: !showingNavigationDropdown, 'inline-flex': showingNavigationDropdown }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Responsive Navigation -->
            <div :class="{ block: showingNavigationDropdown, hidden: !showingNavigationDropdown }" class="sm:hidden">
                <div class="space-y-1 px-4 pb-3 pt-2">
                    <ResponsiveNavLink :href="route('recipes.create')" :active="route().current('recipes.create')">
                        Yeni Tarif
                    </ResponsiveNavLink>
                    <ResponsiveNavLink :href="route('history')" :active="route().current('history')">
                        Geçmişim
                    </ResponsiveNavLink>
                </div>

                <div class="border-t border-gray-200 pb-1 pt-4">
                    <div class="px-4">
                        <div class="text-base font-medium text-gray-800">{{ $page.props.auth.user.name }}</div>
                        <div class="text-sm font-medium text-gray-500">{{ $page.props.auth.user.email }}</div>
                    </div>
                    <div class="mt-3 space-y-1 px-4">
                        <ResponsiveNavLink :href="route('profile.edit')">Profil</ResponsiveNavLink>
                        <ResponsiveNavLink :href="route('logout')" method="post" as="button">Çıkış Yap</ResponsiveNavLink>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Page Heading -->
        <header v-if="$slots.header" class="bg-white shadow-sm">
            <div class="mx-auto max-w-7xl px-4 py-5 sm:px-6 lg:px-8">
                <slot name="header" />
            </div>
        </header>

        <!-- Page Content -->
        <main>
            <slot />
        </main>
    </div>
</template>
