<?php

namespace App\Models\Traits;

use App\Models\V1\User;

trait ClientFormTrait
{
    public $aux_network_operator_id;
    public $aux_network_operator;
    public $message_aux_network_operator;
    public $picked_aux_network_operator;
    public $aux_network_operators;
    public $client;
    public $active_client;
    public $message;
    public $file;
    public $identification;
    public $name;
    public $phone;
    public $location_type_id;
    public $location_types;
    public $location_id;
    public $locations;
    public $direction;
    public $email;
    public $latitude;
    public $longitude;
    public $stratum_id;
    public $strata;
    public $decodedAddress;
    public $client_type;
    public $client_type_id;
    public $client_types;
    public $voltage_level_id;
    public $voltage_levels;
    public $subsistence_consumption_id;
    public $subsistence_consumptions;
    public $contribution;
    public $public_lighting_tax;
    public $active;
    public $network_topology;
    public $network_operator_id;
    public $network_operator;
    public $picked_network_operator;
    public $network_operators;
    public $message_network_operator;
    public $equipment;
    public $serials;
    public $serials_array;
    public $pickeds;
    public $posts;
    public $equipment_id;
    public $equipment_types;
    public $person_types;
    public $person_type;
    public $identification_type;
    public $identification_types;
    public $technician;
    public $picked_technician;
    public $message_technician;
    public $technicians;
    public $create_supervisor;
    public $network_topologies;
    public $last_name;
    public $has_telemetry;
    public $billing_name;
    public $billing_address;
    public $technician_id;
    public $technician_select_disabled;
    public $addressDetails;
    public $inputs;
    public $kwh_month;
    public $kvarlh_month;
    public $kvarch_month;
    public $kwh_hour;
    public $kvarlh_hour;
    public $kvarch_hour;
    public $kvarlh_penalizable;
    public $alerts;
    public $billing_types;
    public $billing_type;
    public $client_person_type;
    public $client_identification;
    public $client_identification_type;
    public $alias;
    public $indicatives;
    public $indicative;
    public $time_zones;
    public $time_zone;
    public $address_details;
    public $stratification;
    public $stratification_name;

    public function getVaupesStratification()
    {
        $admin = User::getUserModel()->user->getAdmin();
        return $admin->identification == "901651742-8";
    }
}

