
<?php


class BaseXMLImpl {
    
    public $x = null;
    public function getRecords( string $tag ) {
        return $this->x->getElementsByTagName($tag);
    }
    
    public function getAttribute( string $prop ) {
        return $this->x->getAttribute($prop);
    }

    // used for cards
    public function getParentAttribute( string $prop ) {
        $parent = $this->x->parentNode;
        return $parent->getAttribute($prop);
    }

    public function getVersoInfo() {

        $categorie = $this->x->parentNode;
        $type = $categorie->parentNode;
        $info = array();
        $info['titre'] = utf8_decode($type->getAttribute( "titre" ));
        $info['r'] = $type->getAttribute( "r" );
        $info['g'] = $type->getAttribute( "g" );
        $info['b'] = $type->getAttribute( "b" );

        return $info;
    }

    
    public function hasAttribute( string $prop ) {
        return $this->x->hasAttribute($prop);
    }

    public function removeAttribute( string $prop ) {
        return $this->x->removeAttribute($prop);
    }

    public function setAttribute( string $prop, string $val ) {
        return $this->x->setAttribute($prop, $val);
    }

    public function getElements( string $tag ) {
        $records = $this->getRecords( $tag );
        
        $ret = array();
        $nb = $records->length;
        $c = 0;
        for ($a = 0; $a < $nb; ++$a) {
            $elt = $records[$a];
            $ret[] = new ElementImpl( $elt );
        }

        return $ret;
    }

    public function getActeurs() {
        $records = $this->getRecords('acteur');
        
        $ret = array();
        $nb_acteurs = $records->length;
        $c = 0;
        for ($a = 0; $a < $nb_acteurs; ++$a) {
            $acteur = $records[$a];
            $ret[] = new ActeurImpl( $acteur );
        }

        return $ret;
    }
}

class ActeurImpl extends BaseXMLImpl {
    
    public function __construct($x) {
        $this->x = $x;
    }
    
    public function getHoraires() {
        return $this->getRecords('h'); 
    }
}

class CategorieImpl extends BaseXMLImpl {
    
    public function __construct($x) {
        $this->x = $x;
    }
    
    public function getSousCategories() {
        $records = $this->getRecords('scat');
        
        $ret = array();
        $nb_scat = $records->length;
        $c = 0;
        for ($c = 0; $c < $nb_scat; ++$c) {
            $scat = $records[$c];
            $ret[] = new SousCategorieImpl( $scat );
        }

        return $ret;
    }
}

class MarcheCategorieImpl extends CategorieImpl {
    public function __construct($x) {
        $this->x = $x;
    }
}

class SousCategorieImpl extends BaseXMLImpl {
    
    public function __construct($x) {
        $this->x = $x;
    }

}

class ElementImpl extends BaseXMLImpl {
    public function __construct($x) {
        $this->x = $x;
    }
}

/**************************************************************************/
class AnnuaireImpl extends BaseXMLImpl {

    private $xmlDoc = null;

    public function __construct( string $type) {
        $this->xmlDoc = new DOMDocument();
        if ($type == 'Polygons') {
            $this->xmlDoc->load('cartesJeu.xml');
        } else {
            $this->xmlDoc->load('acteurs-cat.xml');
        }
        $this->x = $this->xmlDoc->documentElement;
    }

    public function getHeader() {
        $h = array();
        $h['titre'] = utf8_decode($this->x->getAttribute('titre'));
        $h['sstitre'] = utf8_decode($this->x->getAttribute('sstitre'));
        $h['localisation'] = utf8_decode($this->x->getAttribute('localisation'));
        $h['slogan'] = utf8_decode($this->x->getAttribute('slogan'));
        return $h;
    }


    public function getCategories() {
        $records = $this->getRecords('categorie');
        
        $ret = array();
        $nb_cat = $records->length;
        $c = 0;
        for ($c = 0; $c < $nb_cat; ++$c) {
            $categorie = $records[$c];
            $ret[] = new CategorieImpl( $categorie );
        }

        return $ret;
    }

    public function getMarches() {
        $records = $this->getRecords('marches');
        
        $ret = array();
        $nb_cat = $records->length;
        $c = 0;
        for ($c = 0; $c < $nb_cat; ++$c) {
            $categorie = $records[$c];
            $ret[] = new MarcheCategorieImpl( $categorie );
        }

        return $ret;
    }
}

?>