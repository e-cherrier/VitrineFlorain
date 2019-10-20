<?php

require 'fpdf/fpdf.php';
require 'fpdf/categorie.php';
require 'fpdf/marche.php';
include 'pix_tools.php';

$townArea = .59;
$towns = array(
    new Ville('Nancy', 6.180794, 48.692442, [90.6, 45.1, 62]),
    new Ville('Toul', 5.891387, 48.675334, [32.5, 73.7, 71]),
    new Ville('Pont-à-Mousson', 6.053787, 48.902677, [62.4, 78.8, 87.5]),
    new Ville('Lunéville', 6.495079, 48.591822, [96.1, 61.2, 21.2], 'L'),
    new Ville('Tezey-St-Martin', 6.294456, 48.900973, [61.2, 73.3, 24.3], 'B'),
    new Ville('Colombey-les-Belles', 5.897124, 48.528123, [32.5, 96.1, 45.1]),
    new Ville('Vézelise', 6.092136, 48.481914, [96.1, 96.1, 45.1], 'B'),
);
$blank = new Ville('blank', 6, 48, [100, 100, 100]);

class Ville
{
    public $nom;
    public $lat;
    public $lon;
    public $col;
    public $nb = 0;
    public $pos;
    public $x;
    public $y;

    public function __construct($nom, $lat, $lon, $col, $pos = 'A')
    {
        $this->nom = $nom;
        $this->lat = $lat;
        $this->lon = $lon;
        $this->col = $col;
        $this->pos = $pos;
    }

    public function add()
    {
        $this->nb = $this->nb + 1;
    }

    public function setXY($x, $y)
    {
        $this->x = $x;
        $this->y = $y;
    }
}
/**************************************************************************/
class Annuaire extends FPDF
{
    public $doc = null; // the xml doc.
    public $col = 0; // Current column
    public $top_col;      // Ordinate of column start
    public $bas_col0;      // Ordinate of column 1 end
    public $bas_col1;      // Ordinate of column 2 end
    public $nbSubPages = 4; // A page is split in 4 ssPages containing 2 columns each => 8 columns
    public $subPage = 0; // Current ssPage
    public $topSubPage = 3; // Ordinate of sub page start

    public function Header()
    {
        // Save ordinate
        $this->top_col = $this->GetY() + $this->topSubPage;
        $this->bas_col0 = $this->GetY() + $this->topSubPage;
        $this->bas_col1 = $this->GetY() + $this->topSubPage;

        global $no_header;
        if ($no_header) {
            return;
        }

        // Page header
        global $title;

        $this->SetFont('Futura', '', 10);
        $w = $this->GetStringWidth($title) + 6;
        $this->SetX((210 - $w) / 2);
        $this->SetTextColor(0, 0, 0);
        $this->SetLineWidth(1);
        $this->Cell($w, 9, $title, 0, 1, 'C', false);
        $this->Ln(5);
        // Save ordinate
        $this->top_col = $this->GetY();
        $this->bas_col0 = $this->GetY();
        $this->bas_col1 = $this->GetY();
        $this->col = 0;
    }

    public function Footer()
    {
        global $no_footer;
        if ($no_footer) {
            return;
        }
        // Page footer
        $this->SetY(-15);
        $this->SetFont('Futura', 'I', 8);
        $this->SetTextColor(128);
        $this->Cell(0, 10, 'Page '.($this->PageNo() - 1), 0, 0, 'C');
    }

    public function SetCol($col)
    {
        // ( $this->colMargin*(2*$this->subPage+1) + $this->GetColumnWidth()*$this->subPage*2),
        // Set position at a given column
        $this->col = $col;
        // $x = $col * $this->GetColumnWidth() + $this->GetSubPageWidth() * $this->subPage + ($col + $this->marginLeft + 2 * $this->subPage) * $this->colMargin;
        $x = $col * ($this->GetColumnWidth() + $this->colMargin);
        $x = $x + ($this->GetSubPageWidth() + $this->colMargin) * ($this->subPage);
        $x = $x + ($this->marginLeft);

        $this->SetLeftMargin($x);
        $this->SetX($x);
    }

    public function AcceptPageBreak()
    {
        return false;
    }

    public function resetColumn()
    {
        $this->SetCol(0);
        $this->SetY(max($this->bas_col1, $this->bas_col0));
        $this->bas_col0 = $this->getY();
        $this->bas_col1 = $this->getY();
    }

    public function SpaceLeftCol0($offset)
    {
        // 20 is the default bottom margin
        return $this->GetPageHeight() - $this->bMargin - $this->bas_col0 - $offset;
    }

    public function SpaceLeftCol1($offset)
    {
        // 20 is the default bottom margin
        return $this->GetPageHeight() - $this->bMargin - $this->bas_col1 - $offset;
    }

    public function TextWidth($texte, $s = 10, $m = '', $f = 'Futura')
    {
        // Font
        $this->SetFont($f, $m, $s);

        return $this->GetStringWidth($texte);
    }

    public function PrintText($texte, $s = 60, $f = 10, $align = 'C')
    {
        // Font
        $this->SetTextColor(0, 0, 0);
        $this->SetFont('Futura', '', $f);
        $this->MultiCell($s, $this->cellHeight, $texte, 0, $align);
    }

    public function PrintBullet($name, $s = 6, $size = 10, $c = 'C', $f = 'Futura', $w = 'B')
    {
        // Font
        $this->SetTextColor(255, 0, 0);
        $this->SetFont($f, $w, $size);
        $this->Cell($s, $this->cellHeight, $name);
    }

    public function PrintName($name, $s = 60, $size = 10, $c = 'C', $f = 'Futura', $w = 'B')
    {
        // Font
        $this->SetTextColor(112, 112, 111);
        $this->SetFont($f, $w, $size);
        $this->MultiCell($s, $this->cellHeight, $name, 0, $c);
    }

    public function PrintValue($texte, $s = 90, $f = 12, $align = 'L')
    {
        // logo
        $this->Image('images/logo-monnaie-disquevert.png',
            $this->GetX() - 7, $this->GetY() + 3, 5
        );

        // Font
        $this->SetFont('Futura', '', $f);
        // Output text in a 6 cm width column
        $this->MultiCell($s, $this->cellHeight, $texte, 0, $align);
    }

    /* return true if at least one entry will be print
    */
    public function is_category_represented($parent, $xml, $tag)
    {
        if ($tag == 'scat') {
            $xml = $parent;
        }

        $acteurs = $xml->getElementsByTagName($tag);

        return count($this->entries_to_display($acteurs));
    }

    public function entries_to_display($list)
    {
        global $km;
        global $latRef;
        global $lonRef;
        $nb = $list->length;
        if ($km < 0) {
            return $list;
        }

        $acteurs = [];

        $count = 0;
        for ($pos = 0; $pos < $nb; ++$pos) {
            $acteur = $list[$pos];
            if ($acteur->hasAttribute('tooFar')) {
                continue;
            }
            if (!$acteur->hasAttribute('longitude') ||
                !$acteur->hasAttribute('latitude')
            ) {
                $acteurs[$count] = $acteur;
                ++$count;
                continue;
            }

            $lon = $acteur->getAttribute('longitude');
            $lat = $acteur->getAttribute('latitude');
            $dist = $this->calcCrow($lat, $lon, $latRef, $lonRef);
            if ($dist > $km) {
                $acteur->setAttribute('tooFar', 'really');
                continue;
            }
            $acteurs[$count] = $acteur;
            ++$count;
        }

        return $acteurs;
    }

    public function printLegend()
    {
        $this->SetLineWidth(0);
        $lc = .5;
        $y = $this->GetY();
        $tot = 0;

        for ($lon = 49.00; $lon > 48.41; $lon -= .01) {
            $x = $this->GetX();
            for ($lat = 5.78; $lat < 6.60; $lat += .01) {
                $r = $this->doGetColor($lat, $lon);
                $town = $r['town'];
                $c = $town->col;
                if ($r['black']) {
                    $savedX = $this->GetX();
                    $savedY = $this->GetY();
                    $xt = max($x - 5, 0);
                    if ($town->pos == 'B') {
                        $yt = $y + .5;
                    } else {
                        $yt = $y - 3;
                    }
                    if ($town->pos == 'L') {
                        $xt = $xt - 3;
                    }
                    $town->SetXY($xt, $yt);

                    $tot = $tot + $town->nb;

                    $c = [0, 0, 0];
                    $this->SetXY($savedX, $savedY);
                }

                $this->SetFillColor($c[0] * 2.56, $c[1] * 2.56, $c[2] * 2.56);
                $this->SetDrawColor($c[0] * 2.56, $c[1] * 2.56, $c[2] * 2.56);
                $this->Rect($x, $y,
                    $lc,
                    $lc, 'F'
                );
                $x = $x + $lc - .1;
            }
            $y = $y + $lc - .1;
        }

        global $towns;
        foreach ($towns as $town) {
            $this->SetXY($town->x, $town->y);
            $this->PrintText(utf8_decode($town->nom).' '.$town->nb, 25, 6, 'L');
        }

        $this->SetXY($x - 2, $y - 2);
        global $blank;
        $this->ln();
        $tot = $tot + $blank->nb;
        $this->PrintText('Nombre total d\'acteurs: '.$tot.', dont '.$blank->nb.utf8_decode(' non géolocalisés'), 90, 6, 'L');
    }

    public function getColor($acteur)
    {
        global $blank;
        $ret = array();
        $ret['town'] = $blank;
        $ret['black'] = false;
        if (!$acteur->hasAttribute('longitude') ||
        !$acteur->hasAttribute('latitude')
        ) {
            return $ret;
        }
        $lat = $acteur->getAttribute('latitude');
        $lon = $acteur->getAttribute('longitude');

        return $this->doGetColor($lat, $lon);
    }

    public function doGetColor($lat, $lon)
    {
        global $townArea;
        global $towns;
        $minDist = 9999;
        $minKey = 0;
        $ret = array();
        $ret['black'] = true;
        $ret['town'] = null;
        foreach ($towns as $key => $town) {
            $dist = $this->calcCrow($lat, $lon, $town->lat, $town->lon);
            if ($dist < $townArea) {
                $ret['town'] = $town;

                return $ret;
            }

            if ($dist < $minDist) {
                $minDist = $dist;
                $minKey = $key;
            }
        }

        $ret['black'] = false;
        $ret['town'] = $towns[$minKey];

        return $ret;
    }

    public function calcCrow($lat1, $lon1, $lat2, $lon2)
    {
        $R = 6371; // km
        $dLat = $this->toRad($lat2 - $lat1);
        $dLon = $this->toRad($lon2 - $lon1);
        $lat1 = $this->toRad($lat1);
        $lat2 = $this->toRad($lat2);

        $a = sin($dLat / 2) * sin($dLat / 2) + sin($dLon / 2) * sin($dLon / 2) * cos($lat1) * cos($lat2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        $d = $R * $c;

        return $d;
    }

    // Converts numeric degrees to radians
    public function toRad($Value)
    {
        return $Value * pi() / 180;
    }

    public function debug($message, $yoffs = -5, $w = 100)
    {
        $x = $this->GetX();
        $y = $this->GetY();

        //$message = "(".$x.",".$y.") ".$message;
        // Font
        $this->SetTextColor(255, 0, 0);
        $this->SetFont('Futura', 'B', 12);
        // Output text in a 9 cm width column
        //$this->SetX( 40 );
        $this->SetY($y + $yoffs);
        $this->Cell($w, 5, $message, 1, 1, 'L', true);

        $this->SetTextColor(0, 0, 0);
        $this->SetX($x);
        $this->SetY($y);
    }
} // Class Annuaire

/******************************************************************/

class AnnuaireFiches extends Annuaire
{
    public $bottomMargin = 20;
    public $colMargin = 40;
    public $cellHeight = 12;
    public $marginLeft = 1; // 1 if there is a margin at left (and right) 0 if no margin.

    public function PrintAnnuaire($x)
    {
        global $no_footer;
        global $no_header;

        $this->doc = $x;

        $no_footer = true;
        $no_header = true;

        $this->PrintAllCategories($x);
    }

    public function PrintAllCategories($x)
    {
        $categories = $x->getElementsByTagName('categorie');
        $nb_cat = $categories->length;
        for ($cat = 0; $cat < $nb_cat; ++$cat) {
            $categorie = $categories[$cat];

            $myCat = new CategorieFiches($this, $categorie);
            $myCat->display();
        }
    }

    // the column width is the width of the page without the margin size
    // we have 2 margins: left,right.
    public function GetColumnWidth()
    {
        return $this->GetPageWidth() - $this->colMargin * 2;
    }
}

class AnnuaireLivret extends Annuaire
{
    public $bottomMargin = 20;
    public $colMargin = 10;
    public $cellHeight = 5;
    public $marginLeft = 10;

    public function PrintCD54Mention()
    {
        // TODO make it right and call it on the last page
        $this->SetY(5);
        $this->SetFont('Futura', '', 10);
        $w = $this->GetStringWidth('Impression CD 54 - Octobre 2018') + 6;
        $this->SetLeftMargin($this->GetPageWidth() - $w - 30);
        $this->Cell($w, 10, 'Impression CD 54 - Octobre 2018', 0, 1);
        $this->Image('images/fete/logoCR54.png', $this->GetPageWidth() - 40, $this->GetPageHeight() - 95, 20);

        $this->Image('images/Logo_Imprim_Vert-3.png', $this->GetPageWidth() - 30, 6, 30);
    }

    public function PrintCouverture()
    {
        global $title;
        global $localisation;
        global $slogan;

        $this->SetFillColor(204, 220, 62);
        $this->SetTextColor(112, 112, 111);
        $this->Rect(0, 0, $this->GetPageWidth(), $this->GetPageHeight(), 'F');

        $this->Image('images/FlorainFA5-vert.jpg',
            ($this->GetPageWidth() - 60) / 2, 20, 60
        );

        $this->SetFont('Steelfish', '', 98);

        $this->SetY(120);
        $w = $this->GetStringWidth('Le Florain') + 6;
        $this->SetLeftMargin(($this->GetPageWidth() - $w) / 2);
        $this->Cell($w, 30, 'Le Florain', 0, 1);
        $this->Cell($w, 10, '', 0, 1);

        $this->SetFont('FreeScript', '', 45);
        $w = $this->GetStringWidth($slogan) + 6;
        $this->SetX(($this->GetPageWidth() - $w) / 2);
        $this->Cell($w, 5, $slogan);

        $this->SetFont('Futura', '', 78);
        $this->SetY($this->GetPageHeight() / 1.5);
        $w = $this->GetStringWidth("L'Annuaire") + 6;
        $this->SetX(($this->GetPageWidth() - $w) / 2);
        $this->Cell(0, 9, "L'Annuaire", 0, 1);
        $this->Cell(0, 8, '', 0, 1);

        $this->SetFont('Futura', '', 15);
        $w = $this->GetStringWidth($title) + 6;
        $this->SetX(($this->GetPageWidth() - $w) / 2);
        $this->Cell(0, 9, $title);

        $this->SetFont('Futura', '', 34);
        $this->SetY($this->GetPageHeight() - 40);
        $w = $this->GetStringWidth('Monnaie Locale') + 6;
        $this->SetX(($this->GetPageWidth() - $w) / 2);

        $this->Cell($w, 20, 'Monnaie Locale', 0, 1);
        $this->SetFont('Futura', '', 19);
        $w = $this->GetStringWidth($localisation) + 6;
        $this->SetX(($this->GetPageWidth() - $w) / 2);
        $this->Cell($w, 5, $localisation);
    }

    public function PrintCharte()
    {
        global $title;

        // green bg
        $this->SetFillColor(204, 220, 62);
        $this->SetTextColor(112, 112, 111);
        $this->Rect(0, 0, $this->GetPageWidth() / 2, $this->GetPageHeight(), 'F');

        // charte
        $this->SetY($this->GetPageHeight() * .1);
        $this->SetLeftMargin(110);
        $xmlDoc = new DOMDocument();
        $xmlDoc->load('charte.xml');
        $x = $xmlDoc->documentElement;
        $titre = $x->getAttribute('titre');
        $this->PrintName(utf8_decode($titre), 100, 16, 'C');
        $this->Ln(20);
        $intro = $x->getAttribute('intro');
        $this->PrintText(utf8_decode($intro), 90, 12, 'J');

        $this->SetLeftMargin(120);
        $valeurs = $x->getElementsByTagName('valeur');
        $nb_v = $valeurs->length;
        for ($v = 0; $v < $nb_v; ++$v) {
            $this->PrintValue(utf8_decode($valeurs[$v]->nodeValue), 80);
        }

        // logo
        $this->Image('images/FlorainFA5-vert.jpg',
            ($this->GetPageWidth() / 2 - 20) / 2, $this->GetPageheight() * .2, 20
        );

        // site web
        $this->SetFont('Futura', 'B', 28);
        $this->SetTextColor(112, 112, 111);
        $this->SetY($this->GetPageheight() * .4);
        $w = $this->GetStringWidth('www.florain.fr') + 6;
        $this->SetX(($this->GetPageWidth() / 2 - $w) / 2);
        $this->Cell($w, 20, 'www.florain.fr');
        // mail
        $this->SetFont('Futura', 'B', 18);
        $this->SetY($this->GetPageheight() * .5);
        $w = $this->GetStringWidth('contact@florain.fr') + 6;
        $this->SetX(($this->GetPageWidth() / 2 - $w) / 2);
        $this->Cell($w, 20, 'contact@florain.fr');
    }

    public function PrintAnnuaire($x)
    {
        global $no_footer;
        global $no_header;

        $this->doc = $x;
        $this->AddPage();

        $this->PrintCouverture();

        // print header for the next page but no footer for the first page
        $no_footer = true;
        $no_header = false;
        $this->AddPage();

        $no_footer = false; // now we can display it

        $toc = array();
        $toc = $this->PrintAllCategories($x);

        $force_toc = false;
        // get the current number of pages + the 4eme de couv
        $nbpages = $this->PageNo() + 1;
        if ($force_toc) {
            $nbpages = $nbpages + 1;
        }

        // we want a multiple of 4 to print it as a booklet.
        $reste4 = $nbpages % 4;

        if ($reste4 > 0) {
            $nb = 4 - $reste4;
            if ($nb == 3) {
                // print pages for notes
                $this->AddPage();
                $this->PrintNotes();
            }

            $this->AddPage();
            $this->PrintComptoirs($x);

            $no_header = true;
            if ($force_toc || $nb > 1) {
                $this->AddPage();
                $no_footer = true;
                $this->PrintTOC($toc);
            }
        }

        $no_header = true;
        // no header nor footer in the last page
        $this->AddPage();
        $no_footer = true;
        $this->PrintCharte();
    }

    public function PrintAllCategories($x)
    {
        $toc = array();
        $cat = 0;
        $categories = $x->getElementsByTagName('categorie');
        $nb_cat = $categories->length;
        for ($cat = 0; $cat < $nb_cat; ++$cat) {
            $categorie = $categories[$cat];

            $myCat = new CategorieLivret($this, $categorie);
            $toc[$cat] = $myCat->display();
        }

        /*
        $marches = $x->getElementsByTagName('marches');
        $nb_mar = $marches->length;
        for ($mar = 0; $mar < $nb_mar; ++$mar) {
            $marche = $marches[$mar];

            $myMar = new CategorieMarcheLivret($this, $marche);
            $toc[$cat + $mar] = $myMar->display();
        }*/

        return $toc;
    }

    public function TocPrintCat($name, $page)
    {
        $this->Ln(4);
        $this->SetX(45);
        // Font
        $this->SetFont('Futura', 'B', 14);
        // Output text in a 6 cm width column
        $this->Cell(60, 5, $name, 0, 0);
        $this->Cell(60, 5, $page, 0, 1, 'R');
    }

    public function TocPrintSCat($name, $page)
    {
        if ($name == 'none') {
            return;
        }
        $this->SetX(55);
        // Font
        $this->SetFont('Futura', '', 12);
        // Output text in a 6 cm width column
        $this->Cell(60, 5, $name, 0, 0);
        $this->Cell(50, 5, $page, 0, 1, 'R');
    }

    public function PrintComptoirs($x)
    {
        $this->resetColumn();

        $this->SetFont('Futura', 'B', 18);
        $this->SetFillColor(204, 220, 62);
        $this->SetTextColor(112, 112, 111);
        $this->Cell($this->GetSubPageWidth() + $this->colMargin, 6, ' Les comptoirs de change', 'B', 1, 'L', true);
        $this->Ln(4);

        // Save ordinate
        $this->bas_col0 = $this->GetY();
        $this->bas_col1 = $this->GetY();

        $toc = array();
        $acteurs = $x->getElementsByTagName('acteur');
        $nb_acteurs = $acteurs->length;
        $c = 0;
        for ($a = 0; $a < $nb_acteurs; ++$a) {
            $acteur = $acteurs[$a];

            $myA = new ActeurLivret($this, $acteur);
            if ($myA->isComptoir()) {
                $myA->display_comptoir($c % 2, $c);
                $c = $c + 1;
            }
        }

        return $toc;
    }

    public function PrintComptoirsTOC($toc)
    {
        $this->SetFont('Futura', 'B', 18);
        $this->SetFillColor(204, 220, 62);
        $this->SetTextColor(112, 112, 111);
        $this->Cell(0, 6, ' Comptoirs de change', 'B', 1, 'L', true);
        $this->Ln(4);

        $count = count($toc);
        for ($cat = 0; $cat < $count; ++$cat) {
            $scount = count($toc[$cat]);

            for ($s = 0; $s < $scount - 2; ++$s) {
                $acount = count($toc[$cat][$s]);
                for ($a = 0; $a < $acount - 2; ++$a) {
                    if ($toc[$cat][$s][$a]['c'] == true) {
                        $this->TocPrintCat($toc[$cat][$s][$a]['a'], $toc[$cat][$s][$a]['p']);
                    }
                }
            }
        }
    }

    public function PrintNotes()
    {
        $this->resetColumn();

        $this->SetFont('Futura', 'B', 18);
        $this->SetFillColor(204, 220, 62);
        $this->SetTextColor(112, 112, 111);
        $this->Cell(0, 6, ' Mes notes', 'B', 1, 'L', true);
        $this->Ln(3);

        $this->SetLeftMargin(30);

        $largeur = 200;
        $this->Image('images/logo-monnaie-gray.png',
            ($this->GetPageWidth() - $largeur) / 2, ($this->GetPageHeight() - 200) / 2, $largeur
        );
        for ($l = 0; $l < 20; ++$l) {
            $this->Cell(160, 12, ' ', 'B', 1, 'L', false);
        }
    }

    public function PrintTOC($toc)
    {
        $this->resetColumn();

        $this->Ln(10);
        $this->SetFont('Futura', 'B', 18);
        $this->SetFillColor(204, 220, 62);
        $this->SetTextColor(112, 112, 111);
        $this->Cell(0, 6, ' Sommaire', 'B', 1, 'L', true);
        $this->Ln(3);

        $count = count($toc);
        for ($cat = 0; $cat < $count; ++$cat) {
            $scount = count($toc[$cat]);

            $this->TocPrintCat($toc[$cat]['type'], $toc[$cat]['page']);
            for ($s = 0; $s < $scount - 2; ++$s) {
                $this->TocPrintSCat($toc[$cat][$s]['type'], $toc[$cat][$s]['page']);
                /*
                    for( $a = 0; $a < $acount; $a++) {
                    $message = " index " . $toc[$c][$s][$a];
                    $this->PrintText( $message );
                }
                */
            }
        }

        // $this->Ln(10);
        // $this->PrintComptoirsTOC( $toc );
    }

    public function NextPage()
    {
        $this->addPage();
    }

    // the column width is the half of the page without the margin size
    // we have 3 margin: left,right and middle.
    public function GetColumnWidth()
    {
        return ($this->GetPageWidth() - $this->colMargin * 3) / 2;
    }

    public function GetSubPageWidth()
    {
        return $this->GetColumnWidth() * 2 + $this->colMargin;
    }
} // Class AnnuaireLivret

/*********************************************************************/

class AnnuairePoche extends Annuaire
{
    public $bottomMargin = 0;
    public $topSubPage = 0;
    public $colMargin = 2;
    public $cellHeight = 3;
    public $marginLeft = 0; // 1 if there is a margin at left (and right) 0 if no margin.

    public function GetSubPageWidth()
    {
        return $this->GetColumnWidth() * 2 + $this->colMargin;
    }

    public function GetColumnWidth()
    {
        // Divides pages in 8 columns if nbSubPages = 4
        // take into account margins between columns only: no left/right margins
        return ($this->GetPageWidth() - $this->colMargin * ($this->nbSubPages * 2 - 1)) / ($this->nbSubPages * 2);
    }

    public function AddSubPage()
    {
        // If page is split in 4 subPages, and current subPage == 3, then add page. Otherwise add subPage.
        if ($this->nbSubPages <= 1) {
            return;
        }

        if ($this->subPage == ($this->nbSubPages - 1)) {
            $this->subPage = 0;
            $this->addPage('L');
        } elseif ($this->subPage < ($this->nbSubPages - 1)) {
            $this->subPage = $this->subPage + 1;
        }
        $this->setXY(
            $this->colMargin + $this->GetSubPageWidth() * $this->subPage,
            $this->topSubPage
        );
    }

    public function PrintCD54Mention($margin)
    {
        $this->SetFont('Futura', '', 5);
        $w = $this->GetStringWidth('Impression CD 54 - Octobre 2018') + 6;
        $this->SetLeftMargin($margin);
        $this->Ln();
        $this->Ln();
        $cur_y = $this->GetY();
        $this->Cell($w, $this->cellHeight, 'Impression CD 54 - Octobre 2018', 0, 1);
        $this->Image('images/fete/logoCR54.png', $margin + $w + 20, $cur_y - 4, 7);
        $this->Image('images/Logo_Imprim_Vert-3.png', $margin + $w, $cur_y - 1, 15);
    }

    public function PrintCouverture()
    {
        global $sstitle;
        global $localisation;
        global $slogan;

        $margin = $this->GetX();
        $cellwidth = $this->GetSubPageWidth() + $this->colMargin;

        $this->SetFillColor(204, 220, 62);
        $this->SetTextColor(112, 112, 111);
        $this->Rect($margin - $this->colMargin, 0, $this->GetSubPageWidth() + $this->colMargin * 3, $this->GetPageHeight(), 'F');

        $this->Image('images/FlorainFA5-vert.jpg', $margin + ($cellwidth - 40) / 2, 20, 40);

        $this->SetFont('Steelfish', '', 48);

        $this->SetY(80);
        $this->SetX($margin);
        $w = $this->GetStringWidth('Le Florain') + 6;
        $this->SetLeftMargin($margin + ($cellwidth - $w) / 2);
        $this->Cell($w, 15, 'Le Florain', 0, 1);

        $this->SetFont('Futura', '', 17);
        $w = $this->GetStringWidth('Monnaie Locale') + 6;
        $this->SetLeftMargin($margin + ($cellwidth - $w) / 2);
        $this->Cell($w, 7, 'Monnaie Locale', 0, 1);

        $this->SetFont('Futura', '', 9);
        $w = $this->GetStringWidth($localisation) + 6;
        $this->SetLeftMargin($margin + ($cellwidth - $w) / 2);
        $this->Cell($w, 5, $localisation);

        $this->SetY($this->GetPageHeight() / 1.8);
        $this->SetFont('FreeScript', '', 25);
        $w = $this->GetStringWidth($slogan) + 6;
        $this->SetX($margin + ($cellwidth - $w) / 2);
        $this->Cell($w, 5, $slogan);

        $this->SetFont('Futura', '', 40);
        $this->SetY($this->GetPageHeight() / 1.5);
        $w = $this->GetStringWidth("L'Annuaire") + 6;
        $this->SetX($margin + ($cellwidth - $w) / 2);
        $this->Cell($w, 9, "L'Annuaire");
        $this->SetFont('Futura', '', 20);
        $this->SetXY($margin + 5, $this->getY() + 10);
        $this->Cell($w, 5, 'de poche');

        $this->SetY($this->GetPageHeight() - 40);
        // site web
        $this->SetFont('Futura', 'B', 18);
        $w = $this->GetStringWidth('www.florain.fr') + 6;
        $this->SetX($margin);
        $this->PrintName('www.florain.fr', $cellwidth, 10, 'C');
        // mail
        $this->SetFont('Futura', 'B', 14);
        $w = $this->GetStringWidth('contact@florain.fr') + 6;

        $this->SetX($margin);
        $this->PrintName('contact@florain.fr', $cellwidth, 10, 'C');

        $this->SetFont('Futura', 'B', 10);
        $this->SetXY($margin + 5, $this->GetPageHeight() - 20);
        $this->MultiCell($cellwidth - 10, $this->cellHeight, $sstitle, 0, 'C');
    }

    public function PrintCharte()
    {
        global $title;

        $curY = $this->GetY();
        $maxY = $this->GetPageHeight() * .35;
        if ($curY >= $maxY) {
            return;
        }

        $margin = $this->GetX();
        // green bg
        $this->SetFillColor(204, 220, 62);
        $this->SetTextColor(112, 112, 111);
        $this->Rect($margin + $this->GetColumnWidth() * 1.65, 0, $this->GetColumnWidth() * .39, $this->GetPageHeight(), 'F');

        // charte
        $this->SetY(($curY + $maxY) / 2);
        $this->SetLeftMargin($margin);
        $xmlDoc = new DOMDocument();
        $xmlDoc->load('charte.xml');
        $x = $xmlDoc->documentElement;
        $titre = $x->getAttribute('titre');
        $this->PrintName(utf8_decode($titre), $this->GetSubPageWidth(), 16, 'C', 'steelfish', '');
        $this->Ln(5);
        $intro = $x->getAttribute('intro');
        $this->PrintText(utf8_decode($intro), $this->GetSubPageWidth(), 10, 'J');

        $this->SetLeftMargin($margin + 8);
        $valeurs = $x->getElementsByTagName('valeur');
        $nb_v = $valeurs->length;
        for ($v = 0; $v < $nb_v; ++$v) {
            $this->PrintValue(utf8_decode($valeurs[$v]->nodeValue), $this->GetSubPageWidth() * .85, 10);
        }
    }

    public function PrintAnnuaire($x)
    {
        $this->doc = $x;
        $this->AddPage('L');

        $this->PrintAllCategories($x);

        if ($this->subPage == 0) {
            $this->AddSubPage();
        }
        // print the charte if enough space
        if ($this->subPage == 1) {
            $this->AddSubPage();
            $this->SetCol(0);
            $this->PrintCharte();
        }
        if ($this->subPage == 2) {
            $cstart = $this->GetPageHeight() * .35;
            if ($this->GetY() < $cstart) {
                $this->SetCol(0);
                $this->PrintCharte();
            }
        }

        // legend
        $this->setCol(0);
        $this->SetY($this->GetPageheight() - 30);

        // map colors
        $this->printLegend();

        // print the comptoir advice
        $this->SetY($this->GetPageheight() - 27);
        $textMargin = 36;
        $this->SetX($this->GetX() + $textMargin);
        // for one line text $texte_width = $this->marginLeft + $this->colMargin*6 + $this->GetColumnWidth()*6;
        $texte_width = $this->GetSubPageWidth() - $textMargin - 2;
        $texte = utf8_decode($x->getAttribute('comptoirs'));
        $this->SetFont('futura', 'B', 10);
        $h = $this->MultiCellHeight($texte_width, $this->cellHeight, $texte, 0, 'C');
        $this->SetFillColor(234, 250, 180);
        $this->Rect($this->GetX() - 1, $this->GetY() - 3, $texte_width, $h + 4, 'F');

        $this->PrintName($texte, $texte_width);

        // Not used for now. $this->PrintCD54Mention( $this->GetX()+10 );
        // if used: move the block above from 10 higher to make some place

        // at last print the couverture
        $this->AddSubPage();
        $this->SetCol(0);
        $this->PrintCouverture();
    }

    public function PrintAllCategories($x)
    {
        $categories = $x->getElementsByTagName('categorie');
        $nb_cat = $categories->length;
        for ($cat = 0; $cat < $nb_cat; ++$cat) {
            $categorie = $categories[$cat];

            $myCat = $this->NewCategorie($categorie);
            $myCat->display();
        }

        $marches = $x->getElementsByTagName('marches');
        $nb_mar = $marches->length;
        for ($mar = 0; $mar < $nb_mar; ++$mar) {
            $marche = $marches[$mar];

            $myMar = $this->NewCategorieMarchePoche($marche);
            $myMar->display();
        }
    }

    public function NextPage()
    {
        $this->AddSubPage();
    }

    public function NewCategorie($categorie)
    {
        return new CategoriePoche($this, $categorie);
    }

    public function NewCategorieMarchePoche($marche)
    {
        return new CategorieMarchePoche($this, $marche);
    }
}

class AnnuaireCompact extends AnnuairePoche
{
    public function NewCategorie($categorie)
    {
        return new CategorieCompact($this, $categorie);
    }

    public function NewCategorieMarchePoche($marche)
    {
        return new CategorieMarcheCompact($this, $marche);
    }

    public function GetSubPageWidth()
    {
        return $this->GetColumnWidth();
    }

    public function GetColumnWidth()
    {
        // Divides pages in 8 columns if nbSubPages = 4
        // take into account margins between columns only: no left/right margins
        return ($this->GetPageWidth() - $this->colMargin * ($this->nbSubPages - 1)) / ($this->nbSubPages);
    }

    public function PrintAllCategories($x)
    {
        $categories = $x->getElementsByTagName('categorie');
        $nb_cat = $categories->length;
        for ($cat = 0; $cat < $nb_cat; ++$cat) {
            $categorie = $categories[$cat];

            $myCat = $this->NewCategorie($categorie);
            $myCat->display();
        }

        $marches = $x->getElementsByTagName('marches');
        $nb_mar = $marches->length;
        for ($mar = 0; $mar < $nb_mar; ++$mar) {
            $marche = $marches[$mar];

            $myMar = $this->NewCategorieMarchePoche($marche);
            $myMar->display();
        }
    }
}

/******************************************************************/

$type = 'Livret';
$output = 'D';

if (isset($_GET['type'])) {
    $type = $_GET['type'];
}
if (isset($_GET['output'])) {
    $output = $_GET['output'];
}

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

$no_footer = true;
$no_header = true;
$a = null;
if ($type == 'Poche') {
    $a = new AnnuairePoche();
    $filename = 'Annuaire du Florain - format poche.pdf';
} elseif ($type == 'Livret') {
    $a = new AnnuaireLivret();
    $filename = 'Annuaire du Florain - format livret.pdf';
} elseif ($type == 'Compact') {
    $a = new AnnuaireCompact();
    $filename = 'Annuaire du Florain - format compact.pdf';
} else {
    $a = new AnnuaireFiches();
    $filename = 'Annuaire du Florain - format fiches.pdf';
}

$a->AddFont('Steelfish', '', 'steelfishrg.php');
$a->AddFont('Futura', '', 'Futura (Light).php');
$a->AddFont('Futura', 'B', 'Futura Heavy.php');
$a->AddFont('Futura', 'BI', 'Futura Heavy.php');
$a->AddFont('Futura', 'I', 'Futura Heavy.php');
$a->AddFont('FreeScript', '', 'FREESCPT.php');
$a->SetTopMargin(1);
$a->SetAutoPageBreak(false, $a->bottomMargin);

$xmlDoc = new DOMDocument();
$xmlDoc->load('acteurs-cat.xml');

$x = $xmlDoc->documentElement;
$title = utf8_decode($x->getAttribute('titre'));
$sstitle = utf8_decode($x->getAttribute('sstitre'));
$localisation = utf8_decode($x->getAttribute('localisation'));
$slogan = utf8_decode($x->getAttribute('slogan'));
$a->SetTitle($title);
$a->SetAuthor('Le Florain');

$a->PrintAnnuaire($x);

$a->SetDisplayMode('fullpage', 'two');
$a->Output($output, $filename);
