<div class="form-group mb-2 col-md-{{$col_with}} col-sm-{{$col_with}}">

    <div class="input-group">
        <div class="file-drop-area"><span class="choose-file-button">{{$placeholder}}</span><br>
            <input wire:model="{{$input_model}}" type="file"
                   accept={{$file_accepted??".png,.jpg,.gif,.webp,.bmp"}} class="file-input"
                   @if($multiple??false)multiple @endif>
        </div>
        <div id="divImageMediaPreview"></div>
    </div>
    @error($input_model)
    <div class="error-container">
        <small class="form-text text-danger">{{$message}}</small>
    </div>
    @enderror
</div>
