<label>{{$input_label??""}}</label>


<script>
    let autocomplete;
    $("#latitude").val('Wilder');

    function myMap() {

        var center = {lat: 4.134750, lng: -73.637094};
        var mapProp = {
            center: center,
            zoom: 14,
            streetViewControl: false,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        };
        var map = new google.maps.Map(document.getElementById("googleMap"), mapProp);

        const marker = new google.maps.Marker({
            position: center,
            map: map,
            mapTypeControl: false,
            draggable: true
        });


        autocomplete = new google.maps.places.Autocomplete(
            document.getElementById("autocomplete"),
            {
                types: ["address"],
                componentRestrictions: {'country': ['CO']},
                fields: ['place_id', 'geometry', 'name']
            }
        );

        google.maps.event.addListener(marker, 'dragend', function (evt) {
            $("#autocomplete").val('');
            map.panTo(evt.latLng);
            updateLocation(evt.latLng.lat().toFixed(6), evt.latLng.lng().toFixed(6));
        });


        autocomplete.addListener('place_changed', function () {
            marker.setVisible(false);
            const place = autocomplete.getPlace();
            if (!place.geometry) {
                window.alert('No details available for input: \'' + place.name + '\'');
                return;
            }
            updateLocation(place.geometry.location.lat().toFixed(6), place.geometry.location.lng().toFixed(6));
            renderAddress(place);
            fillInAddress(place);
        });

        function fillInAddress(place) {  // optional parameter
            const addressNameFormat = {
                'street_number': 'short_name',
                'route': 'long_name',
                'locality': 'long_name',
                'administrative_area_level_1': 'short_name',
                'country': 'long_name',
                'postal_code': 'short_name',
            };
            const getAddressComp = function (type) {
                for (const component of place.address_components) {
                    if (component.types[0] === type) {
                        return component[addressNameFormat[type]];
                    }
                }
                return '';
            };


        }

        function renderAddress(place) {
            map.setCenter(place.geometry.location);
            marker.setPosition(place.geometry.location);
            marker.setVisible(true);
        }

        function updateLocation(latitude, longitude) {
        @this.latitude
            = latitude;
        @this.longitude
            = longitude;
        }


    }
</script>


<div class="col-md-8 mb-3">
    <input class="form-control" id="autocomplete" type="text" placeholder="Ingrese una direccion (Opcional)"/>
</div>


<div wire:ignore id="googleMap" style="width:100%;height:400px;border-color: teal;border-width: 2px"></div>

<div class="col-md-8 mb-3">
    <p><b>Coordenadas:</b></p>
    <ul>
        <li> Latitude: {{$latitude}}</li>
        <li> Longitude: {{$longitude}}</li>
    </ul>
</div>

<script
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDnwQaw-Tq9Z7BNTOCtGXJTdnnkj32z5jA&callback=myMap&libraries=places"></script>


