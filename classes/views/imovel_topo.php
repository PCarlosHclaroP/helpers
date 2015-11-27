<div id="imob">
    <div id="loginho">
        <a href="<?php echo base_url(); ?>" style="text-decoration: none;">
            <img 
                src="http://www.guiasjp.com/paginas/<?php echo $empresa->pagina_logo_pequeno;?>" 
                width='80' 
                height='60' 
                border='0' 
                alt='<?php echo $empresa->empresa_nome_fantasia;?>' 
                title='<?php echo $empresa->empresa_nome_fantasia;?>'
                > 
        </a>
        <BR>
        <span class='texto'>
            <?php 
            if ($empresa->pagina_creci != "") 
            { 
                echo "CRECI : ".$empresa->pagina_creci;
            } 
            elseif ( ! empty($empresa->empresa_cnpj) ) 
            {  
                echo "CNPJ : ".$empresa->empresa_cnpj;
            } 
            ?>
        </span>
    </div>
    <div id="local_imob" align="left">
        <span class='subtitulo'>
            <?php echo $empresa->empresa_nome_fantasia;?>
            <br>
        </span>
        <span class='texto'>
            <?php 
            $qtde_caracteres = 60 + ( $empresa->descricao_linhas * 60 );
            echo substr( $empresa->empresa_descricao,0,$qtde_caracteres);
            ?>
            <br>
            <?php
            echo $logradouro->logradouro.', '.$empresa->empresa_numero.' '.$empresa->empresa_complemento.' <br> '.$logradouro->bairro.' - '.$cidade->nome;   
            ?>
        </span>
    </div>
    <div id='fone' class='subtitulo' >
        <strong>Telefone: </strong>
        <br>
        <?php echo '('.$cidade->ddd.') '. $empresa->empresa_telefone; ?>
    </div>
    <br>
</div>
<?php 
if ( ! empty( $parametros->links_pagina_inicial ) ) : 
    ?>
<hr>
<div class="linha_link">
    <a href="<?php echo base_url();?>" target="_blank" >Ir para Home</a>
</div>
<br>
    <?php
endif;
?>