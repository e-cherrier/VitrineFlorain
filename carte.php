﻿<?php
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
    <link rel="stylesheet" href="assets/css/comptoirs.css" />
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
    
    .fullscreen:-moz-full-screen {
        height: 100%;
      }
      .fullscreen:-webkit-full-screen {
        height: 100%;
      }
      .fullscreen:-ms-fullscreen {
        height: 100%;
      }

      .fullscreen:fullscreen {
        height: 100%;
      }

      .fullscreen {
        margin-bottom: 10px;
        width: 100%;
        height: 70%;
      }

      .ol-rotate {
        top: 3em;
      }

      .map {
        width: 75%;
        height: 100%;
        float: right;
      }

      .sidepanel {
        background: #cfdd3f;
        width: 25%;
        height: 100%;
        float: right;
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
        
    <div id="fullscreen" class="fullscreen">
      <div id="map" class="map"><div id="popup"></div></div>
      <div class="sidepanel">
        <?php

        $xmlDoc = new DOMDocument();
        $xmlDoc->load("acteurs-cat.xml");

        $x = $xmlDoc->documentElement;
        $categories = $x->getElementsByTagName( "categorie" );
        $nb_cat = $categories->length;

        for($c=1; $c<=$nb_cat; $c++) {
          echo "<p float='left'>\n";
          echo "  <fieldset id='layer". $c . "'>\n";
          echo "    <label>&nbsp;</label>\n";
          echo "    <input id='visible". $c . "' class='visible' type='checkbox'/>\n";
          echo "    <label class='css-label noir' for='visible". $c . "'>" . $categories[$c-1]->getAttribute( "type" ) . " </label>\n";
          echo "  </fieldset>\n";
          echo "</p>\n";
        }
          echo "<p float='left'>\n";
          echo "  <fieldset id='layer". ($nb_cat+1) . "'>\n";
          echo "    <label>&nbsp;</label>\n";
          echo "    <input id='visible". ($nb_cat+1) . "' class='visible' type='checkbox'/>\n";
          echo "    <label class='css-label noir' for='visible". ($nb_cat+1) . "'> Les marchés </label>\n";
          echo "  </fieldset>\n";
          echo "</p>\n";
        ?>
        </ul>
      </div>
    </div>


    <script>
             var afeature;
             var feature_array = new Array;
<?php
for($c=0; $c<$nb_cat+1; $c++) {
  echo "feature_array[" . $c . "] = new Array;\n";
}

function add_acteur( $f, $acteur ) {
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

  // kriptic method to dispach colors
  $color = array(
    0 => 255,
    1 => 0,
    2 => 128,
    3 => 84,
    4 => 170,
    5 => 42,
    6 => 212,
  );
  $r =  $color[($f) % 7];
  $g =  $color[($f/2) % 7];
  $b =  $color[abs(1-($f/4)) % 7];

  $punaise = "'https://openlayers.org/en/v4.2.0/examples/data/dot.png'";
  if( $desc == "Marché" ) {
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
    echo "feature_array[" . $f . "].push( afeature );\n";
}

      // build features per categories

      for($c=0; $c<$nb_cat; $c++) {

        $acteurs = $categories[$c]->getElementsByTagName( "acteur" );
        $nb = $acteurs->length;
        for( $pos=0; $pos<$nb; $pos++ ) {
            add_acteur( $c, $acteurs[$pos] );
        }
      }

      $marche_cat = $x->getElementsByTagName( "marches" );
      $marches = $marche_cat[0]->getElementsByTagName( "scat" );
      $nb_marches = $marches->length;

      for($pos=0; $pos<$nb_marches; $pos++) {
        // all marche must be added to the last feature.
        add_acteur( $nb_cat, $marches[$pos] );
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

      var logoElement = document.createElement('a');
      logoElement.href = 'http://www.florain.fr/';
      logoElement.target = '_blank';

      var logoImage = document.createElement('img');
      logoImage.src = 'http://www.monnaielocalenancy.fr/images/logo-monnaie-disquevert.png';

      logoElement.appendChild(logoImage);

      var map = new ol.Map({
        controls: ol.control.defaults().extend([
          new ol.control.FullScreen({
            source: 'fullscreen'
          })
        ]),
        layers: [
          new ol.layer.Tile({ preload: 4, source: new ol.source.OSM() }),
          <?php
            for($c=0; $c<$nb_cat+1; $c++) {
              echo "new ol.layer.Vector({ source: new ol.source.Vector({ features: feature_array[" . $c . "] }) }),\n";
            }
          ?>
        ],
        target: document.getElementById('map'),
        view: new ol.View({
          center: ol.proj.fromLonLat([6.08262588978, 48.650322978]),
          zoom: 10
        }),
        logo: logoElement
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
