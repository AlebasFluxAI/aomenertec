<ul class="ml-3">
    <div>
        @foreach($wikies as $key=>$wiki)
            <li class="mt-3">
                <span class="fas fa-square-caret-right"></span>

                <a href="#" wire:click="showInput({{$wiki->id}})">
                    {{$wiki->title}}
                    <span class=" badge badge-secondary badge-pill">{{$wiki->children->count()}}</span>
                </a>
                @if($wiki->children->count())
                    @include('livewire.v1.admin.user.super.wiki-input-tree', ['wikies' => $wiki->children,"root_key"=>$key])
                @endif
            </li>
        @endforeach

    </div>
</ul>
