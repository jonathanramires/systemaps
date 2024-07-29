<?php
namespace Source\Core;

use Source\Support\Seo;
use Source\Support\Message;

/**
 * JWSISTEMA | Class Controller
 *
 * @author jonathan ramirez
 * @package Source\Core
 */
class Controller
{
    protected $view;
    protected $seo;
    protected $message;


    public function __construct($pathToViews = null) {
        $this->view = new View($pathToViews);
        $this->seo = new Seo();
        $this->message = new Message();
    }
}