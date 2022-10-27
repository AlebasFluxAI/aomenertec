<div class="col-2">
    <a class="btn btn-redirect btn-sm"
       data-toggle="tooltip" data-placement="{{$tooltip_position??"top"}}" title="{{$tooltip_title??""}}"
       href="{{route($button_route,array_merge([
    "subdomain"=>$button_subdomain??"",
    ($button_binding=="")?$model_id:$button_binding=>$model_id],
    $redirect_values??[]
    ))}}">
        <i class="text-{{$icon_color}} {{$icon}}"></i></a>

</div>
