<?php
require_once('souscategorie.php');

class Exposant extends Acteur {
protected $is_comptoir_;

function setIsComptoir( $is_comptoir ) {
    $this->is_comptoir_ = $is_comptoir;
}

function isComptoir() {

    if( $this->is_comptoir_ and $this->acteur_->hasAttribute( "comptoir" ) ) {
        $c = $this->acteur_->getAttribute( "comptoir" );
      	if( $c == "oui" ) {
            return true;
      	}
    }
    return false;
}

}


class ExposantPoche extends Exposant {

function EnteteHeight() {
    $h = $this->getAttributeHeight( "titre", 10, 'B' );
    //$h = $h + $this->getAttributeHeight( "bref",8 );
    return $h;
}

function height() {
    return $this->EnteteHeight() ;
}

function Entete() {
    $left_entete = $this->a->GetX();
    $top_entete = $this->a->GetY();

    if( $this->isComptoir() ) {
        $this->a->SetFillColor(234,250,180);
				$this->a->Rect(
					$this->a->GetX()-1,
					$this->a->GetY()-1,
					$this->a->GetColumnWidth(),
					$this->height()+2, "F"
				);
    }

    $titre = utf8_decode( $this->acteur_->getAttribute( "titre" ) );

    $this->a->SetY( $top_entete );
    $this->a->SetX( $left_entete);
    $this->a->SetLeftMargin($left_entete);

    // Le nom
    $this->a->PrintName( $titre,$this->a->GetColumnWidth(), 10 );

    /*
    if( $this->acteur_->hasAttribute( "bref" ) ) {
        $bref = utf8_decode( $this->acteur_->getAttribute( "bref" ) );
				if( $bref != "" ) {
            $this->a->PrintText( $bref, $this->a->GetColumnWidth(), 8 );
				}
    }
    */

    $this->a->Ln( 2 );

    // reset position
    $this->a->SetLeftMargin($left_entete);
    $this->a->SetX( $left_entete );
    $cury = $this->a->GetY();
    $y = $top_entete + $this->EnteteHeight();
    // get the y max
    if( $cury < $y ) {
        //$this->a->SetY( $top_entete + $this->EnteteHeight() );
		    $this->a->debug("Curry : " . $cury . " ; y " . $y);
    }
}

function display( $col, $deb_i ) {
    $this->a->SetCol( $col );

    $this->separator( $col, $deb_i );

    $this->Entete();
    $this->acteur_->setAttribute( "displayed", "true" );

    if( $col == 0 ) {
	      $this->a->bas_col0 = $this->a->GetY();
    } else {
	      $this->a->bas_col1 = $this->a->GetY();
    }
}
}


/**************************************************************************/

class ExposantLivret extends Exposant {

function height() {

	    $h = $this->getAttributeHeight( "titre", 14, 'B' );
	    $h = $h + $this->getAttributeHeight( "bref" );

	    return $h;
}

function display( $col, $deb_i ) {
    $this->a->SetCol( $col );
    $this->separator( $col, $deb_i );

    $titre = utf8_decode( $this->acteur_->getAttribute( "titre" ) );
    $bref = utf8_decode( $this->acteur_->getAttribute( "bref" ) );

    $this->a->PrintName( $titre, 90, 14 );
    $this->a->PrintText( $bref, 90 );
    $this->a->Ln();

    $this->acteur_->setAttribute( "displayed", "true" );

    if( $col == 0 ) {
        $this->a->bas_col0 = $this->a->GetY();
    } else {
        $this->a->bas_col1 = $this->a->GetY();
    }
}
}

class Marche extends SousCategorie {

protected $exposants = array();

function __construct( $a, $cat, $scat ) {

	  $this->a = $a;
		$this->cat_ = $cat;
		$this->scat_ = $scat;
    $id = utf8_decode( $this->scat_->getAttribute( "id" ) );
    $acteurs = $a->doc->getElementsByTagName( "acteur" );
    $nb_a = $acteurs->length;

    $idx=0;
    for($a=0; $a<$nb_a; $a++) {
        $acteur = $acteurs[$a];
		    if( ! $acteur->hasAttribute( "marche" ) ) {
			     continue;
		    }
		    $marches = utf8_decode( $acteur->getAttribute( "marche" ) );
		    $ids = preg_split("/[\s,]+/", $marches);
				$nb_ids = count( $ids );

				for( $i=0; $i<$nb_ids; $i++ ) {
						if( $ids[$i] == $id ) {
						    $this->exposants[$idx] = $acteur;
								$idx=$idx+1;
								// remove displayed attribute
								$acteur->removeAttribute( "displayed" );
						}
				}
		}
}

function get_elements() {
    return $this->exposants;
}

function get_elements_count() {
    return count( $this->exposants );
}

function typeAttribute() {
    return "titre";
}

}

class MarcheLivret extends Marche {

function NewActeur(  $annuaire, $acteur ) {
    $expo = new ExposantLivret( $annuaire, $acteur );
    $expo->setIsComptoir( $this->scat_->hasAttribute( "message_comptoir" ));
    return $expo;
}


function displayType( $suite=false )
{
    if( ! $this->scat_->hasAttribute( $this->typeAttribute() ) ) {
        return "none";
    }
    $type = utf8_decode( $this->scat_->getAttribute( $this->typeAttribute() ) );

    $this->a->SetCol(0);
    // Title
    $label = "    " . $type;
    if( $suite == true ) {
        $label = $label . "  (suite)";
    }

    $y1 = $this->a->GetY();
    $this->a->SetFont('Futura','B',18);
    //$this->a->SetFillColor(204,220,62);
    $this->a->SetTextColor(0,0,0);
    $this->a->Cell($this->a->GetColumnWidth()*2+$this->a->colMargin,6,"$label",'B',1,'L',false);
    $this->a->Ln(4);

    // Save ordinate
    $this->a->top_col = $this->a->GetY();
    $this->a->bas_col0 = $this->a->GetY();
    $this->a->bas_col1 = $this->a->GetY();
    $this->a->SetTextColor(0,0,0);

    return $type;
}

}

class MarchePoche extends Marche {

    public $titleCellHeight = 5;
    public $titleCellBotMargin = 2;
    
function NewActeur( $annuaire, $acteur ) {
    $expo = new ExposantPoche( $annuaire, $acteur );
    $expo->setIsComptoir( $this->scat_->hasAttribute( "message_comptoir" ));
    return $expo;
}

function displayType( $suite=false )
{
    if( ! $this->scat_->hasAttribute( $this->typeAttribute() ) ) {
        return "none";
    }

    $type = utf8_decode( $this->scat_->getAttribute( $this->typeAttribute() ) );

    // Title
    $label = "    " . $type;
    if( $suite == true ) {
        $label = $label . "  (suite)";
    }
    $this->a->SetFont('Futura','I',10);
    $this->a->SetFont('Steelfish','',12);
    $this->a->SetTextColor(0,0,0);
    $this->a->Cell($this->a->GetColumnWidth()*2+$this->a->colMargin,$this->titleCellHeight,"$label",'B',1,'L',false);
    $this->a->Ln($this->titleCellBotMargin);

    // Save ordinate
    $this->a->top_col = $this->a->GetY();
    $this->a->bas_col0 = $this->a->GetY();
    $this->a->bas_col1 = $this->a->GetY();

    return $type;
}

}

///////////////////////////////////////////////////////////////////////////////

class CategorieMarcheLivret extends CategorieLivret {

	function typeAttribute() {
	    return "type";
	}
	function NewSousCategorie( $a, $sscat ) {
	    return new MarcheLivret( $a, $this, $sscat );
	}
}

class CategorieMarchePoche extends CategoriePoche {


		function typeAttribute() {
				return "type";
		}
		function NewSousCategorie( $a, $sscat ) {
		    return new MarchePoche( $a, $this, $sscat );
		}
}
