<?php
namespace Source\Models;

use Source\Core\Model;

class Mapscentrals extends Model 
{
    public function __construct() {
        parent::__construct(
                "maps_centrals", 
                ["id"], 
                ["idmap", "url_img", "status"]
                );
    }
}