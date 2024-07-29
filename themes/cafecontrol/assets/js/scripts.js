$(function () {
    // mobile menu open
    $(".j_menu_mobile_open").click(function (e) {
        e.preventDefault();
        $(".j_menu_mobile_tab").css("left", "auto").fadeIn(1).animate({"right": "0"}, 200);
    });

    // mobile menu close
    $(".j_menu_mobile_close").click(function (e) {
        e.preventDefault();
        $(".j_menu_mobile_tab").animate({"left": "100%"}, 200, function () {
            $(".j_menu_mobile_tab").css({
                "right": "auto",
                "display": "none"
            });
        });
    });

    // scroll animate
    $("[data-go]").click(function (e) {
        e.preventDefault();
        var goto = $($(this).data("go")).offset().top;
        $("html, body").animate({scrollTop: goto}, goto / 2, "easeOutBounce");
    });

    // modal open
    $("[data-modal]").click(function (e) {
        e.preventDefault();
        var modal = $(this).data("modal");
        $(modal).fadeIn(200).css("display", "flex");
    });

    // modal close
    $(".j_modal_close").click(function (e) {
        e.preventDefault();
        if ($(e.target).hasClass("j_modal_close")) {
            $(".j_modal_close").fadeOut(200);
        }

        var iframe = $(this).find("iframe");
        if (iframe) {
            iframe.attr("src", iframe.attr("src"));
        }
    });

    // collpase
    $(".j_collapse").click(function (e) {
        e.preventDefault();
        var collapse = $(this);

        collapse.find(".j_collapse_icon").toggleClass("icon-minus").toggleClass("icon-plus");

        if (collapse.find(".j_collapse_box").is(":visible")) {
            collapse.find(".j_collapse_box").slideUp(200);
        } else {
            collapse.parent().find(".j_collapse_box").slideUp(200);
            collapse.find(".j_collapse_box").slideDown(200);
        }
    });

    //ajax form
    $("form:not('.ajax_off')").submit(function (e) {
        e.preventDefault();
        var form = $(this);
        var load = $(".ajax_load");
        var flashClass = "ajax_response";
        var flash = $("." + flashClass);

        form.ajaxSubmit({
            url: form.attr("action"),
            type: "POST",
            dataType: "json",
            beforeSend: function () {
                load.fadeIn(200).css("display", "flex");
            },
            success: function (response) {
                //redirect
                if (response.redirect) {
                    window.location.href = response.redirect;
                }

                //message
                if (response.message) {
                    if (flash.length) {
                        flash.html(response.message).fadeIn(100).effect("bounce", 300);
                    } else {
                        form.prepend("<div class='" + flashClass + "'>" + response.message + "</div>")
                                .find("." + flashClass).effect("bounce", 300);
                    }
                } else {
                    flash.fadeOut(100);
                }
            },
            complete: function () {
                load.fadeOut(200);

                if (form.data("reset") === true) {
                    form.trigger("reset");
                }
            }
        });
    });

    //ativa o modal
    const urlAtual = window.location.href;
    if (urlAtual.indexOf('systemjw') !== -1) {
        const myModal = new bootstrap.Modal('#staticBackdrop');
        const modalToggle = document.getElementById('staticBackdrop');
        myModal.show(modalToggle);
    }

    //ativar a modal de que o usuario conseguiu completar a quadra
    let urlParams = new URLSearchParams(urlAtual);
    
    if (urlParams.has('maptrue')) {
        const myModal = new bootstrap.Modal('#staticModalsuccess');
        const modalToggle = document.getElementById('staticModalsuccess');
        myModal.show(modalToggle);
    }
    

    // script para fazer o zoom na imagem
    document.querySelectorAll('.img-area').forEach(elem => {
        let x, y, width, height;

        elem.onmouseenter = () => {
            const size = elem.getBoundingClientRect();

            x = size.x;
            y = size.y;
            width = size.width;
            height = size.height;
        };

        elem.onmousemove = e => {
            const horizontal = (e.clientX - x) / width * 100;
            const vertical = (e.clientY - y) / height * 100;

            elem.style.setProperty('--x', horizontal + '%');
            elem.style.setProperty('--y', vertical + '%');
        };
    });

    // action para mandar o ID para abrir a modal pequena dos MAPAS
    $(document).on('click', '.modal_id', function () {
        const user_id = $(this).attr("id");
        if (user_id !== '') {
            var dados = {
                user_id: user_id
            };
            $.post('/jwsistema/mapbusca', dados, function (r) {
                $('.modal-content-dadosmapsearch').html(r);
            });
        }
    });
    
    // action reiniciar a quadra
    $(document).on('click', '.btn-reset', function () {
        const user_id = $(this).attr("id");
  
        if (user_id !== '') {
            var dados = {
                user_id: user_id
            };
            $.post('/jwsistema/resetmap', dados, function (r, status) {
                if(status == "success") {
                    window.location.href = urlAtual;
                } else {
                    alert("Opps algo deu errado tente novamente ou contete o administrador do sistema");
                }
            })
        }
    });
    

});

function closedSair() {
    window.location.href = '/jwsistema/entrar';
}