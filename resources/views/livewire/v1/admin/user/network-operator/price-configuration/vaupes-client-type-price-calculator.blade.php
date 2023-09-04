<div>
    @if($this->getVaupesStratification())
        @include("partials.v1.divider_title",[
            "title"=>"Tarifas vaupes"
        ])

        <div class="col-md-6" style="margin: 0 auto">
            <table class="table table-bordered">
                <thead style="position: sticky;top: 0;z-index: 2">
                <tr>

                    <th style="font-size: 10px">
                        <b>Tipo de cliente</b>
                    </th>
                    <th style="font-size: 10px">
                        <b>Tarifa</b>
                    </th>
                </tr>
                </thead>
                <tbody>
                @foreach(\App\Models\V1\Client::vaupesClientStratification() as $key=>$type)
                    <tr>
                        <td>
                            <div class="row">
                                <div class="col-12">
                                    <b>{{$key}}</b>
                                </div>
                            </div>
                        </td>

                        <td style="font-size: 10px">
                            <div class="row">

                                <div>
                                    <input
                                        wire:change="changeVaupesFeeType($event.target.value,'{{$type}}','{{$month}}','{{$year}}','{{$client_type}}')"
                                        class="form-control text-right"
                                        style="font-size: 12px"
                                        type="number"
                                        pattern="^\$\d{1,3}(,\d{3})*(\.\d+)?$"
                                        min="0"
                                        value="{{$this->getVaupesFee($type, $month, $year,$client_type)}}"
                                    >
                                </div>
                            </div>
                        </td>

                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
