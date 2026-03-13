<div style="z-index: 100" class="fixed {{$positionCss}} @if($hideOnClick) cursor-pointer @endif"
     x-data="{
        show: false,
        timeout: null,
        getDuration() {
            var type = '{{ $type }}';
            if (type === 'error' || type === 'warning') return 0;
            return 8000;
        },
        showToast() {
            var d = this.getDuration();
            clearTimeout(this.timeout);
            this.show = true;
            if (d > 0) { this.timeout = setTimeout(() => { this.show = false }, d); }
        }
     }"
     @if($message)
         x-init="() => { showToast(); }"
     @endif
     @new-toast.window="showToast()"
     @click="if(@this.hideOnClick) { show = false; }"
     x-show="show"

     @if($transition)
         x-transition:enter="transition ease-in-out duration-300"
     x-transition:enter-start="opacity-0 transform {{$this->transitioClasses['enter_start_class']}}"
     x-transition:enter-end="opacity-100 transform {{$this->transitioClasses['enter_end_class']}}"
     x-transition:leave="transition ease-in-out duration-500"
     x-transition:leave-start="opacity-100 transform {{$this->transitioClasses['leave_start_class']}}"
     x-transition:leave-end="opacity-0 transform {{$this->transitioClasses['leave_end_class']}}"
    @endif
>
    @if($message)
        <div
            class="flex rounded-full bg-{{$bgColorCss}} border-l-4 border-{{$bgColorCss}}-700 py-2 px-3 shadow-md mb-2 ">
            <!-- icons -->
            @if($showIcon)
                <div class="text-{{$bgColorCss}} rounded-full bg-{{$textColorCss}} mr-3">
                    @include('livewire-toast::icons.' . $type)
                </div>
            @endif
            <!-- message -->
            <div class="text-{{$textColorCss}} max-w-xs ">
                {{$message}}
            </div>
            @if($type === 'error' || $type === 'warning')
                <div class="text-{{$textColorCss}} ml-2 font-bold cursor-pointer" @click="show = false" title="Cerrar">&times;</div>
            @endif
        </div>
    @endif
</div>
