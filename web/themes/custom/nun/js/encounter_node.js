(function ($, Drupal) {
  $(document).ready(function() {
    //retrieve the lat/long values for this encounter node from the settings set in the preprocess
    var latitude = drupalSettings.encounter_geolocation.lat;
    var longitude = drupalSettings.encounter_geolocation.lng;

    // Build the little map icon
    var miniMap = L.map("mini-map", null, {zoomControl: false, dragging: false}).setView([latitude, longitude], 13);
    L.tileLayer("http://server.arcgisonline.com/ArcGIS/rest/services/world_topo_map/MapServer/tile/{z}/{y}/{x}.png", {
      attribution: "",
      dragging: false
    }).addTo(miniMap);
    L.marker([latitude, longitude]).addTo(miniMap);
    miniMap.touchZoom.disable();
    miniMap.doubleClickZoom.disable();
    miniMap.scrollWheelZoom.disable();
    miniMap.boxZoom.disable();
    miniMap.keyboard.disable();
    miniMap.dragging.disable();
    var enlargeButton = L.control({position: "bottomright"});
    enlargeButton.onAdd = function (miniMap){
      var div = L.DomUtil.create("div", "magnify");
      div.innerHTML = "<i class=\'icon-large icon-zoom-in\' style=\'display:none;\'>&nbsp;</i>";
      return div;
    }
    enlargeButton.addTo(miniMap);
    $("#mini-map").hover(
      function(){ $(".magnify i").show(); },
      function(){ $(".magnify i").hide(); }
    );

    // Build the big map.
    var bigMap = L.map("big-map").setView([latitude, longitude], 13);
    L.tileLayer("http://server.arcgisonline.com/ArcGIS/rest/services/world_topo_map/MapServer/tile/{z}/{y}/{x}.png", {
      attribution: "Tiles © <a href=\"http://esri.com\" target=\"_blank\">Esri</a>"
    }).addTo(bigMap);
    L.marker([latitude, longitude]).addTo(bigMap);
    var collapseButton = L.control({position: "topright"});
    collapseButton.onAdd = function (bigMap){
      var div = L.DomUtil.create("div", "collapse-me");
      div.innerHTML = "<i><span class=\"icon-zoom-out\">&nbsp;</span> Collapse</i>";
      return div;
    }
    collapseButton.addTo(bigMap);

    // Map swapping Stuff
    $("#mini-map").click(function(){
      $(this).hide();
      $("#big-map").show();
      bigMap.invalidateSize();
    });
    $(".collapse-me").click(function() {
      $("#big-map").hide();
      $("#mini-map").show();
      miniMap.invalidateSize();
    });
  });
})(jQuery, Drupal);
