<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head, usePage } from '@inertiajs/vue3';
import { ref, onMounted, computed, watch } from 'vue';
import axios from 'axios';

const props = defineProps({
    paragraphs: Array,
});

const currentParagraph = ref('');
const userInput = ref('');
const startTime = ref(null);
const timer = ref(30);
const isRunning = ref(false);
const isFinished = ref(false);
const difficulty = ref('medium');
const totalChars = ref(0);
const correctChars = ref(0);
const timerInterval = ref(null);
const language = ref('en');

const fetchRandomParagraph = async () => {
    try {
        const response = await axios.get(route('typing.random', { 
            difficulty: difficulty.value,
            language: language.value
        }));
        currentParagraph.value = response.data.content;
        resetTest();
    } catch (error) {
        console.error('Failed to fetch paragraph', error);
    }
};

const resetTest = () => {
    userInput.value = '';
    isRunning.value = false;
    isFinished.value = false;
    startTime.value = null;
    timer.value = 30;
    totalChars.value = 0;
    correctChars.value = 0;
    clearInterval(timerInterval.value);
};

const startTest = () => {
    if (!isRunning.value && !isFinished.value) {
        isRunning.value = true;
        startTime.value = Date.now();
        timerInterval.value = setInterval(() => {
            if (timer.value > 0) {
                timer.value--;
            } else {
                finishTest();
            }
        }, 1000);
    }
};

const finishTest = () => {
    isRunning.value = false;
    isFinished.value = true;
    clearInterval(timerInterval.value);
    saveResult();
};

const saveResult = async () => {
    try {
        await axios.post(route('typing.store'), {
            wpm: wpm.value,
            accuracy: accuracy.value,
            total_chars: userInput.value.length,
            duration: 30 - timer.value,
        });
    } catch (error) {
        console.error('Failed to save result', error);
    }
};

const wpm = computed(() => {
    if (!userInput.value.length) return 0;
    const timeInMinutes = (30 - timer.value) / 60 || 0.01;
    return Math.round((userInput.value.length / 5) / timeInMinutes);
});

const accuracy = computed(() => {
    if (!userInput.value.length) return 100;
    let correct = 0;
    const inputArr = userInput.value.split('');
    const targetArr = currentParagraph.value.split('');
    
    inputArr.forEach((char, index) => {
        if (char === targetArr[index]) {
            correct++;
        }
    });
    
    return Math.round((correct / inputArr.length) * 100);
});

const getCharClass = (index) => {
    if (index >= userInput.value.length) return 'text-gray-500';
    return userInput.value[index] === currentParagraph.value[index] 
        ? 'text-emerald-400' 
        : 'text-rose-500 bg-rose-500/20 rounded';
};

watch(userInput, (newVal) => {
    if (newVal.length > 0 && !isRunning.value && !isFinished.value) {
        startTest();
    }
    if (newVal.length >= currentParagraph.value.length && isRunning.value) {
        finishTest();
    }
});

onMounted(() => {
    fetchRandomParagraph();
});
</script>

<template>
    <Head title="TypeRush - Typing Test" />

    <AppLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-white leading-tight">
                TypeRush Challenge
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
                <!-- Stats Bar -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
                    <div class="glass-card p-6 border border-white/10 flex flex-row sm:flex-col justify-between sm:justify-start items-center sm:items-start">
                        <div>
                            <div class="text-gray-400 text-xs uppercase font-black tracking-widest mb-1">Time</div>
                            <div class="text-3xl font-mono text-amber-400">{{ timer }}s</div>
                        </div>
                        <div class="sm:hidden text-gray-600">/30s</div>
                    </div>
                    <div class="glass-card p-6 border border-white/10 flex flex-row sm:flex-col justify-between sm:justify-start items-center sm:items-start">
                        <div>
                            <div class="text-gray-400 text-xs uppercase font-black tracking-widest mb-1">WPM</div>
                            <div class="text-3xl font-mono text-emerald-400">{{ wpm }}</div>
                        </div>
                    </div>
                    <div class="glass-card p-6 border border-white/10 flex flex-row sm:flex-col justify-between sm:justify-start items-center sm:items-start">
                        <div>
                            <div class="text-gray-400 text-xs uppercase font-black tracking-widest mb-1">Accuracy</div>
                            <div class="text-3xl font-mono text-sky-400">{{ accuracy }}%</div>
                        </div>
                    </div>
                </div>

                <!-- Typing Area -->
                <div class="glass-card border border-white/10 p-4 sm:p-8 relative">
                    <div v-if="isFinished" class="absolute inset-0 z-30 bg-gray-900/90 flex flex-col items-center justify-center backdrop-blur-sm transition-all duration-500">
                        <h3 class="text-4xl font-bold text-white mb-4 animate-bounce">Test Over!</h3>
                        <div class="flex space-x-4">
                            <button @click="fetchRandomParagraph" class="px-6 py-2 bg-emerald-500 hover:bg-emerald-600 text-white rounded-lg font-bold transition-all transform hover:scale-105 shadow-lg shadow-emerald-500/20">
                                Try Again
                            </button>
                            <a :href="route('typing.leaderboard')" class="px-6 py-2 bg-gray-700 hover:bg-gray-600 text-white rounded-lg font-bold transition-all">
                                Leaderboard
                            </a>
                        </div>
                    </div>

                    <div class="flex justify-between items-center mb-6 relative z-20">
                        <div class="flex items-center space-x-6">
                            <!-- Difficulty Selector -->
                            <div class="flex space-x-2">
                                <button v-for="d in ['easy', 'medium', 'hard']" :key="d" 
                                    @click="difficulty = d; fetchRandomParagraph()"
                                    :class="[
                                        'px-3 py-1 rounded text-xs uppercase font-bold transition-all',
                                        difficulty === d ? 'bg-indigo-500 text-white shadow-lg shadow-indigo-500/50' : 'bg-gray-700 text-gray-400 hover:bg-gray-600'
                                    ]">
                                    {{ d }}
                                </button>
                            </div>
                        </div>
                        <div class="flex items-center space-x-2">
                            <button @click="language = language === 'en' ? 'id' : 'en'; fetchRandomParagraph()" 
                                class="text-gray-400 hover:text-white transition-colors p-2 hover:bg-white/5 rounded-lg flex items-center space-x-2 min-w-[60px] justify-center group">
                                <span class="text-xs font-black uppercase tracking-widest group-hover:text-amber-400 transition-colors">{{ language }}</span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 opacity-50 group-hover:opacity-100 transition-opacity" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M4.293 15.707a1 1 0 010-1.414l5-5a1 1 0 011.414 0l5 5a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414 0zm0-6a1 1 0 010-1.414l5-5a1 1 0 011.414 0l5 5a1 1 0 01-1.414 1.414L10 5.414 5.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div class="text-base sm:text-xl font-mono leading-relaxed select-none relative mb-4 flex flex-wrap">
                        <span v-for="(char, index) in currentParagraph" :key="index" 
                            :class="[getCharClass(index), index === userInput.length ? 'relative' : '']"
                            class="transition-colors duration-100">
                            <!-- Inline Cursor -->
                            <span v-if="index === userInput.length" 
                                class="absolute w-0.5 h-[1.2em] bg-amber-400 animate-pulse -left-[1px] top-1/2 -translate-y-1/2 z-10">
                            </span>
                            {{ char === ' ' ? '\u00A0' : char }}
                        </span>
                        <!-- Cursor for the end of the text -->
                        <span v-if="userInput.length === currentParagraph.length" 
                            class="inline-block w-0.5 h-[1.2em] bg-amber-400 animate-pulse ml-[1px] align-middle"></span>
                    </div>

                    <textarea
                        v-model="userInput"
                        class="absolute inset-0 opacity-0 cursor-default resize-none"
                        autofocus
                        :disabled="isFinished"
                        @keydown.tab.prevent
                    ></textarea>

                    <div class="mt-8 text-center">
                        <p class="text-gray-500 text-sm italic">Start typing to begin the test</p>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<style scoped>
.font-mono {
    font-family: 'JetBrains Mono', monospace;
    letter-spacing: -0.02em;
    word-spacing: 0.1em;
}

.leading-relaxed {
    line-height: 1.8;
}

@keyframes pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0; }
}

.animate-pulse {
    animation: pulse 1s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}

/* Custom highlight for error characters */
.text-rose-500 {
    text-decoration: underline wavy #f43f5e 2px;
}
</style>
