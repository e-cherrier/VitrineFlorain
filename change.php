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
    <title>Les bureaux de change du Florain</title>
    <meta name="description" content="Site web dedié a la création de la monnaie locale solidaire et citoyenne pour Nancy et ses environs. Le Florain est la dénomination désignée par les futurs usagés!"/>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <!--[if lte IE 8]><script src="assets/js/ie/html5shiv.js"></script><![endif]-->
    <link rel="stylesheet" href="assets/css/comptoirs.css" />
    <link rel="stylesheet" href="style.css" />
    <!--[if lte IE 8]><link rel="stylesheet" href="assets/css/ie8.css" /><![endif]-->
    <!--[if lte IE 9]><link rel="stylesheet" href="assets/css/ie9.css" /><![endif]-->
<!--OSM-->
    <link rel="stylesheet" href="./assets/css/css/ol.css" type="text/css">
    <!-- The line below is only needed for old environments like Internet Explorer and Android 4.x -->
    <script src="https://cdn.polyfill.io/v2/polyfill.min.js?features=requestAnimationFrame,Element.prototype.classList,URL"></script>
    <script src="./assets/js/ol.js"></script>
    <script src="https://code.jquery.com/jquery-2.2.3.min.js"></script>
    <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css"> -->
    <link rel="stylesheet" href="assets/css/popover.css" />
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    <style>
      #map {
        position: relative;
      }
      #osm_popup {
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
              <h2><br/>Les comptoirs de change du Florain<br />
                 </h2>
<p style="halign=center" align="center" >Pour adhérer à notre association ou pour refaire le plein de Florains, il faut vous rendre dans le comptoir de change le plus proche, dont vous trouverez la liste ci-dessous.

<br/>
Pensez à venir jeter un oeil de temps en temps, de nouveaux comptoirs de change sont à prévoir.</p>
              </header>
              <section class="column">
<?php
function print_comptoir( $acteur ) {
  if(  ! $acteur->hasAttribute( "comptoir" ) ) {
      return;
  }

  $image = $acteur->getAttribute( "image" );
  $siteweb = $image;
  if( $acteur->hasAttribute( "siteweb" ) ) {
      $siteweb = $acteur->getAttribute( "siteweb" );
  }

  $titre = $acteur->getAttribute( "titre" );
  $desc = $acteur->getAttribute( "desc" );
  $telephone = $acteur->getAttribute( "telephone" );
  $adresse = $acteur->getAttribute( "adresse" );

  $p = <<<EOD
      <acteur class="comptoir">
      <img src="images/acteurs/$image" alt="$titre" />
  <p>
      <b>Nom:</b> $titre<br/>
      <b>Adresse:</b> $adresse<br/>
      <b>Téléphone:</b> $telephone<br/>
  </p>
EOD;
  print $p;

  $horaires = $acteur->getElementsByTagName( "h" );
  $nbh = $horaires->length;

  $p = <<<EOD
  <p><b>Horaires:</b><br/>
  <table class="horaires"><tbody>
EOD;
  print $p;
  for($h=0; $h<$nbh; $h++) {
      $l = $horaires[$h]->getAttribute( "l" );
      $t = $horaires[$h]->getAttribute( "t" );
      $p = <<<EOD
      <tr><td>$l</td><td>$t</td></tr>
EOD;
      print $p;
  }
  $p = <<<EOD
  </tbody></table></p>
EOD;
  print $p;

  if( $acteur->hasAttribute( "siteweb" ) ) {
      $siteweb = $acteur->getAttribute( "siteweb" );
      $p = <<<EOD
      <a href="http://$siteweb">$siteweb</a>
EOD;
      print $p;
  }
  if( $acteur->hasAttribute( "message_comptoir" ) ) {
      print "<p class='message'>" . $acteur->getAttribute( "message_comptoir" ) . "</p>";
  }
  print "</acteur>";
}

function add_on_map($acteur) {
  if(  ! $acteur->hasAttribute( "comptoir" ) ) {
      return;
  }
  $titre = $acteur->getAttribute( "titre" );
  $lon = $acteur->getAttribute( "longitude" );
  $lat = $acteur->getAttribute( "latitude" );
  $horaires = $acteur->getAttribute( "horaires" );

  $r = 255; $g = 0; $b = 0;
  $type = $acteur->getAttribute( "type" );
  if( $acteur->hasAttribute( "attente" ) ) {
      $r = 0; $g = 255; $b = 0;
  }
?>
afeature = new ol.Feature({
  geometry: new ol.geom.Point(ol.proj.fromLonLat([<?php echo $lat?>, <?php echo $lon?>])),
  name: "<?php echo $titre?>",
  desc: "<?php echo $horaires?>",
  rainfall: 500
});

afeature.setStyle(
  new ol.style.Style({
     image: new ol.style.Icon(/** @type {olx.style.IconOptions} */ ({
       color: [<?php echo $r . "," . $g . "," . $b?> ],
       crossOrigin: 'anonymous',
       src: './assets/css/images/dot.png'
     }))
  })
);
features.push( afeature );
<?php
}

      $xmlDoc = new DOMDocument();
      $xmlDoc->load("acteurs-cat.xml");

      $x = $xmlDoc->documentElement;
      $acteurs = $x->getElementsByTagName( "acteur" );
      $nb = $acteurs->length;
      $indexes = range(0, $nb-1);
      shuffle($indexes);
      for($pos=0; $pos<$nb; $pos++) {
        $acteur = $acteurs[$indexes[$pos]];
        print_comptoir( $acteur );
      }

      $marche_cat = $x->getElementsByTagName( "marches" );
      $marches = $marche_cat[0]->getElementsByTagName( "scat" );
      $nb_marches = $marches->length;

      for($pos=0; $pos<$nb_marches; $pos++) {
        print_comptoir( $marches[$pos] );
      }

?>
    </section>

    <!-- OSM -->
    <div id="map" class="map"><div id="osm_popup"></div></div>
    <script>
            var features = new Array;
            var afeature;
<?php
      for($pos=0; $pos<$nb; $pos++) {
          add_on_map( $acteurs[$pos] );
      }
      for($pos=0; $pos<$nb_marches; $pos++) {
          add_on_map( $marches[$pos] );
      }
?>
      var vectorSource = new ol.source.Vector({
        features: features
      });

      var vectorLayer = new ol.layer.Vector({
        source: vectorSource
      });
/*
      var rasterLayer = new ol.layer.Tile({
        source: new ol.source.TileJSON({
          url: 'https://api.tiles.mapbox.com/v3/mapbox.geography-class.json?secure',
          crossOrigin: ''
        })
      });
*/
      var fond_de_carte = new ol.layer.Tile({
            preload: 4,
            source: new ol.source.OSM()
          });

      var map = new ol.Map({
        layers: [fond_de_carte, vectorLayer],
        target: document.getElementById('map'),
        view: new ol.View({
          center: ol.proj.fromLonLat([6.08262588978, 48.650322978]),
          zoom: 11
        })
      });

      var element = document.getElementById('osm_popup');

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
          <ul class="menu">
            <li>&copy; Le FLorain</li>  </ul>

      </footer>
      </div>

    <!-- Scripts -->


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
