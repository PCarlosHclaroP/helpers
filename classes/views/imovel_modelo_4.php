<!DOCTYPE html>
<html>
    <head>
        
        <meta charset="ISO-8859-1">
        
        <?php
        if ( isset($topo) )
        {
            echo $topo['includes'];
        }
        ?>
        <script type="text/javascript" src="<?php echo base_url();?>js/jquery-2.1.4.min.js"></script>
        <link rel="stylesheet" href="<?php echo base_url();?>css/bootstrap-3-3-5.css">
        <script type="text/javascript" src="<?php echo base_url();?>js/bootstrap-3-3-5.js"></script>
        <?php
        if ( isset($topo['includes_b']) )
        {
            echo $topo['includes_b'];
        }
        ?>
    </head>
    <body <?php if ( isset($topo) && empty($topo['img']) ) { ?> bgcolor="<?php echo $parametros['cor_janela'];?>" <?php } ?> data-item="<?php echo $id_imovel;?>" >
        <?php
        if ( isset($topo) )
        {
            echo $topo['html'];
            echo '</table>';
        }
        ?>
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <ul class="breadcrumb">
                        <li><a href="<?php echo base_url();?>" title="Inicial">Inicial</a></li>
                        <li><a href="<?php echo base_url();?>imoveis/<?php echo $imovel->cidade_link;?>" title="Imóveis em <?php echo $imovel->cidade;?>"><?php echo $imovel->cidade;?></a></li>
                        <li><a href="<?php echo base_url();?>imoveis/<?php echo $imovel->cidade_link;?>/<?php echo $imovel->imovel_tipo_link;?>" title="<?php echo $imovel->imovel_tipo;?> em <?php echo $imovel->cidade;?>"><?php echo $imovel->imovel_tipo;?></a></li>
                        <li><?php echo $imovel->nome;?></li>
                    </ul>
                </div>
            </div>
            <div class="row stage">
                <div class="col-lg-7 col-md-7 col-sm-6 col-xs-12">
                    <?php
                    if ( isset($imovel->foto1) && ! empty($imovel->foto1) ):
                        ?>
                        <img src="<?php echo base_url().'powsites/'.$imovel->id_empresa.'/imo/650F_'.$imovel->foto1;?>" alt="<?php echo ! empty($imovel->foto1_descricao) ? $imovel->foto1_descricao : $imovel->nome;?>" class="img-reponsive">
                        <?php
                    endif;
                    if ( $imovel->vendido ) :
                        ?>
                        <p class="tarja">Vendido</p>
                        <?php
                    endif;
                    ?>
                        
                </div>
                <div class="col-lg-5 col-md-5 col-sm-5 col-xs-12">
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                            <button class="btn btn-success telefone" type="button" data-item="<?php echo $empresa->empresa_telefone;?>"><span class="glyphicon glyphicon-earphone"></span> Ligue Agora</button>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                            <button class="btn btn-success email" type="button" data-toggle="modal" data-target=".bs-example-modal-lg"  ><span class="glyphicon glyphicon-envelope"></span> Contato por e-mail</button>
                            <!-- -->
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <h1><?php 
                                echo $imovel->nome;
                             ?></h1>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <p class="descricao">
                                <?php echo $imovel->descricao;?>
                            </p>
                        </div>
                    </div>
                    <?php 
                    /*
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <p class="destaque">ESPECIFICAÇÕES</p>
                            <div class="row">
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                    <div class="jumbotron">
                                        <p><?php echo $imovel->titulo_inicial;?></p>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                    <div class="jumbotron">
                                        <p class="preco">
                                            Valor: <br>
                                            <?php 
                                            if ( isset($precos) )
                                            {
                                                foreach ( $precos as $preco )
                                                {
                                                    echo '<span style="margin-left:5px;">'.$preco.'</span><br><span class="pull-right"><small> à vista</small></span>';
                                                }
                                            }
                                            ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                     * 
                     */
                    ?>
                    
                </div>
            </div>
            <div class="row detalhes">
                <div class="col-lg-7 col-md-7 col-sm-6 col-xs-12">
                    <ul class="nav nav-tabs" role="tablist">
                        <li role="presentation" class="active"><a href="#precos-condicoes" aria-controls="precos-condicoes" role="tab" data-toogle="tab">Preços e Condições</a></li>
                        <?php 
                        if ( isset($documentacao) && $documentacao ) :
                            ?>
                            <li role="presentation" ><a href="#documentacao" aria-controls="documentacao" role="tab" data-toogle="tab">Documentação</a></li>
                            <?php
                        endif;
                        /*
                        if ( isset($mapa) && ! empty($mapa) ) :
                        ?>
                            <li role="presentation" class="local" ><a href="#localizacao" aria-controls="localizacao" role="tab" data-toogle="tab">Localização</a></li>
                        <?php
                        endif;
                         * 
                         */
                        if ( isset($imovel->video) && ! empty($imovel->video) ) :
                        ?>
                            <li role="presentation" ><a href="#video" aria-controls="video" role="tab" data-toogle="tab">Video</a></li>
                        <?php
                        endif;
                        ?>
                    </ul>
                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane fade in active" id="precos-condicoes">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <p class="preco">
                                        <?php 
                                        if ( isset($precos) )
                                        {
                                            foreach ( $precos as $preco )
                                            {
                                                echo $preco.'<small> à vista</small>';
                                            }
                                        }
                                        ?>
                                    </p>
                                </div>
                                <?php
                                if ( isset($imovel->preco_titulo) && ! empty($imovel->preco_titulo) ) :
                                    ?>
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <p><?php echo nl2br($imovel->preco_complemento);?></p>
                                        <h3><?php echo $imovel->preco_titulo;?></h3>
                                    </div>
                                    <?php
                                endif;
                                ?>
                            </div>
                        </div>
                        <?php 
                        if ( isset($documentacao) && $documentacao ) :
                            ?>
                            <div role="tabpanel" class="tab-pane fade text-center" id="documentacao">
                                <?php
                                foreach ( $documentacao as $documento ) :
                                    ?>
                                    <a href="<?php echo base_url().( str_replace('[id]', $empresa->id, $documento->pasta) ).$documento->arquivo;?>" class="btn btn-success" target="_blank"><?php echo ( ! empty($documento->titulo) ? $documento->titulo : 'Download');?></a>
                                    <?php
                                endforeach;
                                ?>
                            </div>
                            <?php
                        endif;
                        /**
                        if ( isset($mapa) && ! empty($mapa) ) :
                        ?>
                            <div role="tabpanel" class="tab-pane fade text-center" id="localizacao">
                                <?php echo $mapa;?>
                            </div>
                        <?php
                        endif;
                         * 
                         */
                        if ( isset($imovel->video) && ! empty($imovel->video) ) :
                        ?>
                            <div role="tabpanel" class="tab-pane fade text-center" id="video">
                                <center><iframe width="500" height="300" src="<?php echo set_embed_video($imovel->video);?>" frameborder="0" allowfullscreen></iframe></center>
                            </div>
                        <?php
                        endif;
                        ?>
                    </div>
                </div>
                <div class="col-lg-5 col-md-5 col-sm-6 col-xs-12">
                    <h4>Galeria de Fotos</h4>
                    <?php echo $galeria;?>
                </div>
            </div>
            <?php
            if ( isset($mapa) && ! empty($mapa) ) :
            ?>
            <div class="row relacionados">
                <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
                    <h3>Localização</h3>
                </div>
                <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
                    <?php echo $mapa;?>
                </div>
            </div>
            <?php
            endif;
            ?>
            <div class="row relacionados">
                <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
                    <h3>Imóveis Relacionados</h3>
                </div>
                <?php
                echo $relacionados;
                ?>
            </div>
            
        </div>
        <?php
        echo $rodape;
        ?>
        
        <div class="modal fade bs-example-modal-lg" id="contato" tabindex="-1" role="dialog" >
            <div class="modal-dialog" role="document" >
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Entre em contato com <?php echo $empresa->empresa_nome_fantasia;?></h4>
                    </div>
                    <div class="modal-body" >
                        <?php echo $formulario;?>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
    </body>
</html>

