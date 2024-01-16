<div class="h-screen">
    <div class="section-title text-center">
    </div>
    <div class="row h-full text-center">
        <div class="col-md-3 bg-gray-200" style="border-color: darkgray;border-width: 0 1px 0 0">
            @if(!$wikies->count())
                <div class="text-center justify-center mt-10">
                    <span class="fas fa-folder-open" style="font-size: 100px"></span>
                    <p style="font-size: 25px">Sin entradas</p>
                </div>
            @else
                <div class="text-center pl-4">
                    <ul>
                        <div class="mt-3">
                            <h2><strong>Arbol de entradas</strong></h2>
                        </div>
                        <div class="text-center">
                            <hr class="mr-10 my-2" style="width: 50%">
                        </div>
                        @foreach(\App\Models\V1\WikiTree::whereNull("parent_id")->get() as $key=>$wiki)
                            <li class="mt-6 ">
                                <span class="fas fa-square-caret-right"></span>
                                <a href="#" wire:click="showInput({{$wiki->id}})">
                                    {{$wiki->title}}
                                    <span class=" badge badge-secondary badge-pill">{{$wiki->children->count()}}</span>
                                </a>

                                @if($wiki->children->count())
                                    @include('livewire.v1.admin.user.super.wiki-input-tree', ['wikies' => $wiki->children,"root_key"=>$key])
                                @endif
                                <div class="text-center">
                                    <hr class="mr-10 my-2" style="width: 50%">
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
        <div class="col-md-7" style="background-color: #e8e8e8">

            <div class="mt-6" id="scrollableContent">
                <div class="section-title text-center">
                    <h2>{{ $title }}</h2>
                </div>
                <div class="mt-6">
                    {!! $content !!}
                </div>
            </div>

        </div>
    </div>
</div>
