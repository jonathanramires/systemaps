<?php $this->layout("theme"); ?>

<!-- Modal -->
<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">System maps</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick="closedSair()"></button>
            </div>
            <div class="modal-body">
                <div class="img-area">
                    <img src="<?= theme("/assets/images/$dados->past_img/$dados->url_img"); ?>" alt="alt" class="img-fluid img-all"/>
                </div>
                <div>
                    <h3 class="p-4 text-center">
                        <?php
                        switch ($dados->idmap) {
                            case "TSR":
                                echo "Territorio Salão do reino";
                                break;
                            case "TJ":
                                echo "Territorio Josenildo";
                                break;
                            case "TG":
                                echo "Territorio Gioia (Miguel capel)";
                                break;
                            case "TCDHU":
                                echo "Territorio CDHU";
                                break;
                            case "TAM":
                                echo "Territorio Ambuita";
                                break;
                            case "TMG":
                                echo "Territorio Maria gabriela";
                                break;
                            case "TMS":
                                echo "Territorio Monte Sinai (jose cicero)";
                                break;
                            case "TPF":
                                echo "Territorio Ponto final";
                                break;
                            case "TRDF":
                                echo "Territorio Residencial das Flores";
                                break;
                        }
                        ?>

                    </h3>
                </div>
                <div class="row justify-content-between p-2">
                    <div class="col-sm-12 col-md-4 p-2 alert-danger shadow-lg rounded">
                        <span class="d-block text-center fs-4"><?= $stats->total; ?></span>
                        <span class="d-block text-center">Total de quadras faltando trabalhar</span>
                    </div>
                    <div class="col-sm-12 col-md-4 p-2 alert-primary shadow-lg rounded">
                        <?php if (!empty($stats->ordeData->idmap)): ?>
                            <span class="d-block text-center fs-4"><?= $stats->ordeData->idmap, $stats->ordeData->mapnum ?></span>
                            <span class="d-block text-center">Ultima quadra trabalhada</span>
                        <?php else: ?>
                            <span class="d-block text-center fs-4">Atenção</span>
                            <span class="d-block text-center">Por favor inicie o territorio</span>
                        <?php endif; ?>
                    </div>
                    <div class="col-sm-12 col-md-4 p-2 alert-info shadow-lg rounded">
                        <?php if (!empty($stats->ordeData->date_check)): ?>
                            <span class="d-block text-center fs-4"><?= date('d/m/Y', strtotime($stats->ordeData->date_check)); ?></span>
                            <span class="d-block text-center">Ultima data trabalhada no Territorio</span>
                        <?php else: ?>
                            <span class="d-block text-center fs-4">Atenção</span>
                            <span class="d-block text-center">Ainda não a data da ultima quadra trabalhada</span>
                        <?php endif; ?>
                    </div>
                </div>
                <table class="table">
                    <thead class="table_tr_dados_head">
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Quadras</th>
                            <th scope="col">Data iniciado</th>
                            <th scope="col">Data concluido</th>
                            <th scope="col">Status</th>
                            <th scope="col">Observação</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($idmap as $item) : ?>
                           <?php $array[$item->mapnum] = $item->mapnum; ?>
                            <tr class="table_tr_dados">
                                <th scope="row" class="table_tr_dados_id"><?= $item->id ?></th>
                                <td class="table_tr_dados_code"><a href="#" data-bs-toggle="modal" data-bs-target="#statimappequeno" role="button" class="modal_id btn btn-dark" id="<?= $item->mapnum ?>"><?= "0".count($array); ?></a></td>
                                <td class="table_tr_dados_code"><span class="table_tr_dados_code_date"><strong>Data iniciado:</strong></span><?php echo ($item->date_start === null) ? "---" : date('d/m/Y', strtotime($item->date_start)); ?></td>
                                <td class="table_tr_dados_code"><span class="table_tr_dados_code_date"><strong>Data concluido:</strong></span><?php echo ($item->date_check === NULL) ? "---" : date('d/m/Y', strtotime($item->date_check)); ?></td>
                                <td class="table_tr_dados_code">
                                    <?php
                                    switch ($item->progress) {
                                        case 0:
                                            echo "<span class='status_and closed'>Encerrado</span>";
                                            break;
                                        case 1:
                                            echo "<span class='status_and concluded'>Concluido</span>";
                                            break;
                                        case 2:
                                            echo "<span class='status_and end_and'>Em andamento</span>";
                                            break;
                                        case 3:
                                            echo "<span class='status_and info'>Abrir Quadra</span>";
                                            break;
                                    }
                                    ?>
                                </td>
                                <td class="table_tr_dados_code">
                                    <?php
                                    switch ($item->progress) {
                                        case 0:
                                            echo "Quadra encerrada";
                                            break;
                                        case 1:
                                            echo "<b>" . (!empty($item->observation) ? $item->observation : "Sem observação") . "</b>";
                                            break;
                                        case 2:
                                            echo "Quadra sendo trabalhada pelos publicadores";
                                            break;
                                        case 3:
                                            echo "Quadra precisando trabalhar";
                                            break;
                                    }
                                    ?>
                                </td>
                            </tr>
                            <!--
                            0 = quadra encerrada 
                            1 = quadra concluida
                            2 = quadra em andamento
                            3 = abrir quadra
                            -->
                        <?php endforeach; ?>

                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#staticHistoricoquadras">Ver Historico das Quadras</button>
                <button type="button" class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#statimodalactionReiniciar">Reiniciar Territorio?</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" onclick="closedSair()">Fechar</button>
            </div>
        </div>
    </div>
</div>


<!-- modal Historico de quadras -->
<div class="modal fade" id="staticHistoricoquadras" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">System maps</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div>
                    <h3 class="p-4 text-center">Territorio maria gabriela (Historico das quadras)</h3>
                </div>
                <div class="container">
                    <div class="row">
                        <div class="col-12">
                            <div class="page-title-box">                                    
                                <div class="page-title-right">
                                    <form>
                                        <div class="row">
                                            <div class="col-5">
                                                <div>
                                                    <label for="exampleFormControlInput1" class="form-label">Data inicial</label>
                                                    <input type="date" class="form-control" id="dash-daterange">
                                                </div>
                                            </div>
                                            <div class="col-5">
                                                <div>
                                                    <label for="exampleFormControlInput1" class="form-label">Data Final</label>
                                                    <input type="date" class="form-control" id="dash-daterange">
                                                </div>
                                            </div>
                                            <div class="col-2">
                                                <button type="submit" class="btn btn-primary">FILTRAR</button>
                                            </div>
                                            <div>
                                                </form>
                                            </div>
                                        </div>
                                </div>
                            </div>
                        </div>
                        <table class="table">
                            <thead class="table_tr_dados_head">
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Quadras</th>
                                    <th scope="col">Data iniciado</th>
                                    <th scope="col">Data concluido</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Tempo</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="table_tr_dados">
                                    <th scope="row" class="table_tr_dados_id">1</th>
                                    <td class="table_tr_dados_code"><a href="#" data-bs-toggle="modal" data-bs-target="#statimappequeno">TSR001</a></td>
                                    <td class="table_tr_dados_code"><span class="table_tr_dados_code_date"><strong>Data iniciado:</strong></span>01/03/2023</td>
                                    <td class="table_tr_dados_code"><span class="table_tr_dados_code_date"><strong>Data concluido:</strong></span>04/03/2023</td>
                                    <td class="table_tr_dados_code"><span class="status_and concluded">Concluido</span></td>
                                    <td class="table_tr_dados_code">Quadra concluida com sucesso</td>
                                </tr>
                                <tr class="table_tr_dados">
                                    <th scope="row" class="table_tr_dados_id">2</th>
                                    <td class="table_tr_dados_code"><a href="#">TSR002</a></td>
                                    <td class="table_tr_dados_code"><span class="table_tr_dados_code_date"><strong>Data iniciado:</strong></span>01/03/2023</td>
                                    <td class="table_tr_dados_code"><span class="table_tr_dados_code_date"><strong>Data concluido:</strong></span>---</td>
                                    <td class="table_tr_dados_code"><span class="status_and end_and">Em andamento</span></td>
                                    <td class="table_tr_dados_code">Publicadores <br>trabalhando nesta Quadra</td>
                                </tr>
                                <tr class="table_tr_dados">
                                    <th scope="row" class="table_tr_dados_id">3</th>
                                    <td class="table_tr_dados_code"><a href="#">TSR003</a></td>
                                    <td class="table_tr_dados_code"><span class="table_tr_dados_code_date"><strong>Data iniciado:</strong></span>01/03/2023</td>
                                    <td class="table_tr_dados_code"><span class="table_tr_dados_code_date"><strong>Data concluido:</strong></span>---</td>
                                    <td class="table_tr_dados_code"><span class="status_and closed">Encerrado</span></td>
                                    <td class="table_tr_dados_code"><p>Ja faz 10 dias <br> que não e feito esta quadra</p></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#statimodalactionReiniciar">Reiniciar Territorio?</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

















<!-- modal botão reiniciar territorio -->
<div class="modal fade modal-alert bg-secondary bg-opacity-10" id="statimodalactionReiniciar" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content rounded-3 shadow">
            <div class="modal-body p-4 text-center">
                <h5 class="mb-0">Deseja reiniciar as Quadras?</h5>
                <p class="mb-0">Ao reiniciar as quadras todas as quadras serão marcadas como "ABRIR QUADRA" esta ação tambem e feita automaticamente quando todas as quadras estiverem CONCLUIDAS.</p>
            </div>
            <div class="modal-footer flex-nowrap p-0">
                <button type="button" class="btn btn-lg btn-link fs-6 text-decoration-none col-6 m-0 rounded-0 border-end btn-reset" id="<?= $_GET['idmap']; ?>"><strong>Sim reiniciar Quadras</strong></button>
                <button type="button" class="btn btn-lg btn-link fs-6 text-decoration-none col-6 m-0 rounded-0" data-bs-dismiss="modal">Não</button>
            </div>
        </div>
    </div>
</div>





<!-- Modal Mapa pequeno -->
<div class="modal fade" id="statimappequeno" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content modal-content-dadosmapsearch">

        </div>
    </div>
</div>


<!-- modal Sucesso ao COMPLETAR a quadra -->
<div class="modal fade modal-alert bg-secondary bg-opacity-10" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true" id="staticModalsuccess">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success">
                <h5 class="modal-title text-white text-center">Parabens</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick="closedSair()"></button>
            </div>
            <div class="modal-body">
                <i class="icon-check text-center d-block fs-1 text-success"></i>
                <?php $a = array_rand($textosincentivo); ?>
                <?php if (isset($dadosmapssmalss)): ?>
                    <h3 class="text-center">Parabens bom Publicador quadra <strong><?= $dadosmapssmalss->idmap, $dadosmapssmalss->mapnum ?></strong> concluida com sucesso.</h3>
                    <p><?= $textosincentivo[$a] ?></p>
                    <form action="<?= url("/systemjwcheckupdate/{$dadosmapssmalss->id}") ?>" method="post">
                        <div class="form-floating">
                            <textarea class="form-control" name="textobs" placeholder="Alguma observação sobre a quadra?" id="floatingTextarea2" style="height: 100px"></textarea>
                            <label for="floatingTextarea2">Alguma observação sobre a quadra?</label>
                        </div>

                        <button class="auth_form_btn transition gradient gradient-green gradient-hover">Salvar</button>
                    </form>
                <?php endif; ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" onclick="closedSair()">Fechar</button>
            </div>
        </div>
    </div>
</div>