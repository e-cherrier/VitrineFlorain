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
    <title>Le Florain: Monnaie Locale Citoyenne et Complémentaire à Nancy et environs</title>
    <meta name="description" content="Site web dedié à la création de la monnaie locale solidaire et citoyenne pour Nancy et ses environs. Le Florain est la dénomination désignée par les futurs usagés!"/>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <!--[if lte IE 8]><script src="assets/js/ie/html5shiv.js"></script><![endif]-->
<!--<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">-->
    <link rel="stylesheet" href="assets/css/main.css" />
    <link rel="stylesheet" href="assets/css/style_accueil.css" />
    <link rel="stylesheet" href="assets/css/nav.css" />
    <link rel="stylesheet" href="assets/css/bs_carousel.css" />
    <!--[if lte IE 8]><link rel="stylesheet" href="assets/css/ie8.css" /><![endif]-->
    <!--[if lte IE 9]><link rel="stylesheet" href="assets/css/ie9.css" /><![endif]-->

<!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>-->

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
  <style>
ol.carousel-indicators li {
  background: #d7da23;
}

ol.carousel-indicators li.active {
  background: #615f5f;
}
</style>
  </head>
  <body id="top">

<?php
$header = new Header();
$header->display();
?>

      <section id="accueil" class="main styleAcceuil right dark fullscreen">
        <div class="content box style1">
          <h2> Les derniers acteurs agréés </h2>
<div id="myCarousel" class="carousel slide">

    <?php

      $xmlDoc = new DOMDocument();
      $xmlDoc->load('acteurs-cat.xml');
      $x = $xmlDoc->documentElement;

      $nouveaux = array();
      $idx = 0;

      $acteurs = $x->getElementsByTagName('acteur');
      $nb_acteurs = $acteurs->length;

      $today = new DateTime();
      $today->setTimestamp(time());
      $oneMonthAgo = $today->sub(DateInterval::createFromDateString('2 month'));

      for ($a = 0; $a < $nb_acteurs; ++$a) {
          $acteur = $acteurs[$a];
          $date = $acteur->getAttribute('date');
          // $datetime2 = date_create('2009-10-13');
          $aggDate = DateTime::createFromFormat('d-m-Y', $date);
          $titre = str_replace("'", '', $acteur->getAttribute('titre'));
          if ($oneMonthAgo < $aggDate) {
              $nouveaux[$idx] = $acteur;
              $idx = $idx + 1;
          }
      }

      // <!-- Indicators -->
      echo "<ol class='carousel-indicators'>\n";
      echo "<li data-target='#myCarousel' data-slide-to='0' class='active'></li>\n";
      for ($a = 1; $a < count($nouveaux); ++$a) {
          echo "<li data-target='#myCarousel' data-slide-to='".$a."' ></li>\n";
      }
      echo "</ol>\n";

      $indexes = range(0, count($nouveaux) - 1);
      shuffle($indexes);
      // <!-- Wrapper for slides -->
      echo "<div class='carousel-inner'>\n";
      $acteur = $nouveaux[$indexes[0]];
      $image = $acteur->getAttribute('image');
      $titre = str_replace("'", '', $acteur->getAttribute('titre'));
      $desc = $acteur->getAttribute('desc');
      $lenmax = 260;
      if (strlen($desc) > $lenmax) {
          $desc = substr($desc, 0, $lenmax - 7).' [...]';
      }
      $adresse = $acteur->getAttribute('adresse');
      $siteweb = $acteur->getAttribute('siteweb');
      echo "  <div class='item active'>\n";
      echo "     <img src='images/acteurs/".$image."' alt='".$titre."'/>\n";
      echo "     <div>\n";
      echo "       <h1 align='center'>".$titre."</h1>\n";
      echo '       <p >'.$desc."</p>\n";
      echo "       <div class='coordonnees'>\n";
      echo '         <p >'.$adresse."<br/>\n";
      echo "         <a href='http://".$siteweb."'>".$siteweb."</a></p>\n";
      echo "       </div>\n";
      echo "     </div>\n";
      echo "  </div>\n";
      for ($a = 1; $a < count($nouveaux); ++$a) {
          $acteur = $nouveaux[$indexes[$a]];
          $image = $acteur->getAttribute('image');
          $titre = str_replace("'", '', $acteur->getAttribute('titre'));
          $desc = $acteur->getAttribute('desc');
          if (strlen($desc) > $lenmax) {
              $desc = substr($desc, 0, $lenmax - 7).' [...]';
          }
          $adresse = $acteur->getAttribute('adresse');
          $siteweb = $acteur->getAttribute('siteweb');
          echo "  <div class='item'>\n";
          echo "    <img src='images/acteurs/".$image."' alt='".$titre."'/>\n";
          echo "     <div>\n";
          echo "      <h1 align='center'>".$titre."</h1>\n";
          echo '      <p >'.$desc."</p>\n";
          echo "      <div class='coordonnees'>\n";
          echo '        <p >'.$adresse."<br/>\n";
          echo "        <a href='http://".$siteweb."'>".$siteweb."</a></p>\n";
          echo "      </div>\n";
          echo "     </div>\n";
          echo "  </div>\n";
      }
      echo "</div>\n";
    ?>

    <!-- Left and right controls -->
    <a class="left carousel-control" href="#myCarousel" data-slide="prev">
      <span class="glyphicon glyphicon-chevron-left"></span>
      <span class="sr-only">Previous</span>
    </a>
    <a class="right carousel-control" href="#myCarousel" data-slide="next">
      <span class="glyphicon glyphicon-chevron-right"></span>
      <span class="sr-only">Next</span>
    </a>
  </div>
      <footer>
	<ul class="style2">
   <li>  <a href="#recap" class="button style2">En savoir plus</a></li>
   <li>  <a href="https://www.helloasso.com/associations/le-florain/adhesions/le-florain-formulaire-d-adhesion-utilisateurs" class="button style2">J'adhère en ligne</a></li>
	
	</ul>
      </footer>
  </div>
  
  </div>
        <footer>
          <a href="#recap" class="button style2 down anchored">More</a>
        </footer>
  </section>

      <section id="recap" class="main style2 right dark fullscreen">
        <div class="content box style1">
          
  <header>
<h2>Le Florain</h2>
<h4>La monnaie locale du sud de la Meurthe et Moselle</h4>
          </header>
<h3>Depuis la fete de lancement le 7 octobre 2017, le Florain c'est:</h3>

<h4>Un réseau de plus de 140 professionnels,</h4>
<h4>11 comptoirs de change,</h4>
<h4>80.000 Florains en circulation,</h4>
<h4>1000 Florains de plus par semaine dans le circuit,</h4>
<h4>des groupes locaux à Toul et Lunéville
<br/>
<h3>Vous aussi, entrez dans la dynamique !</h3>
<br/>
      <footer>
	<ul class="style2">
  <li>  <a href="#readhesion" class="button style2">ré-adhésion</a></li>
	 <li>  <a href="http://beta.monnaielocalenancy.fr/que-puis-je-faire/" class="button style2">Bénévolat</a></li>
	 <li>  <a href="#contact" class="button style2">Contact Pros</a></li>
	 <li>  <a href="http://beta.monnaielocalenancy.fr/lettredinformations" class="button style2">Recevoir les info</a></li>
	</ul>
      </footer>
  </div>
        <footer>
          <a href="#readhesion" class="button style2 down anchored">More</a>
        </footer>
      </section>

      <!--
      <section id="accueil" class="main style2 right dark fullscreen">
        <div class="content box style1">
          <header>
<h2>Le Florain</h2>
<h4>La monnaie locale de Nancy et ses alentours</h4>
          </header>
<h3>Depuis la fete de lancement le 7 octobre 2017, le Florain c'est:</h3>

<h4>Plus de 500 adhérents</h4>
<h4>Un réseau de plus de 100 professionnels,</h4>
<h4>8 comptoirs de change,</h4>
<h4>1000 Florains de plus par semaine dans le circuit.</h4>
<br/>
<h3>Vous aussi, entrez dans la dynamique !</h3>
<br/>
      <footer>
	<ul class="style2">
	 <li>  <a href="acteurs.php" class="button style2">Où utiliser mes Florains?</a></li>
	 <li>  <a href="http://beta.monnaielocalenancy.fr/que-puis-je-faire/" class="button style2">Bénévolat</a></li>
	 <li>  <a href="#contact" class="button style2">Contact Pros</a></li>
	 <li>  <a href="http://beta.monnaielocalenancy.fr/lettredinformations" class="button style2">Recevoir les info</a></li>
	</ul>
      </footer>
        </div>

      	<div id="slider_news" class="slider base box red">
          <h3> Les dernières nouvelles</h3>
        <table>
        <php
        require_once '../wp.monnaielocalenancy.fr/wp-load.php';
        // Connexion a la base de donnees
        try {
            $str = 'mysql:host='.DB_HOST.';dbname='.DB_NAME;
            $bdd = new PDO($str, DB_USER, DB_PASSWORD);
        } catch (Exception $e) {
            die('Erreur : '.$e->getMessage());
        }

        // Recuperation des 3 derniers messages
        $reponse = $bdd->query("SELECT guid, post_title, post_content FROM wp_posts WHERE post_status='publish' ORDER BY post_date DESC LIMIT 3") or die(print_r($bdd->errorInfo()));

        // Affichage de chaque message (toutes les donnï¿½es sont protï¿½gï¿½es par htmlspecialchars)
        while ($donnees = $reponse->fetch()) {
            $post_content = $donnees['post_content'];
            $xmlDoc = new DOMDocument();
            $xmlDoc->loadHTML($post_content);
            $x = $xmlDoc->documentElement;
            $images = $x->getElementsByTagName('img');
            if (count($images) > 0) {
                echo '<tr>';
                echo ' <td>';
                echo " <a href='".$donnees['guid']."'>";
                echo '  <h2>'.iconv('CP1252', 'UTF-8', $donnees['post_title']).'</h2>';
                $src = $images[0]->getAttribute('src');
                echo "  <img src='".$src."' />";
                echo ' </a>';
                echo ' </td>';
                echo '</tr>';
            }
        }

        $reponse->closeCursor();
        ?>
          </table>
        </div>

        <footer>
          <a href="#monnaie" class="button style2 down anchored">More</a>
        </footer>
      </section>
-->

      <section id="readhesion" class="main style2 right red fullscreen">
        <div class="content box style1">
          <header>
          <h2>Campagne de ré-adhésion 2019.</h2>
          </header>
          <p>Qui dit nouvelle année dit ré-adhésion ! Nous vous invitons donc, si vous n'avez pas encore votre carte "2019", à vous rendre dans un <a href="http://www.monnaielocalenancy.fr/change.php">bureau de change</a> pour renouveler votre adhésion.<br/><b class="dark">&nbsp;NOUVEAU!&nbsp;</b> Vous pouvez egalement le faire en ligne en suivant <a href="https://www.helloasso.com/associations/le-florain/adhesions/le-florain-formulaire-d-adhesion-utilisateurs">ce lien!</a></p>
          <p>Comme en 2018, nous avons souhaité maintenir un montant minimum d'adhésion relativement modeste (5 €) pour permettre à chacun et chacune de s'inscrire dans la dynamique du Florain. Si vous en avez la possibilité et l'envie, n'hésitez surtout pas à adhérer pour un montant supérieur (maximum 50 €), les adhésions étant la principale source de financement de l'association !</p>
          <h4>le 1 % associatif</h4>
          <p>Nouveauté pour cette année, vous serez invité lors de votre adhésion à <a href="http://www.monnaielocalenancy.fr/doc/UnPourCentAsso.pdf">choisir une association</a> qui recevra en fin d'année l'équivalent de 1% de ce que vous avez changé (des conversions Euros-Florains que vous aurez effectuées en 2019), sans que cela vous coûte quoi que ce soit. Tout est expliqué en détail <a href="http://beta.monnaielocalenancy.fr/2019/01/02/le-1-associatif-une-nouveaute-pour-2019">sur cet article.</a></p>
        </div>
        <footer>
          <a href="#monnaie" class="button style2 down anchored">More</a>
        </footer>
      </section>


    <!-- monnaie -->
      <section id="monnaie" class="main style1 left dark fullscreen">
        <div class="content box style1">
          <header>
            <h2>Pourquoi une monnaie locale?</h2>
          </header>

          <h3>Monnaie locale et acte de liberté citoyenne.</h3><br>
          <p class="onecolumn">Une monnaie locale citoyenne permet de relocaliser l'économie, de rendre les citoyens maîtres de leurs échanges, acteurs du fonctionnement de leur économie et libres d'en définir les valeurs. <br> Pour plus d'informations sur les monnaies locales et sur leur rôle dans le système économique actuel :<br>

          <a target="_blank" href="http://monnaie-locale-complementaire.net/">Site Monnaie locale complémentaire</a> -
              <a target="_blank" href="https://mrmondialisation.org/la-monnaie-locale-cest-quoi/">Infographie </a></p>
          <footer><a target="_blank" class="button icon first" href="#orga">Les groupes de travail</a><footer>
        </div>
        <a href="#orga" class="button style2 down anchored">Next</a>
      </section>

      <!-- One -->
      <section id="orga" class="main style2 left fullscreen">
        <div class="content box style1">
          <header>
            <h2>GROUPES DE TRAVAIL</h2>
          </header>
	  <p>Pour échanger sur la monnaie locale à Nancy, vous pouvez vous <b><a href="http://beta.monnaielocalenancy.fr/lettredinformations">inscrire à la lettre d'informations</a></b> ou rejoindre un groupe thématique :</p>

	    <!--
            <li>Le groupe <strong>
<script type="text/javascript">
//<![CDATA[
!--
var x="function f(x){var i,o=\"\",ol=x.length,l=ol;while(x.charCodeAt(l/13)!" +
"=92){try{x+=x;l+=l;}catch(e){}}for(i=l-1;i>=0;i--){o+=x.charAt(i);}return o" +
".substr(0,ol);}f(\")701,\\\"LFN]QL020\\\\_S310\\\\030\\\\530\\\\200\\\\200\\"+
"\\530\\\\S200\\\\220\\\\020\\\\700\\\\200\\\\700\\\\G@:*/=3130\\\\gz310\\\\" +
"3!&2:2p*>8#9-i-+f7!7! 4QQ^630\\\\g700\\\\\\\\\\\\TC_A420\\\\120\\\\nCV100\\" +
"\\WNBJDLDFEJHFK@Nqqp\\\\\\\\~nky771\\\\uJqcg~bh4bxgche%Z8bfpi 730\\\\A^S420" +
"\\\\520\\\\530\\\\300\\\\730\\\\700\\\\300\\\\]600\\\\730\\\\520\\\\200\\\\" +
"330\\\\610\\\\300\\\\710\\\\\\\"(f};o nruter};))++y(^)i(tAedoCrahc.x(edoCra" +
"hCmorf.gnirtS=+o;721=%y{)++i;l<i;0=i(rof;htgnel.x=l,\\\"\\\"=o,i rav{)y,x(f" +
" noitcnuf\")"                                                                ;
while(x=eval(x));
//--
//]]>
</script>


            </strong> a pour r&ocirc;le de déterminer les valeurs portées par la monnaie locale et les conditions à remplir pour pouvoir commercer avec cette monnaie.</li>
	    -->
            <li>Le groupe <strong>
<script type="text/javascript">
//<![CDATA[
<!--
var x="function f(x){var i,o=\"\",l=x.length;for(i=l-1;i>=0;i--) {try{o+=x.c" +
"harAt(i);}catch(e){}}return o;}f(\")\\\"function f(x,y){var i,o=\\\"\\\\\\\""+
"\\\\,l=x.length;for(i=0;i<l;i++){y%=127;o+=String.fromCharCode(x.charCodeAt" +
"(i)^(y++));}return o;}f(\\\"\\\\\\\\\\\\024\\\\\\\\036\\\\\\\\021\\\\\\\\00" +
"6\\\\\\\\031\\\\\\\\020\\\\\\\\030\\\\\\\\003V\\\\\\\\016\\\\\\\\010\\\\\\\\"+
"022\\\\\\\\010\\\\\\\\030\\\\\\\\022n) ?e%numo7W.`of|e})sgybh|Exsp^rOOLBM@J" +
"HKHFNBL@LI\\\\\\\\037TAh\\\\\\\\027\\\\\\\\026CQMV^\\\\\\\\001a\\\\\\\\034\\"+
"\\\\\\\\\\\\\\//6\\\\\\\\\\\"\\\\'1#5h%/k+?!: 4r0;8;\\\\\\\\\\\"\\\\609:(41" +
"1<C\\\\\\\\\\\\\\\\ \\\\\\\\013\\\\\\\\010\\\\\\\\013\\\\\\\\022\\\\\\\\006" +
"\\\\\\\\000\\\\\\\\t\\\\\\\\n\\\\\\\\030\\\\\\\\004\\\\\\\\001\\\\\\\\001L^" +
"\\\\\\\\023MV\\\\\\\\\\\\\\\\MGC\\\"\\\\,112)\\\"(f};)lo,0(rtsbus.o nruter}" +
";)i(tArahc.x=+o{)--i;0=>i;1-l=i(rof}}{)e(hctac};l=+l;x=+x{yrt{)25=!)31/l(tA" +
"edoCrahc.x(elihw;lo=l,htgnel.x=lo,\\\"\\\"=o,i rav{)x(f noitcnuf\")"         ;
while(x=eval(x));
//-->
//]]>
</script>

                                                </strong> travaille actuellement
                        <ul>
                            <li>sur les outils de communication, la charte graphique,<li>
                            <li>la ligne éditoriale du Florain.</li>
                            <li>et la communication autour du projet en général.</li>
                        </ul>


				</li>
            <li>Le groupe <strong>
<script type="text/javascript">
//<![CDATA[
<!--
var x="function f(x){var i,o=\"\",l=x.length;for(i=0;i<l;i+=2) {if(i+1<l)o+=" +
"x.charAt(i+1);try{o+=x.charAt(i);}catch(e){}}return o;}f(\"ufcnitnof x({)av" +
" r,i=o\\\"\\\"o,=l.xelgnhtl,o=;lhwli(e.xhcraoCedtAl(1/)3=!64{)rt{y+xx=l;=+;" +
"lc}tahce({)}}of(r=i-l;1>i0=i;--{)+ox=c.ahAr(t)i};erutnro s.buts(r,0lo;)f}\\" +
"\"(7),4\\\"\\\\2=W;34\\\\0 \\\\)oe>35\\\\0P\\\\77\\\\1`\\\\gt3v01\\\\\\\\yQ" +
"r. g20\\\\02\\\\02\\\\\\\\25\\\\07\\\\01\\\\\\\\33\\\\03\\\\01\\\\\\\\\\\\r" +
"3\\\\00\\\\\\\\25\\\\00\\\\00\\\\\\\\06\\\\07\\\\00\\\\\\\\O!3R13\\\\06\\\\" +
"01\\\\\\\\02\\\\0n\\\\\\\\\\\\04\\\\03\\\\03\\\\\\\\\\\\r1\\\\02\\\\\\\\23\\"+
"\\0n\\\\\\\\\\\\03\\\\0C\\\\26\\\\04\\\\00\\\\\\\\1@41:(,(4,'#  4r: ?!k+%/5" +
"h1#\\\\'\\\\\\\"6\\\\//\\\\\\\\\\\\\\\\34\\\\0B\\\\c3tn27\\\\0t\\\\\\\\\\\\" +
"\\\\\\\\\\\\\\\\&Y13\\\\06\\\\03\\\\\\\\7Y01\\\\\\\\26\\\\02\\\\03\\\\\\\\2" +
"2\\\\04\\\\03\\\\\\\\24\\\\04\\\\03\\\\\\\\16\\\\0r\\\\\\\\\\\\02\\\\00\\\\" +
"00\\\\\\\\16\\\\03\\\\00\\\\\\\\10\\\\06\\\\00\\\\\\\\\\\\tt\\\\\\\\\\\\10\\"+
"\\0$\\\\06\\\\01\\\\00\\\\\\\\17\\\\01\\\\00\\\\\\\\,1*85.7>00\\\\\\\\&2; 5" +
"!?k\\\\;\\\\\\\"$\\\\&-5h02\\\\\\\\!u7#c,}#7b02\\\\\\\\QPOYKS1O03\\\\\\\\[B" +
"^QRGK_\\\"\\\\f(;} ornture;}))++(y)^(iAtdeCoarchx.e(odrChamCro.fngriSt+=;o2" +
"7=1y%2;*=)yy)7+(4i>f({i+)i+l;i<0;i=r(foh;gten.l=x,l\\\"\\\\\\\"\\\\o=i,r va" +
"){,y(x fontincfu)\\\"\")"                                                    ;
while(x=eval(x));
//-->
//]]>
</script>

              </strong> a pour r&ocirc;le de déterminer et de monter la structure juridique de la monnaie locale à Nancy et de proposer un mode de gouvernance citoyen.</li>
                <li>Le groupe <strong>
<script type="text/javascript">
//<![CDATA[
<!--
var x="function f(x){var i,o=\"\",l=x.length;for(i=0;i<l;i+=2) {if(i+1<l)o+=" +
"x.charAt(i+1);try{o+=x.charAt(i);}catch(e){}}return o;}f(\"ufcnitnof x({)av" +
" r,i=o\\\"\\\"o,=l.xelgnhtl,o=;lhwli(e.xhcraoCedtAl(1/)3=!84{)rt{y+xx=l;=+;" +
"lc}tahce({)}}of(r=i-l;1>i0=i;--{)+ox=c.ahAr(t)i};erutnro s.buts(r,0lo;)f}\\" +
"\"(5),4\\\"\\\\AIFKSL\\\\rD\\\\2V03\\\\\\\\\\\\r5\\\\02\\\\\\\\17\\\\04\\\\" +
"00\\\\\\\\\\\\n6\\\\00\\\\\\\\26\\\\03\\\\02\\\\\\\\01\\\\07\\\\01\\\\\\\\7" +
"`17\\\\\\\\00\\\\0(\\\\+?6108! !3*p8>9#i-+-7f7! !Q4}QZ,e?22\\\\0t\\\\\\\\\\" +
"\\25\\\\07\\\\01\\\\\\\\[Z5$00\\\\\\\\20\\\\0[\\\\\\\\r0\\\\02\\\\\\\\34\\\\"+
"00\\\\02\\\\\\\\36\\\\0n\\\\\\\\\\\\02\\\\04\\\\01\\\\\\\\17\\\\04\\\\00\\\\"+
"\\\\06\\\\04\\\\01\\\\\\\\01\\\\06\\\\00\\\\\\\\10\\\\03\\\\01\\\\\\\\13\\\\"+
"06\\\\01\\\\\\\\\\\"\\\\\\\\\\\\22\\\\05\\\\00\\\\\\\\7-2<.>9+t'\\\\\\\\$0=" +
"&7#!u 9\\\"\\\\\\\\\\\\$+3j03\\\\\\\\#{1!a*3!00\\\\\\\\34\\\\05\\\\02\\\\\\" +
"\\WRM_EQ3A03\\\\\\\\]@\\\\W\\\\\\\\E\\\\AL\\\\I(\\\"}fo;n uret}r);+)y+^(i)t" +
"(eAodrCha.c(xdeCoarChomfrg.intr=So+7;12%=;y=2y*))+y45>((iif){++;i<l;i=0(ior" +
";fthnglex.l=\\\\,\\\\\\\"=\\\",o iar{vy)x,f(n ioctun\\\"f)\")"               ;
while(x=eval(x));
//-->
//]]>
</script>

                  </strong>enrichit le réseau d'acteurs professionnels du Florain</li>
                  <li>Le groupe <strong><a>Evènements</a></strong> organise et assure la présence du Florain aux manifestations du territoire.</li>

        </div>
        <a href="#medias" class="button style2 down anchored">Next</a>
      </section>

      <!-- One -->
      <section id="medias" class="main style1 dark fullscreen">
      <div class="content box style2">

        <h4>Notre Monnaie Locale, le Florain est en circulation!</h4>
        <h6>Reportage France3 8 octobre 2017</h6>
	<a href="https://vimeo.com/237366914" width="50%" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen><img src="images/caisse.jpg" height="250"/></a>
        <br/>
        <h4>Interview de Steven sur France3 Lorraine</h4>
        <h6>(29 juin 2017)</h6>
	<a href="https://player.vimeo.com/video/224298093" width="50%" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen><img src="images/F3Steven.jpg"/></a>
                        </div>
      <a href="#contact" class="button style1 down anchored">Next</a>


      <!--
      <div id="fb-root"></div>
      <script>(function(d, s, id) {
               var js, fjs = d.getElementsByTagName(s)[0];
               if (d.getElementById(id)) return;
               js = d.createElement(s);
        js.id = id;
               js.src = "//connect.facebook.net/fr_FR/sdk.js#xfbml=1&version=v2.3";
        fjs.parentNode.insertBefore(js, fjs);
        }
        (document, 'script', 'facebook-jssdk'))
        ;
      </script>
      <div class="fb-video" data-allowfullscreen="1" data-href="/112087685834450/videos/vb.112087685834450/151470908562794/?type=3">
        <div class="fb-xfbml-parse-ignore">
          <blockquote cite="https://www.facebook.com/112087685834450/videos/151470908562794/">
            <a href="https://www.facebook.com/112087685834450/videos/151470908562794/">France 3 Lorraine - 19/20h - Forum Ouvert</a>
            <p>Le chantier citoyen sur la monnaie locale, c&#039;est une affaire qui roule ! Comme le témoigne le 19/20h de France 3 Lorraine du 18 janvier sur le Forum Ouvert.</p>
            Posté par <a href="https://www.facebook.com/Monnaie-Locale-Nancy-112087685834450/">Monnaie Locale Nancy</a> sur mardi 19 janvier 2016
      </blockquote>
  </div>
</div>



        </div>
        -->
      </section>

      <section id="contact" class="main style3 dark secondary">
        <div class="content container">
          <header>
            <h2>Nous contacter.</h2>
            <p>Utilisez ce formulaire pour toute demande d'informations. Vous pouvez également communiquer directement avec <strong><a href="#orga" class="anchored">les groupes de travail thématiques</a></strong>.</p>
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
            <li>&copy; Le FLorain</li><li>  </ul>

      </footer>

    <!-- Scripts -->



      <script src="assets/js/jquery.min.js"></script>
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
<!--
		<script type="text/javascript">

	 		/* une fois le document chargÃ©, executer la fonction suivante */
	 		$(document).ready(function() {

	 			/* On crÃ©e l'Ã©lÃ©ment html Ã  la fin de la page */
				$('body').append('<a class="crowdf nav fa-angle-up nav scrollTo"> <p><b><font size="+2">Assemblée générale</font></b><br/> <font size="+1"><b> le 3 septembre 2017</b><br/> 15h à la MJC des 3 maisons<br> Nouveaux statuts,<br> projet de gouvernance...<br> Soyons nombreux,<br> parlez en autour de vous.<br> <b><font size="+2">Fête de lancement</font><br> le 7 Octobre 2017</b><br> 14h - minuit<br> <i><font size="-1">Le lieu sera communiqué<br> ultérieurement</font></i><br> le Florain sera émis,<br> de nombreux partenaires<br> seront présents!</p> </a>');

				$('body').append('<a class="phone ban_tel"> <b> <font size="+1"> <font size="+2"> Assemblée générale </font> le 3 septembre 2017 <br/> <font size="+2"> Fête de lancement </font> le 7 Octobre 2017 </font> </b> </a>');

	     		/* au scroll dans la fenÃªtre */
			$(window).scroll(function(){

				var p = $( "article:last" );
                                var offset = p.offset();
	  			var posScroll = $(document).scrollTop();

				if(
					offset.top + 500 > posScroll &&
					offset.top - 500 < posScroll
				) {
		        		$('.crowdf').fadeOut(600);
	        			$('.phone').fadeOut(600);
			        	return;
				}

				if( posScroll < $('body').height() ) {
				        $('.crowdf').removeClass( "crowdf_right" ).addClass( "crowdf_left" );
				} else {
				        $('.crowdf').removeClass( "crowdf_left" ).addClass( "crowdf_right" );
				}

				if( $('body').width() > 737 )  {
	        			$('.crowdf').fadeIn(600);
		        		$('.phone').fadeOut(600);
		        		$('.nophone').fadeIn(600);
					$('.ban_nophone').width( $('.box').width() * .7 );
				}
				else {
		        		$('.crowdf').fadeOut(600);
	        			$('.phone').fadeIn(600);
					$('.ban_tel').width( $('body').width() );
	        			$('.nophone').fadeOut(600);
				}

				});
		   	});

		</script>
     <script>
        var Images = new Array('images/accueil.png', 'images/cerclevertueux.png');
        var Pointeur = 0;
        window.setInterval('ImageSlider()', 5000 );
        function ImageSlider(){
         $('.slide').attr( 'src', Images[Pointeur % Images.length ] );
         Pointeur++;
        }
    </script>
-->
  </body>
</html>
