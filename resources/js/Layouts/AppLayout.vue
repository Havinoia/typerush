<script setup>
import { ref } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';

const showingNavigationDropdown = ref(false);
const page = usePage();
const user = page.props.auth?.user;
</script>

<template>
    <div class="min-h-screen flex flex-col bg-gray-900 pb-12 sm:pb-0">
        <!-- Floating Glass Navbar -->
        <nav class="fixed top-4 left-1/2 -translate-x-1/2 w-[95%] max-w-7xl z-50 glass rounded-2xl shadow-2xl transition-all duration-300">
            <div class="mx-auto px-6 lg:px-8">
                <div class="flex h-16 justify-between items-center">
                    <div class="flex items-center">
                        <!-- Logo -->
                        <div class="flex shrink-0 items-center group">
                            <Link :href="route('typing.index')" class="flex items-center">
                                <div class="w-10 h-10 bg-gradient-to-tr from-amber-400 to-amber-600 rounded-xl flex items-center justify-center shadow-lg shadow-amber-500/20 group-hover:rotate-12 transition-transform duration-300">
                                    <ApplicationLogo class="h-6 w-auto fill-current text-white" />
                                </div>
                                <span class="ml-3 text-2xl font-black text-white tracking-tighter uppercase italic group-hover:text-amber-400 transition-colors">TypeRush</span>
                            </Link>
                        </div>

                        <!-- Navigation Links -->
                        <div class="hidden space-x-1 sm:ms-12 sm:flex items-center">
                            <NavLink :href="route('typing.index')" :active="route().current('typing.index')" class="px-4 py-2 rounded-xl hover:bg-white/5 transition-all">
                                Typing Test
                            </NavLink>
                            <NavLink :href="route('typing.leaderboard')" :active="route().current('typing.leaderboard')" class="px-4 py-2 rounded-xl hover:bg-white/5 transition-all">
                                Leaderboard
                            </NavLink>
                            <NavLink v-if="user" :href="route('dashboard')" :active="route().current('dashboard')" class="px-4 py-2 rounded-xl hover:bg-white/5 transition-all">
                                Dashboard
                            </NavLink>
                        </div>
                    </div>

                    <div class="hidden sm:ms-6 sm:flex sm:items-center space-x-6">
                        <!-- Auth Links -->
                        <template v-if="user">
                            <div class="relative">
                                <Dropdown align="right" width="48">
                                    <template #trigger>
                                        <span class="inline-flex rounded-md">
                                            <button type="button" class="inline-flex items-center rounded-xl px-4 py-2 text-sm font-bold text-gray-200 transition-all hover:bg-white/5 border border-white/10 hover:border-white/20">
                                                {{ user.name }}
                                                <svg class="-me-0.5 ms-2 h-4 w-4 opacity-50" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                </svg>
                                            </button>
                                        </span>
                                    </template>

                                    <template #content>
                                        <DropdownLink :href="route('profile.edit')">Profile</DropdownLink>
                                        <div class="border-t border-gray-700/50 my-1"></div>
                                        <DropdownLink :href="route('logout')" method="post" as="button" class="text-rose-400">Log Out</DropdownLink>
                                    </template>
                                </Dropdown>
                            </div>
                        </template>
                        <template v-else>
                            <Link :href="route('login')" class="text-sm font-bold text-gray-400 hover:text-white transition-colors">Log in</Link>
                            <Link :href="route('register')" class="text-sm px-6 py-2 bg-gradient-to-r from-indigo-500 to-indigo-600 hover:from-indigo-600 hover:to-indigo-700 text-white rounded-xl font-black transition-all shadow-xl shadow-indigo-500/20 active:scale-95">REGISTER</Link>
                        </template>
                    </div>

                    <!-- Hamburger -->
                    <div class="-me-2 flex items-center sm:hidden">
                        <button @click="showingNavigationDropdown = !showingNavigationDropdown" class="inline-flex items-center justify-center rounded-xl p-2 text-gray-400 hover:bg-white/5 hover:text-white transition-all">
                            <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                <path :class="{ hidden: showingNavigationDropdown, 'inline-flex': !showingNavigationDropdown }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                <path :class="{ hidden: !showingNavigationDropdown, 'inline-flex': showingNavigationDropdown }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Responsive Navigation Menu -->
            <div :class="{ block: showingNavigationDropdown, hidden: !showingNavigationDropdown }" class="sm:hidden border-t border-white/5">
                <div class="space-y-1 pb-3 pt-2 px-4">
                    <ResponsiveNavLink :href="route('typing.index')" :active="route().current('typing.index')">Typing Test</ResponsiveNavLink>
                    <ResponsiveNavLink :href="route('typing.leaderboard')" :active="route().current('typing.leaderboard')">Leaderboard</ResponsiveNavLink>
                    <ResponsiveNavLink v-if="user" :href="route('dashboard')" :active="route().current('dashboard')">Dashboard</ResponsiveNavLink>
                </div>
                <div v-if="user" class="border-t border-white/5 pb-1 pt-4">
                    <div class="px-6">
                        <div class="text-base font-bold text-white uppercase">{{ user.name }}</div>
                        <div class="text-sm font-medium text-gray-400">{{ user.email }}</div>
                    </div>
                    <div class="mt-3 space-y-1 px-4 pb-4">
                        <ResponsiveNavLink :href="route('profile.edit')">Profile</ResponsiveNavLink>
                        <ResponsiveNavLink :href="route('logout')" method="post" as="button" class="text-rose-400">Log Out</ResponsiveNavLink>
                    </div>
                </div>
                <div v-else class="border-t border-white/5 py-6 px-6 space-y-3">
                    <Link :href="route('login')" class="block w-full text-center py-3 text-gray-400 hover:text-white border border-white/10 rounded-xl font-bold">Log in</Link>
                    <Link :href="route('register')" class="block w-full text-center py-3 bg-indigo-500 text-white rounded-xl font-black shadow-lg shadow-indigo-500/20">REGISTER</Link>
                </div>
            </div>
        </nav>

        <!-- Page Content -->
        <main class="flex-grow pt-32 px-4 sm:px-6 lg:px-8">
            <slot />
        </main>

        <!-- Footer -->
        <footer class="py-12 border-t border-white/5 bg-gray-900/50">
            <div class="max-w-7xl mx-auto px-6 text-center">
                <p class="text-gray-500 text-sm italic">Built with performance and speed in mind. © 2026 TypeRush.</p>
            </div>
        </footer>
    </div>
</template>
