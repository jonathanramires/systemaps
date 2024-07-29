<?php

namespace Source\Models;

use Source\Core\Model;
use Source\Core\View;
use Source\Core\Session;
use Source\Support\Email;

class Auth extends Model {

    public function __construct() {
        parent::__construct("user", ["id"], ["email", "password"]);
    }
    
    public static function user(): ?User {
        $session = new Session();
        
        if(!$session->has("authUser")) {
            return null;
        }
        
        return (new User())->findById($session->authUser);
        
    }
    public static function logout(): void
    {
        $session = new Session();
        $session->unset("authUser");
    }

    public function register(User $user): bool {
        if (!$user->save()) {
            $this->message = $user->message;
            return false;
        }

        $view = new View(__DIR__ . "/../../shared/views/email");
        $message = $view->render("confirm", [
            "first_name" => $user->first_name,
            "confirm_link" => url("/obrigado/" . base64_encode($user->email))
        ]);
        
        return true;
    }

    public function login(string $email, string $password, bool $save = false): bool {
        if(!is_email($email)) {
            $this->message->warning("O e-mail informado não é valido");
            return false;
        }
        
        if($save) {
            setcookie("authEmail", $email, time() + 604800, "/");
        } else {
            setcookie("authEmail", null, time() - 3600, "/");
        }
        
        if(!is_passwd($password)) {
            $this->message->warning("A senha informada não é valida");
            return false;
        }
        
        $user = (new User())->findByEmail($email);
        if(!$user) {
            $this->message->error("O e-mail informado não esta cadastrado");
            return false;
        }
        
        if(!passwd_verify($password, $user->password)) {
            $this->message->error("A senha infromada não confere");
            return false;
        }
        
        if(passwd_rehash($user->password)) {
            $user->password = $password;
            $use->save();
        }
        
        //LOGIN
        (new Session())->set("authUser", $user->id);
        $this->message->success("Login efetuado com sucesso")->flash();
        return true;
    }
}
