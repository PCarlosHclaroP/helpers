<style type="text/css">
    *{margin:0; padding:0; border:0}
    body{ background:url(<?php echo base_url();?>imagens/bg_topo.jpg) top repeat-x;}
    div#popdetalhe{width:946px; margin:auto; padding:5px;}
    div#imob{ margin-bottom:20px; height:125px; }
    div#loginho{height:90px; width:120px; padding:10px; float:left }
    div#local_imob{ width:550px; height:90px; padding:10px; float:left; }
    div#fone{ width:150px; padding:10px; float:right; }
    div#esquerda{background:#FFFFFF; width:950px; float:none;  }
    div#direita {background:#FFFFFF; width:950px; float:none; }
    div.container {padding:0px!important; }
    div#identifica{background:#FFFFFF; width:950px; margin-top:5px; margin-bottom:10px; float:none;}
    div#thumb_fotos{background:#FFFFFF; width:660px; margin-top:5px;  float:left;  border: 1px solid <?php echo $ficha_imovel_color;?>;}
    .carousel-indicators li.active { background-color: <?php echo $ficha_imovel_color;?>;}
    div#fotoprincipal { padding:5px; clear:both;}
    div#legenda {  padding:5px;}
    div#descricao{background:#FFFFFF; width:950px; margin-top:5px; margin-bottom:5px; float:none }
    div#formulario{background-image:url('.base_url().'imagens/bg_form.jpg); height:245px; width:950; float:none; padding:20px; border: 1px solid <?php echo $ficha_imovel_color;?>;}
    #formulario input[type="text"] {border:1px solid <?php echo $ficha_imovel_color;?>;_vertical-align:middle;}
    #formulario input[type="text"]:hover { background-color:#e1e1e1; border:1px solid <?php echo $ficha_imovel_color;?>; }
    #formulario input[type="text"]:focus { background-color:#e1e1e1; border:1px solid <?php echo $ficha_imovel_color;?>; }
    #formulario textarea{border:1px solid <?php echo $ficha_imovel_color;?>;_vertical-align:middle;}
    #formulario textarea:hover { background-color:#e1e1e1; border:1px solid <?php echo $ficha_imovel_color;?>; }
    #formulario textarea:focus { background-color:#e1e1e1; border:1px solid <?php echo $ficha_imovel_color;?>; }
    div#caracteristica{background:#FFFFFF; width:910px; float:none; padding:20px; margin-top:20px; margin-bottom: 20px; border: 1px solid <?php echo $ficha_imovel_color;?>;}
    div#mapa{background:#FFFFFF; margin-top:25px;  margin-bottom: 10px; clear:both; }
    div#video{background:#FFFFFF; margin-top:25px;  margin-bottom: 10px; clear:both; }
    div#rodape{background:#FFFFFF; height:110px; margin-top:15px; clear:both;  }
    div#menurodape____{background:#FFFFFF; margin-top:25px; margin-bottom:25px;}
    h1 {  color: <?php echo $ficha_imovel_color;?>; font-family: arial; font-size: 22px; font-weight: normal; padding: 0px; margin: 0px; margin-top:2px; margin-bottom:5px; }
    h2 {  color: <?php echo $ficha_imovel_color;?>; font-family: arial; font-size: 18px; font-weight: normal; padding-top: 10px; }
    .thumb { width: 60px; height: 45px; float: left; margin: 11px; cursor:pointer; }
    .subtitulo { color: <?php echo $ficha_imovel_color;?>; font-family: arial; font-size: 16px; font-weight: bold; }
    .texto { font-family: arial; font-size: 12px; font-weight: normal; }
    .negrito { font-family: arial; font-size: 12px; font-weight: normal; font-weight: bold; }
    div#menurodape { background:#FFFFFF;  margin-top:25px;  margin-bottom:25px; color: #000000; padding:10px; font:14px Arial; font-weight: bold; border-top: 1px solid <?php echo $ficha_imovel_color;?>; border-bottom: 1px solid <?php echo $ficha_imovel_color;?>; vertical-align:middle; }
    .menu_rodape a{ color:#333333; padding: 5px 25px; text-decoration:none; text-align: center; vertical-align:middle; }
    .menu_rodape a:hover{ color: #999999; }
    .menu_rodape a#home{ color:#666666; }
    .menu_rodape a.pri{ border-left: 0; }
    .menu_rodape a#home:hover{ color:#999999; }
    .menu_rodape a.current{ color:#CC0000; }
    .status_do_imovel{ z-index: 9999;  position: absolute;  margin-left: 5px;  margin-top: 110px; color: <?php echo $parametros->cor_txt_destaque;?>; background-color:<?php echo $parametros->cor_reservaimovel;?>; font-family: arial; padding: 23px 0px; width: 640px; }
    .status_do_imovel_menor{ color:<?php echo $parametros->cor_txt_destaque;?>; background-color: <?php echo $parametros->cor_reservaimovel;?>; font-family: arial; padding: 10px; margin-top: 15px; }
    input.honeypot-captcha{display:none;}
    .linha_link {
                text-align: right;
                width: 100%;
                height: 25px;
                clear: both;
                float: none;
            }
            .linha_link a {
                color:<?php echo $ficha_imovel_color;?>;
                text-align: right;
                float: left;
                font-family: arial;
                text-decoration: none;
                font-weight: bold;
                margin-right: 10px;
                margin-left: 15px;
            }
            
</style>