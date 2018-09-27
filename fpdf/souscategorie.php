<?php
require('acteur.php');

/**************************************************************************/

class SousCategorie {

protected $a;
protected $cat_;
protected $scat_;
public $titleCellHeight = 6;
public $titleCellBotMargin = 4;

function __construct( $pdf, $cat, $scat ) {
    $this->a = $pdf;
    $this->cat_ = $cat;
    $this->scat_ = $scat;
}

function title_height() {
    if( ! $this->scat_->hasAttribute( "type" ) ) {
        return 0;
    }
    return $this->titleCellHeight + $this->titleCellBotMargin;
}

function get_elements() {
  return $this->scat_->getElementsByTagName( "acteur" );
}

function get_elements_count() {
    return $this->scat_->getElementsByTagName( "acteur" )->length;
}

// SousCategorie
function full_height() {
    $acteurs = $this->get_elements();
    $nb = $this->get_elements_count();
    $indexes = range(0, $nb-1);
    $height = 0;
    for($pos=0; $pos<$nb; $pos++) {
        $acteur = $acteurs[$indexes[$pos]];
        if( $acteur->hasAttribute( "attente" ) ) {
            continue;
	}

	$myActeur = $this->NewActeur( $this->a, $acteur );
	$height = $height + $myActeur->height();
    }
    return $height / 2 + $this->title_height();
}



// SousCategorie
function SpyType($label)
{
    // Title
    $label = "    " . $label;
    $y1 = $this->a->GetY();
    $this->a->SetFont('Futura','BI',14);
    $w = $this->a->GetStringWidth($label)+6;
    $this->a->Cell($w,6,"$label",'B',0,'L',false);


    $coord = "(y=".$y1.",h=".$this->a->GetPageHeight();
    $this->a->Cell( 0,6,"$coord",'B',1,'L',false );

    $this->a->Ln(4);
    $y2 = $this->a->GetY();

    $dy = $y2-$y1;

    $this->a->SetY( $y1 );
    $this->a->Cell( 0,6,"$dy",'B',1,'L',false );
    $this->a->SetY( $y2 );

    // Save ordinate
    $this->a->top_col = $this->a->GetY();
    $this->a->bas_col0 = $this->a->GetY();
    $this->a->bas_col1 = $this->a->GetY();
}

function simple_swap( $pair ) {
    if( $pair[0] == null || $pair[0] == "none" ) {
        $h0 = 0;
    } else {
        $h0 = $pair[0]->height();
    }
    if( $pair[1] == null || $pair[1] == "none" ) {
        $h1 = 0;
    } else {
        $h1 = $pair[1]->height();
    }

    $maxcol = max( $this->a->bas_col0 + $h0,
                   $this->a->bas_col1 + $h1 );
    $maxswap = max( $this->a->bas_col1 + $h0,
                    $this->a->bas_col0 + $h1 );
    if( $maxcol > $maxswap ) {
        $acteur = $pair[0];
        $pair[0] = $pair[1];
        $pair[1] = $acteur;
    }
    return $pair;
}

function priority_swap( $pair ) {
    $p0=-1;
    $p1=-1;
    if( $pair[0] == null || $pair[0] == "none" ) {
        $h0 = 0;
    } else {
        $h0 = $pair[0]->height();
        $p0 = $pair[0]->priority();
    }
    if( $pair[1] == null || $pair[1] == "none" ) {
        $h1 = 0;
    } else {
        $h1 = $pair[1]->height();
        $p1 = $pair[1]->priority();
    }

    if( $h0 != 0 && $h1 != 0 ) {
        // keep priority order
        if( $p0 > $p1 ) {
            return $pair;
        } else if( $p0 < $p1 ) {
            $acteur = $pair[0];
            $pair[0] = $pair[1];
            $pair[1] = $acteur;
            return $pair;
        }
    }

    $maxcol = max( $this->a->bas_col0 + $h0,
                   $this->a->bas_col1 + $h1 );
    $maxswap = max( $this->a->bas_col1 + $h0,
                    $this->a->bas_col0 + $h1 );
    if( $maxcol > $maxswap ) {
        $acteur = $pair[0];
        $pair[0] = $pair[1];
        $pair[1] = $acteur;
    }
    return $pair;
}

// requierements: the acteur priority is at least greater than one of the pair element
function priority_insert( $offset, $pair, $p0, $p1, $h0, $h1, $acteur ) {
    // first: is there enough place ?
    $height = $acteur->height();
    if( $this->a->SpaceLeftCol0( $offset ) < $height &&
	$this->a->SpaceLeftCol1( $offset ) < $height
    ) {
	return $pair;
    }

    $p = $acteur->priority();
    //$p0 = $pair[0]->priority();
    //$p1 = $pair[1]->priority();

    // only place in col 1
    if( $p1 < $p && $this->a->SpaceLeftCol0( $offset ) < $height ) {
        $pair[1] = $acteur;
	return $pair;
    }

    // only place in col 0
    if( $p0 < $p && $this->a->SpaceLeftCol1( $offset ) < $height ) {
        $pair[0] = $acteur;
	return $pair;
    }

    // there is place in both column
    // remove the lowest priority then the smallest height
    if( $p0 < $p && $p0 < $p1 ) {
        $pair[0] = $acteur;
	return $pair;
    }
    if( $p1 < $p && $p1 < $p0 ) {
        $pair[1] = $acteur;
	return $pair;
    }

    //$h0 = $pair[0]->height();
    //$h1 = $pair[1]->height();

    if( $p0 < $p && $h0 <= $h1 ) {
        $pair[0] = $acteur;
	return $pair;
    }
    if( $p1 < $p ) {
        $pair[1] = $acteur;
    }
    return $pair;
}

function optimizePair( $offset, $pair, $acteur ) {
    $p0=-1;
    $p1=-1;

    $height = $acteur->height();
    $h0=0;
    $h1=0;
    if( $pair[0] == null || $pair[0] == "none" ) {
	    if( $this->a->SpaceLeftCol0( $offset ) > $height ) {
          $pair[0] = $acteur;
          $pair = $this->priority_swap( $pair );
		      return $pair;
	    }
    } else {
        $p0 = $pair[0]->priority();
        $h0 = $pair[0]->height();
    }
    if( $pair[1] == null || $pair[1] == "none" ) {
	    if( $this->a->SpaceLeftCol1( $offset ) > $height ) {
          $pair[1] = $acteur;
          $pair = $this->priority_swap( $pair );
	        return $pair;
	    }
    } else {
        $p1 = $pair[1]->priority();
        $h1 = $pair[1]->height();
    }

    $prioritaire = false;
    $p = $acteur->priority();
    if( $p > $p1 || $p > $p0 ) {
       $prioritaire = true;
    }
    if( $p < $p1 && $p < $p0 ) {
        return $pair;
    }

    $pair = $this->priority_insert( $offset, $pair, $p0, $p1, $h0, $h1, $acteur );
    $pair = $this->priority_swap( $pair );
    return $pair;
}

function findBestActeurPair( $offset=0 ) {
    $pair = array( "none", "none" );
    $acteurs = $this->get_elements();
    $nb = $this->get_elements_count();
    for($pos=0; $pos<$nb; $pos++) {
        $acteur = $acteurs[$pos];
        if( $acteur->hasAttribute( "attente" ) ) {
            continue;
	      }
        if( $acteur->hasAttribute( "displayed" ) ) {
            continue;
	      }
	      $myActeur = $this->NewActeur( $this->a, $acteur );

      	$pair = $this->optimizePair( $offset, $pair, $myActeur );
    }

    return $pair;
}

/*
 * Return true if we need to begin a new page before displaying the sub categorie
 *
 * The minimum height left needed is:
 * - the heigth of the ss cat title
 * - plus the max height of a pair of acteurs (one per column)
 */
function needNewPage( $offset=0 ) {

    $offset = $offset + $this->title_height();
    $pair = $this->findBestActeurPair( $offset );

    $acteurs = $this->get_elements();
    $nb = $this->get_elements_count();

    $p1 = $pair[1] == null || $pair[1] == "none";
    $p0 = $pair[0] == null || $pair[0] == "none";
    if( $nb == 1 && (( ! $p1 && $p0 )||( $p1 && !$p0) ) ) {
      	// s'il n'y a qu'un seul acteur et qu'un seul element
      	// tout est bon
      	return false;
    }

    if( $p1 || $p0 ) {
        return true;
    }
    return false;
}


// SousCategorie
function display() {
    $toc = array();
    $this->a->resetColumn();
    if( $this->needNewPage() ) {
        $this->a->NextPage();
        $this->cat_->displayType( true );
    }

    $type = $this->displayType();
    $toc['type'] = $type;
    $toc['page'] = $this->a->PageNo()-1;

    $acteurs = $this->get_elements();
    $nb = $this->get_elements_count();
    $nb_displayed = 0;
    $deb_i = 1;
    while( $nb_displayed < $nb ) {

	      $pair = $this->findBestActeurPair();

        if( $pair[0] != null && $pair[0] != "none" ) {
            $pair[0]->display( 0, $deb_i );
      	    $toc[$nb_displayed] = array();
      	    $toc[$nb_displayed]['a'] = $pair[0]->name();
      	    $toc[$nb_displayed]['p'] = $this->a->PageNo()-1;
      	    $toc[$nb_displayed]['c'] = $pair[0]->isComptoir();
      	    $nb_displayed++;
        }
        if( $pair[1] != null && $pair[1] != "none" ) {
            $pair[1]->display( 1, $deb_i );
      	    $toc[$nb_displayed] = array();
      	    $toc[$nb_displayed]['a'] = $pair[1]->name();
      	    $toc[$nb_displayed]['p'] = $this->a->PageNo()-1;
      	    $toc[$nb_displayed]['c'] = $pair[1]->isComptoir();
      	    $nb_displayed++;
        }
      	if( $nb_displayed < $nb && (
      	    $pair[0] == null || $pair[1] == null ||
      	    $pair[0] == "none" || $pair[1] == "none"
        ) ) {
      	    $this->a->SetCol(0);
      	    $this->a->NextPage();
      	    $ret = $this->displayType( true );
      	    if( $ret=="none" ) {
      	        $this->cat_->displayType( true );
      	    }
      	    $deb_i = 0;
      	}
    	$deb_i = $deb_i + 1;
    }

    return $toc;
}

function typeAttribute() {
    return "type";
}

}

/*************************************************************/

class SousCategorieFiches extends SousCategorie {

    function NewActeur( $annuaire, $acteur ) {
        $cat = $this->cat_->type();
        $sscat = $this->sousCatType();
        return new ActeurFiches( $annuaire, $acteur, $cat, $sscat );
    }

    function sousCatType()
    {
        if( ! $this->scat_->hasAttribute( $this->typeAttribute() ) ) {
            return "";
        }
        return $this->scat_->getAttribute( $this->typeAttribute() );
    }
    
    function display() {
        $acteurs = $this->get_elements();
        $nb = $this->get_elements_count();
        for( $a = 0; $a < $nb; $a++ ) {
            
            $acteur = $acteurs[$a];
            if( $acteur->hasAttribute( "attente" ) ) {
                continue;
            }
            if( $acteur->hasAttribute( "displayed" ) ) {
                continue;
            }
	        $myActeur = $this->NewActeur( $this->a, $acteur );
            $myActeur->display(0,0);
        }
    }
}

/*************************************************************/

class SousCategorieLivret extends SousCategorie {

function NewActeur(  $annuaire, $acteur ) {
    return new ActeurLivret( $annuaire, $acteur );
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
    $this->a->SetFont('Futura','I',16);
    $this->a->Cell($this->a->GetColumnWidth()*2+$this->a->colMargin,6,"$label",'B',1,'L',false);
    $this->a->Ln(4);

    // Save ordinate
    $this->a->top_col = $this->a->GetY();
    $this->a->bas_col0 = $this->a->GetY();
    $this->a->bas_col1 = $this->a->GetY();

    return $type;
}
}

/*************************************************************/

class SousCategoriePoche extends SousCategorie {

    public $titleCellHeight = 4;
    public $titleCellBotMargin = 1;

function NewActeur(  $annuaire, $acteur ) {
    return new ActeurPoche( $annuaire, $acteur );
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
    $this->a->Cell($this->a->GetColumnWidth()*2+$this->a->colMargin,$this->titleCellHeight,"$label",'B',1,'C',false);
    $this->a->Ln($this->titleCellBotMargin);

    // Save ordinate
    $this->a->top_col = $this->a->GetY();
    $this->a->bas_col0 = $this->a->GetY();
    $this->a->bas_col1 = $this->a->GetY();

    return $type;
}
}
