<img src='{{$image_url}}' data-toggle="modal" data-target="#exampleModal.{{$image_url}}"
     class="rounded img-fluid" alt="Imagen adjunta"
     width="150px"
     height="150px">
<div style="z-index: 10000; position: fixed" class="modal fade" id="exampleModal.{{$image_url}}" tabindex="2"
     role="dialog"
     aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="click-zoom">
                    <label>
                        <input type="checkbox"/>
                        <img src="{{$image_url}}"/>
                    </label>
                </div>
            </div>

        </div>
    </div>
</div>
