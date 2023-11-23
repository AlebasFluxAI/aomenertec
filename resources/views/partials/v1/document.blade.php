<div class="row m-2">
    <div class="col-md-6 p-2" style="background-color: #c3c3c3">
        <embed src="{{$document_url}}" width="500" height="375">

    </div>
    @if(isset($description))

        <div class="col-md-5 text-left   m-1 p-2" style="background-color: #c3c3c3">

            <p><b>Descripción:</b> {{$description}}</p>
        </div>
    @endif
</div>
