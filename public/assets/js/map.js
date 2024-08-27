(function () {
  // Initialize the map
  let map = new L.map("map", {
    center: [47.798, 1.351],
    zoom: 6,
  });

  let layer = new L.TileLayer(
    "http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png"
  );
  map.addLayer(layer);

  // Retrieve saved map state from localStorage
  const savedMapState = JSON.parse(localStorage.getItem("mapState"));

  if (savedMapState) {
    if (
      savedMapState.center &&
      savedMapState.center.lat &&
      savedMapState.center.lng
    ) {
      map.setView(
        [savedMapState.center.lat, savedMapState.center.lng],
        savedMapState.zoom
      );
    }
  }

  // Get events data from the data attribute by using data-events attribute
  const mapDataElement = document.getElementById("map-data");
  if (mapDataElement) {
    const events = JSON.parse(mapDataElement.getAttribute("data-events"));

    events.forEach((event) => {
      let marker = new L.Marker([event.latitude, event.longitude]);
      marker.addTo(map).bindPopup(
        `<a href="${event.link}">${event.name}</a>
                <p>${event.paf}€</p>`
      );
    });
  }

  // Save map state when moved
  function saveMapState() {
    const mapState = {
      center: map.getCenter(),
      zoom: map.getZoom(),
    };
    localStorage.setItem("mapState", JSON.stringify(mapState));
  }

  // Call saveMapState whenever the map view changes
  map.on("moveend", saveMapState);
  map.on("zoomend", saveMapState);
})();
