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

            <select id="estadoSelect" class="controls">
            </select>



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

</body>


    <!-- Script para manejar el mapa y la búsqueda -->
    <script src="{{ asset("script.js") }}"></script>
</html>
