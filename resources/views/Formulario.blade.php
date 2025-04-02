<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario Incidencia</title>
    
    <!-- Estilos CSS -->
    <link rel="stylesheet" href="{{ asset('style1.css') }}">

    <!-- Leaflet.js (Mapa OpenStreetMap) -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

    <!--libreria para poder verificar si un punto esta dentro de un area definida por un geojson-->
    <script src="https://unpkg.com/leaflet-pip/leaflet-pip.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <form action="{{ route('guardarFormulario') }}" method="POST">
        @csrf
        <section class="form-register">
            <h3>Formulario de consulta</h3>

            <!-- Mapa -->
            <div id="map"></div>

            <!-- Barra de búsqueda y botón -->
            <div class="search-container">
                <input class="controls" type="text" id="searchBox" placeholder="Buscar dirección o lugar...">
                <button type="button" id="searchButton">Buscar</button>
            </div>  

            <input class="controls" type="text" name="latitud" id="lati" placeholder="Latitud" readonly>
            <input class="controls" type="text" name="longitud" id="long" placeholder="Longitud" readonly>

            <h2>Seleccione una opción:</h2>
            <select class="controls" name="opciones" id="opciones">
                <option value="Falta de agua">Falta de agua</option>
                <option value="Solicitud de pipa">Solicitud de pipa</option>
                <option value="Fuga de agua">Fuga de agua</option>
                <option value="Agua contaminada">Agua contaminada</option>
                <option value="Falta tapa en caja de válvula">Falta tapa en caja de válvula</option>
                <option value="Desbordamiento de aguas negras">Desbordamiento de aguas negras</option>
                <option value="Coladera sin tapa">Coladera sin tapa</option>
                <option value="Socavón / Hundimiento">Socavón / Hundimiento</option>
                <option value="Inundación / Encharcamiento">Inundación / Encharcamiento</option>
                <option value="Drenaje tapado /coladera / Tubería">Drenaje tapado /coladera / Tubería</option>
                <option value="Tomas Clandestinas">Tomas Clandestinas</option>
            </select>

            <input class="controls" type="text" name="municipio" id="muni" placeholder="Ingrese el municipio">

            <input class="botons" type="submit" value="Enviar">
        </section>
    </form>

    <!-- Script para manejar el mapa y la búsqueda -->
    <script>
        var map = L.map('map').setView([19.432608, -99.133209], 13); // CDMX por defecto

        var MexicoBounds = L.latLngBounds(
            L.latLng(14.532, -118.455), //El limimte suroeste
            L.latLng(32.718, -86.703) //El limite noroeste
        );

        map.setMaxBounds(MexicoBounds);


        //cargamos el geoJson de Mexico
        $.getJSON("/geojson/mexico", function(geojsonData) {
            //se agrega una linea al rededor del pais que delimita el terreno
            mexicoLayer = L.geoJSON(geojsonData, {
                style: {
                    color: "blue",
                    weight: 2,
                    fillOpacity: 0 //este campo se usa para mostrar una opacidad dentro del pais
                }
            }).addTo(map);

            // Ajustar el mapa a los límites de México
            map.fitBounds(mexicoLayer.getBounds());
        });


        // Agregar capa de OpenStreetMap
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        // Agregar marcador arrastrable
        var marker = L.marker([19.432608, -99.133209], { draggable: true }).addTo(map);

        let lastValidPosition = marker.getLatLng();

        // Evento para actualizar latitud y longitud cuando el usuario mueve el marcador
        marker.on('dragend', function () {

            var newPosition = marker.getLatLng();

            var insideMexico = isInsideMexicoBounds(newPosition)

            //si el marcador esta dentro de mexico
            if (insideMexico) {
               
                // guardamos la latitud y la longitud
                document.getElementById("lati").value = newPosition.lat.toFixed(6);
                document.getElementById("long").value = newPosition.lng.toFixed(6);

                lastValidPosition = newPosition; // Guardamos la nueva posición válida
            } else {
                alert("El marcador no puede salir de México.");
                marker.setLatLng(lastValidPosition); 
                // Regresamos el marcador a su última posición válida, para poder regresar el marcador a una area valida
            }
        
        });

        //esta funcion verifica si un punto esta dentro de de un area valida del geojson de mexico
        function isInsideMexicoBounds(newPosition){

              //leafletPip.pointInLayer retorna un array con la informacion sobre el poligono donde esta el punto si es que se encontro
              var insideMexico = leafletPip.pointInLayer(
                [newPosition.lng, newPosition.lat], //tomamos las posiciones del marcador
                mexicoLayer, // mandamos la capa que creamos para delimitar el area, ahi es donde se buscara
                true // este valor boolean indica si se debe de verificar dentro de poligonos multiples, en caso de que haya
            );

            // retornamos si hay informacion en el array (si el punto esta dentro de mexico)
            return insideMexico.length > 0;
        }

        // Evento de búsqueda de dirección
        document.getElementById("searchButton").addEventListener("click", function () {
            var address = document.getElementById("searchBox").value + " Mexico";
            if (address.trim() === "") {
                alert("Por favor, ingrese una dirección o lugar.");
                return;
            }

            // Llamada a la API de OpenStreetMap (Nominatim)
            fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(address)}`)
                .then(response => response.json())
                .then(data => {
                    if (data.length > 0) {
                        var latlng = [parseFloat(data[0].lat), parseFloat(data[0].lon)];
                        map.setView(latlng, 16);
                        marker.setLatLng(latlng);
                        document.getElementById("lati").value = latlng[0].toFixed(6);
                        document.getElementById("long").value = latlng[1].toFixed(6);

                        console.log(data);

                        // Habilitar arrastre después de la búsqueda
                        marker.dragging.enable();
                    } else {
                        alert("Dirección no encontrada. Intente con otra.");
                    }
                })
                .catch(error => {
                    console.error("Error en la búsqueda:", error);
                    alert("Hubo un error al buscar la dirección.");
                });
        });
    </script>
</body>
</html>
