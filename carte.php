<?php
include('nav.php');
?>
<!--
  Big Picture by HTML5 UP
  html5up.net | @n33co
  Free for personal and commercial use under the CCA 3.0 license (html5up.net/license)
-->
<html>
  <head>
    <title>Où faire circuler mes Florains ?</title>
    <meta name="description" content="Site web dedié a la création de la monnaie locale solidaire et citoyenne pour Nancy et ses environs. Le Florain est la dénomination désignée par les futurs usagés!"/>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <!--[if lte IE 8]><script src="assets/js/ie/html5shiv.js"></script><![endif]-->
    <link rel="stylesheet" href="assets/css/comptoirs.css" />
    <!--[if lte IE 8]><link rel="stylesheet" href="assets/css/ie8.css" /><![endif]-->
    <!--[if lte IE 9]><link rel="stylesheet" href="assets/css/ie9.css" /><![endif]-->
<!--OSM-->
    <link rel="stylesheet" href="https://openlayers.org/en/v4.2.0/css/ol.css" type="text/css">
    <!-- The line below is only needed for old environments like Internet Explorer and Android 4.x -->
    <script src="https://cdn.polyfill.io/v2/polyfill.min.js?features=requestAnimationFrame,Element.prototype.classList,URL"></script>
    <script src="https://openlayers.org/en/v4.2.0/build/ol.js"></script>
    <script src="https://code.jquery.com/jquery-2.2.3.min.js"></script>
    <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css"> -->
    <link rel="stylesheet" href="assets/css/popover.css" />
    <link rel="stylesheet" href="assets/css/cb.css" />
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    <style>
      #map {
        position: relative;
      }
      #popup {
        background-color: white;
      }
    </style>
<!--OSM-->
  </head>


  <body id="top">
<?php
$header = new Header();
$header->display();
?>

        <!-- Main -->
          <div id="acteurs">
<?php
$header->display_acteurs_nav();
?>
              <header>
              <h2><br/>Où faire circuler mes Florains ?<br />
                 </h2>
              </header>


    <!-- OSM -->
      <table><tr>
        <td>
          <fieldset id="layer1">
            <input id="visible1" class="visible" type="checkbox"/>
            <label class="css-label vert" for="visible1"> Alimentation </label>
          </fieldset>
        </td>
        <td>&nbsp;&nbsp;&nbsp;
        </td>
        <td>
          <fieldset id="layer3">
        <input id="visible3" class="visible" type="checkbox"/>
            <label class="css-label jaune" for="visible3"> Marchés </label>
          </fieldset>
        </td>
        <td>&nbsp;&nbsp;&nbsp;
        </td>
        <td>
          <fieldset id="layer2">
            <input id="visible2" class="visible" type="checkbox"/>
            <label class="css-label rouge" for="visible2"> Autres </label>
          </fieldset>
        </td>
<td width="100%"/>
      </tr></table>
    <div id="map" class="map"><div id="popup"></div></div>


    <script>
            var alimentation_features = new Array;
            var marche_features = new Array;
            var autres_features = new Array;
            var afeature;
<?php

function add_acteur( $acteur ) {
  if( $acteur->hasAttribute( "attente" ) ) {
      return;
  }
  if( ! $acteur->hasAttribute( "longitude" ) ) {
      return;
  }
  if( ! $acteur->hasAttribute( "latitude" ) ) {
      return;
  }
  $titre = $acteur->getAttribute( "titre" );
  $desc = $acteur->getAttribute( "bref" );
  $web = $acteur->getAttribute( "siteweb" );
  $lon = $acteur->getAttribute( "longitude" );
  $lat = $acteur->getAttribute( "latitude" );
  $r = 255; $g = 0; $b = 0;
  $type = $acteur->getAttribute( "type" );
  if( $type == "Alimentation" ) {
      $r = 0; $g = 255; $b = 0;
  }
  $punaise = "'https://openlayers.org/en/v4.2.0/examples/data/dot.png'";
  if( $desc == "Marché" ) {
      $r = 255; $g = 255; $b = 0;
      $desc = $acteur->getAttribute( "desc" );
  }
?>
afeature = new ol.Feature({
geometry: new ol.geom.Point(ol.proj.fromLonLat([<?php echo $lat?>, <?php echo $lon?>])),
name: "<?php echo $titre?>",
desc: "<?php echo $desc . " <br/><a href='http://" . $web . "'>".$web."</a>" ?>"
});

afeature.setStyle(
new ol.style.Style({
   image: new ol.style.Icon(/** @type {olx.style.IconOptions} */ ({
     color: [<?php echo $r . "," . $g . "," . $b?> ],
     crossOrigin: 'anonymous',
 src: <?php echo $punaise ?>
   }))
})
);
<?php
  if( $type == "Alimentation" ) {
      echo "alimentation_features.push( afeature );";
  } else if( $type == "Marché" ) {
      echo "marche_features.push( afeature );";
  } else {
      echo "autres_features.push( afeature );";
  }

}

      $xmlDoc = new DOMDocument();
      $xmlDoc->load("acteurs-cat.xml");

      $x = $xmlDoc->documentElement;
      $acteurs = $x->getElementsByTagName( "acteur" );
      $nb = $acteurs->length;
      for($pos=0; $pos<$nb; $pos++) {
            add_acteur( $acteurs[$pos] );
      }

      $marche_cat = $x->getElementsByTagName( "marches" );
      $marches = $marche_cat[0]->getElementsByTagName( "scat" );
      $nb_marches = $marches->length;

      for($pos=0; $pos<$nb_marches; $pos++) {
        add_acteur( $marches[$pos] );
      }
?>

/*
      var rasterLayer = new ol.layer.Tile({
        source: new ol.source.TileJSON({
          url: 'https://api.tiles.mapbox.com/v3/mapbox.geography-class.json?secure',
          crossOrigin: ''
        })
      });
*/

      var map = new ol.Map({
    layers: [
        new ol.layer.Tile({ preload: 4, source: new ol.source.OSM() }),
        new ol.layer.Vector({ source: new ol.source.Vector({ features: alimentation_features }) }),
        new ol.layer.Vector({ source: new ol.source.Vector({ features: autres_features }) }),
        new ol.layer.Vector({ source: new ol.source.Vector({ features: marche_features }) })
        ],
        target: document.getElementById('map'),
        view: new ol.View({
          center: ol.proj.fromLonLat([6.08262588978, 48.650322978]),
          zoom: 10
        })
      });

      var element = document.getElementById('popup');

      var osm_popup = new ol.Overlay({
        element: element,
        positioning: 'bottom-center',
        stopEvent: false,
        offset: [0, -10]
      });
      map.addOverlay(osm_popup);

      // display osm_popup on click
      map.on('click', function(evt) {
        var feature = map.forEachFeatureAtPixel(evt.pixel,
            function(feature) {
              return feature;
            });
        if (feature) {
          var coordinates = feature.getGeometry().getCoordinates();
          osm_popup.setPosition(coordinates);
          $(element).popover({
            'placement': 'top',
            'html': true,
          });
      $(element).data('bs.popover').options.title = feature.get('name');
      $(element).data('bs.popover').options.content = feature.get('desc');
          $(element).popover('show');
        } else {
          $(element).popover('destroy');
        }
      });

      // change mouse cursor when over marker
      map.on('pointermove', function(e) {
        if (e.dragging) {
          $(element).popover('destroy');
          return;
        }
        var pixel = map.getEventPixel(e.originalEvent);
        var hit = map.hasFeatureAtPixel(pixel);
        map.getTarget().style.cursor = hit ? 'pointer' : '';
      });
    </script>

          </div> <!-- OSM -->
          </div>


      <footer id="footer">
<!-- Icons -->
          <ul class="actions">
            <li><a target="_blank" href="https://www.facebook.com/LeFlorain" class="icon fa-facebook"><span class="label">Facebook</span></a></li>
            <li><a target="_blank" href="https://twitter.com/LeFlorain" class="icon fa-twitter"><span class="label">Twitter</span></a></li>
        </ul>

        <!-- Menu -->
          <ul class="menu"> <li>&copy; Le Florain</li></ul>
      </footer>
      </div>

    <!-- Scripts -->

      <script>

      function bindInputs(layerid, layer) {
        var visibilityInput = $(layerid + ' input.visible');
        visibilityInput.on('change', function() {
          layer.setVisible(this.checked);
        });
        visibilityInput.prop('checked', layer.getVisible());

        var opacityInput = $(layerid + ' input.opacity');
        opacityInput.on('input change', function() {
          layer.setOpacity(parseFloat(this.value));
        });
        opacityInput.val(String(layer.getOpacity()));
      }
      map.getLayers().forEach(function(layer, i) {
        bindInputs('#layer' + i, layer);
        if (layer instanceof ol.layer.Group) {
          layer.getLayers().forEach(function(sublayer, j) {
            bindInputs('#layer' + i + j, sublayer);
          });
        }
      });

    </script>

      <!-- OSM
      <script src="assets/js/jquery.min.js"></script>
       -->
      <script src="assets/js/jquery.poptrox.min.js"></script>
      <script src="assets/js/jquery.dropotron.min.js"></script>
      <script src="assets/js/jquery.scrolly.min.js"></script>
      <script src="assets/js/jquery.scrollex.min.js"></script>
      <script src="assets/js/jquery.onvisible.min.js"></script>
      <script src="assets/js/skel.min.js"></script>
      <script src="assets/js/util.js"></script>
      <!--[if lte IE 8]><script src="assets/js/ie/respond.min.js"></script><![endif]-->
      <script src="assets/js/main.js"></script>
      <script src="assets/js/main2.js"></script>

  </body>
</html>
