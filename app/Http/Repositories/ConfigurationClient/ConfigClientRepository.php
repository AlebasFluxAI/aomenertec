<?php

namespace App\Http\Repositories\ConfigurationClient;
interface ConfigClientRepository
{
    public function setStatusCoilForSerial();

    public function getStatusCoilForSerial();

    public function setDateForSerial();

    public function getDateForSerial();

    public function getTypeSensorForSerial();

    public function setTypeSensorForSerial();

    public function getStatusSensorForSerial();


}
