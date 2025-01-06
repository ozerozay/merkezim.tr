<?php

new class extends Livewire\Volt\Component {
    public function show()
    {
        $this->dispatch('spotlight.toggle');
    }
};

?>
<div>
<div class="fixed bottom-6 right-6">
    <button 
        wire:click="show"
        class="relative flex h-16 w-16 items-center justify-center rounded-full bg-primary shadow-lg transition-all duration-500
               hover:shadow-[0_0_30px] hover:shadow-primary/50 active:scale-95
               before:absolute before:inset-0 before:rounded-full before:bg-primary/20 before:animate-ping before:duration-1000">
        
        <!-- Parıltı Efekti -->
        <div class="absolute inset-0 rounded-full bg-gradient-to-tr from-primary/0 via-primary-content/5 to-primary/0 animate-spotlight"></div>
        
        <!-- Sihirli Asa İkonu -->
        <svg xmlns="http://www.w3.org/2000/svg" 
             class="h-8 w-8 text-primary-content transition-transform duration-500 animate-pulse" 
             fill="none" 
             viewBox="0 0 24 24" 
             stroke="currentColor">
            <path stroke-linecap="round" 
                  stroke-linejoin="round" 
                  stroke-width="1.5"
                  d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z" />
            
            <!-- Yıldız Parıltıları -->
            <path class="animate-twinkle" 
                  stroke-linecap="round" 
                  stroke-linejoin="round" 
                  stroke-width="1.5"
                  d="M15 5l1-1m0 0l1-1m-1 1l-1-1m1 1l1 1m-6 12l1-1m0 0l1-1m-1 1l-1-1m1 1l1 1" />
        </svg>
    </button>
</div>

<style>
@keyframes spotlight {
    0%, 100% { opacity: 0; transform: rotate(0deg); }
    25% { opacity: 0.3; transform: rotate(90deg); }
    50% { opacity: 0.1; transform: rotate(180deg); }
    75% { opacity: 0.3; transform: rotate(270deg); }
}

.animate-spotlight {
    animation: spotlight 8s linear infinite;
}

@keyframes twinkle {
    0%, 100% { opacity: 1; transform: scale(1); }
    50% { opacity: 0.3; transform: scale(0.8); }
}

.animate-twinkle {
    animation: twinkle 2s ease-in-out infinite;
}
</style>
</div>