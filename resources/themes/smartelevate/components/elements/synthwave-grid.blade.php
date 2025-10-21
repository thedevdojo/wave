<style>
    @keyframes grid {
            0% { transform: translateY(-50%); }
            100% { transform: translateY(0); }
        }
        .animate-grid {
            animation: grid 15s linear infinite;
        }
        .synthwave-grid {
            background-repeat: repeat;
            background-size: 60px 60px;
            height: 300vh;
            inset: 0% 0px;
            margin-left: -50%;
            transform-origin: 100% 0 0;
            width: 600vw;
        }
        :not(.dark) .synthwave-grid-light {
            background-image: 
                linear-gradient(to right, rgba(0,0,0,0.4) 1px, transparent 0),
                linear-gradient(to bottom, rgba(0,0,0,0.4) 1px, transparent 0);
        }
        .dark .synthwave-grid-dark {
            background-image: 
                linear-gradient(to right, rgba(255,255,255,0.7) 1px, transparent 0),
                linear-gradient(to bottom, rgba(255,255,255,0.7) 1px, transparent 0);
        }
</style>
<div x-data="{ angle: 45 }" class="relative flex flex-col items-center justify-center w-full h-full overflow-hidden border-0 rounded-lg" x-cloak>
    <div class="absolute inset-0 w-full h-full overflow-hidden opacity-50 pointer-events-none" style="perspective: 200px;">
        <div class="absolute inset-0" :style="{ transform: `rotateX(${angle}deg)` }">
            <div class="animate-grid synthwave-grid synthwave-grid-light synthwave-grid-dark"></div>
        </div>
        <div class="absolute inset-0 bg-gradient-to-t from-white to-transparent to-90% dark:from-black"></div>
    </div>
</div>