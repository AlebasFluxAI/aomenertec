@foreach(explode(",",str_replace(array('[', ']','"'), '', $client_report_variables)) as $variable)
    <p>{{\App\Models\V1\Client::getReportVariableFromId($variable)}}</p>
@endforeach

