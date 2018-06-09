<?php
require('fpdf/fpdf.php');
require('fpdf/categorie.php');
require('fpdf/marche.php');
include('pix_tools.php');

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

function Header()
{
    // Save ordinate
    $this->top_col = $this->GetY() + $this->topSubPage;
    $this->bas_col0 = $this->GetY() + $this->topSubPage;
    $this->bas_col1 = $this->GetY() + $this->topSubPage;

    global $no_header;
    if( $no_header ) {
        return;
    }

    // Page header
    global $title;

    $this->SetFont('Futura','',10);
    $w = $this->GetStringWidth($title)+6;
    $this->SetX((210-$w)/2);
    $this->SetTextColor(0,0,0);
    $this->SetLineWidth(1);
    $this->Cell($w,9,$title,0,1,'C',false);
    $this->Ln(5);
    // Save ordinate
    $this->top_col = $this->GetY();
    $this->bas_col0 = $this->GetY();
    $this->bas_col1 = $this->GetY();
    $this->col = 0;
}

function Footer()
{
    global $no_footer;
    if( $no_footer ) {
        return;
    }
    // Page footer
    $this->SetY(-15);
    $this->SetFont('Futura','I',8);
    $this->SetTextColor(128);
    $this->Cell(0,10,'Page '.($this->PageNo()-1),0,0,'C');
}

function SetCol($col)
{
        // ( $this->colMargin*(2*$this->subPage+1) + $this->GetColumnWidth()*$this->subPage*2),
    // Set position at a given column
    $this->col = $col;
    $x = $col*$this->GetColumnWidth()+$this->GetColumnWidth()*$this->subPage*2 + ($col+$this->marginLeft+2*$this->subPage)*$this->colMargin;
    $this->SetLeftMargin($x);
    $this->SetX($x);

}

function AcceptPageBreak()
{
    return false;
}

function resetColumn() {
    $this->SetCol(0);
    $this->SetY( max( $this->bas_col1, $this->bas_col0 ) );
    $this->bas_col0 = $this->getY();
    $this->bas_col1 = $this->getY();
}

function SpaceLeftCol0( $offset ) {
    // 20 is the default bottom margin
    return $this->GetPageHeight() - $this->bMargin - $this->bas_col0 - $offset;
}

function SpaceLeftCol1( $offset ) {
    // 20 is the default bottom margin
    return $this->GetPageHeight() - $this->bMargin - $this->bas_col1 - $offset;
}

function TextWidth($texte, $s=10, $m='', $f='Futura' ) {
    // Font
    $this->SetFont($f,$m,$s);
    return $this->GetStringWidth( $texte );
}


function PrintText($texte, $s=60, $f=10, $align='C') {
    // Font
    $this->SetTextColor(0,0,0);
    $this->SetFont('Futura','',$f);
    $this->MultiCell($s,$this->cellHeight,$texte,0, $align);
}

function PrintName($name, $s=60, $size=10, $c='C', $f='Futura', $w='B') {
    // Font
    $this->SetTextColor(112,112,111);
    $this->SetFont($f,$w,$size);
    $this->MultiCell($s,$this->cellHeight,$name,0,$c);
}

function PrintValue($texte, $s=90, $f=12, $align='L') {

    // logo
    $this->Image('images/logo-monnaie-disquevert.png',
        $this->GetX() - 7, $this->GetY() + 7, 5
    );

    // Font
    $this->SetFont('Futura','',$f);
    // Output text in a 6 cm width column
    $this->MultiCell($s,$this->cellHeight,$texte,0, $align);
}

function debug( $message, $yoffs=-5, $w=100 ) {

    $x = $this->GetX();
    $y = $this->GetY();

    //$message = "(".$x.",".$y.") ".$message;
    // Font
    $this->SetTextColor( 255, 0 , 0 );
    $this->SetFont('Futura','B',12);
    // Output text in a 9 cm width column
    //$this->SetX( 40 );
    $this->SetY( $y + $yoffs );
    $this->Cell($w,5,$message,1,1,'L', true);

    $this->SetTextColor( 0, 0 , 0 );
    $this->SetX( $x );
    $this->SetY( $y );
}

} // Class Annuaire

/******************************************************************/

class AnnuaireLivret extends Annuaire {

public $bottomMargin = 20;
public $colMargin = 10;
public $cellHeight = 5;
public $marginLeft = 1; // 1 if there is a margin at left (and right) 0 if no margin.

function PrintCouverture() {
    global $title;

    $this->SetFillColor(204,220,62);
    $this->SetTextColor(112,112,111);
    $this->Rect( 0, 0, $this->GetPageWidth(), $this->GetPageHeight(), "F" );

    $this->Image('images/FlorainFA5-vert.jpg',
        ($this->GetPageWidth()-60)/2, 20, 60
    );

    $this->SetFont('Steelfish','',98);

    $this->SetY(120);
    $w = $this->GetStringWidth("Le Florain")+6;
    $this->SetLeftMargin(($this->GetPageWidth()-$w)/2);
    $this->Cell( $w, 30, "Le Florain", 0, 1 );
    $this->SetFont('Futura','',34);
    $this->Cell( $w, 20, "Monnaie Locale", 0, 1 );
    $this->SetFont('Futura','',19);
    $this->Cell( $w, 5, "de l'Aire de Vie Nancéienne"  );

    $this->SetFont('Futura','',78);
    $this->SetY($this->GetPageHeight()/1.3);
    $w = $this->GetStringWidth("L'Annuaire")+6;
    $this->SetX(($this->GetPageWidth()-$w)/2);
    $this->Cell( 0, 9, "L'Annuaire" );

    $this->SetFont('Futura','',15);
    $this->SetY($this->GetPageHeight()-40);
    $w = $this->GetStringWidth($title)+6;
    $this->SetX(($this->GetPageWidth()-$w)/2);
    $this->Cell( 0, 9, $title );
}

function PrintCharte() {
    global $title;

    // green bg
    $this->SetFillColor(204,220,62);
    $this->SetTextColor(112,112,111);
    $this->Rect( 0, 0, $this->GetPageWidth()/2, $this->GetPageHeight(), "F" );

    // charte
    $this->SetY( $this->GetPageHeight()*.1 );
    $this->SetLeftMargin( 110 );
    $xmlDoc = new DOMDocument();
    $xmlDoc->load("charte.xml");
    $x = $xmlDoc->documentElement;
    $titre = $x->getAttribute( "titre");
    $this->PrintName(utf8_decode( $titre ), 100, 16, 'C' );
    $this->Ln( 20 );
    $intro = $x->getAttribute( "intro");
    $this->PrintText( utf8_decode( $intro ), 90, 12, 'J' );

    $this->SetLeftMargin( 120 );
    $valeurs = $x->getElementsByTagName( "valeur" );
    $nb_v = $valeurs->length;
    for($v=0; $v<$nb_v; $v++) {
        $this->PrintValue( utf8_decode( $valeurs[$v]->nodeValue ), 80 );
    }

    // logo
    $this->Image('images/FlorainFA5-vert.jpg',
        ($this->GetPageWidth()/2-20)/2, $this->GetPageheight()*.2, 20
    );

    // site web
    $this->SetFont('Futura','B',28);
    $this->SetTextColor( 112, 112, 111 );
    $this->SetY($this->GetPageheight()*.4);
    $w = $this->GetStringWidth("www.florain.fr")+6;
    $this->SetX(($this->GetPageWidth()/2-$w)/2);
    $this->Cell( $w, 20, "www.florain.fr" );
    // mail
    $this->SetFont('Futura','B',18);
    $this->SetY($this->GetPageheight()*.5);
    $w = $this->GetStringWidth("contact@florain.fr")+6;
    $this->SetX(($this->GetPageWidth()/2-$w)/2);
    $this->Cell( $w, 20, "contact@florain.fr" );
}

function PrintAnnuaire( $x ) {
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
    $toc = $this->PrintAllCategories( $x );

    $force_toc = false;
    // get the current number of pages + the 4eme de couv
    $nbpages = $this->PageNo() + 1;
    if( $force_toc ) {
    	  $nbpages = $nbpages + 1;
    }

    // we want a multiple of 4 to print it as a booklet.
    $reste4 = $nbpages % 4;

    if( $reste4 > 0 ) {
        $nb = 4 - $reste4;
        if( $nb == 3 ) {
            // print pages for notes
            $this->AddPage();
            $this->PrintNotes();
        }

        $this->AddPage();
        $this->PrintComptoirs( $x );

        $no_header = true;
        if( $force_toc || $nb > 1 ) {
            $this->AddPage();
            $no_footer = true;
            $this->PrintTOC( $toc );
        }
    }

    $no_header = true;
    // no header nor footer in the last page
    $this->AddPage();
    $no_footer = true;
    $this->PrintCharte();
}

function PrintAllCategories( $x ) {

    $toc = array();
    $categories = $x->getElementsByTagName( "categorie" );
    $nb_cat = $categories->length;
    for($cat=0; $cat<$nb_cat; $cat++) {

        $categorie = $categories[$cat];

        $myCat = new CategorieLivret( $this, $categorie );
        $toc[$cat] = $myCat->display();
    }

    $marches = $x->getElementsByTagName( "marches" );
    $nb_mar = $marches->length;
    for($mar=0; $mar<$nb_mar; $mar++) {

        $marche = $marches[$mar];

        $myMar = new CategorieMarcheLivret( $this, $marche );
        $toc[$cat+$mar] = $myMar->display();
    }

    return $toc;
}

function TocPrintCat( $name, $page ) {
    $this->Ln(4);
    $this->SetX(45);
    // Font
    $this->SetFont('Futura','B',14);
    // Output text in a 6 cm width column
    $this->Cell(60,5,$name,0,0);
    $this->Cell(60,5,$page,0,1,'R');
}

function TocPrintSCat( $name, $page ) {
    if( $name == "none" ) {
        return;
    }
    $this->SetX(55);
    // Font
    $this->SetFont('Futura','',12);
    // Output text in a 6 cm width column
    $this->Cell(60,5,$name,0,0);
    $this->Cell(50,5,$page,0,1,'R');
}

function PrintComptoir() {
    // Font
    $this->SetFont('Steelfish','',14);
    $this->SetFillColor(255,255,255);
    // Output text in a 3 cm width column
    $this->Cell(30,5,"Comptoir de Change",0,1,'C', true);
}

function PrintComptoirs( $x ) {
    $this->resetColumn();

    $this->SetFont('Futura','B',18);
    $this->SetFillColor(204,220,62);
    $this->SetTextColor(112,112,111);
    $this->Cell($this->GetColumnWidth()*2+$this->colMargin,6," Les comptoirs de change",'B',1,'L',true);
    $this->Ln(4);

    // Save ordinate
    $this->bas_col0 = $this->GetY();
    $this->bas_col1 = $this->GetY();

    $toc = array();
    $acteurs = $x->getElementsByTagName( "acteur" );
    $nb_acteurs = $acteurs->length;
    $c = 0;
    for($a=0; $a<$nb_acteurs; $a++) {

        $acteur = $acteurs[$a];

        $myA = new ActeurLivret( $this, $acteur );
        if( $myA->isComptoir() ) {
            $myA->display_comptoir( $c % 2, $c );
            $c = $c + 1;
        }
    }
    return $toc;
}

function PrintComptoirsTOC( $toc ) {

    $this->SetFont('Futura','B',18);
    $this->SetFillColor(204,220,62);
    $this->SetTextColor(112,112,111);
    $this->Cell(0,6," Comptoirs de change",'B',1,'L',true);
    $this->Ln(4);

    $count = count($toc);
    for( $cat = 0; $cat < $count; $cat++) {
        $scount = count( $toc[$cat] );

        for( $s = 0; $s < $scount-2; $s++) {
            $acount = count( $toc[$cat][$s] );
            for( $a = 0; $a < $acount-2; $a++) {
                if( $toc[$cat][$s][$a]['c'] == true ) {
                    $this->TocPrintCat( $toc[$cat][$s][$a]['a'], $toc[$cat][$s][$a]['p'] );
                }
            }
        }
    }
}

function PrintNotes() {
    $this->resetColumn();

    $this->SetFont('Futura','B',18);
    $this->SetFillColor(204,220,62);
    $this->SetTextColor(112,112,111);
    $this->Cell(0,6," Mes notes",'B',1,'L',true);
    $this->Ln(3);

    $this->SetLeftMargin( 30 );

    $largeur = 200;
    $this->Image('images/logo-monnaie-gray.png',
        ($this->GetPageWidth()-$largeur)/2, ($this->GetPageHeight()-200)/2, $largeur
    );
    for( $l=0; $l < 20; $l++ ) {
         $this->Cell(160,12," ",'B',1,'L',false);

    }

}
function PrintTOC( $toc ) {
    $this->resetColumn();

    $this->Ln(10);
    $this->SetFont('Futura','B',18);
    $this->SetFillColor(204,220,62);
    $this->SetTextColor(112,112,111);
    $this->Cell(0,6," Sommaire",'B',1,'L',true);
    $this->Ln(3);

    $count = count($toc);
    for( $cat = 0; $cat < $count; $cat++) {
        $scount = count( $toc[$cat] );

        $this->TocPrintCat( $toc[$cat]['type'], $toc[$cat]['page'] );
        for( $s = 0; $s < $scount-2; $s++) {
            $this->TocPrintSCat( $toc[$cat][$s]['type'], $toc[$cat][$s]['page'] );
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

function NextPage() {
    $this->addPage();
}

// the column width is the half of the page without the margin size
// we have 3 margin: left,right and middle.
function GetColumnWidth()
{
    return ($this->GetPageWidth()-$this->colMargin*3) / 2;
}

} // Class AnnuaireLivret

/*********************************************************************/

class AnnuairePoche extends Annuaire
{

public $bottomMargin = 3;
public $colMargin = 2;
public $cellHeight = 3;
public $marginLeft = 0; // 1 if there is a margin at left (and right) 0 if no margin.

function GetColumnWidth()
{
    // Divides pages in 8 columns if nbSubPages = 4
    // take into account margins between columns only: no left/right margins
    return ($this->GetPageWidth()-$this->colMargin*( $this->nbSubPages*2-1 ) ) / ($this->nbSubPages*2);
}

function AddSubPage()
{
// If page is split in 4 subPages, and current subPage == 3, then add page. Otherwise add subPage.
    if( $this->nbSubPages <= 1 ) {
        return;
    }

    if( $this->subPage == ($this->nbSubPages - 1) ) {
        $this->subPage = 0;
        $this->addPage('L');
    } else if ( $this->subPage < ($this->nbSubPages - 1) ) {
        $this->subPage = $this->subPage + 1;
    }
    $this->setXY(
        ( $this->colMargin*2*$this->subPage + $this->GetColumnWidth()*$this->subPage*2),
        $this->topSubPage
    );
}

function PrintCouverture() {
    global $sstitle;

    $margin = $this->GetX();
    $cellwidth = $this->GetColumnWidth()*2 + $this->colMargin;

    $this->SetFillColor(204,220,62);
    $this->SetTextColor(112,112,111);
    $this->Rect( $margin-$this->colMargin, 0, $this->GetColumnWidth()*2+$this->colMargin*3, $this->GetPageHeight(), "F" );

    $this->Image('images/FlorainFA5-vert.jpg',  $margin + ($cellwidth-40)/2, 20, 40);

    $this->SetFont('Steelfish','',48);

    $this->SetY(80);
    $w = $this->GetStringWidth("Le Florain")+6;
    $this->SetLeftMargin($margin+($cellwidth-$w)/2);
    $this->Cell( $w, 15, "Le Florain", 0, 1 );

    $this->SetFont('Futura','',17);
    $w = $this->GetStringWidth("Monnaie Locale")+6;
    $this->SetLeftMargin($margin+($cellwidth-$w)/2);
    $this->Cell( $w, 7, "Monnaie Locale", 0, 1 );

    $this->SetFont('Futura','',9);
    $w = $this->GetStringWidth("de l'Aire de Vie Nancéienne")+6;
    $this->SetLeftMargin($margin+($cellwidth-$w)/2);
    $this->Cell( $w, 5, "de l'Aire de Vie Nancéienne"  );

    $this->SetFont('Futura','',40);
    $this->SetY($this->GetPageHeight()/1.5);
    $w = $this->GetStringWidth("L'Annuaire")+6;
    $this->SetX($margin+($cellwidth-$w)/2);
    $this->Cell( $w, 9, "L'Annuaire" );
    $this->SetFont('Futura','',20);
    $this->SetXY($margin+5,$this->getY()+10);
    $this->Cell( $w, 5, "de poche" );

    $this->SetFont('Futura','B',10);
    $this->SetXY( $margin+5,$this->GetPageHeight()-20);
    $this->MultiCell($cellwidth-10, $this->cellHeight, $sstitle, 0, 'C');

    $this->SetY($this->GetPageHeight()-40);
    // site web
    $this->SetFont('Futura','B',18);
    $w = $this->GetStringWidth("www.florain.fr")+6;
    $this->SetX($margin+($cellwidth-$w)/2);
    $this->PrintName("www.florain.fr", 0, 10, 'C' );
    // mail
    $this->SetFont('Futura','B',14);
    $w = $this->GetStringWidth("contact@florain.fr")+6;
    $this->SetX($margin+($cellwidth-$w)/2);
    $this->PrintName("contact@florain.fr", 0, 10, 'C' );
}

function PrintCharte() {
    global $title;

    $margin = $this->GetX();

    // green bg
    $this->SetFillColor(204,220,62);
    $this->SetTextColor(112,112,111);
    $this->Rect( $margin+$this->GetColumnWidth()*1.65, 0, $this->GetColumnWidth()*.39, $this->GetPageHeight(), "F" );

    // charte
    $this->SetY( $this->GetPageHeight()*.05 );
    $this->SetLeftMargin( $margin );
    $xmlDoc = new DOMDocument();
    $xmlDoc->load("charte.xml");
    $x = $xmlDoc->documentElement;
    $titre = $x->getAttribute( "titre");
    $this->PrintName(utf8_decode( $titre ), $this->GetColumnWidth()*2, 16, 'C', "steelfish", "" );
    $this->Ln( 5 );
    $intro = $x->getAttribute( "intro");
    $this->PrintText( utf8_decode( $intro ), $this->GetColumnWidth()*2, 10, 'J' );

    $this->SetLeftMargin( $margin+8 );
    $valeurs = $x->getElementsByTagName( "valeur" );
    $nb_v = $valeurs->length;
    for($v=0; $v<$nb_v; $v++) {
        $this->PrintValue( utf8_decode( $valeurs[$v]->nodeValue ), $this->GetColumnWidth()*1.8, 10 );
    }

}

function PrintAnnuaire( $x ) {

    $this->doc = $x;
    $this->AddPage('L');

    $this->PrintAllCategories( $x );

    // print the charte if enough space
    if( $this->subPage == 1 ) {
        $this->AddSubPage();
        $this->SetCol( 0 );
        $this->PrintCharte();
    }

    // print the comptoir advice
    $this->setCol( 0 );
    $this->SetY($this->GetPageheight()-15);

    // for one line text $texte_width = $this->marginLeft + $this->colMargin*6 + $this->GetColumnWidth()*6;
    $texte_width = $this->GetColumnWidth()*2;
    $texte = utf8_decode( $x->getAttribute( "comptoirs" ) );
    $h = $this->MultiCellHeight( $texte_width, 4, $texte, 0, 'C' );
    $this->SetFillColor(234,250,180);
    $this->Rect( $this->GetX()-1, $this->GetY()-3, $texte_width, $h+2, "F" );

    $this->PrintName( $texte, $texte_width );

    // at last print the couverture
    $this->AddSubPage();
    $this->SetCol( 0 );
    $this->PrintCouverture();
}

function PrintAllCategories( $x ) {

    $categories = $x->getElementsByTagName( "categorie" );
    $nb_cat = $categories->length;
    for($cat=0; $cat<$nb_cat; $cat++) {

        $categorie = $categories[$cat];

        $myCat = new CategoriePoche( $this, $categorie );
        $myCat->display();
    }

    $marches = $x->getElementsByTagName( "marches" );
    $nb_mar = $marches->length;
    for($mar=0; $mar<$nb_mar; $mar++) {

        $marche = $marches[$mar];

        $myMar = new CategorieMarchePoche( $this, $marche );
        $myMar->display();
    }
}

function NextPage() {
    $this->AddSubPage();
}

}

/******************************************************************/

if( isset($_GET) ) {
    while(list($name, $value) = each($_GET)){
        $$name = $value;
    }
}
if (!isset($type)) $type = 'Livret';
if (!isset($output)) $output = 'D';

$no_footer = true;
$no_header = true;
$a = NULL;
if( $type == 'Poche' ) {
    $a = new AnnuairePoche();
    $filename = "Annuaire du Florain - format poche.pdf";
} else {
    $a = new AnnuaireLivret();
    $filename = "Annuaire du Florain - format livret.pdf";
}

$a->AddFont('Steelfish','','steelfishrg.php');
$a->AddFont('Futura','','Futura (Light).php');
$a->AddFont('Futura','B','Futura Heavy.php');
$a->AddFont('Futura','BI','Futura Heavy.php');
$a->AddFont('Futura','I','Futura Heavy.php');
$a->SetTopMargin( 1 );
$a->SetAutoPageBreak( false, $a->bottomMargin );

$xmlDoc = new DOMDocument();
$xmlDoc->load("acteurs-cat.xml");

$x = $xmlDoc->documentElement;
$title = utf8_decode( $x->getAttribute( "titre" ) );
$sstitle = utf8_decode( $x->getAttribute( "sstitre" ) );
$a->SetTitle($title);
$a->SetAuthor('Le Florain');

$a->PrintAnnuaire( $x );

$a->SetDisplayMode( 'fullpage', 'two' );
$a->Output( $output, $filename );

?>
