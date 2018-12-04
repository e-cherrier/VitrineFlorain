<?php
require('souscategorie.php');


class Categorie {

protected $a;
protected $cat_;

public $titleCellHeight = 6;
public $titleCellBotMargin = 4;

function __construct( $pdf, $cat ) {
    $this->a = $pdf;
    $this->cat_ = $cat;
}

function title_height() {
    return $this->titleCellHeight + $this->titleCellBotMargin;
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

function type() {
    if( ! $this->cat_->hasAttribute( $this->typeAttribute() ) ) {
        return "";
    }
    return $this->cat_->getAttribute( $this->typeAttribute() );
}

function typeAttribute() {
    return "type";
}

}

class CategorieFiches extends Categorie {

    function NewSousCategorie( $a, $sscat ) {
        return new SousCategorieFiches( $a, $this, $sscat );
    }
    
    function display() {
    
        $sscategories= $this->cat_->getElementsByTagName( "scat" );
        $nb_sscat = $sscategories->length;
    
        for($sscat=0; $sscat<$nb_sscat; $sscat++) {
            $sscategorie = $sscategories[$sscat];
    
            $mySsCat = $this->NewSousCategorie( $this->a, $sscategorie );
            $mySsCat->display();
        }
    }
    
}

class CategorieLivret extends Categorie {

function NewSousCategorie( $a, $sscat ) {
    return new SousCategorieLivret( $a, $this, $sscat );
}

function displayType( $suite=false )
{
    if( ! $this->cat_->hasAttribute( $this->typeAttribute() ) ) {
        return "none";
    }
    $type = utf8_decode( $this->cat_->getAttribute( $this->typeAttribute() ) );

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
    $this->a->Cell($this->a->GetColumnWidth()*2+$this->a->colMargin,$this->titleCellHeight,"$label",'B',1,'L',true);
    $this->a->Ln($this->titleCellBotMargin);

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

				$mySsCat = $this->NewSousCategorie( $this->a, $sscategorie );
				$toc[$sscat] = $mySsCat->display();
    }

    return $toc;
}

}

class CategoriePoche extends Categorie {

public $titleCellHeight = 5;
public $titleCellBotMargin = 2;

function NewSousCategorie( $a, $sscat ) {
    return new SousCategoriePoche( $a, $this, $sscat );
}

function displayType( $suite=false )
{
    if( ! $this->cat_->hasAttribute( $this->typeAttribute() ) ) {
        return "none";
    }
    $type = utf8_decode( $this->cat_->getAttribute( $this->typeAttribute() ) );

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
    $this->a->Cell($this->a->GetColumnWidth()*2+$this->a->colMargin,$this->titleCellHeight,"$label",'B',1,'L',true);
    $this->a->Ln($this->titleCellBotMargin);

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
				$mySsCat = $this->NewSousCategorie( $this->a, $sscategorie );
				$mySsCat->display();
    }
}

}
