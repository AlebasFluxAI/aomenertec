<?php

use App\Http\Resources\V1\Permissions;

return [
    "admins" => [
        Permissions::EQUIPMENT_CREATE,
        Permissions::EQUIPMENT_EDIT,
        Permissions::EQUIPMENT_DELETE,
        Permissions::EQUIPMENT_SHOW,

        Permissions::EQUIPMENT_TYPE_CREATE,
        Permissions::EQUIPMENT_TYPE_EDIT,
        Permissions::EQUIPMENT_TYPE_DELETE,
        Permissions::EQUIPMENT_TYPE_SHOW,

        Permissions::NETWORK_OPERATOR_CREATE,
        Permissions::NETWORK_OPERATOR_EDIT,
        Permissions::NETWORK_OPERATOR_DELETE,
        Permissions::NETWORK_OPERATOR_SHOW,
        Permissions::NETWORK_OPERATOR_LINK_EQUIPMENT,

        Permissions::TECHNICIAN_CREATE,
        Permissions::TECHNICIAN_EDIT,
        Permissions::TECHNICIAN_DELETE,
        Permissions::TECHNICIAN_SHOW,
        Permissions::TECHNICIAN_LINK_CLIENT,

        Permissions::SELLER_CREATE,
        Permissions::SELLER_EDIT,
        Permissions::SELLER_DELETE,
        Permissions::SELLER_SHOW,
        Permissions::SELLER_LINK_CLIENT,

        Permissions::CLIENT_CREATE,
        Permissions::CLIENT_EDIT,
        Permissions::CLIENT_DELETE,
        Permissions::CLIENT_SHOW,
        Permissions::CLIENT_SETTINGS,
        Permissions::CLIENT_SHOW_MONITORING,
    ],
    "super_admins" => [
        Permissions::ADMIN_CREATE,
        Permissions::ADMIN_LINK_EQUIPMENT,
        Permissions::ADMIN_LINK_EQUIPMENT_TYPE,
        Permissions::ADMIN_EDIT,
        Permissions::ADMIN_DELETE,
        Permissions::ADMIN_SHOW,


        Permissions::EQUIPMENT_CREATE,
        Permissions::EQUIPMENT_EDIT,
        Permissions::EQUIPMENT_DELETE,
        Permissions::EQUIPMENT_SHOW,

        Permissions::EQUIPMENT_TYPE_CREATE,
        Permissions::EQUIPMENT_TYPE_EDIT,
        Permissions::EQUIPMENT_TYPE_DELETE,
        Permissions::EQUIPMENT_TYPE_SHOW,

        Permissions::NETWORK_OPERATOR_CREATE,
        Permissions::NETWORK_OPERATOR_EDIT,
        Permissions::NETWORK_OPERATOR_DELETE,
        Permissions::NETWORK_OPERATOR_SHOW,
        Permissions::NETWORK_OPERATOR_LINK_EQUIPMENT,

        Permissions::TECHNICIAN_CREATE,
        Permissions::TECHNICIAN_EDIT,
        Permissions::TECHNICIAN_DELETE,
        Permissions::TECHNICIAN_SHOW,
        Permissions::TECHNICIAN_LINK_CLIENT,

        Permissions::SELLER_CREATE,
        Permissions::SELLER_EDIT,
        Permissions::SELLER_DELETE,
        Permissions::SELLER_SHOW,
        Permissions::SELLER_LINK_CLIENT,

        Permissions::CLIENT_CREATE,
        Permissions::CLIENT_EDIT,
        Permissions::CLIENT_DELETE,
        Permissions::CLIENT_SHOW,
        Permissions::CLIENT_SHOW_MONITORING,
        Permissions::CLIENT_SETTINGS
    ],
    "network_operators" => [
        Permissions::TECHNICIAN_CREATE,
        Permissions::TECHNICIAN_EDIT,
        Permissions::TECHNICIAN_DELETE,
        Permissions::TECHNICIAN_SHOW,
        Permissions::TECHNICIAN_LINK_CLIENT,

        Permissions::SELLER_CREATE,
        Permissions::SELLER_EDIT,
        Permissions::SELLER_DELETE,
        Permissions::SELLER_SHOW,
        Permissions::SELLER_LINK_CLIENT,

        Permissions::CLIENT_CREATE,
        Permissions::CLIENT_EDIT,
        Permissions::CLIENT_DELETE,
        Permissions::CLIENT_SHOW,
        Permissions::CLIENT_SHOW_MONITORING,
        Permissions::CLIENT_SETTINGS
    ],
    "technicians" => [
        Permissions::CLIENT_SHOW,
        Permissions::CLIENT_SHOW_MONITORING,
    ],
    "sellers" => [
        Permissions::CLIENT_SHOW,
        Permissions::CLIENT_SHOW_MONITORING,
    ],
    "supervisors" => [
        Permissions::CLIENT_SHOW,
        Permissions::CLIENT_SHOW_MONITORING,
    ]
];
