<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <meta name="mit" content="2023-01-27T22:35:26-03:00+196032">
        <meta name="viewport" content="width=device-width,initial-scale=1">

        <title>CafeControl - Gerencie suas contas com um bom café</title>

        <link rel="icon" type="image/png" href="<?= theme("/assets/images/favicon.png"); ?>"/>
        <link rel="stylesheet" href="<?= theme("/assets/css/styles.css") ?>"/>
        <link rel="stylesheet" href="<?= theme("/assets/css/boot.css"); ?>"/>
        <link rel="stylesheet" href="<?= theme("/assets/css/bootstrap.min.css"); ?>"/>
        <link rel="stylesheet" href="<?= theme("/assets/css/message.css") ?>"/>
        <link rel="stylesheet" href="<?= theme("/assets/css/style.css"); ?>"/>
    </head>
    <body>

        <div class="ajax_load">
            <div class="ajax_load_box">
                <div class="ajax_load_box_circle"></div>
                <p class="ajax_load_box_title">Aguarde, carregando...</p>
            </div>
        </div>

        <!--HEADER-->
        <header class="main_header gradient gradient-green">
            <div class="container">
                <div class="main_header_logo">
                    <h1><a class="icon-coffee transition" title="Home" href="./">Cafe<b>Control</b></a></h1>
                </div>

                <nav class="main_header_nav">
                    <span class="main_header_nav_mobile j_menu_mobile_open icon-menu icon-notext radius transition"></span>
                    <div class="main_header_nav_links j_menu_mobile_tab">
                        <span class="main_header_nav_mobile_close j_menu_mobile_close icon-error icon-notext transition"></span>
                        <a class="link transition radius active" title="Home" href="<?= url(); ?>">Home</a>
                        <a class="link transition radius" title="Sobre" href="<?= url("sobre"); ?>">Sobre o sistema</a>
                        <a class="link transition radius" title="Sobre" href="/">Precisa de ajuda?</a>
                        <a class="link login transition radius icon-sign-in" title="Entrar" href="<?= url("entrar"); ?>">Entrar</a>
                    </div>
                </nav>
            </div>
        </header>

        <!--CONTENT-->
        <main class="main_content">
            <?= $this->section("content"); ?>
        </main>

        <?php if ($this->section("optout")): ?>
            <?= $this->section("optout"); ?>
        <?php else: ?>
            <article class="footer_optout">
                <div class="footer_optout_content content">
                    <span class="icon icon-coffee icon-notext"></span>
                    <h2>Comece a Organizar os territorios agora mesmo</h2>
                    <p>É rápido, simples!</p>
                    <a href="?file=auth-register"
                       class="footer_optout_btn gradient gradient-green gradient-hover radius icon-check-square-o">Quero começar</a>
                </div>
            </article>
        <?php endif ?>

        <!--FOOTER-->
        <footer class="main_footer">
            <div class="container content">
                <section class="main_footer_content">
                    <article class="main_footer_content_item">
                        <h2>Sobre:</h2>
                        <p>O Sistemap e um sistema criado com o intuito para melhorar tanto a gestão como ter um controle dos mapas das testemunhas de jeova
                            . Obs: este sistema não tem vinculo algum com o jw.org caso queira saber mais sobre nossa organização acesse <a href="https://www.jw.org/pt/" target="_blank">jw.org</a>(site oficial das testemunhas de jeova)</p>
                        <a title="Termos de uso" href="<?= url("termos"); ?>">Termos de uso</a>
                    </article>

                    <article class="main_footer_content_item">
                        <h2>Mais:</h2>
                        <a class="link transition radius active" title="Home" href="<?= url(); ?>">Home</a>
                        <a class="link transition radius" title="Sobre" href="<?= url("sobre"); ?>">Sobre o sistema</a>
                        <a class="link transition radius" title="Sobre" href="/">Precisa de ajuda?</a>
                        <a class="link transition radius" title="Entrar" href="<?= url("entrar"); ?>">Entrar</a>
                    </article>

                    <article class="main_footer_content_item">
                        <h2>Contato:</h2>
                        <p class="icon-phone"><b>Telefone:</b><br> +55 55 5555.5555</p>
                        <p class="icon-envelope"><b>Email:</b><br> cafe@cafecontrol.com</p>
                    </article>

                    <article class="main_footer_content_item social">
                        <h2>Social:</h2>
                        <a target="_blank" class="icon-facebook" href="facebook.com.br/<?= CONF_SOCIAL_FACEBOOK_PAGE ?>" title="CafeControl no Facebook">/CafeControl</a>
                        <a target="_blank" class="icon-instagram" href="#insta" title="CafeControl no Instagram">@CafeControl</a>
                        <a target="_blank" class="icon-youtube" href="#yt" title="CafeControl no YouTube">/CafeControl</a>
                    </article>
                </section>
            </div>
        </footer>

        <script src="<?= theme("assets/js/jquery.min.js"); ?>"></script>
        <script src="<?= theme("assets/js/jquery-ui.js"); ?>"></script>
        <script src="<?= theme("assets/js/bootstrap.min.js"); ?>"></script>
        <script src="<?= theme("assets/js/jquery.form.js"); ?>"></script>
        <script src="<?= theme("assets/js/scripts.js"); ?>"></script>

    </body>
</html>