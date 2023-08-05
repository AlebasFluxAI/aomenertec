<div class="primary-content table-responsive">
    <br>
    <p> Puedes configurar el precio de kWh para los diferentes estratos
        <br>
        <b> Cada vez que se realice un cambio se
            actualizara el valor de dato cambiado.</b>
    </p>
    <br>
    <div class="primary-content table-responsive">
        <table class="table table-bordered">
            <thead style="position: sticky;top: 0;z-index: 2">
            <tr>

                <th>
                    <b>Estrato</b>
                </th>
                <th>
                    Subsidio (kWh)
                </th>
                <th>
                    Credito (kWh)
                </th>
                <th>
                    Valor ($/kWh)
                </th>
            </tr>
            </thead>
            <tbody>
            @foreach($data as $data_body)
                <tr>
                    <td>
                        <b>{{$data_body->acronym}}</b>
                    </td>
                    <td>
                        <div class="row">
                            <div class="col-5 offset-2">
                                <input wire:change="changeSubsidy($event.target.value,{{$data_body->id}})"
                                       class="form-control text-right"
                                       type="number"
                                       value="{{$this->getSubsidy($data_body->id)}}">
                            </div>
                            <div class="col-4">
                                <b>COP</b>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="row">
                            <div class="col-5 offset-2">
                                <input wire:change="changeCredit($event.target.value,{{$data_body->id}})"
                                       class="form-control text-right"
                                       type="number"
                                       value="{{$this->getCredit($data_body->id)}}">
                            </div>
                            <div class="col-4">
                                <b>COP</b>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="row">
                            <div class="col-5 offset-2">
                                <input wire:change="changeValue($event.target.value,{{$data_body->id}})"
                                       class="form-control text-right"
                                       type="number"
                                       value="{{$this->getValue($data_body->id)}}">
                            </div>
                            <div class="col-4">
                                <b>COP</b>
                            </div>
                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>

