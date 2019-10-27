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
    <title>Le Florain: Monnaie Locale et Complementaire de l'Aire de Vie Nanceienne</title>
    <meta name="description" content="Site web dedié a la création de la monnaie locale solidaire et citoyenne pour Nancy et ses environs. Le Florain est la dénomination désignée par les futurs usagés!"/>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <!--[if lte IE 8]><script src="assets/js/ie/html5shiv.js"></script><![endif]-->
    <link rel="stylesheet" href="assets/css/main.css" />
    <link rel="stylesheet" href="assets/css/nav.css" />
    <link rel="stylesheet" href="style.css" />
    <!--[if lte IE 8]><link rel="stylesheet" href="assets/css/ie8.css" /><![endif]-->
    <!--[if lte IE 9]><link rel="stylesheet" href="assets/css/ie9.css" /><![endif]-->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
<?php
      $xmlDoc = new DOMDocument();
      $xmlDoc->load('acteurs-cat.xml');
      $x = $xmlDoc->documentElement;

      // Generate the script to show / hide categories
      $cat_ids = array();
      $btn_ids = array();
      $idx = 0;

      $categories = $x->getElementsByTagName('categorie');
      $nb_cat = $categories->length;

      for ($cat = 0; $cat < $nb_cat; ++$cat) {
          $categorie = $categories[$cat];
          if ($categorie->hasAttribute('type') &&
              $categorie->getAttribute('type') != ''
          ) {
              $sous_categories = $categorie->getElementsByTagName('scat');
              $nb_sous_cat = $sous_categories->length;
              if ($nb_sous_cat > 1) {
                  for ($scat = 0; $scat < $nb_sous_cat; ++$scat) {
                      $id = '#cat'.$cat.'scat'.$scat;
                      $cat_ids[$idx] = $id;
                      $id = '#press_cat'.$cat.'scat'.$scat;
                      $btn_ids[$idx] = $id;
                      $idx = $idx + 1;
                  }
              } else {
                  $id = '#cat'.$cat;
                  $cat_ids[$idx] = $id;
                  $id = '#press_cat'.$cat;
                  $btn_ids[$idx] = $id;
                  $idx = $idx + 1;
              }
          }
      }

      $cat_ids[$idx] = '#marches';
      $btn_ids[$idx] = '#press_marches';

      $cat_displayed = rand(0, count($btn_ids) - 1);
      echo "<script>\n";
      echo "$(document).ready(function(){\n";
      for ($b = 0; $b < count($btn_ids); ++$b) {
          echo "$('".$btn_ids[$b]."').click(function(){\n";
          for ($c = 0; $c < count($cat_ids); ++$c) {
              if ($b == $c) {
                  echo "$('".$cat_ids[$c]."').show();";
              } else {
                  echo "$('".$cat_ids[$c]."').hide();";
              }
          }
          echo "});\n";
      }
      echo "});\n";
      echo "</script>\n";

 ?>

  </head>
  <body id="top">
<?php

function is_hidden($cat, $cat_displayed)
{
    if ($cat != $cat_displayed) {
        return 'hidden';
    }

    return '';
}
      $header = new Header();
      $header->display();

      echo "<div id='acteurs'>";
      $header->display_acteurs_nav();

      echo '<h2>'.$x->getAttribute('titre').'</h2>';

      echo "<section class='paragraphe'>";

      echo "<section class='column toc'>";

      for ($cat = 0; $cat < $nb_cat; ++$cat) {
          $categorie = $categories[$cat];
          if ($categorie->hasAttribute('type') &&
              $categorie->getAttribute('type') != ''
          ) {
              $acteurs = $categorie->getElementsByTagName('acteur');
              $nb = $acteurs->length;
              echo "<h4 class='toc'><a href='#acteurs' id='press_cat".$cat."'>".$categorie->getAttribute('type').' ('.$nb.')</a></h4>';
              $sous_categories = $categorie->getElementsByTagName('scat');
              $nb_sous_cat = $sous_categories->length;
              for ($scat = 0; $scat < $nb_sous_cat; ++$scat) {
                  $sous_categorie = $sous_categories[$scat];
                  if ($sous_categorie->hasAttribute('type') &&
                      $sous_categorie->getAttribute('type') != ''
                  ) {
                      $acteurs = $sous_categorie->getElementsByTagName('acteur');
                      $nb = $acteurs->length;
                      echo "<h5 class='toc' ><a href='#acteurs' id='press_cat".$cat.'scat'.$scat."'>".$sous_categorie->getAttribute('type').' ('.$nb.')</a></h5>';
                  }
              }
          }
      }
      $marche_cat = $x->getElementsByTagName('marches');
      $marches = $marche_cat[0]->getElementsByTagName('scat');
      $nb_marches = $marches->length;
      echo "<h4 class='toc'><a href='#acteurs' id='press_marches'>".$marche_cat[0]->getAttribute('type').' ('.$nb_marches.')</a></h4>';
      echo '</section>';

      echo "<div id='show'>";
      $cat_idx = 0;
      for ($cat = 0; $cat < $nb_cat; ++$cat) {
          $categorie = $categories[$cat];
          $sous_categories = $categorie->getElementsByTagName('scat');
          $nb_sous_cat = $sous_categories->length;
          if (!$categorie->hasAttribute('type')) {
              continue;
          }
          if ($nb_sous_cat > 1) {
              echo "<div id='cat".$cat."'>";
          } else {
              echo "<div id='cat".$cat."' ".is_hidden($cat_idx++, $cat_displayed).'>';
              echo "<h1 id='cat".$cat."'>".$categorie->getAttribute('type').'</h1>';
          }

          for ($scat = 0; $scat < $nb_sous_cat; ++$scat) {
              $sous_categorie = $sous_categories[$scat];

              if ($sous_categorie->hasAttribute('type') &&
                  $sous_categorie->getAttribute('type') != ''
              ) {
                  echo "<div id='cat".$cat.'scat'.$scat."' ".is_hidden($cat_idx++, $cat_displayed).'>';
                  echo '<h1>'.$categorie->getAttribute('type').' / '.$sous_categorie->getAttribute('type').'</h1>';
              } else {
                  echo "<div id='cat".$cat.'scat'.$scat."'>";
              }
              $acteurs = $sous_categorie->getElementsByTagName('acteur');
              $nb = $acteurs->length;

              echo "<section class='column'>";

              $indexes = range(0, $nb - 1);
              shuffle($indexes);
              for ($pos = 0; $pos < $nb; ++$pos) {
                  $acteur = $acteurs[$indexes[$pos]];
                  if ($acteur->hasAttribute('attente')) {
                      continue;
                  }
                  $acteur_class = 'commerce';
                  if ($acteur->hasAttribute('comptoir') &&
                      $acteur->getAttribute('comptoir') == 'oui') {
                      $acteur_class = 'comptoir';
                  }

                  $image = $acteur->getAttribute('image');
                  $siteweb = '';

                  $titre = $acteur->getAttribute('titre');
                  $bref = $acteur->getAttribute('bref');
                  $desc = $acteur->getAttribute('desc');
                  $adresse = $acteur->getAttribute('adresse');

                  $p = <<<EOD
                  <acteur class="$acteur_class">
                  <p align='right'><u>$bref</u></p>
                    <h2>$titre</h2>
                    <img src="images/acteurs/$image" alt="$titre" />
EOD;
                  echo $p;
                  if ($acteur_class == 'comptoir') {
                      echo '<h3>Comptoir de change</h3>';
                  }
                  if ($acteur->hasAttribute('message')) {
                      echo "<p class='message'>".$acteur->getAttribute('message').'</p>';
                  }
                  $p = <<<EOD
                  <p>$desc</p>
                  <p>$adresse</p>
EOD;
                  echo $p;
                  if ($acteur->hasAttribute('siteweb')) {
                      $siteweb = $acteur->getAttribute('siteweb');
                      $p = <<<EOD
                      <a href="http://$siteweb">$siteweb</a>
EOD;
                      echo $p;
                  }
                  echo '</acteur>';
              }
              echo '</section>';
              echo '</div>';
          }
          echo '</div>';
      }

      /* marches */

      $tous_les_acteurs = $x->getElementsByTagName('acteur');
      $nb_a = $tous_les_acteurs->length;

      $exposants = array();

      $indexes = array();
      for ($a = 0; $a < $nb_a; ++$a) {
          $acteur = $tous_les_acteurs[$a];
          if (!$acteur->hasAttribute('marche')) {
              continue;
          }
          $les_marches = utf8_decode($acteur->getAttribute('marche'));
          $ids = preg_split("/[\s,]+/", $les_marches);
          $nb_ids = count($ids);

          for ($i = 0; $i < $nb_ids; ++$i) {
              if (empty($indexes[$ids[$i]])) {
                  $indexes[$ids[$i]] = 0;
              }
              $exposants[$ids[$i]][$indexes[$ids[$i]]] = $acteur;
              $indexes[$ids[$i]] = $indexes[$ids[$i]] + 1;
          }
      }

      $indexes = range(0, $nb_marches - 1);
      shuffle($indexes);
      echo "<div id='marches' ".is_hidden($cat_idx++, $cat_displayed).'>';
      echo '<h1>'.$marche_cat[0]->getAttribute('type').'</h1>';
      echo "<section class='column'>";
      for ($pos = 0; $pos < $nb_marches; ++$pos) {
          $acteur = $marches[$indexes[$pos]];
          if ($acteur->hasAttribute('attente')) {
              continue;
          }
          $acteur_class = 'commerce';

          $image = $acteur->getAttribute('image');
          $siteweb = '';

          $titre = $acteur->getAttribute('titre');
          $desc = $acteur->getAttribute('desc');
          $adresse = $acteur->getAttribute('adresse');

          $p = <<<EOD
        <acteur class="$acteur_class">
          <h2>$titre</h2>
          <img src="images/acteurs/$image" alt="$titre" />
EOD;
          echo $p;
          if ($acteur_class == 'comptoir') {
              echo '<h3>Comptoir de change</h3>';
          }
          if ($acteur->hasAttribute('message')) {
              echo "<p class='message'>".$acteur->getAttribute('message').'</p>';
          }
          $p = <<<EOD
        <p>$desc</p>
        <p>$adresse</p>
EOD;
          echo $p;
          if ($acteur->hasAttribute('siteweb')) {
              $siteweb = $acteur->getAttribute('siteweb');
              $p = <<<EOD
            <a href="http://$siteweb">$siteweb</a>
EOD;
              echo $p;
          }

          $p = <<<EOD
        <p><b>Retrouvez:</b></p>
EOD;
          echo $p;

          $id = $acteur->getAttribute('id');
          $nb_e = count($exposants[$id]);
          $idx_e = range(0, $nb_e - 1);
          shuffle($idx_e);
          for ($e = 0; $e < $nb_e; ++$e) {
              $expo = $exposants[$id][$idx_e[$e]];

              if ($expo->hasAttribute('attente')) {
                  continue;
              }
              $message_comptoir = 'none';
              $acteur_class = 'commerce';
              if ($expo->hasAttribute('comptoir') &&
              $expo->getAttribute('comptoir') == 'oui') {
                  if ($acteur->hasAttribute('message_comptoir')) {
                      $acteur_class = 'comptoir';
                      $message_comptoir = $acteur->getAttribute('message_comptoir');

                      $p = <<<EOD
EOD;
                      echo $p;
                  }
              }

              $titre = $expo->getAttribute('titre');
              $bref = $expo->getAttribute('bref');

              echo "<p class='".$acteur_class."'><b>".$titre.':</b> '.$bref;
              if ($message_comptoir != 'none') {
                  echo '<br/>'.$message_comptoir;
              }
              echo '</p>';
          }

          echo '</acteur>';
      }
      echo '</section>';
      echo '</div>';
?>
</div> <!-- top -->
</section> <!-- paragraphe -->


          </div>


      <footer id="footer">
        <!-- Icons -->
          <ul class="actions">
            <li><a target="_blank" href="https://www.facebook.com/LeFlorain" class="icon fa-facebook"><span class="label">Facebook</span></a></li>
            <li><a target="_blank" href="https://twitter.com/LeFlorain" class="icon fa-twitter"><span class="label">Twitter</span></a></li>
          </ul>
        <!-- Menu -->
          <ul class="menu"> <li>&copy; Le Florain</li> </ul>
      </footer>
      </div>

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

  </body>
</html>
