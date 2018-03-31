<?php

/**************************************************************************/

class Acteur {

protected $a;
public $acteur_;

function __construct( $pdf, $acteur) {
    $this->a = $pdf;
    $this->acteur_ = $acteur;
}

function name() {
    $t = "none";
    if( $this->acteur_->hasAttribute( "titre" ) ) {
        $t = utf8_decode( $this->acteur_->getAttribute( "titre" ) );
    }
    return $t;
}

function isComptoir() {
    if( $this->acteur_->hasAttribute( "comptoir" ) ) {
        $c = $this->acteur_->getAttribute( "comptoir" );
	if( $c == "oui" ) {
            return true;
	}
    }
    return false;
}

function priority() {
    if( $this->acteur_->hasAttribute( "code" ) ) {
        $c = $this->acteur_->getAttribute( "code" );
	return $c;
    }
    return 1;
}

function getAttributeHeight( $attr, $s=10, $m='', $f='Futura' ) {
    $h = 0;
    if( $this->acteur_->hasAttribute( $attr ) ) {
        $a = utf8_decode( $this->acteur_->getAttribute( $attr ) );
	if( $a == "" ) {
	    return $h;
	}
        $this->a->SetFont($f,$m,$s);
	$h = $this->a->MultiCellHeight( $this->a->GetColumnWidth(), $this->a->cellHeight, $a, 0, 'C' );
    }
    return $h;
}

function separator( $col, $deb_i ) {
    $this->a->SetCol( $col );
    if( $col == 0 ) {
        $this->a->SetY( $this->a->bas_col0 );
	if( $deb_i > 1 ) {
	    $this->a->SetDrawColor( 127 );
	    $this->a->Line( 
                $this->a->GetX()-1, $this->a->GetY()-1,
		$this->a->GetX()-1+$this->a->GetColumnWidth()+$this->a->colMargin/2, $this->a->GetY()-1
            );
	    $this->a->Ln(2);
	    $this->a->SetDrawColor( 0 );
	}
    } else {
        $this->a->SetY( $this->a->bas_col1 );
	if( $deb_i > 1 ) {
	    $this->a->SetDrawColor( 127 );
	    $this->a->Line(
                $this->a->GetX()-1, $this->a->GetY()-1,
		$this->a->GetX()-1+$this->a->GetColumnWidth()+$this->a->colMargin/2, $this->a->GetY()-1
            );
	    $this->a->Ln(2);
	    $this->a->SetDrawColor( 0 );
	}
    }
}

}

/**************************************************************************/

class ActeurPoche extends Acteur {

function EnteteHeight() {
    $h = $this->getAttributeHeight( "titre", 10, 'B' );
    $h = $h + $this->getAttributeHeight( "bref",8 );
    $h = $h + $this->getAttributeHeight( "adresse",8 );
    $h = $h + $this->getAttributeHeight( "telephone",8 );
    $h = $h + $this->getAttributeHeight( "siteweb", 6 );
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

    if( $this->acteur_->hasAttribute( "bref" ) ) {
        $bref = utf8_decode( $this->acteur_->getAttribute( "bref" ) );
	if( $bref != "" ) {
            $this->a->PrintText( $bref, $this->a->GetColumnWidth(), 8 );
	}
    }

    if( $this->acteur_->hasAttribute( "adresse" ) ) {
        $adresse = utf8_decode( $this->acteur_->getAttribute( "adresse" ) );
	if( $adresse != "" ) {
	    $this->a->PrintText( $adresse, $this->a->GetColumnWidth(), 8 );
	}
    }

    if( $this->acteur_->hasAttribute( "telephone" ) ) {
        $telephone = utf8_decode( $this->acteur_->getAttribute( "telephone" ) );
	if( $telephone != "" ) {
		$this->a->PrintText( $telephone, $this->a->GetColumnWidth(), 8 );
	}
    }
    if( $this->acteur_->hasAttribute( "siteweb" ) ) {
        $siteweb = $this->acteur_->getAttribute( "siteweb" );
	if( $siteweb != "" ) {
            $this->a->PrintText( $siteweb, $this->a->GetColumnWidth(), 6, 'C' );
	}
    }

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

class ActeurLivret extends Acteur {

function EnteteHeight() {
    $h = $this->getAttributeHeight( "titre", 14, 'B' );
    $h = $h + $this->getAttributeHeight( "adresse" );
    $h = $h + $this->getAttributeHeight( "telephone" );
    $h = $h + $this->getAttributeHeight( "siteweb", 8 );
    
    // 21=height of the image + 0 de marge
    $hi = 21 + 0;
    if( $this->isComptoir() ) {
        $hi = $hi+5;
    }

    if( $h < $hi ) {
        $h = $hi;
    }
    return $h;
}

function height() {
    $this->a->SetFont('Futura','',12);
    $desc = utf8_decode( $this->acteur_->getAttribute( "desc" ) );
    $h =  $this->a->MultiCellHeight( $this->a->GetColumnWidth(), $this->a->cellHeight, $desc );

    if( $this->acteur_->hasAttribute( "message_comptoir" ) ) {
        $message = utf8_decode( $this->acteur_->getAttribute( "message_comptoir" ) );
	$h = $h + $this->a->MultiCellHeight( $this->a->GetColumnWidth(), $this->a->cellHeight, $message ) +5;
    }

    return $h + $this->EnteteHeight() ;
}

function Image( $image ) {
	$thpath = "images/acteurs/th/" . $image;
	if( is_file( $thpath ) ) {
	    $this->a->Image($thpath,null,null,null,21);
	    return;
	}
	$imgpath = "images/acteurs/" . $image;
	if( is_file( $imgpath ) ) {
	    makeSmallerImage( $imgpath, $thpath, 128, 128);
	    $this->a->Image($thpath,null,null,null,21);
	    return;
	}
}

function Entete() {
    $left_entete = $this->a->GetX();
    $top_entete = $this->a->GetY();

    if( $this->isComptoir() ) {
        // $this->a->SetFillColor(204,220,62);
        $this->a->SetFillColor(234,250,180);
        $this->a->Rect( $this->a->GetX()-1, $this->a->GetY()-1, $this->a->GetColumnWidth()+2, $this->height()+2, "F" );
        $this->a->PrintComptoir();
    } 

    $image = utf8_decode( $this->acteur_->getAttribute( "image" ) );
    $titre = utf8_decode( $this->acteur_->getAttribute( "titre" ) );
    $adresse = utf8_decode( $this->acteur_->getAttribute( "adresse" ) );

    // image a gauche
    $this->Image( $image );

    $this->a->SetY( $top_entete );
    $this->a->SetX( $left_entete + 30 );
    $this->a->SetLeftMargin($left_entete + 30 );
    // Le nom
    $this->a->PrintName( $titre, 60, 14 );
    $this->a->PrintText( $adresse );

    if( $this->acteur_->hasAttribute( "telephone" ) ) {
        $telephone = utf8_decode( $this->acteur_->getAttribute( "telephone" ) );
	if( $telephone != "" ) {
		$this->a->PrintText( $telephone );
	}
    }
    if( $this->acteur_->hasAttribute( "siteweb" ) ) {
        $siteweb = $this->acteur_->getAttribute( "siteweb" );
	if( $siteweb != "" ) {
            $this->a->PrintText( $siteweb, 60, 8, 'C' );
	}
    }
 
    // reset position
    $this->a->SetLeftMargin($left_entete);
    $this->a->SetX( $left_entete );
    $cury = $this->a->GetY();
    $y = $top_entete + $this->EnteteHeight();
    // get the y max
    if( $cury < $y ) {
        $this->a->SetY( $top_entete + $this->EnteteHeight() );
    }
}

function display_comptoir( $col, $c ) {

    $this->a->SetCol( $col );
    $this->separator( $col, $c );

    $titre = utf8_decode( $this->acteur_->getAttribute( "titre" ) );
    $adresse = utf8_decode( $this->acteur_->getAttribute( "adresse" ) );

    $this->a->PrintName( $titre, $this->a->GetColumnWidth(), 14 );
    $this->a->PrintText( $adresse, $this->a->GetColumnWidth() );

    if( $this->acteur_->hasAttribute( "telephone" ) ) {
        $telephone = utf8_decode( $this->acteur_->getAttribute( "telephone" ) );
	if( $telephone != "" ) {
            $this->a->PrintText( $telephone, $this->a->GetColumnWidth() );
	}
    }
    if( $this->acteur_->hasAttribute( "siteweb" ) ) {
        $siteweb = $this->acteur_->getAttribute( "siteweb" );
	if( $siteweb != "" ) {
            $this->a->PrintText( $siteweb, $this->a->GetColumnWidth(), 8, 'C' );
	}
    }
    $horaires = $this->acteur_->getElementsByTagName( "h" );
    $nbh = $horaires->length;
 
    $this->a->SetFont('Futura','B',10);
    $this->a->Cell( 50, 5, "Horaires:", 0, 1, 'L');

    $this->a->SetFont('Futura','',10);
    for($h=0; $h<$nbh; $h++) {
        $l = $horaires[$h]->getAttribute( "l" );
	$t = $horaires[$h]->getAttribute( "t" );
        $this->a->Cell( 40, 5, $l, 0, 0, 'R');
        $this->a->Cell( 50, 5, $t, 0, 1, 'L');
    }

    $message = utf8_decode( $this->acteur_->getAttribute( "message_comptoir" ) );
    if( $message != "" ) {
        $this->a->SetFont('Futura','B',10);
        $this->a->MultiCell( $this->a->GetColumnWidth(), $this->a->cellHeight, $message, 0, 'C' );
    }

    $this->a->Ln( 10 );

    if( $col == 0 ) {
	$this->a->bas_col0 = $this->a->GetY();
    } else { 
	$this->a->bas_col1 = $this->a->GetY();
    }
}

function display( $col, $deb_i ) {
    $this->a->SetCol( $col );
    $this->separator( $col, $deb_i );
    $this->Entete();
    
    $desc = utf8_decode( $this->acteur_->getAttribute( "desc" ) );
    // Font
    $this->a->SetFont('Futura','',12);
    // Output text in a 9 cm width column
    $this->a->MultiCell($this->a->GetColumnWidth(),$this->a->cellHeight,$desc);
    $this->a->Ln();

    $message = "";
    if( $this->acteur_->hasAttribute( "message_comptoir" ) ) {
        $message = utf8_decode( $this->acteur_->getAttribute( "message_comptoir" ) );
        $this->a->PrintText( $message, $this->a->GetColumnWidth(), 12, 'C' );
        $this->a->Ln();
    }

    $this->acteur_->setAttribute( "displayed", "true" );

    if( $col == 0 ) {
	$this->a->bas_col0 = $this->a->GetY();
    } else { 
	$this->a->bas_col1 = $this->a->GetY();
    }
}

}

