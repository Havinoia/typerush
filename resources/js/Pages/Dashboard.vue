<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
</script>

<template>
    <Head title="Dashboard" />

    <AppLayout>
        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <!-- Dashboard Welcome Card -->
                <div class="glass-card overflow-hidden shadow-xl sm:rounded-3xl p-8 mb-8">
                    <h1 class="text-3xl font-black text-white uppercase italic tracking-tighter mb-2">Welcome Dashboard</h1>
                    <p class="text-gray-500">View your latest typing stats and progress below.</p>
                </div>

                <div class="bg-gray-800/10 backdrop-blur-md overflow-hidden shadow-sm sm:rounded-lg border border-white/5">
                    <div class="p-6 text-gray-200">
                        You're logged in! Ready for another run?
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
