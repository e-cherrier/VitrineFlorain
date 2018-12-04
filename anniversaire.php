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
    <title>Le Florain fête  son 1er anniversaire</title>
    <meta name="description" content="Site web dedié a la création de la monnaie locale solidaire et citoyenne pour Nancy et ses environs. Le Florain est la dénomination désignée par les futurs usagés!"/>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    
    <link rel="stylesheet" href="assets/css/main.css" />
      <script src="assets/js/jquery.min.js"></script>
<!--OSM-->
    <link rel="stylesheet" href="https://openlayers.org/en/v4.2.0/css/ol.css" type="text/css">
    <!-- The line below is only needed for old environments like Internet Explorer and Android 4.x -->
    <script src="https://cdn.polyfill.io/v2/polyfill.min.js?features=requestAnimationFrame,Element.prototype.classList,URL"></script>
    <script src="https://openlayers.org/en/v4.2.0/build/ol.js"></script>
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

      .map {
        width: 50%;
        height: 70%;
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

      <section id="fete" class="main style2 right red fullscreen">
        <div class="content box anniv style1">
          <header>
            <h2>1 an déjà,</h2>
            <h2>Demandez le programme!</h2>
          </header>
          <ul>
            <li>11h: inauguration officielle</li>
            <li>12h-14h: repas <b>sur réservation</b></li>
            <li>10h-18h: marché varié, comptoirs de change et d'adhésions, buvette</li>
          </ul>
    <p>et toute la journée:</a>
          <img class="sf" src="http://www.monnaielocalenancy.fr/images/fete/logoCR54.png" width="100px" float="left"/>
    <div class="bigref">
    <p><a href="#animations">Défilés de mode</a><br/>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="#animations">Animations-Concerts</a><br/>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="#conferences">Conférences-Tables rondes</a><br/><br/>
    </p>
    </div>
      <footer>
	<ul class="anniv">
	 <li>  <a href="#jyvais" class="button">J'y vais!</a></li>
	 <li>  <a href="#reservation" class="button">Réservation</a></li>
	 <li>  <a href="#alimentation" class="button">Le marché</a></li>
	 <li>  <a href="#animations" class="button">En détails</a></li>
	</ul>
      </footer>
        </div>
        <footer>
          <a href="#alimentation" class="button style2 down anchored">More</a>
        </footer>
        </div>
      </section>


<section id="alimentation" class="carousel style3 primary">
          <header>
            <h2>Marché</h2>
            <p>Alimentation</p>
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
		      if( ! $acteurs[$indexes[$pos]]->hasAttribute( "anniv" ) ) {
	                continue;
	              }
		      if( $acteurs[$indexes[$pos]]->getAttribute( "type" ) != "Alimentation" ) {
	                  continue;
	              }

		      print "<article>\n";

		      print "<a href='". $acteurs[$indexes[$pos]]->getAttribute( "siteweb" ) . "' class='image featured'>\n";
	              print "  <img src='images/acteurs/" . $acteurs[$indexes[$pos]]->getAttribute( "image" ) . "'/></a>\n"; 
		      print "  <header>\n";
                      print "    <h4>" . $acteurs[$indexes[$pos]]->getAttribute( "titre" ) . "</h4>\n"; 
		      print "  </header>\n";
                      print "  <p>" . $acteurs[$indexes[$pos]]->getAttribute( "bref" ) . "</p>\n"; 

		      print "</article>\n";
                   }
                ?> 
        </div>
        <a href="#autre" class="button style2 down anchored">Next</a>
</section>

<section id="autre" class="carousel style3 primary">
          <header>
            <h2>Autres exposants</h2>
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
		      if( ! $acteurs[$indexes[$pos]]->hasAttribute( "anniv" ) ) {
	           continue;
	        }
		      if( $acteurs[$indexes[$pos]]->getAttribute( "type" ) == "Alimentation" ) {
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
          <footer>
               <a  class="button style2 " href="./acteurs.php"><h1>Voir la liste actuelle des professionnels agréés</h1><a>
          </footer>
        <a href="#animations" class="button style2 down anchored">Next</a>
</section>

<section id="animations" class="carousel style3 primary">
          <header>
            <h2>Animations et Concerts</h2>
            <p>Dans le cadre de cette journée associative, les artistes ont accepté de se produire bénévolement.</p>
            <p>Merci de contribuer librement au paiement de leur prestation</p>
          </header>
	<div class="reel">

		<?php
      $xmlDoc = new DOMDocument();
      $xmlDoc->load("animations1an.xml");

      $x = $xmlDoc->documentElement;
      $acteurs = $x->getElementsByTagName( "acteur" );
      $nb = $acteurs->length;
      $indexes = range(0, $nb-1);
      for($pos=0; $pos<$nb; $pos++) {
        if( $acteurs[$indexes[$pos]]->hasAttribute( "attente" ) ) {
          continue;
        }
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
  <a href="#conferences" class="button style2 down anchored">Next</a>
</section>

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
          <!--
          <ul class="anniv">
            <li>•	jeudi 6 septembre au marché de Maxéville (Brasseries)</li>
            <li>•	vendredi 7 septembre au marché de Vandoeuvre</li>
            <li>•	samedi 08 et dimanche 09 septembre à la Fête de la Bière à Maxéville (Brasseries)</li>
            <li>•	samedi 16 septembre à la Fête de la Nature à Laxou</li>
            <li>•	samedi 22 et dimanche 23 septembre à Jardins de Ville / Jardins de Vie à Jarville (parc de Montaigu)</li>
            <li>•	samedi 29 et dimanche 30 septembre à Jardins Extraordinaires au CD 54 (Nancy - Blandan)</li>
            <li>•	dimanche 30 septembre au marché d'automne de Villey le Sec (dans l'enceinte du fort)</li>
          </ul>
           -->
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


      <section id="jyvais" class="main style1 left red fullscreen">
        <div class="content box style1">
          <header>
            <h2>Comment s'y rendre ?</h2>
            <h4>Conseil Départemental - 48, Esplanade Jacques Baudot</h4>
          </header>
          
          <div class="anniv menu" >
            <div>
              <img float="left"  src="http://www.monnaielocalenancy.fr/images/fete/logoCR54.png" width="20%"/>
              <p>Le Conseil Départemental sera ouvert au public de 10h à 18h.<br/><br/>
                 L’entrée est libre.<br/><br/>
                 En transport en commun : ligne 7 arrêt Nancy Thermal.<br/><br/>
                 Pour vous garer, le parking de la piscine Thermal sera accessible.
              </p>
            </div>
          </div>
          <div id="map" class="map"><div id="popup"></div></div>

          <script>
              var logoElement = document.createElement('a');
              logoElement.href = 'http://www.florain.fr/';
              logoElement.target = '_blank';

              var logoImage = document.createElement('img');
              logoImage.src = 'http://www.monnaielocalenancy.fr/images/logo-monnaie-disquevert.png';

              logoElement.appendChild(logoImage);

              var features = new Array;
              afeature = new ol.Feature({
                  geometry: new ol.geom.Point(ol.proj.fromLonLat([6.165932, 48.679155])),
                  name: "Conseil Departemental de M&M",
                  desc: "<a href='http://www.meurthe-et-moselle.fr'>http://www.meurthe-et-moselle.fr</a>"
              });

              afeature.setStyle(
                new ol.style.Style({
                  image: new ol.style.Icon(/** @type {olx.style.IconOptions} */ ({
                    color: [255,0,0],
                    crossOrigin: 'anonymous',
                    src: 'https://openlayers.org/en/v4.2.0/examples/data/dot.png'
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
                  center: ol.proj.fromLonLat([6.165932, 48.679155]),
                  zoom: 17
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
          
            
        </div>
        <a href="#benevolat" class="button style2 down anchored">Next</a>
      </section>

      <section id="benevolat" class="main style1 left red fullscreen">
        <div class="content box style1">
          <header class="anniv">
            <h2>Comment aider ?<h2>
          </header>

          <div class="anniv">
          <p>Un groupe de travail bénévole planche sur l’organisation de cette manifestation. N’hésitez pas à nous rejoindre dès maintenant,
            ou le jour J pour donner un coup de main à l’accueil, au comptoir de change, à la buvette, … <br/><br/>
            Si vous êtes disponible et motivé.e, merci de cliquer sur le lien ci dessous pour préciser vos préférences sur les missions confiées,
            vos coordonnées téléphoniques et vos disponibilités.
            <ul>
              <li>le vendredi 7 et samedi 8 septembre pour de l'affichage à Nancy et alentours,</li>
              <li>le vendredi 5 octobre après-midi pour l'installation</li>
              <li>le samedi 6 octobre de 9h à 20h</li>
            </ul>
          </p>

                <footer >
                  <ul class="anniv">
                    <li><a target="_blank" href="https://framaforms.org/inscription-benevoles-fete-anniversaire-1535791702" class="button">Je participe</a></li>
                  </ul>
                </footer>

            </div>
        </div>
        <a href="#contact" class="button style2 down anchored">Next</a>
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
                  <div class="6u 12u(mobile)"><h4>Veuillez entrer le resultat de un plus un:</h4></div>
                  <div class="6u 12u(mobile)"><input type="text" name="calcul" id="calcul" placeholder="Un robot ne sais pas !"></div>
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
