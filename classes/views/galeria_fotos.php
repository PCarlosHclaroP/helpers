<?php 

if ( $parametros->modelo_detalhe == 4 ) :
    ?>
<script type="text/javascript">
    $(function(){
        lightbox.option({
            'maxwidth': 900,
            'maxHeight':700
        });
        
    });
</script>
    <div id="myCarousel" class="carousel slide ">
        <div class="carousel-inner ">
            <?php 
            $contador = isset($contador) ? $contador : $empresa->pagina_limite_ofertas;
            $qtde_abas = ceil($contador / 4);
            $conta_fotos = 1;
            $tem_navegacao = $qtde_abas > 1 ? TRUE : FALSE;
            ?>
            <div class="row">
                <div class="col-lg-1 col-md-1 col-sm-1 col-xs-2">
                    <?php 
                    if ( $tem_navegacao ) :
                        ?>
                        <a class="carousel-control left" href="#myCarousel" data-slide="prev">&lsaquo;</a>
                        <?php
                    endif;
                    ?>
                </div>
                <div class="col-lg-10 col-md-10 col-sm-10 col-xs-8">
                    <?php 
                    /**
                     * @todo separar as div de navegação 
                     */ 
                      ?>
                     
                    <?php
                    for( $a = 1; $a <= $qtde_abas; $a++ ) :
                        ?>
                        <div class="item <?php echo ( $conta_fotos == 1 ? 'active' : '' );?>" style="text-align: center;">
                            <?php
                            for( $c = 0; $c < 4; $c++) :
                                $foto = 'foto'.$conta_fotos ;
                                $e = $imovel->$foto;
                                    if ( isset($e) && ! empty( $e ) ) :
                                        $extensão = strtolower(strstr(substr($e, -4),'.') ? substr($e, -3) : substr($e, -4));
                                        ?>

                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                            <div class="thumbnail">
                                                <?php 
                                                if ( ( strstr($e,'http://') || file_exists( base_cwd().'/powsites/'.$imovel->id_empresa.'/imo/650F_'.$e ) ) ) :
                                                    if ( strstr($e,'http://') ):
                                                        $image_redimensiona = $e;
                                                        $image_link = $e;
                                                    else:
                                                        $image_link = base_url().'powsites/'.$imovel->id_empresa.'/imo/1150F_'.$e;
                                                        $image_redimensiona = base_url().'powsites/'.$imovel->id_empresa.'/imo/650F_'.$e;
                                                    endif;
                                                else:
                                                    $image_redimensiona = $e;
                                                    $image_ativa = $e;
                                                endif;
                                                $image_ativa = trata_image($image_redimensiona, $imovel->id , (array)$empresa, 150, 150, ( $conta_fotos ) );
                                                ?>
                                                <a href="<?php echo $image_link;?>" data-lightbox="a" >
                                                    <img class="img-responsive" src="<?php echo $image_ativa;?>">
                                                </a>
                                            </div>
                                        </div>
                                        <?php
                                    endif;
                                $conta_fotos++;
                            endfor;
                            ?>
                        </div>
                        <?php
                    endfor;
                    ?>
                </div>
                <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1">
                    <?php 
                    if ( $tem_navegacao ) :
                        ?>
                        <a class="carousel-control right" href="#myCarousel" data-slide="next">&rsaquo;</a> 
                        <?php
                    endif;
                    ?>

                </div>
            </div>
        </div>
    </div>
    
    <?php
else:
    ?>
    <div class="container" style="clear: both;">
        <?php 
        if ( isset($imovel->foto1) && ! empty($imovel->foto1)) :
            ?>    
            <div id="myCarousel" class="carousel slide ">
                <div class="carousel-inner ">
                    <?php 
                    $ol = '';
                    for( $a = 1; $a <= $empresa->pagina_limite_ofertas; $a++ ) :
                        $foto = 'foto'.$a;
                        $e = $imovel->$foto;
                        if ( isset($e) && ! empty( $e ) ) :
                            ?>
                            <div class="item <?php echo ( $a == 1 ? 'active' : '' );?>" style="text-align: center;">
                                <?php 
                                if ( ( strstr($e,'http://') || file_exists( base_cwd().'/powsites/'.$imovel->id_empresa.'/imo/650F_'.$e ) ) ) :
                                    if ( strstr($e,'http://') ):
                                        $image_ativa = $e;
                                    else:
                                        $image_ativa = base_url().'powsites/'.$imovel->id_empresa.'/imo/650F_'.$e;
                                    endif;
                                else:
                                    $image_ativa = $e;
                                endif;
                                ?>
                                <center>
                                    <a href="<?php echo $image_ativa;?>" data-lightbox="a">
                                        <img style="height:auto!important;" src="<?php echo $image_ativa;?>">
                                    </a>
                                </center>
                            </div> 
                            <?php 
                            $ol .= '<li data-target="#myCarousel" data-slide-to="'.($a-1).'" class="'.( $a == 1 ? 'active' : '' ).'"><img src="'.base_url().'powsites/'.$imovel->id_empresa.'/imo/T_'.$e.'"></li>';
                        endif;
                    endfor; 
                    ?>
                </div>
                <a class="carousel-control left" href="#myCarousel" data-slide="prev">&lsaquo;</a>
                <a class="carousel-control right" href="#myCarousel" data-slide="next">&rsaquo;</a> 
                <ol class="carousel-indicators">
                <?php echo $ol;?>
                </ol>
            </div>
            <?php
        endif; 
        ?>
    </div>
    <?php
endif;
?>