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
    <title>Change tes Billets!</title>
    <meta name="description" content="Site web dedié a la création de la monnaie locale solidaire et citoyenne pour Nancy et ses environs. Le Florain est la dénomination désignée par les futurs usagés!"/>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    
    <link rel="stylesheet" href="assets/css/main.css" />
    <link rel="stylesheet" href="assets/css/nav.css" />
      <script src="assets/js/jquery.min.js"></script>
<!--OSM-->
    <link rel="stylesheet" href="https://openlayers.org/en/v4.2.0/css/ol.css" type="text/css">
    <!-- The line below is only needed for old environments like Internet Explorer and Android 4.x -->
    <script src="https://cdn.polyfill.io/v2/polyfill.min.js?features=requestAnimationFrame,Element.prototype.classList,URL"></script>
    <script src="./assets/js/ol.js"></script>
    <!--<script src="https://code.jquery.com/jquery-2.2.3.min.js"></script>-->
    <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css"> -->
    <link rel="stylesheet" href="assets/css/popover.css" />
    <link rel="stylesheet" href="assets/css/cb.css" />
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    

    <style>
    .tooltip {
        position: relative;
        border-bottom: 1px dotted black;
    }

    .tooltip .tooltiptext {
        visibility: hidden;
        width: 220px;
        color: #555;
        background-color: #fff;
        text-align: center;
        border-radius: 6px;
        padding: 5px 0;
        position: absolute;
        z-index: 1;
        bottom: 125%;
        left: 50%;
        margin-left: -60px;
        opacity: 0;
        transition: opacity 0.3s;
    }

    .tooltip .tooltiptext::after {
        content: "";
        position: absolute;
        top: 100%;
        left: 50%;
        margin-left: -5px;
        border-width: 5px;
        border-style: solid;
        border-color: #555 transparent transparent transparent;
    }

    .tooltip:hover .tooltiptext {
        visibility: visible;
        opacity: 1;
    }
    table {
        border: medium solid #000000;
        width: 50%;
    }
    caption {
      color: #fff;
    }
    td, th {
        border: medium solid #000000;
        width: 10%;
        text-align: center;
        padding: 5px;
        color: #fff200;
    }
    th {
        background: #fff;
        color: #000;
    }
    td.tooltip {
        background: #fff200;
        color: #000;
    }


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


      .sidepanel {
        background: #cfdd3f;
        width: 25%;
        height: 100%;
        float: right;
      }

      .popover {
        background-color: #888;
        color: #fff;
      }
      .popover-title {
        background-color: #555;
      }
      .red a:hover {
        color: #555;
      }
			a.ahover span {
			  color: yellow;
			}
			a.ahover:hover span {
			  color: #555;
        transform: rotate(180deg);
			}
    </style>
<!--OSM-->
  </head>
  <body id="top">
<?php
$header = new Header();
$header->display();
?>

<section id="changetesbillets" class="main style2 right red fullscreen">
  <div class="content box anniv style1">
    <header>
      <h4>Les 4, 5 et 6 Juin</h4>
      <h4>Le Florain vous invite à ses journées</h4>
      <h2>Change tes Billets!</h2>
    </header>
    <h4> Marchés, Animations, Conférence, Ciné-Débat et offres spéciales</h4>
      <h4> A Colombey-les-Belles, Nancy, Vandoeuvre</h4>
      <br/>
      <h3>Découvrez le programme !</h3>
    <footer>
      <ul class="anniv">
      <li>  <a href="#dimanche" class="button">📅 Dimanche 6</a></li>
      <li>  <a href="#samedi" class="button">📅 Samedi 5</a></li>
      <li>  <a href="#vendredi" class="button">📅 Vendredi 4</a></li>
      </ul>
    </footer>
  </div>
  <footer>
    <a href="#vendredi" class="button style2 down anchored">More</a>
  </footer>
</section>

<section id="vendredi" class="main style2 right red fullscreen">
  <div class="content box style1 anniv">
    <header>
      <h2>vendredi 4 juin</h2>
    </header>
    <div class="anniv menu" >
      <div>
        <h3>Conférence débat à l'Autre Canal<h3>
        <h4> <em>"Les monnaies locales : monnaie d’intérêt général"</em> de 18h à 19h30</h4>
        <li>D'après une étude détaillée du mouvement Sol.</li>
        <li>retransmis en direct en ligne:
          <a href="https://zoom.us/j/93554021513" class="ahover button fab fa-mdb" style="height:1.2em;padding: 0em 0.4em 1em 0.4em;">
            <span class="icon fa fa-phone"></span>
          </a>
        </li>
        <a href="https://sol-monnaies-locales.org/l-impact-social" class="ahover button" style="border-radius:0px;">Plus d'info sur le site du mouvement Sol</a>
        <br/>
        <br/>

        <h3>Point d'info, Comptoir de change et d'adhésion à <b>l'Autre Marché</b> </h3>
        <h4>Halle Ouverte de l'Octroi</h4>
      </div>
    </div>
          
    <div id="map" class="map"></div>

    <script>

      var features = new Array;
      afeature = new ol.Feature({
          geometry: new ol.geom.Point(ol.proj.fromLonLat([6.199138, 48.693938])),
          name: "la Halle ouverte de l’Octroi",
          desc: "Boulevard d'Austrasie à Nancy"
      });

      afeature.setStyle(
        new ol.style.Style({
          image: new ol.style.Icon(/** @type {olx.style.IconOptions} */ ({
            color: [255,0,0],
            crossOrigin: 'anonymous',
            src: 'assets/css/images/dot.png'
          }))
        })
      );
      
      features.push( afeature );

      var map = new ol.Map({
        layers: [
          new ol.layer.Tile({ preload: 4, source: new ol.source.OSM() }),
          new ol.layer.Vector({ source: new ol.source.Vector({ features: features }) }),
        ],
        target: document.getElementById('map'),
        view: new ol.View({
          center: ol.proj.fromLonLat([6.199138, 48.693938]),
          zoom: 17
        }),
        controls: [],
        interactions: []
      });
      
    </script>
          
  </div>
  <a href="#samedi" class="button style2 down anchored">Next</a>
</section>

<section id="samedi" class="main style2 right red fullscreen">
  <div class="content box anniv style1">
    <header>
      <h2>samedi 5 juin</h2>
    </header>              
     <p><b>Animations et offres spéciales</b> dans nos comptoirs de change.</p>

        <div class="anniv menu" >
          <div>
          <a href="#nancyctb21" class="button" style="float:right; border-radius:0px;">Voir les exposants</a>
            <p>A Nancy de 10h à 19h00</p>
              <li><b>Rallye photo</b> à la découverte des partenaires du Florain.</li>
              <li><b>Marchés des acteurs du Florain,</b></li>
              <li>point d'info, Comptoir de change et d'adhésion.</li>
              <li>Place Charles III.</li>
          </div>
          <div  id="map_charles3" class="fullmap"></div>
        </div>


        <div style="margin-left:1em;" class="ctb anniv menu" >
          <div>
          <a href="#colombey" class="button" style="float:right; border-radius:0px;">Voir les exposants</a>
            <p>A Colombey-les-Belles de 10h à 19h00</p>
              <li><b>Marchés des acteurs du Florain,</b></li>
              <li>point d'info, Comptoir de change et d'adhésion.</li>
              <li>Sous les Halles (en face de la mairie).</li>
          </div>
          <div id="map_colombey" class="fullmap"></div>
        </div>
          
    <script>

      var features = new Array;
      afeature = new ol.Feature({
          geometry: new ol.geom.Point(ol.proj.fromLonLat([6.181548, 48.689204])),
          name: "Place Charles III",
          desc: "à Nancy"
      });

      afeature.setStyle(
        new ol.style.Style({
          image: new ol.style.Icon(/** @type {olx.style.IconOptions} */ ({
            color: [255,0,0],
            crossOrigin: 'anonymous',
            src: 'assets/css/images/dot.png'
          }))
        })
      );
      
      features.push( afeature );

      var map_charles3 = new ol.Map({
        layers: [
          new ol.layer.Tile({ preload: 4, source: new ol.source.OSM() }),
          new ol.layer.Vector({ source: new ol.source.Vector({ features: features }) }),
        ],
        target: document.getElementById('map_charles3'),
        view: new ol.View({
          center: ol.proj.fromLonLat([6.181548, 48.689204]),
          zoom: 17
        }),
        controls: [],
        interactions: []
      });
    </script> 
    <script>

      var features = new Array;
      afeature = new ol.Feature({
          geometry: new ol.geom.Point(ol.proj.fromLonLat([5.8971334682262535, 48.52866497331211])),
          name: "les Halles de Colombey les Belles",
          desc: "en face de la mairie"
      });

      afeature.setStyle(
        new ol.style.Style({
          image: new ol.style.Icon(/** @type {olx.style.IconOptions} */ ({
            color: [255,0,0],
            crossOrigin: 'anonymous',
            src: 'assets/css/images/dot.png'
          }))
        })
      );
      
      features.push( afeature );

      var map = new ol.Map({
        layers: [
          new ol.layer.Tile({ preload: 4, source: new ol.source.OSM() }),
          new ol.layer.Vector({ source: new ol.source.Vector({ features: features }) }),
        ],
        target: document.getElementById('map_colombey'),
        view: new ol.View({
          center: ol.proj.fromLonLat([5.8971334682262535, 48.52866497331211]),
          zoom: 17
        }),
        controls: [],
        interactions: []
      });
      
    </script>
  </div>
  <footer>
    <a href="#colombey" class="button style2 down anchored">More</a>
  </footer>
</section>

<section id="colombey" class="carousel style3 primary">
  <header>
    <h2>Samedi à Colombey-les-Belles</h2>
    <p>Halle face à la mairie</p>
  </header>
  <div class="reel">
    <?php
      $xmlDoc = new DOMDocument();
      $xmlDoc->load("acteurs-cat.xml");

      $x = $xmlDoc->documentElement;
      $acteurs = $x->getElementsByTagName( "acteur" );
      $nb = $acteurs->length;
      $indexes = range(0, $nb-1);
      shuffle($indexes);
      for($pos=0; $pos<$nb; $pos++) {
        $a = $acteurs[$indexes[$pos]];
        if( ! $a->hasAttribute( "colombey_ctb21" ) ) {
          continue;
        }

        print "<article>\n";
        print "<a href='". $a->getAttribute( "siteweb" ) . "' class='image featured'>\n";
        print "<span class='mention'>" . $a->getAttribute( "type" ) . "</span>\n";
        print "  <img src='images/acteurs/" . $a->getAttribute( "image" ) . "'/></a>\n"; 
        print "  <header>\n";
        print "    <h4>" . $a->getAttribute( "titre" ) . "</h4>\n"; 
        print "  </header>\n";

        $v = $a->getAttribute( "colombey_ctb21" );
        if( $v != "oui" ) {
          print "  <h1>" . $v . "</h1>\n";
        }
        print "  <p>" . $a->getAttribute( "bref" ) . "<br/>\n";
        print "     " . $a->getAttribute( "adresse" ) . "</p>\n";

        print "</article>\n";
      }
    ?> 
  </div>
  <a href="#nancyctb21" class="button style2 down anchored">Next</a>
</section>

<section id="nancyctb21" class="carousel style3 primary">
  <header>
    <h2>Samedi à Nancy</h2>
    <p>Place Charles III</p>
  </header>
	<div class="reel">

		<?php
        $xmlDoc = new DOMDocument();
        $xmlDoc->load("acteurs-cat.xml");

        $x = $xmlDoc->documentElement;
        $acteurs = $x->getElementsByTagName( "acteur" );
        $nb = $acteurs->length;
        $indexes = range(0, $nb-1);
		    shuffle($indexes);
        for($pos=0; $pos<$nb; $pos++) {
		      if( ! $acteurs[$indexes[$pos]]->hasAttribute( "nancy-ctb21" ) ) {
	           continue;
	        }
		      print "<article>\n";
		      print "<a href='". $acteurs[$indexes[$pos]]->getAttribute( "siteweb" ) . "' class='image featured'>\n";
		      print "<span class='mention'>" . $acteurs[$indexes[$pos]]->getAttribute( "type" ) . "</span>\n";
	        print "  <img src='images/acteurs/" . $acteurs[$indexes[$pos]]->getAttribute( "image" ) . "'/></a>\n"; 
		      print "  <header>\n";
          print "    <h4>" . $acteurs[$indexes[$pos]]->getAttribute( "titre" ) . "</h4>\n"; 
		      print "  </header>\n";
          print "  <p>" . $acteurs[$indexes[$pos]]->getAttribute( "bref" ) . "</p>\n"; 
		      print "</article>\n";
        }
    ?> 
  
  </div>
  <a href="#offres" class="button style2 down anchored">Next</a>
</section>

<section id="offres" class="carousel style3 primary">
  <header>
    <h2>Offres Spéciales</h2>
    <p>Le Florain offre des <em>goodies</em> pour toutes nouvelles adhésions :)</p>
  </header>
	<div class="reel">

		<?php
      $xmlDoc = new DOMDocument();
      $xmlDoc->load("acteurs-cat.xml");

      $x = $xmlDoc->documentElement;
      $acteurs = $x->getElementsByTagName( "acteur" );
      $nb = $acteurs->length;
      $indexes = range(0, $nb-1);
      for($pos=0; $pos<$nb; $pos++) {
        $a = $acteurs[$indexes[$pos]];
        if( ! $a->hasAttribute( "offre-ctb21" ) ) {
          continue;
        }
  
        $adresse = $a->getAttribute('adresse');
        $elts = explode(',', $adresse);
        print "<article>\n";
        print "<a href='". $a->getAttribute( "siteweb" ) . "' class='image featured'>\n";
        print "<span class='mention'>" . $a->getAttribute( "type" ) . "</span>\n";
        print "  <img src='images/acteurs/" . $a->getAttribute( "image" ) . "'/></a>\n"; 
        print "  <header>\n";
        print "    <h4>" . $a->getAttribute( "titre" ) . "</h4>\n"; 
        print "  </header>\n";
        print "  <p style='border: medium solid #555;'>" . $a->getAttribute( "offre-ctb21" ) . "</p>\n"; 
        print "  <p>" . $a->getAttribute( "bref" ) . "<br/>\n";
        print "     " . $elts[0] . "<br/>\n";
        if( sizeof($elts) > 1 ) { 
          print "     " . $elts[1] . "</p>\n";
        }
        print "</article>\n";
      }
    ?> 
  </div>
  <a href="#dimanche" class="button style2 down anchored">Next</a>
</section>


<section id="dimanche" class="main style2 right red fullscreen">
  <div class="content box anniv style1">
    <header>
      <h2>dimanche 6 juin</h2>
    </header>
        <div class="anniv menu" >
          <div>
            <h3>Participation à <b>"Désir de Nature"</b></h3>
            <h4>Parc du Charmois à Vandœuvre.</h4>
            <h4>10h à 18h00.</h4>
            <ul>
               <li>Animations - Molky</li>
               <li>Point info, comptoir de changes et d'adhésion.</li>
               <li><strong>15h Ciné - débat:</strong> <em>"Et si mon argent changeait le monde ?"</em> salle Dinet - Ferme du Charmois.</li>
            </ul>
          <h3>Marché de Vandœuvre</h3>
          <h4>9h à 12h00.</h4>
          <li>Point info, comptoir de change et d'adhésion.</li>
        </ul>
          </div>
        </div>
          <div  id="map_vand" class="map"><div id="popup"></div></div>

              
        <script>
          var features = new Array;
          afeature1 = new ol.Feature({
              geometry: new ol.geom.Point(ol.proj.fromLonLat([6.173468, 48.665086])),
              name: "Marché de Vandoeuvre",
              desc: "comptoir de change du Florain"
          });
          afeature2 = new ol.Feature({
              geometry: new ol.geom.Point(ol.proj.fromLonLat([ 6.173419259, 48.6681293])),
              name: "Parc du Charmois",
              desc: "Ciné-Débat, Comptoir de Change"
          });

          var style = new ol.style.Style({
              image: new ol.style.Icon(/** @type {olx.style.IconOptions} */ ({
                color: [255,0,0],
                crossOrigin: 'anonymous',
                src: 'assets/css/images/dot.png'
              }))
            });
          afeature1.setStyle(style);
          afeature2.setStyle(style);
          
          features.push( afeature1 );
          features.push( afeature2 );

          var map = new ol.Map({
            layers: [
              new ol.layer.Tile({ preload: 4, source: new ol.source.OSM() }),
              new ol.layer.Vector({ source: new ol.source.Vector({ features: features }) }),
            ],
            target: document.getElementById('map_vand'),
            view: new ol.View({
              center: ol.proj.fromLonLat([6.173636621081427, 48.66687398582606 ]),
              zoom: 16
            }),
            controls: [],
            interactions: []
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

  </div>
  <footer>
    <a href="#contact" class="button style2 down anchored">More</a>
  </footer>
</section>


      <section id="contact" class="main style3 dark secondary">
        <div class="content container">
          <header>
            <h2>Nous contacter.</h2>
          </header>
          <div class="box container 75%">

          <!-- Contact Form -->
              <form method="post" action="envoyer.php">
                <div class="row 50%">
                  <div class="6u 12u(mobile)"><input type="text" name="name" id="name" placeholder="Nom"></div>
                  <div class="6u 12u(mobile)"><input type="text" name="prenom" id="prenom" placeholder="Prénom"></div>
                </div>
                <div class="row 50%">
                  <div class="6u 12u(mobile)"><input type="text" name="phone" id="phone" placeholder="Téléphone"></div>
                  <div class="6u 12u(mobile)"><input type="email" name="email" id="email" placeholder="Email"></div>
                </div>
                <div class="row 50%">
                  <div class="12u"><textarea name="message" placeholder="Message" rows="4"></textarea></div>
                </div>
                <div class="row 50%">
                  <div class="6u 12u(mobile)"><h4>Veuillez entrer le résultat de un plus un:</h4></div>
                  <div class="6u 12u(mobile)"><input type="text" name="calcul" id="calcul" placeholder="Un robot ne sait pas !"></div>
                </div>
                <div class="row">
                  <div class="12u">
                    <ul class="actions">
                      <li><input type="submit" name="envoyer" value="Envoyer"></li>
                    </ul>
                  </div>
                </div>
              </form>

          </div><br><br><br>
        </div><a href="#top" class="button style2 down anchored">Next</a>
      </section>

    
      <footer id="footer">

        <!-- Icons -->
          <ul class="actions">
            
            <li><a target="_blank" href="https://www.facebook.com/LeFlorain" class="icon fa-facebook"><span class="label">Facebook</span></a></li>
            
            <li><a target="_blank" href="https://twitter.com/LeFlorain" class="icon fa-twitter"><span class="label">Twitter</span></a></li>
        
          
          </ul>

        <!-- Menu -->
          <ul class="menu">
            <li>&copy; Le Florain</li>       </ul>

      </footer>

    <!-- Scripts -->
      
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
