<?php

namespace App\Http\Livewire\V1\Admin\Client\Monitoring\Charts;

use App\Models\V1\Client;
use Livewire\Component;

class CardsData extends Component
{
    public $variables;
    public $variables_selected;
    public $data_frame;
    public $last_data;
    public $cards;
    public $client;

    protected $rules = [

        'cards.*.list_model_variable' => 'required',
    ];

    public function mount(Client $client, $variables, $data_frame)
    {
        $this->variables = $variables;
        $this->data_frame = $data_frame;
        $this->client = $client;
        $last_data = $this->client->microcontrollerData()->latest()->first();
        // if ($last_data == null){
        //   $last_data = $this->client->hourlyMicrocontrollerData()->latest()->first();
        //}
        if ($last_data) {
            $this->last_data = collect(json_decode($last_data->raw_json, true));
            $this->cards = [];
            $this->variables_selected = [];
            $initial_variables = $variables->take(3);
            foreach ($initial_variables as $variable) {
                $aux = [];
                $var_data_frame = $this->data_frame->where('variable_id', $variable['id'])->all();
                foreach ($var_data_frame as $item) {
                    $item['value'] = round($this->last_data[$item['variable_name']], 2);
                    array_push($aux, $item);
                }
                array_push($this->cards, [
                    "id" => $variable['id'],
                    "color" => $variable['style'],
                    "icon" => $variable['icon'],
                    "list_model_variable" => $variable['id'],
                    "variables_selected" => $aux,
                ]);
            }
        }
    }

    public function updatedCards($value, $key)
    {
        $variable_select = $this->variables->where('id', $value)->first();
        $id = filter_var($key, FILTER_SANITIZE_NUMBER_INT);
        $aux = [];
        $var_data_frame = $this->data_frame->where('variable_id', $value)->all();
        if ($this->last_data) {
            foreach ($var_data_frame as $item) {
                $item['value'] = round($this->last_data[$item['variable_name']], 2);
                array_push($aux, $item);
            }
            $this->cards[$id]['id'] = $variable_select['id'];
            $this->cards[$id]['color'] = $variable_select['style'];
            $this->cards[$id]['icon'] = $variable_select['icon'];
            $this->cards[$id]['variables_selected'] = $aux;
        }
    }


    public function render()
    {
        return view('livewire.v1.admin.client.monitoring.charts.cards-data');
    }
}
