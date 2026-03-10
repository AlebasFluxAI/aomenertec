<button type="button"
    wire:click="redirectNotification"
    class="w-full bg-[#ebf8ff] text-[#0044a4] rounded-xl flex items-center justify-center py-2 font-medium text-sm gap-2 hover:bg-[#d1f0ff] transition-colors"
    style="border: none; cursor: pointer;"
>
    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
        <path d="M18 8V6a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v7a2 2 0 0 0 2 2h8"></path>
        <path d="M10 19v-3.96 3.15"></path>
        <path d="M7 19h5"></path>
        <rect width="6" height="10" x="16" y="12" rx="2"></rect>
    </svg>
    <span>{{ $notificationCounter }}</span>
</button>
