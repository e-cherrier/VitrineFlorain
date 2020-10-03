
<?php

class Header
{
    public function display()
    {
        $p = <<<EOD
    <!-- Header -->
      <header id="header">

        <!-- Logo -->
          <h1 id="logo"><a href="index.php#top"><img src="images/logo-monnaie.svg" height="100%"/><span>Le Florain</span></a></h1>


        <!-- Nav -->
            <nav id="nav" class="desktop">
              <ul>
                <li><a href="index.php#monnaie">Pourquoi ?</a>
                      <ul>
                        <li> <a href="index.php#monnaie">Pourquoi une Monnaie locale citoyenne?</a></li>
                        <li> <a href="index.php#comment">Comment ca marche ?</a></li>

                        <li> <a href="http://beta.monnaielocalenancy.fr/bienvenue-a-la-rencontre-de-notre-future-monnaie-citoyenne/notre-charte/">Notre Charte</a></li>
                        <li> <a href="http://beta.monnaielocalenancy.fr/bienvenue-a-la-rencontre-de-notre-future-monnaie-citoyenne/statuts-de-lassociation">Les statuts de l'association</a></li>
                        <li> <a href="documents.php">Télécharger les plaquettes, logos et bien plus...</a></li>
                        <li><a href="http://beta.monnaielocalenancy.fr/faq">FAQ</a></li>
                        <li> <a href="index.php#orga">Les groupes de travail</a></li>
                        <li><a href="index.php#medias">Dans les médias !</a>
                          <ul>
                            <li> <a href="index.php#medias">France 3</a></li>
                            <li> <a href="index.php#medias">France Bleu Sud Lorraine</a></li>
                          </ul>
                        </li>
                      </ul>
                </li>
                <li><a href="change.php">Où me les procurer ?</a></li>
                <li><a href="acteurs.php">Où les dépenser ?</a>
                  <ul>
                    <li> <a href="acteurs.php">la liste détaillée</a></li>
                    <li> <a>les annuaires de poche</a>
                      <ul>
                        <li> <a target="_blank" href="annuaire.php?edition=Lunéville">Edition de Lunéville</a></li>
                        <li> <a target="_blank" href="annuaire.php?edition=Nancy">Edition de Nancy</a></li>
                        <li> <a target="_blank" href="annuaire.php?edition=Pont-à-Mousson">Edition de Pont-à-Mousson</a></li>
                        <li> <a target="_blank" href="annuaire.php?edition=Toul">Edition de Toul</a></li>
                        <li> <a target="_blank" href="annuaire.php?edition=Badonviller">Edition de Badonviller</a></li>
                      </ul>
                    </li>
                    <li> <a target="_blank" href="annuaire.php?type=Livret">l'annuaire en livret</a></li>
                    <li> <a href="carte.php">la carte</a></li>
                  </ul>
                </li>
                <li><a href="http://beta.monnaielocalenancy.fr">Actualités</a></li>
                <li><a href="index.php#contact">Contacts</a></li>

                <li><a><span class="icon fa fa-bars"></span></a>
                  <ul>
                    <li><a target="_blank" href="https://www.facebook.com/LeFlorain" class="icon fa-facebook">  Facebook</a></li>
                    <li><a target="_blank" href="https://twitter.com/LeFlorain" class="icon fa-twitter"> Twitter</a></li>
		              </ul>
                </li>
              </ul>
            </nav>
            <nav id="nav" class="app">
              <ul>
                <li><a><span class="icon fa fa-bars"></span></a>
                  <ul>
                    <li><a href="change.php"><span class="icon fa fa-calculator"></span>  Où me les procurer?</a></li>
                    <li><a href="acteurs.php"><span class="icon fa fa-server"></span>  Où les dépenser?</a></li>
                    <li><a href="carte.php"><span class="icon fa fa-compass"></span>  La carte</a></li>
                    <li><a href="http://beta.monnaielocalenancy.fr">Actualités</a></li>
                    <li><a href="index.php#contact">Contacts</a></li>
                  </ul>
                </li>
              </ul>
            </nav>
      </header>
EOD;
        echo $p;
    }

    public function display_acteurs_nav()
    {
        $p = <<<EOD
	    <div  class="actions dark desktop" >
               <a id="press_liste" href="acteurs.php" class="button style2 icon first">Liste</a>
               <a id="press_carte" href="carte.php" class="button style2 icon first">Carte</a>
               <a id="press_comptoirs" href="change.php" class="button style2 icon first">Comptoirs</a>
               <a id="press_imprim" target="_blank" href="annuaire.php?type=Compact" class="button style2 icon first">Annuaire de Poche</a>
               <a id="press_annuaire" target="_blank" href="annuaire.php?type=Livret" class="button style2 icon first">Annuaire en Livret</a>
            </div>
EOD;
        echo $p;
    }
}
