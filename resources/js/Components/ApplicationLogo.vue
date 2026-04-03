<template>
    <svg viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
        <!-- Lightning Aura (Back Glow) -->
        <path d="M16 2L12 14H18L14 30" 
            stroke="white" 
            stroke-width="4" 
            stroke-linecap="round" 
            stroke-linejoin="round"
            class="opacity-20 blur-sm animate-pulse" />

        <!-- Stylized Finger (Index Finger Pressing) -->
        <path d="M14 8C14 6.89543 14.8954 6 16 6C17.1046 6 18 6.89543 18 8V18C18 19.1046 17.1046 20 16 20C14.8954 20 14 19.1046 14 18V8Z" 
            fill="currentColor" />
        <path d="M12 12C12 10.8954 12.8954 10 14 10H18C19.1046 10 20 10.8954 20 12V22C20 25.3137 17.3137 28 14 28H12V12Z" 
            fill="currentColor" opacity="0.5" />

        <!-- Sharp Lightning Bolt (Front) -->
        <path d="M16 2L12 14H18L14 30" 
            stroke="white" 
            stroke-width="2.5" 
            stroke-linecap="round" 
            stroke-linejoin="round"
            class="drop-shadow-[0_0_8px_rgba(255,255,255,1)] animate-flash" />
    </svg>
</template>

<style scoped>
@keyframes flash {
    0%, 100% { filter: drop-shadow(0 0 5px rgba(255, 255, 255, 1)); opacity: 1; }
    50% { filter: drop-shadow(0 0 15px rgba(255, 255, 255, 1)); opacity: 0.8; }
}
.animate-flash {
    animation: flash 1s ease-in-out infinite;
}
</style>
