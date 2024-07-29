<?php

namespace Source\Models;

use Source\Core\Model;

class Mapssmalls extends Model {

    public function __construct() {
        parent::__construct("maps_smalls", ["id"], ["idmap", "status"]);
    }

    /*
     * 0 = quadra encerrada 
      1 = quadra concluida
      2 = quadra em andamento
      3 = abrir quadra
     */

}
