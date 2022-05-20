<div class="divider-2 mb-4"></div>


<div class="row contenedor-grande">
    <div class="col-md-12">
        <p id="help-icon-form-p">
            <i
                data-toggle="tooltip" data-placement="top"
                title="Dentro de este modulo es posible asignar un listado de equipos a un administador, primero debe seleccionar un tipo de equipo, luego un equipo
                utilizando el ingreso de texto sobre la tabla"
                class="fas fa-circle-question" id="help-icon-form-i"></i>
        </p>
    </div>
    @include("partials.v1.form.primary_form",[
          "form_toast"=>false,
          "session_message"=>"message",
          "form_submit_action"=>"submitForm",
          "form_submit_action_text"=>"Asociar equipos",
          "form_inputs"=>[
                              [
                                "input_type"=>"dropdown",
                                "label_text"=>"1. Seleccione un tipo de equipo",
                                "icon_class"=>"fas fa-desktop",
                                "dropdown_editing"=>false,
                                "dropdown_refresh"=>"pass",
                                "placeholder"=>"Seleccione el tipo de equipo",
                                "col_with"=>12,
                                "dropdown_model"=>"equipmentTypeId",
                                "dropdown_values"=>$equipmentTypes,

                          ]

                       ]
               ])

    @include("partials.v1.form.form_input_icon",[
                                   "input_model"=>"equipmentFilter",
                                   "input_field"=>$form_input["input_field"]??"",
                                   "input_type"=>"text",
                                   "icon_class"=>"fas fa-desktop",
                                   "placeholder"=>"Ingrese nombre o serial del equipo",
                                   "col_with"=>12,
                                   "required"=>true,
                                   "input_rows"=>0,
                                   "input_enabled"=>$equipmentTypeId!=null
                              ])
    <p>Equipos seleccionados: {{count($selectedRows)}}</p>
    <div class="divider-1 mb-2"></div>
    @include("partials.v2.table.primary-table",[
                "table_pageable"=>false,
                "table_checkable"=>true,
                "table_empty_text"=>"No existen equipos disponibles de este tipo",
                "table_headers"=>[
                        [
                            "col_name" =>"ID",
                            "col_data" =>"id",
                            "col_filter"=>false
                        ],
                        [
                            "col_name" =>"Nombre",
                            "col_data" =>"name",
                            "col_filter"=>false
                        ],
                           [
                            "col_name" =>"Serial",
                            "col_data" =>"serial",
                            "col_filter"=>false
                        ],
                          [
                            "col_name" =>"Tipo",
                            "col_data" =>"equipmentType.type",
                            "col_filter"=>false
                        ],
                        [
                            "col_name" =>"Descripcion",
                            "col_data" =>"description",
                            "col_filter"=>false
                        ],
                        [
                            "col_name" =>"Asignado",
                            "col_data" =>"assigned",
                            "col_filter"=>false,
                            "col_type"=>\App\Http\Resources\V1\ColTypeEnum::COL_TYPE_BOOLEAN
                        ],
                 ],


                                                 /* Le dice al componente tabla las acciones que tendra la columna de acciones en la tabla [
                                                 _edit_button=>{ruta para redireccionar a edicion}
                                                 _delete_button => {boton de borrado, siempre tomando como identificador la primera colunma de la tabla - ID}
                                                   ]*/
                "table_rows"=>$equipmentBachelors

            ])
</div>
