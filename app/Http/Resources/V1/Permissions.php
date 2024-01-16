<?php

namespace App\Http\Resources\V1;

class Permissions
{
    public const SUPER_ADMIN_CREATE = "super_admin.create";
    public const SUPER_ADMIN_EDIT = "super_admin.edit";
    public const SUPER_ADMIN_DELETE = "super_admin.delete";
    public const SUPER_ADMIN_SHOW = "super_admin.show";
    public const SUPER_ADMIN_ENABLED = "super_admin.enabled";
    public const SUPER_ADMIN_WIKI_INPUT = "super_admin.wiki_input";
    public const SUPER_ADMIN_ANNUALLY_CLIENT_COST = "super_admin.annually.client.cost";

    public const ADMIN_CREATE = "admin.create";
    public const ADMIN_EDIT = "admin.edit";
    public const ADMIN_DELETE = "admin.delete";
    public const ADMIN_SHOW = "admin.show";
    public const ADMIN_ENABLED = "admin.enabled";

    public const ADMIN_LINK_EQUIPMENT_TYPE = "admin.link_equipment_type";
    public const ADMIN_LINK_EQUIPMENT = "admin.link_equipment";
    public const ADMIN_REMOVE_EQUIPMENT = "admin.remove_equipment";
    public const ADMIN_REMOVE_EQUIPMENT_TYPE = "admin.remove_equipment_type";
    public const ADMIN_LINK_CLIENT = "admin.link_client";
    public const ADMIN_REMOVE_CLIENT = "admin.remove_client";
    public const ADMIN_MONITORING = "admin.monitoring";
    public const ADMIN_SETTING_CLIENT = "admin.setting_client";
    public const CLIENT_ADD_EQUIPMENT = "client_add_equipment";
    public const CLIENT_SHOW_ALERTS = "client_show_alerts";
    public const CLIENT_WORK_ORDER = "client_work_order";
    public const CLIENT_WORK_ACTIVATION_ORDER = "client_activation_work_order";
    public const CLIENT_INVOICE_GENERATE = "client_invoice_generate";
    public const CLIENT_INVOICE_MANUAL_PAYMENT = "client_invoice_manual_payment";

    public const EQUIPMENT_CREATE = "equipment.create";
    public const EQUIPMENT_EDIT = "equipment.edit";
    public const EQUIPMENT_DELETE = "equipment.delete";
    public const EQUIPMENT_REPAIR = "equipment.repair";
    public const EQUIPMENT_SHOW = "equipment.show";
    public const EQUIPMENT_CONFIG = "equipment.config";

    public const EQUIPMENT_TYPE_CREATE = "equipment_type.create";
    public const EQUIPMENT_TYPE_EDIT = "equipment_type.edit";
    public const EQUIPMENT_TYPE_DELETE = "equipment_type.delete";
    public const EQUIPMENT_TYPE_SHOW = "equipment_type.show";


    public const NETWORK_OPERATOR_CREATE = "network_operator.create";
    public const NETWORK_OPERATOR_EDIT = "network_operator.edit";
    public const NETWORK_OPERATOR_DELETE = "network_operator.delete";
    public const NETWORK_OPERATOR_SHOW = "network_operator.show";
    public const NETWORK_OPERATOR_LINK_EQUIPMENT = "network_operator.link_equipment";
    public const NETWORK_OPERATOR_REMOVE_EQUIPMENT = "network_operator.remove_equipment";
    public const NETWORK_OPERATOR_ENABLED = "admin.enabled";

    public const TECHNICIAN_CREATE = "technician.create";
    public const TECHNICIAN_EDIT = "technician.edit";
    public const TECHNICIAN_DELETE = "technician.delete";
    public const TECHNICIAN_SHOW = "technician.show";
    public const TECHNICIAN_LINK_CLIENT = "technician.link_client";
    public const TECHNICIAN_LINK_EQUIPMENT = "technician.link_equipment";
    public const TECHNICIAN_REMOVE_EQUIPMENT = "technician.remove_equipment";
    public const TECHNICIAN_ENABLED = "technician.enabled";

    public const SUPPORT_CREATE = "support.create";
    public const SUPPORT_EDIT = "support.edit";
    public const SUPPORT_WORK_ORDER_QUEUE = "support.work_order_queue";
    public const SUPPORT_DELETE = "support.delete";
    public const SUPPORT_SHOW = "support.show";
    public const SUPPORT_LINK_CLIENT = "support.link_client";
    public const SUPPORT_ENABLE_PQR = "support.enable_pqr";
    public const SUPPORT_ENABLED = "support.enabled";


    public const SUPERVISOR_CREATE = "supervisor.create";
    public const SUPERVISOR_EDIT = "supervisor.edit";
    public const SUPERVISOR_DELETE = "supervisor.delete";
    public const SUPERVISOR_SHOW = "supervisor.show";
    public const SUPERVISOR_LINK_CLIENT = "supervisor.link_client";
    public const SUPERVISOR_ENABLED = "supervisor.enabled";

    public const SELLER_CREATE = "seller.create";
    public const SELLER_EDIT = "seller.edit";
    public const SELLER_DELETE = "seller.delete";
    public const SELLER_SHOW = "seller.show";
    public const SELLER_LINK_CLIENT = "seller.link_client";
    public const SELLER_ENABLED = "seller.enabled";
    public const SELLER_MANAGE_PURCHASE = "seller.manage_purchase";
    public const SELLER_MANAGE_PURCHASE_CREATE = "seller.manage_purchase_create";
    public const SELLER_PURCHASE_HISTORICAL = "seller.purchase_historical";

    public const CLIENT_CREATE = "client.create";
    public const CLIENT_EDIT = "client.edit";
    public const CLIENT_DELETE = "client.delete";
    public const CLIENT_SHOW = "client.show";
    public const CLIENT_SHOW_MONITORING = "client.show_monitoring";
    public const CLIENT_SHOW_INVOICING = "client.show_invoicing";
    public const CLIENT_HAND_READING = "client.hand_reading";
    public const CLIENT_HAND_READING_SHOW = "client.hand_reading_show";
    public const CLIENT_HAND_READING_CREATE = "client.hand_reading_create";
    public const CLIENT_SETTINGS = "client.settings";
    public const CLIENT_ENABLED = "client.enabled";
    public const CLIENT_ACTION_ENABLE = "client.action.enable";
    public const CLIENT_ACTION_DISABLE = "client.action.disable";
    public const CLIENT_MONITORING_CONTROL = "client.monitoring.control";

    public const CLIENT_DISABLED_SHOW = "client-disabled.show";

    public const PQR_SHOW = "pqr_show";
    public const PQR_CHANGE_LEVEL = "pqr_change_level";
    public const PQR_REQUEST_CLOSE = "pqr_request_close";
    public const PQR_REPLY = "pqr_reply";
    public const PQR_CREATE = "pqr_create";
    public const PQR_CREATE_NETWORK_OPERATOR = "pqr_create_network_operator";
    public const PQR_CLOSE = "pqr_close";
    public const PQR_EQUIPMENT_CHANGE = "pqr_equipment_change";
    public const PQR_TO_WORK_ORDER = "pqr_convert_work_order";
    public const PQR_EQUIPMENT_CHANGE_MANAGE = "pqr_equipment_change_manage";
    public const PQR_LINK_CLIENT = "pqr_link_client";
    public const SUPPORT_PQR_QUEUE = "support.pqr_queue";


    public const NETWORK_OPERATOR_PRICE_CONFIGURATION = "network_operator_price_configuration";
    public const NETWORK_OPERATOR_SERVICE_BAG_CONFIGURATION = "network_operator_service_bag_configuration";

    public const WORK_ORDER_CREATE = "work_order_create";
    public const WORK_ORDER_SHOW = "work_order_show";
    public const WORK_ORDER_EDIT = "work_order_edit";
    public const WORK_ORDER_DETAILS = "work_order_details";
    public const WORK_ORDER_INDEX = "work_order_index";
    public const WORK_ORDER_SOLVE = "work_order_solve";
    public const WORK_ORDER_IN_PROGRESS = "work_order_in_progress";
    public const WORK_ORDER_STOP = "work_order_stop";

    public const BILLABLE_ITEMS_SHOW = "billable_items_show";
    public const BILLABLE_ITEMS_INDEX = "billable_items_index";
    public const BILLABLE_ITEMS_CREATE = "billable_items_create";
    public const BILLABLE_ITEMS_EDIT = "billable_items_edit";

    public const TAX_SHOW = "tax_show";
    public const TAX_INDEX = "tax_index";
    public const TAX_CREATE = "tax_create";
    public const TAX_EDIT = "tax_edit";

    public const INVOICE_SHOW = "invoice_show";
    public const INVOICE_INDEX = "invoice_index";
    public const INVOICE_REPORT = "invoice_report";
    public const INVOICE_PAY = "invoice_pay";
    public const INVOICE_FILE = "invoice_file";
}
