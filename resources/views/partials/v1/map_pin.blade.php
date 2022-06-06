<script>
    function myMap() {
        var center = {lat: {{$latitude}}, lng: {{$longitude}}};
        var mapProp = {
            center: center,
            zoom: 14,
            streetViewControl: false,
            mapTypeControlOptions: {
                mapTypeIds: []
            },
            mapTypeId: google.maps.MapTypeId.ROADMAP
        };
        var map = new google.maps.Map(document.getElementById("googleMap"), mapProp);

        const marker = new google.maps.Marker({
            position: center,
            map: map,
            mapTypeControl: false,
            draggable: false
        });


    }
</script>


<div wire:ignore id="googleMap" style="width:100%;height:400px;border-color: teal;border-width: 2px"></div>

<script
    src="https://maps.googleapis.com/maps/api/js?key={{config("google.apiKey")}}&callback=myMap&libraries=places"></script>


