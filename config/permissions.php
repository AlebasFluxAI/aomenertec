<?php

use App\Http\Resources\V1\Permissions;
use App\Models\V1\Admin;
use App\Models\V1\NetworkOperator;
use App\Models\V1\Seller;
use App\Models\V1\SuperAdmin;
use App\Models\V1\Supervisor;
use App\Models\V1\Support;
use App\Models\V1\Technician;

return [
    // Amdin
    Admin::class => [
        Permissions::EQUIPMENT_CREATE,
        Permissions::EQUIPMENT_EDIT,
        Permissions::EQUIPMENT_DELETE,
        Permissions::EQUIPMENT_REPAIR,
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
        Permissions::NETWORK_OPERATOR_REMOVE_EQUIPMENT,
        Permissions::NETWORK_OPERATOR_ENABLED,
        Permissions::NETWORK_OPERATOR_PRICE_CONFIGURATION,

        Permissions::TECHNICIAN_CREATE,
        Permissions::TECHNICIAN_EDIT,
        Permissions::TECHNICIAN_DELETE,
        Permissions::TECHNICIAN_SHOW,
        Permissions::TECHNICIAN_LINK_CLIENT,
        Permissions::TECHNICIAN_LINK_CLIENT,
        Permissions::TECHNICIAN_LINK_EQUIPMENT,
        Permissions::TECHNICIAN_REMOVE_EQUIPMENT,
        Permissions::TECHNICIAN_ENABLED,

        Permissions::SELLER_CREATE,
        Permissions::SELLER_EDIT,
        Permissions::SELLER_DELETE,
        Permissions::SELLER_SHOW,
        Permissions::SELLER_LINK_CLIENT,
        Permissions::SELLER_MANAGE_PURCHASE,

        Permissions::CLIENT_CREATE,
        Permissions::CLIENT_EDIT,
        Permissions::CLIENT_SHOW,
        Permissions::CLIENT_SHOW_MONITORING,
        Permissions::CLIENT_MONITORING_CONTROL,
        Permissions::CLIENT_SHOW_ALERTS,
        Permissions::CLIENT_SETTINGS,
        Permissions::CLIENT_SHOW_INVOICING,
        Permissions::INVOICE_REPORT,

        Permissions::SUPPORT_LINK_CLIENT,

        Permissions::SUPERVISOR_CREATE,
        Permissions::SUPERVISOR_EDIT,
        Permissions::SUPERVISOR_DELETE,
        Permissions::SUPERVISOR_SHOW,
        Permissions::SUPERVISOR_LINK_CLIENT,
        Permissions::SUPERVISOR_ENABLED,

        Permissions::PQR_SHOW,
        Permissions::PQR_CHANGE_LEVEL,
        Permissions::PQR_REPLY,
        Permissions::PQR_REQUEST_CLOSE,
        Permissions::PQR_EQUIPMENT_CHANGE_MANAGE,
        Permissions::CLIENT_ADD_EQUIPMENT,
        Permissions::PQR_EQUIPMENT_CHANGE,

        Permissions::WORK_ORDER_SHOW,
        Permissions::WORK_ORDER_DETAILS,
        Permissions::WORK_ORDER_INDEX,
        Permissions::WORK_ORDER_CREATE,
        Permissions::WORK_ORDER_EDIT,
        Permissions::CLIENT_WORK_ORDER,
        Permissions::CLIENT_INVOICE_GENERATE,

        Permissions::CLIENT_DISABLED_SHOW,


        Permissions::INVOICE_SHOW,
        Permissions::INVOICE_INDEX,
        Permissions::INVOICE_FILE,
        Permissions::INVOICE_PAY,

        Permissions::PQR_LINK_CLIENT,
        Permissions::CLIENT_WORK_ACTIVATION_ORDER


    ],

    // Super admin
    SuperAdmin::class => [
        Permissions::EQUIPMENT_CONFIG,
        Permissions::ADMIN_CREATE,
        Permissions::ADMIN_LINK_EQUIPMENT,
        Permissions::ADMIN_REMOVE_EQUIPMENT,
        Permissions::ADMIN_LINK_EQUIPMENT_TYPE,
        Permissions::ADMIN_EDIT,
        Permissions::ADMIN_DELETE,
        Permissions::ADMIN_SHOW,
        Permissions::ADMIN_ENABLED,
        Permissions::NETWORK_OPERATOR_SERVICE_BAG_CONFIGURATION,
        Permissions::SUPER_ADMIN_ANNUALLY_CLIENT_COST,
        Permissions::SUPER_ADMIN_WIKI_INPUT,


        Permissions::EQUIPMENT_TYPE_CREATE,
        Permissions::EQUIPMENT_TYPE_EDIT,
        Permissions::EQUIPMENT_TYPE_DELETE,
        Permissions::EQUIPMENT_TYPE_SHOW,

        Permissions::EQUIPMENT_CREATE,
        Permissions::EQUIPMENT_EDIT,
        Permissions::EQUIPMENT_DELETE,
        Permissions::EQUIPMENT_REPAIR,
        Permissions::EQUIPMENT_SHOW,

        Permissions::NETWORK_OPERATOR_CREATE,
        Permissions::NETWORK_OPERATOR_EDIT,
        Permissions::NETWORK_OPERATOR_DELETE,
        Permissions::NETWORK_OPERATOR_SHOW,
        Permissions::NETWORK_OPERATOR_LINK_EQUIPMENT,
        Permissions::NETWORK_OPERATOR_REMOVE_EQUIPMENT,
        Permissions::NETWORK_OPERATOR_ENABLED,
        Permissions::NETWORK_OPERATOR_PRICE_CONFIGURATION,

        Permissions::TECHNICIAN_CREATE,
        Permissions::TECHNICIAN_EDIT,
        Permissions::TECHNICIAN_DELETE,
        Permissions::TECHNICIAN_SHOW,
        Permissions::TECHNICIAN_LINK_CLIENT,
        Permissions::TECHNICIAN_ENABLED,
        Permissions::TECHNICIAN_LINK_EQUIPMENT,
        Permissions::TECHNICIAN_REMOVE_EQUIPMENT,

        Permissions::SELLER_CREATE,
        Permissions::SELLER_EDIT,
        Permissions::SELLER_DELETE,
        Permissions::SELLER_SHOW,
        Permissions::SELLER_LINK_CLIENT,

        //Permissions::CLIENT_EDIT,
        Permissions::CLIENT_DELETE,
        Permissions::CLIENT_SHOW,
        Permissions::CLIENT_SHOW_MONITORING,
        Permissions::CLIENT_MONITORING_CONTROL,
        Permissions::CLIENT_SHOW_ALERTS,
        Permissions::CLIENT_SETTINGS,
        Permissions::CLIENT_WORK_ORDER,
        Permissions::CLIENT_SHOW_INVOICING,
        Permissions::INVOICE_REPORT,
        Permissions::CLIENT_INVOICE_GENERATE,

        Permissions::SUPERVISOR_CREATE,
        Permissions::SUPERVISOR_EDIT,
        Permissions::SUPERVISOR_DELETE,
        Permissions::SUPERVISOR_SHOW,
        Permissions::SUPERVISOR_LINK_CLIENT,
        Permissions::SUPERVISOR_ENABLED,

        Permissions::SUPPORT_CREATE,
        Permissions::SUPPORT_EDIT,
        Permissions::SUPPORT_DELETE,
        Permissions::SUPPORT_SHOW,
        Permissions::SUPPORT_LINK_CLIENT,
        Permissions::SUPPORT_ENABLE_PQR,

        Permissions::SUPER_ADMIN_CREATE,
        Permissions::SUPER_ADMIN_EDIT,
        Permissions::SUPER_ADMIN_DELETE,
        Permissions::SUPER_ADMIN_SHOW,
        Permissions::SUPER_ADMIN_ENABLED,

        Permissions::PQR_SHOW,
        Permissions::PQR_CHANGE_LEVEL,
        Permissions::PQR_REPLY,
        Permissions::PQR_REQUEST_CLOSE,
        Permissions::PQR_CLOSE,
        Permissions::PQR_EQUIPMENT_CHANGE,
        Permissions::PQR_EQUIPMENT_CHANGE_MANAGE,

        Permissions::WORK_ORDER_DETAILS,
        Permissions::WORK_ORDER_SHOW,
        Permissions::WORK_ORDER_INDEX,
        Permissions::WORK_ORDER_CREATE,
        Permissions::WORK_ORDER_EDIT,
        Permissions::WORK_ORDER_SOLVE,
        Permissions::WORK_ORDER_IN_PROGRESS,
        Permissions::WORK_ORDER_STOP,

        Permissions::CLIENT_DISABLED_SHOW,
        Permissions::CLIENT_ACTION_DISABLE,
        Permissions::CLIENT_ACTION_ENABLE,

        Permissions::BILLABLE_ITEMS_SHOW,
        Permissions::BILLABLE_ITEMS_INDEX,
        Permissions::BILLABLE_ITEMS_CREATE,
        Permissions::BILLABLE_ITEMS_EDIT,

        Permissions::INVOICE_SHOW,
        Permissions::INVOICE_INDEX,

        Permissions::TAX_SHOW,
        Permissions::TAX_INDEX,
        Permissions::TAX_CREATE,
        Permissions::TAX_EDIT,
        Permissions::INVOICE_FILE,

    ],
    // Operador de red
    NetworkOperator::class => [


        Permissions::NETWORK_OPERATOR_PRICE_CONFIGURATION,
        Permissions::CLIENT_INVOICE_MANUAL_PAYMENT,
        Permissions::EQUIPMENT_CREATE,
        Permissions::EQUIPMENT_EDIT,
        Permissions::EQUIPMENT_SHOW,
        Permissions::EQUIPMENT_REPAIR,
        Permissions::EQUIPMENT_DELETE,

        Permissions::TECHNICIAN_CREATE,
        Permissions::TECHNICIAN_EDIT,
        Permissions::TECHNICIAN_DELETE,
        Permissions::TECHNICIAN_SHOW,
        Permissions::TECHNICIAN_LINK_CLIENT,
        Permissions::TECHNICIAN_LINK_EQUIPMENT,
        Permissions::TECHNICIAN_REMOVE_EQUIPMENT,
        Permissions::TECHNICIAN_ENABLED,

        Permissions::SELLER_CREATE,
        Permissions::SELLER_EDIT,
        Permissions::SELLER_DELETE,
        Permissions::SELLER_SHOW,
        Permissions::SELLER_LINK_CLIENT,
        Permissions::SELLER_MANAGE_PURCHASE,

        Permissions::CLIENT_CREATE,
        Permissions::CLIENT_EDIT,
        Permissions::CLIENT_SHOW,
        Permissions::CLIENT_SHOW_MONITORING,
        Permissions::CLIENT_MONITORING_CONTROL,
        Permissions::CLIENT_SHOW_ALERTS,
        Permissions::CLIENT_SETTINGS,
        Permissions::CLIENT_ADD_EQUIPMENT,
        Permissions::CLIENT_SHOW_INVOICING,
        Permissions::INVOICE_REPORT,
        Permissions::CLIENT_INVOICE_GENERATE,

        Permissions::SUPPORT_CREATE,
        Permissions::SUPPORT_EDIT,
        Permissions::SUPPORT_DELETE,
        Permissions::SUPPORT_SHOW,

        Permissions::SUPERVISOR_CREATE,
        Permissions::SUPERVISOR_EDIT,
        Permissions::SUPERVISOR_DELETE,
        Permissions::SUPERVISOR_SHOW,
        Permissions::SUPERVISOR_LINK_CLIENT,
        Permissions::SUPERVISOR_ENABLED,

        Permissions::PQR_SHOW,
        Permissions::PQR_REPLY,
        Permissions::PQR_CREATE_NETWORK_OPERATOR,
        Permissions::PQR_CLOSE,
        Permissions::PQR_EQUIPMENT_CHANGE_MANAGE,
        Permissions::PQR_CHANGE_LEVEL,
        Permissions::PQR_EQUIPMENT_CHANGE,

        Permissions::WORK_ORDER_DETAILS,
        Permissions::WORK_ORDER_SHOW,
        Permissions::WORK_ORDER_INDEX,
        Permissions::WORK_ORDER_CREATE,
        Permissions::CLIENT_WORK_ORDER,
        Permissions::WORK_ORDER_EDIT,
        Permissions::PQR_LINK_CLIENT,
        Permissions::INVOICE_INDEX,
        Permissions::INVOICE_SHOW,
        Permissions::INVOICE_FILE,
        Permissions::INVOICE_PAY,

    ],
    // Tecnico
    Technician::class => [
        Permissions::CLIENT_SHOW,
        Permissions::CLIENT_SHOW_MONITORING,
        Permissions::CLIENT_MONITORING_CONTROL,
        Permissions::CLIENT_SHOW_ALERTS,
        Permissions::CLIENT_HAND_READING,
        Permissions::CLIENT_HAND_READING_SHOW,
        Permissions::CLIENT_HAND_READING_CREATE,


        Permissions::PQR_SHOW,
        Permissions::PQR_CHANGE_LEVEL,
        Permissions::PQR_REPLY,
        Permissions::PQR_REQUEST_CLOSE,
        Permissions::PQR_EQUIPMENT_CHANGE,
        Permissions::PQR_CLOSE,

        Permissions::WORK_ORDER_DETAILS,
        Permissions::WORK_ORDER_INDEX,
        Permissions::WORK_ORDER_SOLVE,
        Permissions::WORK_ORDER_IN_PROGRESS,
        Permissions::WORK_ORDER_STOP,
        Permissions::PQR_EQUIPMENT_CHANGE_MANAGE,
        Permissions::WORK_ORDER_CREATE,
        Permissions::CLIENT_WORK_ORDER,
        Permissions::WORK_ORDER_SHOW
    ],
    // Soporte
    Support::class => [
        Permissions::CLIENT_SHOW,
        Permissions::CLIENT_SHOW_MONITORING,
        Permissions::CLIENT_MONITORING_CONTROL,
        Permissions::CLIENT_SHOW_ALERTS,
        Permissions::CLIENT_EDIT,

        Permissions::PQR_SHOW,
        Permissions::PQR_CHANGE_LEVEL,
        Permissions::PQR_REPLY,
        Permissions::PQR_REQUEST_CLOSE,
        Permissions::PQR_CLOSE,
        Permissions::PQR_CHANGE_LEVEL,

        Permissions::EQUIPMENT_TYPE_CREATE,
        Permissions::EQUIPMENT_TYPE_EDIT,
        Permissions::EQUIPMENT_TYPE_DELETE,
        Permissions::EQUIPMENT_TYPE_SHOW,

        Permissions::EQUIPMENT_CREATE,
        Permissions::EQUIPMENT_EDIT,
        Permissions::EQUIPMENT_DELETE,
        Permissions::EQUIPMENT_REPAIR,
        Permissions::EQUIPMENT_SHOW,

        Permissions::WORK_ORDER_DETAILS,
        Permissions::WORK_ORDER_SHOW,
        Permissions::WORK_ORDER_INDEX,
        Permissions::WORK_ORDER_CREATE,
        Permissions::WORK_ORDER_SOLVE,
        Permissions::WORK_ORDER_IN_PROGRESS,
        Permissions::WORK_ORDER_STOP,
        Permissions::WORK_ORDER_EDIT,

        Permissions::CLIENT_SETTINGS,

        Permissions::CLIENT_DISABLED_SHOW,
        Permissions::CLIENT_ACTION_ENABLE,
        Permissions::SUPPORT_WORK_ORDER_QUEUE,

        Permissions::SUPPORT_PQR_QUEUE,

        Permissions::PQR_TO_WORK_ORDER,

    ],
    // Vendedor
    Seller::class => [
        Permissions::CLIENT_SHOW,
        Permissions::CLIENT_SHOW_MONITORING,
        Permissions::CLIENT_MONITORING_CONTROL,
        Permissions::CLIENT_SHOW_ALERTS,
        Permissions::SELLER_MANAGE_PURCHASE,
        Permissions::SELLER_MANAGE_PURCHASE_CREATE,

        Permissions::PQR_SHOW,
        Permissions::PQR_CHANGE_LEVEL,
        Permissions::PQR_REPLY,
        Permissions::PQR_REQUEST_CLOSE,
        Permissions::PQR_CLOSE,
        Permissions::PQR_CHANGE_LEVEL,
    ],
    // Supervisor
    Supervisor::class => [
        Permissions::CLIENT_SHOW,
        Permissions::CLIENT_SHOW_MONITORING,
        Permissions::CLIENT_INVOICE_GENERATE,
        Permissions::CLIENT_SHOW_ALERTS,
        Permissions::PQR_CREATE,
        Permissions::PQR_SHOW,
        Permissions::PQR_REPLY,
        Permissions::PQR_CLOSE,

    ]
];
