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
        <li>retransmis en direct en ligne!</li><br/>

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
            <p>A Nancy de 10h à 19h00</p>
              <li><b>Rallye photo</b> dans la ville.</li>
              <li><b>Marchés des acteurs du Florain,</b></li>
              <li>point d'info, Comptoir de change et d'adhésion.</li>
              <li>Place Charles III.</li>
          </div>
          <div  id="map_charles3" class="fullmap"></div>
        </div>


        <div style="margin-left:1em;" class="ctb anniv menu" >
          <div>
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
    <h2>Offres Speciales</h2>
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
        if( ! $acteurs[$indexes[$pos]]->hasAttribute( "offre-ctb21" ) ) {
          continue;
        }
        print "<article>\n";
        print "<a href='". $acteurs[$indexes[$pos]]->getAttribute( "siteweb" ) . "' class='image featured'>\n";
        print "<span class='mention'>" . $acteurs[$indexes[$pos]]->getAttribute( "type" ) . "</span>\n";
        print "  <img src='images/acteurs/" . $acteurs[$indexes[$pos]]->getAttribute( "image" ) . "'/></a>\n"; 
        print "  <header>\n";
        print "    <h4>" . $acteurs[$indexes[$pos]]->getAttribute( "titre" ) . "</h4>\n"; 
        print "  </header>\n";
        print "  <p>" . $acteurs[$indexes[$pos]]->getAttribute( "offre-ctb21" ) . "</p>\n"; 
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

<!--
<section id="conferences" class="carousel style3 primary">
          <header>
            <h2>Conférences - Tables rondes</h2>
          </header>
	<div class="reel">

		<?php
      $xmlDoc = new DOMDocument();
      $xmlDoc->load("conferences1an.xml");

      $x = $xmlDoc->documentElement;
      $acteurs = $x->getElementsByTagName( "acteur" );
      $nb = $acteurs->length;
      $indexes = range(0, $nb-1);
      for($pos=0; $pos<$nb; $pos++) {
        print "<article>\n";
        print "<a href='". $acteurs[$indexes[$pos]]->getAttribute( "siteweb" ) . "' class='image featured'>\n";
        print "<span class='mention'>" . $acteurs[$indexes[$pos]]->getAttribute( "type" ) . "</span>\n";
        print "  <img src='" . $acteurs[$indexes[$pos]]->getAttribute( "image" ) . "'/></a>\n"; 
        print "  <header>\n";
        print "    <h4>" . $acteurs[$indexes[$pos]]->getAttribute( "horaire" ) . "</h4>\n"; 
        print "    <h3>" . $acteurs[$indexes[$pos]]->getAttribute( "nom" ) . "</h3>\n"; 
        print "  </header>\n";
        print "  <p>" . $acteurs[$indexes[$pos]]->getAttribute( "desc" ) . "</p>\n"; 
        print "</article>\n";
      }
    ?> 
  </div>
  <a href="#restauration" class="button style2 down anchored">Next</a>
</section>

      <section id="restauration" class="main style1 left red fullscreen">
        <div class="content box style1">
          <header>
            <h2>Restauration</h2>
          </header>
          
          <div>
            <div class="anniv texte">
              <div>
                <ul>
                  <li>Buvette avec des produits bio et/ou locaux ouverte toute la journée.</li>
                  <br/>
                  <li>Le repas est confectionné à partir de produits bio et locaux par <a href="https://lesfermiersdici.com/traiteur">Les Fermiers d’ici</a>.</li>
                </ul>
                <img float="left" src="http://www.monnaielocalenancy.fr/images/acteurs/LesFermiersDIci.jpg" width="130px"/>
                <footer>
                  <ul class="anniv">
                    <li><a target="_blank" href="#reservation" class="button">Je reserve mes repas!</a></li>
                  </ul>
                </footer>
                <br/>
                <br/>
              </div>
            </div>
            <div class="anniv menu">
              <div>
                <p>Repas de 12h à 14h sur réservation:</p>
                <ul>
                  <li> Entrée </li>
                  <ul><li class="plat">Tarte aux poireaux et aux noix</li></ul>
                  <li> Plat </li>
                  <ul><li class="plat">Hachis parmentier de potimarron</li></ul>
                  <li> Dessert </li>
                  <ul><li class="plat">Crapuleux à la quetsche</li></ul>
                </ul>
                <p>15 euros/florains</p>
              <div>
           </div>
         </div>
            
        </div>
        <a href="#reservation" class="button style2 down anchored">Next</a>
      </section>


      <section id="reservation" class="main style1 left dark fullscreen">
        <div class="content box style1">
          <header class="anniv">
            <h2>Je réserve mes repas</h2><h3>réservations possibles jusqu'au 1er octobre</h3>
          </header>
          <div class="warning"><p>Les réservations nous permettent de limiter le gaspillage alimentaire en prévoyant
             le nombre exact de repas, il ne sera donc pas possible de manger sur place sans réservation préalable.</p></div>
	
          <p>Réservez en ligne avec le formulaire, le reglement pourra se faire sur place.<br/>
          Ou venez rencontrer les bénévoles du Florain lors des manifestations de septembre:<p>

          <div class="anniv left">
          <table class="calendar">
            <caption>passez la souris sur les cases surlignées</caption>
            <tr>
              <th>Jeudi</th>
              <th>Vendredi</th>
              <th>Samedi</th>
              <th>Dimanche</th>
            </tr><tr>
              <td class="tooltip">6<span class="tooltiptext">Le 6 septembre au marché de Maxéville (Brasseries)</span></td>
              <td class="tooltip">7<span class="tooltiptext">Le 7 septembre au marché de Vandoeuvre</span></td>
              <td class="tooltip">8<span class="tooltiptext">Les 8 et 9 septembre à la Fête de la Bière à Maxéville (Brasseries)</span></td>
              <td class="tooltip">9<span class="tooltiptext">Les 8 et 9 septembre à la Fête de la Bière à Maxéville (Brasseries)</span></td>
            </tr><tr>
              <td>13</td>
              <td>14</td>
              <td>15</td>
              <td class="tooltip">16<span class="tooltiptext">Le 16 septembre à la Fête de la Nature: La campagne à la ville à Laxou</span></td>
            </tr><tr>
              <td>20</td>
              <td>21</td>
              <td class="tooltip">22<span class="tooltiptext">Les 22 et 23 septembre à Jardins de Ville / Jardins de Vie à Jarville (parc de Montaigu)</span></td>
              <td class="tooltip">23<span class="tooltiptext">Les 22 et 23 septembre à Jardins de Ville / Jardins de Vie à Jarville (parc de Montaigu)</span></td>
            </tr><tr>
              <td>27</td>
              <td>28</td>
              <td class="tooltip">29<span class="tooltiptext">Les 29 et 30 septembre à Jardins Extraordinaires au CD 54 (Nancy - Blandan)</span></td>
              <td class="tooltip">30<span class="tooltiptext">Les 29 et 30 septembre à Jardins Extraordinaires au CD 54 (Nancy - Blandan) et Le 30 septembre au marché d'automne de Villey le Sec (dans l'enceinte du fort)</span></td>
            </tr>
          </table>
          </div>
          <--
          <ul class="anniv">
            <li>•	jeudi 6 septembre au marché de Maxéville (Brasseries)</li>
            <li>•	vendredi 7 septembre au marché de Vandoeuvre</li>
            <li>•	samedi 08 et dimanche 09 septembre à la Fête de la Bière à Maxéville (Brasseries)</li>
            <li>•	samedi 16 septembre à la Fête de la Nature à Laxou</li>
            <li>•	samedi 22 et dimanche 23 septembre à Jardins de Ville / Jardins de Vie à Jarville (parc de Montaigu)</li>
            <li>•	samedi 29 et dimanche 30 septembre à Jardins Extraordinaires au CD 54 (Nancy - Blandan)</li>
            <li>•	dimanche 30 septembre au marché d'automne de Villey le Sec (dans l'enceinte du fort)</li>
          </ul>
           ->
          <div class="bottom">
                <footer>
                  <ul class="anniv">
                    <li><a target="_blank" href="https://framaforms.org/inscription-repas-fete-anniversaire-1-an-florain-1535628289" class="button tooltip resa">Réserver en ligne<span class="tooltiptext">Le paiement pourra se faire sur place.</span></a></li>
                  </ul>
                  <ul class="anniv">
                    <li><a target="_blank" href="#restauration" class="button tooltip resa">Le Menu</a></li>
                  </ul>
                  
                </footer>
         </div>
          </div>
        <a href="#jyvais" class="button style2 down anchored">Next</a>
      </section>
    -->

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
