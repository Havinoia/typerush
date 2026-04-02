<script setup>
import { Link } from '@inertiajs/vue3';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
</script>

<template>
    <div class="min-h-screen flex flex-col items-center justify-center p-6 bg-gray-900 border-indigo-500/20">
        <!-- Background Decoration -->
        <div class="fixed top-0 left-0 w-full h-full overflow-hidden -z-10 pointer-events-none opacity-20">
            <div class="absolute top-[-10%] left-[-10%] w-[40%] h-[40%] bg-indigo-500 rounded-full blur-[120px] animate-pulse"></div>
            <div class="absolute bottom-[-10%] right-[-10%] w-[40%] h-[40%] bg-amber-500 rounded-full blur-[120px] animate-pulse" style="animation-delay: 2s;"></div>
        </div>

        <div class="w-full max-w-md">
            <!-- Logo area -->
            <div class="flex flex-col items-center mb-10 group">
                <Link href="/" class="flex flex-col items-center">
                    <div class="w-16 h-16 bg-gradient-to-tr from-amber-400 to-amber-600 rounded-2xl flex items-center justify-center shadow-2xl shadow-amber-500/40 group-hover:rotate-12 transition-all duration-500 scale-110">
                        <ApplicationLogo class="h-10 w-auto fill-current text-white" />
                    </div>
                    <h1 class="mt-6 text-4xl font-black text-white tracking-tighter uppercase italic group-hover:text-amber-400 transition-colors">TypeRush</h1>
                </Link>
            </div>

            <!-- Form Card -->
            <div class="glass-card rounded-3xl p-8 sm:p-10 border border-white/10 animate-float">
                <slot />
            </div>

            <!-- Footer links -->
            <div class="mt-8 text-center">
                <Link href="/" class="text-gray-500 hover:text-white transition-colors text-sm font-medium">
                    ← Back to Typing Test
                </Link>
            </div>
        </div>
    </div>
</template>

<style scoped>
.animate-float {
    animation: float 8s ease-in-out infinite;
}
</style>
