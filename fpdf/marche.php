<?php

require_once 'souscategorie.php';

class Exposant extends Acteur
{
    protected $is_comptoir_;

    public function setIsComptoir($is_comptoir)
    {
        $this->is_comptoir_ = $is_comptoir;
    }

    public function isComptoir()
    {
        if ($this->is_comptoir_ and $this->acteur_->hasAttribute('comptoir')) {
            $c = $this->acteur_->getAttribute('comptoir');
            if ($c == 'oui') {
                return true;
            }
        }

        return false;
    }
}

class ExposantPoche extends Exposant
{
    public function EnteteHeight()
    {
        $h = $this->getAttributeHeight('titre', 10, 'B');
        //$h = $h + $this->getAttributeHeight( "bref",8 );
        return $h;
    }

    public function height()
    {
        return $this->EnteteHeight();
    }

    public function Entete()
    {
        $left_entete = $this->a->GetX();
        $top_entete = $this->a->GetY();

        if ($this->isComptoir()) {
            $this->a->SetFillColor(234, 250, 180);
            $this->a->Rect(
                    $this->a->GetX() - 1,
                    $this->a->GetY() - 1,
                    $this->a->GetColumnWidth(),
                    $this->height() + 2, 'F'
                );
        }

        $titre = utf8_decode($this->acteur_->getAttribute('titre'));

        $this->a->SetY($top_entete);
        $this->a->SetX($left_entete);
        $this->a->SetLeftMargin($left_entete);

        // Le nom
        $this->a->PrintName($titre, $this->a->GetColumnWidth(), 10);

        /*
        if( $this->acteur_->hasAttribute( "bref" ) ) {
            $bref = utf8_decode( $this->acteur_->getAttribute( "bref" ) );
                    if( $bref != "" ) {
                $this->a->PrintText( $bref, $this->a->GetColumnWidth(), 8 );
                    }
        }
        */

        $this->a->Ln(2);

        // reset position
        $this->a->SetLeftMargin($left_entete);
        $this->a->SetX($left_entete);
        $cury = $this->a->GetY();
        $y = $top_entete + $this->EnteteHeight();
        // get the y max
        if ($cury < $y) {
            //$this->a->SetY( $top_entete + $this->EnteteHeight() );
            $this->a->debug('Curry : '.$cury.' ; y '.$y);
        }
    }

    public function display($col, $deb_i)
    {
        $this->a->SetCol($col);

        $this->separator($col, $deb_i);

        $this->Entete();
        $this->a->setDisplayed($this->acteur_);

        if ($col == 0) {
            $this->a->bas_col0 = $this->a->GetY();
        } else {
            $this->a->bas_col1 = $this->a->GetY();
        }
    }
}

class ExposantCompact extends Exposant
{
    public function EnteteHeight()
    {
        $h = $this->getAttributeHeight('titre', 8, 'B');
        //$h = $h + $this->getAttributeHeight( "bref",8 );
        return $h;
    }

    public function height()
    {
        return $this->EnteteHeight();
    }

    public function Entete()
    {
        $left_entete = $this->a->GetX();
        $top_entete = $this->a->GetY();

        if ($this->isComptoir()) {
            $this->a->SetFillColor(234, 250, 180);
            $this->a->Rect(
                $this->a->GetX() - 1,
                $this->a->GetY() - 1,
                $this->a->GetColumnWidth(),
                $this->height() + 2, 'F'
            );
        }

        $titre = utf8_decode($this->acteur_->getAttribute('titre'));

        $this->a->SetY($top_entete);
        $this->a->SetX($left_entete);
        $this->a->SetLeftMargin($left_entete);

        // Le nom
        $this->a->PrintText($titre, $this->a->GetColumnWidth(), 8, 'L');

        if ($this->acteur_->hasAttribute('bref')) {
            $bref = utf8_decode($this->acteur_->getAttribute('bref'));
            if ($bref != '') {
                $this->a->SetY($top_entete);
                $this->a->PrintText($bref, $this->a->GetColumnWidth(), 8, 'R');
            }
        }

        // reset position
        $this->a->SetLeftMargin($left_entete);
        $this->a->SetX($left_entete);
        $cury = $this->a->GetY();
        $y = $top_entete + $this->EnteteHeight();
        // get the y max
        if ($cury < $y) {
            //$this->a->SetY( $top_entete + $this->EnteteHeight() );
            $this->a->debug('Curry : '.$cury.' ; y '.$y);
        }
    }

    public function display($col, $deb_i)
    {
        // $this->a->SetCol(0);

        //$this->separator(0, 0);

        $this->Entete();
        $this->a->setDisplayed($this->acteur_);

        $this->a->bas_col0 = $this->a->GetY();
    }
}

/**************************************************************************/

class ExposantLivret extends Exposant
{
    public function height()
    {
        $h = $this->getAttributeHeight('titre', 14, 'B');
        $h = $h + $this->getAttributeHeight('bref');

        return $h;
    }

    public function display($col, $deb_i)
    {
        $this->a->SetCol($col);
        $this->separator($col, $deb_i);

        $titre = utf8_decode($this->acteur_->getAttribute('titre'));
        $bref = utf8_decode($this->acteur_->getAttribute('bref'));

        $this->a->PrintName($titre, 90, 14);
        $this->a->PrintText($bref, 90);
        $this->a->Ln();

        $this->a->setDisplayed($this->acteur_);

        if ($col == 0) {
            $this->a->bas_col0 = $this->a->GetY();
        } else {
            $this->a->bas_col1 = $this->a->GetY();
        }
    }
}

class Marche extends SousCategorie
{
    protected $tag_ = 'scat';
    protected $exposants = array();

    // SousCategorie Marche
    public function __construct($a, $cat, $scat)
    {
        $this->a = $a;
        $this->cat_ = $cat;
        $this->scat_ = $scat;
        $this->tag_ = 'scat';
        $id = utf8_decode($this->scat_->getAttribute('id'));
        $acteurs = $a->doc->getElementsByTagName('acteur');
        $nb_a = $acteurs->length;

        $idx = 0;
        for ($a = 0; $a < $nb_a; ++$a) {
            $acteur = $acteurs[$a];
            if (!$acteur->hasAttribute('marche')) {
                continue;
            }
            $marches = utf8_decode($acteur->getAttribute('marche'));
            $ids = preg_split("/[\s,]+/", $marches);
            $nb_ids = count($ids);

            for ($i = 0; $i < $nb_ids; ++$i) {
                if ($ids[$i] == $id) {
                    $this->exposants[$idx] = $acteur;
                    $idx = $idx + 1;
                    // remove displayed attribute
                    $acteur->removeAttribute('displayed');
                    $acteur->removeAttribute('tooFar');
                }
            }
        }
    }

    // SousCategorie Marche
    public function get_elements()
    {
        return $this->exposants;
    }

    // SousCategorie Marche
    public function get_elements_count()
    {
        return count($this->exposants);
    }

    // SousCategorie Marche
    public function typeAttribute()
    {
        return 'titre';
    }
}

class MarcheLivret extends Marche
{
    // SousCategorie MarcheLivret
    public function NewActeur($annuaire, $acteur)
    {
        $expo = new ExposantLivret($annuaire, $acteur);
        $expo->setIsComptoir($this->scat_->hasAttribute('message_comptoir'));

        return $expo;
    }

    // SousCategorie MarcheLivret
    public function displayType($suite = false)
    {
        if (!$this->scat_->hasAttribute($this->typeAttribute())) {
            return 'none';
        }
        $type = utf8_decode($this->scat_->getAttribute($this->typeAttribute()));

        $this->a->SetCol(0);
        // Title
        $label = '    '.$type;
        if ($suite == true) {
            $label = $label.'  (suite)';
        }

        $y1 = $this->a->GetY();
        $this->a->SetFont('Futura', 'B', 18);
        //$this->a->SetFillColor(204,220,62);
        $this->a->SetTextColor(0, 0, 0);
        $this->a->Cell($this->a->GetSubPageWidth(), 6, "$label", 'B', 1, 'L', false);
        $this->a->Ln(4);

        // Save ordinate
        $this->a->top_col = $this->a->GetY();
        $this->a->bas_col0 = $this->a->GetY();
        $this->a->bas_col1 = $this->a->GetY();
        $this->a->SetTextColor(0, 0, 0);

        return $type;
    }
}

class MarchePoche extends Marche
{
    public $titleCellHeight = 5;
    public $titleCellBotMargin = 2;

    // SousCategorie MarchePoche
    public function NewActeur($annuaire, $acteur)
    {
        $expo = new ExposantPoche($annuaire, $acteur);
        $expo->setIsComptoir($this->scat_->hasAttribute('message_comptoir'));

        return $expo;
    }

    // SousCategorie MarchePoche
    public function displayType($suite = false)
    {
        if (!$this->scat_->hasAttribute($this->typeAttribute())) {
            return 'none';
        }
        $lc = 1;
        $r = $this->a->getColor($this->scat_);
        $c = $r['town']->getColor();
        $this->a->SetFillColor($c[0] * 255, $c[1] * 255, $c[2] * 255);
        $this->a->Rect(
            $this->a->GetX(),
            $this->a->GetY() + 1,
            $lc,
            4, 'F'
        );

        $type = utf8_decode($this->scat_->getAttribute($this->typeAttribute()));

        // Title
        $label = '    '.$type;
        if ($suite == true) {
            $label = $label.'  (suite)';
        }
        $this->a->SetFont('Futura', 'I', 10);
        $this->a->SetFont('Steelfish', '', 12);
        $this->a->SetTextColor(0, 0, 0);
        $this->a->Cell($this->a->GetSubPageWidth() + $this->a->colMargin, $this->titleCellHeight, "$label", 'B', 1, 'L', false);
        $this->a->Ln($this->titleCellBotMargin);

        // Save ordinate
        $this->a->top_col = $this->a->GetY();
        $this->a->bas_col0 = $this->a->GetY();
        $this->a->bas_col1 = $this->a->GetY();

        return $type;
    }

    /*
    * Return true if we need to begin a new page before displaying the sub categorie
    *
    * The minimum height left needed is:
    * - the heigth of the ss cat title
    * - plus the max height of a pair of acteurs (one per column)
    */
    public function needNewPage($offset = 0)
    {
        $offset = $offset + $this->title_height();
        $acteurs = $this->get_elements();
        $nb = $this->get_elements_count();

        for ($pos = 0; $pos < $nb; ++$pos) {
            $acteur = $acteurs[$pos];
            if (!$this->candidate($acteur)) {
                continue;
            }
            $myActeur = $this->NewActeur($this->a, $acteur);

            if ($myActeur->height() + $offset > $this->a->SpaceLeftCol0(0)) {
                return true;
            }
            break;
        }

        return false;
    }
}

class MarcheCompact extends Marche
{
    public $titleCellHeight = 5;
    public $titleCellBotMargin = 2;

    // SousCategorie MarcheCompact
    public function NewActeur($annuaire, $acteur)
    {
        $expo = new ExposantCompact($annuaire, $acteur);
        $expo->setIsComptoir($this->scat_->hasAttribute('message_comptoir'));

        return $expo;
    }

    /*
    * Return true if we need to begin a new page before displaying the sub categorie
    *
    * The minimum height left needed is:
    * - the heigth of the ss cat title
    * - plus the max height of a pair of acteurs (one per column)
    */
    public function needNewPage($offset = 0)
    {
        $offset = $offset + $this->title_height();
        $acteurs = $this->get_elements();
        $nb = $this->get_elements_count();

        for ($pos = 0; $pos < $nb; ++$pos) {
            $acteur = $acteurs[$pos];
            if (!$this->candidate($acteur)) {
                continue;
            }
            $myActeur = $this->NewActeur($this->a, $acteur);

            if ($myActeur->height() + $offset > $this->a->SpaceLeftCol0(0)) {
                return true;
            }
            break;
        }

        return false;
    }

    // SousCategorie MarcheCompact
    public function displayType($suite = false)
    {
        if (!$this->scat_->hasAttribute($this->typeAttribute())) {
            return 'none';
        }

        $type = utf8_decode($this->scat_->getAttribute($this->typeAttribute()));

        $lc = 1;
        $r = $this->a->getColor($this->scat_);
        $c = $r['town']->getColor();
        $this->a->SetFillColor($c[0] * 255, $c[1] * 255, $c[2] * 255);
        $this->a->Rect(
            $this->a->GetX(),
            $this->a->GetY() + 1,
            $lc,
            4, 'F'
        );
        // Title
        $label = $type;
        if ($suite == true) {
            $label = $label.'  (suite)';
        }
        $this->a->Ln($this->titleCellBotMargin);
        $this->a->PrintName($label, $this->a->GetColumnWidth(), 10, 'L');
        //$this->a->SetFont('Futura', 'I', 10);
        //$this->a->SetTextColor(0, 0, 0);
        //$this->a->Cell($this->a->GetSubPageWidth() + $this->a->colMargin, $this->titleCellHeight, "$label", 'B', 1, 'L', false);
        //$this->a->Ln($this->titleCellBotMargin);

        // Save ordinate
        $this->a->top_col = $this->a->GetY();
        $this->a->bas_col0 = $this->a->GetY();
        $this->a->bas_col1 = $this->a->GetY();

        return $type;
    }

    // SousCategorie MarcheCompact
    public function display()
    {
        $nb = $this->get_elements_count();
        if ($nb == 0) {
            return;
        }

        if ($this->needNewPage()) {
            $this->a->NextPage();
            $this->cat_->displayType(true);
        }
        $type = $this->displayType();

        $deb_i = 1;
        $acteurs = $this->get_elements();
        for ($pos = 0; $pos < $nb; ++$pos) {
            $acteur = $acteurs[$pos];
            if (!$this->candidate($acteur)) {
                continue;
            }
            $myActeur = $this->NewActeur($this->a, $acteur);

            if ($myActeur->height() > $this->a->SpaceLeftCol0(0)) {
                $this->a->NextPage();
                $this->a->SetCol(0);
                $ret = $this->displayType(true);
                if ($ret == 'none') {
                    $this->cat_->displayType(true);
                }
                $deb_i = 1;
            }

            $myActeur->display(0, $deb_i);
            $deb_i = $deb_i + 1;
        }

        return [];
    }
}

///////////////////////////////////////////////////////////////////////////////

class CategorieMarcheLivret extends CategorieLivret
{
    protected $tag_ = 'scat';

    public function typeAttribute()
    {
        return 'type';
    }

    public function NewSousCategorie($a, $sscat)
    {
        return new MarcheLivret($a, $this, $sscat);
    }
}

class CategorieMarchePoche extends CategoriePoche
{
    protected $tag_ = 'scat';

    public function typeAttribute()
    {
        return 'type';
    }

    public function NewSousCategorie($a, $sscat)
    {
        return new MarchePoche($a, $this, $sscat);
    }
}

class CategorieMarcheCompact extends CategorieCompact
{
    protected $tag_ = 'scat';

    public function typeAttribute()
    {
        return 'type';
    }

    public function NewSousCategorie($a, $sscat)
    {
        return new MarcheCompact($a, $this, $sscat);
    }
}
