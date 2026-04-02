<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

const props = defineProps({
    topResults: Array,
});
</script>

<template>
    <Head title="TypeRush - Leaderboard" />

    <AppLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-white leading-tight">
                Global Leaderboard
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
                <div class="glass-card border border-white/10 overflow-hidden shadow-xl">
                    <div class="p-4 sm:p-8 text-gray-100">
                        <div class="flex flex-col sm:flex-row justify-between items-center mb-8 space-y-4 sm:space-y-0">
                            <h3 class="text-2xl font-black text-amber-400 uppercase italic tracking-tighter">Top TypeRushers</h3>
                            <Link :href="route('typing.index')" class="w-full sm:w-auto text-center px-6 py-2 bg-indigo-500 hover:bg-indigo-600 rounded-xl text-sm font-black uppercase tracking-widest transition-all shadow-lg shadow-indigo-500/20">
                                Take a Test
                            </Link>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="w-full text-left">
                                <thead class="border-b border-gray-700">
                                    <tr class="text-gray-400 text-sm font-bold uppercase tracking-wider">
                                        <th class="py-4 px-6">Rank</th>
                                        <th class="py-4 px-6">User</th>
                                        <th class="py-4 px-6">WPM</th>
                                        <th class="py-4 px-6">Accuracy</th>
                                        <th class="py-4 px-6">Date</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-700">
                                    <tr v-for="(result, index) in topResults" :key="result.id" 
                                        :class="index < 3 ? 'bg-gray-700/30' : ''"
                                        class="hover:bg-gray-700/50 transition-colors">
                                        <td class="py-4 px-6 font-mono font-bold">
                                            <span v-if="index === 0" class="text-amber-500">🥇</span>
                                            <span v-else-if="index === 1" class="text-slate-400">🥈</span>
                                            <span v-else-if="index === 2" class="text-amber-700">🥉</span>
                                            <span v-else>#{{ index + 1 }}</span>
                                        </td>
                                        <td class="py-4 px-6">
                                            <div class="flex items-center space-x-3">
                                                <div class="w-8 h-8 rounded-full bg-gray-600 flex items-center justify-center font-bold text-white uppercase text-xs">
                                                    {{ result.user ? result.user.name[0] : 'G' }}
                                                </div>
                                                <span class="font-medium">{{ result.user ? result.user.name : 'Guest' }}</span>
                                            </div>
                                        </td>
                                        <td class="py-4 px-6">
                                            <span class="text-emerald-400 font-bold font-mono text-lg">{{ result.wpm }}</span>
                                        </td>
                                        <td class="py-4 px-6">
                                            <span class="text-sky-400 font-mono">{{ result.accuracy }}%</span>
                                        </td>
                                        <td class="py-4 px-6 text-gray-500 text-sm">
                                            {{ new Date(result.created_at).toLocaleDateString() }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
