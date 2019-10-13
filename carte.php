<?php
include 'nav.php';
$latRef = -1;
$lonRef = -1;
$km = -1;
if (
  isset($_GET['km']) &&
  isset($_GET['lat']) &&
  isset($_GET['lon'])
) {
    $km = $_GET['km'];
    $latRef = $_GET['lat'];
    $lonRef = $_GET['lon'];
}

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
    <link rel="stylesheet" href="assets/css/main.css" />
    <link rel="stylesheet" href="assets/css/nav.css" />
<!--OSM-->
    <link rel="stylesheet" href="./assets/css/ol.css" type="text/css">
    <!-- The line below is only needed for old environments like Internet Explorer and Android 4.x -->
    <script src="https://cdn.polyfill.io/v2/polyfill.min.js?features=requestAnimationFrame,Element.prototype.classList,URL"></script>
    <script src="./assets/js/ol.js"></script>
    <script src="https://code.jquery.com/jquery-2.2.3.min.js"></script>
    <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css"> -->
    <link rel="stylesheet" href="assets/css/popover.css" />
    <link rel="stylesheet" href="assets/css/cb.css" />
    <link rel="stylesheet" href="assets/css/osm.css" />
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
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
        $xmlDoc->load('acteurs-cat.xml');

        $x = $xmlDoc->documentElement;
        $categories = $x->getElementsByTagName('categorie');
        $nb_cat = $categories->length;

        $shift = 2;
        for ($c = $shift; $c < ($nb_cat + $shift); ++$c) {
            echo "<p>\n";
            echo "  <fieldset id='layer".$c."'>\n";
            echo "    <label>&nbsp;</label>\n";
            echo "    <input id='visible".$c."' class='visible' type='checkbox'/>\n";
            echo "    <label class='css-label c".($c - $shift + 1)."' for='visible".$c."'>".$categories[$c - $shift]->getAttribute('type')." </label>\n";
            echo "  </fieldset>\n";
            echo "</p>\n";
        }
          echo "<p>\n";
          echo "  <fieldset id='layer".($nb_cat + $shift)."'>\n";
          echo "    <label>&nbsp;</label>\n";
          echo "    <input id='visible".($nb_cat + $shift)."' class='visible' type='checkbox'/>\n";
          echo "    <label class='css-label c".($nb_cat + 1)."' for='visible".($nb_cat + $shift)."'> Les marchés </label>\n";
          echo "  </fieldset>\n";
          echo "</p>\n";
          echo "<p>\n";
          echo "  <fieldset id='layer".($nb_cat + $shift + 1)."'>\n";
          echo "    <label>&nbsp;</label>\n";
          echo "    <input id='visible".($nb_cat + $shift + 1)."' class='visible' type='checkbox' value='false'/>\n";
          echo "    <label class='css-label c".($nb_cat + 2)."' for='visible".($nb_cat + $shift + 1)."'>Nombres</label>\n";
          echo "  </fieldset>\n";
          echo "  <fieldset>\n";
          echo "    <label style='margin-left: 20px; vertical-align: top;'>&nbsp;Distance:</label>\n";
          echo "    <input id='distance' type='range' min='10' max='500' step='1' value='40' />\n";
          echo " </fieldset>\n";
          echo "</p>\n";
        ?>
      </div>
    </div>


    <script>

      var distance = document.getElementById('distance');



             var afeature;
             var feature_array = new Array;
             var all_features = new Array;
<?php
for ($c = 0; $c < $nb_cat + 1; ++$c) {
            echo 'feature_array['.$c."] = new Array;\n";
        }

function calcCrow($lat1, $lon1, $lat2, $lon2)
{
    $R = 6371; // km
    $dLat = toRad($lat2 - $lat1);
    $dLon = toRad($lon2 - $lon1);
    $lat1 = toRad($lat1);
    $lat2 = toRad($lat2);

    $a = sin($dLat / 2) * sin($dLat / 2) + sin($dLon / 2) * sin($dLon / 2) * cos($lat1) * cos($lat2);
    $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
    $d = $R * $c;

    return $d;
}

// Converts numeric degrees to radians
function toRad($Value)
{
    return $Value * pi() / 180;
}

function add_acteur($f, $acteur)
{
    if ($acteur->hasAttribute('attente')) {
        return;
    }

    $lon = $acteur->getAttribute('longitude');
    $lat = $acteur->getAttribute('latitude');
    global $km;
    global $latRef;
    global $lonRef;
    if ($km > 0) {
        $dist = calcCrow($lat, $lon, $latRef, $lonRef);
        if( $dist > $km ) {
          return;
        }
    }

    $titre = $acteur->getAttribute('titre');
    $desc = $acteur->getAttribute('bref');
    $web = $acteur->getAttribute('siteweb');

    // kriptic method to dispach colors
    $color = array(
    0 => array('r' => 255, 'g' => 0, 'b' => 0),
    1 => array('r' => 255, 'g' => 255, 'b' => 0),
    2 => array('r' => 255, 'g' => 170, 'b' => 234),
    3 => array('r' => 174, 'g' => 0, 'b' => 255),
    4 => array('r' => 0, 'g' => 255, 'b' => 255),
    5 => array('r' => 119, 'g' => 74, 'b' => 74),
    7 => array('r' => 0, 'g' => 0, 'b' => 255),
    6 => array('r' => 255, 'g' => 192, 'b' => 0),
    8 => array('r' => 0, 'g' => 255, 'b' => 0),
  );
    $r = $color[$f]['r'];
    $g = $color[$f]['g'];
    $b = $color[$f]['b'];

    $punaise = "'./assets/css/images/dot.png'";
    if ($desc == 'Marché') {
        $desc = $acteur->getAttribute('desc');
    } ?>
afeature = new ol.Feature({
geometry: new ol.geom.Point(ol.proj.fromLonLat([<?php echo $lat; ?>, <?php echo $lon; ?>])),
name: "<?php echo $titre; ?>",
desc: "<?php echo $desc." <br/><a href='http://".$web."'>".$web.'</a>'; ?>"
});


afeature.setStyle(
  new ol.style.Style({
    image: new ol.style.Icon(/** @type {olx.style.IconOptions} */ ({
      color: [<?php echo $r.','.$g.','.$b; ?> ],
      crossOrigin: 'anonymous',
      src: <?php echo $punaise; ?>
    }))
  })
);

<?php
    echo 'feature_array['.$f."].push( afeature );\n";
    echo "all_features.push( afeature );\n";
}

      // build features per categories

      $reflon = 48.91;
      $reflat = 6.75;
      $curlon = $reflon;
      $curlat = $reflat;
      $nblon = 1;
      $nblat = 1;
      $ref = 0;

      for ($c = 0; $c < $nb_cat; ++$c) {
          $acteurs = $categories[$c]->getElementsByTagName('acteur');
          $nb = $acteurs->length;
          for ($pos = 0; $pos < $nb; ++$pos) {
              $acteur = $acteurs[$pos];
              if (!$acteur->hasAttribute('longitude') || !$acteur->hasAttribute('latitude')) {
                  $lon = $curlon;
                  $lat = $curlat;
                  $acteur->setAttribute('longitude', $lon);
                  $acteur->setAttribute('latitude', $lat);
                  $ref = ($ref + 1) % 4;
                  if ($ref == 0) {
                      $curlon = $curlon + .005;
                      $curlat = $reflat;
                      $nblon = $nblon + 1;
                  } else {
                      $curlat = $curlat + .005;
                      $nblat = min([4, $nblat + 1]);
                  }
              }
              add_acteur($c, $acteur);
          }
      }

      $marche_cat = $x->getElementsByTagName('marches');
      $marches = $marche_cat[0]->getElementsByTagName('scat');
      $nb_marches = $marches->length;

      for ($pos = 0; $pos < $nb_marches; ++$pos) {
          // all marche must be added to the last feature.
          add_acteur($nb_cat, $marches[$pos]);
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
      l = 0;
      layers = new Array;
      layers[l++]=new ol.layer.Tile({ preload: 4, source: new ol.source.OSM() });

      var textStyle = new ol.style.Text({
          textAlign: 'left',
          font: '10px helvetica,sans-serif',
          text: "Acteurs non geolocalises",
          stroke: new ol.style.Stroke({color: '#fff', width: 3}),
          offsetX: 10,
          offsetY: 20
      });
      var square_style = new ol.style.Style({
          stroke: new ol.style.Stroke({
            color: 'blue',
            width: 1
          }),
          fill: new ol.style.Fill({
            color: 'rgba(0, 0, 255, 0.1)'
          }),
          text: textStyle
    });

      var vectorSource = new ol.source.Vector({});
      layers[l++] = new ol.layer.Vector({
          source: vectorSource,
          style: square_style
      });

      var s = 0.005;
      var x = <?php echo $reflat; ?> - s/2;
      var y = <?php echo $reflon; ?> - s/2;
      var thing = new ol.geom.Polygon( [[
          ol.proj.transform([x,y], 'EPSG:4326', 'EPSG:3857'),
          ol.proj.transform([x+s*<?php echo $nblat; ?>,y], 'EPSG:4326', 'EPSG:3857'),
          ol.proj.transform([x+s*<?php echo $nblat; ?>,y+s*<?php echo $nblon; ?>], 'EPSG:4326', 'EPSG:3857'),
          ol.proj.transform([x,y+s*<?php echo $nblon; ?>], 'EPSG:4326', 'EPSG:3857')
      ]]);
      var featuresquare = new ol.Feature({
          name: "Square",
          geometry: thing
      });
      vectorSource.addFeature( featuresquare );



      var styleCache = {};
      for( f=0; f<feature_array.length;f++) {
           layers[l++] = new ol.layer.Vector({ source: new ol.source.Vector({ features: feature_array[f] }) });
      }


        var source = new ol.source.Vector({
          features: all_features
        });
        var clusterSource = new ol.source.Cluster({
          distance: 100,
          source: source
        });

        var clusters = new ol.layer.Vector({
          source: clusterSource,
          style: function(feature) {
            var size = feature.get('features').length;
            var style = styleCache[size];
            if (!style) {
              style = new ol.style.Style({
                /*
                image: new ol.style.Circle({
                  radius: 40,
                  stroke: new ol.style.Stroke({
                    color: '#000',
                    width: 3
                  })
                }),
                */
                text: new ol.style.Text({
                  font: '50px helvetica,sans-serif',
                  text: size.toString(),
                  fill: new ol.style.Fill({
                    color: '#fff'
                  }),
                  stroke: new ol.style.Stroke({
                      color: '#000',
                      width: 3
                  })
                })
              });
              styleCache[size] = style;
            }
            return style;
          }
        });
        layers[l++] = clusters;

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
        layers: layers,
        target: document.getElementById('map'),
        view: new ol.View({
          center: ol.proj.fromLonLat([6.08262588978, 48.650322978]),
          zoom: 10
        }),
        logo: logoElement
      });
      
      distance.addEventListener('input', function() {
        clusterSource.setDistance(parseInt(distance.value, 10));
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
