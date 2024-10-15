<?php

return [
    'variables' => [
        ['id' => 1,  'display_name' =>  'Energia activa(kWh)',         'icon'=>'fas fa-tachometer-alt',                  'variable_name'=> 'import_wh',          'chart_type' => 'line',   'real_time' => true, 'style' => 'active_energy'],
        ['id' => 2,  'display_name' =>  'Horario(kWh)',                'icon'=>'fas fa-tachometer-alt',                  'variable_name'=> 'import_wh',          'chart_type' => 'column', 'real_time' => false, 'style' => 'active_energy'],
        ['id' => 3,  'display_name' =>  'Acumulado fases(kWh)',        'icon'=>'fas fa-tachometer-alt',                  'variable_name'=> 'phase_import_kwh',   'chart_type' => 'line',   'real_time' => true, 'style' => 'active_energy'],
        ['id' => 4,  'display_name' =>  'Horario fases(kWh)',          'icon'=>'fas fa-tachometer-alt',                  'variable_name'=> 'phase_import_kwh',   'chart_type' => 'column', 'real_time' => false, 'style' => 'active_energy'],

        ['id' => 5,  'display_name' =>  'Energia reactiva(kVARh)',     'icon'=>'fas fa-atom',                            'variable_name'=> 'import_VArh',        'chart_type' => 'line',   'real_time' => true, 'style' => 'reactive_energy'],
        ['id' => 6,  'display_name' =>  'Horario(kVARh)',              'icon'=>'fas fa-atom',                            'variable_name'=> 'import_wh',          'chart_type' => 'column', 'real_time' => false, 'style' => 'reactive_energy'],
        ['id' => 7,  'display_name' =>  'Acumulado fases (kVARh)',     'icon'=>'fas fa-atom',                            'variable_name'=> 'phase_import_kvarh', 'chart_type' => 'line',   'real_time' => true, 'style' => 'reactive_energy'],
        ['id' => 8,  'display_name' =>  'Horario fases (kVARh)',       'icon'=>'fas fa-atom',                            'variable_name'=> 'phase_import_kvarh', 'chart_type' => 'column', 'real_time' => false, 'style' => 'reactive_energy'],

        ['id' => 9,  'display_name' =>  'Reactiva Capacitiva(kVARCh)', 'icon'=>"fa-brands fa-creative-commons-nd",       'variable_name'=> 'phase_import_kvarh', 'chart_type' => 'line',   'real_time' => false, 'style' => 'reactive_capacitive'],
        ['id' => 10, 'display_name' =>  'Horario (kVARCh)',            'icon'=>'fa-brands fa-creative-commons-nd',       'variable_name'=> 'import_VArh',        'chart_type' => 'column', 'real_time' => false, 'style' => 'reactive_capacitive'],
        ['id' => 11, 'display_name' =>  'Acumulado fases (kVARCh)',    'icon'=>'fa-brands fa-creative-commons-nd',       'variable_name'=> 'import_VArh',        'chart_type' => 'line',   'real_time' => false, 'style' => 'reactive_capacitive'],
        ['id' => 12, 'display_name' =>  'Horario fases (kVARCh)',      'icon'=>'fa-brands fa-creative-commons-nd',       'variable_name'=> 'import_VArh',        'chart_type' => 'column', 'real_time' => false, 'style' => 'reactive_capacitive'],

        ['id' => 13, 'display_name' =>  'Reactiva Inductiva(kVARLh)',  'icon'=>"fa-brands fa-creative-commons-sampling", 'variable_name'=> 'phase_import_kvarh', 'chart_type' => 'line',   'real_time' => false, 'style' => 'reactive_inductive'],
        ['id' => 14, 'display_name' => 'Horario (kVARLh)',             'icon'=>'fa-brands fa-creative-commons-sampling', 'variable_name'=> 'import_VArh',        'chart_type' => 'column', 'real_time' => false, 'style' => 'reactive_inductive'],
        ['id' => 15, 'display_name' => 'Acumulado fases (kVARLh)',     'icon'=>'fa-brands fa-creative-commons-sampling', 'variable_name'=> 'import_VArh',        'chart_type' => 'line',   'real_time' => false, 'style' => 'reactive_inductive'],
        ['id' => 16, 'display_name' => 'Horario fases (kVARLh)',       'icon'=>'fa-brands fa-creative-commons-sampling', 'variable_name'=> 'import_VArh',        'chart_type' => 'column', 'real_time' => false, 'style' => 'reactive_inductive'],


        ['id' => 17, 'display_name' =>  'Voltaje(V)',                  'icon'=>'fas fa-lightbulb',                       'variable_name'=> 'volt',               'chart_type' => 'line',   'real_time' => true, 'style' => 'voltage'],
        ['id' => 18, 'display_name' =>  'Corriente(A)',                'icon'=>'fas fa-bolt',                            'variable_name'=> 'current',            'chart_type' => 'line',   'real_time' => true, 'style' => 'current'],
        ['id' => 19, 'display_name' =>  'Potencia Fases(W)',                 'icon'=>'fas fa-long-arrow-alt-right',            'variable_name'=> 'power',              'chart_type' => 'line',   'real_time' => true, 'style' => 'power'],
        ['id' => 20, 'display_name' =>  'Potencia(W)',                 'icon'=>'fas fa-long-arrow-alt-right',            'variable_name'=> 'power',              'chart_type' => 'line',   'real_time' => true, 'style' => 'power'],
        ['id' => 21, 'display_name' =>  'P. Aparente(VA)',              'icon'=>'fas fa-expand-alt',                      'variable_name'=> 'VA',                 'chart_type' => 'line',   'real_time' => true, 'style' => 'power'],
        ['id' => 22, 'display_name' =>  'P. Reactiva Fases(VAR)',            'icon'=>'fas fa-long-arrow-alt-up',               'variable_name'=> 'VAr',                'chart_type' => 'line',   'real_time' => true, 'style' => 'power'],
        ['id' => 32, 'display_name' =>  'P. Reactiva(VAR)',            'icon'=>'fas fa-long-arrow-alt-up',               'variable_name'=> 'VAr',                'chart_type' => 'line',   'real_time' => true, 'style' => 'power'],
        ['id' => 23, 'display_name' =>  'FP Fases',                          'icon'=>'fas fa-exchange-alt',                    'variable_name'=> 'power_factor',       'chart_type' => 'line',   'real_time' => true, 'style' => 'power_factor'],
        ['id' => 24, 'display_name' =>  'FP',                          'icon'=>'fas fa-exchange-alt',                    'variable_name'=> 'power_factor',       'chart_type' => 'line',   'real_time' => true, 'style' => 'power_factor'],
        ['id' => 25, 'display_name' =>  'Angulo fase',                 'icon'=>'fas fa-greater-than-equal',              'variable_name'=> 'phase_angle',        'chart_type' => 'line',   'real_time' => true, 'style' => 'phase_angle'],
        ['id' => 26, 'display_name' =>  'Angulo fase total',                 'icon'=>'fas fa-greater-than-equal',              'variable_name'=> 'phase_angle',        'chart_type' => 'line',   'real_time' => true, 'style' => 'phase_angle'],
        ['id' => 27, 'display_name' =>  'Voltaje fases(V)',                 'icon'=>'fas fa-hubspot',                         'variable_name'=> 'current_thd',        'chart_type' => 'line',   'real_time' => true, 'style' => 'thd'],
        ['id' => 28, 'display_name' =>  'THDI(%)',                     'icon'=>'fas fa-hubspot',                         'variable_name'=> 'current_thd',        'chart_type' => 'line',   'real_time' => true, 'style' => 'thd'],
        ['id' => 29, 'display_name' =>  'THDV(%)',                     'icon'=>'fas fa-percentage',                      'variable_name'=> 'volt_thd',           'chart_type' => 'line',   'real_time' => true, 'style' => 'thd'],
        ['id' => 30, 'display_name' =>  'THDV fases(%)',               'icon'=>'fas fa-percent',                         'variable_name'=> 'volts_thd',          'chart_type' => 'line',   'real_time' => true, 'style' => 'thd'],
        ['id' => 31, 'display_name' =>  'Frecuencia(Hz)',              'icon'=>'fas fa-chart-line',                      'variable_name'=> 'frecuency' ,         'chart_type' => 'line',   'real_time' => true, 'style' => 'frecuency'],
        ['id' => 33, 'display_name' =>  'VoltajeDC(VDC)',              'icon'=>'fas fa-lightbulbs',                      'variable_name'=> 'volt_dc' ,         'chart_type' => 'line',   'real_time' => true, 'style' => 'voltage'],
    ],


    'data_frame' => [
        ['id' => 1 , 'variable_name'=> 'network_operator_id', 'description' => '', 'display_name' => '',    'variable_id'=> '', 'key' => '',          'start' => 0,   'lenght' => 16, 'type' => 'P', 'factor' => 1, 'bolean_accum' => false],
        ['id' => 2 , 'variable_name'=> 'equipment_id', 'description' => '',        'display_name' => '',    'variable_id'=> '', 'key' => '',          'start' => 16,  'lenght' => 16, 'type' => 'P', 'factor' => 1, 'bolean_accum' => false],
        ['id' => 3 , 'variable_name'=> 'latitude', 'description' => '',            'display_name' => '',    'variable_id'=> '', 'key' => '',          'start' => 32,  'lenght' => 8,  'type' => 'f', 'factor' => 1, 'bolean_accum' => false],
        ['id' => 4 , 'variable_name'=> 'longitude', 'description' => '',           'display_name' => '',    'variable_id'=> '', 'key' => '',          'start' => 40,  'lenght' => 8,  'type' => 'f', 'factor' => 1, 'bolean_accum' => false],
        ['id' => 5 , 'variable_name'=> 'flags', 'description' => '',               'display_name' => '',    'variable_id'=> '', 'key' => '',          'start' => 48,  'lenght' => 16, 'type' => 'P', 'factor' => 1, 'bolean_accum' => false],
        ['id' => 6 , 'variable_name'=> 'timestamp', 'description' => '',           'display_name' => '',    'variable_id'=> '', 'key' => '',          'start' => 64,  'lenght' => 8,  'type' => 'V', 'factor' => 1, 'bolean_accum' => false],
        ['id' => 7 , 'variable_name'=> 'ph1_volt',           'description' => '',            'display_name' => 'L1 V',    'variable_id'=> 17, 'key' => 'L1',        'start' => 72,  'lenght' => 8,  'type' => 'f', 'factor' => 1, 'min' => 0, 'max' => 14000, 'default' => 120, 'bolean_accum' => false],
        ['id' => 8 , 'variable_name'=> 'ph2_volt',           'description' => '',            'display_name' => 'L2 V',    'variable_id'=> 17, 'key' => 'L2',        'start' => 80,  'lenght' => 8,  'type' => 'f', 'factor' => 1, 'min' => 0, 'max' => 14000, 'default' => 120, 'bolean_accum' => false],
        ['id' => 9 , 'variable_name'=> 'ph3_volt',           'description' => '',            'display_name' => 'L3 V',    'variable_id'=> 17, 'key' => 'L3',        'start' => 88,  'lenght' => 8,  'type' => 'f', 'factor' => 1, 'min' => 0, 'max' => 14000, 'default' => 120, 'bolean_accum' => false],
        ['id' => 10, 'variable_name'=> 'ph1_current',        'description' => '',         'display_name' => 'L1 A',    'variable_id'=> 18, 'key' => 'L1',        'start' => 96,  'lenght' => 8,  'type' => 'f', 'factor' => 1, 'min' => 0, 'max' => 4000, 'default' => 0, 'bolean_accum' => false],
        ['id' => 11, 'variable_name'=> 'ph2_current',        'description' => '',         'display_name' => 'L2 A',    'variable_id'=> 18, 'key' => 'L2',        'start' => 104, 'lenght' => 8,  'type' => 'f', 'factor' => 1, 'min' => 0, 'max' => 4000, 'default' => 0, 'bolean_accum' => false],
        ['id' => 12, 'variable_name'=> 'ph3_current',        'description' => '',         'display_name' => 'L3 A',    'variable_id'=> 18, 'key' => 'L3',        'start' => 112, 'lenght' => 8,  'type' => 'f', 'factor' => 1, 'min' => 0, 'max' => 4000, 'default' => 0, 'bolean_accum' => false],
        ['id' => 13, 'variable_name'=> 'ph1_power',          'description' => '',           'display_name' => 'L1 W',    'variable_id'=> 19, 'key' => 'L1',        'start' => 120, 'lenght' => 8,  'type' => 'f', 'factor' => 1, 'min' => 0, 'max' => 1000000, 'default' => 0, 'bolean_accum' => false],
        ['id' => 14, 'variable_name'=> 'ph2_power',          'description' => '',           'display_name' => 'L2 W',    'variable_id'=> 19, 'key' => 'L2',        'start' => 128, 'lenght' => 8,  'type' => 'f', 'factor' => 1, 'min' => 0, 'max' => 1000000, 'default' => 0, 'bolean_accum' => false],
        ['id' => 15, 'variable_name'=> 'ph3_power',          'description' => '',           'display_name' => 'L3 W',    'variable_id'=> 19, 'key' => 'L3',        'start' => 136, 'lenght' => 8,  'type' => 'f', 'factor' => 1, 'min' => 0, 'max' => 1000000, 'default' => 0, 'bolean_accum' => false],
        ['id' => 16, 'variable_name'=> 'ph1_VA',             'description' => '',              'display_name' => 'L1 VA',    'variable_id'=> 21, 'key' => 'L1',        'start' => 144, 'lenght' => 8,  'type' => 'f', 'factor' => 1, 'min' => 0, 'max' => 1000000, 'default' => 0, 'bolean_accum' => false],
        ['id' => 17, 'variable_name'=> 'ph2_VA',             'description' => '',              'display_name' => 'L2 VA',    'variable_id'=> 21, 'key' => 'L2',        'start' => 152, 'lenght' => 8,  'type' => 'f', 'factor' => 1, 'min' => 0, 'max' => 1000000, 'default' => 0, 'bolean_accum' => false],
        ['id' => 18, 'variable_name'=> 'ph3_VA',             'description' => '',              'display_name' => 'L3 VA',    'variable_id'=> 21, 'key' => 'L3',        'start' => 160, 'lenght' => 8,  'type' => 'f', 'factor' => 1, 'min' => 0, 'max' => 1000000, 'default' => 0, 'bolean_accum' => false],
        ['id' => 19, 'variable_name'=> 'ph1_VAr',            'description' => '',             'display_name' => 'L1 VAR',    'variable_id'=> 22, 'key' => 'L1',        'start' => 168, 'lenght' => 8,  'type' => 'f', 'factor' => 1, 'min' => -1000000, 'max' => 1000000, 'default' => 0, 'bolean_accum' => false],
        ['id' => 20, 'variable_name'=> 'ph2_VAr',            'description' => '',             'display_name' => 'l2 VAR',    'variable_id'=> 22, 'key' => 'L2',        'start' => 176, 'lenght' => 8,  'type' => 'f', 'factor' => 1, 'min' => -1000000, 'max' => 1000000, 'default' => 0, 'bolean_accum' => false],
        ['id' => 21, 'variable_name'=> 'ph3_VAr',            'description' => '',             'display_name' => 'L3 VAR',    'variable_id'=> 22, 'key' => 'L3',        'start' => 184, 'lenght' => 8,  'type' => 'f', 'factor' => 1, 'min' => -1000000, 'max' => 1000000, 'default' => 0, 'bolean_accum' => false],
        ['id' => 22, 'variable_name'=> 'ph1_power_factor',   'description' => '',    'display_name' => 'L1 FP',    'variable_id'=> 23, 'key' => 'L1',        'start' => 192, 'lenght' => 8,  'type' => 'f', 'factor' => 1, 'min' => 0, 'max' => 2, 'default' => 0.9, 'bolean_accum' => false],
        ['id' => 23, 'variable_name'=> 'ph2_power_factor',   'description' => '',    'display_name' => 'L2 FP',    'variable_id'=> 23, 'key' => 'L2',        'start' => 200, 'lenght' => 8,  'type' => 'f', 'factor' => 1, 'min' => 0, 'max' => 2, 'default' => 0.9, 'bolean_accum' => false],
        ['id' => 24, 'variable_name'=> 'ph3_power_factor',   'description' => '',    'display_name' => 'L3 FP',    'variable_id'=> 23, 'key' => 'L3',        'start' => 208, 'lenght' => 8,  'type' => 'f', 'factor' => 1, 'min' => 0, 'max' => 2, 'default' => 0.9, 'bolean_accum' => false],
        ['id' => 25, 'variable_name'=> 'total_power_factor',   'description' => '',    'display_name' => 'FP',    'variable_id'=> 24, 'key' => 'L3',        'start' => 216, 'lenght' => 8,  'type' => 'f', 'factor' => 1, 'min' => 0, 'max' => 20, 'default' => 0.9, 'bolean_accum' => false],

        ['id' => 26, 'variable_name'=> 'ph1_phase_angle',    'description' => '',     'display_name' => 'L1 AF',    'variable_id'=> 25, 'key' => 'L1',        'start' => 224, 'lenght' => 8,  'type' => 'f', 'factor' => 1, 'min' => -90, 'max' => 90, 'default' => 30, 'bolean_accum' => false],
        ['id' => 27, 'variable_name'=> 'ph2_phase_angle',    'description' => '',     'display_name' => 'L2 AF',    'variable_id'=> 25, 'key' => 'L2',        'start' => 232, 'lenght' => 8,  'type' => 'f', 'factor' => 1, 'min' => -90, 'max' => 90, 'default' => 30, 'bolean_accum' => false],
        ['id' => 28, 'variable_name'=> 'ph3_phase_angle',    'description' => '',     'display_name' => 'L3 AF',    'variable_id'=> 25, 'key' => 'L3',        'start' => 240, 'lenght' => 8,  'type' => 'f', 'factor' => 1, 'min' => -90, 'max' => 90, 'default' => 30, 'bolean_accum' => false],
        ['id' => 29, 'variable_name'=> 'total_phase_angle',    'description' => '',     'display_name' => 'AF',    'variable_id'=> 26, 'key' => 'L3',        'start' => 248, 'lenght' => 8,  'type' => 'f', 'factor' => 1, 'min' => -90, 'max' => 90, 'default' => 30, 'bolean_accum' => false],

        ['id' => 30, 'variable_name'=> 'total_system_power', 'description' => '',  'display_name' => 'W',    'variable_id'=> 20, 'key' => 'W',          'start' => 256, 'lenght' => 8,  'type' => 'f', 'factor' => 1, 'min' => 0, 'max' => 1000000, 'default' => 0, 'bolean_accum' => false],
        ['id' => 31, 'variable_name'=> 'total_system_var',   'description' => '',    'display_name' => 'VAR',    'variable_id'=> 32, 'key' => 'VAR',          'start' => 264, 'lenght' => 8,  'type' => 'f', 'factor' => 1, 'min' => -1000000, 'max' => 1000000, 'default' => 0, 'bolean_accum' => false],
        ['id' => 32, 'variable_name'=> 'frequency',          'description' => '',           'display_name' => 'FRECUENCIA',    'variable_id'=> 31, 'key' => 'Hz',        'start' => 272, 'lenght' => 8,  'type' => 'f', 'factor' => 1, 'min' => 0, 'max' => 100, 'default' => 60, 'bolean_accum' => false],
        ['id' => 33, 'variable_name'=> 'import_wh',          'description' => '',           'display_name' => 'ENERGIA kWh TOTAL',    'variable_id'=> 1,  'key' => 'TOTAL',     'start' => 280, 'lenght' => 8,  'type' => 'f', 'factor' => 1, 'min' => 0, 'max' => 1000000, 'default' => true, 'bolean_accum' => false],
        ['id' => 34, 'variable_name'=> 'export_wh',          'description' => '',           'display_name' => '',    'variable_id'=> '', 'key' => '',          'start' => 288, 'lenght' => 8,  'type' => 'f', 'factor' => 1, 'min' => 1, 'max' => 1000000, 'default' => true, 'bolean_accum' => false],
        ['id' => 35, 'variable_name'=> 'import_VArh',        'description' => '',         'display_name' => 'ENERGIA kVARh TOTAL',    'variable_id'=> 5,  'key' => 'TOTAL',     'start' => 296, 'lenght' => 8,  'type' => 'f', 'factor' => 1, 'min' => 1, 'max' => 1000000, 'default' => true, 'bolean_accum' => false],
        ['id' => 36, 'variable_name'=> 'export_VArh',        'description' => '',         'display_name' => '',    'variable_id'=> '', 'key' => '',          'start' => 304, 'lenght' => 8,  'type' => 'f', 'factor' => 1, 'min' => 1, 'max' => 1000000, 'default' => true, 'bolean_accum' => false],
        ['id' => 37, 'variable_name'=> 'ph1_ph2_volt',       'description' => '',        'display_name' => 'L1-L2 V',    'variable_id'=> 27, 'key' => 'L1-L2',     'start' => 312, 'lenght' => 8,  'type' => 'f', 'factor' => 1, 'min' => 0, 'max' => 30000, 'default' => 220, 'bolean_accum' => false],
        ['id' => 38, 'variable_name'=> 'ph2_ph3_volt',       'description' => '',        'display_name' => 'L2-L3 V',    'variable_id'=> 27, 'key' => 'L2-L3',     'start' => 320, 'lenght' => 8,  'type' => 'f', 'factor' => 1, 'min' => 0, 'max' => 30000, 'default' => 220, 'bolean_accum' => false],
        ['id' => 39, 'variable_name'=> 'ph3_ph1_volt',       'description' => '',        'display_name' => 'L3-L4 V',    'variable_id'=> 27, 'key' => 'L3-L1',     'start' => 328, 'lenght' => 8,  'type' => 'f', 'factor' => 1, 'min' => 0, 'max' => 30000, 'default' => 220, 'bolean_accum' => false],
        ['id' => 40, 'variable_name'=> 'ph1_volt_thd',       'description' => '',        'display_name' => 'L1 VTHD',    'variable_id'=> 29, 'key' => 'L1',        'start' => 336, 'lenght' => 8,  'type' => 'f', 'factor' => 1, 'min' => 0, 'max' => 1000, 'default' => 0, 'bolean_accum' => false],
        ['id' => 41, 'variable_name'=> 'ph2_volt_thd',       'description' => '',        'display_name' => 'L2 VTHD',    'variable_id'=> 29, 'key' => 'L2',        'start' => 344, 'lenght' => 8,  'type' => 'f', 'factor' => 1, 'min' => 0, 'max' => 1000, 'default' => 0, 'bolean_accum' => false],
        ['id' => 42, 'variable_name'=> 'ph3_volt_thd',       'description' => '',        'display_name' => 'L3 VTHD',    'variable_id'=> 29, 'key' => 'L3',        'start' => 352, 'lenght' => 8,  'type' => 'f', 'factor' => 1, 'min' => 0, 'max' => 1000, 'default' => 0, 'bolean_accum' => false],
        ['id' => 43, 'variable_name'=> 'ph1_current_thd',    'description' => '',     'display_name' => 'L1 ATHD',    'variable_id'=> 28, 'key' => 'L1',        'start' => 360, 'lenght' => 8,  'type' => 'f', 'factor' => 1, 'min' => 0, 'max' => 1000, 'default' => 0, 'bolean_accum' => false],
        ['id' => 44, 'variable_name'=> 'ph2_current_thd',    'description' => '',     'display_name' => 'L2 ATHD',    'variable_id'=> 28, 'key' => 'L2',        'start' => 368, 'lenght' => 8,  'type' => 'f', 'factor' => 1, 'min' => 0, 'max' => 1000, 'default' => 0, 'bolean_accum' => false],
        ['id' => 45, 'variable_name'=> 'ph3_current_thd',    'description' => '',     'display_name' => 'L3 ATHD',    'variable_id'=> 28, 'key' => 'L3',        'start' => 376, 'lenght' => 8,  'type' => 'f', 'factor' => 1, 'min' => 0, 'max' => 1000, 'default' => 0, 'bolean_accum' => false],
        ['id' => 46, 'variable_name'=> 'ph1_ph2_volt_thd',   'description' => '',    'display_name' => 'L1-L2 VTHD',    'variable_id'=> 30, 'key' => 'L1-L2',     'start' => 384, 'lenght' => 8,  'type' => 'f', 'factor' => 1, 'min' => 0, 'max' => 1000, 'default' => 0, 'bolean_accum' => false],
        ['id' => 47, 'variable_name'=> 'ph2_ph3_volt_thd',   'description' => '',    'display_name' => 'L2-L3 VTHD',    'variable_id'=> 30, 'key' => 'L2-L3',     'start' => 392, 'lenght' => 8,  'type' => 'f', 'factor' => 1, 'min' => 0, 'max' => 1000, 'default' => 0, 'bolean_accum' => false],
        ['id' => 48, 'variable_name'=> 'ph3_ph1_volt_thd',   'description' => '',    'display_name' => 'L3-L1 VTHD',    'variable_id'=> 30, 'key' => 'L3-L1',     'start' => 400, 'lenght' => 8,  'type' => 'f', 'factor' => 1, 'min' => 0, 'max' => 1000, 'default' => 0, 'bolean_accum' => false],
        ['id' => 49, 'variable_name'=> 'ph1_import_kwh',     'description' => '',      'display_name' => 'L1 ENERGIA kWh TOTAL',    'variable_id'=> 3,  'key' => 'L1',        'start' => 408, 'lenght' => 8,  'type' => 'f', 'factor' => 1, 'min' => 1, 'max' => 1000000, 'default' => true, 'bolean_accum' => false],
        ['id' => 50, 'variable_name'=> 'ph2_import_kwh',     'description' => '',      'display_name' => 'L2 ENERGIA kWh TOTAL',    'variable_id'=> 3,  'key' => 'L2',        'start' => 416, 'lenght' => 8,  'type' => 'f', 'factor' => 1, 'min' => 1, 'max' => 1000000, 'default' => true, 'bolean_accum' => false],
        ['id' => 51, 'variable_name'=> 'ph3_import_kwh',     'description' => '',      'display_name' => 'L3 ENERGIA kWh TOTAL',    'variable_id'=> 3,  'key' => 'L3',        'start' => 424, 'lenght' => 8,  'type' => 'f', 'factor' => 1, 'min' => 1, 'max' => 1000000, 'default' => true, 'bolean_accum' => false],
        ['id' => 52, 'variable_name'=> 'ph1_import_kvarh',   'description' => '',    'display_name' => 'L1 ENERGIA kVARh TOTAL',    'variable_id'=> 7,  'key' => 'L1',        'start' => 432, 'lenght' => 8,  'type' => 'f', 'factor' => 1, 'min' => 1, 'max' => 1000000, 'default' => true, 'bolean_accum' => false],
        ['id' => 53, 'variable_name'=> 'ph2_import_kvarh',   'description' => '',    'display_name' => 'L2 ENERGIA kVARh TOTAL',    'variable_id'=> 7,  'key' => 'L2',        'start' => 440, 'lenght' => 8,  'type' => 'f', 'factor' => 1, 'min' => 1, 'max' => 1000000, 'default' => true, 'bolean_accum' => false],
        ['id' => 54, 'variable_name'=> 'ph3_import_kvarh',   'description' => '',    'display_name' => 'L3 ENERGIA kVARh TOTAL',    'variable_id'=> 7,  'key' => 'L3',        'start' => 448, 'lenght' => 8,  'type' => 'f', 'factor' => 1, 'min' => 1, 'max' => 1000000, 'default' => true, 'bolean_accum' => false],
        ['id' => 55, 'variable_name'=> 'ph1_varLh_acumm', 'description' => '',     'display_name' => 'L1 ENERGIA kVARLh TOTAL',    'variable_id'=> 15, 'key' => 'L1',        'start' => 456, 'lenght' => 8,  'type' => 'f', 'factor' => 1, 'min' => 0, 'max' => 1000000, 'default' => 0, 'bolean_accum' => false],
        ['id' => 56, 'variable_name'=> 'ph2_varLh_acumm', 'description' => '',     'display_name' => 'L2 ENERGIA kVARLh TOTAL',    'variable_id'=> 15, 'key' => 'L2',        'start' => 464, 'lenght' => 8,  'type' => 'f', 'factor' => 1, 'min' => 0, 'max' => 1000000, 'default' => 0, 'bolean_accum' => false],
        ['id' => 57, 'variable_name'=> 'ph3_varLh_acumm', 'description' => '',     'display_name' => 'L3 ENERGIA kVARLh TOTAL',    'variable_id'=> 15, 'key' => 'L3',        'start' => 472, 'lenght' => 8,  'type' => 'f', 'factor' => 1, 'min' => 0, 'max' => 1000000, 'default' => 0, 'bolean_accum' => false],
        ['id' => 58, 'variable_name'=> 'ph1_varCh_acumm', 'description' => '',     'display_name' => 'L1 ENERGIA kVARCh TOTAL',    'variable_id'=> 11,  'key' => 'L1',       'start' => 480, 'lenght' => 8,  'type' => 'f', 'factor' => 1, 'min' => 0, 'max' => 1000000, 'default' => 0, 'bolean_accum' => false],
        ['id' => 59, 'variable_name'=> 'ph2_varCh_acumm', 'description' => '',     'display_name' => 'L2 ENERGIA kVARCh TOTAL',    'variable_id'=> 11,  'key' => 'L2',       'start' => 488, 'lenght' => 8,  'type' => 'f', 'factor' => 1, 'min' => 0, 'max' => 1000000, 'default' => 0, 'bolean_accum' => false],
        ['id' => 60, 'variable_name'=> 'ph3_varCh_acumm', 'description' => '',     'display_name' => 'L3 ENERGIA kVARCh TOTAL',    'variable_id'=> 11,  'key' => 'L3',       'start' => 496, 'lenght' => 8,  'type' => 'f', 'factor' => 1, 'min' => 0, 'max' => 1000000, 'default' => 0, 'bolean_accum' => false],
        ['id' => 61, 'variable_name' => 'volt_dc', 'description' => '', 'display_name' => 'VOLTAJE BATERIA', 'variable_id' => 33, 'key' => 'VDC', 'start' => 504, 'lenght' => 8, 'type' => 'f', 'factor' => 1, 'min' => 0, 'max' => 10000, 'default' => 0, 'bolean_accum' => false],

        ['id' => 62, 'variable_name' => 'Wh_calc', 'description' => '', 'display_name' => '', 'variable_id' => '', 'key' => '', 'start' => 504, 'lenght' => 8, 'type' => 'f', 'factor' => 1, 'min' => 0, 'max' => 1000000, 'default' => 0, 'bolean_accum' => false],
        ['id' => 63, 'variable_name' => 'ph1_varCh_interval', 'description' => '', 'display_name' => 'L1 ENERGIA kVARCh HORARIO', 'variable_id' => 12, 'key' => 'L1', 'start' => 512, 'lenght' => 8, 'type' => 'f', 'factor' => 1, 'min' => 0, 'max' => 1000000, 'default' => 0, 'bolean_accum' => true],
        ['id' => 64, 'variable_name' => 'ph1_varLh_interval', 'description' => '', 'display_name' => 'L1 ENERGIA kVARLh HORARIO', 'variable_id' => 16, 'key' => 'L1', 'start' => 520, 'lenght' => 8, 'type' => 'f', 'factor' => 1, 'min' => 0, 'max' => 1000000, 'default' => 0, 'bolean_accum' => true],
        ['id' => 65, 'variable_name' => 'ph2_varCh_interval', 'description' => '', 'display_name' => 'L2 ENERGIA kVARCh HORARIO', 'variable_id' => 12, 'key' => 'L2', 'start' => 528, 'lenght' => 8, 'type' => 'f', 'factor' => 1, 'min' => 0, 'max' => 1000000, 'default' => 0, 'bolean_accum' => true],
        ['id' => 66, 'variable_name' => 'ph2_varLh_interval', 'description' => '', 'display_name' => 'L2 ENERGIA kVARLh HORARIO', 'variable_id' => 16, 'key' => 'L2', 'start' => 536, 'lenght' => 8, 'type' => 'f', 'factor' => 1, 'min' => 0, 'max' => 1000000, 'default' => 0, 'bolean_accum' => true],
        ['id' => 67, 'variable_name' => 'ph3_varCh_interval', 'description' => '', 'display_name' => 'L3 ENERGIA kVARCh HORARIO', 'variable_id' => 12, 'key' => 'L3', 'start' => 544, 'lenght' => 8, 'type' => 'f', 'factor' => 1, 'min' => 0, 'max' => 1000000, 'default' => 0, 'bolean_accum' => true],
        ['id' => 68, 'variable_name' => 'ph3_varLh_interval', 'description' => '', 'display_name' => 'L3 ENERGIA kVARLh HORARIO', 'variable_id' => 16, 'key' => 'L3', 'start' => 552, 'lenght' => 8, 'type' => 'f', 'factor' => 1, 'min' => 0, 'max' => 1000000, 'default' => 0, 'bolean_accum' => true],
        ['id' => 69, 'variable_name' => 'varLh_acumm', 'description' => '', 'display_name' => 'ENERGIA kVARLh TOTAL', 'variable_id' => 13, 'key' => 'TOTAL', 'start' => 560, 'lenght' => 8, 'type' => 'f', 'factor' => 1, 'min' => 0, 'max' => 1000000, 'default' => 0, 'bolean_accum' => false],
        ['id' => 70, 'variable_name' => 'varCh_acumm', 'description' => '', 'display_name' => 'ENERGIA kVARCh TOTAL', 'variable_id' => 9, 'key' => 'TOTAL', 'start' => 572, 'lenght' => 8, 'type' => 'f', 'factor' => 1, 'min' => 0, 'max' => 1000000, 'default' => 0, 'bolean_accum' => false],
        ['id' => 71, 'variable_name' => 'kwh_interval', 'description' => '', 'display_name' => 'ENERGIA kWh HORARIO', 'variable_id' => 2, 'key' => 'TOTAL', 'start' => 580, 'lenght' => 8, 'type' => 'f', 'factor' => 1, 'min' => 0, 'max' => 1000000, 'default' => 0, 'bolean_accum' => true],
        ['id' => 72, 'variable_name' => 'varLh_interval', 'description' => '', 'display_name' => 'ENERGIA kVARLh HORARIO', 'variable_id' => 14, 'key' => 'TOTAL', 'start' => 588, 'lenght' => 8, 'type' => 'f', 'factor' => 1, 'min' => 0, 'max' => 1000000, 'default' => 0, 'bolean_accum' => true],
        ['id' => 73, 'variable_name' => 'varCh_interval', 'description' => '', 'display_name' => 'ENERGIA kVARCh HORARIO', 'variable_id' => 10, 'key' => 'TOTAL', 'start' => 596, 'lenght' => 8, 'type' => 'f', 'factor' => 1, 'min' => 0, 'max' => 1000000, 'default' => 0, 'bolean_accum' => true],
        ['id' => 74, 'variable_name' => 'ph1_kwh_interval', 'description' => '', 'display_name' => 'L1 ENERGIA kWh HORARIO', 'variable_id' => 4, 'key' => 'L1', 'start' => 604, 'lenght' => 8, 'type' => 'f', 'factor' => 1, 'min' => 0, 'max' => 1000000, 'default' => 0, 'bolean_accum' => true],
        ['id' => 75, 'variable_name' => 'ph2_kwh_interval', 'description' => '', 'display_name' => 'L2 ENERGIA kWh HORARIO', 'variable_id' => 4, 'key' => 'L2', 'start' => 612, 'lenght' => 8, 'type' => 'f', 'factor' => 1, 'min' => 0, 'max' => 1000000, 'default' => 0, 'bolean_accum' => true],
        ['id' => 76, 'variable_name' => 'ph3_kwh_interval', 'description' => '', 'display_name' => 'L3 ENERGIA kWh HORARIO', 'variable_id' => 4, 'key' => 'L3', 'start' => 620, 'lenght' => 8, 'type' => 'f', 'factor' => 1, 'min' => 0, 'max' => 1000000, 'default' => 0, 'bolean_accum' => true],
        ['id' => 77, 'variable_name' => 'varh_interval', 'description' => '', 'display_name' => 'ENERGIA kVARh HORARIO', 'variable_id' => 6, 'key' => 'TOTAL', 'start' => 628, 'lenght' => 8, 'type' => 'f', 'factor' => 1, 'min' => 0, 'max' => 1000000, 'default' => 0, 'bolean_accum' => true],
        ['id' => 78, 'variable_name' => 'ph1_varh_interval', 'description' => '', 'display_name' => 'L1 ENERGIA kVARh HORARIO', 'variable_id' => 8, 'key' => 'L1', 'start' => 636, 'lenght' => 8, 'type' => 'f', 'factor' => 1, 'min' => 0, 'max' => 1000000, 'default' => 0, 'bolean_accum' => true],
        ['id' => 79, 'variable_name' => 'ph2_varh_interval', 'description' => '', 'display_name' => 'L2 ENERGIA kVARh HORARIO', 'variable_id' => 8, 'key' => 'L2', 'start' => 644, 'lenght' => 8, 'type' => 'f', 'factor' => 1, 'min' => 0, 'max' => 1000000, 'default' => 0, 'bolean_accum' => true],
        ['id' => 80, 'variable_name' => 'ph3_varh_interval', 'description' => '', 'display_name' => 'L3 ENERGIA kVARh HORARIO', 'variable_id' => 8, 'key' => 'L3', 'start' => 652, 'lenght' => 8, 'type' => 'f', 'factor' => 1, 'min' => 0, 'max' => 1000000, 'default' => 0, 'bolean_accum' => true],
    ],

    'alert_config_frame' => [
        ['variable_name' => 'network_operator_id', 'start' => 0,   'lenght' => 16,'type' => 'Q'],
        ['variable_name' => 'equipment_id', 'start' => 16,   'lenght' => 16,'type' => 'Q'],
        ['variable_name' => 'network_operator_new_id', 'start' => 32,   'lenght' => 16, 'type' => 'Q'],
        ['variable_name' => 'equipment_new_id','start' => 48,   'lenght' => 16, 'type' => 'Q'],
        ['flag_id' => 16,'limit' => 'max_alert', 'variable_name' => 'max_vol_ph_1', 'start' => 64,   'lenght' => 8, 'type' => 'f'],
        ['flag_id' => 16,'limit' => 'min_alert', 'variable_name' => 'min_vol_ph_1', 'start' => 72,   'lenght' => 8,'type' => 'f'],
        ['flag_id' => 17,'limit' => 'max_alert', 'variable_name' => 'max_vol_ph_2', 'start' => 80,   'lenght' => 8,'type' => 'f'],
        ['flag_id' => 17,'limit' => 'min_alert', 'variable_name' => 'min_vol_ph_2', 'start' => 88,   'lenght' => 8,'type' => 'f'],
        ['flag_id' => 18,'limit' => 'max_alert', 'variable_name' => 'max_vol_ph_3', 'start' => 96,   'lenght' => 8,'type' => 'f'],
        ['flag_id' => 18,'limit' => 'min_alert', 'variable_name' => 'min_vol_ph_3', 'start' => 104,   'lenght' => 8,'type' => 'f'],
        ['flag_id' => 19,'limit' => 'max_alert', 'variable_name' => 'max_current_ph_1', 'start' => 112,   'lenght' => 8,'type' => 'f'],
        ['flag_id' => 19,'limit' => 'min_alert', 'variable_name' => 'min_current_ph_1', 'start' => 120,   'lenght' => 8,'type' => 'f'],
        ['flag_id' => 20,'limit' => 'max_alert', 'variable_name' => 'max_current_ph_2', 'start' => 128,   'lenght' => 8,'type' => 'f'],
        ['flag_id' => 20,'limit' => 'min_alert', 'variable_name' => 'min_current_ph_2', 'start' => 136,   'lenght' => 8,'type' => 'f'],
        ['flag_id' => 21,'limit' => 'max_alert', 'variable_name' => 'max_current_ph_3', 'start' => 144,   'lenght' => 8,'type' => 'f'],
        ['flag_id' => 21,'limit' => 'min_alert', 'variable_name' => 'min_current_ph_3', 'start' => 152,   'lenght' => 8,'type' => 'f'],
        ['flag_id' => 22,'limit' => 'max_alert', 'variable_name' => 'max_power_ph_1',  'start' => 160,   'lenght' => 8,'type' => 'f'],
        ['flag_id' => 22,'limit' => 'min_alert', 'variable_name' => 'min_power_ph_1',  'start' => 168,   'lenght' => 8,'type' => 'f'],
        ['flag_id' => 23,'limit' => 'max_alert', 'variable_name' => 'max_power_ph_2',  'start' => 176,   'lenght' => 8,'type' => 'f'],
        ['flag_id' => 23,'limit' => 'min_alert', 'variable_name' => 'min_power_ph_2',  'start' => 184,   'lenght' => 8,'type' => 'f'],
        ['flag_id' => 24,'limit' => 'max_alert', 'variable_name' => 'max_power_ph_3',  'start' => 192,   'lenght' => 8,'type' => 'f'],
        ['flag_id' => 24,'limit' => 'min_alert', 'variable_name' => 'min_power_ph_3',  'start' => 200,   'lenght' => 8,'type' => 'f'],
        ['flag_id' => 25,'limit' => 'max_alert', 'variable_name' => 'max_va_ph_1',     'start' => 208,   'lenght' => 8,'type' => 'f'],
        ['flag_id' => 25,'limit' => 'min_alert', 'variable_name' => 'min_va_ph_1',     'start' => 216,   'lenght' => 8,'type' => 'f'],
        ['flag_id' => 26,'limit' => 'max_alert', 'variable_name' => 'max_va_ph_2',     'start' => 224,   'lenght' => 8,'type' => 'f'],
        ['flag_id' => 26,'limit' => 'min_alert', 'variable_name' => 'min_va_ph_2',     'start' => 232,   'lenght' => 8,'type' => 'f'],
        ['flag_id' => 27,'limit' => 'max_alert', 'variable_name' => 'max_va_ph_3',     'start' => 240,   'lenght' => 8,'type' => 'f'],
        ['flag_id' => 27,'limit' => 'min_alert', 'variable_name' => 'min_va_ph_3',     'start' => 248,   'lenght' => 8,'type' => 'f'],
        ['flag_id' => 28,'limit' => 'max_alert', 'variable_name' => 'max_var_ph_1',    'start' => 256,   'lenght' => 8,'type' => 'f'],
        ['flag_id' => 28,'limit' => 'min_alert', 'variable_name' => 'min_var_ph_1',    'start' => 264,   'lenght' => 8,'type' => 'f'],
        ['flag_id' => 29,'limit' => 'max_alert', 'variable_name' => 'max_var_ph_2',    'start' => 272,   'lenght' => 8,'type' => 'f'],
        ['flag_id' => 29,'limit' => 'min_alert', 'variable_name' => 'min_var_ph_2',    'start' => 280,   'lenght' => 8,'type' => 'f'],
        ['flag_id' => 30,'limit' => 'max_alert', 'variable_name' => 'max_var_ph_3',    'start' => 288,   'lenght' => 8,'type' => 'f'],
        ['flag_id' => 30,'limit' => 'min_alert', 'variable_name' => 'min_var_ph_3',    'start' => 296,   'lenght' => 8,'type' => 'f'],
        ['flag_id' => 31,'limit' => 'max_alert', 'variable_name' => 'max_pfp_ph_1',    'start' => 304,   'lenght' => 8,'type' => 'f'],
        ['flag_id' => 31,'limit' => 'min_alert', 'variable_name' => 'min_pfp_ph_1',    'start' => 312,   'lenght' => 8,'type' => 'f'],
        ['flag_id' => 32,'limit' => 'max_alert', 'variable_name' => 'max_pfp_ph_2',    'start' => 320,   'lenght' => 8,'type' => 'f'],
        ['flag_id' => 32,'limit' => 'min_alert', 'variable_name' => 'min_pfp_ph_2',    'start' => 328,   'lenght' => 8,'type' => 'f'],
        ['flag_id' => 33,'limit' => 'max_alert', 'variable_name' => 'max_pfp_ph_3',    'start' => 336,   'lenght' => 8,'type' => 'f'],
        ['flag_id' => 33,'limit' => 'min_alert', 'variable_name' => 'min_pfp_ph_3',    'start' => 344,   'lenght' => 8,'type' => 'f'],
        ['flag_id' => 34,'limit' => 'max_alert', 'variable_name' => 'max_pfp',    'start' => 352,   'lenght' => 8,'type' => 'f'],
        ['flag_id' => 34,'limit' => 'min_alert', 'variable_name' => 'min_pfp',    'start' => 360,   'lenght' => 8,'type' => 'f'],
        ['flag_id' => 35,'limit' => 'max_alert', 'variable_name' => 'max_af',    'start' => 368,   'lenght' => 8,'type' => 'f'],
        ['flag_id' => 35,'limit' => 'min_alert', 'variable_name' => 'min_af',    'start' => 376,   'lenght' => 8,'type' => 'f'],
        ['flag_id' => 36,'limit' => 'max_alert', 'variable_name' => 'max_power',    'start' => 384,   'lenght' => 8,'type' => 'f'],
        ['flag_id' => 36,'limit' => 'min_alert', 'variable_name' => 'min_power',    'start' => 392,   'lenght' => 8,'type' => 'f'],
        ['flag_id' => 37,'limit' => 'max_alert', 'variable_name' => 'max_freq',        'start' => 400,   'lenght' => 8,'type' => 'f'],
        ['flag_id' => 37,'limit' => 'min_alert', 'variable_name' => 'min_freq',        'start' => 408,   'lenght' => 8,'type' => 'f'],
        ['flag_id' => 38,'limit' => 'max_alert', 'variable_name' => 'max_volt_1_2',    'start' => 416,   'lenght' => 8,'type' => 'f'],
        ['flag_id' => 38,'limit' => 'min_alert', 'variable_name' => 'min_volt_1_2',    'start' => 424,   'lenght' => 8,'type' => 'f'],
        ['flag_id' => 39,'limit' => 'max_alert', 'variable_name' => 'max_volt_3_1',    'start' => 432,   'lenght' => 8,'type' => 'f'],
        ['flag_id' => 39,'limit' => 'min_alert', 'variable_name' => 'min_volt_3_1',    'start' => 440,   'lenght' => 8,'type' => 'f'],
        ['flag_id' => 40,'limit' => 'max_alert', 'variable_name' => 'max_volt_2_3',    'start' => 448,   'lenght' => 8,'type' => 'f'],
        ['flag_id' => 40,'limit' => 'min_alert', 'variable_name' => 'min_volt_2_3',    'start' => 456,   'lenght' => 8,'type' => 'f'],
        ['flag_id' => 41, 'limit' => 'max_alert', 'variable_name' => 'max_volt_dc', 'start' => 464, 'lenght' => 8, 'type' => 'f'],
        ['flag_id' => 41, 'limit' => 'min_alert', 'variable_name' => 'min_volt_dc', 'start' => 472, 'lenght' => 8, 'type' => 'f'],
        ['flag_id' => 42, 'limit' => 'max_alert', 'variable_name' => 'max_var', 'start' => 480, 'lenght' => 8, 'type' => 'f'],
        ['flag_id' => 42, 'limit' => 'min_alert', 'variable_name' => 'min_var', 'start' => 488, 'lenght' => 8, 'type' => 'f'],

        ['flag_id' => 43, 'limit' => 'max_alert', 'variable_name' => 'kwh_month', 'start' => 496, 'lenght' => 8, 'type' => 'f'],
        ['flag_id' => 44, 'limit' => 'max_alert', 'variable_name' => 'kvarlh_month', 'start' => 504, 'lenght' => 8, 'type' => 'f'],
        ['flag_id' => 45, 'limit' => 'max_alert', 'variable_name' => 'kvarch_month', 'start' => 512, 'lenght' => 8, 'type' => 'f'],
        ['flag_id' => 46, 'limit' => 'max_alert', 'variable_name' => 'kwh_hour', 'start' => 520, 'lenght' => 8, 'type' => 'f'],
        ['flag_id' => 47, 'limit' => 'max_alert', 'variable_name' => 'kvarlh_hour', 'start' => 528, 'lenght' => 8, 'type' => 'f'],
        ['flag_id' => 48, 'limit' => 'max_alert', 'variable_name' => 'kvarch_hour', 'start' => 536, 'lenght' => 8, 'type' => 'f'],
        ['flag_id' => 49, 'limit' => 'max_alert', 'variable_name' => 'kvarlh_penalizable', 'start' => 544, 'lenght' => 8, 'type' => 'f'],
        ['flag_id' => 50, 'limit' => 'max_alert', 'variable_name' => 'kvarch/kwh', 'start' => 552, 'lenght' => 8, 'type' => 'f'],

        ],
    'alert_config_time_frame' => [
        ['flag_id' => 16,'limit' => 'time_alert', 'variable_name' => 'time_vol_ph_1', 'start' => 64,   'lenght' => 8, 'type' => 'f'],
        ['flag_id' => 17,'limit' => 'time_alert', 'variable_name' => 'time_vol_ph_2', 'start' => 80,   'lenght' => 8,'type' => 'f'],
        ['flag_id' => 18,'limit' => 'time_alert', 'variable_name' => 'time_vol_ph_3', 'start' => 96,   'lenght' => 8,'type' => 'f'],
        ['flag_id' => 19,'limit' => 'time_alert', 'variable_name' => 'time_current_ph_1', 'start' => 112,   'lenght' => 8,'type' => 'f'],
        ['flag_id' => 20,'limit' => 'time_alert', 'variable_name' => 'time_current_ph_2', 'start' => 128,   'lenght' => 8,'type' => 'f'],
        ['flag_id' => 21,'limit' => 'time_alert', 'variable_name' => 'time_current_ph_3', 'start' => 144,   'lenght' => 8,'type' => 'f'],
        ['flag_id' => 22,'limit' => 'time_alert', 'variable_name' => 'time_power_ph_1',  'start' => 160,   'lenght' => 8,'type' => 'f'],
        ['flag_id' => 23,'limit' => 'time_alert', 'variable_name' => 'time_power_ph_2',  'start' => 176,   'lenght' => 8,'type' => 'f'],
        ['flag_id' => 24,'limit' => 'time_alert', 'variable_name' => 'time_power_ph_3',  'start' => 192,   'lenght' => 8,'type' => 'f'],
        ['flag_id' => 25,'limit' => 'time_alert', 'variable_name' => 'time_va_ph_1',     'start' => 208,   'lenght' => 8,'type' => 'f'],
        ['flag_id' => 26,'limit' => 'time_alert', 'variable_name' => 'time_va_ph_2',     'start' => 224,   'lenght' => 8,'type' => 'f'],
        ['flag_id' => 27,'limit' => 'time_alert', 'variable_name' => 'time_va_ph_3',     'start' => 240,   'lenght' => 8,'type' => 'f'],
        ['flag_id' => 28,'limit' => 'time_alert', 'variable_name' => 'time_var_ph_1',    'start' => 256,   'lenght' => 8,'type' => 'f'],
        ['flag_id' => 29,'limit' => 'time_alert', 'variable_name' => 'time_var_ph_2',    'start' => 272,   'lenght' => 8,'type' => 'f'],
        ['flag_id' => 30,'limit' => 'time_alert', 'variable_name' => 'time_var_ph_3',    'start' => 288,   'lenght' => 8,'type' => 'f'],
        ['flag_id' => 31,'limit' => 'time_alert', 'variable_name' => 'time_pfp_ph_1',    'start' => 304,   'lenght' => 8,'type' => 'f'],
        ['flag_id' => 32,'limit' => 'time_alert', 'variable_name' => 'time_pfp_ph_2',    'start' => 320,   'lenght' => 8,'type' => 'f'],
        ['flag_id' => 33,'limit' => 'time_alert', 'variable_name' => 'time_pfp_ph_3',    'start' => 336,   'lenght' => 8,'type' => 'f'],
        ['flag_id' => 34,'limit' => 'time_alert', 'variable_name' => 'time_pfp',    'start' => 352,   'lenght' => 8,'type' => 'f'],
        ['flag_id' => 35,'limit' => 'time_alert', 'variable_name' => 'time_af',    'start' => 368,   'lenght' => 8,'type' => 'f'],
        ['flag_id' => 36,'limit' => 'time_alert', 'variable_name' => 'time_power',    'start' => 384,   'lenght' => 8,'type' => 'f'],
        ['flag_id' => 37,'limit' => 'time_alert', 'variable_name' => 'time_freq',        'start' => 400,   'lenght' => 8,'type' => 'f'],
        ['flag_id' => 38,'limit' => 'time_alert', 'variable_name' => 'time_volt_1_2',    'start' => 416,   'lenght' => 8,'type' => 'f'],
        ['flag_id' => 39,'limit' => 'time_alert', 'variable_name' => 'time_volt_3_1',    'start' => 432,   'lenght' => 8,'type' => 'f'],
        ['flag_id' => 40,'limit' => 'time_alert', 'variable_name' => 'time_volt_2_3',    'start' => 448,   'lenght' => 8,'type' => 'f'],
        ['flag_id' => 41,'limit' => 'time_alert', 'variable_name' => 'time_vthd_ph_1',   'start' => 464,   'lenght' => 8,'type' => 'f'],
        ['flag_id' => 42,'limit' => 'time_alert', 'variable_name' => 'time_vthd_ph_2',   'start' => 480,   'lenght' => 8,'type' => 'f'],
        ['flag_id' => 43,'limit' => 'time_alert', 'variable_name' => 'time_vthd_ph_3',   'start' => 496,   'lenght' => 8,'type' => 'f'],
        ['flag_id' => 44,'limit' => 'time_alert', 'variable_name' => 'time_cthd_ph_1',   'start' => 512,   'lenght' => 8,'type' => 'f'],
        ['flag_id' => 45,'limit' => 'time_alert', 'variable_name' => 'time_cthd_ph_2',   'start' => 528,   'lenght' => 8,'type' => 'f'],
        ['flag_id' => 46,'limit' => 'time_alert', 'variable_name' => 'time_cthd_ph_3',   'start' => 544,   'lenght' => 8,'type' => 'f'],
        ['flag_id' => 47,'limit' => 'time_alert', 'variable_name' => 'time_vthd_ph_1_2', 'start' => 560,   'lenght' => 8,'type' => 'f'],
        ['flag_id' => 48,'limit' => 'time_alert', 'variable_name' => 'time_vthd_ph_2_3', 'start' => 576,   'lenght' => 8,'type' => 'f'],
        ['flag_id' => 49,'limit' => 'time_alert', 'variable_name' => 'time_vthd_ph_3_1', 'start' => 592,   'lenght' => 8,'type' => 'f'],
        ['flag_id' => 50, 'limit' => 'time_alert', 'variable_name' => 'time_volt_dc', 'start' => 608, 'lenght' => 8, 'type' => 'f'],
    ],
    'flags_frame' => [
        ['id' => 1 ,'flag_name'=> 'flagStorage', 'description' => '',      'bit' => 0, 'index' => 63],
        ['id' => 2 ,'flag_name'=> 'flagAlert', 'description' => '',        'bit' => 1, 'index' => 62],
        ['id' => 3 ,'flag_name'=> 'flagIsWifi', 'description' => '',       'bit' => 2, 'index' => 61],
        ['id' => 4 ,'flag_name'=> 'coil1', 'description' => '',            'bit' => 3, 'index' => 60],
        ['id' => 5 ,'flag_name'=> 'coil2', 'description' => '',            'bit' => 4, 'index' => 59],
        ['id' => 6 ,'flag_name'=> 'coil3', 'description' => '',            'bit' => 5, 'index' => 58],
        ['id' => 7 ,'flag_name'=> 'coil4', 'description' => '',            'bit' => 6, 'index' => 57],
        ['id' => 8 ,'flag_name'=> 'coil5', 'description' => '',            'bit' => 7, 'index' => 56],
        ['id' => 9 ,'flag_name'=> 'coil6', 'description' => '',            'bit' => 8, 'index' => 55],
        ['id' => 10,'flag_name'=> 'coil7', 'description' => '',            'bit' => 9, 'index' => 54],
        ['id' => 11,'flag_name'=> 'coil8', 'description' => '',            'bit' => 10, 'index' => 53],
        ['id' => 12,'flag_name'=> 'coil9', 'description' => '',            'bit' => 11, 'index' => 52],
        ['id' => 13,'flag_name'=> 'coil10', 'description' => '',           'bit' => 12, 'index' => 51],
        ['id' => 14,'flag_name'=> 'flagOpened', 'description' => '',       'bit' => 27, 'index' => 36],
        ['id' => 15,'flag_name'=> 'flagAdc1', 'description' => '',         'bit' => 28, 'index' => 35],
        ['id' => 16,'flag_name'=> 'flagVolt1', 'description' => '',     'variable_name'=> 'ph1_volt',          'placeholder'=>'Voltaje fase 1', 'bit' => 29, 'index' => 34, 'variable_id' => 7],
        ['id' => 17,'flag_name'=> 'flagVolt2', 'description' => '',     'variable_name'=> 'ph2_volt',          'placeholder'=>'Voltaje fase 2', 'bit' => 30, 'index' => 33, 'variable_id' => 8],
        ['id' => 18,'flag_name'=> 'flagVolt3', 'description' => '',     'variable_name'=> 'ph3_volt',          'placeholder'=>'Voltaje fase 3', 'bit' => 31, 'index' => 32, 'variable_id' => 9],
        ['id' => 19,'flag_name'=> 'flagCurr1', 'description' => '',     'variable_name'=> 'ph1_current',       'placeholder'=>'Corriente fase 1', 'bit' => 32, 'index' => 31, 'variable_id' => 10],
        ['id' => 20,'flag_name'=> 'flagCurr2', 'description' => '',     'variable_name'=> 'ph2_current',       'placeholder'=>'Corriente fase 2', 'bit' => 33, 'index' => 30, 'variable_id' => 11],
        ['id' => 21,'flag_name'=> 'flagCurr3', 'description' => '',     'variable_name'=> 'ph3_current',       'placeholder'=>'Corriente fase 3', 'bit' => 34, 'index' => 29, 'variable_id' => 12],
        ['id' => 22,'flag_name'=> 'flagPot1', 'description' => '',      'variable_name'=> 'ph1_power',         'placeholder'=>'Potencia fase 1', 'bit' => 35, 'index' => 28, 'variable_id' => 13],
        ['id' => 23,'flag_name'=> 'flagPot2', 'description' => '',      'variable_name'=> 'ph2_power',         'placeholder'=>'Potencia fase 2', 'bit' => 36, 'index' => 27, 'variable_id' => 14],
        ['id' => 24,'flag_name'=> 'flagPot3', 'description' => '',      'variable_name'=> 'ph3_power',         'placeholder'=>'Potencia fase 3', 'bit' => 37, 'index' => 26, 'variable_id' => 15],
        ['id' => 25,'flag_name'=> 'flagVA1', 'description' => '',       'variable_name'=> 'ph1_VA',            'placeholder'=>'Voltio-Amperios fase 1', 'bit' => 38, 'index' => 25, 'variable_id' => 16],
        ['id' => 26,'flag_name'=> 'flagVA2', 'description' => '',       'variable_name'=> 'ph2_VA',            'placeholder'=>'Voltio-Amperios fase 2', 'bit' => 39, 'index' => 24, 'variable_id' => 17],
        ['id' => 27,'flag_name'=> 'flagVA3', 'description' => '',       'variable_name'=> 'ph3_VA',            'placeholder'=>'Voltio-Amperios fase 3', 'bit' => 40, 'index' => 23, 'variable_id' => 18],
        ['id' => 28,'flag_name'=> 'flagVAr1', 'description' => '',      'variable_name'=> 'ph1_VAr',           'placeholder'=>'Voltio-Amperios reactivos fase 1', 'bit' => 41, 'index' => 22, 'variable_id' => 19],
        ['id' => 29,'flag_name'=> 'flagVAr2', 'description' => '',      'variable_name'=> 'ph2_VAr',           'placeholder'=>'Voltio-Amperios reactivos fase 2', 'bit' => 42, 'index' => 21, 'variable_id' => 20],
        ['id' => 30,'flag_name'=> 'flagVAr3', 'description' => '',      'variable_name'=> 'ph3_VAr',           'placeholder'=>'Voltio-Amperios reactivos fase 3', 'bit' => 43, 'index' => 20, 'variable_id' => 21],
        ['id' => 31,'flag_name'=> 'flagPF1', 'description' => '',       'variable_name'=> 'ph1_power_factor',  'placeholder'=>'Factor de potencia fase 1', 'bit' => 44, 'index' => 19, 'variable_id' => 22],
        ['id' => 32,'flag_name'=> 'flagPF2', 'description' => '',       'variable_name'=> 'ph2_power_factor',  'placeholder'=>'Factor de potencia fase 2', 'bit' => 45, 'index' => 18, 'variable_id' => 23],
        ['id' => 33,'flag_name'=> 'flagPF3', 'description' => '',       'variable_name'=> 'ph3_power_factor',  'placeholder'=>'Factor de potencia fase 3', 'bit' => 46, 'index' => 17, 'variable_id' => 24],
        ['id' => 34,'flag_name'=> 'flagPF', 'description' => '',       'variable_name'=> 'total_power_factor',  'placeholder'=>'Factor de potencia total', 'bit' => 47, 'index' => 16, 'variable_id' => 25],
        ['id' => 35,'flag_name'=> 'flagAP', 'description' => '',       'variable_name'=> 'total_phase_angle',  'placeholder'=>'Angulo de fase total', 'bit' => 48, 'index' => 15, 'variable_id' => 29],
        ['id' => 36,'flag_name'=> 'flagWT', 'description' => '',       'variable_name'=> 'total_system_power',  'placeholder'=>'Potencia total', 'bit' => 49, 'index' => 14, 'variable_id' => 30],

        ['id' => 37,'flag_name'=> 'flag_Freq', 'description' => '',     'variable_name'=> 'frequency',  'placeholder'=>'Frecuencia', 'bit' => 50, 'index' => 13, 'variable_id' => 32],
        ['id' => 38,'flag_name'=> 'flagVolt12', 'description' => '',    'variable_name'=> 'ph1_ph2_volt',      'placeholder'=>'Voltaje entre fase 1 y 2', 'bit' => 51, 'index' => 12, 'variable_id' => 37],
        ['id' => 39,'flag_name'=> 'flagVolt23', 'description' => '',    'variable_name'=> 'ph2_ph3_volt',      'placeholder'=>'Voltaje entre fase 2 y 3', 'bit' => 52, 'index' => 11, 'variable_id' => 38],
        ['id' => 40,'flag_name'=> 'flagVolt31', 'description' => '',    'variable_name'=> 'ph3_ph1_volt',      'placeholder'=>'Voltaje entre fase 3 y 1', 'bit' => 53, 'index' => 10,  'variable_id' => 39],
        ['id' => 41,'flag_name'=> 'flagDcVolt', 'description' => '',     'variable_name'=> 'ph1_volt',          'placeholder'=>'Voltaje bateria', 'bit' => 54, 'index' => 9, 'variable_id' => 61],
        ['id' => 42,'flag_name'=> 'flagvar', 'description' => '',     'variable_name'=> 'var',          'placeholder'=>'Voltio-amperios reactivo', 'bit' => 55, 'index' => 8, 'variable_id' => 31],

        ['id' => 43,'flag_name'=> 'kwh_month', 'description' => '', 'variable_name'=>'kwh_month','placeholder'=> 'kWh/Mes', 'bit' => 19, 'index' => 44, 'variable_id' => 61],
        ['id' => 44,'flag_name'=> 'kvarlh_month', 'description' => '', 'variable_name'=>'kvarlh_month', 'placeholder'=> 'kVARLh/Mes', 'bit' => 20, 'index' => 43, 'variable_id' => 61],
        ['id' => 45,'flag_name'=> 'kvarch_month', 'description' => '', 'variable_name'=>'kvarch_month', 'placeholder'=> 'kVARCh/Mes', 'bit' => 21, 'index' => 42, 'variable_id' => 61],
        ['id' => 46,'flag_name'=> 'kwh_hour', 'description' => '', 'variable_name'=>'kwh_hour', 'placeholder'=> 'kWh/Hora', 'bit' => 22, 'index' => 41, 'variable_id' => 61],
        ['id' => 47,'flag_name'=> 'kvarlh_hour', 'description' => '', 'variable_name'=>'kvarlh_hour', 'placeholder'=> 'kVARLh/Hora', 'bit' => 23, 'index' => 40, 'variable_id' => 61],
        ['id' => 48,'flag_name'=> 'kvarch_hour', 'description' => '', 'variable_name'=>'kvarch_hour', 'placeholder'=> 'kVARCh/Hora', 'bit' => 24, 'index' => 39, 'variable_id' => 61],
        ['id' => 49,'flag_name'=> 'kvarlh_penalizable', 'description' => '', 'variable_name'=>'kvarlh_penalizable', 'placeholder'=> 'Porcentaje horario kVARLh/kWh', 'bit' => 25, 'index' => 38, 'variable_id' => 61],
        ['id' => 50,'flag_name'=> 'kvarch/kwh', 'description' => '', 'variable_name'=>'kvarch/kwh', 'placeholder'=> 'Porcentaje horario kVARCh/kWh', 'bit' => 26, 'index' => 37, 'variable_id' => 61],

    ],

    ////
    ///
    'data_frame_events' => [
        [
            'event_id' => 1,
            'frame' => [
                ['id' => 1, 'variable_name'=> 'event_id',     'start' => 0,  'parameter_name' => null, 'format' => 'number', 'lenght' => 1, 'type' => 'C'],
                ['id' => 2, 'variable_name'=> 'id_event_log', 'start' => 1,  'parameter_name' => null, 'format' => 'number', 'lenght' => 4, 'type' => 'V'],
                ['id' => 3, 'variable_name'=> 'frame_alerts', 'start' => 5,  'parameter_name' => null, 'format' => 'unix',   'lenght' => 4, 'type' => 'V'],
                ['id' => 4, 'variable_name'=> 'crc',          'start' => 9, 'parameter_name' => null, 'format' => 'number', 'lenght' => 2, 'type' => 'v'],
            ],
            'uri_event' => \App\Models\V1\Api\EventLog::EVENT_SET_ALERT_LIMITS
        ],
        [
            'event_id' => 2,
            'frame' => [
                ['id' => 1, 'variable_name'=> 'event_id',     'start' => 0,  'parameter_name' => null, 'format' => 'number', 'lenght' => 1, 'type' => 'C'],
                ['id' => 2, 'variable_name'=> 'id_event_log', 'start' => 1,  'parameter_name' => null, 'format' => 'number', 'lenght' => 4, 'type' => 'V'],
                ['id' => 3, 'variable_name'=> 'timestamp', 'start' => 5,  'parameter_name' => null, 'format' => 'unix',   'lenght' => 4, 'type' => 'V'],
                ['id' => 4, 'variable_name'=> 'serial', 'start' => 9,  'parameter_name' => null, 'format' => 'unix',   'lenght' => 4, 'type' => 'V'],
                ['id' => 5, 'variable_name'=> 'crc',          'start' => 13, 'parameter_name' => null, 'format' => 'number', 'lenght' => 2, 'type' => 'v'],
            ],
            'job_name' => 'SaveAlertConfigurations'
        ],
        [
            'event_id' => 3,
            'frame' => [
                ['id' => 1, 'variable_name'=> 'event_id',     'start' => 0,  'parameter_name' => null, 'format' => 'number', 'lenght' => 1, 'type' => 'C'],
                ['id' => 2, 'variable_name'=> 'id_event_log', 'start' => 1,  'parameter_name' => null, 'format' => 'number', 'lenght' => 4, 'type' => 'V'],
                ['id' => 3, 'variable_name'=> 'frame_alerts', 'start' => 5,  'parameter_name' => null, 'format' => 'unix',   'lenght' => 4, 'type' => 'V'],
                ['id' => 5, 'variable_name'=> 'crc',          'start' => 9, 'parameter_name' => null, 'format' => 'number', 'lenght' => 2, 'type' => 'v'],
            ],
            'uri_event' => \App\Models\V1\Api\EventLog::EVENT_SET_ALERT_TIME
        ],
        [
            'event_id' => 4,
            'frame' => [
                ['id' => 1, 'variable_name'=> 'event_id',     'start' => 0,  'parameter_name' => null, 'format' => 'number', 'lenght' => 1, 'type' => 'C'],
                ['id' => 2, 'variable_name'=> 'id_event_log', 'start' => 1,  'parameter_name' => null, 'format' => 'number', 'lenght' => 4, 'type' => 'V'],
                ['id' => 3, 'variable_name'=> 'timestamp', 'start' => 5,  'parameter_name' => null, 'format' => 'unix',   'lenght' => 4, 'type' => 'V'],
                ['id' => 4, 'variable_name'=> 'serial', 'start' => 9,  'parameter_name' => null, 'format' => 'unix',   'lenght' => 4, 'type' => 'V'],
                ['id' => 5, 'variable_name'=> 'crc',          'start' => 13, 'parameter_name' => null, 'format' => 'number', 'lenght' => 2, 'type' => 'v'],
            ]
        ],
        [
            'event_id' => 5,
            'frame' => [
                ['id' => 1 , 'variable_name'=> 'event_id',             'start' => 0, 'parameter_name' => null,                   'format' => 'number', 'lenght' => 1, 'type' => 'C'],
                ['id' => 2 , 'variable_name'=> 'id_event_log',         'start' => 1, 'parameter_name' => null,                   'format' => 'number', 'lenght' => 4, 'type' => 'V'],
                ['id' => 3 , 'variable_name'=> 'time_sampling_choice', 'start' => 5, 'parameter_name' => 'time_sampling_choice', 'format' => 'number', 'lenght' => 1, 'type' => 'C'],
                ['id' => 4 , 'variable_name'=> 'data_per_interval',    'start' => 6, 'parameter_name' => 'data_per_interval',    'format' => 'number', 'lenght' => 1, 'type' => 'C'],
                ['id' => 5 , 'variable_name'=> 'data_per_seconds',     'start' => 7, 'parameter_name' => 'data_per_seconds',     'format' => 'number', 'lenght' => 1, 'type' => 'C'],
                ['id' => 6 , 'variable_name'=> 'crc',                  'start' => 8, 'parameter_name' => null,                   'format' => 'number', 'lenght' => 2, 'type' => 'v'],
            ],
            'uri_event' => \App\Models\V1\Api\EventLog::EVENT_SET_SAMPLING_TIME
        ],
        [
            'event_id' => 6,
            'frame' => [
                ['id' => 1 , 'variable_name'=> 'event_id',     'start' => 0,  'parameter_name' => null, 'format' => 'number', 'lenght' => 1, 'type' => 'C'],
                ['id' => 2 , 'variable_name'=> 'id_event_log', 'start' => 1,  'parameter_name' => null, 'format' => 'number', 'lenght' => 4, 'type' => 'V'],
                ['id' => 3 , 'variable_name'=> 'timestamp',    'start' => 5,  'parameter_name' => null, 'format' => 'unix',   'lenght' => 4, 'type' => 'V'],
                ['id' => 4 , 'variable_name'=> 'serial',       'start' => 9,  'parameter_name' => null, 'format' => 'number', 'lenght' => 4, 'type' => 'V'],
                ['id' => 5 , 'variable_name'=> 'crc',          'start' => 13, 'parameter_name' => null, 'format' => 'number', 'lenght' => 2, 'type' => 'v'],
            ]
        ],
        [
            'event_id' => 7,
            'frame' => [
                ['id' => 1 , 'variable_name'=> 'event_id',        'start' => 0, 'parameter_name' => null,       'format' => 'number', 'lenght' => 1, 'type' => 'C'],
                ['id' => 2 , 'variable_name'=> 'id_event_log',    'start' => 1, 'parameter_name' => null,       'format' => 'number', 'lenght' => 4, 'type' => 'V'],
                ['id' => 4 , 'variable_name'=> 'lenght_ssid',     'start' => 5, 'parameter_name' => null,       'format' => 'lenght', 'lenght' => 1, 'type' => 'C'],
                ['id' => 5 , 'variable_name'=> 'ssid',            'start' => 6, 'parameter_name' => 'ssid',     'format' => 'string', 'lenght' => 1, 'type' => 'C'],
                ['id' => 6 , 'variable_name'=> 'lenght_password', 'start' => 7, 'parameter_name' => null,       'format' => 'lenght', 'lenght' => 1, 'type' => 'C'],
                ['id' => 7 , 'variable_name'=> 'password',        'start' => 8, 'parameter_name' => 'password', 'format' => 'string', 'lenght' => 1, 'type' => 'C'],
                ['id' => 8 , 'variable_name'=> 'crc',             'start' => 9, 'parameter_name' => null,       'format' => 'number', 'lenght' => 2, 'type' => 'v'],
            ],
            'uri_event' => \App\Models\V1\Api\EventLog::EVENT_SET_WIFI_CREDENTIALS
        ],
        [
            'event_id' => 8,
            'frame' => [
                ['id' => 1 , 'variable_name'=> 'event_id',     'start' => 0,  'parameter_name' => null, 'format' => 'number', 'lenght' => 1, 'type' => 'C'],
                ['id' => 2 , 'variable_name'=> 'id_event_log', 'start' => 1,  'parameter_name' => null, 'format' => 'number', 'lenght' => 4, 'type' => 'V'],
                ['id' => 3 , 'variable_name'=> 'timestamp',    'start' => 5,  'parameter_name' => null, 'format' => 'unix',   'lenght' => 4, 'type' => 'V'],
                ['id' => 4 , 'variable_name'=> 'serial',       'start' => 9,  'parameter_name' => null, 'format' => 'number', 'lenght' => 4, 'type' => 'V'],
                ['id' => 5 , 'variable_name'=> 'crc',          'start' => 13, 'parameter_name' => null, 'format' => 'number', 'lenght' => 2, 'type' => 'v'],
            ]
        ],
        [
            'event_id' => 9,
            'frame' => [
                ['id' => 1 , 'variable_name'=> 'event_id',        'start' => 0, 'parameter_name' => null,        'format' => 'number', 'lenght' => 1, 'type' => 'C'],
                ['id' => 2 , 'variable_name'=> 'id_event_log',    'start' => 1, 'parameter_name' => null,        'format' => 'number', 'lenght' => 4, 'type' => 'V'],
                ['id' => 3 , 'variable_name'=> 'lenght_host',     'start' => 5, 'parameter_name' => null,        'format' => 'lenght', 'lenght' => 1, 'type' => 'C'],
                ['id' => 4 , 'variable_name'=> 'host',            'start' => 6, 'parameter_name' => 'host',      'format' => 'string', 'lenght' => 1, 'type' => 'C'],
                ['id' => 5 , 'variable_name'=> 'port',            'start' => 7, 'parameter_name' => 'port',      'format' => 'number', 'lenght' => 2, 'type' => 'v'],
                ['id' => 6 , 'variable_name'=> 'lenght_user',     'start' => 9, 'parameter_name' => null,        'format' => 'lenght', 'lenght' => 1, 'type' => 'C'],
                ['id' => 7 , 'variable_name'=> 'user',            'start' => 10, 'parameter_name' => 'user',     'format' => 'string', 'lenght' => 1, 'type' => 'C'],
                ['id' => 8 , 'variable_name'=> 'lenght_password', 'start' => 11, 'parameter_name' => null,       'format' => 'lenght', 'lenght' => 1, 'type' => 'C'],
                ['id' => 9 , 'variable_name'=> 'password',        'start' => 12, 'parameter_name' => 'password', 'format' => 'string', 'lenght' => 1, 'type' => 'C'],
                ['id' => 10, 'variable_name'=> 'crc',             'start' => 13, 'parameter_name' => null,       'format' => 'number', 'lenght' => 2, 'type' => 'v'],
            ],
            'uri_event' => \App\Models\V1\Api\EventLog::EVENT_SET_BROKER_CREDENTIALS
        ],
        [
            'event_id' => 10,
            'frame' => [
                ['id' => 1 , 'variable_name'=> 'event_id',     'start' => 0,  'parameter_name' => null, 'format' => 'number', 'lenght' => 1, 'type' => 'C'],
                ['id' => 2 , 'variable_name'=> 'id_event_log', 'start' => 1,  'parameter_name' => null, 'format' => 'number', 'lenght' => 4, 'type' => 'V'],
                ['id' => 3 , 'variable_name'=> 'timestamp',    'start' => 5,  'parameter_name' => null, 'format' => 'unix',   'lenght' => 4, 'type' => 'V'],
                ['id' => 4 , 'variable_name'=> 'serial',       'start' => 9,  'parameter_name' => null, 'format' => 'number', 'lenght' => 4, 'type' => 'V'],
                ['id' => 5 , 'variable_name'=> 'crc',          'start' => 13, 'parameter_name' => null, 'format' => 'number', 'lenght' => 2, 'type' => 'v'],
            ]
        ],
        [
            'event_id' => 11,
            'frame' => [
                ['id' => 1 , 'variable_name'=> 'event_id',     'start' => 0, 'parameter_name' => null,        'format' => 'number', 'lenght' => 1, 'type' => 'C'],
                ['id' => 2 , 'variable_name'=> 'id_event_log', 'start' => 1, 'parameter_name' => null,        'format' => 'number', 'lenght' => 4, 'type' => 'V'],
                ['id' => 3 , 'variable_name'=> 'timestamp',    'start' => 5, 'parameter_name' => null,        'format' => 'unix',   'lenght' => 4, 'type' => 'V'],
                ['id' => 4 , 'variable_name'=> 'crc',          'start' => 9, 'parameter_name' => null,        'format' => 'number', 'lenght' => 2, 'type' => 'v'],
            ],
            'uri_event' => \App\Models\V1\Api\EventLog::EVENT_SET_DATE
        ],
        [
            'event_id' => 12,
            'frame' => [
                ['id' => 1 , 'variable_name'=> 'event_id',     'start' => 0,  'parameter_name' => null, 'format' => 'number', 'lenght' => 1, 'type' => 'C'],
                ['id' => 2 , 'variable_name'=> 'id_event_log', 'start' => 1,  'parameter_name' => null, 'format' => 'number', 'lenght' => 4, 'type' => 'V'],
                ['id' => 3 , 'variable_name'=> 'timestamp',    'start' => 5,  'parameter_name' => null, 'format' => 'unix',   'lenght' => 4, 'type' => 'V'],
                ['id' => 4 , 'variable_name'=> 'serial',       'start' => 9,  'parameter_name' => null, 'format' => 'number', 'lenght' => 4, 'type' => 'V'],
                ['id' => 5 , 'variable_name'=> 'crc',          'start' => 13, 'parameter_name' => null, 'format' => 'number', 'lenght' => 2, 'type' => 'v'],
            ]
        ],
        [
            'event_id' => 13,
            'frame' => [
                ['id' => 1 , 'variable_name'=> 'event_id',     'start' => 0, 'parameter_name' => null, 'format' => 'number', 'lenght' => 1, 'type' => 'C'],
                ['id' => 2 , 'variable_name'=> 'id_event_log', 'start' => 1, 'parameter_name' => null, 'format' => 'number', 'lenght' => 4, 'type' => 'V'],
                ['id' => 3 , 'variable_name'=> 'crc',          'start' => 5, 'parameter_name' => null, 'format' => 'number', 'lenght' => 2, 'type' => 'v'],
            ],
            'uri_event' => \App\Models\V1\Api\EventLog::EVENT_GET_DATE
        ],
        [
            'event_id' => 14,
            'frame' => [
                ['id' => 1 , 'variable_name'=> 'event_id',     'start' => 0,  'parameter_name' => null, 'format' => 'number', 'lenght' => 1, 'type' => 'C'],
                ['id' => 2 , 'variable_name'=> 'id_event_log', 'start' => 1,  'parameter_name' => null, 'format' => 'number', 'lenght' => 4, 'type' => 'V'],
                ['id' => 3 , 'variable_name'=> 'timestamp',    'start' => 5,  'parameter_name' => null, 'format' => 'unix',   'lenght' => 4, 'type' => 'V'],
                ['id' => 4 , 'variable_name'=> 'serial',       'start' => 9,  'parameter_name' => null, 'format' => 'number', 'lenght' => 4, 'type' => 'V'],
                ['id' => 5 , 'variable_name'=> 'crc',          'start' => 13, 'parameter_name' => null, 'format' => 'number', 'lenght' => 2, 'type' => 'v'],
            ]
        ],
        [
            'event_id' => 15,
            'frame' => [
                ['id' => 1 , 'variable_name'=> 'event_id',     'start' => 0, 'parameter_name' => null,     'format' => 'number', 'lenght' => 1, 'type' => 'C'],
                ['id' => 2 , 'variable_name'=> 'id_event_log', 'start' => 1, 'parameter_name' => null,     'format' => 'number', 'lenght' => 4, 'type' => 'V'],
                ['id' => 3 , 'variable_name'=> 'salida_id',    'start' => 5, 'parameter_name' => null,     'format' => 'number', 'lenght' => 1, 'type' => 'C'],
                ['id' => 4 , 'variable_name'=> 'status',       'start' => 6, 'parameter_name' => 'status', 'format' => 'number', 'lenght' => 1, 'type' => 'C'],
                ['id' => 5 , 'variable_name'=> 'crc',          'start' => 7, 'parameter_name' => null,     'format' => 'number', 'lenght' => 2, 'type' => 'v'],
            ],
            'uri_event' => \App\Models\V1\Api\EventLog::EVENT_SET_STATUS_COIL
        ],
        [
            'event_id' => 16,
            'frame' => [
                ['id' => 1 ,  'variable_name'=> 'event_id',     'start' => 0,  'parameter_name' => null, 'format' => 'number', 'lenght' => 1, 'type' => 'C'],
                ['id' => 2 ,  'variable_name'=> 'id_event_log', 'start' => 1,  'parameter_name' => null, 'format' => 'number', 'lenght' => 4, 'type' => 'V'],
                ['id' => 3 ,  'variable_name'=> 'timestamp',    'start' => 5,  'parameter_name' => null, 'format' => 'unix',   'lenght' => 4, 'type' => 'V'],
                ['id' => 4 ,  'variable_name'=> 'serial',       'start' => 9,  'parameter_name' => null, 'format' => 'number', 'lenght' => 4, 'type' => 'V'],
                ['id' => 5 ,  'variable_name'=> 'salida_id',    'start' => 13, 'parameter_name' => null, 'format' => 'number', 'lenght' => 1, 'type' => 'C'],
                ['id' => 6 ,  'variable_name'=> 'status',       'start' => 14, 'parameter_name' => null, 'format' => 'number', 'lenght' => 1, 'type' => 'C'],
                ['id' => 7 ,  'variable_name'=> 'import_kwh',   'start' => 15, 'parameter_name' => null, 'format' => 'number',   'lenght' => 4, 'type' => 'f'],
                ['id' => 8 ,  'variable_name'=> 'import_kvarh', 'start' => 19, 'parameter_name' => null, 'format' => 'number',   'lenght' => 4, 'type' => 'f'],
                ['id' => 9 ,  'variable_name'=> 'export_kwh',   'start' => 23, 'parameter_name' => null, 'format' => 'number',   'lenght' => 4, 'type' => 'f'],
                ['id' => 10 , 'variable_name'=> 'export_kvarh', 'start' => 27, 'parameter_name' => null, 'format' => 'number',   'lenght' => 4, 'type' => 'f'],
                ['id' => 11 , 'variable_name'=> 'crc',          'start' => 31, 'parameter_name' => null, 'format' => 'number', 'lenght' => 2, 'type' => 'v'],
            ]
        ],
        [
            'event_id' => 17,
            'frame' => [
                ['id' => 1 , 'variable_name'=> 'event_id',     'start' => 0, 'parameter_name' => null, 'format' => 'number', 'lenght' => 1, 'type' => 'C'],
                ['id' => 2 , 'variable_name'=> 'id_event_log', 'start' => 1, 'parameter_name' => null, 'format' => 'number', 'lenght' => 4, 'type' => 'V'],
                ['id' => 3 , 'variable_name'=> 'salida_id',    'start' => 5, 'parameter_name' => null, 'format' => 'number', 'lenght' => 1, 'type' => 'C'],
                ['id' => 5 , 'variable_name'=> 'crc',          'start' => 6, 'parameter_name' => null, 'format' => 'number', 'lenght' => 2, 'type' => 'v'],
            ],
            'uri_event' => \App\Models\V1\Api\EventLog::EVENT_GET_STATUS_COIL
        ],
        [
            'event_id' => 18,
            'frame' => [
                ['id' => 1 ,  'variable_name'=> 'event_id',     'start' => 0,  'parameter_name' => null, 'format' => 'number', 'lenght' => 1, 'type' => 'C'],
                ['id' => 2 ,  'variable_name'=> 'id_event_log', 'start' => 1,  'parameter_name' => null, 'format' => 'number', 'lenght' => 4, 'type' => 'V'],
                ['id' => 3 ,  'variable_name'=> 'timestamp',    'start' => 5,  'parameter_name' => null, 'format' => 'unix',   'lenght' => 4, 'type' => 'V'],
                ['id' => 4 ,  'variable_name'=> 'serial',       'start' => 9,  'parameter_name' => null, 'format' => 'number', 'lenght' => 4, 'type' => 'V'],
                ['id' => 5 ,  'variable_name'=> 'salida_id',    'start' => 13, 'parameter_name' => null, 'format' => 'number', 'lenght' => 1, 'type' => 'C'],
                ['id' => 6 ,  'variable_name'=> 'status',       'start' => 14, 'parameter_name' => null, 'format' => 'number', 'lenght' => 1, 'type' => 'C'],
                ['id' => 7 ,  'variable_name'=> 'import_kwh',   'start' => 15, 'parameter_name' => null, 'format' => 'number',   'lenght' => 4, 'type' => 'f'],
                ['id' => 8 ,  'variable_name'=> 'import_kvarh', 'start' => 19, 'parameter_name' => null, 'format' => 'number',   'lenght' => 4, 'type' => 'f'],
                ['id' => 9 ,  'variable_name'=> 'export_kwh',   'start' => 23, 'parameter_name' => null, 'format' => 'number',   'lenght' => 4, 'type' => 'f'],
                ['id' => 10 , 'variable_name'=> 'export_kvarh', 'start' => 27, 'parameter_name' => null, 'format' => 'number',   'lenght' => 4, 'type' => 'f'],
                ['id' => 11 , 'variable_name'=> 'crc',          'start' => 31, 'parameter_name' => null, 'format' => 'number', 'lenght' => 2, 'type' => 'v'],
            ]
        ],
        [
            'event_id' => 19,
            'frame' => [
                ['id' => 1 ,  'variable_name'=> 'event_id',     'start' => 0,  'parameter_name' => null, 'format' => 'number', 'lenght' => 1, 'type' => 'C'],
                ['id' => 2 ,  'variable_name'=> 'timestamp',    'start' => 1,  'parameter_name' => null, 'format' => 'unix',   'lenght' => 4, 'type' => 'V'],
                ['id' => 3 ,  'variable_name'=> 'serial',       'start' => 5,  'parameter_name' => null, 'format' => 'number', 'lenght' => 4, 'type' => 'V'],
                ['id' => 4 ,  'variable_name'=> 'salida_id',    'start' => 9, 'parameter_name' => null, 'format' => 'number', 'lenght' => 1, 'type' => 'C'],
                ['id' => 5 ,  'variable_name'=> 'status',       'start' => 10, 'parameter_name' => null, 'format' => 'number', 'lenght' => 1, 'type' => 'C'],
                ['id' => 6 ,  'variable_name'=> 'import_kwh',   'start' => 11, 'parameter_name' => null, 'format' => 'unix',   'lenght' => 4, 'type' => 'f'],
                ['id' => 7 ,  'variable_name'=> 'import_kvarh', 'start' => 15, 'parameter_name' => null, 'format' => 'unix',   'lenght' => 4, 'type' => 'f'],
                ['id' => 8 ,  'variable_name'=> 'export_kwh',   'start' => 19, 'parameter_name' => null, 'format' => 'unix',   'lenght' => 4, 'type' => 'f'],
                ['id' => 9 , 'variable_name'=> 'export_kvarh', 'start' => 23, 'parameter_name' => null, 'format' => 'unix',   'lenght' => 4, 'type' => 'f'],
                ['id' => 10 , 'variable_name'=> 'crc',          'start' => 27, 'parameter_name' => null, 'format' => 'number', 'lenght' => 2, 'type' => 'v'],
            ],
            'uri_event' => \App\Models\V1\Api\EventLog::EVENT_CHANGE_STATE_SUPPLY_IN_APLICATION

        ],
        [
            'event_id' => 20,
            'frame' => [
                ['id' => 1 , 'variable_name'=> 'event_id',     'start' => 0, 'parameter_name' => null, 'format' => 'number', 'lenght' => 1, 'type' => 'C'],
                ['id' => 2 , 'variable_name'=> 'id_event_log', 'start' => 1, 'parameter_name' => null, 'format' => 'number', 'lenght' => 4, 'type' => 'V'],
                ['id' => 3 , 'variable_name'=> 'type',         'start' => 5, 'parameter_name' => 'type', 'format' => 'number', 'lenght' => 1, 'type' => 'C'],
                ['id' => 4 , 'variable_name'=> 'crc',          'start' => 6, 'parameter_name' => null, 'format' => 'number', 'lenght' => 2, 'type' => 'v'],
            ],
            'uri_event' => \App\Models\V1\Api\EventLog::EVENT_SET_CONFIG_SENSOR
        ],
        [
            'event_id' => 21,
            'frame' => [
                ['id' => 1 , 'variable_name'=> 'event_id',     'start' => 0,  'parameter_name' => null, 'format' => 'number', 'lenght' => 1, 'type' => 'C'],
                ['id' => 2 , 'variable_name'=> 'id_event_log', 'start' => 1,  'parameter_name' => null, 'format' => 'number', 'lenght' => 4, 'type' => 'V'],
                ['id' => 3 , 'variable_name'=> 'timestamp',    'start' => 5,  'parameter_name' => null, 'format' => 'unix',   'lenght' => 4, 'type' => 'V'],
                ['id' => 4 , 'variable_name'=> 'serial',       'start' => 9,  'parameter_name' => null, 'format' => 'number', 'lenght' => 4, 'type' => 'V'],
                ['id' => 5 , 'variable_name'=> 'type',         'start' => 13, 'parameter_name' => null, 'format' => 'number', 'lenght' => 1, 'type' => 'C'],
                ['id' => 6 , 'variable_name'=> 'crc',          'start' => 14, 'parameter_name' => null, 'format' => 'number', 'lenght' => 2, 'type' => 'v'],
            ]
        ],
        [
            'event_id' => 22,
            'frame' => [
                ['id' => 1 , 'variable_name'=> 'event_id',     'start' => 0, 'parameter_name' => null, 'format' => 'number', 'lenght' => 1, 'type' => 'C'],
                ['id' => 2 , 'variable_name'=> 'id_event_log', 'start' => 1, 'parameter_name' => null, 'format' => 'number', 'lenght' => 4, 'type' => 'V'],
                ['id' => 3 , 'variable_name'=> 'crc',          'start' => 5, 'parameter_name' => null, 'format' => 'number', 'lenght' => 2, 'type' => 'v'],
            ],
            'uri_event' => \App\Models\V1\Api\EventLog::EVENT_GET_CONFIG_SENSOR
        ],
        [
            'event_id' => 23,
            'frame' => [
                ['id' => 1 , 'variable_name'=> 'event_id',     'start' => 0,  'parameter_name' => null, 'format' => 'number', 'lenght' => 1, 'type' => 'C'],
                ['id' => 2 , 'variable_name'=> 'id_event_log', 'start' => 1,  'parameter_name' => null, 'format' => 'number', 'lenght' => 4, 'type' => 'V'],
                ['id' => 3 , 'variable_name'=> 'timestamp',    'start' => 5,  'parameter_name' => null, 'format' => 'unix',   'lenght' => 4, 'type' => 'V'],
                ['id' => 4 , 'variable_name'=> 'serial',       'start' => 9,  'parameter_name' => null, 'format' => 'number', 'lenght' => 4, 'type' => 'V'],
                ['id' => 5 , 'variable_name'=> 'type',         'start' => 13, 'parameter_name' => null, 'format' => 'number', 'lenght' => 1, 'type' => 'C'],
                ['id' => 6 , 'variable_name'=> 'crc',          'start' => 14, 'parameter_name' => null, 'format' => 'number', 'lenght' => 2, 'type' => 'v'],
            ]
        ],
        [
            'event_id' => 24,
            'frame' => [
                ['id' => 1 , 'variable_name'=> 'event_id',     'start' => 0, 'parameter_name' => null, 'format' => 'number', 'lenght' => 1, 'type' => 'C'],
                ['id' => 2 , 'variable_name'=> 'id_event_log', 'start' => 1, 'parameter_name' => null, 'format' => 'number', 'lenght' => 4, 'type' => 'V'],
                ['id' => 3 , 'variable_name'=> 'crc',          'start' => 5, 'parameter_name' => null, 'format' => 'number', 'lenght' => 2, 'type' => 'v'],
            ],
            'uri_event' => \App\Models\V1\Api\EventLog::EVENT_GET_STATUS_SENSOR
        ],
        [
            'event_id' => 25,
            'frame' => [
                ['id' => 1 , 'variable_name'=> 'event_id',     'start' => 0,  'parameter_name' => null, 'format' => 'number', 'lenght' => 1, 'type' => 'C'],
                ['id' => 2 , 'variable_name'=> 'id_event_log', 'start' => 1,  'parameter_name' => null, 'format' => 'number', 'lenght' => 4, 'type' => 'V'],
                ['id' => 3 , 'variable_name'=> 'timestamp',    'start' => 5,  'parameter_name' => null, 'format' => 'unix',   'lenght' => 4, 'type' => 'V'],
                ['id' => 4 , 'variable_name'=> 'serial',       'start' => 9,  'parameter_name' => null, 'format' => 'number', 'lenght' => 4, 'type' => 'V'],
                ['id' => 5 , 'variable_name'=> 'status',       'start' => 13, 'parameter_name' => null, 'format' => 'number', 'lenght' => 1, 'type' => 'C'],
                ['id' => 6 , 'variable_name'=> 'crc',          'start' => 14, 'parameter_name' => null, 'format' => 'number', 'lenght' => 2, 'type' => 'v'],
            ]
        ],
        [
            'event_id' => 26,
            'frame' => [
                ['id' => 1 , 'variable_name'=> 'event_id',     'start' => 0, 'parameter_name' => null, 'format' => 'number', 'lenght' => 1, 'type' => 'C'],
                ['id' => 2 , 'variable_name'=> 'id_event_log', 'start' => 1, 'parameter_name' => null, 'format' => 'number', 'lenght' => 4, 'type' => 'V'],
                ['id' => 3 , 'variable_name'=> 'crc',          'start' => 5, 'parameter_name' => null, 'format' => 'number', 'lenght' => 2, 'type' => 'v'],
            ],
            'uri_event' => \App\Models\V1\Api\EventLog::EVENT_GET_STATUS_CONNECTION
        ],
        [
            'event_id' => 27,
            'frame' => [
                ['id' => 1 , 'variable_name'=> 'event_id',     'start' => 0,  'parameter_name' => null, 'format' => 'number', 'lenght' => 1, 'type' => 'C'],
                ['id' => 2 , 'variable_name'=> 'id_event_log', 'start' => 1,  'parameter_name' => null, 'format' => 'number', 'lenght' => 4, 'type' => 'V'],
                ['id' => 3 , 'variable_name'=> 'timestamp',    'start' => 5,  'parameter_name' => null, 'format' => 'unix',   'lenght' => 4, 'type' => 'V'],
                ['id' => 4 , 'variable_name'=> 'serial',       'start' => 9,  'parameter_name' => null, 'format' => 'number', 'lenght' => 4, 'type' => 'V'],
                ['id' => 5 , 'variable_name'=> 'status',       'start' => 13, 'parameter_name' => null, 'format' => 'number', 'lenght' => 1, 'type' => 'C'],
                ['id' => 6 , 'variable_name'=> 'crc',          'start' => 14, 'parameter_name' => null, 'format' => 'number', 'lenght' => 2, 'type' => 'v'],
            ]
        ],
        [
            'event_id' => 28,
            'frame' => [
                ['id' => 1 , 'variable_name'=> 'event_id',     'start' => 0,  'parameter_name' => null, 'format' => 'number', 'lenght' => 1, 'type' => 'C'],
                ['id' => 2 , 'variable_name'=> 'timestamp',    'start' => 1,  'parameter_name' => null, 'format' => 'unix',   'lenght' => 4, 'type' => 'V'],
                ['id' => 3 , 'variable_name'=> 'serial',       'start' => 5,  'parameter_name' => null, 'format' => 'number', 'lenght' => 4, 'type' => 'V'],
                ['id' => 4 , 'variable_name'=> 'crc',          'start' => 9, 'parameter_name' => null, 'format' => 'number', 'lenght' => 2, 'type' => 'v'],
            ],
            'job_name' => 'SendReactiveDataMcJob',
            'uri_event' => \App\Models\V1\Api\EventLog::SET_REACTIVE_DATA
        ],
        [
            'event_id' => 29,
            'frame' => [
                ['id' => 1, 'variable_name'=> 'event_id',         'start' => 0,   'parameter_name' => null, 'format' => 'number', 'lenght' => 1, 'type' => 'C'],
                ['id' => 2, 'variable_name'=> 'l1_import_kvarLh', 'start' => 1,   'parameter_name' => null, 'format' => 'number',   'lenght' => 4, 'type' => 'f'],
                ['id' => 3, 'variable_name'=> 'l2_import_kvarLh', 'start' => 5,   'parameter_name' => null, 'format' => 'number', 'lenght' => 4, 'type' => 'f'],
                ['id' => 4, 'variable_name'=> 'l3_import_kvarLh', 'start' => 9,  'parameter_name' => null, 'format' => 'number', 'lenght' => 4, 'type' => 'f'],
                ['id' => 5, 'variable_name'=> 'l1_import_kvarCh', 'start' => 13,  'parameter_name' => null, 'format' => 'number',   'lenght' => 4, 'type' => 'f'],
                ['id' => 6, 'variable_name'=> 'l2_import_kvarCh', 'start' => 17,  'parameter_name' => null, 'format' => 'number', 'lenght' => 4, 'type' => 'f'],
                ['id' => 7, 'variable_name'=> 'l3_import_kvarCh', 'start' => 21,  'parameter_name' => null, 'format' => 'number', 'lenght' => 4, 'type' => 'f'],
                ['id' => 8, 'variable_name'=> 'crc',              'start' => 25,  'parameter_name' => null, 'format' => 'number', 'lenght' => 2, 'type' => 'v'],
            ]
        ],
        [
            'event_id' => 30,
            'frame' => [
                ['id' => 1 , 'variable_name'=> 'event_id',     'start' => 0, 'parameter_name' => null, 'format' => 'number', 'lenght' => 1, 'type' => 'C'],
                ['id' => 2 , 'variable_name'=> 'id_event_log', 'start' => 1, 'parameter_name' => null, 'format' => 'number', 'lenght' => 4, 'type' => 'V'],
                ['id' => 3 , 'variable_name'=> 'crc',          'start' => 5, 'parameter_name' => null, 'format' => 'number', 'lenght' => 2, 'type' => 'v'],
            ],
            'uri_event' => \App\Models\V1\Api\EventLog::EVENT_GET_CURRENT_READINGS
        ],
        [
            'event_id' => 31,
            'frame' => [
                ['id' => 1 , 'variable_name'=> 'event_id',     'start' => 0,  'parameter_name' => null, 'format' => 'number', 'lenght' => 1, 'type' => 'C'],
                ['id' => 2 , 'variable_name'=> 'id_event_log',    'start' => 1,  'parameter_name' => null, 'format' => 'unix',   'lenght' => 4, 'type' => 'V'],
                ['id' => 3 , 'variable_name'=> 'network_operator_id',       'start' => 5,  'parameter_name' => null, 'format' => 'number', 'lenght' => 8, 'type' => 'P'],
                ['id' => 4 , 'variable_name'=> 'serial',       'start' => 13,  'parameter_name' => null, 'format' => 'number', 'lenght' => 8, 'type' => 'P'],
                ['id' => 6 , 'variable_name'=> 'latitud',       'start' => 21,  'parameter_name' => null, 'format' => 'number', 'lenght' => 4, 'type' => 'f'],
                ['id' => 7 , 'variable_name'=> 'longitud',       'start' => 25,  'parameter_name' => null, 'format' => 'number', 'lenght' => 4, 'type' => 'f'],
                ['id' => 8 , 'variable_name'=> 'flags',       'start' => 29,  'parameter_name' => null, 'format' => 'number', 'lenght' => 8, 'type' => 'P'],
                ['id' => 9 ,  'variable_name'=> 'timestamp',           'start' => 37,  'parameter_name' => null, 'format' => 'number', 'lenght' => 4, 'type' => 'V'],
                ['id' => 10 , 'variable_name'=> 'ph1_volt',          'start' => 41,  'parameter_name' => null, 'format' => 'number', 'lenght' => 4, 'type' => 'f'],
                ['id' => 11 , 'variable_name'=> 'ph2_volt',           'start' => 45,  'parameter_name' => null, 'format' => 'number', 'lenght' => 4, 'type' => 'f'],
                ['id' => 12 , 'variable_name'=> 'ph3_volt',           'start' => 49,  'parameter_name' => null, 'format' => 'number', 'lenght' => 4, 'type' => 'f'],
                ['id' => 13 , 'variable_name'=> 'ph1_current',        'start' => 53,  'parameter_name' => null, 'format' => 'number', 'lenght' => 4, 'type' => 'f'],
                ['id' => 14 , 'variable_name'=> 'ph2_current',        'start' => 57,  'parameter_name' => null, 'format' => 'number', 'lenght' => 4, 'type' => 'f'],
                ['id' => 15 , 'variable_name'=> 'ph3_current',        'start' => 61,  'parameter_name' => null, 'format' => 'number', 'lenght' => 4, 'type' => 'f'],
                ['id' => 16 , 'variable_name'=> 'ph1_power',          'start' => 65,  'parameter_name' => null, 'format' => 'number', 'lenght' => 4, 'type' => 'f'],
                ['id' => 17 , 'variable_name'=> 'ph2_power',         'start' => 69,  'parameter_name' => null, 'format' => 'number', 'lenght' => 4, 'type' => 'f'],
                ['id' => 18 , 'variable_name'=> 'ph3_power',         'start' => 73,  'parameter_name' => null, 'format' => 'number', 'lenght' => 4, 'type' => 'f'],
                ['id' => 19 , 'variable_name'=> 'ph1_VA',             'start' => 77,  'parameter_name' => null, 'format' => 'number', 'lenght' => 4, 'type' => 'f'],
                ['id' => 20 , 'variable_name'=> 'ph2_VA',             'start' => 81,  'parameter_name' => null, 'format' => 'number', 'lenght' => 4, 'type' => 'f'],
                ['id' => 21 , 'variable_name'=> 'ph3_VA',             'start' => 85,  'parameter_name' => null, 'format' => 'number', 'lenght' => 4, 'type' => 'f'],
                ['id' => 22 , 'variable_name'=> 'ph1_VAr',              'start' => 89,  'parameter_name' => null, 'format' => 'number', 'lenght' => 4, 'type' => 'f'],
                ['id' => 23 , 'variable_name'=> 'ph2_VAr',            'start' => 93,  'parameter_name' => null, 'format' => 'number', 'lenght' => 4, 'type' => 'f'],
                ['id' => 24 , 'variable_name'=> 'ph3_VAr',             'start' => 97,  'parameter_name' => null, 'format' => 'number', 'lenght' => 4, 'type' => 'f'],
                ['id' => 25 , 'variable_name'=> 'ph1_power_factor',       'start' => 101,  'parameter_name' => null, 'format' => 'number', 'lenght' => 4, 'type' => 'f'],
                ['id' => 25 , 'variable_name'=> 'ph2_power_factor',       'start' => 105,  'parameter_name' => null, 'format' => 'number', 'lenght' => 4, 'type' => 'f'],
                ['id' => 27 , 'variable_name'=> 'ph3_power_factor',       'start' => 109,  'parameter_name' => null, 'format' => 'number', 'lenght' => 4, 'type' => 'f'],
                ['id' => 28 , 'variable_name'=> 'total_power_factor', 'start' => 113,  'parameter_name' => null, 'format' => 'number', 'lenght' => 4, 'type' => 'f'],
                ['id' => 29 , 'variable_name'=> 'ph1_phase_angle',       'start' => 117,  'parameter_name' => null, 'format' => 'number', 'lenght' => 4, 'type' => 'f'],
                ['id' => 30 , 'variable_name'=> 'ph2_phase_angle',       'start' => 121,  'parameter_name' => null, 'format' => 'number', 'lenght' => 4, 'type' => 'f'],
                ['id' => 31 , 'variable_name'=> 'ph3_phase_angle',       'start' => 125,  'parameter_name' => null, 'format' => 'number', 'lenght' => 4, 'type' => 'f'],
                ['id' => 32 , 'variable_name'=> 'total_phase_angle',       'start' => 129,  'parameter_name' => null, 'format' => 'number', 'lenght' => 4, 'type' => 'f'],
                ['id' => 33 , 'variable_name'=> 'total_system_power',       'start' => 133,  'parameter_name' => null, 'format' => 'number', 'lenght' => 4, 'type' => 'f'],
                ['id' => 33 , 'variable_name'=> 'total_system_var',       'start' => 137,  'parameter_name' => null, 'format' => 'number', 'lenght' => 4, 'type' => 'f'],
                ['id' => 34 , 'variable_name'=> 'frecuency',              'start' => 141,  'parameter_name' => null, 'format' => 'number', 'lenght' => 4, 'type' => 'f'],
                ['id' => 35 , 'variable_name'=> 'import_wh',            'start' => 145,  'parameter_name' => null, 'format' => 'number', 'lenght' => 4, 'type' => 'f'],
                ['id' => 36 , 'variable_name'=> 'export_wh',            'start' => 149,  'parameter_name' => null, 'format' => 'number', 'lenght' => 4, 'type' => 'f'],
                ['id' => 37 , 'variable_name'=> 'importVArh',           'start' => 153,  'parameter_name' => null, 'format' => 'number', 'lenght' => 4, 'type' => 'f'],
                ['id' => 38 , 'variable_name'=> 'exportVArh',            'start' => 157,  'parameter_name' => null, 'format' => 'number', 'lenght' => 4, 'type' => 'f'],
                ['id' => 39 , 'variable_name'=> 'ph1_ph2_volt',           'start' => 161,  'parameter_name' => null, 'format' => 'number', 'lenght' => 4, 'type' => 'f'],
                ['id' => 40 , 'variable_name'=> 'ph2_ph3_volt',           'start' => 165,  'parameter_name' => null, 'format' => 'number', 'lenght' => 4, 'type' => 'f'],
                ['id' => 41 , 'variable_name'=> 'ph3_ph1_volt',        'start' => 169,  'parameter_name' => null, 'format' => 'number', 'lenght' => 4, 'type' => 'f'],
                ['id' => 41 , 'variable_name'=> 'ph1_volt_thd',        'start' => 173,  'parameter_name' => null, 'format' => 'number', 'lenght' => 4, 'type' => 'f'],
                ['id' => 43 , 'variable_name'=> 'ph2_volt_thd',        'start' => 177,  'parameter_name' => null, 'format' => 'number', 'lenght' => 4, 'type' => 'f'],
                ['id' => 44 , 'variable_name'=> 'ph3_volt_thd',        'start' => 181,  'parameter_name' => null, 'format' => 'number', 'lenght' => 4, 'type' => 'f'],
                ['id' => 45 , 'variable_name'=> 'ph1_current_thd',       'start' => 185,  'parameter_name' => null, 'format' => 'number', 'lenght' => 4, 'type' => 'f'],
                ['id' => 46 , 'variable_name'=> 'ph2_current_thd',       'start' => 189,  'parameter_name' => null, 'format' => 'number', 'lenght' => 4, 'type' => 'f'],
                ['id' => 47 , 'variable_name'=> 'ph3_current_thd',       'start' => 193,  'parameter_name' => null, 'format' => 'number', 'lenght' => 4, 'type' => 'f'],
                ['id' => 48 , 'variable_name'=> 'ph1_ph2_volt_thd',       'start' => 197,  'parameter_name' => null, 'format' => 'number', 'lenght' => 4, 'type' => 'f'],
                ['id' => 49 , 'variable_name'=> 'ph2_ph3_volt_thd',       'start' => 201,  'parameter_name' => null, 'format' => 'number', 'lenght' => 4, 'type' => 'f'],
                ['id' => 50 , 'variable_name'=> 'ph3_ph1_volt_thd',       'start' => 205,  'parameter_name' => null, 'format' => 'number', 'lenght' => 4, 'type' => 'f'],
                ['id' => 51 , 'variable_name'=> 'ph1_import_kwh',       'start' => 209,  'parameter_name' => null, 'format' => 'number', 'lenght' => 4, 'type' => 'f'],
                ['id' => 52 , 'variable_name'=> 'ph2_import_kwh',       'start' => 213,  'parameter_name' => null, 'format' => 'number', 'lenght' => 4, 'type' => 'f'],
                ['id' => 53 , 'variable_name'=> 'ph3_import_kwh',       'start' => 217,  'parameter_name' => null, 'format' => 'number', 'lenght' => 4, 'type' => 'f'],
                ['id' => 54 , 'variable_name'=> 'ph1_import_kvarh',       'start' => 221,  'parameter_name' => null, 'format' => 'number', 'lenght' => 4, 'type' => 'f'],
                ['id' => 55 , 'variable_name'=> 'ph2_import_kvarh',       'start' => 225,  'parameter_name' => null, 'format' => 'number', 'lenght' => 4, 'type' => 'f'],
                ['id' => 56 , 'variable_name'=> 'ph3_import_kvarh',       'start' => 229,  'parameter_name' => null, 'format' => 'number', 'lenght' => 4, 'type' => 'f'],
                ['id' => 58 , 'variable_name'=> 'ph1_varLh_acumm',       'start' => 233,  'parameter_name' => null, 'format' => 'number', 'lenght' => 4, 'type' => 'f'],
                ['id' => 60 , 'variable_name'=> 'ph2_varLh_acumm',       'start' => 237,  'parameter_name' => null, 'format' => 'number', 'lenght' => 4, 'type' => 'f'],
                ['id' => 62 , 'variable_name'=> 'ph3_varLh_acumm',       'start' => 241,  'parameter_name' => null, 'format' => 'number', 'lenght' => 4, 'type' => 'f'],
                ['id' => 57 , 'variable_name'=> 'ph1_varCh_acumm',       'start' => 245,  'parameter_name' => null, 'format' => 'number', 'lenght' => 4, 'type' => 'f'],
                ['id' => 59 , 'variable_name'=> 'ph2_varCh_acumm',       'start' => 249,  'parameter_name' => null, 'format' => 'number', 'lenght' => 4, 'type' => 'f'],
                ['id' => 61 , 'variable_name'=> 'ph3_varCh_acumm',       'start' => 253,  'parameter_name' => null, 'format' => 'number', 'lenght' => 4, 'type' => 'f'],
                ['id' => 63 , 'variable_name'=> 'volt_dc',              'start' => 257,  'parameter_name' => null, 'format' => 'number', 'lenght' => 4, 'type' => 'f'],
                ['id' => 64 , 'variable_name'=> 'crc',                'start' => 261, 'parameter_name' => null, 'format' => 'number', 'lenght' => 2, 'type' => 'v'],
            ]
        ],
        [
            'event_id' => 32,
            'frame' => [
                ['id' => 1 , 'variable_name'=> 'event_id',     'start' => 0,  'parameter_name' => null, 'format' => 'number', 'lenght' => 1, 'type' => 'C'],
                ['id' => 2 , 'variable_name'=> 'timestamp',    'start' => 1,  'parameter_name' => null, 'format' => 'unix',   'lenght' => 4, 'type' => 'V'],
                ['id' => 3 , 'variable_name'=> 'serial',       'start' => 5,  'parameter_name' => null, 'format' => 'number', 'lenght' => 4, 'type' => 'V'],
                ['id' => 4 , 'variable_name'=> 'crc',          'start' => 9, 'parameter_name' => null, 'format' => 'number', 'lenght' => 2, 'type' => 'v'],
            ],
            'uri_event' => \App\Models\V1\Api\EventLog::EVENT_INITIAL_CONNECTION

        ],
        [
            'event_id' => 33,
            'frame' => [
                ['id' => 1 , 'variable_name'=> 'event_id', 'start' => 0, 'parameter_name' => null, 'format' => 'number', 'lenght' => 1, 'type' => 'C'],
                ['id' => 2 , 'variable_name'=> 'serial',   'start' => 1, 'parameter_name' => null, 'format' => 'number', 'lenght' => 4, 'type' => 'V'],
                ['id' => 3 , 'variable_name'=> 'crc',      'start' => 5, 'parameter_name' => null, 'format' => 'number', 'lenght' => 2, 'type' => 'v'],
            ],
            'uri_event' => \App\Models\V1\Api\EventLog::EVENT_LOST_CONNECTION
        ],
        [
            'event_id' => 34,
            'frame' => [
                ['id' => 1 ,  'variable_name'=> 'event_id',     'start' => 0,  'parameter_name' => null, 'format' => 'number', 'lenght' => 1, 'type' => 'C'],
                ['id' => 2 ,  'variable_name'=> 'id_event_log',    'start' => 1,  'parameter_name' => null, 'format' => 'unix',   'lenght' => 4, 'type' => 'V'],
                ['id' => 3 ,  'variable_name'=> 'status',       'start' => 5,  'parameter_name' => 'status', 'format' => 'number', 'lenght' => 1, 'type' => 'C'],
                ['id' => 8 , 'variable_name'=> 'crc',          'start' => 6, 'parameter_name' => null, 'format' => 'number', 'lenght' => 2, 'type' => 'v'],
            ],
            'uri_event' => \App\Models\V1\Api\EventLog::EVENT_ON_OFF_REAL_TIME
        ],
        [
            'event_id' => 35,
            'frame' => [
                ['id' => 1 ,  'variable_name'=> 'event_id',     'start' => 0,  'parameter_name' => null, 'format' => 'number', 'lenght' => 1, 'type' => 'C'],
                ['id' => 2 ,  'variable_name'=> 'timestamp',    'start' => 1,  'parameter_name' => null, 'format' => 'unix',   'lenght' => 4, 'type' => 'V'],
                ['id' => 3 ,  'variable_name'=> 'serial',       'start' => 5,  'parameter_name' => null, 'format' => 'number', 'lenght' => 4, 'type' => 'V'],
                ['id' => 3 ,  'variable_name'=> 'status',       'start' => 9,  'parameter_name' => null, 'format' => 'number', 'lenght' => 1, 'type' => 'C'],
                ['id' => 4 ,  'variable_name'=> 'import_kwh',   'start' => 10, 'parameter_name' => null, 'format' => 'number',   'lenght' => 4, 'type' => 'f'],
                ['id' => 5 ,  'variable_name'=> 'import_kvarh', 'start' => 14, 'parameter_name' => null, 'format' => 'number',   'lenght' => 4, 'type' => 'f'],
                ['id' => 6 ,  'variable_name'=> 'export_kwh',   'start' => 18, 'parameter_name' => null, 'format' => 'number',   'lenght' => 4, 'type' => 'f'],
                ['id' => 7, 'variable_name'=> 'export_kvarh', 'start' => 22, 'parameter_name' => null, 'format' => 'number',   'lenght' => 4, 'type' => 'f'],
                ['id' => 8 , 'variable_name'=> 'crc',          'start' => 26, 'parameter_name' => null, 'format' => 'number', 'lenght' => 2, 'type' => 'v'],
            ],
            'uri_event' => \App\Models\V1\Api\EventLog::EVENT_CHANGE_STATE_DOOR

        ],
        [
            'event_id' => 36,
            'frame' => [
                ['id' => 1 ,  'variable_name'=> 'event_id',     'start' => 0,  'parameter_name' => null, 'format' => 'number', 'lenght' => 1, 'type' => 'C'],
                ['id' => 2 ,  'variable_name'=> 'timestamp',    'start' => 1,  'parameter_name' => null, 'format' => 'unix',   'lenght' => 4, 'type' => 'V'],
                ['id' => 3 ,  'variable_name'=> 'serial',       'start' => 5,  'parameter_name' => null, 'format' => 'number', 'lenght' => 4, 'type' => 'V'],
                ['id' => 4 ,  'variable_name'=> 'salida_id',    'start' => 9, 'parameter_name' => null, 'format' => 'number', 'lenght' => 1, 'type' => 'C'],
                ['id' => 5 ,  'variable_name'=> 'status',       'start' => 10, 'parameter_name' => null, 'format' => 'number', 'lenght' => 1, 'type' => 'C'],
                ['id' => 6 ,  'variable_name'=> 'import_kwh',   'start' => 11, 'parameter_name' => null, 'format' => 'unix',   'lenght' => 4, 'type' => 'f'],
                ['id' => 7 ,  'variable_name'=> 'import_kvarh', 'start' => 15, 'parameter_name' => null, 'format' => 'unix',   'lenght' => 4, 'type' => 'f'],
                ['id' => 8 ,  'variable_name'=> 'export_kwh',   'start' => 19, 'parameter_name' => null, 'format' => 'unix',   'lenght' => 4, 'type' => 'f'],
                ['id' => 9 , 'variable_name'=> 'export_kvarh', 'start' => 23, 'parameter_name' => null, 'format' => 'unix',   'lenght' => 4, 'type' => 'f'],
                ['id' => 10 , 'variable_name'=> 'crc',          'start' => 27, 'parameter_name' => null, 'format' => 'number', 'lenght' => 2, 'type' => 'v'],
            ],
            'uri_event' => \App\Models\V1\Api\EventLog::EVENT_SUPPLY_INTERRUPTION_TO_MANIPULATION
        ],
        [
            'event_id' => 37,
            'frame' => [
                ['id' => 1 ,  'variable_name'=> 'event_id',     'start' => 0,  'parameter_name' => null, 'format' => 'number', 'lenght' => 1, 'type' => 'C'],
                ['id' => 2 ,  'variable_name'=> 'id_event_log',    'start' => 1,  'parameter_name' => null, 'format' => 'unix',   'lenght' => 4, 'type' => 'V'],
                ['id' => 3 ,  'variable_name'=> 'timestamp',    'start' => 5,  'parameter_name' => null, 'format' => 'unix',   'lenght' => 4, 'type' => 'V'],
                ['id' => 4 ,  'variable_name'=> 'serial',       'start' => 9,  'parameter_name' => null, 'format' => 'number', 'lenght' => 4, 'type' => 'V'],
                ['id' => 5 ,  'variable_name'=> 'salida_id',    'start' => 13, 'parameter_name' => null, 'format' => 'number', 'lenght' => 1, 'type' => 'C'],
                ['id' => 6 ,  'variable_name'=> 'status',       'start' => 14, 'parameter_name' => null, 'format' => 'number', 'lenght' => 1, 'type' => 'C'],
                ['id' => 7 ,  'variable_name'=> 'import_kwh',   'start' => 15, 'parameter_name' => null, 'format' => 'unix',   'lenght' => 4, 'type' => 'f'],
                ['id' => 8 ,  'variable_name'=> 'import_kvarh', 'start' => 19, 'parameter_name' => null, 'format' => 'unix',   'lenght' => 4, 'type' => 'f'],
                ['id' => 9 ,  'variable_name'=> 'export_kwh',   'start' => 23, 'parameter_name' => null, 'format' => 'unix',   'lenght' => 4, 'type' => 'f'],
                ['id' => 10 , 'variable_name'=> 'export_kvarh', 'start' => 27, 'parameter_name' => null, 'format' => 'unix',   'lenght' => 4, 'type' => 'f'],
                ['id' => 11 , 'variable_name'=> 'crc',          'start' => 29, 'parameter_name' => null, 'format' => 'number', 'lenght' => 2, 'type' => 'v'],
            ]
        ],
        [
            'event_id' => 38,
            'frame' => [
                ['id' => 1 ,  'variable_name'=> 'event_id',     'start' => 0,  'parameter_name' => null, 'format' => 'number', 'lenght' => 1, 'type' => 'C'],
                ['id' => 2 ,  'variable_name'=> 'id_event_log',    'start' => 1,  'parameter_name' => null, 'format' => 'unix',   'lenght' => 4, 'type' => 'V'],
                ['id' => 3 ,  'variable_name'=> 'timestamp',    'start' => 5,  'parameter_name' => null, 'format' => 'unix',   'lenght' => 4, 'type' => 'V'],
                ['id' => 4 ,  'variable_name'=> 'serial',       'start' => 9,  'parameter_name' => null, 'format' => 'number', 'lenght' => 4, 'type' => 'V'],
                ['id' => 5 ,  'variable_name'=> 'salida_id',    'start' => 13, 'parameter_name' => null, 'format' => 'number', 'lenght' => 1, 'type' => 'C'],
                ['id' => 6 ,  'variable_name'=> 'status',       'start' => 14, 'parameter_name' => null, 'format' => 'number', 'lenght' => 1, 'type' => 'C'],
                ['id' => 7 ,  'variable_name'=> 'import_kwh',   'start' => 15, 'parameter_name' => null, 'format' => 'unix',   'lenght' => 4, 'type' => 'f'],
                ['id' => 8 ,  'variable_name'=> 'import_kvarh', 'start' => 19, 'parameter_name' => null, 'format' => 'unix',   'lenght' => 4, 'type' => 'f'],
                ['id' => 9 ,  'variable_name'=> 'export_kwh',   'start' => 23, 'parameter_name' => null, 'format' => 'unix',   'lenght' => 4, 'type' => 'f'],
                ['id' => 10 , 'variable_name'=> 'export_kvarh', 'start' => 27, 'parameter_name' => null, 'format' => 'unix',   'lenght' => 4, 'type' => 'f'],
                ['id' => 11 ,  'variable_name'=> 'max_volt',   'start' => 31, 'parameter_name' => null, 'format' => 'unix',   'lenght' => 4, 'type' => 'f'],
                ['id' => 12 ,  'variable_name'=> 'min_volt', 'start' => 35, 'parameter_name' => null, 'format' => 'unix',   'lenght' => 4, 'type' => 'f'],
                ['id' => 13 ,  'variable_name'=> 'ph1_volt',   'start' => 39, 'parameter_name' => null, 'format' => 'unix',   'lenght' => 4, 'type' => 'f'],
                ['id' => 14 , 'variable_name'=> 'ph2_volt', 'start' => 43, 'parameter_name' => null, 'format' => 'unix',   'lenght' => 4, 'type' => 'f'],
                ['id' => 15 , 'variable_name'=> 'ph3_volt', 'start' => 47, 'parameter_name' => null, 'format' => 'unix',   'lenght' => 4, 'type' => 'f'],
                ['id' => 16 , 'variable_name'=> 'crc',          'start' => 49, 'parameter_name' => null, 'format' => 'number', 'lenght' => 2, 'type' => 'v'],
            ]
        ],
        [
            'event_id' => 39,
            'frame' => [
                ['id' => 1 ,  'variable_name'=> 'event_id',     'start' => 0,  'parameter_name' => null, 'format' => 'number', 'lenght' => 1, 'type' => 'C'],
                ['id' => 2 ,  'variable_name'=> 'timestamp',    'start' => 1,  'parameter_name' => null, 'format' => 'unix',   'lenght' => 4, 'type' => 'V'],
                ['id' => 3 ,  'variable_name'=> 'serial',       'start' => 5,  'parameter_name' => null, 'format' => 'number', 'lenght' => 4, 'type' => 'V'],
                ['id' => 4 ,  'variable_name'=> 'salida_id',    'start' => 9, 'parameter_name' => null, 'format' => 'number', 'lenght' => 1, 'type' => 'C'],
                ['id' => 5 ,  'variable_name'=> 'status',       'start' => 10, 'parameter_name' => null, 'format' => 'number', 'lenght' => 1, 'type' => 'C'],
                ['id' => 6 ,  'variable_name'=> 'import_kwh',   'start' => 11, 'parameter_name' => null, 'format' => 'unix',   'lenght' => 4, 'type' => 'f'],
                ['id' => 7 ,  'variable_name'=> 'import_kvarh', 'start' => 15, 'parameter_name' => null, 'format' => 'unix',   'lenght' => 4, 'type' => 'f'],
                ['id' => 8 ,  'variable_name'=> 'export_kwh',   'start' => 19, 'parameter_name' => null, 'format' => 'unix',   'lenght' => 4, 'type' => 'f'],
                ['id' => 9 , 'variable_name'=> 'export_kvarh', 'start' => 23, 'parameter_name' => null, 'format' => 'unix',   'lenght' => 4, 'type' => 'f'],
                ['id' => 10 ,  'variable_name'=> 'max_volt',   'start' => 27, 'parameter_name' => null, 'format' => 'unix',   'lenght' => 4, 'type' => 'f'],
                ['id' => 11 ,  'variable_name'=> 'min_volt', 'start' => 31, 'parameter_name' => null, 'format' => 'unix',   'lenght' => 4, 'type' => 'f'],
                ['id' => 12 ,  'variable_name'=> 'ph1_volt',   'start' => 35, 'parameter_name' => null, 'format' => 'unix',   'lenght' => 4, 'type' => 'f'],
                ['id' => 13 , 'variable_name'=> 'ph2_volt', 'start' => 39, 'parameter_name' => null, 'format' => 'unix',   'lenght' => 4, 'type' => 'f'],
                ['id' => 14 , 'variable_name'=> 'ph3_volt', 'start' => 43, 'parameter_name' => null, 'format' => 'unix',   'lenght' => 4, 'type' => 'f'],
                ['id' => 15 , 'variable_name'=> 'crc',          'start' => 45, 'parameter_name' => null, 'format' => 'number', 'lenght' => 2, 'type' => 'v'],
            ],
            'uri_event' => \App\Models\V1\Api\EventLog::EVENT_CHANGE_STATE_SUPPLY_TO_VOLTAGE
        ],
        [
            'event_id' => 40,
            'frame' => [
                ['id' => 1 , 'variable_name'=> 'event_id',     'start' => 0,  'parameter_name' => null, 'format' => 'number', 'lenght' => 1, 'type' => 'C'],
                ['id' => 2 , 'variable_name'=> 'timestamp',    'start' => 1,  'parameter_name' => null, 'format' => 'unix',   'lenght' => 4, 'type' => 'V'],
                ['id' => 3 , 'variable_name'=> 'serial',       'start' => 5,  'parameter_name' => null, 'format' => 'number', 'lenght' => 4, 'type' => 'V'],
                ['id' => 4 , 'variable_name'=> 'crc',          'start' => 9, 'parameter_name' => null, 'format' => 'number', 'lenght' => 2, 'type' => 'v'],
            ],
            'uri_event' => \App\Models\V1\Api\EventLog::EVENT_METER_READING_FAILURE
        ],
        [
            'event_id' => 41,
            'frame' => [
                ['id' => 1 , 'variable_name'=> 'event_id',     'start' => 0,  'parameter_name' => null, 'format' => 'number', 'lenght' => 1, 'type' => 'C'],
                ['id' => 2 , 'variable_name'=> 'id_event_log', 'start' => 1,  'parameter_name' => null, 'format' => 'number', 'lenght' => 4, 'type' => 'V'],
                ['id' => 3 , 'variable_name'=> 'timestamp',    'start' => 5,  'parameter_name' => null, 'format' => 'unix',   'lenght' => 4, 'type' => 'V'],
                ['id' => 4 , 'variable_name'=> 'serial',       'start' => 9,  'parameter_name' => null, 'format' => 'number', 'lenght' => 4, 'type' => 'V'],
                ['id' => 5 , 'variable_name'=> 'status',         'start' => 13, 'parameter_name' => null, 'format' => 'number', 'lenght' => 1, 'type' => 'C'],
                ['id' => 6 , 'variable_name'=> 'crc',          'start' => 14, 'parameter_name' => null, 'format' => 'number', 'lenght' => 2, 'type' => 'v'],
            ]
        ],
        [
            'event_id' => 42,
            'frame' => [
                ['id' => 1 , 'variable_name'=> 'event_id',     'start' => 0,  'parameter_name' => null, 'format' => 'number', 'lenght' => 1, 'type' => 'C'],
                ['id' => 2 , 'variable_name'=> 'id_event_log', 'start' => 1,  'parameter_name' => null, 'format' => 'number', 'lenght' => 4, 'type' => 'V'],
                ['id' => 3 , 'variable_name'=> 'size_file',    'start' => 5,  'parameter_name' => null, 'format' => 'number',   'lenght' => 4, 'type' => 'V'],
                ['id' => 3 , 'variable_name'=> 'version',    'start' => 9,  'parameter_name' => 'version', 'format' => 'number',   'lenght' => 4, 'type' => 'f'],
                ['id' => 6 , 'variable_name'=> 'crc',          'start' => 13, 'parameter_name' => null, 'format' => 'number', 'lenght' => 2, 'type' => 'v'],
            ],
            'uri_event' => \App\Models\V1\Api\EventLog::EVENT_OTA_UPDATE

        ],
        [
            'event_id' => 43,
            'frame' => [
                ['id' => 1 , 'variable_name'=> 'event_id',     'start' => 0,  'parameter_name' => null, 'format' => 'number', 'lenght' => 1, 'type' => 'C'],
                ['id' => 2 , 'variable_name'=> 'id_event_log', 'start' => 1,  'parameter_name' => null, 'format' => 'number', 'lenght' => 4, 'type' => 'V'],
                ['id' => 2 , 'variable_name'=> 'timestamp', 'start' => 5,  'parameter_name' => null, 'format' => 'number', 'lenght' => 4, 'type' => 'V'],
                ['id' => 4 , 'variable_name'=> 'serial',       'start' => 9,  'parameter_name' => null, 'format' => 'number', 'lenght' => 4, 'type' => 'V'],
                ['id' => 5 , 'variable_name'=> 'status',         'start' => 13, 'parameter_name' => null, 'format' => 'number', 'lenght' => 1, 'type' => 'C'],
                ['id' => 6 , 'variable_name'=> 'crc',          'start' => 14, 'parameter_name' => null, 'format' => 'number', 'lenght' => 2, 'type' => 'v'],
            ],
            'job_name' => 'FirmwareUpdateJob'
        ],
        [
            'event_id' => 44
            ,
            'frame' => [
                ['id' => 1 , 'variable_name'=> 'event_id',     'start' => 0,  'parameter_name' => null, 'format' => 'number', 'lenght' => 1, 'type' => 'C'],
                ['id' => 2 , 'variable_name'=> 'id_event_log', 'start' => 1,  'parameter_name' => null, 'format' => 'number', 'lenght' => 4, 'type' => 'V'],
                ['id' => 3 , 'variable_name'=> 'timestamp', 'start' => 5,  'parameter_name' => null, 'format' => 'number', 'lenght' => 4, 'type' => 'V'],
                ['id' => 4 , 'variable_name'=> 'serial',       'start' => 9,  'parameter_name' => null, 'format' => 'number', 'lenght' => 4, 'type' => 'V'],
                ['id' => 5 , 'variable_name'=> 'status',         'start' => 13, 'parameter_name' => null, 'format' => 'number', 'lenght' => 1, 'type' => 'C'],
                ['id' => 6 , 'variable_name'=> 'crc',          'start' => 14, 'parameter_name' => null, 'format' => 'number', 'lenght' => 2, 'type' => 'v'],
            ]
        ],
        [
            'event_id' => 45,
            'frame' => [
                ['id' => 1, 'variable_name'=> 'event_id',     'start' => 0,  'parameter_name' => null, 'format' => 'number', 'lenght' => 1, 'type' => 'C'],
                ['id' => 2, 'variable_name'=> 'id_event_log', 'start' => 1,  'parameter_name' => null, 'format' => 'number', 'lenght' => 4, 'type' => 'V'],
                ['id' => 3, 'variable_name'=> 'frame_control', 'start' => 5,  'parameter_name' => null, 'format' => 'unix',   'lenght' => 4, 'type' => 'V'],
                ['id' => 4, 'variable_name'=> 'crc',          'start' => 9, 'parameter_name' => null, 'format' => 'number', 'lenght' => 2, 'type' => 'v'],
            ],
            'uri_event' => \App\Models\V1\Api\EventLog::EVENT_SET_CONTROL_LIMITS
        ],
        [
            'event_id' => 46,
            'frame' => [
                ['id' => 1, 'variable_name'=> 'event_id',     'start' => 0,  'parameter_name' => null, 'format' => 'number', 'lenght' => 1, 'type' => 'C'],
                ['id' => 2, 'variable_name'=> 'id_event_log', 'start' => 1,  'parameter_name' => null, 'format' => 'number', 'lenght' => 4, 'type' => 'V'],
                ['id' => 3, 'variable_name'=> 'timestamp', 'start' => 5,  'parameter_name' => null, 'format' => 'unix',   'lenght' => 4, 'type' => 'V'],
                ['id' => 4, 'variable_name'=> 'serial', 'start' => 9,  'parameter_name' => null, 'format' => 'unix',   'lenght' => 4, 'type' => 'V'],
                ['id' => 5, 'variable_name'=> 'crc',          'start' => 13, 'parameter_name' => null, 'format' => 'number', 'lenght' => 2, 'type' => 'v'],
            ],
           'job_name' => 'SaveAlertControlConfigurations'
        ],
        [
            'event_id' => 47,
            'frame' => [
                ['id' => 1, 'variable_name'=> 'event_id',     'start' => 0,  'parameter_name' => null, 'format' => 'number', 'lenght' => 1, 'type' => 'C'],
                ['id' => 2, 'variable_name'=> 'id_event_log', 'start' => 1,  'parameter_name' => null, 'format' => 'number', 'lenght' => 4, 'type' => 'V'],
                ['id' => 3, 'variable_name'=> 'status_service_coil', 'start' => 5,  'parameter_name' => 'status_service_coil', 'format' => 'number',   'lenght' => 1, 'type' => 'C'],
                ['id' => 4, 'variable_name'=> 'crc',          'start' => 6, 'parameter_name' => null, 'format' => 'number', 'lenght' => 2, 'type' => 'v'],
            ],
            'uri_event' => \App\Models\V1\Api\EventLog::EVENT_SET_SERVICE_COIL
        ],
        [
            'event_id' => 48,
            'frame' => [
            ['id' => 1 ,  'variable_name'=> 'event_id',     'start' => 0,  'parameter_name' => null, 'format' => 'number', 'lenght' => 1, 'type' => 'C'],
            ['id' => 2 ,  'variable_name'=> 'id_event_log',    'start' => 1,  'parameter_name' => null, 'format' => 'unix',   'lenght' => 4, 'type' => 'V'],
            ['id' => 3 ,  'variable_name'=> 'timestamp',    'start' => 5,  'parameter_name' => null, 'format' => 'unix',   'lenght' => 4, 'type' => 'V'],
            ['id' => 4 ,  'variable_name'=> 'serial',       'start' => 9,  'parameter_name' => null, 'format' => 'number', 'lenght' => 4, 'type' => 'V'],
            ['id' => 5 ,  'variable_name'=> 'status_service_coil',       'start' => 13, 'parameter_name' => null, 'format' => 'number', 'lenght' => 1, 'type' => 'C'],
            ['id' => 6 ,  'variable_name'=> 'import_kwh',   'start' => 14, 'parameter_name' => null, 'format' => 'unix',   'lenght' => 4, 'type' => 'f'],
            ['id' => 7 ,  'variable_name'=> 'import_kvarh', 'start' => 18, 'parameter_name' => null, 'format' => 'unix',   'lenght' => 4, 'type' => 'f'],
            ['id' => 8 ,  'variable_name'=> 'export_kwh',   'start' => 22, 'parameter_name' => null, 'format' => 'unix',   'lenght' => 4, 'type' => 'f'],
            ['id' => 9 , 'variable_name'=> 'export_kvarh', 'start' => 26, 'parameter_name' => null, 'format' => 'unix',   'lenght' => 4, 'type' => 'f'],
            ['id' => 10 , 'variable_name'=> 'crc',          'start' => 30, 'parameter_name' => null, 'format' => 'number', 'lenght' => 2, 'type' => 'v'],
        ],
            //'job_name' => 'SaveStatusControlConfigurations'
        ],
        [
            'event_id' => 49,
            'frame' => [
            ['id' => 1 ,  'variable_name'=> 'event_id',     'start' => 0,  'parameter_name' => null, 'format' => 'number', 'lenght' => 1, 'type' => 'C'],
            ['id' => 2 ,  'variable_name'=> 'timestamp',    'start' => 1,  'parameter_name' => null, 'format' => 'unix',   'lenght' => 4, 'type' => 'V'],
            ['id' => 3 ,  'variable_name'=> 'serial',       'start' => 5,  'parameter_name' => null, 'format' => 'number', 'lenght' => 4, 'type' => 'V'],
            ['id' => 4 ,  'variable_name'=> 'status_service_coil',       'start' => 9, 'parameter_name' => null, 'format' => 'number', 'lenght' => 1, 'type' => 'C'],
            ['id' => 5 ,  'variable_name'=> 'import_kwh',   'start' => 10, 'parameter_name' => null, 'format' => 'unix',   'lenght' => 4, 'type' => 'f'],
            ['id' => 6 ,  'variable_name'=> 'import_kvarh', 'start' => 14, 'parameter_name' => null, 'format' => 'unix',   'lenght' => 4, 'type' => 'f'],
            ['id' => 7 ,  'variable_name'=> 'export_kwh',   'start' => 18, 'parameter_name' => null, 'format' => 'unix',   'lenght' => 4, 'type' => 'f'],
            ['id' => 8 , 'variable_name'=> 'export_kvarh', 'start' => 22, 'parameter_name' => null, 'format' => 'unix',   'lenght' => 4, 'type' => 'f'],
            ['id' => 9 , 'variable_name'=> 'crc',          'start' => 26, 'parameter_name' => null, 'format' => 'number', 'lenght' => 2, 'type' => 'v'],
        ],
            'uri_event' => \App\Models\V1\Api\EventLog::EVENT_CHANGE_STATE_SERVICE_COIL_IN_APLICATION
        ],
        [
            'event_id' => 50,
            'frame' => [
                ['id' => 1 , 'variable_name'=> 'event_id',        'start' => 0, 'parameter_name' => null,       'format' => 'number', 'lenght' => 1, 'type' => 'C'],
                ['id' => 2 , 'variable_name'=> 'id_event_log',    'start' => 1, 'parameter_name' => null,       'format' => 'number', 'lenght' => 4, 'type' => 'V'],
                ['id' => 3 , 'variable_name'=> 'lenght_password', 'start' => 5, 'parameter_name' => null,       'format' => 'lenght', 'lenght' => 1, 'type' => 'C'],
                ['id' => 4 , 'variable_name'=> 'password',        'start' => 6, 'parameter_name' => 'password', 'format' => 'string', 'lenght' => 1, 'type' => 'C'],
                ['id' => 5 , 'variable_name'=> 'crc',             'start' => 7, 'parameter_name' => null,       'format' => 'number', 'lenght' => 2, 'type' => 'v'],
            ],
            'uri_event' => \App\Models\V1\Api\EventLog::EVENT_SET_PASSWORD_METER_APP
        ],
        [
            'event_id' => 51,
            'frame' => [
                ['id' => 1 , 'variable_name'=> 'event_id',     'start' => 0,  'parameter_name' => null, 'format' => 'number', 'lenght' => 1, 'type' => 'C'],
                ['id' => 2 , 'variable_name'=> 'id_event_log',    'start' => 1, 'parameter_name' => null,       'format' => 'number', 'lenght' => 4, 'type' => 'V'],
                ['id' => 3 , 'variable_name'=> 'timestamp',    'start' => 5,  'parameter_name' => null, 'format' => 'unix',   'lenght' => 4, 'type' => 'V'],
                ['id' => 4 , 'variable_name'=> 'serial',       'start' => 9,  'parameter_name' => null, 'format' => 'number', 'lenght' => 4, 'type' => 'V'],
                ['id' => 5 , 'variable_name'=> 'lenght_password', 'start' => 13, 'parameter_name' => null,       'format' => 'lenght', 'lenght' => 1, 'type' => 'C'],
                ['id' => 6 , 'variable_name'=> 'password',        'start' => 14, 'parameter_name' => 'password', 'format' => 'string', 'lenght' => 4, 'type' => 'C'],
                ['id' => 7 , 'variable_name'=> 'crc',             'start' => 18, 'parameter_name' => null,       'format' => 'number', 'lenght' => 2, 'type' => 'v'],
            ]
        ],
        [
            'event_id' => 52,
            'frame' => [
                ['id' => 1 , 'variable_name'=> 'event_id',     'start' => 0,  'parameter_name' => null, 'format' => 'number', 'lenght' => 1, 'type' => 'C'],
                ['id' => 2 , 'variable_name'=> 'timestamp',    'start' => 1,  'parameter_name' => null, 'format' => 'unix',   'lenght' => 4, 'type' => 'V'],
                ['id' => 3 , 'variable_name'=> 'serial',       'start' => 5,  'parameter_name' => null, 'format' => 'number', 'lenght' => 4, 'type' => 'V'],
                ['id' => 4 , 'variable_name'=> 'lenght_password', 'start' => 9, 'parameter_name' => null,       'format' => 'lenght', 'lenght' => 1, 'type' => 'C'],
                ['id' => 5 , 'variable_name'=> 'password',        'start' => 10, 'parameter_name' => 'password', 'format' => 'string', 'lenght' => 1, 'type' => 'C'],
                ['id' => 6 , 'variable_name'=> 'crc',             'start' => 11, 'parameter_name' => null,       'format' => 'number', 'lenght' => 2, 'type' => 'v'],
            ],
            'uri_event' => \App\Models\V1\Api\EventLog::EVENT_CHANGE_PASSWORD_IN_APLICATION,
        ],
        [
            'event_id' => 53,
            'frame' => [
                ['id' => 1 , 'variable_name'=> 'event_id',        'start' => 0, 'parameter_name' => null,       'format' => 'number', 'lenght' => 1, 'type' => 'C'],
                ['id' => 2 , 'variable_name'=> 'id_event_log',    'start' => 1, 'parameter_name' => null,       'format' => 'number', 'lenght' => 4, 'type' => 'V'],
                ['id' => 3 , 'variable_name'=> 'crc',             'start' => 5, 'parameter_name' => null,       'format' => 'number', 'lenght' => 2, 'type' => 'v'],
            ],
            'uri_event' => \App\Models\V1\Api\EventLog::EVENT_GET_PASSWORD_METER
        ],
        [
            'event_id' => 54,
            'frame' => [
                ['id' => 1 , 'variable_name'=> 'event_id',     'start' => 0,  'parameter_name' => null, 'format' => 'number', 'lenght' => 1, 'type' => 'C'],
                ['id' => 2 , 'variable_name'=> 'id_event_log',    'start' => 1, 'parameter_name' => null,       'format' => 'number', 'lenght' => 4, 'type' => 'V'],
                ['id' => 3 , 'variable_name'=> 'timestamp',    'start' => 5,  'parameter_name' => null, 'format' => 'unix',   'lenght' => 4, 'type' => 'V'],
                ['id' => 4 , 'variable_name'=> 'serial',       'start' => 9,  'parameter_name' => null, 'format' => 'number', 'lenght' => 4, 'type' => 'V'],
                ['id' => 5 , 'variable_name'=> 'lenght_password', 'start' => 13, 'parameter_name' => null,       'format' => 'lenght', 'lenght' => 1, 'type' => 'C'],
                ['id' => 6 , 'variable_name'=> 'password',        'start' => 14, 'parameter_name' => 'password', 'format' => 'string', 'lenght' => 4, 'type' => 'C'],
                ['id' => 7 , 'variable_name'=> 'crc',             'start' => 18, 'parameter_name' => null,       'format' => 'number', 'lenght' => 2, 'type' => 'v'],
            ]
        ],
        [
            'event_id' => 55,
            'frame' => [
                ['id' => 1, 'variable_name'=> 'event_id',     'start' => 0,  'parameter_name' => null, 'format' => 'number', 'lenght' => 1, 'type' => 'C'],
                ['id' => 2, 'variable_name'=> 'id_event_log', 'start' => 1,  'parameter_name' => null, 'format' => 'number', 'lenght' => 4, 'type' => 'V'],
                ['id' => 3, 'variable_name'=> 'billing_day', 'start' => 5,  'parameter_name' => null, 'format' => 'number',   'lenght' => 1, 'type' => 'C'],
                ['id' => 4, 'variable_name'=> 'crc',          'start' => 6, 'parameter_name' => null, 'format' => 'number', 'lenght' => 2, 'type' => 'v'],
            ],
            'uri_event' => \App\Models\V1\Api\EventLog::EVENT_SET_BILLING_DAY
        ],
        [
            'event_id' => 56,
            'frame' => [
                ['id' => 1, 'variable_name'=> 'event_id',     'start' => 0,  'parameter_name' => null, 'format' => 'number', 'lenght' => 1, 'type' => 'C'],
                ['id' => 2, 'variable_name'=> 'id_event_log', 'start' => 1,  'parameter_name' => null, 'format' => 'number', 'lenght' => 4, 'type' => 'V'],
                ['id' => 3, 'variable_name'=> 'timestamp', 'start' => 5,  'parameter_name' => null, 'format' => 'unix',   'lenght' => 4, 'type' => 'V'],
                ['id' => 4, 'variable_name'=> 'serial', 'start' => 9,  'parameter_name' => null, 'format' => 'unix',   'lenght' => 4, 'type' => 'V'],
                ['id' => 5, 'variable_name'=> 'crc',          'start' => 13, 'parameter_name' => null, 'format' => 'number', 'lenght' => 2, 'type' => 'v'],
            ],
            //'job_name' => 'SaveStatusControlConfigurations'
        ],
        [
            'event_id' => 57,
            'frame' => [
                ['id' => 1, 'variable_name'=> 'event_id',     'start' => 0,  'parameter_name' => null, 'format' => 'number', 'lenght' => 1, 'type' => 'C'],
                ['id' => 2, 'variable_name'=> 'id_event_log', 'start' => 1,  'parameter_name' => null, 'format' => 'number', 'lenght' => 4, 'type' => 'V'],
                ['id' => 3, 'variable_name'=> 'frame_status', 'start' => 5,  'parameter_name' => null, 'format' => 'unix',   'lenght' => 4, 'type' => 'V'],
                ['id' => 4, 'variable_name'=> 'crc',          'start' => 9, 'parameter_name' => null, 'format' => 'number', 'lenght' => 2, 'type' => 'v'],
            ],
            'uri_event' => \App\Models\V1\Api\EventLog::EVENT_SET_STATUS_CONTROL_LIMITS
        ],
        [
            'event_id' => 58,
            'frame' => [
                ['id' => 1, 'variable_name'=> 'event_id',     'start' => 0,  'parameter_name' => null, 'format' => 'number', 'lenght' => 1, 'type' => 'C'],
                ['id' => 2, 'variable_name'=> 'id_event_log', 'start' => 1,  'parameter_name' => null, 'format' => 'number', 'lenght' => 4, 'type' => 'V'],
                ['id' => 3, 'variable_name'=> 'timestamp', 'start' => 5,  'parameter_name' => null, 'format' => 'unix',   'lenght' => 4, 'type' => 'V'],
                ['id' => 4, 'variable_name'=> 'serial', 'start' => 9,  'parameter_name' => null, 'format' => 'unix',   'lenght' => 4, 'type' => 'V'],
                ['id' => 5, 'variable_name'=> 'crc',          'start' => 13, 'parameter_name' => null, 'format' => 'number', 'lenght' => 2, 'type' => 'v'],
            ],
            'job_name' => 'SaveStatusControlConfigurations'
        ],
    ],
    'webhook_events' =>[
        [
            'notification_type_id' => 10,
            'event_id' => 2,
            'json' => [
                ['id' => 1 , 'variable_name'=> 'notification_type_id', 'value' => 10,                                                          'parameter_name' => null,           'object' => []],
                ['id' => 2 , 'variable_name'=> 'message',              'value' => 'Se realizó la configuración de rangos admisibles para alertar dispositivo', 'parameter_name' => null,           'object' => []],
                ['id' => 3 , 'variable_name'=> 'success',              'value' => 1,                                                          'parameter_name' => null,           'object' => []],
                ['id' => 4 , 'variable_name'=> 'serial',               'value' => null,                                                       'parameter_name' => 'serial',       'object' => []],
                ['id' => 5 , 'variable_name'=> 'id_transaction',       'value' => null,                                                       'parameter_name' => null,           'object' => []],
                ['id' => 6 , 'variable_name'=> 'id_event',             'value' => null,                                                       'parameter_name' => null, 'object' => []],
                ['id' => 7 , 'variable_name'=> 'data',                 'value' => null,                                                       'parameter_name' => null,           'object' => [
                    ['variable_name'=> 'response_date', 'parameter_name' => 'timestamp', 'format' => 'date']
                ]
                ],
            ]
        ],
        [
            'notification_type_id' => 11,
            'event_id' => 4,
            'json' => [
                ['id' => 1 , 'variable_name'=> 'notification_type_id', 'value' => 11,                                                          'parameter_name' => null,           'object' => []],
                ['id' => 2 , 'variable_name'=> 'message',              'value' => 'Se realizó la configuración de tiempos para alertar dispositivo', 'parameter_name' => null,           'object' => []],
                ['id' => 3 , 'variable_name'=> 'success',              'value' => 1,                                                          'parameter_name' => null,           'object' => []],
                ['id' => 4 , 'variable_name'=> 'serial',               'value' => null,                                                       'parameter_name' => 'serial',       'object' => []],
                ['id' => 5 , 'variable_name'=> 'id_transaction',       'value' => null,                                                       'parameter_name' => null,           'object' => []],
                ['id' => 6 , 'variable_name'=> 'id_event',             'value' => null,                                                       'parameter_name' => null, 'object' => []],
                ['id' => 7 , 'variable_name'=> 'data',                 'value' => null,                                                       'parameter_name' => null,           'object' => [
                    ['variable_name'=> 'response_date', 'parameter_name' => 'timestamp', 'format' => 'date']
                ]
                ],
            ]
        ],
        [
            'notification_type_id' => 12,
            'event_id' => 6,
            'json' => [
                ['id' => 1 , 'variable_name'=> 'notification_type_id', 'value' => 12,                                                          'parameter_name' => null,           'object' => []],
                ['id' => 2 , 'variable_name'=> 'message',              'value' => 'Se configuró la latencia de muestreo del dispositivo', 'parameter_name' => null,           'object' => []],
                ['id' => 3 , 'variable_name'=> 'success',              'value' => 1,                                                          'parameter_name' => null,           'object' => []],
                ['id' => 4 , 'variable_name'=> 'serial',               'value' => null,                                                       'parameter_name' => 'serial',       'object' => []],
                ['id' => 5 , 'variable_name'=> 'id_transaction',       'value' => null,                                                       'parameter_name' => null,           'object' => []],
                ['id' => 6 , 'variable_name'=> 'id_event',             'value' => null,                                                       'parameter_name' => null, 'object' => []],
                ['id' => 7 , 'variable_name'=> 'data',                 'value' => null,                                                       'parameter_name' => null,           'object' => [
                    ['variable_name'=> 'response_date', 'parameter_name' => 'timestamp', 'format' => 'date']
                ]
                ],
            ]
        ],
        [
            'notification_type_id' => 13,
            'event_id' => 8,
            'json' => [
                ['id' => 1 , 'variable_name'=> 'notification_type_id', 'value' => 13,                                                          'parameter_name' => null,           'object' => []],
                ['id' => 2 , 'variable_name'=> 'message',              'value' => 'Se configuró ssid y password de red Wifi', 'parameter_name' => null,           'object' => []],
                ['id' => 3 , 'variable_name'=> 'success',              'value' => 1,                                                          'parameter_name' => null,           'object' => []],
                ['id' => 4 , 'variable_name'=> 'serial',               'value' => null,                                                       'parameter_name' => 'serial',       'object' => []],
                ['id' => 5 , 'variable_name'=> 'id_transaction',       'value' => null,                                                       'parameter_name' => null,           'object' => []],
                ['id' => 6 , 'variable_name'=> 'id_event',             'value' => null,                                                       'parameter_name' => null, 'object' => []],
                ['id' => 7 , 'variable_name'=> 'data',                 'value' => null,                                                       'parameter_name' => null,           'object' => [
                    ['variable_name'=> 'response_date', 'parameter_name' => 'timestamp', 'format' => 'date']
                ]
                ],
            ]
        ],
        [
            'notification_type_id' => 14,
            'event_id' => 10,
            'json' => [
                ['id' => 1 , 'variable_name'=> 'notification_type_id', 'value' => 14,                                                          'parameter_name' => null,           'object' => []],
                ['id' => 2 , 'variable_name'=> 'message',              'value' => 'Se configuraron credenciales para la conexión al broker en el dispositivo', 'parameter_name' => null,           'object' => []],
                ['id' => 3 , 'variable_name'=> 'success',              'value' => 1,                                                          'parameter_name' => null,           'object' => []],
                ['id' => 4 , 'variable_name'=> 'serial',               'value' => null,                                                       'parameter_name' => 'serial',       'object' => []],
                ['id' => 5 , 'variable_name'=> 'id_transaction',       'value' => null,                                                       'parameter_name' => null,           'object' => []],
                ['id' => 6 , 'variable_name'=> 'id_event',             'value' => null,                                                       'parameter_name' => null, 'object' => []],
                ['id' => 7 , 'variable_name'=> 'data',                 'value' => null,                                                       'parameter_name' => null,           'object' => [
                    ['variable_name'=> 'response_date', 'parameter_name' => 'timestamp', 'format' => 'date']
                ]
                ],
            ]
        ],
        [
            'notification_type_id' => 1,
            'event_id' => 12,
            'json' => [
                ['id' => 1 , 'variable_name'=> 'notification_type_id', 'value' => 1,                                                          'parameter_name' => null,           'object' => []],
                ['id' => 2 , 'variable_name'=> 'message',              'value' => 'Se realizó la configuración de la hora en el dispositivo', 'parameter_name' => null,           'object' => []],
                ['id' => 3 , 'variable_name'=> 'success',              'value' => 1,                                                          'parameter_name' => null,           'object' => []],
                ['id' => 4 , 'variable_name'=> 'serial',               'value' => null,                                                       'parameter_name' => 'serial',       'object' => []],
                ['id' => 5 , 'variable_name'=> 'id_transaction',       'value' => null,                                                       'parameter_name' => null,           'object' => []],
                ['id' => 6 , 'variable_name'=> 'id_event',             'value' => null,                                                       'parameter_name' => null, 'object' => []],
                ['id' => 7 , 'variable_name'=> 'data',                 'value' => null,                                                       'parameter_name' => null,           'object' => [
                    ['variable_name'=> 'timestamp', 'parameter_name' => 'timestamp', 'format' => 'date']
                ]
                ],
            ]
        ],
        [
            'notification_type_id' => 2,
            'event_id' => 14,
            'json' => [
                ['id' => 1 , 'variable_name'=> 'notification_type_id', 'value' => 2,                                                     'parameter_name' => null,           'object' => []],
                ['id' => 2 , 'variable_name'=> 'message',              'value' => 'Se realizó la consulta de la hora en el dispositivo', 'parameter_name' => null,           'object' => []],
                ['id' => 3 , 'variable_name'=> 'success',              'value' => 1,                                                     'parameter_name' => null,           'object' => []],
                ['id' => 4 , 'variable_name'=> 'serial',               'value' => null,                                                  'parameter_name' => 'serial',       'object' => []],
                ['id' => 5 , 'variable_name'=> 'id_transaction',       'value' => null,                                                  'parameter_name' => null,           'object' => []],
                ['id' => 6 , 'variable_name'=> 'id_event',             'value' => null,                                                  'parameter_name' => null, 'object' => []],
                ['id' => 7 , 'variable_name'=> 'data',                 'value' => null,                                                  'parameter_name' => null,           'object' => [
                    ['variable_name'=> 'timestamp', 'parameter_name' => 'timestamp', 'format' => 'date']
                ]
                ],
            ]
        ],
        [
            'notification_type_id' => 3,
            'event_id' => 16,
            'json' => [
                ['id' => 1 , 'variable_name'=> 'notification_type_id', 'value' => 3,                                          'parameter_name' => null,           'object' => []],
                ['id' => 2 , 'variable_name'=> 'message',              'value' => 'Se realizó el accionamiento de la bobina', 'parameter_name' => null,           'object' => []],
                ['id' => 3 , 'variable_name'=> 'success',              'value' => 1,                                          'parameter_name' => null,           'object' => []],
                ['id' => 4 , 'variable_name'=> 'serial',               'value' => null,                                       'parameter_name' => 'serial',       'object' => []],
                ['id' => 5 , 'variable_name'=> 'id_transaction',       'value' => null,                                       'parameter_name' => null,           'object' => []],
                ['id' => 6 , 'variable_name'=> 'id_event',             'value' => null,                                       'parameter_name' => null, 'object' => []],
                ['id' => 7 , 'variable_name'=> 'data',                 'value' => null,                                       'parameter_name' => null,           'object' => [
                    ['variable_name'=> 'response_date', 'parameter_name' => 'timestamp',    'format' => 'date'],
                    ['variable_name'=> 'status_coil',   'parameter_name' => 'status',       'format' => 'number'],
                    ['variable_name'=> 'import_kwh',    'parameter_name' => 'import_kwh',   'format' => 'number'],
                    ['variable_name'=> 'export_kwh',    'parameter_name' => 'export_kwh',   'format' => 'number'],
                    ['variable_name'=> 'import_kvarh',  'parameter_name' => 'import_kvarh', 'format' => 'number'],
                    ['variable_name'=> 'export_kvarh',  'parameter_name' => 'export_kvarh', 'format' => 'number'],
                ]
                ],
            ]
        ],
        [
            'notification_type_id' => 4,
            'event_id' => 18,
            'json' => [
                ['id' => 1 , 'variable_name'=> 'notification_type_id', 'value' => 4,                                             'parameter_name' => null,           'object' => []],
                ['id' => 2 , 'variable_name'=> 'message',              'value' => 'Se realizó la consulta del estado de la bobina', 'parameter_name' => null,           'object' => []],
                ['id' => 3 , 'variable_name'=> 'success',              'value' => 1,                                             'parameter_name' => null,           'object' => []],
                ['id' => 4 , 'variable_name'=> 'serial',               'value' => null,                                          'parameter_name' => 'serial',       'object' => []],
                ['id' => 5 , 'variable_name'=> 'id_transaction',       'value' => null,                                          'parameter_name' => null,           'object' => []],
                ['id' => 6 , 'variable_name'=> 'id_event',             'value' => null,                                          'parameter_name' => null, 'object' => []],
                ['id' => 7 , 'variable_name'=> 'data',                 'value' => null,                                          'parameter_name' => null,           'object' => [
                    ['variable_name'=> 'response_date', 'parameter_name' => 'timestamp',    'format' => 'date'],
                    ['variable_name'=> 'status_coil',   'parameter_name' => 'status',       'format' => 'number'],
                    ['variable_name'=> 'import_kwh',    'parameter_name' => 'import_kwh',   'format' => 'number'],
                    ['variable_name'=> 'export_kwh',    'parameter_name' => 'export_kwh',   'format' => 'number'],
                    ['variable_name'=> 'import_kvarh',  'parameter_name' => 'import_kvarh', 'format' => 'number'],
                    ['variable_name'=> 'export_kvarh',  'parameter_name' => 'export_kvarh', 'format' => 'number'],
                ]
                ],
            ]
        ],
        [
            'notification_type_id' => 15,
            'event_id' => 19,
            'json' => [
                ['id' => 1 , 'variable_name'=> 'notification_type_id', 'value' => 15,                                             'parameter_name' => null,           'object' => []],
                ['id' => 2 , 'variable_name'=> 'message',              'value' => 'Se realizo accionamiento del suministro electrico desde la aplicación', 'parameter_name' => null,           'object' => []],
                ['id' => 3 , 'variable_name'=> 'success',              'value' => 1,                                             'parameter_name' => null,           'object' => []],
                ['id' => 4 , 'variable_name'=> 'serial',               'value' => null,                                          'parameter_name' => 'serial',       'object' => []],
                ['id' => 5 , 'variable_name'=> 'id_transaction',       'value' => null,                                          'parameter_name' => null,           'object' => []],
                ['id' => 6 , 'variable_name'=> 'id_event',             'value' => null,                                          'parameter_name' => null, 'object' => []],
                ['id' => 7 , 'variable_name'=> 'data',                 'value' => null,                                          'parameter_name' => null,           'object' => [
                    ['variable_name'=> 'response_date', 'parameter_name' => 'timestamp',    'format' => 'date'],
                    ['variable_name'=> 'status_coil',   'parameter_name' => 'status',       'format' => 'number'],
                    ['variable_name'=> 'import_kwh',    'parameter_name' => 'import_kwh',   'format' => 'number'],
                    ['variable_name'=> 'export_kwh',    'parameter_name' => 'export_kwh',   'format' => 'number'],
                    ['variable_name'=> 'import_kvarh',  'parameter_name' => 'import_kvarh', 'format' => 'number'],
                    ['variable_name'=> 'export_kvarh',  'parameter_name' => 'export_kvarh', 'format' => 'number'],
                ]
                ],
            ]
        ],
        [
            'notification_type_id' => 5,
            'event_id' => 21,
            'json' => [
                ['id' => 1 , 'variable_name'=> 'notification_type_id', 'value' => 5,                                                    'parameter_name' => null,           'object' => []],
                ['id' => 2 , 'variable_name'=> 'message',              'value' => 'Se realizó la configuración del sensor de apertura', 'parameter_name' => null,           'object' => []],
                ['id' => 3 , 'variable_name'=> 'success',              'value' => 1,                                                    'parameter_name' => null,           'object' => []],
                ['id' => 4 , 'variable_name'=> 'serial',               'value' => null,                                                 'parameter_name' => 'serial',       'object' => []],
                ['id' => 5 , 'variable_name'=> 'id_transaction',       'value' => null,                                                 'parameter_name' => null,           'object' => []],
                ['id' => 6 , 'variable_name'=> 'id_event',             'value' => null,                                                 'parameter_name' => null, 'object' => []],
                ['id' => 7 , 'variable_name'=> 'data',                 'value' => null,                                                 'parameter_name' => null,           'object' => [
                    ['variable_name'=> 'response_date', 'parameter_name' => 'timestamp',    'format' => 'date'],
                    ['variable_name'=> 'status_sensor', 'parameter_name' => 'type',       'format' => 'number'],
                ]
                ],
            ]
        ],
        [
            'notification_type_id' => 6,
            'event_id' => 23,
            'json' => [
                ['id' => 1 , 'variable_name'=> 'notification_type_id', 'value' => 6,                                                                   'parameter_name' => null,           'object' => []],
                ['id' => 2 , 'variable_name'=> 'message',              'value' => 'Se realizó la consulta de la configuración del sensor de apertura', 'parameter_name' => null,           'object' => []],
                ['id' => 3 , 'variable_name'=> 'success',              'value' => 1,                                                                   'parameter_name' => null,           'object' => []],
                ['id' => 4 , 'variable_name'=> 'serial',               'value' => null,                                                                'parameter_name' => 'serial',       'object' => []],
                ['id' => 5 , 'variable_name'=> 'id_transaction',       'value' => null,                                                                'parameter_name' => null,           'object' => []],
                ['id' => 6 , 'variable_name'=> 'id_event',             'value' => null,                                                                'parameter_name' => null, 'object' => []],
                ['id' => 7 , 'variable_name'=> 'data',                 'value' => null,                                                                'parameter_name' => null,           'object' => [
                    ['variable_name'=> 'response_date', 'parameter_name' => 'timestamp', 'format' => 'date'],
                    ['variable_name'=> 'status_sensor', 'parameter_name' => 'type',      'format' => 'number'],
                ]
                ],
            ]
        ],
        [
            'notification_type_id' => 7,
            'event_id' => 25,
            'json' => [
                ['id' => 1 , 'variable_name'=> 'notification_type_id', 'value' => 7,                                                          'parameter_name' => null,           'object' => []],
                ['id' => 2 , 'variable_name'=> 'message',              'value' => 'Se realizó la consulta del estado del sensor de apertura', 'parameter_name' => null,           'object' => []],
                ['id' => 3 , 'variable_name'=> 'success',              'value' => 1,                                                          'parameter_name' => null,           'object' => []],
                ['id' => 4 , 'variable_name'=> 'serial',               'value' => null,                                                       'parameter_name' => 'serial',       'object' => []],
                ['id' => 5 , 'variable_name'=> 'id_transaction',       'value' => null,                                                       'parameter_name' => null,           'object' => []],
                ['id' => 6 , 'variable_name'=> 'id_event',             'value' => null,                                                       'parameter_name' => null, 'object' => []],
                ['id' => 7 , 'variable_name'=> 'data',                 'value' => null,                                                       'parameter_name' => null,           'object' => [
                    ['variable_name'=> 'response_date', 'parameter_name' => 'timestamp', 'format' => 'date'],
                    ['variable_name'=> 'status_door',   'parameter_name' => 'status',    'format' => 'number'],
                ]
                ],
            ]
        ],
        [
            'notification_type_id' => 8,
            'event_id' => 27,
            'json' => [
                ['id' => 1 , 'variable_name'=> 'notification_type_id', 'value' => 8,                                                               'parameter_name' => null,           'object' => []],
                ['id' => 2 , 'variable_name'=> 'message',              'value' => 'Se realizó la consulta del estado de conexión del dispositivo', 'parameter_name' => null,           'object' => []],
                ['id' => 3 , 'variable_name'=> 'success',              'value' => 1,                                                               'parameter_name' => null,           'object' => []],
                ['id' => 4 , 'variable_name'=> 'serial',               'value' => null,                                                            'parameter_name' => 'serial',       'object' => []],
                ['id' => 5 , 'variable_name'=> 'id_transaction',       'value' => null,                                                            'parameter_name' => null,           'object' => []],
                ['id' => 6 , 'variable_name'=> 'id_event',             'value' => null,                                                            'parameter_name' => null, 'object' => []],
                ['id' => 7 , 'variable_name'=> 'data',                 'value' => null,                                                            'parameter_name' => null,           'object' => [
                    ['variable_name'=> 'response_date',  'parameter_name' => 'timestamp', 'format' => 'date'],
                    ['variable_name'=> 'status_connect', 'parameter_name' => 'status',    'format' => 'number'],
                ]
                ],
            ]
        ],
        [
            'notification_type_id' => 16,
            'event_id' => 31,
            'json' => [
                ['id' => 1 , 'variable_name'=> 'notification_type_id', 'value' => 16,                                                               'parameter_name' => null,           'object' => []],
                ['id' => 2 , 'variable_name'=> 'message',              'value' => 'Se realizó la consulta de los registros del medidor', 'parameter_name' => null,           'object' => []],
                ['id' => 3 , 'variable_name'=> 'success',              'value' => 1,                                                               'parameter_name' => null,           'object' => []],
                ['id' => 4 , 'variable_name'=> 'serial',               'value' => null,                                                            'parameter_name' => 'serial',       'object' => []],
                ['id' => 5 , 'variable_name'=> 'id_transaction',       'value' => null,                                                            'parameter_name' => null,           'object' => []],
                ['id' => 6 , 'variable_name'=> 'id_event',             'value' => null,                                                            'parameter_name' => null, 'object' => []],
                ['id' => 7 , 'variable_name'=> 'data',                 'value' => null,                                                            'parameter_name' => null,           'object' => [
                    ['variable_name'=> 'response_date',  'parameter_name' => 'timestamp', 'format' => 'date'],
                    ['variable_name'=> 'ph1_volt', 'parameter_name' => 'ph1_volt',    'format' => 'number'],
                    ['variable_name'=> 'ph2_volt', 'parameter_name' => 'ph2_volt',    'format' => 'number'],
                    ['variable_name'=> 'ph3_volt', 'parameter_name' => 'ph3_volt',    'format' => 'number'],
                    ['variable_name'=> 'ph1_current', 'parameter_name' => 'ph1_current',    'format' => 'number'],
                    ['variable_name'=> 'ph2_current', 'parameter_name' => 'ph2_current',    'format' => 'number'],
                    ['variable_name'=> 'ph3_current', 'parameter_name' => 'ph3_current',    'format' => 'number'],
                    ['variable_name'=> 'ph1_power', 'parameter_name' => 'ph1_power',    'format' => 'number'],
                    ['variable_name'=> 'ph2_power', 'parameter_name' => 'ph2_power',    'format' => 'number'],
                    ['variable_name'=> 'ph3_power', 'parameter_name' => 'ph3_power',    'format' => 'number'],
                    ['variable_name'=> 'ph1_VA', 'parameter_name' => 'ph1_VA',    'format' => 'number'],
                    ['variable_name'=> 'ph2_VA', 'parameter_name' => 'ph2_VA',    'format' => 'number'],
                    ['variable_name'=> 'ph3_VA', 'parameter_name' => 'ph3_VA',    'format' => 'number'],
                    ['variable_name'=> 'ph1_VAr', 'parameter_name' => 'ph1_VAr',    'format' => 'number'],
                    ['variable_name'=> 'ph2_VAr', 'parameter_name' => 'ph2_VAr',    'format' => 'number'],
                    ['variable_name'=> 'ph3_VAr', 'parameter_name' => 'ph3_VAr',    'format' => 'number'],
                    ['variable_name'=> 'ph1_power_factor', 'parameter_name' => 'ph1_power_factor',    'format' => 'number'],
                    ['variable_name'=> 'ph2_power_factor', 'parameter_name' => 'ph2_power_factor',    'format' => 'number'],
                    ['variable_name'=> 'ph3_power_factor', 'parameter_name' => 'ph3_power_factor',    'format' => 'number'],
                    ['variable_name'=> 'total_power_factor', 'parameter_name' => 'total_power_factor',    'format' => 'number'],
                    ['variable_name'=> 'ph1_phase_angle', 'parameter_name' => 'ph1_phase_angle',    'format' => 'number'],
                    ['variable_name'=> 'ph2_phase_angle', 'parameter_name' => 'ph2_phase_angle',    'format' => 'number'],
                    ['variable_name'=> 'ph3_phase_angle', 'parameter_name' => 'ph3_phase_angle',    'format' => 'number'],
                    ['variable_name'=> 'total_phase_angle', 'parameter_name' => 'total_phase_angle',    'format' => 'number'],
                    ['variable_name'=> 'total_system_power', 'parameter_name' => 'total_system_power',    'format' => 'number'],
                    ['variable_name'=> 'total_system_var', 'parameter_name' => 'total_system_var',    'format' => 'number'],
                    ['variable_name'=> 'frecuency', 'parameter_name' => 'frecuency',    'format' => 'number'],
                    ['variable_name'=> 'import_wh', 'parameter_name' => 'import_wh',    'format' => 'number'],
                    ['variable_name'=> 'export_wh', 'parameter_name' => 'export_wh',    'format' => 'number'],
                    ['variable_name'=> 'importVArh', 'parameter_name' => 'importVArh',    'format' => 'number'],
                    ['variable_name'=> 'exportVArh', 'parameter_name' => 'exportVArh',    'format' => 'number'],
                    ['variable_name'=> 'ph1_ph2_volt', 'parameter_name' => 'ph1_ph2_volt',    'format' => 'number'],
                    ['variable_name'=> 'ph2_ph3_volt', 'parameter_name' => 'ph2_ph3_volt',    'format' => 'number'],
                    ['variable_name'=> 'ph3_ph1_volt', 'parameter_name' => 'ph3_ph1_volt',    'format' => 'number'],
                    ['variable_name'=> 'ph1_volt_thd', 'parameter_name' => 'ph1_volt_thd',    'format' => 'number'],
                    ['variable_name'=> 'ph2_volt_thd', 'parameter_name' => 'ph2_volt_thd',    'format' => 'number'],
                    ['variable_name'=> 'ph3_volt_thd', 'parameter_name' => 'ph3_volt_thd',    'format' => 'number'],
                    ['variable_name'=> 'ph1_current_thd', 'parameter_name' => 'ph1_current_thd',    'format' => 'number'],
                    ['variable_name'=> 'ph2_current_thd', 'parameter_name' => 'ph2_current_thd',    'format' => 'number'],
                    ['variable_name'=> 'ph3_current_thd', 'parameter_name' => 'ph3_current_thd',    'format' => 'number'],
                    ['variable_name'=> 'ph1_ph2_volt_thd', 'parameter_name' => 'ph1_ph2_volt_thd',    'format' => 'number'],
                    ['variable_name'=> 'ph2_ph3_volt_thd', 'parameter_name' => 'ph2_ph3_volt_thd',    'format' => 'number'],
                    ['variable_name'=> 'ph3_ph1_volt_thd', 'parameter_name' => 'ph3_ph1_volt_thd',    'format' => 'number'],
                    ['variable_name'=> 'ph1_import_kwh', 'parameter_name' => 'ph1_import_kwh',    'format' => 'number'],
                    ['variable_name'=> 'ph2_import_kwh', 'parameter_name' => 'ph2_import_kwh',    'format' => 'number'],
                    ['variable_name'=> 'ph3_import_kwh', 'parameter_name' => 'ph3_import_kwh',    'format' => 'number'],
                    ['variable_name'=> 'ph1_import_kvarh', 'parameter_name' => 'ph1_import_kvarh',    'format' => 'number'],
                    ['variable_name'=> 'ph2_import_kvarh', 'parameter_name' => 'ph2_import_kvarh',    'format' => 'number'],
                    ['variable_name'=> 'ph3_import_kvarh', 'parameter_name' => 'ph3_import_kvarh',    'format' => 'number'],
                    ['variable_name'=> 'ph1_varLh_acumm', 'parameter_name' => 'ph1_varLh_acumm',    'format' => 'number'],
                    ['variable_name'=> 'ph2_varLh_acumm', 'parameter_name' => 'ph2_varLh_acumm',    'format' => 'number'],
                    ['variable_name'=> 'ph3_varLh_acumm', 'parameter_name' => 'ph3_varLh_acumm',    'format' => 'number'],
                    ['variable_name'=> 'ph1_varCh_acumm', 'parameter_name' => 'ph1_varCh_acumm',    'format' => 'number'],
                    ['variable_name'=> 'ph2_varCh_acumm', 'parameter_name' => 'ph2_varCh_acumm',    'format' => 'number'],
                    ['variable_name'=> 'ph3_varCh_acumm', 'parameter_name' => 'ph3_varCh_acumm',    'format' => 'number'],
                    ['variable_name'=> 'volt_dc', 'parameter_name' => 'volt_dc',    'format' => 'number'],
                ]
                ],
            ]
        ],
        [
            'notification_type_id' => 17,
            'event_id' => 32,
            'json' => [
                ['id' => 1 , 'variable_name'=> 'notification_type_id', 'value' => 17,                                                               'parameter_name' => null,           'object' => []],
                ['id' => 2 , 'variable_name'=> 'message',              'value' => 'Se presenta conexión del equipo', 'parameter_name' => null,           'object' => []],
                ['id' => 3 , 'variable_name'=> 'success',              'value' => 1,                                                               'parameter_name' => null,           'object' => []],
                ['id' => 4 , 'variable_name'=> 'serial',               'value' => null,                                                            'parameter_name' => 'serial',       'object' => []],
                ['id' => 5 , 'variable_name'=> 'id_transaction',       'value' => null,                                                            'parameter_name' => null,           'object' => []],
                ['id' => 6 , 'variable_name'=> 'id_event',             'value' => null,                                                            'parameter_name' => null, 'object' => []],
                ['id' => 7 , 'variable_name'=> 'data',                 'value' => null,                                                            'parameter_name' => null,           'object' => [
                    ['variable_name'=> 'response_date',  'parameter_name' => 'timestamp', 'format' => 'date'],
                ]
                ],
            ]
        ],
        [
            'notification_type_id' => 17,
            'event_id' => 33,
            'json' => [
                ['id' => 1 , 'variable_name'=> 'notification_type_id', 'value' => 17,                                                               'parameter_name' => null,           'object' => []],
                ['id' => 2 , 'variable_name'=> 'message',              'value' => 'Se presenta desconexión del equipo', 'parameter_name' => null,           'object' => []],
                ['id' => 3 , 'variable_name'=> 'success',              'value' => 1,                                                               'parameter_name' => null,           'object' => []],
                ['id' => 4 , 'variable_name'=> 'serial',               'value' => null,                                                            'parameter_name' => 'serial',       'object' => []],
                ['id' => 5 , 'variable_name'=> 'id_transaction',       'value' => null,                                                            'parameter_name' => null,           'object' => []],
                ['id' => 6 , 'variable_name'=> 'id_event',             'value' => null,                                                            'parameter_name' => null, 'object' => []],
                ['id' => 7 , 'variable_name'=> 'data',                 'value' => null,                                                            'parameter_name' => null,           'object' => [
                    ['variable_name'=> 'response_date',  'parameter_name' => 'timestamp', 'format' => 'date'],
                ]
                ],
            ]
        ],
        [
            'notification_type_id' => 18,
            'event_id' => 41,
            'json' => [
                ['id' => 1 , 'variable_name'=> 'notification_type_id', 'value' => 18,                                                               'parameter_name' => null,           'object' => []],
                ['id' => 2 , 'variable_name'=> 'message',              'value' => 'Se activo/desactivo el envio de mensajes en tiempo real', 'parameter_name' => null,           'object' => []],
                ['id' => 3 , 'variable_name'=> 'success',              'value' => 1,                                                               'parameter_name' => null,           'object' => []],
                ['id' => 4 , 'variable_name'=> 'serial',               'value' => null,                                                            'parameter_name' => 'serial',       'object' => []],
                ['id' => 5 , 'variable_name'=> 'id_transaction',       'value' => null,                                                            'parameter_name' => null,           'object' => []],
                ['id' => 6 , 'variable_name'=> 'id_event',             'value' => null,                                                            'parameter_name' => null, 'object' => []],
                ['id' => 7 , 'variable_name'=> 'data',                 'value' => null,                                                            'parameter_name' => null,           'object' => [
                    ['variable_name'=> 'response_date',  'parameter_name' => 'timestamp', 'format' => 'date'],
                    ['variable_name'=> 'status_real_time',   'parameter_name' => 'status',       'format' => 'number'],

                ]
                ],
            ]
        ],
        [
            'notification_type_id' => 9,
            'event_id' => 35,
            'json' => [
                ['id' => 1 , 'variable_name'=> 'notification_type_id', 'value' => 9,                                          'parameter_name' => null,           'object' => []],
                ['id' => 2 , 'variable_name'=> 'message',              'value' => 'Se detecto cambio de estado en sensor de apertura', 'parameter_name' => null,           'object' => []],
                ['id' => 3 , 'variable_name'=> 'success',              'value' => 1,                                          'parameter_name' => null,           'object' => []],
                ['id' => 4 , 'variable_name'=> 'serial',               'value' => null,                                       'parameter_name' => 'serial',       'object' => []],
                ['id' => 5 , 'variable_name'=> 'id_transaction',       'value' => null,                                                            'parameter_name' => null,           'object' => []],
                ['id' => 6 , 'variable_name'=> 'id_event',             'value' => null,                                                            'parameter_name' => null, 'object' => []],
                ['id' => 7 , 'variable_name'=> 'data',                 'value' => null,                                       'parameter_name' => null,           'object' => [
                    ['variable_name'=> 'response_date', 'parameter_name' => 'timestamp',    'format' => 'date'],
                    ['variable_name'=> 'status_door',   'parameter_name' => 'status',       'format' => 'number'],
                    ['variable_name'=> 'import_kwh',    'parameter_name' => 'import_kwh',   'format' => 'number'],
                    ['variable_name'=> 'export_kwh',    'parameter_name' => 'export_kwh',   'format' => 'number'],
                    ['variable_name'=> 'import_kvarh',  'parameter_name' => 'import_kvarh', 'format' => 'number'],
                    ['variable_name'=> 'export_kvarh',  'parameter_name' => 'export_kvarh', 'format' => 'number'],
                ]
                ],
            ]
        ],
        [
            'notification_type_id' => 19,
            'event_id' => 36,
            'json' => [
                ['id' => 1 , 'variable_name'=> 'notification_type_id', 'value' => 19,                                             'parameter_name' => null,           'object' => []],
                ['id' => 2 , 'variable_name'=> 'message',              'value' => 'Se presento corte en suministro por manipulación de gabinete', 'parameter_name' => null,           'object' => []],
                ['id' => 3 , 'variable_name'=> 'success',              'value' => 1,                                             'parameter_name' => null,           'object' => []],
                ['id' => 4 , 'variable_name'=> 'serial',               'value' => null,                                          'parameter_name' => 'serial',       'object' => []],
                ['id' => 5 , 'variable_name'=> 'id_transaction',       'value' => null,                                          'parameter_name' => null,           'object' => []],
                ['id' => 6 , 'variable_name'=> 'id_event',             'value' => null,                                          'parameter_name' => null, 'object' => []],
                ['id' => 7 , 'variable_name'=> 'data',                 'value' => null,                                          'parameter_name' => null,           'object' => [
                    ['variable_name'=> 'response_date', 'parameter_name' => 'timestamp',    'format' => 'date'],
                    ['variable_name'=> 'status_coil',   'parameter_name' => 'status',       'format' => 'number'],
                    ['variable_name'=> 'import_kwh',    'parameter_name' => 'import_kwh',   'format' => 'number'],
                    ['variable_name'=> 'export_kwh',    'parameter_name' => 'export_kwh',   'format' => 'number'],
                    ['variable_name'=> 'import_kvarh',  'parameter_name' => 'import_kvarh', 'format' => 'number'],
                    ['variable_name'=> 'export_kvarh',  'parameter_name' => 'export_kvarh', 'format' => 'number'],
                ]
                ],
            ]
        ],
        [
            'notification_type_id' => 3,
            'event_id' => 37,
            'json' => [
                ['id' => 1 , 'variable_name'=> 'notification_type_id', 'value' => 20,                                             'parameter_name' => null,           'object' => []],
                ['id' => 2 , 'variable_name'=> 'message',              'value' => 'Fallo el accionamiento del suministro electrico por manipulación de gabinete', 'parameter_name' => null,           'object' => []],
                ['id' => 3 , 'variable_name'=> 'success',              'value' => 0,                                             'parameter_name' => null,           'object' => []],
                ['id' => 4 , 'variable_name'=> 'serial',               'value' => null,                                          'parameter_name' => 'serial',       'object' => []],
                ['id' => 5 , 'variable_name'=> 'id_transaction',       'value' => null,                                          'parameter_name' => null,           'object' => []],
                ['id' => 6 , 'variable_name'=> 'id_event',             'value' => null,                                          'parameter_name' => null, 'object' => []],
                ['id' => 7 , 'variable_name'=> 'data',                 'value' => null,                                          'parameter_name' => null,           'object' => [
                    ['variable_name'=> 'response_date', 'parameter_name' => 'timestamp',    'format' => 'date'],
                    ['variable_name'=> 'status_coil',   'parameter_name' => 'status',       'format' => 'number'],
                    ['variable_name'=> 'import_kwh',    'parameter_name' => 'import_kwh',   'format' => 'number'],
                    ['variable_name'=> 'export_kwh',    'parameter_name' => 'export_kwh',   'format' => 'number'],
                    ['variable_name'=> 'import_kvarh',  'parameter_name' => 'import_kvarh', 'format' => 'number'],
                    ['variable_name'=> 'export_kvarh',  'parameter_name' => 'export_kvarh', 'format' => 'number'],
                ]
                ],
            ]
        ],
        [
            'notification_type_id' => 3,
            'event_id' => 38,
            'json' => [
                ['id' => 1 , 'variable_name'=> 'notification_type_id', 'value' => 21,                                             'parameter_name' => null,           'object' => []],
                ['id' => 2 , 'variable_name'=> 'message',              'value' => 'Fallo el accionamiento del suministro electrico por falla en el nivel de tensión', 'parameter_name' => null,           'object' => []],
                ['id' => 3 , 'variable_name'=> 'success',              'value' => 0,                                             'parameter_name' => null,           'object' => []],
                ['id' => 4 , 'variable_name'=> 'serial',               'value' => null,                                          'parameter_name' => 'serial',       'object' => []],
                ['id' => 5 , 'variable_name'=> 'id_transaction',       'value' => null,                                          'parameter_name' => null,           'object' => []],
                ['id' => 6 , 'variable_name'=> 'id_event',             'value' => null,                                          'parameter_name' => null, 'object' => []],
                ['id' => 7 , 'variable_name'=> 'data',                 'value' => null,                                          'parameter_name' => null,           'object' => [
                    ['variable_name'=> 'response_date', 'parameter_name' => 'timestamp',    'format' => 'date'],
                    ['variable_name'=> 'status_coil',   'parameter_name' => 'status',       'format' => 'number'],
                    ['variable_name'=> 'import_kwh',    'parameter_name' => 'import_kwh',   'format' => 'number'],
                    ['variable_name'=> 'export_kwh',    'parameter_name' => 'export_kwh',   'format' => 'number'],
                    ['variable_name'=> 'import_kvarh',  'parameter_name' => 'import_kvarh', 'format' => 'number'],
                    ['variable_name'=> 'export_kvarh',  'parameter_name' => 'export_kvarh', 'format' => 'number'],
                    ['variable_name'=> 'volt_max',  'parameter_name' => 'max_volt', 'format' => 'number'],
                    ['variable_name'=> 'volt_min',  'parameter_name' => 'min_volt', 'format' => 'number'],
                    ['variable_name'=> 'ph1_volt',  'parameter_name' => 'ph1_volt', 'format' => 'number'],
                    ['variable_name'=> 'ph2_volt',  'parameter_name' => 'ph2_volt', 'format' => 'number'],
                    ['variable_name'=> 'ph3_volt',  'parameter_name' => 'ph3_volt', 'format' => 'number'],
                ]
                ],
            ]
        ],
        [
            'notification_type_id' => 22,
            'event_id' => 39,
            'json' => [
                ['id' => 1 , 'variable_name'=> 'notification_type_id', 'value' => 22,                                             'parameter_name' => null,           'object' => []],
                ['id' => 2 , 'variable_name'=> 'message',              'value' => 'Se presenta cambio de estado en el suministro por falla en el nivel de tensión', 'parameter_name' => null,           'object' => []],
                ['id' => 3 , 'variable_name'=> 'success',              'value' => 1,                                             'parameter_name' => null,           'object' => []],
                ['id' => 4 , 'variable_name'=> 'serial',               'value' => null,                                          'parameter_name' => 'serial',       'object' => []],
                ['id' => 5 , 'variable_name'=> 'id_transaction',       'value' => null,                                          'parameter_name' => null,           'object' => []],
                ['id' => 6 , 'variable_name'=> 'id_event',             'value' => null,                                          'parameter_name' => null, 'object' => []],
                ['id' => 7 , 'variable_name'=> 'data',                 'value' => null,                                          'parameter_name' => null,           'object' => [
                    ['variable_name'=> 'response_date', 'parameter_name' => 'timestamp',    'format' => 'date'],
                    ['variable_name'=> 'status_coil',   'parameter_name' => 'status',       'format' => 'number'],
                    ['variable_name'=> 'import_kwh',    'parameter_name' => 'import_kwh',   'format' => 'number'],
                    ['variable_name'=> 'export_kwh',    'parameter_name' => 'export_kwh',   'format' => 'number'],
                    ['variable_name'=> 'import_kvarh',  'parameter_name' => 'import_kvarh', 'format' => 'number'],
                    ['variable_name'=> 'export_kvarh',  'parameter_name' => 'export_kvarh', 'format' => 'number'],
                    ['variable_name'=> 'volt_max',  'parameter_name' => 'max_volt', 'format' => 'number'],
                    ['variable_name'=> 'volt_min',  'parameter_name' => 'min_volt', 'format' => 'number'],
                    ['variable_name'=> 'ph1_volt',  'parameter_name' => 'ph1_volt', 'format' => 'number'],
                    ['variable_name'=> 'ph2_volt',  'parameter_name' => 'ph2_volt', 'format' => 'number'],
                    ['variable_name'=> 'ph3_volt',  'parameter_name' => 'ph3_volt', 'format' => 'number'],
                ]
                ],
            ]
        ],
        [
            'notification_type_id' => 23,
            'event_id' => 40,
            'json' => [
                ['id' => 1 , 'variable_name'=> 'notification_type_id', 'value' => 23,                                                               'parameter_name' => null,           'object' => []],
                ['id' => 2 , 'variable_name'=> 'message',              'value' => 'Fallo al acceder a registros del medidor', 'parameter_name' => null,           'object' => []],
                ['id' => 3 , 'variable_name'=> 'success',              'value' => 1,                                                               'parameter_name' => null,           'object' => []],
                ['id' => 4 , 'variable_name'=> 'serial',               'value' => null,                                                            'parameter_name' => 'serial',       'object' => []],
                ['id' => 5 , 'variable_name'=> 'id_transaction',       'value' => null,                                                            'parameter_name' => null,           'object' => []],
                ['id' => 6 , 'variable_name'=> 'id_event',             'value' => null,                                                            'parameter_name' => null, 'object' => []],
                ['id' => 7 , 'variable_name'=> 'data',                 'value' => null,                                                            'parameter_name' => null,           'object' => [
                    ['variable_name'=> 'response_date',  'parameter_name' => 'timestamp', 'format' => 'date'],

                ]
                ],
            ]
        ],
        [
            'notification_type_id' => 43,
            'event_id' => 43,
            'json' => [
                ['id' => 1 , 'variable_name'=> 'notification_type_id', 'value' => 43,                                                               'parameter_name' => null,           'object' => []],
                ['id' => 2 , 'variable_name'=> 'message',              'value' => 'Medidor disponible para actualización', 'parameter_name' => null,           'object' => []],
                ['id' => 3 , 'variable_name'=> 'success',              'value' => 1,                                                               'parameter_name' => null,           'object' => []],
                ['id' => 4 , 'variable_name'=> 'serial',               'value' => null,                                                            'parameter_name' => 'serial',       'object' => []],
                ['id' => 5 , 'variable_name'=> 'id_transaction',       'value' => null,                                                            'parameter_name' => null,           'object' => []],
                ['id' => 6 , 'variable_name'=> 'id_event',             'value' => null,                                                            'parameter_name' => null, 'object' => []],
                ['id' => 7 , 'variable_name'=> 'data',                 'value' => null,                                                            'parameter_name' => null,           'object' => [
                    ['variable_name'=> 'response_date',  'parameter_name' => 'timestamp', 'format' => 'date'],
                    ['variable_name'=> 'status',  'parameter_name' => 'status', 'format' => 'number'],

                ]
                ],
            ]
        ],
        [
            'notification_type_id' => 24,
            'event_id' => null,
            'json' => [
                ['id' => 1 , 'variable_name'=> 'notification_type_id', 'value' => 24,                                                               'parameter_name' => null,           'object' => []],
                ['id' => 2 , 'variable_name'=> 'message',              'value' => 'Alerta de variable fuera de rango', 'parameter_name' => null,           'object' => []],
                ['id' => 3 , 'variable_name'=> 'success',              'value' => 1,                                                               'parameter_name' => null,           'object' => []],
                ['id' => 4 , 'variable_name'=> 'serial',               'value' => null,                                                            'parameter_name' => 'serial',       'object' => []],
                ['id' => 5 , 'variable_name'=> 'id_transaction',       'value' => null,                                                            'parameter_name' => null,           'object' => []],
                ['id' => 6 , 'variable_name'=> 'id_event',             'value' => null,                                                            'parameter_name' => null, 'object' => []],
                ['id' => 7 , 'variable_name'=> 'data',                 'value' => null,                                                            'parameter_name' => null,           'object' => [
                    ['variable_name'=> 'response_date',  'parameter_name' => 'timestamp', 'format' => 'number'],
                    ['variable_name'=> 'variable_name',  'parameter_name' => 'variable_name', 'format' => 'string'],
                    ['variable_name'=> 'value',  'parameter_name' => 'value', 'format' => 'number'],
                    ['variable_name'=> 'max_value',  'parameter_name' => 'max_value', 'format' => 'number'],
                    ['variable_name'=> 'min_value',  'parameter_name' => 'min_value', 'format' => 'number'],
                ]
                ],
            ]
        ],
//        [
//            'notification_type_id' => 25,
//            'event_id' => null,
//            'json' => [
//                ['id' => 1 , 'variable_name'=> 'notification_type_id', 'value' => 25,                                                               'parameter_name' => null,           'object' => []],
//                ['id' => 2 , 'variable_name'=> 'message',              'value' => 'Nueva lectura recibida', 'parameter_name' => null,           'object' => []],
//                ['id' => 3 , 'variable_name'=> 'success',              'value' => 1,                                                               'parameter_name' => null,           'object' => []],
//                ['id' => 4 , 'variable_name'=> 'serial',               'value' => null,                                                            'parameter_name' => 'serial',       'object' => []],
//                ['id' => 5 , 'variable_name'=> 'id_transaction',       'value' => null,                                                            'parameter_name' => null,           'object' => []],
//                ['id' => 6 , 'variable_name'=> 'id_event',             'value' => null,                                                            'parameter_name' => null, 'object' => []],
//                ['id' => 7 , 'variable_name'=> 'data',                 'value' => null,                                                            'parameter_name' => null,           'object' => [
//                    ['variable_name'=> 'response_date',  'parameter_name' => 'timestamp', 'format' => 'number'],
//                    ['variable_name'=> 'ph1_volt', 'parameter_name' => 'ph1_volt',    'format' => 'number'],
//                    ['variable_name'=> 'ph2_volt', 'parameter_name' => 'ph2_volt',    'format' => 'number'],
//                    ['variable_name'=> 'ph3_volt', 'parameter_name' => 'ph3_volt',    'format' => 'number'],
//                    ['variable_name'=> 'ph1_current', 'parameter_name' => 'ph1_current',    'format' => 'number'],
//                    ['variable_name'=> 'ph2_current', 'parameter_name' => 'ph2_current',    'format' => 'number'],
//                    ['variable_name'=> 'ph3_current', 'parameter_name' => 'ph3_current',    'format' => 'number'],
//                    ['variable_name'=> 'ph1_power', 'parameter_name' => 'ph1_power',    'format' => 'number'],
//                    ['variable_name'=> 'ph2_power', 'parameter_name' => 'ph2_power',    'format' => 'number'],
//                    ['variable_name'=> 'ph3_power', 'parameter_name' => 'ph3_power',    'format' => 'number'],
//                    ['variable_name'=> 'ph1_VA', 'parameter_name' => 'ph1_VA',    'format' => 'number'],
//                    ['variable_name'=> 'ph2_VA', 'parameter_name' => 'ph2_VA',    'format' => 'number'],
//                    ['variable_name'=> 'ph3_VA', 'parameter_name' => 'ph3_VA',    'format' => 'number'],
//                    ['variable_name'=> 'ph1_VAr', 'parameter_name' => 'ph1_VAr',    'format' => 'number'],
//                    ['variable_name'=> 'ph2_VAr', 'parameter_name' => 'ph2_VAr',    'format' => 'number'],
//                    ['variable_name'=> 'ph3_VAr', 'parameter_name' => 'ph3_VAr',    'format' => 'number'],
//                    ['variable_name'=> 'ph1_power_factor', 'parameter_name' => 'ph1_power_factor',    'format' => 'number'],
//                    ['variable_name'=> 'ph2_power_factor', 'parameter_name' => 'ph2_power_factor',    'format' => 'number'],
//                    ['variable_name'=> 'ph3_power_factor', 'parameter_name' => 'ph3_power_factor',    'format' => 'number'],
//                    ['variable_name'=> 'total_power_factor', 'parameter_name' => 'total_power_factor',    'format' => 'number'],
//                    ['variable_name'=> 'ph1_phase_angle', 'parameter_name' => 'ph1_phase_angle',    'format' => 'number'],
//                    ['variable_name'=> 'ph2_phase_angle', 'parameter_name' => 'ph2_phase_angle',    'format' => 'number'],
//                    ['variable_name'=> 'ph3_phase_angle', 'parameter_name' => 'ph3_phase_angle',    'format' => 'number'],
//                    ['variable_name'=> 'total_phase_angle', 'parameter_name' => 'total_phase_angle',    'format' => 'number'],
//                    ['variable_name'=> 'total_system_power', 'parameter_name' => 'total_system_power',    'format' => 'number'],
//                    ['variable_name'=> 'total_system_var', 'parameter_name' => 'total_system_var',    'format' => 'number'],
//                    ['variable_name'=> 'frecuency', 'parameter_name' => 'frecuency',    'format' => 'number'],
//                    ['variable_name'=> 'import_wh', 'parameter_name' => 'import_wh',    'format' => 'number'],
//                    ['variable_name'=> 'export_wh', 'parameter_name' => 'export_wh',    'format' => 'number'],
//                    ['variable_name'=> 'importVArh', 'parameter_name' => 'importVArh',    'format' => 'number'],
//                    ['variable_name'=> 'exportVArh', 'parameter_name' => 'exportVArh',    'format' => 'number'],
//                    ['variable_name'=> 'ph1_ph2_volt', 'parameter_name' => 'ph1_ph2_volt',    'format' => 'number'],
//                    ['variable_name'=> 'ph2_ph3_volt', 'parameter_name' => 'ph2_ph3_volt',    'format' => 'number'],
//                    ['variable_name'=> 'ph3_ph1_volt', 'parameter_name' => 'ph3_ph1_volt',    'format' => 'number'],
//                    ['variable_name'=> 'ph1_volt_thd', 'parameter_name' => 'ph1_volt_thd',    'format' => 'number'],
//                    ['variable_name'=> 'ph2_volt_thd', 'parameter_name' => 'ph2_volt_thd',    'format' => 'number'],
//                    ['variable_name'=> 'ph3_volt_thd', 'parameter_name' => 'ph3_volt_thd',    'format' => 'number'],
//                    ['variable_name'=> 'ph1_current_thd', 'parameter_name' => 'ph1_current_thd',    'format' => 'number'],
//                    ['variable_name'=> 'ph2_current_thd', 'parameter_name' => 'ph2_current_thd',    'format' => 'number'],
//                    ['variable_name'=> 'ph3_current_thd', 'parameter_name' => 'ph3_current_thd',    'format' => 'number'],
//                    ['variable_name'=> 'ph1_ph2_volt_thd', 'parameter_name' => 'ph1_ph2_volt_thd',    'format' => 'number'],
//                    ['variable_name'=> 'ph2_ph3_volt_thd', 'parameter_name' => 'ph2_ph3_volt_thd',    'format' => 'number'],
//                    ['variable_name'=> 'ph3_ph1_volt_thd', 'parameter_name' => 'ph3_ph1_volt_thd',    'format' => 'number'],
//                    ['variable_name'=> 'ph1_import_kwh', 'parameter_name' => 'ph1_import_kwh',    'format' => 'number'],
//                    ['variable_name'=> 'ph2_import_kwh', 'parameter_name' => 'ph2_import_kwh',    'format' => 'number'],
//                    ['variable_name'=> 'ph3_import_kwh', 'parameter_name' => 'ph3_import_kwh',    'format' => 'number'],
//                    ['variable_name'=> 'ph1_import_kvarh', 'parameter_name' => 'ph1_import_kvarh',    'format' => 'number'],
//                    ['variable_name'=> 'ph2_import_kvarh', 'parameter_name' => 'ph2_import_kvarh',    'format' => 'number'],
//                    ['variable_name'=> 'ph3_import_kvarh', 'parameter_name' => 'ph3_import_kvarh',    'format' => 'number'],
//                    ['variable_name'=> 'ph1_varLh_acumm', 'parameter_name' => 'ph1_varLh_acumm',    'format' => 'number'],
//                    ['variable_name'=> 'ph2_varLh_acumm', 'parameter_name' => 'ph2_varLh_acumm',    'format' => 'number'],
//                    ['variable_name'=> 'ph3_varLh_acumm', 'parameter_name' => 'ph3_varLh_acumm',    'format' => 'number'],
//                    ['variable_name'=> 'ph1_varCh_acumm', 'parameter_name' => 'ph1_varCh_acumm',    'format' => 'number'],
//                    ['variable_name'=> 'ph2_varCh_acumm', 'parameter_name' => 'ph2_varCh_acumm',    'format' => 'number'],
//                    ['variable_name'=> 'ph3_varCh_acumm', 'parameter_name' => 'ph3_varCh_acumm',    'format' => 'number'],
//                    ['variable_name'=> 'volt_dc', 'parameter_name' => 'volt_dc',    'format' => 'number'],
//                ]
//                ],
//            ]
//        ],
        [
            'notification_type_id' => 46,
            'event_id' => 46,
            'json' => [
                ['id' => 1 , 'variable_name'=> 'notification_type_id', 'value' => 46,                                                          'parameter_name' => null,           'object' => []],
                ['id' => 2 , 'variable_name'=> 'message',              'value' => 'Se realizó la configuración de rangos admisibles para controlar dispositivo', 'parameter_name' => null,           'object' => []],
                ['id' => 3 , 'variable_name'=> 'success',              'value' => 1,                                                          'parameter_name' => null,           'object' => []],
                ['id' => 4 , 'variable_name'=> 'serial',               'value' => null,                                                       'parameter_name' => 'serial',       'object' => []],
                ['id' => 5 , 'variable_name'=> 'id_transaction',       'value' => null,                                                       'parameter_name' => null,           'object' => []],
                ['id' => 6 , 'variable_name'=> 'id_event',             'value' => null,                                                       'parameter_name' => null, 'object' => []],
                ['id' => 7 , 'variable_name'=> 'data',                 'value' => null,                                                       'parameter_name' => null,           'object' => [
                    ['variable_name'=> 'response_date', 'parameter_name' => 'timestamp', 'format' => 'date']
                ]
                ],
            ]
        ],
        [
            'notification_type_id' => 48,
            'event_id' => 48,
            'json' => [
                ['id' => 1 , 'variable_name'=> 'notification_type_id', 'value' => 48,                                             'parameter_name' => null,           'object' => []],
                ['id' => 2 , 'variable_name'=> 'message',              'value' => 'Se activo/desactivo el servicio de manipulacion del suministro electrico', 'parameter_name' => null,           'object' => []],
                ['id' => 3 , 'variable_name'=> 'success',              'value' => 1,                                             'parameter_name' => null,           'object' => []],
                ['id' => 4 , 'variable_name'=> 'serial',               'value' => null,                                          'parameter_name' => 'serial',       'object' => []],
                ['id' => 5 , 'variable_name'=> 'id_transaction',       'value' => null,                                          'parameter_name' => null,           'object' => []],
                ['id' => 6 , 'variable_name'=> 'id_event',             'value' => null,                                          'parameter_name' => null, 'object' => []],
                ['id' => 7 , 'variable_name'=> 'data',                 'value' => null,                                          'parameter_name' => null,           'object' => [
                    ['variable_name'=> 'response_date', 'parameter_name' => 'timestamp',    'format' => 'date'],
                    ['variable_name'=> 'status_service_coil',   'parameter_name' => 'status_service_coil',       'format' => 'number'],
                    ['variable_name'=> 'import_kwh',    'parameter_name' => 'import_kwh',   'format' => 'number'],
                    ['variable_name'=> 'export_kwh',    'parameter_name' => 'export_kwh',   'format' => 'number'],
                    ['variable_name'=> 'import_kvarh',  'parameter_name' => 'import_kvarh', 'format' => 'number'],
                    ['variable_name'=> 'export_kvarh',  'parameter_name' => 'export_kvarh', 'format' => 'number'],
                ]
                ],
            ]
        ],
        [
            'notification_type_id' => 80,
            'event_id' => 49,
            'json' => [
                ['id' => 1 , 'variable_name'=> 'notification_type_id', 'value' => 80,                                             'parameter_name' => null,           'object' => []],
                ['id' => 2 , 'variable_name'=> 'message',              'value' => 'Se activo/desactivo el servicio de manipulacion del suministro electrico desde la app', 'parameter_name' => null,           'object' => []],
                ['id' => 3 , 'variable_name'=> 'success',              'value' => 1,                                             'parameter_name' => null,           'object' => []],
                ['id' => 4 , 'variable_name'=> 'serial',               'value' => null,                                          'parameter_name' => 'serial',       'object' => []],
                ['id' => 5 , 'variable_name'=> 'id_transaction',       'value' => null,                                          'parameter_name' => null,           'object' => []],
                ['id' => 6 , 'variable_name'=> 'id_event',             'value' => null,                                          'parameter_name' => null, 'object' => []],
                ['id' => 7 , 'variable_name'=> 'data',                 'value' => null,                                          'parameter_name' => null,           'object' => [
                    ['variable_name'=> 'response_date', 'parameter_name' => 'timestamp',    'format' => 'date'],
                    ['variable_name'=> 'status_service_coil',   'parameter_name' => 'status_service_coil',       'format' => 'number'],
                    ['variable_name'=> 'import_kwh',    'parameter_name' => 'import_kwh',   'format' => 'number'],
                    ['variable_name'=> 'export_kwh',    'parameter_name' => 'export_kwh',   'format' => 'number'],
                    ['variable_name'=> 'import_kvarh',  'parameter_name' => 'import_kvarh', 'format' => 'number'],
                    ['variable_name'=> 'export_kvarh',  'parameter_name' => 'export_kvarh', 'format' => 'number'],
                ]
                ],
            ]
        ],
        [
            'notification_type_id' => 51,
            'event_id' => 51,
            'json' => [
                ['id' => 1 , 'variable_name'=> 'notification_type_id', 'value' => 51,                                                          'parameter_name' => null,           'object' => []],
                ['id' => 2 , 'variable_name'=> 'message',              'value' => 'Se realizó el cambio de contraseña en el medidor', 'parameter_name' => null,           'object' => []],
                ['id' => 3 , 'variable_name'=> 'success',              'value' => 1,                                                          'parameter_name' => null,           'object' => []],
                ['id' => 4 , 'variable_name'=> 'serial',               'value' => null,                                                       'parameter_name' => 'serial',       'object' => []],
                ['id' => 5 , 'variable_name'=> 'id_transaction',       'value' => null,                                                       'parameter_name' => null,           'object' => []],
                ['id' => 6 , 'variable_name'=> 'id_event',             'value' => null,                                                       'parameter_name' => null, 'object' => []],
                ['id' => 7 , 'variable_name'=> 'data',                 'value' => null,                                                       'parameter_name' => null,           'object' => [
                    ['variable_name'=> 'response_date', 'parameter_name' => 'timestamp', 'format' => 'date'],
                    ['variable_name'=> 'password', 'parameter_name' => 'password', 'format' => 'number']
                ]
                ],
            ]
        ],
        [
            'notification_type_id' => 52,
            'event_id' => 52,
            'json' => [
                ['id' => 1 , 'variable_name'=> 'notification_type_id', 'value' => 52,                                                          'parameter_name' => null,           'object' => []],
                ['id' => 2 , 'variable_name'=> 'message',              'value' => 'Se realizó cambio de contraseña en el medidor desde la app', 'parameter_name' => null,           'object' => []],
                ['id' => 3 , 'variable_name'=> 'success',              'value' => 1,                                                          'parameter_name' => null,           'object' => []],
                ['id' => 4 , 'variable_name'=> 'serial',               'value' => null,                                                       'parameter_name' => 'serial',       'object' => []],
                ['id' => 5 , 'variable_name'=> 'id_transaction',       'value' => null,                                                       'parameter_name' => null,           'object' => []],
                ['id' => 6 , 'variable_name'=> 'id_event',             'value' => null,                                                       'parameter_name' => null, 'object' => []],
                ['id' => 7 , 'variable_name'=> 'data',                 'value' => null,                                                       'parameter_name' => null,           'object' => [
                    ['variable_name'=> 'response_date', 'parameter_name' => 'timestamp', 'format' => 'date'],
                    ['variable_name'=> 'password', 'parameter_name' => 'password', 'format' => 'number']
                ]
                ],
            ]
        ],
        [
            'notification_type_id' => 54,
            'event_id' => 54,
            'json' => [
                ['id' => 1 , 'variable_name'=> 'notification_type_id', 'value' => 54,                                                          'parameter_name' => null,           'object' => []],
                ['id' => 2 , 'variable_name'=> 'message',              'value' => 'Se realizó la consulta de la contraseña del medidor', 'parameter_name' => null,           'object' => []],
                ['id' => 3 , 'variable_name'=> 'success',              'value' => 1,                                                          'parameter_name' => null,           'object' => []],
                ['id' => 4 , 'variable_name'=> 'serial',               'value' => null,                                                       'parameter_name' => 'serial',       'object' => []],
                ['id' => 5 , 'variable_name'=> 'id_transaction',       'value' => null,                                                       'parameter_name' => null,           'object' => []],
                ['id' => 6 , 'variable_name'=> 'id_event',             'value' => null,                                                       'parameter_name' => null, 'object' => []],
                ['id' => 7 , 'variable_name'=> 'data',                 'value' => null,                                                       'parameter_name' => null,           'object' => [
                    ['variable_name'=> 'response_date', 'parameter_name' => 'timestamp', 'format' => 'date'],
                    ['variable_name'=> 'password', 'parameter_name' => 'password', 'format' => 'number']
                ]
                ],
            ]
        ],
        [
            'notification_type_id' => 56,
            'event_id' => 56,
            'json' => [
                ['id' => 1 , 'variable_name'=> 'notification_type_id', 'value' => 56,                                                          'parameter_name' => null,           'object' => []],
                ['id' => 2 , 'variable_name'=> 'message',              'value' => 'Se realizó la configuración de la fecha de corte del dispositivo', 'parameter_name' => null,           'object' => []],
                ['id' => 3 , 'variable_name'=> 'success',              'value' => 1,                                                          'parameter_name' => null,           'object' => []],
                ['id' => 4 , 'variable_name'=> 'serial',               'value' => null,                                                       'parameter_name' => 'serial',       'object' => []],
                ['id' => 5 , 'variable_name'=> 'id_transaction',       'value' => null,                                                       'parameter_name' => null,           'object' => []],
                ['id' => 6 , 'variable_name'=> 'id_event',             'value' => null,                                                       'parameter_name' => null, 'object' => []],
                ['id' => 7 , 'variable_name'=> 'data',                 'value' => null,                                                       'parameter_name' => null,           'object' => [
                    ['variable_name'=> 'response_date', 'parameter_name' => 'timestamp', 'format' => 'date']
                ]
                ],
            ]
        ],
        [
            'notification_type_id' => 58,
            'event_id' => 58,
            'json' => [
                ['id' => 1 , 'variable_name'=> 'notification_type_id', 'value' => 58,                                                          'parameter_name' => null,           'object' => []],
                ['id' => 2 , 'variable_name'=> 'message',              'value' => 'Se realizó la configuración de estados para los rangos admisibles del dispositivo', 'parameter_name' => null,           'object' => []],
                ['id' => 3 , 'variable_name'=> 'success',              'value' => 1,                                                          'parameter_name' => null,           'object' => []],
                ['id' => 4 , 'variable_name'=> 'serial',               'value' => null,                                                       'parameter_name' => 'serial',       'object' => []],
                ['id' => 5 , 'variable_name'=> 'id_transaction',       'value' => null,                                                       'parameter_name' => null,           'object' => []],
                ['id' => 6 , 'variable_name'=> 'id_event',             'value' => null,                                                       'parameter_name' => null, 'object' => []],
                ['id' => 7 , 'variable_name'=> 'data',                 'value' => null,                                                       'parameter_name' => null,           'object' => [
                    ['variable_name'=> 'response_date', 'parameter_name' => 'timestamp', 'format' => 'date']
                ]
                ],
            ]
        ],



    ]

];
