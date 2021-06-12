<?php

/**************************************************************************/
class CardTemplate {
    public $angle = 0;
    public $rayon = 0;
    public $nbPerPage = 1;
    protected $XCenter=0;
    protected $YCenter=0;
    protected $a;

    public function Image($x, $y, $r, $image)
    {
        $imgpath = 'images/acteurs/'.$image;
        if (is_file($imgpath)) {
            // $this->a->Image($imgpath, $x - $r/2, $y-$r/2+15, null, 30);
            $this->a->Image($imgpath, $x-21, $y-15, 42, 30);
        }
    }
}

class EvenementCardTemplate extends CardTemplate {

    protected $nbX = 5;
    protected $nbY = 5;
    protected $XCenter=0;
    protected $YCenter=0;
    protected $pdf = null;
    protected $color = null;
    protected $title = "";
    protected $Lx=0;
    protected $Ly=0;


    public function __construct($pdf) {
        $this->pdf = $pdf;
        $this->XCenter = $pdf->GetPageWidth() / 2 ;
        $this->YCenter = $pdf->GetPageHeight() / 2 ;
        $this->nbPerPage = $this->nbX *  $this->nbY;
        $this->Lx = $this->pdf->GetPageWidth() / $this->nbX - 3 ;
        $this->Ly = $this->pdf->GetPageHeight() / $this->nbY - 3;
        $this->rr = new RoundedRect();
    }

    function getCenter( $idx, $verso=false ) {
        $i = fmod( $idx, $this->nbX );
        $j = ceil( ($idx+1) / $this->nbY );

        if( $i == 0 && $j == 1 ) {
            $this->pdf->addPage();
        }

        $sideFactor = 1;
        if( $verso ) {
            $sideFactor = -1;
        }
        $c['X'] = $this->XCenter - (($this->nbX+$sideFactor - 1)/2 - $i ) *  $this->Lx * $sideFactor;
        $c['Y'] = $this->YCenter - ($this->nbY/2 - ($j-1)) *  $this->Ly;

        return $c;
    }

    function rect( $c, $shift = 0, $r = 2, $f = '' ) {
        $this->rr->_Rect($this->pdf, $c['X']+ $shift, $c['Y']+ $shift, $this->Lx- $shift*2, $this->Ly- $shift*2, $r, '1234', $f);
    }

    function verso( $center, $titre, $r, $g, $b ) {
        
        $c['X'] = $center['X']-1;
        $c['Y'] = $center['Y']+.1;
        $this->pdf->SetFillColor(255, 255, 255);
        $this->rect( $c, 0.25);
        $this->pdf->SetFillColor( intval($r), intval($g),intval($b));
        $shift = $this->Lx*.05;

        $this->rect( $c, $shift, 0, 'F');
        $this->pdf->Image('images/com/FlorainF_FA5_TG.png',
            $c["X"] + $shift, $c["Y"]+ $shift, $this->Lx- $shift*2, $this->Ly- $shift*2
        );
        $this->pdf->SetTextColor(255, 255, 255);

        $a = 50;
        $this->pdf->SetFont('Futura', 'B', 25); 
        $w = $this->pdf->GetStringWidth( $titre );
        
        //$xAdj = $this->Lx* $w/$a;
        //$yAdj = $this->Ly * $w/(90-$a);
        //$this->pdf->RotatedText( $c['X'] + $xAdj,  $c['Y']+ $yAdj, $titre, $a);

        $yAdj = ( $this->pdf->GetStringWidth( "Territoire" ) - $w ) * .7;

        $this->pdf->RotatedText( $c['X'] + $this->Lx*.15,  $c['Y']+ $this->Ly*.3 + $yAdj, $titre, -55);
    }

    function recto( $c, $title, $text, $action ) {
        $this->rect( $c, .25 );

        $this->pdf->setY($c['Y']+5);
        $this->pdf->setX($c['X']);
        $this->pdf->SetFont('Steelfish', '', 20);
        $this->pdf->SetTextColor(112, 112, 111);
        $this->pdf->MultiCell($this->Lx, 7, $title, 0, 'C');

        $this->pdf->Ln();
        $this->pdf->setX($c['X']);
        $this->pdf->SetFont('Futura', '', 8);
        $this->pdf->MultiCell($this->Lx, 5, $text, 0, 'C');
        
        $this->pdf->Ln();
        $this->pdf->SetTextColor(0, 0, 0);
        $this->pdf->setX($c['X']);
        $this->pdf->SetFont('Futura', '', 8);
        $this->pdf->MultiCell($this->Lx, 5, $action, 0, 'C');

    }

}


class EvenementCardVerso
{
    protected $xml;
    public function __construct($pdf, $xml,$template)
    {
        $this->a = $pdf;
        $this->xml = $xml;
        $this->template = $template;
    }

    public function display( $idx ) {
        $this->printVerso( $idx );
    }

    protected function printVerso( $idx ) {
        $categorie = $this->xml->parentNode;
        $type = $categorie->parentNode;
        $titre = utf8_decode($type->getAttribute( "titre" ));
        $r = $type->getAttribute( "r" );
        $g = $type->getAttribute( "g" );
        $b = $type->getAttribute( "b" );

        $c = $this->template->getCenter( $idx, true );
        $this->template->verso( $c, $titre, $r, $g, $b );
    }
}

class EvenementCardRecto extends EvenementCardVerso
{
    
    public function __construct($pdf, $xml, $template)
    {
        $this->a = $pdf;
        $this->xml = $xml;
        $this->template = $template;
    }

    public function display( $idx ) {
        $this->printRecto( $idx );
    }

    protected function printRecto( $idx ) {

        $parent = $this->xml->parentNode;
        $titre = utf8_decode($parent->getAttribute( "type" ));
        $texte = utf8_decode($this->xml->getAttribute( "texte" ));
        $action = utf8_decode($this->xml->getAttribute( "action" ));
        $c = $this->template->getCenter( $idx );
        $this->template->recto( $c, $titre, $texte, $action );
    }
}

class PolygonCardTemplate0 extends CardTemplate
{
    public function __construct( $pdf ) {
        $this->a = $pdf;
        $this->rayon = $pdf->GetPageWidth() / 4;
        $this->angle = 0;
        $this->nbPerPage = 5;

        $this->XCenter = $pdf->GetPageWidth() / 2 ;
        $this->YCenter = $pdf->GetPageHeight() / 2 ;
    }

    public function getCenter( $i ) {
        
        $m = fmod( $i, 5);
        
        if( $m == 0 ) {
            $this->a->addPage();
        }

        $lamba = .935;
        $shiftX = $this->XCenter /2 * $lamba;
        $shiftY = $this->XCenter /1.155 * $lamba;
        if( $m == 0 ) {
            $coords["X"] = $this->XCenter - $shiftX;
            $coords["Y"] = $this->YCenter - $shiftY;
        } else if($m == 1 ) {
            $coords["X"] = $this->XCenter + $shiftX;
            $coords["Y"] = $this->YCenter - $shiftY;
        } else if($m == 2 ){
            $coords["X"] = $this->XCenter ;
            $coords["Y"] = $this->YCenter ;
        } else if($m == 3 ){
            $coords["X"] = $this->XCenter - $shiftX;
            $coords["Y"] = $this->YCenter + $shiftY;
        } else if($m == 4 ){
            $coords["X"] = $this->XCenter + $shiftX;
            $coords["Y"] = $this->YCenter + $shiftY;
        }

        return $coords;
    }
    

    public function Entete($x, $y, $r, $acteur)
    {
        $image = utf8_decode($acteur->getAttribute('image'));
        $titre = utf8_decode($acteur->getAttribute('titre'));
        $bref = utf8_decode($acteur->getAttribute('bref'));

        $this->Image($x, $y, $r, $image);

        $this->a->SetY($y - $r/2);
        $this->a->SetX($x - $r*.8);
        // Le nom
        $this->a->SetFont('Steelfish', '', 19);
        $this->a->RotatedText($x - $r*.7, $y - $r *.40, $titre, 30);

        $this->a->SetFont('Futura', '', 16);
        $this->a->RotatedText($x - $r*.7, $y + $r *.45, $bref, -30);

    }

    public function printActeur($x, $y, $r, $acteur)
    {
        $savedX = $this->a->GetX();
        $savedY = $this->a->GetY();

        $this->Entete($x, $y, $r, $acteur);
        $this->printTrgls( $x, $y, $r );
        
        $this->a->SetX($savedX);
        $this->a->SetY($savedY);
    }
    
    public function printTrgls($x, $y, $r) {

        $min = 1; // rand(1,2);
        $max = 1; // rand(2,4);
        $ns = 3;

        $nl = rand(2,3);
        $tr = $this->rayon /7;
        $ix = 0;
        $iy = $tr*2;

        $tx = $x - $this->rayon *.75;
        $ty = $y - ($nl-1) * $iy / 2;

        $c = range(0, 5);
        shuffle($c);

        for( $l=0; $l<$nl; ++$l) {

            $cx = $tx + $l*$ix;
            $cy = $ty + $l*$iy;
            $this->a->RegularPolygon( $cx, $cy, $tr, $ns, -30, 'F', null, $this->a->color($c[$l]) );
            
            $this->a->SetY($cy - $iy);
            $this->a->SetX($cx - $tr/3);
            $this->a->SetTextColor(0, 0, 0);

            $this->a->SetFont('Steelfish', '', 16);
            $val = rand( $min , $max);

            $this->a->Cell(10, 30, $val, 0, 1);
        }

        $nr = rand(1,$nl);
        $ty = $y - ($nr-1) * $iy / 2;
        $tx = $x + $this->rayon *.65;

        for( $l=0; $l<$nr; ++$l) {

            $cx = $tx + $l*$ix;
            $cy = $ty + $l*$iy;
            $this->a->RegularPolygon( $cx, $cy, $tr, $ns, -30, 'F', null, $this->a->color($c[$nl+$l]) );
            $this->a->SetY($cy - $iy);
            $this->a->SetX($cx - $tr/3);
            $this->a->SetFont('Steelfish', '', 16);

            $val = rand( $min * 2, $max *2);
            $this->a->Cell(10, 30, $val, 0, 1);
        }

    }

    public function printSlogan( $c ) { 
        $this->a->SetTextColor(112, 112, 111);
        $this->a->SetFont('FreeScript', '', 35);
        $this->a->RotatedText($c["X"] - $this->rayon*.7, $c["Y"] - $this->rayon *.40, "La monnaie", 30);
        $this->a->RotatedText($c["X"] + $this->rayon*.02, $c["Y"] + $this->rayon *.90, utf8_decode("qui crée du lien!"), 30);
    }
}


class PolygonCardTemplate90Orig extends CardTemplate
{
    public function __construct( $pdf ) {
        $this->a = $pdf;
        $this->rayon = $pdf->GetPageWidth() / 5;
        $this->angle = 90;
        $this->nbPerPage = 6;
        
        $this->XCenter = $pdf->GetPageWidth() / 2 ;
        $this->YCenter = $pdf->GetPageHeight() / 2 ;
    }

    public function getCenter( $i, $recto ) {
        
        $m = fmod( $i, $this->nbPerPage);
        
        if( $m == 0 ) {
            $this->a->addPage();
        }

        $toTop = 20;
        $toLeft = 29;

        $lamba = .935;
        $shiftX = $this->XCenter /2 * $lamba;
        $shiftY = $this->XCenter /1.23 * $lamba;
        if( ! $recto ) {
            $toLeft = -$toLeft;
            $shiftX = -$shiftX;
        }
        if( $m == 0 ) {
            $coords["X"] = $this->XCenter - $shiftX;
            $coords["Y"] = $this->YCenter - $shiftY - $toTop;
        } else if($m == 1 ) {
            $coords["X"] = $this->XCenter + $shiftX - $toLeft;
            $coords["Y"] = $this->YCenter - $shiftY - $toTop +$shiftY/2;
        } else if($m == 2 ){
            $coords["X"] = $this->XCenter - $shiftX;
            $coords["Y"] = $this->YCenter  - $toTop;
        } else if($m == 3 ){
            $coords["X"] = $this->XCenter + $shiftX- $toLeft;
            $coords["Y"] = $this->YCenter  - $toTop+$shiftY/2;
        } else if($m == 4 ){
            $coords["X"] = $this->XCenter - $shiftX;
            $coords["Y"] = $this->YCenter + $shiftY - $toTop;
        } else if($m == 5 ){
            $coords["X"] = $this->XCenter + $shiftX- $toLeft;
            $coords["Y"] = $this->YCenter + $shiftY - $toTop+$shiftY/2;
        } 

        return $coords;
    }
    

    public function Entete($x, $y, $r, $acteur)
    {
        
        $image = utf8_decode($acteur->getAttribute('image'));
        $titre = utf8_decode($acteur->getAttribute('titre'));
        $bref = utf8_decode($acteur->getAttribute('bref'));

        $this->Image($x, $y, $r, $image);
        
        $this->a->SetTextColor(112, 112, 111);
        $this->a->SetFont('Steelfish', '', 19);

        $this->a->SetLeftMargin($x-$this->rayon *.60);
        $this->a->SetY($y- $this->rayon *.82);
        $this->a->MultiCell(50, 8, $titre, 0, 'C');

        $this->a->SetFont('Futura', '', 12);
        $this->a->MultiCell(50, 5, $bref, 0, 'C');

    }

    public function printActeur($x, $y, $r, $acteur)
    {
        $savedX = $this->a->GetX();
        $savedY = $this->a->GetY();

        $this->Entete($x, $y, $r, $acteur);
        
        $this->printTrgls( $x, $y, $r );
        

        $this->a->SetX($savedX);
        $this->a->SetY($savedY);
    }
    
    public function printTrgls($x, $y, $r) {

        $min = rand(1,2);
        $max = rand(2,4);
        $ns = 3;

        $nl = rand(2,3);
        $tr = $this->rayon /7;
        $ix = $tr*1.15;
        $iy = $tr*2;

        $tx = $x - $this->rayon *.82 + (3-$nl)*2;
        $ty = $y + (4-$nl)*$iy/2 *0.65;

        $this->a->SetTextColor(0, 0, 0);

        $c = range(0, 5);
        shuffle($c);

        for( $l=0; $l<$nl; ++$l) {

            $cx = $tx + $l*$ix;
            $cy = $ty + $l*$iy;
            $this->a->RegularPolygon( $cx, $cy, $tr, $ns, 0, 'F', null, $this->a->color($c[$l]) );
            
            $this->a->SetY($cy - $iy*1.15);
            $this->a->SetX($cx - $tr/3);
            $this->a->SetFont('Steelfish', '', 16);
            $val = rand( $min , $max);

            $this->a->Cell(10, 30, $val, 0, 1);
        }

        $nr = rand(1,$nl);
        $ty = $y - ($nr-3.5) * $iy / 2;
        $tx = $x + $this->rayon *.75 - (3-$nr)*3;

        for( $l=0; $l<$nr; ++$l) {

            $cx = $tx - $l*$ix;
            $cy = $ty + $l*$iy;
            $this->a->RegularPolygon( $cx, $cy, $tr, $ns, 60, 'F', null, $this->a->color($c[$l + $nl]) );
            $this->a->SetY($cy - $iy*1.25);
            $this->a->SetX($cx - $tr/3);
            $this->a->SetFont('Steelfish', '', 16);

            $val = rand( $min * 2, $max *2);
            $this->a->Cell(10, 30, $val, 0, 1);
        }

    }
    
    public function printSlogan( $c ) {
        
        $this->a->SetTextColor(112, 112, 111);

        $this->a->SetFont('Steelfish', '', 40);
        $this->a->SetLeftMargin($c["X"]-$this->rayon *.60);
        $this->a->SetY($c["Y"]- $this->rayon *.85);
        $this->a->Cell(50, 10, "Le Florain", 0, 0, 'C');
        
        $this->a->SetFont('FreeScript', '', 35);

        $this->a->SetLeftMargin($c["X"]-$this->rayon *.53);
        $this->a->SetY($c["Y"] + $this->rayon *.47);
        $this->a->Cell(50, 10, "La monnaie qui", 0, 0, 'C');

        $this->a->SetLeftMargin($c["X"]-$this->rayon *.55);
        $this->a->SetY($c["Y"] + $this->rayon *.65);
        $this->a->Cell(50, 10, utf8_decode("crée du lien!"), 0, 0, 'C');
    }
}

class PolygonCardTemplate90 extends CardTemplate
{
    public function __construct( $pdf ) {
        $this->a = $pdf;
        $this->rayon = $pdf->GetPageWidth() / 5;
        $this->angle = 90;
        $this->nbPerPage = 6;
        
        $this->XCenter = $pdf->GetPageWidth() / 2 ;
        $this->YCenter = $pdf->GetPageHeight() / 2 ;
    }

    public function getCenter( $i, $recto ) {
        
        $m = fmod( $i, $this->nbPerPage);
        
        if( $m == 0 ) {
            $this->a->addPage();
        }

        $toTop = 20;
        $toLeft = 29;

        $lamba = .935;
        $shiftX = $this->XCenter /2 * $lamba;
        $shiftY = $this->XCenter /1.23 * $lamba;
        if( ! $recto ) {
            $toLeft = -$toLeft;
            $shiftX = -$shiftX;
        }
        if( $m == 0 ) {
            $coords["X"] = $this->XCenter - $shiftX;
            $coords["Y"] = $this->YCenter - $shiftY - $toTop;
        } else if($m == 1 ) {
            $coords["X"] = $this->XCenter + $shiftX - $toLeft;
            $coords["Y"] = $this->YCenter - $shiftY - $toTop +$shiftY/2;
        } else if($m == 2 ){
            $coords["X"] = $this->XCenter - $shiftX;
            $coords["Y"] = $this->YCenter  - $toTop;
        } else if($m == 3 ){
            $coords["X"] = $this->XCenter + $shiftX- $toLeft;
            $coords["Y"] = $this->YCenter  - $toTop+$shiftY/2;
        } else if($m == 4 ){
            $coords["X"] = $this->XCenter - $shiftX;
            $coords["Y"] = $this->YCenter + $shiftY - $toTop;
        } else if($m == 5 ){
            $coords["X"] = $this->XCenter + $shiftX- $toLeft;
            $coords["Y"] = $this->YCenter + $shiftY - $toTop+$shiftY/2;
        } 

        return $coords;
    }
    

    public function Entete($x, $y, $r, $acteur)
    {
        
        $image = utf8_decode($acteur->getAttribute('image'));
        $titre = utf8_decode($acteur->getAttribute('titre'));
        $bref = utf8_decode($acteur->getAttribute('bref'));

        $this->Image($x, $y, $r, $image);
        
        $this->a->SetTextColor(112, 112, 111);
        $this->a->SetFont('Steelfish', '', 19);

        $this->a->SetLeftMargin($x-$this->rayon *.60);
        $this->a->SetY($y- $this->rayon *.82);
        $this->a->MultiCell(50, 8, $titre, 0, 'C');

        $this->a->SetFont('Futura', '', 12);
        $this->a->MultiCell(50, 5, $bref, 0, 'C');

    }

    public function printActeur($x, $y, $r, $acteur)
    {
        $savedX = $this->a->GetX();
        $savedY = $this->a->GetY();

        $this->Entete($x, $y, $r, $acteur);
        
        $this->printTrgls( $x, $y, $r );
        

        $this->a->SetX($savedX);
        $this->a->SetY($savedY);
    }
    
    public function printTrgls($x, $y, $r) {

        $min = rand(1,2);
        $max = rand(2,4);
        $ns = 6;

        $nl = 1;
        $tr = $this->rayon /5;
        $ix = $tr*0.15;
        $iy = $tr*2;

        $tx = $x - $this->rayon *.82 + (3-$nl)*2;
        $ty = $y + (4-$nl)*$iy/2 *0.665;

        $this->a->SetTextColor(255, 255, 255);

        $c = range(0, 5);

        $l = -1;
        $cx = $tx + $l*$ix;
        $cy = $ty + $l*$iy;
        $this->a->RegularPolygon( $cx, $cy, $tr, $ns, 90, 'F', null, $this->a->color($c[2]) );
        
        $this->a->SetY($cy - $iy + 2);
        $this->a->SetX($cx - $ix - 1);
        $this->a->SetFont('Steelfish', '', 25);
        $val = rand( $min , $max);

        $this->a->Cell(10, 30, $val, 0, 1);

        $ty = $y + 0 * $iy / 2;
        $tx = $x + $this->rayon *.75 - 0;

        $this->a->SetTextColor(0, 0, 0);

        $l=0;
            $cx = $tx - $l*$ix;
            $cy = $ty + $l*$iy;
            $this->a->RegularPolygon( $cx, $cy, $tr, $ns, 90, 'F', null, $this->a->color($c[$l + $nl]) );
            $this->a->SetY($cy - $iy + 2);
            $this->a->SetX($cx -  $ix - 1);
            $this->a->SetFont('Steelfish', '', 25);

            $val = rand( $min * 2, $max *2);
            $this->a->Cell(10, 30, $val, 0, 1);
        

    }
    
    public function printSlogan( $c ) {
        
        $this->a->SetTextColor(112, 112, 111);

        $this->a->SetFont('Steelfish', '', 40);
        $this->a->SetLeftMargin($c["X"]-$this->rayon *.60);
        $this->a->SetY($c["Y"]- $this->rayon *.85);
        $this->a->Cell(50, 10, "Le Florain", 0, 0, 'C');
        
        $this->a->SetFont('FreeScript', '', 35);

        $this->a->SetLeftMargin($c["X"]-$this->rayon *.53);
        $this->a->SetY($c["Y"] + $this->rayon *.47);
        $this->a->Cell(50, 10, "La monnaie qui", 0, 0, 'C');

        $this->a->SetLeftMargin($c["X"]-$this->rayon *.55);
        $this->a->SetY($c["Y"] + $this->rayon *.65);
        $this->a->Cell(50, 10, utf8_decode("crée du lien!"), 0, 0, 'C');
    }
}

class EmptyPolygonCard
{
    protected $a;
    protected $slogan;
    protected $template = null;
    protected $recto = false;


    public function __construct($pdf , $slogan, $template )
    {
        $this->a = $pdf;
        $this->slogan = $slogan;
        $this->template = $template;
    }

    public function display( $col ) {

        $c = $this->printPolygon( $col, "F", 4 );
        $this->a->Image('images/FlorainFA5-vert.jpg',
            $c["X"] - $this->template->rayon*.8*.5, $c["Y"]- $this->template->rayon*1.3*.5, $this->template->rayon*.8, $this->template->rayon*1.3
        );

        $this->template->printSlogan( $c );
    }

    public function printPolygon( $col, $style="", $shift = 0 ) {


        $c = $this->template->getCenter( $col, $this->recto );
        
        // adjustments according printer margins
        $c['X'] = $c['X']-1;
        $c['Y'] = $c['Y']+.1;

        $ns = 6;

        $this->a->RegularPolygon( $c["X"], $c["Y"], $this->template->rayon + $shift, $ns, $this->template->angle, $style, null, [204, 220, 62] );

        return $c;
    }
}

class PolygonCard extends EmptyPolygonCard
{
    protected $acteur;
    
    public function __construct($pdf, $acteur, $template)
    {
        $this->a = $pdf;
        $this->acteur = $acteur;
        $this->template = $template;
        $this->recto = true;

    }

    public function display( $col ) {
        $c = $this->printPolygon( $col );
        $this->template->printActeur( $c["X"], $c["Y"], $this->template->rayon, $this->acteur );

    }
}

class Utils {
    
    public function __construct($pdf, $xml)
    {
        $this->a = $pdf;
        $this->xml = $xml;
        $this->rr = new RoundedRect();

    }
    function rect( $x, $y, $w, $h, $shift = 0, $r = 2, $f = '', $c='1234' ) {
        $this->rr->_Rect($this->a, $x+ $shift, $y+ $shift, $w- $shift*2, $h- $shift*2, $r, $c, $f);
    }
}

class Plateau extends utils {

    protected $marge = 5;
    
    public function display() {

        $nbx = 10;
        $cote = ($this->a->GetPageWidth() - $this->marge*2) / $nbx;
        $nby = ceil(($this->a->GetPageHeight() - $this->marge*2) / $cote);

        $Cx = $this->marge;
        $Cy = $this->marge;
        for( $y=0; $y<$nby-1; $y++ ) {
            $this->rect( $Cx, $Cy, $cote, $cote, .2, 1 );
            $Cy+=$cote;
        }

        $Cx = $this->marge + ($nbx-1)*$cote;
        $Cy = $this->marge;
        for( $y=0; $y<$nby-1; $y++ ) {
            $this->rect( $Cx, $Cy, $cote, $cote, .2, 1 );
            $Cy+=$cote;
        }
        
        $Cx = $this->marge + $cote;
        $Cy = $this->marge;
        for( $x=1; $x<$nbx-1; $x++ ) {
            $this->rect( $Cx, $Cy, $cote, $cote, .2, 1 );
            $Cx+=$cote;
        }
        
        $Cx = $this->marge + $cote;
        $Cy = $this->marge + ($nby-2)*$cote;
        for( $x=1; $x<$nbx-1; $x++ ) {
            $this->rect( $Cx, $Cy, $cote, $cote, .2, 1);
            $Cx+=$cote;
        }
    }

}

class ValuesBoard extends utils {
    
    protected $x = 0;
    protected $y = 0;

    public function __construct($pdf, $xml)
    {
        $this->a = $pdf;
        $this->xml = $xml;
        $this->rr = new RoundedRect();
    }

    public function display() {
        $competences = $this->xml->getElementsByTagName('competence');
        $nb_competences = $competences->length;
        $this->width = $this->a->GetPageWidth() / $nb_competences;
        $this->height = $this->a->GetPageHeight() / 2;
        for ($c = 0; $c < $nb_competences; ++$c) {
            $competence = $competences[$c];
            $this->printRecto( $competence, $this->width, 3 );
        }

        $this->x = 0;
        $this->y = $this->height;
        $indicateurs = $this->xml->getElementsByTagName('indicateur');
        $totCol = (int)$indicateurs[0]->parentNode->getAttribute( "totCol");
        $nb_indicateurs = $indicateurs->length;
        $this->height = $this->a->GetPageHeight() / 2;
        for ($c = 0; $c < $nb_indicateurs; ++$c) {
            $indicateur = $indicateurs[$c];
            $nb = (int)$indicateur->getAttribute( "nb");
            $width = $this->a->GetPageWidth() / $totCol * $nb;
            $this->printRecto( $indicateur, $width, 3 );
        }

        // verso
        $this->a->addPage();
        $this->x = 0;
        $this->y = 0;
        $this->width = $this->a->GetPageWidth() / $nb_competences;
        $this->height = $this->a->GetPageHeight() / 2;
        for ($c = $nb_competences-1; $c>=0; --$c) {
            $competence = $competences[$c];
            $this->printVerso( $competence, $this->width );
        }
        $this->x = 0;
        $this->y = $this->height;
        $this->height = $this->a->GetPageHeight() / 2;
        for ($c = $nb_indicateurs-1; $c>=0; --$c) {
            $indicateur = $indicateurs[$c];
            $nb = (int)$indicateur->getAttribute( "nb");
            $width = $this->a->GetPageWidth() / $totCol * $nb;
            $this->printVerso( $indicateur, $width );
        }
    }

    public function setFillColor( $c ) {
        
        $r = $c->parentNode->getAttribute( "r" );
        $g = $c->parentNode->getAttribute( "g" );
        $b = $c->parentNode->getAttribute( "b" );
        if( $c->hasAttribute( "r") ) {
            $r = $c->getAttribute( "r" );
            $g = $c->getAttribute( "g" );
            $b = $c->getAttribute( "b" );

        }
        $this->a->SetFillColor( $r, $g, $b );
    }

    
    public function printVerso( $c, $width ) {

        $this->setFillColor( $c );
        $this->rect( $this->x, $this->y, $width, $this->height, 3, 2, '' );
        $this->rect( $this->x, $this->y, $width, $this->height, 4, 2, 'F' );

        
        $this->a->SetTextColor(112, 112, 111);
        $this->a->SetFont('Steelfish', '', 35);
        $titre = utf8_decode( $c->getAttribute( "nom" ) );
        $len = $this->a->GetStringWidth( $titre );
        $this->a->RotatedText( $this->x + $width/2 - 3,  $this->y + $this->height/2 - $len/2, $titre, -90);


        $this->x = $this->x + $width;
    }

    public function printRecto( $c, $width, $shift ) {
        $nbCol = 2;
        if( $c->hasAttribute("nb") ) {
            $nbCol = (int) $c->getAttribute("nb");
        }

        $this->rect( $this->x, $this->y, $width, $this->height, $shift );

        $this->setFillColor( $c );
        $this->rect( $this->x, $this->y, $width, 25, 4, 2, "F" );

        
        $this->rect( $this->x, $this->y + 20, $width, $this->height - 20, 6, 0, "F" );
        $stepx = ($width-12) / $nbCol;
        $stepy = ($this->height - 20 - 12) / 10;
        
        $this->fillValues( $nbCol, $this->x, $this->y, $stepx, $stepy );

        $nom = $c->getAttribute("nom");
        $this->a->setY( $this->y + 7 );
        $this->a->setX( $this->x + $width/2 );

        $this->a->SetTextColor(112, 112, 111);
        $this->a->SetFont('Steelfish', '', 23);
        $this->a->Cell(1, 10, utf8_decode($nom), 0, 0, 'C');

        $this->x = $this->x + $width;
    }

    public function fillValues( $nbCol, $x, $y, $stepx, $stepy, $shift=6, $split = false ) {
        $this->a->SetFillColor( 255,255, 255 );
        $this->a->SetTextColor(112, 112, 111);
        $this->a->SetFont('Futura', 'B', 15);
        for( $xx =0 ; $xx < $nbCol; $xx++ ){
            for( $yy =0 ; $yy < 10; $yy++ ){
                $this->a->SetFillColor( 255,255, 255 );
                $this->rect( $x +$shift+ $xx*$stepx, $y +$shift+ 20+$yy*$stepy, $stepx, $stepy, 1, 0, "F" );
                if( $split ) {
                    $p = null;
                    $p[] = $x +$shift+ $xx*$stepx + $stepx;
                    $p[] = $y +$shift+ 20+$yy*$stepy + $stepy;
                    $p[] = $x +$shift+ $xx*$stepx + $stepx;
                    $p[] = $y +$shift+ 20+$yy*$stepy;
                    $p[] = $x +$shift+ $xx*$stepx;
                    $p[] = $y +$shift+ 20+$yy*$stepy + $stepy;
                    $this->a->StyledPolygon($p, "F", null, [200,200, 217]);
                }
                $this->a->setY( $y +$shift+ 21+$yy*$stepy );
                $this->a->setX( $x +$shift+ $xx*$stepx );
                $this->a->Cell(10, 10, 9-$yy, 0, 0, 'C');
            }
        }
    }
}

class ValuesComparisonBoard extends ValuesBoard {
    
    protected $x = 0;
    protected $y = 0;

    public function __construct($pdf, $xml)
    {
        $this->a = $pdf;
        $this->xml = $xml;
        $this->rr = new RoundedRect();
    }
    
    public function display() {

        $top = 40;
        $resources = $this->xml->getElementsByTagName('resource');
        $nb_resources = $resources->length;
        $this->width = $this->a->GetPageWidth() / $nb_resources;
        $this->height = $this->a->GetPageHeight() - $top;

        // title
        $titre = utf8_decode( $resources[0]->parentNode->getAttribute( "titre" ) );
        $this->rect( $this->x, $this->y, $this->a->GetPageWidth(), $top, 3 );
        $this->setFillColor( $resources[0] );
        $this->rect( $this->x, $this->y, $this->a->GetPageWidth(), $top, 4, 2, "F" );

        $x = $this->x +$this->a->GetPageWidth()*.446;
        $y = $this->y +$top/4;
        $stepx = 70;
        $stepy = $top/2;
        $this->a->SetFillColor( 255,255, 255 );
        $this->rect( $x, $y, $stepx, $stepy, 1, 0, "F" );
        $p = null;
        $p[] = $x + $stepx;
        $p[] = $y + $stepy;
        $p[] = $x + $stepx;
        $p[] = $y;
        $p[] = $x;
        $p[] = $y + $stepy;
        $this->a->StyledPolygon($p, "F", null, [200,200, 217]);

        $this->a->SetTextColor(112, 112, 111);
        $this->a->SetFont('Steelfish', '', 35);
        $this->a->setXY(  $this->a->GetPageWidth()/2 ,  $top/2 );
        $this->a->Cell(1, 3, $titre, 0, 0, 'C');

        
        $this->y = $top;
        $this->x = 0;

        // recto
        for ($c = 0; $c < $nb_resources; ++$c) {
            $resource = $resources[$c];
            $this->printRecto( $resource, $this->width, 2 );
        }

        // verso

        $this->a->addPage('L');
        $this->rect( 0, 0, $this->a->GetPageWidth(), $top, 3 ); 
        $this->setFillColor( $resources[0] );
        $this->rect( 0, 0, $this->a->GetPageWidth(), $top, 4, 2, "F" );
        
        $this->a->SetTextColor(112, 112, 111);
        $this->a->SetFont('Steelfish', '', 35);
        $this->a->setXY(  $this->a->GetPageWidth()/2 ,  $top/2 );
        $this->a->Cell(1, 3, $titre, 0, 0, 'C');


        $this->x = 0;
        $this->y = $top;
        for ($c = $nb_resources-1; $c>=0; --$c) {
            $resource = $resources[$c];
            $this->printVerso( $resource, $this->width );
        }
    }
    
    public function printRecto( $c, $width, $shift ) {
        $nbCol = 3;

        $this->rect( $this->x, $this->y, $width, $this->height, $shift );

        $this->setFillColor( $c );
        $this->rect( $this->x, $this->y, $width, 25, $shift+2, 2, "F" );

        
        $this->rect( $this->x, $this->y + 20, $width, $this->height - 20, $shift+2, 0, "F" );
        $stepx = ($width-$shift*4) / $nbCol;
        $stepy = ($this->height - 20 -$shift*4) / 10;
        
        $this->fillValues( $nbCol, $this->x, $this->y, $stepx, $stepy, $shift*2, true );

        $nom = $c->getAttribute("nom");
        $this->a->setY( $this->y + 7 );
        $this->a->setX( $this->x + $width/2 );

        $this->a->SetTextColor(112, 112, 111);
        $this->a->SetFont('Steelfish', '', 23);
        $this->a->Cell(1, 10, utf8_decode($nom), 0, 0, 'C');

        $this->x = $this->x + $width;
    }
}


class Regles extends Utils {
   
    protected $width = 30;
    protected $pageNum = 0;
    protected $leftMargin = 15;
    protected $topMargin = 10;

    public function __construct($pdf, $xml)
    {
        $this->a = $pdf;
        $this->xml = $xml;
        $this->rr = new RoundedRect();
        $this->margins = 20;
        $this->width = $this->a->GetPageWidth() / 2 - $this->leftMargin*2;
        $this->a->SetY( $this->topMargin );
        $this->pageNum = 0;

    }

    public function display() {
        $this->a->SetLeftMargin( $this->leftMargin );
        $this->a->SetY( $this->topMargin );
        
        $regles = $this->xml->getElementsByTagName('regle');
        $parent = $regles[0]->parentNode;
        
        $this->a->SetTextColor(112, 112, 111);
        $this->a->SetFont('Steelfish', '', 25);
        $this->a->MultiCell($this->width, 10, "Regles du jeu", 0, 'L');
        $this->a->Ln(5);
        
        if( $parent->hasAttribute( "r") ) {
            $r = $parent->getAttribute( "r" );
            $g = $parent->getAttribute( "g" );
            $b = $parent->getAttribute( "b" );
            $this->a->SetFillColor( $r, $g, $b );
        }
        $nb_regles = $regles->length;
        for ($r = 0; $r < $nb_regles; ++$r) {
            $regle = $regles[$r];
            $this->printAction( $regle );
        }
        
        if( $this->a->getY() > $this->a->GetPageHeight() * .75 ) {
            $this->addPage( 'L');
        }
        $this->a->SetTextColor(112, 112, 111);
        $this->a->SetFont('Steelfish', '', 25);
        $this->a->MultiCell($this->width, 10, "Les actions", 0, 'L');
        $this->a->Ln(5);

        $actions = $this->xml->getElementsByTagName('action');
        $parent = $actions[0]->parentNode;
        
        if( $parent->hasAttribute( "r") ) {
            $r = $parent->getAttribute( "r" );
            $g = $parent->getAttribute( "g" );
            $b = $parent->getAttribute( "b" );
            $this->a->SetFillColor( $r, $g, $b );
        }
        $nb_actions = $actions->length;
        for ($r = 0; $r < $nb_actions; ++$r) {
            $action = $actions[$r];
            $this->printAction( $action );
        }
    }

    public function addPage() {
        $this->pageNum = $this->pageNum + 1;
        if( fmod( $this->pageNum, 2 ) == 0 ) {
          $this->a->addPage('L');
          $this->a->SetLeftMargin( $this->leftMargin );
        } else {
          $this->a->SetLeftMargin( $this->leftMargin + $this->a->GetPageWidth()/2 );
        }
        $this->a->SetY( $this->topMargin );
    }

    public function split( $text, $available ) {
        $words = preg_split("/\n/", $text);

        $cur="";
        $res = array();
        $res['top'] = "";
        $res['rest'] = "";
        foreach ($words as $word) {
            $cur .= $word."\n";
            $h = $this->a->MultiCellHeight($this->width, 5, utf8_decode($cur));
            if( $h < $available ) {
                $res['top'] .= $word."\n";
            } else{
                $res['rest'] .=  $word."\n";
            }
        }

        return $res;
    }

    public function fullPrint( $titre, $text, $x, $y, $w ) {
        $this->rect( $x, $y, $w, 10, 0, 2, 'F', '12');
        $this->rect( $x, $y, $w, 10, 0, 2, '', '12');

        $this->a->SetTextColor(112, 112, 111);
        $this->a->SetFont('Steelfish', '', 23);
        $this->a->MultiCell($this->width, 10, utf8_decode($titre), 0, 'C');
        
        $this->a->SetTextColor(112, 112, 111);
        $this->a->SetFont('Futura', '', 12);

        $h = $this->a->MultiCellHeight($this->width, 5, utf8_decode($text));
        $this->rect( $x, $y+10, $w, $h, 0, 2, '', '34');
        $this->a->MultiCell($this->width, 5, utf8_decode($text), 0, 'L');
    }
    
    public function printTop( $titre, $text, $available, $x, $y, $w ) {
        $this->rect( $x, $y, $w, 10, 0, 2, 'F', '12');
        $this->rect( $x, $y, $w, 10, 0, 2, '', '12');

        $this->a->SetTextColor(112, 112, 111);
        $this->a->SetFont('Steelfish', '', 23);
        $this->a->MultiCell($this->width, 10, utf8_decode($titre), 0, 'C');
        
        $split = $this->split( $text, $available );

        $this->a->SetTextColor(112, 112, 111);
        $this->a->SetFont('Futura', '', 12);

        //$h = $this->a->MultiCellHeight($this->width, 5, utf8_decode($split['top']));

        $this->rect( $x, $y+10, $w, $available, 0, 2, '', '');
        $this->a->MultiCell($this->width, 5, utf8_decode($split['top']), 0, 'L');

        return $split['rest'];
    }
    
    public function printRest( $text, $x, $y, $w ) {
        $intMargin = 3;
        $this->a->ln($intMargin);
        $this->a->SetTextColor(112, 112, 111);
        $this->a->SetFont('Futura', '', 12);

        $h = $this->a->MultiCellHeight($this->width, 5, utf8_decode($text)) + $intMargin;
        $this->rect( $x, $y, $w, $h, 0, 2, '', '34');
        $this->a->MultiCell($this->width, 5, utf8_decode($text), 0, 'L');
    }

    public function printAction($regle) {

        $titre = $regle->getAttribute( "titre" );
        $text = $regle->nodeValue;

        $h = $this->a->MultiCellHeight($this->width, 5, utf8_decode($text));
        $h += $this->a->MultiCellHeight($this->width, 10, utf8_decode($titre));

        $x = $this->a->GetX()-10;
        $y = $this->a->GetY()-.5;
        $w = $this->width+20;

        $fullHeight =  $h;

        if( $this->a->GetY() > $this->a->GetPageHeight() - $fullHeight ) {
            // first see if there is place for the beginning
            $available = $this->a->GetPageHeight() - $this->a->GetY() - $this->topMargin*2;
            $minHeight =  + 10 + 5 + 5;
            if( $available > $minHeight ) {
                // display the top
                $rest = $this->printTop( $titre, $text, $available, $x, $y, $w );
                $this->addPage();
                $x = $this->a->GetX()-10;
                $y = $this->a->GetY()-.5;
                $w = $this->width+20;
                $this->printRest( $rest, $x, $y, $w );
            } else {
                $this->addPage();
                $x = $this->a->GetX()-10;
                $y = $this->a->GetY()-.5;
                $w = $this->width+20;
                $this->fullPrint( $titre, $text, $x, $y, $w);
            }
        } else {
           $this->fullPrint( $titre, $text, $x, $y, $w);
        }

        $this->a->Ln(5);
    }

    public function printRegle($regle) {
        
        $titre = $regle->getAttribute( "titre" );
        $text = $regle->nodeValue;

        $this->a->SetTextColor(112, 112, 111);
        $this->a->SetFont('Steelfish', '', 23);
        $this->a->MultiCell($this->width, 10, utf8_decode($titre), 0, 'C');
        
        $this->a->SetTextColor(112, 112, 111);
        $this->a->SetFont('Futura', '', 12);
        $this->a->MultiCell($this->width, 5, utf8_decode($text), 0, 'L');

        $this->a->Ln(5);

        if( $this->a->GetY() > $this->a->GetPageHeight() - 30 ) {
            $this->pageNum = $this->pageNum + 1;
            if( fmod( $this->pageNum, 2 ) == 0 ) {
              $this->a->addPage('L');
              $this->a->SetLeftMargin( $this->leftMargin );
            } else {
              $this->a->SetLeftMargin( $this->leftMargin + $this->a->GetPageWidth()/2 );
            }
            $this->a->SetY( $this->topMargin );
        }

    }
}