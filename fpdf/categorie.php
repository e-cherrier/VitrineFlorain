<?php
require('souscategorie.php');


class Categorie {
	
protected $a;
protected $cat_;

function __construct( $pdf, $cat ) {
    $this->a = $pdf;
    $this->cat_ = $cat;
}

function title_height() {
    return 10; //  -> cell height = 6 + ln(4)
}

function needNewPage( $offset=0 ) {

    $sscategories= $this->cat_->getElementsByTagName( "scat" );
    $nb_sscat = $sscategories->length;
    if( $nb_sscat == 0 ) {
        return false;
    }

    $sscategorie = $sscategories[0];

    $mySsCat = $this->NewSousCategorie( $this->a, $sscategorie );

    return $mySsCat->needNewPage( $offset + $this->title_height() );
}


}

class CategorieLivret extends Categorie {

function NewSousCategorie( $a, $sscat ) {
    return new SousCategorieLivret( $a, $this, $sscat );
}

function displayType( $suite=false )
{
    if( ! $this->cat_->hasAttribute( "type" ) ) {
        return "none";
    }
    $type = utf8_decode( $this->cat_->getAttribute( "type" ) );

    //$this->a->resetColumn();  //MBN ICI
    $this->a->SetCol(0);
    // Title
    $label = "    " . $type;
    if( $suite == true ) {
        $label = $label . "  (suite)";
    }
    
    $y1 = $this->a->GetY();
    $this->a->SetFont('Futura','B',18);
    $this->a->SetFillColor(204,220,62);
    $this->a->SetTextColor(112,112,111);
    $this->a->Cell($this->a->GetColumnWidth()*2+$this->a->colMargin,6,"$label",'B',1,'L',true);
    $this->a->Ln(4);

    // Save ordinate
    $this->a->top_col = $this->a->GetY();
    $this->a->bas_col0 = $this->a->GetY();
    $this->a->bas_col1 = $this->a->GetY();
    $this->a->SetTextColor(0,0,0);

    return $type;
}

function display() {
    $toc = array();
    $this->a->resetColumn();
    if( $this->needNewPage() ) {
	$this->a->addPage();
    }

    $toc['type'] = $this->displayType();
    $toc['page'] = $this->a->PageNo()-1;

    $sscategories= $this->cat_->getElementsByTagName( "scat" );
    $nb_sscat = $sscategories->length;

    for($sscat=0; $sscat<$nb_sscat; $sscat++) {
        $sscategorie = $sscategories[$sscat];

	$mySsCat = new SousCategorieLivret( $this->a, $this, $sscategorie );
	$toc[$sscat] = $mySsCat->display();
    }

    return $toc;
}

}

class CategoriePoche extends Categorie {

function NewSousCategorie( $a, $sscat ) {
    return new SousCategoriePoche( $a, $this, $sscat );
}

function displayType( $suite=false )
{
    if( ! $this->cat_->hasAttribute( "type" ) ) {
        return "none";
    }
    $type = utf8_decode( $this->cat_->getAttribute( "type" ) );

    //$this->a->resetColumn();  //MBN ICI
    $this->a->SetCol(0);
    // Title
    $label = "    " . $type;
    if( $suite == true ) {
        $label = $label . "  (suite)";
    }
    
    $y1 = $this->a->GetY();
    $this->a->SetFont('Futura','B',11);
    $this->a->SetFont('Steelfish','',14);
    $this->a->SetFillColor(204,220,62);
    $this->a->SetTextColor(112,112,111);
    $this->a->Cell($this->a->GetColumnWidth()*2+$this->a->colMargin,4,"$label",'B',1,'L',true);
    $this->a->Ln(2);

    // Save ordinate
    $this->a->top_col = $this->a->GetY();
    $this->a->bas_col0 = $this->a->GetY();
    $this->a->bas_col1 = $this->a->GetY();
    $this->a->SetTextColor(0,0,0);

    return $type;
}

function display() {
    $this->a->resetColumn();
    if( $this->needNewPage() ) {
	$this->a->AddSubPage();
    }

    $this->displayType();

    $sscategories= $this->cat_->getElementsByTagName( "scat" );
    $nb_sscat = $sscategories->length;

    for($sscat=0; $sscat<$nb_sscat; $sscat++) {
        $sscategorie = $sscategories[$sscat];
	$mySsCat = new SousCategoriePoche( $this->a, $this, $sscategorie );
	$mySsCat->display();
    }
}

}

