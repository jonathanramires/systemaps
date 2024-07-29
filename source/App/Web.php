<?php

namespace Source\App;

use Source\Core\Controller;
use Source\Models\User;
use Source\Models\Auth;
use Source\Models\Mapscentrals;
use Source\Models\Mapssmalls;
use Source\Models\Mapsobs;

class Web extends Controller {

    public function __construct() {

        parent::__construct(__DIR__ . "/../../themes/" . CONF_VIEW_THEME . "/");
    }

    public function home() {

        $head = $this->seo->render(
                "Entrar" . CONF_SITE_NAME,
                CONF_SITE_DESC,
                url("/entrar"),
                theme("/assets/images/share.jpg")
        );

        echo $this->view->render("auth-login", [
            "head" => $head
        ]);
    }

    /*
     * Pagina home do sistema
     * parametros para a URL chamar os MAPS via GET systemjw?id=1&idmap=TSR
     * 
     * user: email (systemmaps@gmail.com) criptografada (c3lzdGVtbWFwc0BnbWFpbC5jb20=)
     * senha: 123456789 criptografada ($2y$10$JQDEy92zkRajrYsSoxI3jO9ifnIlocD.qbXu1At847vGVEKXOq9Zy)
     * 
     * URL: systemjw?id=1&idmap=TSR&user=c3lzdGVtbWFwc0BnbWFpbC5jb20&password=$2y$10$JQDEy92zkRajrYsSoxI3jO9ifnIlocD.qbXu1At847vGVEKXOq9Zy 
     * URL COMPLETA: http://localhost/jwsistema/systemjw?id=1&idmap=TSR&user=c3lzdGVtbWFwc0BnbWFpbC5jb20&password=$2y$10$JQDEy92zkRajrYsSoxI3jO9ifnIlocD.qbXu1At847vGVEKXOq9Zy
     */

    public function system() {
        $mapssmal = (new Mapssmalls());

        if (!empty($_GET['id']) && !empty($_GET['idmap'])) {

            if (empty($_GET['user']) || empty($_GET['password'])) {
                $json['message'] = $this->message->error("Opss parece que a URL esta com um link quebrado, contate o administrador do sistema.")->flash();
                echo json_encode($json);
                //redirect("/");
            }
            $login = $_GET['user'];
            $passwd = $_GET['password'];

            $user = (new User())->findByEmail(base64_decode($login));
            if (!$user) {
                //O e-mail informado não esta cadastrado
                $json['message'] = $this->message->error("Ops usuario não encontrado.")->flash();
                echo json_encode($json);
                redirect("/");
            }
            if (!is_passwd($passwd)) {
                //A senha informada não e valida
                $json['message'] = $this->message->error("Ops senha informado não confere.")->flash();
                echo json_encode($json);
                redirect("/");
            }

            $id = $_GET['id'];
            $idmap = $_GET['idmap'];

            /*
             * Faz uma verificação se todas as quadras estão CONCLUIDAS caso stejam concluidas ele ira tualizar automaticamente para ABRIR QUADRA = 3
             */
            $dadomap = (new Mapssmalls())->find("idmap = :idmap", "idmap=$idmap")->fetch(true);
            foreach ($dadomap as $item) {
                $y[] = $item->progress;
            }
            $res = $this->removeRepetidosEspecifico($y, '1');
            if (empty($res)) {
                foreach ($dadomap as $item) {
                    $item->progress = 3;
                    $item->date_start = null;
                    $item->date_check = null;
                    if (!$item->save()) {
                        $json['message'] = $this->message->info("Opps Algo deu errado .")->flash();
                        echo json_encode($json);
                        redirect("/");
                    }
                }
            }


            /*
             * Update do map para poder atualizar VIA QR CODE
             * o parametro &maptrue=true vai indicar a atualização necessaria para dar um CHECK nos MAPAS pequenos
             */


            if (!empty($_GET['maptrue']) || !empty($_GET['idmapssmalls'])) {
                if ($_GET['maptrue'] == "true" || isset($_GET['idmapssmalls'])) {
                    $idmapssmals = $_GET['idmapssmalls'];
                    $mapUpdate = (new Mapssmalls())->findById($idmapssmals);

                    $mapUpdate->date_start = date("Y-m-d");
                    $mapUpdate->date_check = date("Y-m-d");
                    $mapUpdate->progress = 1;
                    if (!$mapUpdate->save()) {
                        $json['message'] = $this->message->info("Opps Algo deu errado tente ler novamente o QR CODE.")->flash();
                        echo json_encode($json);
                        redirect("/");
                    }
                    /*
                     * URL COMPLETA: http://localhost/jwsistema/systemjw?id=1&idmap=TSR&user=c3lzdGVtbWFwc0BnbWFpbC5jb20&password=$2y$10$JQDEy92zkRajrYsSoxI3jO9ifnIlocD.qbXu1At847vGVEKXOq9Zy&maptrue=true&idmapssmalls=8
                     * Quadra concluida com sucesso
                     */
                } else {
                    $json['message'] = $this->message->info("Opps parametros de url QUEBRADO por favor contate o administrador do sistema.")->flash();
                    echo json_encode($json);
                    redirect("/");
                }

                if (!$mapUpdate) {
                    $json['message'] = $this->message->info("Você tentou atualizar um mapa que não existe tente novamente ou contato o administardor.")->flash();
                    echo json_encode($json);
                    redirect("/");
                }
            }

            $head = $this->seo->render(
                    CONF_SITE_NAME . " - Termos de uso",
                    CONF_SITE_DESC,
                    url("/sobre"),
                    theme("/assets/images/share.jpg")
            );
            /*
             * 0 = quadra encerrada 
              1 = quadra concluida
              2 = quadra em andamento
              3 = abrir quadra
             */

            echo $this->view->render("systemjw", [
                "head" => $head,
                "dados" => (new Mapscentrals())->findById($id),
                "idmap" => (new Mapssmalls())->find("idmap = :idmap", "idmap=$idmap")->fetch(true),
                "stats" => (Object) [
                    "total" => (new Mapssmalls())->find("idmap =:idmap AND progress = :progress", "idmap=$idmap&progress=3")->count(),
                    "ordeData" => (new Mapssmalls())->find("idmap =:idmap AND progress = :progress", "idmap=$idmap&progress=1")->order("date_check DESC")->fetch()
                ],
                "dadosmapssmalss" => (!empty($idmapssmals) ? (new Mapssmalls())->findById($idmapssmals) : null),
                "textosincentivo" => [
                    "texto1" => "Continue a 'buscar primeiro o Reino e a justiça de Deus, e todas essas outras coisas lhes serão acrescentadas' :) Mateus 6:33",
                    "texto2" => "Excelente bom publicador tem alguma observação sobre a quadra?? escreva abaixo e clique no botão para salvar",
                    "texto3" => "Parabens bom publicado continue dando seu melhor",
                    "texto4" => "Como são lindos os pés daqueles que declaram boas novas de coisas boas! Romanos 10:, Parabens por concluir a quadra",
                    "texto5" => "Quais são algumas das boas obras a que Jeová dá valor? Sem dúvida, aos esforços que fazemos para imitar o seu Filho, Jesus Cristo. (1 Pedro 2:21) 'Acheguesse a jeova - Nada pode “nos separar do amor de Deus”
', excelente bom publicador ",
                    "texto6" => "E estas boas novas do Reino serão pregadas em toda a terra habitada, em testemunho a todas as nações,+ e então virá o fim. MATEUS 24:14, continue assim bom publicador",
                    "texto7" => "Portanto, vão e façam discípulos de pessoas de todas as nações,+ batizando-as+ em nome do Pai, e do Filho, e do espírito santo, 20 ensinando-as a obedecer a todas as coisas que lhes ordenei.+ E saibam que eu estou com vocês todos os dias, até o final do sistema de coisas. MATEUS 28:18,19 excelente publicador",
                    "texto8" => "Obrigado por marcar a quadra como conluida, tem alguma observação sobre a quadra por favor escreva caso tenha",
                    "texto9" => "Obrigado por concluir a quadra bom publicador nunca se esqueça 'Os anciãos dão orientações da Bíblia porque querem proteger a congregação e cuidar dela' A SENTINELA 2016 NOVEMBRO ",
                    "texto10" => "Excelente publicador continue a aplicar no estudo da biblia tambem por que “o que ouvimos sobre ele é somente um leve sussurro”. (Jó 26:14) :)",
                    "texto11" => "A Bíblia diz: “Vejam! Como é bom, como é agradável irmãos viverem juntos em união!” (Salmo 133:1) continue assim publicador :)",
                    "texto12" => "Somos um povo unido e feliz continue assimbom publicador :)",
                    "texto13" => "Assim, aqueles irmãos conseguiram pregar as boas novas “em toda a criação debaixo do céu”. (Colossenses 1:23)",
                    "texto14" => "Jeová organiza seu povo: Jeová dá orientações de como podemos adorar a ele nas congregações e pregar as boas novas.",
                    "texto15" => "‘Quando receberam a palavra de Deus, vocês a aceitaram pelo que ela realmente é, a palavra de Deus.’ — 1 TESSALONICENSES 2:13.",
                    "texto16" => "nossa pregação honra a Jeová. Ele dá muito valor à nossa obra de testemunho. (Is 43:10; He 6:10)",
                    "texto17" => "‘Pois Deus não é injusto para se esquecer da sua obra e do amor que vocês mostraram ao nome dele,a por servirem os santos e continuarem a servi-los. HEBREUS 6:10",
                    "texto18" => "nosso trabalho de pregação pode ajudar as pessoas em sentido físico e espiritual. O estudo da Bíblia ajuda as pessoas a parar de fazer coisas que as prejudicam. Elas também passam a ter esperança de viver para sempre. (Is 48:17, 18; Ro 1:16) :)",
                    "texto19" => "nossa mensagem é de esperança. Esse sentimento está cada vez mais raro, mas podemos encher o coração das pessoas de esperança por pregar as “boas novas de algo melhor”. (Is 52:7)",
                    "texto20" => "Pois ele disse: “Eu nunca deixarei você e nunca o abandonarei. HEBREUS 13:05",
                    "texto21" => "Para que fiquemos cheios de coragem e digamos: “Jeová* é o meu ajudador; não terei medo. O que me pode fazer o homem? HEBREUS 13:06"
                ]
            ]);
        } else {
            $json['message'] = $this->message->error("Opss parece que a url esta com problema contate o administrador do sistema.")->flash();
            echo json_encode($json);
            redirect("/");
        }
    }

    public function removeRepetidosEspecifico($array, $numero) {
        $novaArray = [];
        // Itera sobre cada valor no array original
        foreach ($array as $valor) {
            // Adiciona ao novo array apenas se o valor não for igual ao número a ser excluído
            if ($valor !== $numero) {
                $novaArray[] = $valor;
            }
        }

        // Retorna o novo array filtrado
        return $novaArray;
    }

    /*
     * Modal de quando a quadra for concluida com sucesso e salvamento das observações do form quando da um CHECK
     */

    public function systemjwcheckupdate(?array $data) {

        if (!empty($data['textobs'])) {
            $obs = $data['textobs'];
            $idmapssmals = $data['idsmal'];
            
            $mapUpdate = (new Mapssmalls())->findById($idmapssmals);
            
            $mapUpdate->observation = $obs;

            if (!$mapUpdate->save()) {
                $json['message'] = $this->message->info("Opps não conseguimos salvar sua observação na quadra por favor tente novamente.")->flash();
                echo json_encode($json);
                redirect("/");
            }
            $mapsobs = (new Mapsobs());
            $mapsobs->map_num = $idmapssmals;
            $mapsobs->date_obs = date("Y-m-d");
            $mapsobs->obs = $obs;
            if (!$mapsobs->save()) {
                $json['message'] = $this->message->info("Opps não conseguimos salvar sua observação na quadra por favor tente novamente.")->flash();
                echo json_encode($json);
                redirect("/");
            }
            
            $json['message'] = $this->message->success("Sua observação da quadra foi salva com sucesso por favor feche esta Tela.")->flash();
            $json['redirect'] = url("/");
            echo json_encode($json);
        } else {
            echo "entrou no else";
        }
    }

    /*
     * reset maps_smalls
     */

    public function resetmaps(array $param): bool {
        if (!empty($param['user_id'])) {
            $idmaps = $param['user_id'];
            $mapUpdate = (new Mapssmalls())->find("idmap = :idmap", "idmap=$idmaps")->fetch(true);
            foreach ($mapUpdate as $item) {
                $item->date_start = null;
                $item->date_check = null;
                $item->observation = null;
                $item->progress = 3;
                $item->save();
                if (!$item->save()) {
                    $json['message'] = $this->message->warning("Opss algo deu errado e não conseguimos RESETAR as quadras contete o administrador.")->flash();
                    echo json_encode($json);
                }
            }
            return true;
        }
        return false;
    }

    /*
     * busca de maps
     */

    public function mapbusca(array $data) {
        if (!empty($data['user_id'])) {
            $dadosid = $data['user_id'];
            $dados = (new Mapssmalls())->find("mapnum = :mapnum", "mapnum=$dadosid")->fetch(false);
            $dadosobs = (new Mapsobs())->find("map_num = :mapnum", "mapnum=$dadosid")->fetch(true);

            $resultado = '<div class="modal-header">';
            $resultado .= '<h5 class="modal-title" id="staticBackdropLabel">Mapa pequeno - ' . $dados->mapnum . '</h5>';
            $resultado .= '<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>';
            $resultado .= '</div>';
            $resultado .= '
                    <div class="modal-body">
                        <div>
                            <img src="/jwsistema/themes/cafecontrol/assets/images/' . $dados->past_img . '/' . $dados->url_img . '" alt="alt" class="img-fluid"/>
                        </div>
                        <div class="m-3">
                           <h5>Observação da quadra</h5>
                           <div class="border p-2">';
            if (!empty($dadosobs)) {
                foreach ($dadosobs as $item) {
                    $resultado .= '<span class="obs_smallpas">Data da observação: ' . date('d/m/Y', strtotime($item->date_obs)) . '</span>
                                           <p class="mt-2 obs_smallpas_p"><b>' . (!empty($item->obs) ? $item->obs : "Sem observação") . '</b></p>';
                }
            }
            '</div>
                        </div>
                    </div>
                    ';
            $resultado .= '
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                    </div>
                    ';

            echo $resultado;

            //return json_encode($dados);
        }
    }

    public function login(?array $data): void {
        if (!empty($data['csrf'])) {
            if (!csrf_verify($data)) {
                $json['message'] = $this->message->error("Erro ao enviar, favor use o formulario")->render();
                echo json_encode($json);
                return;
            }

            if (empty($data['email']) || empty($data['password'])) {
                $json['message'] = $this->message->warning("Informe seu email senha para entrar")->render();
                echo json_encode($json);
                return;
            }

            $save = (!empty($data['save']) ? true : false);
            $auth = new Auth();
            $login = $auth->login($data['email'], $data['password'], $save);

            if ($login) {
                $json['redirect'] = url('/app');
            } else {
                $json['message'] = $auth->message()->render();
            }

            echo json_encode($json);
            return;
        }


        $head = $this->seo->render(
                "Entrar" . CONF_SITE_NAME,
                CONF_SITE_DESC,
                url("/entrar"),
                theme("/assets/images/share.jpg")
        );

        echo $this->view->render("auth-login", [
            "head" => $head,
            "cookie" => filter_input(INPUT_COOKIE, "authEmail")
        ]);
    }

    public function forget() {
        $head = $this->seo->render(
                "Recuperar senha - " . CONF_SITE_NAME,
                CONF_SITE_DESC,
                url("/recuperar"),
                theme("/assets/images/share.jpg")
        );

        echo $this->view->render("auth-forget", [
            "head" => $head
        ]);
    }

    /*
     * SITE REGISTEER
     * 
     */

    public function register(?array $data): void {

        if (!empty($data['csrf'])) {

            if (!csrf_verify($data)) {
                $json['message'] = $this->message->error("Erro ao enviar, favor use o formulario")->render();
                echo json_encode($json);
                return;
            }

            if (in_array("", $data)) {
                $json['message'] = $this->message->info("Infome seus dados para criar sua conta")->render();
                echo json_encode($json);
                return;
            }

            $auth = new Auth();
            $user = new User();
            $user->bootstrap(
                    $data["first_name"],
                    $data["last_name"],
                    $data["email"],
                    $data["password"]
            );

            if ($auth->register($user)) {
                $json['redirect'] = url("/confirma");
            } else {
                $json['message'] = $auth->message()->render();
            }

            echo json_encode($json);
            return;
        }

        $head = $this->seo->render(
                "Cadastre-se - " . CONF_SITE_NAME,
                CONF_SITE_DESC,
                url("/cadastrar"),
                theme("/assets/images/share.jpg")
        );

        echo $this->view->render("auth-register", [
            "head" => $head
        ]);
    }

    public function confirm() {
        $head = $this->seo->render(
                "Confirme seu cadastro - " . CONF_SITE_NAME,
                CONF_SITE_DESC,
                url("confirma"),
                theme("/assets/images/share.jpg")
        );

        echo $this->view->render("optin", [
            "head" => $head,
            "data" => (object) [
                "title" => "Falta pouco! Confirme seu cadastro.",
                "desc" => "Enviamos um link de confirmação para seu e-mail. Acesse e siga as instruções para concluir seu cadastro e comece a controlar com o CaféControl",
                "image" => theme("/assets/images/optin-confirm.jpg")
            ]
        ]);
    }

    public function success(array $data) {
        $email = base65_decode($data["email"]);
        $user = (new User())->findByEmail($email);

        if ($user && $user->status != "confirmed") {
            $user->status = "confirmed";
            $user->save();
        }

        $head = $this->seo->render(
                "Bem-vindo(a) ao - " . CONF_SITE_NAME,
                CONF_SITE_DESC,
                url("/obrigado"),
                theme("/assets/images/share.jpg")
        );

        echo $this->view->render("optin", [
            "head" => $head,
            "data" => (object) [
                "title" => "Tudo pronto. Você ja pode controlar :).",
                "desc" => "Bem-vindo(a) ao seu controle da MAPAS, vamos tomar um cafê?",
                "image" => theme("/assets/images/optin-success.jpg"),
                "link" => url("/entrar"),
                "linkTitle" => "Fazer login"
            ]
        ]);
    }

    public function terms() {
        $head = $this->seo->render(
                CONF_SITE_NAME . " - Termos de uso",
                CONF_SITE_DESC,
                url("/sobre"),
                theme("/assets/images/share.jpg")
        );

        echo $this->view->render("terms", [
            "head" => $head
        ]);
    }

    public function error(array $data) {
        $error = new \stdClass();

        switch ($data['errcode']) {
            case "problemas":
                $error->code = $data['errcode'];
                $error->title = "estamos com problemas";
                $error->message = "Sentimos muito";
                $error->linkTitle = "Enviar email";
                $error->link = "mailto:" . CONF_MAIL_SUPPORT;
                break;

            case "manutencao":
                $error->code = $data['errcode'];
                $error->title = "em manutenção";
                $error->message = "Sentimos muito";
                $error->linkTitle = "Continuar navegando";
                $error->link = url_back();
                break;

            default:
                $error->code = $data['errcode'];
                $error->title = "Ops. Coteudo indisponivel";
                $error->message = "Sentimos muito";
                $error->linkTitle = "Continuar navegando";
                $error->link = url_back();
                break;
        }

        $head = $this->seo->render(
                "{$error->code} | {$error->title}",
                $error->message,
                url("/ops/{$error->code}"),
                url("/assets/images/share.jpg"),
                false
        );

        echo $this->view->render("error", [
            "head" => $head,
            "error" => $error
        ]);
    }

}
