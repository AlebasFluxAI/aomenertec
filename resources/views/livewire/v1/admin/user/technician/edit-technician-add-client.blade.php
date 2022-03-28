@include("partials.v1.form.form_dropdown_input_searchable",[
                   "col_with" =>$col_with,
                   "icon_class" =>$icon_class,
                   "dropdown_model" =>$dropdown_model,
                   "placeholder" =>$placeholder,
                   "required" =>$required,
                   "picked_variable" =>$picked_variable,
                   "message_variable" =>$message_variable,
                   "dropdown_results" =>$dropdown_results,
                   "selected_value_function" =>$selected_value_function,
                   "dropdown_result_id" =>$dropdown_result_id,
                   "dropdown_result_value" =>$dropdown_result_value,
                   "count_bool" =>$count_bool,

         ])

@include("partials.v1.table.primary-table", [
                        "table_pageable"=>$table_pageable,
                        "table_headers"=>$table_headers,
                        "table_actions"=>$table_actions,
                        "table_rows"=>$table_rows

         ])
