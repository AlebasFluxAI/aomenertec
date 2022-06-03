<div class="col-2">
    <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#exampleModalLong">
        <i class="text-{{$icon_color}}  fas fa-map-location"></i>
    </button>
</div>


<div class="modal fade" id="exampleModalLong" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">{{$modal_title}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                @include($view_name,$view_data)
            </div>

        </div>
    </div>
</div>
