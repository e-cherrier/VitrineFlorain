<?php
include 'nav.php';
?>
<!--
  Big Picture by HTML5 UP
  html5up.net | @n33co
  Free for personal and commercial use under the CCA 3.0 license (html5up.net/license)
-->
<html>
  <head>
    <title>Le Florain organise une brocante et une bourse aux instruments</title>
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

      <section id="brocante" class="main style2 right red fullscreen">
        <div class="content box style1">
          <header>
            <h2>La Brocante du Florain</h2>
            <h4>Le 13 octobre prochain de 9h à 17h</h4>
          </header>

          <div class="anniv" >
          <a href="https://lautrecanalnancy.fr"><img src="http://nancy.curieux.net/agenda/images/lieux/_34-logo.png" width="10%"/></a>
    </div>

          <p>avec le soutien précieux de <a href="https://lautrecanalnancy.fr">l’Autre Canal</a>,
          le Florain organise une brocante sous la Halle ouverte de l’Octroi (quartier Rives de Meurthe à Nancy).<br/>
          Une excellente occasion de développer notre partenariat avec ce haut lieu culturel, tout en proposant 
          aux adhérent.e.s du Florain la possibilité de vendre des objets d’occasion et ainsi collecter des florains
          sans passer par la case du change ;).<br/><br/>
          En cohérence avec notre charte de valeur, nous organiserons aussi ce jour-là une bourse aux instruments
          de musique ouverte à toutes et tous : un parfait mélange entre réemploi et accès à la culture.<br/><br/>
          Ce dimanche 13 octobre se tiendra également la Foire aux Disques dans la grande Halle de l’Octroi,
          à deux pas de notre brocante.</p>
          

      <footer>
	<ul class="anniv">
	 <li>  <a href="#jyvais" class="button"><span class="icon fa-compass"></span>&nbsp;&nbsp;Comment s'y rendre ?</a></li>
	 <li>  <a target="_blank" href="http://florain.fr/pub/ReglementBrocanteFlorain2019.pdf" class="button"><span class="icon fa-file"></span>&nbsp;&nbsp;Règlement de la brocante</a></li>
	 <li>  <a href="#inscriptions" class="button"><span class="icon fa-pencil"></span>&nbsp;&nbsp;Inscriptions et paiement</a></li>
	</ul>
      </footer>
        </div>
        <footer>
          <a href="#inscriptions" class="button style2 down anchored">More</a>
        </footer>
        </div>
      </section>

      <section id="inscriptions" class="main style1 left red fullscreen">
        <div class="content box style2">
          <header>
            <h2>Inscriptions et paiement</h2>
          </header>
         <p>
<ul>
<li>Une quarantaine de stands sera disponible.</li>
<li>Les inscriptions sont possibles, dans la limite des places disponibles,
<a target="_blank" href="https://framaforms.org/inscription-brocante-du-florain-1563961256">en suivant ce lien.</a></li> <br/> 
<li><b>Seules les inscriptions complètes seront enregistrées:</b></li>
<li>•	Merci de préparer une copie de votre pièce d'identité.</li>
<li>•	Pour les non-adhérents au Florain : une copie de votre attestation en responsabilité civile.</li>
<li>•	Pour les particuliers : une attestation sur l’honneur duement complétée et signée <a target="_blank" href="http://florain.fr/pub/AttestationSurLHonneurModele.pdf">(modèle à télécharger ici)</a></li>
</ul>
</p>
           
      <footer class="anniv">
        <ul >
        <li>  <a href="#paiement" class="button"><span class="icon fa-euro"></span>&nbsp;&nbsp;Paiement</a></li>
        </ul>
      </footer> 
        </div>
        <footer>
          <a href="#paiement" class="button style2 down anchored">Next</a>
        </footer>
      </section>
      
      <section id="paiement" class="main style2 left red fullscreen">
        <div class="content box style1">
          <header>
            <h2>Paiement</h2>
<h3>Les tarifs sont les suivants :</h3>
          </header>
         <p>
<ul>
  <li>•	1 table : 20 € / 10 € pour les adhérents au Florain </li>
  <li>•	2 tables : 50 € / 30 € pour les adhérents au Florain </li>
  <li>•	3ème table (en option selon la place disponible) : 50 €</li>
</ul>
</p>
<p>Si vous souhaitez bénéficier des tarifs adhérents, vous pouvez adhérer au Florain <a target="_blank" href="https://www.helloasso.com/associations/le-florain/adhesions/le-florain-formulaire-d-adhesion-utilisateurs">en cliquant ici.</a><br/>
 <u>Le paiement se fera au plus tard le vendredi 05 octobre 2019</u>, via le lien qui s'affichera une fois l'inscription réalisée, <u>quel que soit le mode de paiement choisi</u> :</p>
 <ul>

 <li>• <b>en ligne</b> de manière sécurisée</li>
 <li>• <b>par chèque</b>: utilisez le code promo CHEQUE54 puis adresser un chèque à l'ordre de "Le Florain" au 58 Boulevard Albert 1er 54000 NANCY ou déposez-le auprès de Vet’Ethic(*).</li>
 <li>• <b>en Florains</b>: utilisez le code promo FLOR154 et déposez votre paiement auprès de Vet’Ethic(*).</li>
</ul>
<p>(*) Vet’Ethic: 33 rue Saint Michel à Nancy. Ouvert du lundi au samedi de 10h à 18h30</p>

</p>

<footer>
	<ul class="anniv">
	 <li>  <a href="#jyvais" class="button"><span class="icon fa-compass"></span>&nbsp;&nbsp;Comment s'y rendre ?</a></li>
	 <li>  <a href="#contact" class="button"><span class="icon fa-question"></span>&nbsp;&nbsp;Une question ?</a></li>
	</ul>
      </footer>
            
        </div>
<footer>
        <a href="#jyvais" class="button style2 down anchored">Next</a>
      </footer>
      </section>


      <section id="jyvais" class="main style1 left red fullscreen">
        <div class="content box style1 mobile">
          <header>
            <h2>Comment s'y rendre ?</h2>
            <h4>la Halle ouverte de l’Octroi</h4>
            <h4>quartier Rives de Meurthe</h4>
            <h4>Boulevard d'Austrasie à Nancy</h4>
          </header>
          
          <div class="anniv menu" >
            <div>
              <p>la Halle sera ouverte au public de 9 à 17h.<br/><br/>
                 L’entrée est libre.<br/><br/>
                 En transport en commun : tram arrêt St Georges.<br/><br/>
                 Pour vous garer, deux parking sont à disposition sur le pole.
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
                  geometry: new ol.geom.Point(ol.proj.fromLonLat([6.198621, 48.693816])),
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
                  center: ol.proj.fromLonLat([6.198621, 48.693816]),
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
