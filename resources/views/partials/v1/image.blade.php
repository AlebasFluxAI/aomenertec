<img src='{{$image_url}}' data-toggle="modal" data-target="#exampleModal.{{$image_url}}"
     class="rounded img-fluid" alt="Imagen adjunta"
     width="150px"
     height="150px">
<div class="modal fade" id="exampleModal.{{$image_url}}" tabindex="-1"
     role="dialog"
     aria-labelledby="exampleModalLabel"
     aria-hidden="false">
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
