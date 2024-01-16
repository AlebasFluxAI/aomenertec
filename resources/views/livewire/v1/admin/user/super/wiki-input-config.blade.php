<div class="login">
    @section("header")
        {{--extended app.blade--}}
    @endsection

    @include("partials.v1.title",[
            "first_title"=>"Entradas",
            "second_title"=>"Wiki"
        ])

    <div class="row">

        <div class="col-md-4 bg-gray-200">
            @if(!$wikies->count())
                <div class="text-center justify-center mt-10">
                    <span class="fas fa-folder-open" style="font-size: 100px"></span>
                    <p style="font-size: 25px">Sin entradas</p>
                </div>
            @else
                <div class="text-center ml-2">
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
        <div class="col-md-8 contenedor-grande">
            @include("partials.v1.table_nav",
                 ["mt"=>2,"nav_options"=>[
                   ["button_align"=>"right",
                   "click_action"=>"",
                   "button_icon"=>"fas fa-plus",
                   "button_content"=>"Crear nueva entrada",
                   "target_route"=>"configuracion.v1.wiki.entradas",
                   ],

               ]
       ])
            <!-- Place the first <script> tag in your HTML's <head> -->
            <script
                src="https://cdn.tiny.cloud/1/9e3csoqunsfug0z2fipvmy64vmqzeosxndvu55fgb22jbjlx/tinymce/6/tinymce.min.js"
                referrerpolicy="origin"></script>

            <!-- Place the following <script> and <textarea> tags your HTML's <body> -->
            <script>
                tinymce.init({
                    selector: 'textarea',
                    plugins: 'ai tinycomments mentions anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount checklist mediaembed casechange export formatpainter pageembed permanentpen footnotes advtemplate advtable advcode editimage tableofcontents mergetags powerpaste tinymcespellchecker autocorrect a11ychecker typography inlinecss',
                    toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table mergetags | align lineheight | tinycomments | checklist numlist bullist indent outdent | emoticons charmap | removeformat',
                    tinycomments_mode: 'embedded',
                    tinycomments_author: 'Author name',
                    height: "600",
                    forced_root_block: false,
                    setup: function (editor) {
                        editor.on('init change', function () {
                            editor.save();
                        });
                        editor.on('change', function (e) {
                        @this.set('content', editor.getContent())
                            ;
                        });
                    },
                    mergetags_list: [
                        {value: 'First.Name', title: 'First Name'},
                        {value: 'Email', title: 'Email'},
                    ],
                    ai_request: (request, respondWith) => respondWith.string(() => Promise.reject("See docs to implement AI Assistant")),
                });
                window.addEventListener('contentChanged', (e) => {
                    tinymce.activeEditor.setContent(e.detail.content);
                });
            </script>
            @include("partials.v1.divider_title",["title"=>"Crear nueva entrada"])
            <form wire:submit.prevent="submitForm" id="formulario" class="needs-validation" role="form">
                <div wire:ignore>

                    @include("partials.v1.form.form_input_icon",[
                                   "input_label"=>"Titulo",
                                   "updated_input"=>"defer",
                                   "input_model"=>"title",
                                   "input_type"=>"text",
                                   "placeholder"=>"Titulo de la entrada",
                                   "col_with"=>10,
                                   "required"=>true,
                              ])
                    <br>

                    @include("partials.v1.form.form_list",[
                                               "col_with"=>10,
                                               "input_label"=>"Padre de la entrada",
                                               "list_model" => "parent",
                                               "list_default" => false,
                                               "not_selection"=>" ",
                                               "list_options" => $wikies->toArray(),
                                               "list_option_title"=>"title",
                                               "list_option_value"=>"id",
                                               "list_option_view"=>"title",



                          ])
                    <br>
                    <label>Contenido de la entrada</label>
                    <textarea wire:model="content" name="content" id="content">
                     </textarea>
                    <br>
                    <div class="text-right">
                        <button id="add" type="submit" class="mb-2 py-2 px-4">
                            <b>
                                Guardar entrada
                            </b>
                        </button>
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>

