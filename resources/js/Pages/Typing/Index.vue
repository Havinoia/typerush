<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head, usePage } from '@inertiajs/vue3';
import { ref, onMounted, computed, watch, nextTick } from 'vue';
import axios from 'axios';

const props = defineProps({
    paragraphs: Array,
});

const currentParagraph = ref('');
const userInput = ref('');
const startTime = ref(null);
const timer = ref(60);
const isRunning = ref(false);
const isFinished = ref(false);
const totalChars = ref(0);
const correctChars = ref(0);
const totalKeystrokes = ref(0);
const mistakeCount = ref(0);
const timerInterval = ref(null);
const language = ref('en');
const lastResult = ref(JSON.parse(localStorage.getItem('typerush_last_test')) || null);
const showResultsModal = ref(false);
const wordStats = ref({ correct: 0, wrong: 0 });
const scrollOffset = ref(0);
const typingContainer = ref(null);

const deleteHistory = () => {
    lastResult.value = null;
    localStorage.removeItem('typerush_last_test');
};

const toggleLanguage = () => {
    language.value = language.value === 'en' ? 'id' : 'en';
    resetTest();
    fetchRandomParagraph();
};

const fetchRandomParagraph = async () => {
    try {
        const response = await axios.get(route('typing.random', { 
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
    timer.value = 60;
    totalChars.value = 0;
    correctChars.value = 0;
    totalKeystrokes.value = 0;
    mistakeCount.value = 0;
    showResultsModal.value = false;
    scrollOffset.value = 0;
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
                finishAndShowResults();
            }
        }, 1000);
    }
};

const finishTest = () => {
    isRunning.value = false;
    isFinished.value = true;
    clearInterval(timerInterval.value);
};

const finishAndShowResults = async () => {
    finishTest();
    calculateWordStats();
    showResultsModal.value = true;
    
    lastResult.value = {
        wpm: wpm.value,
        accuracy: accuracy.value,
        keystrokes: {
            correct: totalKeystrokes.value - mistakeCount.value,
            wrong: mistakeCount.value,
            total: totalKeystrokes.value
        },
        words: {
            correct: wordStats.value.correct,
            wrong: wordStats.value.wrong
        },
        timestamp: new Date().toLocaleTimeString()
    };
    
    localStorage.setItem('typerush_last_test', JSON.stringify(lastResult.value));
    await saveResult();
};

const saveResult = async () => {
    try {
        await axios.post(route('typing.store'), {
            wpm: wpm.value,
            accuracy: accuracy.value,
            total_chars: userInput.value.length,
            duration: 60 - timer.value,
        });
    } catch (error) {
        console.error('Failed to save result', error);
    }
};

const wpm = computed(() => {
    if (!userInput.value.length) return 0;
    const timeInSeconds = 60 - timer.value;
    if (timeInSeconds === 0) return 0;
    const timeInMinutes = timeInSeconds / 60;
    
    // Using standard (characters / 5) / time
    let correct = 0;
    const targetArr = currentParagraph.value.split('');
    userInput.value.split('').forEach((char, index) => {
        if (char === targetArr[index]) correct++;
    });
    
    return Math.round((correct / 5) / timeInMinutes);
});

const paragraphWords = computed(() => {
    if (!currentParagraph.value) return [];
    
    const words = [];
    let currentWord = { chars: [], startIndex: 0 };
    
    currentParagraph.value.split('').forEach((char, index) => {
        currentWord.chars.push({ char, index });
        if (char === ' ' || index === currentParagraph.value.length - 1) {
            currentWord.endIndex = index;
            words.push(currentWord);
            currentWord = { chars: [], startIndex: index + 1 };
        }
    });
    
    return words;
});

const activeWordRange = computed(() => {
    const pos = userInput.value.length;
    const word = paragraphWords.value.find(w => pos >= w.startIndex && pos <= w.endIndex);
    return word ? { start: word.startIndex, end: word.endIndex } : { start: 0, end: 0 };
});

const currentWordHasMistake = computed(() => {
    const { start, end } = activeWordRange.value;
    const input = userInput.value;
    const target = currentParagraph.value;
    
    for (let i = start; i < Math.min(input.length, end + 1); i++) {
        if (input[i] !== target[i]) return true;
    }
    return false;
});

const calculateWordStats = () => {
    const targetWords = currentParagraph.value.trim().split(/\s+/);
    const inputWords = userInput.value.trim().split(/\s+/);
    
    let correct = 0;
    let wrong = 0;
    
    inputWords.forEach((word, index) => {
        if (word === targetWords[index]) {
            correct++;
        } else {
            wrong++;
        }
    });
    
    wordStats.value = { correct, wrong };
};

const accuracy = computed(() => {
    if (totalKeystrokes.value === 0) return 100;
    const correctKeystrokes = totalKeystrokes.value - mistakeCount.value;
    return Math.round((correctKeystrokes / totalKeystrokes.value) * 100);
});

const handleKeyDown = (e) => {
    if (isFinished.value) return;
    
    // Ignore meta keys
    const ignoredKeys = ['Shift', 'Control', 'Alt', 'Meta', 'CapsLock', 'Tab', 'Escape', 'ArrowLeft', 'ArrowRight', 'ArrowUp', 'ArrowDown'];
    if (ignoredKeys.includes(e.key)) return;

    if (!isRunning.value) startTest();

    if (e.key === 'Backspace') {
        totalKeystrokes.value++;
        return;
    }

    totalKeystrokes.value++;
    
    // Check for mistake
    const nextIndex = userInput.value.length;
    if (e.key !== currentParagraph.value[nextIndex]) {
        mistakeCount.value++;
    }
};

const updateScroll = () => {
    if (!typingContainer.value) return;
    const activeSpan = typingContainer.value.querySelector('.relative.active-char');
    if (activeSpan) {
        const offsetTop = activeSpan.offsetTop;
        
        // 10FastFingers Sliding Logic: 
        // We set the scroll offset to match the offsetTop of the current line.
        // This ensures the current line you are typing is always the TOP line.
        scrollOffset.value = offsetTop;
    }
};

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
        finishAndShowResults();
    }
    nextTick(() => updateScroll());
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

        <div class="py-12 relative overflow-x-hidden">
            <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 relative">
                <!-- History Sidebar (Pinned Left) -->
                <div v-if="lastResult" class="hidden lg:block absolute -left-72 top-0 w-64 transition-all duration-500 animate-in fade-in slide-in-from-left-4">
                    <div class="glass-card border border-white/10 p-6 relative group">
                        <!-- Delete History Button -->
                        <button @click="deleteHistory" class="absolute -top-2 -right-2 w-6 h-6 bg-rose-500 hover:bg-rose-600 text-white rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all shadow-lg z-20">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>

                        <h3 class="text-xs font-black text-amber-400 uppercase tracking-widest mb-4 flex items-center">
                            <span class="w-2 h-2 bg-amber-400 rounded-full mr-2"></span>
                            Last Test
                        </h3>
                        <div class="space-y-4">
                            <div class="flex justify-between items-end">
                                <span class="text-gray-400 text-xs uppercase font-bold">WPM</span>
                                <span class="text-2xl font-mono text-emerald-400">{{ lastResult.wpm }}</span>
                            </div>
                            <div class="flex justify-between items-end border-t border-white/5 pt-3">
                                <span class="text-gray-400 text-xs uppercase font-bold">Accuracy</span>
                                <span class="text-xl font-mono text-sky-400">{{ lastResult.accuracy }}%</span>
                            </div>
                            <div class="flex justify-between items-end border-t border-white/5 pt-3">
                                <span class="text-gray-400 text-xs uppercase font-bold">Keystrokes</span>
                                <span class="text-sm font-mono text-white">
                                    <span class="text-emerald-400">{{ lastResult.keystrokes.correct }}</span> / 
                                    <span class="text-rose-400">{{ lastResult.keystrokes.wrong }}</span>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Main Grid -->
                <div class="w-full">
                    <!-- Stats Bar -->
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
                        <div class="glass-card p-6 border border-white/10 flex flex-row sm:flex-col justify-between sm:justify-start items-center sm:items-start">
                            <div>
                                <div class="text-gray-400 text-xs uppercase font-black tracking-widest mb-1">Time</div>
                                <div class="text-3xl font-mono text-amber-400">{{ timer }}s</div>
                            </div>
                            <div class="sm:hidden text-gray-600">/60s</div>
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

                    <!-- Typing Container -->
                                        <div class="glass-card border border-white/10 p-4 sm:p-8 relative">
                        <!-- Original Test Over Overlay (Keep as fallback) -->
                        <div v-if="isFinished && !showResultsModal" class="absolute inset-0 z-[60] bg-gray-900/90 flex flex-col items-center justify-center backdrop-blur-sm">
                            <button @click="resetTest(); fetchRandomParagraph()" class="px-8 py-3 bg-indigo-500 hover:bg-indigo-600 text-white rounded-xl font-black uppercase tracking-widest transition-all">
                                New Test
                            </button>
                        </div>

                        <div class="flex justify-start items-center mb-6 relative z-[70]">
                            <div class="flex items-center space-x-2">
                                <button @click="toggleLanguage" 
                                    class="text-gray-400 hover:text-white transition-colors p-2 hover:bg-white/5 rounded-lg flex items-center space-x-2 min-w-[60px] justify-center group">
                                    <span class="text-xs font-black uppercase tracking-widest group-hover:text-amber-400 transition-colors">{{ language }}</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 opacity-50 group-hover:opacity-100 transition-opacity" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M4.293 15.707a1 1 0 010-1.414l5-5a1 1 0 011.414 0l5 5a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414 0zm0-6a1 1 0 010-1.414l5-5a1 1 0 011.414 0l5 5a1 1 0 01-1.414 1.414L10 5.414 5.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <!-- 2-Line Scrolling Typing Area -->
                        <div class="relative h-[3.8em] overflow-hidden" ref="typingContainer">
                            <div class="text-base sm:text-2xl font-mono leading-[1.9] select-none relative mb-4 transition-transform duration-400 ease-[cubic-bezier(0.4,0,0.2,1)] pointer-events-none text-justify"
                                style="text-justify: inter-word;"
                                :style="{ transform: `translateY(-${scrollOffset}px)` }">
                                <div v-for="(wordObj, wIndex) in paragraphWords" :key="wIndex" 
                                    class="inline-block"
                                    :class="[
                                        userInput.length >= wordObj.startIndex && userInput.length <= wordObj.endIndex 
                                            ? (currentWordHasMistake ? 'bg-rose-500/10' : 'bg-white/5') 
                                            : ''
                                    ]"
                                    style="border-radius: 4px;">
                                    <span v-for="charObj in wordObj.chars" :key="charObj.index" 
                                        :class="[
                                            getCharClass(charObj.index), 
                                            charObj.index === userInput.length ? 'relative active-char text-indigo-400 scale-110 drop-shadow-[0_0_10px_rgba(129,140,248,0.4)]' : ''
                                        ]"
                                        class="inline-block transition-all duration-150 px-[0.2px] font-mono">
                                        <span v-if="charObj.index === userInput.length" 
                                            class="absolute w-[3px] h-[1.3em] bg-indigo-500 shadow-[0_0_20px_rgba(129,140,248,1)] -left-[1.5px] top-1/2 -translate-y-1/2 z-10 rounded-full animate-pulse transition-all duration-75">
                                        </span>
                                        {{ charObj.char === ' ' ? '\u00A0' : charObj.char }}
                                    </span>
                                </div>
                                <span v-if="userInput.length === currentParagraph.length" 
                                    class="inline-block w-0.5 h-[1.2em] bg-amber-400 animate-pulse ml-[1px] align-middle"></span>
                            </div>
                        </div>

                        <textarea
                            v-model="userInput"
                            class="absolute inset-0 opacity-0 cursor-default resize-none z-50 overflow-hidden"
                            style="caret-color: transparent;"
                            autofocus
                            :disabled="isFinished"
                            @keydown="handleKeyDown"
                            @keydown.tab.prevent
                        ></textarea>

                        <div class="mt-8 text-center" v-if="!isRunning && !isFinished">
                            <p class="text-gray-500 text-sm italic animate-pulse">Start typing to begin the test</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Results Modal -->
        <div v-if="showResultsModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-gray-900/80 backdrop-blur-sm transition-all duration-500">
            <div class="glass-card border border-white/20 w-full max-w-xl overflow-hidden shadow-2xl scale-in-center overflow-y-auto max-h-[90vh]">
                <div class="p-8 text-center">
                    <h3 class="text-6xl font-mono text-emerald-400 mb-2">{{ wpm }}</h3>
                    <p class="text-gray-400 uppercase font-black tracking-widest text-sm mb-8">Words Per Minute</p>
                    
                    <div class="grid grid-cols-2 gap-4 mb-8">
                        <div class="bg-white/5 rounded-2xl p-6 border border-white/5">
                            <div class="text-gray-400 text-[10px] uppercase font-black tracking-widest mb-1">Keystrokes</div>
                            <div class="text-lg font-mono flex justify-center space-x-1">
                                <span class="text-emerald-400">{{ totalKeystrokes - mistakeCount }}</span>
                                <span class="text-gray-600">|</span>
                                <span class="text-rose-400">{{ mistakeCount }}</span>
                                <span class="text-gray-400 text-xs ml-2 self-center">{{ totalKeystrokes }}</span>
                            </div>
                        </div>
                        <div class="bg-white/5 rounded-2xl p-6 border border-white/5">
                            <div class="text-gray-400 text-[10px] uppercase font-black tracking-widest mb-1">Accuracy</div>
                            <div class="text-lg font-mono text-sky-400">{{ accuracy }}%</div>
                        </div>
                        <div class="bg-white/5 rounded-2xl p-6 border border-white/5">
                            <div class="text-gray-400 text-[10px] uppercase font-black tracking-widest mb-1">Correct Words</div>
                            <div class="text-lg font-mono text-emerald-400">{{ wordStats.correct }}</div>
                        </div>
                        <div class="bg-white/5 rounded-2xl p-6 border border-white/5">
                            <div class="text-gray-400 text-[10px] uppercase font-black tracking-widest mb-1">Wrong Words</div>
                            <div class="text-lg font-mono text-rose-400">{{ wordStats.wrong }}</div>
                        </div>
                    </div>

                    <div class="flex space-x-4">
                        <button @click="resetTest(); fetchRandomParagraph()" class="flex-grow py-4 bg-indigo-500 hover:bg-indigo-600 text-white rounded-2xl font-black uppercase tracking-widest transition-all shadow-lg shadow-indigo-500/20">
                            Try Again
                        </button>
                        <button @click="showResultsModal = false" class="px-8 py-4 bg-gray-800 hover:bg-gray-700 text-gray-300 rounded-2xl font-black uppercase tracking-widest transition-all">
                            Close
                        </button>
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
