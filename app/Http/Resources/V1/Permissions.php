<?php

namespace App\Http\Resources\V1;

use App\Events\UserNotificationEvent;
use ArrayAccess;

class Permissions
{
    public const ADMIN_CREATE = "admin.create";
    public const ADMIN_EDIT = "admin.edit";
    public const ADMIN_DELETE = "admin.delete";
    public const ADMIN_SHOW = "admin.show";
    public const ADMIN_LINK_EQUIPMENT_TYPE = "admin.link_equipment_type";
    public const ADMIN_LINK_EQUIPMENT = "admin.link_equipment";
    public const ADMIN_REMOVE_EQUIPMENT = "admin.remove_equipment";
    public const ADMIN_REMOVE_EQUIPMENT_TYPE = "admin.remove_equipment_type";
    public const ADMIN_LINK_CLIENT = "admin.link_client";
    public const ADMIN_REMOVE_CLIENT = "admin.remove_client";
    public const ADMIN_MONITORING = "admin.monitoring";
    public const ADMIN_SETTING_CLIENT = "admin.setting_client";

    public const EQUIPMENT_CREATE = "equipment.create";
    public const EQUIPMENT_EDIT = "equipment.edit";
    public const EQUIPMENT_DELETE = "equipment.delete";
    public const EQUIPMENT_SHOW = "equipment.show";

    public const EQUIPMENT_TYPE_CREATE = "equipment_type.create";
    public const EQUIPMENT_TYPE_EDIT = "equipment_type.edit";
    public const EQUIPMENT_TYPE_DELETE = "equipment_type.delete";
    public const EQUIPMENT_TYPE_SHOW = "equipment_type.show";


    public const NETWORK_OPERATOR_CREATE = "network_operator.create";
    public const NETWORK_OPERATOR_EDIT = "network_operator.edit";
    public const NETWORK_OPERATOR_DELETE = "network_operator.delete";
    public const NETWORK_OPERATOR_SHOW = "network_operator.show";
    public const NETWORK_OPERATOR_LINK_EQUIPMENT = "network_operator.link_equipment";

    public const TECHNICIAN_CREATE = "technician.create";
    public const TECHNICIAN_EDIT = "technician.edit";
    public const TECHNICIAN_DELETE = "technician.delete";
    public const TECHNICIAN_SHOW = "technician.show";
    public const TECHNICIAN_LINK_CLIENT = "technician.link_client";
    public const TECHNICIAN_LINK_EQUIPMENT = "technician.link_equipment";

    public const SELLER_CREATE = "seller.create";
    public const SELLER_EDIT = "seller.edit";
    public const SELLER_DELETE = "seller.delete";
    public const SELLER_SHOW = "seller.show";
    public const SELLER_LINK_CLIENT = "seller.link_client";

    public const CLIENT_CREATE = "client.create";
    public const CLIENT_EDIT = "client.edit";
    public const CLIENT_DELETE = "client.delete";
    public const CLIENT_SHOW = "client.show";
    public const CLIENT_SHOW_MONITORING = "client.show_monitoring";
    public const CLIENT_SETTINGS = "client.settings";


}
