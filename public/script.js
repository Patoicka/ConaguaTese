var map = L.map("map").setView([19.432608, -99.133209], 13); // CDMX por defecto
// Agregar marcador arrastrable
var marker = L.marker([19.432608, -99.133209], { draggable: true }).addTo(map);

setMap();

//cargamos el geoJson de Mexico, al incio
setMarker();
loadStates();

loadMexicoLayer();

function loadMexicoLayer() {
    fetch(`/geojson/mexico`)
        .then((response) => response.json())
        .then((data) => {
            mexicoLayer = L.geoJSON(data, {
                style: {
                    color: "blue",
                    weight: 2,
                    fillOpacity: 0,
                },
            }).addTo(map);
        });
}

let currentGeojsonData;

function loadGeoJSON(nombre) {
    fetch(`geojson/${nombre}.geojson`)
        .then((response) => response.json())
        .then((data) => {
            if (!mexicoLayer !== "undefined") {
                map.removeLayer(mexicoLayer);
            }

            if (!geojsonData !== "undefined") {
                map.removeLayer(geojsonData);
            }
            // Agregar el nuevo GeoJSON al mapa
            geojsonData = L.geoJSON(data, {
                style: {
                    color: "blue",
                    weight: 2,
                    fillOpacity: 0,
                },
            }).addTo(map);

            currentGeojsonData = geojsonData;
        });
}

function setMap() {
    var MexicoBounds = L.latLngBounds(
        L.latLng(14.532, -118.455), //El limimte suroeste
        L.latLng(32.718, -86.703) //El limite noroeste
    );

    map.setMaxBounds(MexicoBounds);

    // Agregar capa de OpenStreetMap

    L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
        attribution: "&copy; OpenStreetMap contributors",
    }).addTo(map);

    map.on("click", function (e) {
        var latlng = e.latlng; // Obtiene la latitud y longitud del clic

        var insideMexico = isInsideMexicoBounds(latlng);

        //si el marcador esta dentro de mexico
        if (insideMexico) {
            // guardamos la latitud y la longitud
            document.getElementById("lati").value = latlng.lat.toFixed(6);
            document.getElementById("long").value = latlng.lng.toFixed(6);
            marker.setLatLng(latlng);
        } else {
            alert("El marcador no puede salir de México.");
        }
    });
}

function setMarker() {
    let lastValidPosition = marker.getLatLng();

    // Evento para actualizar latitud y longitud cuando el usuario mueve el marcador
    marker.on("dragend", function () {
        var newPosition = marker.getLatLng();

        var insideMexico = isInsideMexicoBounds(newPosition);

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
}

//esta funcion verifica si un punto esta dentro de de un area valida del geojson de mexico
function isInsideMexicoBounds(newPosition) {
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
    fetch(
        `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(
            address
        )}`
    )
        .then((response) => response.json())
        .then((data) => {
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
        .catch((error) => {
            console.error("Error en la búsqueda:", error);
            alert("Hubo un error al buscar la dirección.");
        });
});

$("#estadoSelect").on("change", function () {
    var nombreEstado = $(this).val().toLowerCase();

    $.getJSON(`/geojson/${nombreEstado}.geojson`, function (data) {
        // Remover capas anteriores
        if (typeof estadoLayer !== "undefined") {
            map.removeLayer(estadoLayer);
        }

        // Agregar nueva capa GeoJSON
        estadoLayer = L.geoJSON(data, {
            style: {
                color: "blue",
                weight: 2,
                fillOpacity: 0,
            },
        }).addTo(map);

        // Ajustar vista al nuevo estado
        map.fitBounds(estadoLayer.getBounds());
    }).fail(function () {
        alert("No se encontró el estado en la base de datos.");
    });
});

function loadStates() {
    let comboState = document.getElementById("estadoSelect");

    const estados = [
        "AGUASCALIENTES",
        "BAJA CALIFORNIA",
        "BAJA CALIFORNIA SUR",
        "CAMPECHE",
        "CHIAPAS",
        "CHIHUAHUA",
        "CDMX",
        "COAHUILA",
        "COLIMA",
        "DURANGO",
        "GUANAJUATO",
        "GUERRERO",
        "HIDALGO",
        "JALISCO",
        "ESTADO DE MEXICO",
        "MICHOACAN",
        "MORELOS",
        "NAYARIT",
        "NUEVO LEON",
        "OAXACA",
        "PUEBLA",
        "QUERETARO",
        "QUINTANA ROO",
        "SAN LUIS POTOSI",
    ];

    estados.forEach((state) => {
        let newOption = document.createElement("option");
        newOption.text = state;
        comboState.appendChild(newOption);
    });

    comboState.addEventListener("change", function () {
        const name = this.value.toLowerCase();
        console.log(name);
        loadGeoJSON(name);
    });
}
