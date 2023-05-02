<div class="primary-content table-responsive">
    <table class="table table-bordered">
        <thead style="position: sticky;top: 0;z-index: 2">
        <tr>

            <th style="font-size: 10px">
                <b>Tension</b>
            </th>
            <th style="font-size: 10px">
                <b>Generacion($) {{__("coin.".$model->admin->configAdmin->coin)}}</b>
            </th>
            <th style="font-size: 10px">
                <b>Transmision($) {{__("coin.".$model->admin->configAdmin->coin)}}</b>
            </th>
            <th style="font-size: 10px">
                <b>Distribucion($) {{__("coin.".$model->admin->configAdmin->coin)}}</b>
            </th>
            <th style="font-size: 10px">
                <b>Comercializacion($) {{__("coin.".$model->admin->configAdmin->coin)}}</b>
            </th>
            <th style="font-size: 10px">
                <b>Perdidas($) {{__("coin.".$model->admin->configAdmin->coin)}}</b>
            </th>
            <th style="font-size: 10px">
                <b>Restricciones($) {{__("coin.".$model->admin->configAdmin->coin)}}</b>
            </th>
            <th style="font-size: 10px">
                <b>Costo unitario($) {{__("coin.".$model->admin->configAdmin->coin)}}</b>
            </th>
            <th style="font-size: 10px">
                <b>Tarifa ($/kWh) {{__("coin.".$model->admin->configAdmin->coin)}}</b>
            </th>
        </tr>
        </thead>
        <tbody>
        @foreach(\App\Models\V1\VoltageLevel::get() as $level)
            <tr>
                <td>
                    <div class="row">
                        <div class="col-12">
                            <b>{{{$level->level}}}</b>
                        </div>
                    </div>
                </td>
                @foreach(\App\Models\V1\NetworkOperator::priceType() as $type)

                    <td style="font-size: 10px">
                        <div class="row">
                            <div class="col-12">
                                <input
                                    wire:change="changeFee($event.target.value,'{{$level->id}}','{{$type}}','{{$client_type}}')"
                                    class="form-control text-right"
                                    style="font-size: 12px"
                                    type="currency"
                                    pattern="^\$\d{1,3}(,\d{3})*(\.\d+)?$"
                                    min="0"
                                    placeholder="$"
                                    {{($type==\App\Models\V1\NetworkOperator::TOTAL_FEE)?"disabled":""}}
                                    value={{$this->getFee($level->id,$type,$client_type)}}>

                            </div>

                        </div>
                    </td>

                @endforeach


            </tr>
        @endforeach
        </tbody>
    </table>
    @include("partials.v1.divider_title",[
        "title"=>"Tarifas opcionales"
    ])
    <div class="col-6 text-center offset-3">
        <table class="table table-bordered">
            <thead style="position: sticky;top: 0;z-index: 2">
            <tr>
                <th style="font-size: 10px">
                    <b>Tension</b>
                </th>
                <th style="font-size: 10px">
                    <b>Tarifa ($/kWh) {{__("coin.".$model->admin->configAdmin->coin)}}</b>
                </th>
            </tr>
            </thead>
            <tbody>
            @foreach(\App\Models\V1\VoltageLevel::get() as $level)
                <tr>
                    <td>
                        <div class="row">
                            <div class="col-12">
                                <b>{{$level->level}}</b>
                            </div>
                        </div>
                    </td>
                    <td style="font-size: 10px">
                        @foreach(\App\Models\V1\NetworkOperator::priceOtionalType() as $type)
                            <div class="row">
                                <div class="col-12">
                                    <input
                                        wire:change="changeOptionalFee($event.target.value,'{{$level->id}}','{{$type}}','{{$client_type}}')"
                                        class="form-control text-right"
                                        style="font-size: 12px"
                                        type="currency"
                                        pattern="^\$\d{1,3}(,\d{3})*(\.\d+)?$"
                                        min="0"
                                        placeholder="$"
                                        value={{$this->getOptionalFee($level->id,$type,$client_type)}}>
                                </div>

                            </div>
                        @endforeach
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    @include("partials.v1.divider_title",[
        "title"=>"Otros conceptos"
    ])

    <table class="table table-bordered">
        <thead style="position: sticky;top: 0;z-index: 2">
        <tr>

            <th style="font-size: 10px">
                <b>Estrato</b>
            </th>
            <th style="font-size: 10px">
                <b>Descuento %</b>
            </th>
            <th style="font-size: 10px">
                <b>Contribucion %</b>
            </th>
            <th style="font-size: 10px">
                <b>Impuestos AP ($ - %)</b>
            </th>

        </tr>
        </thead>
        <tbody>
        @foreach(\App\Models\V1\Stratum::get() as $strata)
            <tr>
                <td>
                    <div class="row">
                        <div class="col-12">
                            <b>{{$strata->acronym}}</b>
                        </div>
                    </div>
                </td>
                @foreach(\App\Models\V1\NetworkOperator::getOtherConcepts() as $type)

                    <td style="font-size: 10px">
                        <div class="row">

                            <div class="{{$type==\App\Models\V1\NetworkOperator::TAX_CONCEPT?"col-9":"col-12"}}">
                                <input
                                    wire:change="changeOtherFee($event.target.value,'{{$type}}','{{$strata->id}}','{{$client_type}}')"
                                    class="form-control text-right"
                                    style="font-size: 12px"
                                    placeholder="%"
                                    type="currency"
                                    pattern="^\$\d{1,3}(,\d{3})*(\.\d+)?$"
                                    min="0"
                                    {{($type==\App\Models\V1\NetworkOperator::TOTAL_FEE)?"disabled":""}}
                                    value={{$this->getOtherFee($type,$strata->id,$client_type)}}>
                            </div>
                            <div style="font-size: 15px;
                            font-weight: bold"
                                 class="{{$type==\App\Models\V1\NetworkOperator::TAX_CONCEPT?"col-2":"col-0"}}">
                                @if($type==\App\Models\V1\NetworkOperator::TAX_CONCEPT)
                                    <select
                                        wire:change="changeTaxTypeStrata($event.target.value,'{{$strata->id}}','{{$client_type}}')"

                                        style="background-color: #f2f2f2;
                                               border-radius: 5px;
                                               padding: 5px;"
                                        name="languages" id="lang">
                                        <option
                                            value="{{ $this->getPercentageOption($strata->id,$client_type)}}">{{ $this->getPercentageOption($strata->id,$client_type)==\App\Models\V1\ZniLevelFee::MONEY_FEE?"$":"%"}}
                                        <option value="{{ \App\Models\V1\ZniLevelFee::MONEY_FEE}}">$</option>
                                        <option value="{{ \App\Models\V1\ZniLevelFee::PERCENTAGE_FEE}}">%</option>
                                    </select>
                                @endif
                            </div>
                        </div>
                    </td>

                @endforeach


            </tr>
        @endforeach
        </tbody>
    </table>
</div>
