<?php

require 'fpdf/fpdf.php';
require 'fpdf/categorie.php';
require 'fpdf/marche.php';
require 'fpdf/card.php';
require './annuaire_impl.php';
include 'pix_tools.php';

$light = 1;
$cMin = .4 * $light;
$cMax = .9 * $light;
$cMid = .6 * $light;
$townArea = .63;
$areaColor = array(
    [$cMax, $cMin, $cMin], // Red
    [$cMin, $cMax, $cMin], // Green
    [$cMin, $cMin, $cMax], // Blue
    [$cMax, $cMin, $cMax], // Magenta
    [$cMax, $cMax, $cMin], // Yellow
    [$cMin, $cMax, $cMax], // Cyan
    [$cMax, $cMid, $cMin], // Orange
    [$cMid, $cMin, $cMax], // Violet
    [$cMin, $cMid, $cMid], // Dark green
    [$cMid, $cMin, $cMid], // Purple
    [$cMid, $cMid, $cMin], // Kaki

    [$cMax, $cMid, $cMax], // Rose
    [$cMid, $cMax, $cMax], // Light cyan
    [$cMid, $cMax, $cMid], // Light Green
);
$indexCol = 0;
$maxIndexCol = 10;

$towns = array(
    new Ville('Nancy', 6.180794, 48.692442, 30, 'Compact'),
    new Ville('Toul', 5.891387, 48.675334, 30, 'Compact'),
    new Ville('Pont-à-Mousson', 6.053787, 48.902677, 25),
    new Ville('Lunéville', 6.495079, 48.591822, 30, 'Poche', 'L'),
    new Ville('Tezey-St-Martin', 6.294456, 48.900973, 30, 'Poche', 'BL'),
    new Ville('Colombey-les-Belles', 5.897124, 48.528123),
    new Ville('Vézelise', 6.092136, 48.481914, 30, 'Poche', 'B'),
    new Ville('Badonviller', 6.893152, 48.498444, 50, 'Poche', 'B'),
    //new Ville('Commercy', 5.591207, 48.762711),
    //new Ville('Baccarat', 6.740270, 48.450055),
    //new Ville('Bayon', 6.313295, 48.476239),
    //new Ville('Chateau-Salin', 6.508031, 48.822719, 30, 'Poche', 'B'),
);
$blank = new Ville('blank', 6, 48);
$blank->setColor([1, 1, 1]);

$toutes = new Ville('', 6.180794, 48.692442, 3000, 'Compact');

function findTown($name)
{
    global $towns;
    foreach ($towns as $town) {
        if ($town->nom == $name) {
            return $town;
        }
    }

    return findTown('Nancy');
}

function debug_to_console($data)
{
    $output = $data;
    if (is_array($output)) {
        $output = implode(',', $output);
    }

    echo "<script>console.log('Debug Objects: ".$output."' );</script>";
}

 /**
  * Send a GET request using cURL.
  *
  * @param string $url     to request
  * @param array  $get     values to send
  * @param array  $options for cURL
  *
  * @return string
  */
 function curl_get($url, array $get = null, array $options = array())
 {
     $agent = 'Mozilla/5.0 (Windows NT 6.2; WOW64; rv:17.0) Gecko/20100101 Firefox/17.0';
     $dir = dirname(__FILE__);
     $cookie_file = $dir.'/cookies/'.md5($_SERVER['REMOTE_ADDR']).'.txt';

     $defaults = array(
            CURLOPT_URL => $url,
            CURLOPT_HEADER => 0,
            CURLOPT_TIMEOUT => 4,
            CURLOPT_USERAGENT => $agent,
            CURLOPT_REFERER => 'http://www.monnaielocalenancy.fr',
            CURLOPT_COOKIEFILE => $cookie_file,
            CURLOPT_COOKIEJAR => $cookie_file,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_RETURNTRANSFER => true,
        );

     $ch = curl_init();
     curl_setopt_array($ch, ($options + $defaults));

     if (!$result = curl_exec($ch)) {
         $result = '<xml><town>chez moi!</town></xml>';
     }

     curl_close($ch);

     return $result;
 }

 function findTownName($x)
 {
     $tags = array('village', 'town', 'city');
     foreach ($tags as $t) {
         $r = $x->getRecords($t);
         if (count($r) === 1) {
             $v = $r[0]->nodeValue;
             if ($v !== null) {
                 return $v;
             }
         }
     }

     return 'chez moi!';
 }

 function getEditionName($lon, $lat)
 {
     $url = 'https://nominatim.openstreetmap.org/reverse?format=xml&lon='.$lon.'&lat='.$lat.'&zoom=18&addressdetails=1';

     $xml = curl_get($url);

     $xmlDoc = new DOMDocument();
     $xmlDoc->loadXML($xml);
     $x = $xmlDoc->documentElement;

     return findTownName($x);
 }

class Ville
{
    public $nom;
    public $type;
    public $lat;
    public $lon;
    public $km;
    private $col;
    public $nb = 0;
    public $pos;
    public $x;
    public $y;

    public function __construct($nom, $lat, $lon, $km = 30, $type = 'Poche', $pos = 'A')
    {
        $this->nom = $nom;
        $this->type = $type;
        $this->lat = $lat;
        $this->lon = $lon;
        $this->km = $km;
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

    public function getColor()
    {
        global $indexCol;
        global $maxIndexCol;
        global $areaColor;

        if (!$this->col) {
            $this->col = $areaColor[$indexCol];
            ++$indexCol;
            if ($indexCol > $maxIndexCol) {
                $indexCol = 0;
            }
        }

        return $this->col;
    }

    public function setColor($col)
    {
        $this->col = $col;
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

    public function color( $i ) {
        global $areaColor;
        return [$areaColor[$i][0]*256,$areaColor[$i][1]*256,$areaColor[$i][2]*256] ;
    }

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
        $list = array();
        if ($tag == 'scat') {
            $xml = $parent;
            $list = $xml->getSousCategories($tag);
        } else{
            $list = $xml->getActeurs($tag);
        }

        return count($this->entries_to_display($list));
    }

    /* when printing all local edition, collect actors that has not been included
     * and print an additionnal page with them.
     */
    public function PrintOrphans($x)
    {
        $orphans = array();
        $acteurs = $x->getActeurs();
        $nb_acteurs = count($acteurs);
        for ($a = 0; $a < $nb_acteurs; ++$a) {
            $acteur = $acteurs[$a];
            if (!$acteur->hasAttribute('editions')) {
                $name = utf8_decode($acteur->getAttribute('titre'));
                $orphans[] = $name;
            }
        }

        if (count($orphans) === 0) {
            return;
        }
        $this->AddPage();

        $this->resetColumn();

        $this->SetFont('Futura', 'B', 18);
        $this->SetFillColor(255, 255, 255);

        $this->SetTextColor(112, 112, 111);
        $this->Cell(0, 6, 'Voici les acteurs non couverts par un annuaire local:', 'B', 1, 'L', true);
        $this->Ln(3);

        $this->SetFont('Futura', 'B', 12);
        foreach ($orphans as  $name) {
            $this->Cell(0, 6, $name, '', 1);
        }
    }

    /* factorize the set display status, and store the displayed edition as well
    */
    public function setDisplayed($acteur)
    {
        $acteur->setAttribute('displayed', 'true');

        $editions = $acteur->getAttribute('editions').' '.$this->edition;

        $acteur->setAttribute('editions', $editions);
    }

    /* reset working attribute tobe ready for the next edition
    */
    public function reset_attributes($x)
    {
        $acteurs = $x->getActeurs();
        $nb_acteurs = count($acteurs);
        for ($a = 0; $a < $nb_acteurs; ++$a) {
            $acteur = $acteurs[$a];
            $acteur->removeAttribute('tooFar');
            $acteur->removeAttribute('displayed');
        }

        $marches = $x->getMarches();
        $nb_marches = count($marches);
        for ($m = 0; $m < $nb_marches; ++$m) {
            $marche = $marches[$m];
            $marche->removeAttribute('tooFar');
            $marche->removeAttribute('displayed');
        }

        $this->subPage = 0;

        global $blank;
        global $towns;

        foreach ($towns as $town) {
            $town->nb = 0;
        }

        $blank->nb = 0;
    }

    public function entries_to_display($list)
    {
        $nb = count($list);
        if ($this->km < 0) {
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

            // edition is set to 'toutes' for actors too far for any edition
            // so they appears on every one!
            $edition = $acteur->getAttribute('edition');
            if ($edition !== 'toutes') {
                $lon = $acteur->getAttribute('longitude');
                $lat = $acteur->getAttribute('latitude');
                $dist = $this->calcCrow($lat, $lon, $this->latRef, $this->lonRef);
                if ($dist > $this->km) {
                    $acteur->setAttribute('tooFar', 'really');
                    continue;
                }
            }
            $acteurs[$count] = $acteur;
            ++$count;
        }

        return $acteurs;
    }

    /* Returns the town label to display in the legend
    * - empty if there is not enough place
    * - with the number of actors if any.
    */
    public function getTextToDisplay($town, $ox)
    {
        $t = utf8_decode($town->nom);
        if ($town->nb > 0) {
            $t = $t.' '.$town->nb;
        }

        if ($this->TextWidth($t, 6) + $town->x > $ox + $this->GetSubPageWidth() / 2) {
            return '';
        }

        return $t;
    }

    /* print the legend
     * Legend is in two parts : the localisation with colors and the comptoir sentence
     */
    public function printLegend($x)
    {
        $this->setCol(0);
        $this->SetY($this->GetPageheight() - 30);

        // map colors
        $this->printMapColors();

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
    }

    /* print the geolocalistion map
     * centered according the lon and lat parameters if provided.
     * by default it is centered at Nancy center.
     */
    public function printMapColors()
    {
        $this->SetLineWidth(0);
        $lc = .5;
        $ox = $this->GetX();
        $oy = $this->GetY();
        $tot = 0;

        $middleLat = 6.180794;
        $middleLon = 48.692442;

        if ($this->km > 0) {
            $middleLat = $this->latRef;
            $middleLon = $this->lonRef;
        }

        global $towns;
        foreach ($towns as $town) {
            $town->x = null;
        }

        $y = $oy;
        for ($lon = $middleLon + .3; $lon > $middleLon - .3; $lon -= .01) {
            $x = $this->GetX();
            for ($lat = $middleLat - .41; $lat < $middleLat + .41; $lat += .01) {
                $r = $this->doGetColor($lat, $lon);
                $town = $r['town'];
                $c = $town->getColor();
                $plot = true;
                if ($r['black']) {
                    $savedX = $this->GetX();
                    $savedY = $this->GetY();
                    $xt = max($x - 5, 0);
                    if (strpos($town->pos, 'B') === false) {
                        $yt = $y - 3;
                    } else {
                        $yt = $y + .5;
                    }
                    if (strpos($town->pos, 'L') === false) {
                        $xt = $xt - 0;
                    } else {
                        $xt = $xt - 3;
                    }
                    $town->SetXY($xt, $yt);

                    $tot = $tot + $town->nb;

                    $c = [0, 0, 0];
                    $this->SetXY($savedX, $savedY);
                    $t = $this->getTextToDisplay($town, $ox);
                    $plot = $t != '';
                }

                if ($plot) {
                    $this->SetFillColor($c[0] * 255, $c[1] * 255, $c[2] * 255);
                    $this->SetDrawColor($c[0] * 255, $c[1] * 255, $c[2] * 255);
                    $this->Rect($x, $y,
                    $lc,
                    $lc, 'F'
                );
                }
                $x = $x + $lc - .1;
            }
            $y = $y + $lc - .1;
        }

        foreach ($towns as $town) {
            if (!$town->x) {
                continue;
            }
            $t = $this->getTextToDisplay($town, $ox);
            if ($t == '') {
                continue;
            }
            $this->SetXY($town->x, $town->y);
            $this->PrintText($t, 25, 6, 'L');
        }

        $this->SetXY($ox, $y - 2);
        global $blank;
        $this->ln();
        $tot = $tot + $blank->nb;
        $text = 'Nombre total d\'acteurs: '.$tot;
        if ($blank->nb > 0) {
            $text = $text.', dont '.$blank->nb.utf8_decode(' non géolocalisés');
        }
        $this->PrintText($text, 90, 6, 'L');
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
        global $blank;

        $ret = array();
        $ret['black'] = false;
        $ret['town'] = null;
        $minDist = 9999;
        $minKey = 0;
        foreach ($towns as $key => $town) {
            $dist = $this->calcCrow($lat, $lon, $town->lat, $town->lon);
            if ($dist < $townArea) {
                $ret['town'] = $town;
                $ret['black'] = true;

                return $ret;
            }

            if ($dist < $minDist) {
                $minDist = $dist;
                $minKey = $key;
            }
        }

        if ($this->km > 0) {
            $distRef = $this->calcCrow($lat, $lon, $this->latRef, $this->lonRef);
            if ($distRef > $this->km) {
                $ret['town'] = $blank;

                return $ret;
            }
        }

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

    public function PrintAnnuaire($x, $town)
    {
        global $no_footer;
        global $no_header;

        $this->doc = $x;

        $no_footer = true;
        $no_header = true;

        $this->edition = $town->nom;
        $this->km = $town->km;
        $this->latRef = $town->lat;
        $this->lonRef = $town->lon;

        $this->PrintAllCategories($x);
        $this->reset_attributes($x);
    }

    public function PrintAllCategories($x)
    {
        $categories = $x->getCategories();
        $nb_cat = count($categories);
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


class PolygonCards extends Annuaire {
    
    public $bottomMargin = 20;
    public $colMargin = 10;
    public $cellHeight = 5;
    public $marginLeft = 10;
    
    
    public function PrintAnnuaire($x, $town)
    {
        $this->doc = $x;
        $this->PrintCards($x);

        $this->PrintEvenements($x, 'carte');

        $this->addPage();
        $b = new ValuesBoard( $this, $x->x );
        $b->display();

        $this->addPage('L');
        $b = new ValuesComparisonBoard( $this, $x->x );
        $b->display();

        $this->addPage();
        $b = new Plateau( $this, $x->x );
        $b->display();
        
        $this->addPage('L');
        $b = new Regles( $this, $x->x );
        $b->display();

        $this->reset_attributes($x);
    }

    
    public function PrintEvenements($x, $tag) {
        $evts = $x->x->getElementsByTagName($tag);
        $nb_evts = $evts->length;
        
        $template = new EvenementCardTemplate( $this );

        for ($e = 0; $e < $nb_evts; ++$e) {
            $evt = $evts[$e];
            $idE = fmod( $e, $template->nbPerPage);
            $myE = new EvenementCardRecto($this, $evt, $template);
            $myE->display( $idE );
            if( $idE == $template->nbPerPage-1 ) {
                // display the back
                for( $i = 0; $i < $template->nbPerPage; $i++ ) {
                    
                    $myE = new EvenementCardVerso(
                        $this, $evts[$e-$template->nbPerPage+$i+1], $template
                    );
                    $myE->display( $i );
                }
            }
        }
    }

    public function PrintCards($x)
    {
        global $slogan;
        $nbPage = 1;
        $a = 0;
        $skip = 0;
        $acteurs = $x->getActeurs();
        $nb_acteurs = count($acteurs);
        $indexes = range(0, $nb_acteurs - 1);
        shuffle($indexes);
        $template = new PolygonCardTemplate0( $this );
        $nb_max = $nbPage * $template->nbPerPage;
        for ($a = 0; $a < $nb_acteurs; ++$a) {
            if( $a - $skip == $nb_max ) {
               break;
            }
            $acteur = $acteurs[$indexes[$a]];

            $image = $acteur->getAttribute('image');
            if( $image === "defaut.jpg" ) {
                $skip++;
                continue;
            }

            $idA = fmod( $a-$skip, $template->nbPerPage);
            $myA = new PolygonCard($this, $acteur, $template);
            $myA->display( $idA );
            if( $idA == $template->nbPerPage-1 ) {
                // display the back
                for( $i = 0; $i < $template->nbPerPage; $i++ ) {
                    
                    $myE = new EmptyPolygonCard(
                        $this, $slogan, $template
                    );
                    $myE->display( $i );
                }
            }

        }


        $template = new PolygonCardTemplate90( $this );
        $nb_max2 = $nbPage * $template->nbPerPage;
        $skip2 = 0;
        for (; $a < $nb_acteurs; ++$a) {
            if( $a - $skip - $nb_max - $skip2 == $nb_max2 ) {
               break;
            }
            $acteur = $acteurs[$indexes[$a]];

            $image = $acteur->getAttribute('image');
            if( $image === "defaut.jpg" ) {
                $skip2++;
                continue;
            }

            $idA = fmod( $a-$skip- $nb_max-$skip2, $template->nbPerPage);
            $myA = new PolygonCard($this, $acteur, $template);
            $myA->display( $idA );
            if( $idA == $template->nbPerPage-1 ) {
                // display the back
                for( $i = 0; $i < $template->nbPerPage; $i++ ) {
                    
                    $myE = new EmptyPolygonCard(
                        $this, $slogan, $template
                    );
                    $myE->display( $i );
                }
            }

        }
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

    public function PrintCouverture($town)
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

    public function PrintAnnuaire($x, $town)
    {
        global $no_footer;
        global $no_header;

        $this->edition = $town->nom;
        $this->km = $town->km;
        $this->latRef = $town->lat;
        $this->lonRef = $town->lon;

        $this->doc = $x;
        $this->AddPage();

        $this->PrintCouverture($town);

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
        $reste4 = 1;

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
        $this->reset_attributes($x);
    }

    public function PrintAllCategories($x)
    {
        $toc = array();
        $cat = 0;
        $categories = $x->getCategories();
        $nb_cat = count($categories);
        for ($cat = 0; $cat < $nb_cat; ++$cat) {
            $categorie = $categories[$cat];

            $myCat = new CategorieLivret($this, $categorie);
            $toc[$cat] = $myCat->display();
        }

        $marches = $x->getMarches();
        $nb_mar = count($marches);
        for ($mar = 0; $mar < $nb_mar; ++$mar) {
            $marche = $marches[$mar];

            $myMar = new CategorieMarcheLivret($this, $marche);
            $toc[$cat + $mar] = $myMar->display();
        }

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
        $acteurs = $x->getActeurs();
        $nb_acteurs = count($acteurs);
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

    public function PrintCouverture($town)
    {
        global $sstitle;
        global $localisation;
        global $slogan;
        global $edition;

        $margin = $this->GetX();
        $cellwidth = $this->GetSubPageWidth() + $this->colMargin;

        $this->SetFillColor(204, 220, 62);
        $this->SetTextColor(112, 112, 111);
        $this->Rect($margin - $this->colMargin, 0, $this->GetSubPageWidth() + $this->colMargin * 3, $this->GetPageHeight(), 'F');
        $this->Image('images/FlorainFA5-vert.jpg', $margin + ($cellwidth - 40) / 2, 20, 40);

        if ($edition != 'globale') {
            // print edition

            $this->SetAlpha(0.7);
            $this->SetDrawColor(255, 204, 0);
            $this->SetFillColor(255, 204, 0);
            $this->SetLineWidth(0);
            $x1 = $margin - $this->colMargin;
            $x2 = $x1 + $this->GetSubPageWidth() + $this->colMargin;
            $y1 = $this->GetPageHeight() * .15;
            $y2 = $y1 - 10;
            $yh = 20;
            $this->Polygon(array($x1, $y1, $x1, $y1 - $yh, $x2, $y2 - $yh, $x2, $y2), 'FD');
            $this->SetAlpha(1);
            $this->SetDrawColor(0, 0, 0);

            $fsize = 42;
            $this->SetFont('Steelfish', '', 42);
            $texte = 'Edition de '.utf8_decode($this->edition);
            if ($edition === 'perso') {
                $texte = utf8_decode('Edition personnalisée');
            }
            $xmarge = ($this->GetSubPageWidth() - $this->GetStringWidth($texte) * .9) / 2;
            if ($xmarge < 6) {
                // case of PAM
                $fsize = 30;
                $this->SetFont('Steelfish', '', $fsize);
                $xmarge = ($this->GetSubPageWidth() - $this->GetStringWidth($texte) * .9) / 2;
            }

            $this->RotatedText($x1 + $xmarge, $y1 - $xmarge / 1.8 - (42 - $fsize) / 2, $texte, 8);

            $texte = utf8_decode('à moins de '.$this->km.' km de '.$this->edition);
            $y1 = $y1 + 5;
            $this->SetFont('Futura', '', 12);

            $xmarge = ($this->GetSubPageWidth() - $this->GetStringWidth($texte) * .9) / 2;

            $this->RotatedText($x1 + $xmarge, $y1 - $xmarge / 1.8 - (42 - $fsize) / 2, $texte, 8);
        }

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
        $this->ln();
        // mail
        $this->SetFont('Futura', 'B', 14);
        $w = $this->GetStringWidth('contact@florain.fr') + 6;

        $this->SetX($margin);
        $this->PrintName('contact@florain.fr', $cellwidth, 10, 'C');

        $this->SetFont('Futura', 'B', 10);
        $this->SetXY($margin + 5, $this->GetPageHeight() - 20);
        $this->MultiCell($cellwidth - 10, $this->cellHeight + 1, $sstitle, 0, 'C');
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
        $this->Rect($margin + $this->GetSubPageWidth() * 0.69, $this->getY(), $this->GetSubPageWidth() * .30, $this->GetPageHeight(), 'F');

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

    public function addParagraphe($titre, $texte)
    {
        $this->SetX($this->lMargin);
        $this->PrintName(utf8_decode($titre), 100, 16, 'L');
        $this->ln(2);

        $this->SetX($this->lMargin + 10);
        $this->PrintText(utf8_decode($texte), 100, 12, 'L');
        $this->ln(4);
    }

    /* Argumentaire sur deux colonnes
    */
    public function displayEngagezVous()
    {
        $margin = $this->GetX();
        $l = $this->GetColumnWidth() * .39;
        // green bg
        $this->SetFillColor(204, 220, 62);
        $this->Rect($margin, 0, $l, $this->GetPageHeight(), 'F');
        $this->SetAlpha(0.6);
        $this->SetDrawColor(255, 204, 0);
        $this->SetLineWidth(2);
        $this->Rect($margin + $l + 1, 1, $this->GetSubPageWidth() * 2 - $l, $this->GetPageHeight() - 2, 'D');
        $this->SetAlpha(1);
        $this->SetTextColor(255, 112, 111);

        $this->SetLeftMargin($margin + 35);

        $availableWidth = $this->GetSubPageWidth() * 2 - $l - 1;

        $this->ln(14);
        $this->PrintName(utf8_decode('Vous adhérez aux valeurs du Florain ?'), $availableWidth, 16, 'L', 'steelfish', '');
        $this->ln(4);
        $this->PrintName(utf8_decode('Vous voulez dynamiser votre Territoire ?'), $availableWidth, 18, 'L', 'steelfish', '');
        $this->ln(10);
        $this->PrintName(utf8_decode('Participez à son développement:'), $availableWidth, 24, 'L', 'steelfish', '');
        $this->ln(12);
        $this->PrintName(utf8_decode('rejoignez ou créez votre groupe local !'), $availableWidth, 30, 'C', 'steelfish', '');
        $this->ln(15);

        $this->SetLeftMargin($margin + 25);
        $this->ln();
        $SVGHeight = $this->cellHeight;
        $this->cellHeight = 6;
        $this->addParagraphe(
            'Démarcher les professionnels pour agrandir le réseau:',
            'Aller en binômes à la rencontre des acteurs du territoire.', $availableWidth
        );
        $this->addParagraphe(
            'Organiser les évènements:',
            "Tout le long de l'année, assurer la participation du Florain aux manifestations du territoire, pour en faire la promotion.", $availableWidth
        );
        $this->addParagraphe(
            'Travailler sur les outils de communication:',
            "Tracts, Réseaux Sociaux, Site internet, Lettre d'infos. Nul besoin d'être rédacteur ou graphiste, lancez vous!", $availableWidth
        );
        $this->addParagraphe(
            'Participer occasionnellement:',
            'Répondre selon ses disponibilités aux différents appels à bénévoles - distributions de tracts - tenues de stand.', $availableWidth
        );
        $this->cellHeight = $SVGHeight;

        $this->SetY($this->GetPageHeight() - 20);
        // site web
        $this->SetX($margin + 15);
        $this->PrintName('www.florain.fr', $availableWidth, 12, 'C');
        // mail
        $this->ln();
        $this->SetX($margin + 15);
        $this->PrintName('contact@florain.fr', $availableWidth, 12, 'C');

        $this->AddSubPage();
    }

    // Poche
    public function PrintAnnuaire($x, $town)
    {
        $this->doc = $x;

        $this->edition = $town->nom;
        $this->km = $town->km;
        $this->latRef = $town->lat;
        $this->lonRef = $town->lon;

        $this->AddPage('L');

        $this->PrintAllCategories($x);

        $charteDisplayed = false;
        $legendDisplayed = false;
        $blankNewPage = false;
        if ($this->PageNo() % 2 == 1) {
            // still place for legend ?
            if ($this->getY() < $this->GetPageheight() - 30) {
                $legendDisplayed = true;
                $this->printLegend($x);
            } elseif ($this->subPage < $this->nbSubPages - 1) {
                $this->AddSubPage();
                $legendDisplayed = true;
                $this->printLegend($x);
            }

            // force a new page
            $this->subPage = $this->nbSubPages - 1;
            $this->AddSubPage();
            $blankNewPage = true;
        }

        if ($this->subPage == 0) {
            if ($blankNewPage) {
                $this->SetCol(0);
                $this->PrintCharte();
                $this->AddSubPage();
                $this->displayEngagezVous();
                $charteDisplayed = true;
            } else {
                $this->AddSubPage();
            }
        }

        // print the charte if enough space
        if (!$charteDisplayed) {
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
        }

        if (!$legendDisplayed) {
            $this->printLegend($x);
        }
        // Not used for now. $this->PrintCD54Mention( $this->GetX()+10 );
        // if used: move the block above from 10 higher to make some place

        // at last print the couverture
        $this->AddSubPage();
        $this->SetCol(0);
        $this->PrintCouverture($town);
        $this->reset_attributes($x);
    }

    public function PrintAllCategories($x)
    {
        $categories = $x->getCategories();
        $nb_cat = count($categories);
        for ($cat = 0; $cat < $nb_cat; ++$cat) {
            $categorie = $categories[$cat];

            $myCat = $this->NewCategorie($categorie);
            $myCat->display();
        }

        $marches = $x->getMarches();
        $nb_mar = count($marches);
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
}

/******************************************************************/

/*
 * Il y a actuellement plusieurs resultats possibles selon le parametre type
 * - Fiche: une page A4 par acteur. c'est la valeur par defaut.
 * - Livret: cree un livret (A4 pliee en deux), avec une page de couverture, un quatrieme de couverture.
 *           en fonction de la place dispobible, le nombre de page etant un multiple de 4, d'autre page sont ajoutee:
 *           la charte de valeurs, la liste des comptoirs de change, une page de notes.
 * - Poche: cree un annuaire avec le maximum d'info pour tenir sur une page A4.
 * - Compact: c'est le Poche remanie et compacte pour tenir sur une page A4.
 *
 * la geolocalisation:
 * On peu limiter la liste des acteur en fonction d'une geolocalisation et d'un perimetre donne:
 * specifier les parametres lon, lat et km, pour ne garder que les acteurs situes a moins du nombre de kilometres
 * donnes des coordonnees lon/lat voulues.
 *
 * les editions speciales:
 * Elles simplifient la gestion des parametres de geolocalisation pour les editions des groupes locaux et determinent
 * le type de format adapte en fonction du nombre d'acteurs concernes.
 * edition=Nancy|Toul|Pont-à-Mousson|Lunéville|Badonviller
 *
 * le parametre output
 * - pour ne pas creer un fichier systematiquement sur le disque local, mettre ce parametre a vide.
 *   Le fichier pdf sera envoye au navigateur
 *
 */

$type = '';
$edition = 'globale';
$output = 'D';

$town = $toutes;
if (isset($_GET['type'])) {
    $type = $_GET['type'];
}
if (isset($_GET['edition'])) {
    // if edition parameter is set: ignore type and manual geolocalisation
    $edition = $_GET['edition'];
    if ($edition === 'perso') {
        $lat = 6.180794;
        $lon = 48.692442;
        $km = 30;
        if (isset($_GET['lat'])) {
            $lat = $_GET['lat'];
        }
        if (isset($_GET['lon'])) {
            $lon = $_GET['lon'];
        }
        if (isset($_GET['km'])) {
            $km = $_GET['km'];
        }
        $ville = getEditionName($lat, $lon);
        $town = new Ville($ville, $lat, $lon, $km, $type);
    } else {
        $town = findTown($edition);
    }
    if( $type === "") {
      $type = $town->type;
    }
}

if (isset($_GET['output'])) {
    $output = $_GET['output'];
}

$no_footer = true;
$no_header = true;
$a = null;
$filename = 'Annuaire du Florain';
if ($edition !== 'toutes') {
    $filename = $filename.' - Edition de '.utf8_decode($edition);
}
if ($type == 'Poche') {
    $a = new AnnuairePoche();
    $filename = $filename.' - format poche.pdf';
} elseif ($type == 'Livret') {
    $a = new AnnuaireLivret();
    $filename = $filename.' - format livret.pdf';
} elseif ($type == 'Compact') {
    $a = new AnnuaireCompact();
    $filename = $filename.' - format compact.pdf';
}elseif ($type == 'Polygons') {
    $a = new PolygonCards();
    $filename = $filename.' - polygones.pdf';
} else {
    $a = new AnnuaireFiches();
    $filename = $filename.' - format fiches.pdf';
}

$a->AddFont('Steelfish', '', 'steelfishrg.php');
$a->AddFont('Futura', '', 'Futura (Light).php');
$a->AddFont('Futura', 'B', 'Futura Heavy.php');
$a->AddFont('Futura', 'BI', 'Futura Heavy.php');
$a->AddFont('Futura', 'I', 'Futura Heavy.php');
$a->AddFont('FreeScript', '', 'FREESCPT.php');
$a->SetTopMargin(1);
$a->SetAutoPageBreak(false, $a->bottomMargin);

$impl = new AnnuaireImpl( $type );

$h = $impl->getHeader();

$title = $h['titre'];
$sstitle = $h['sstitre'];
$localisation = $h['localisation'];
$slogan = $h['slogan'];

$a->SetTitle($title);
$a->SetAuthor('Le Florain');

if ($edition == 'toutes') {
    $town = findTown('Nancy');
    $a->PrintAnnuaire($impl, $town);
    $town = findTown('Toul');
    $a->PrintAnnuaire($impl, $town);
    $town = findTown('Pont-à-Mousson');
    $a->PrintAnnuaire($impl, $town);
    $town = findTown('Lunéville');
    $a->PrintAnnuaire($impl, $town);
    $town = findTown('Badonviller');
    $a->PrintAnnuaire($impl, $town);

    $a->PrintOrphans($impl);
} else {
    $a->PrintAnnuaire($impl, $town);
}

$a->SetDisplayMode('fullpage', 'two');
$a->Output($output, $filename);
