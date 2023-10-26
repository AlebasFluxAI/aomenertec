<div>
    <div style="background-color: #f2f2f2;padding: 15px;border-radius: 15px;margin-bottom: 5%">
        @include("layouts.menu.v1.header_menu_password")
        <br>

        <div class="section-title my-5">
            <h3 class="text-center p3"><b><span class="fas fa-ticket"></span>Pago de facturas de cliente</b>
            </h3>
        </div>
        <div class="contenedor-grande">
            @include("partials.v2.table.primary-table",[
             "table_rows"=>$data,
             "table_headers"=>[
                [
                     "col_name" =>"ID",
                     "col_data" =>"id",
                     "col_filter"=>true
                 ],
                 [
                     "col_name" =>"Codigo",
                     "col_data" =>"code",
                     "col_filter"=>true
                 ],
                 [
                     "col_name" =>"Tipo de factura",
                     "col_data" =>"type",
                     "col_translate"=>"invoice",
                     "col_filter"=>true
                 ],
                 [
                     "col_name" =>"Estado de pago",
                     "col_data" =>"payment_status",
                     "col_translate"=>"invoice",
                     "col_filter"=>false
                 ],
                 [
                     "col_name" =>"Total",
                     "col_data" =>"total",
                     "col_filter"=>false,
                     "col_money"=>true,
                     "col_currency"=>"currency"
                 ],
                 [
                     "col_name" =>"Moneda",
                     "col_data" =>"currency",
                     "col_filter"=>false,
                 ],
                 ],
                   "table_actions"=>[
                              "customs"=>[
                                              [
                                                 "redirect"=>[
                                                             "route"=>"guest.invoice-details-payment",
                                                             "binding"=>"invoice"
                                                       ],
                                                     "icon"=>"fas fa-search",
                                                     "tooltip_title"=>"Detalles",
                                                     "permission"=>[\App\Http\Resources\V1\Permissions::INVOICE_SHOW],
                                               ],
                                                 [
                                                   "redirect"=>[
                                                               "route"=>"administrar.v1.facturacion.invitados.facturas.pdf",
                                                               "binding"=>"invoice"
                                                         ],
                                                       "icon"=>"fas fa-file-pdf",
                                                       "tooltip_title"=>"Descargar PDF",
                                                       "permission"=>[\App\Http\Resources\V1\Permissions::INVOICE_FILE],
                                                 ],
                                               [
                                                       "payment_button"=>true,
                                                       "icon"=>"fas fa-download",
                                                       "tooltip_title"=>"Pagar factura",
                                                       "permission"=>[\App\Http\Resources\V1\Permissions::INVOICE_FILE],
                                                 ],
                                          ],
                                   ],

         ])
        </div>
    </div>
</div>
