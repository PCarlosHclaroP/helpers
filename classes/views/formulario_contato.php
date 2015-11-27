<div id="formulario"> 
    <form name="form" action="<?php echo isset($url) ? $url : base_url().$_SERVER['PHP_SELF'];?>" method="post" onSubmit="return confirma()">
    <?php 
    if ( $modelo_detalhe == 4 ) :
        ?>
            
        <?php
        $array = array(
                        array('tipo' => 'text', 'name' => 'nome', 'form_group' => TRUE, 'label' => 'Nome Completo', 'classe' => '', 'valor' => FALSE, 'extras' => ''),
                        array('tipo' => 'text', 'name' => 'email', 'form_group' => TRUE, 'label' => 'E-mail', 'classe' => '', 'valor' => FALSE, 'extras' => ''),
                        array('tipo' => 'text', 'name' => 'telefone', 'form_group' => TRUE, 'label' => 'Telefone com DDD', 'classe' => 'telefone', 'valor' => FALSE, 'extras' => ''),
                        array('tipo' => 'text', 'name' => 'estado', 'form_group' => TRUE, 'label' => 'Estado', 'classe' => '', 'valor' => FALSE, 'extras' => ''),
                        array('tipo' => 'text', 'name' => 'f_cidade', 'form_group' => TRUE, 'label' => 'Cidade', 'classe' => '', 'valor' => FALSE, 'extras' => ''),
                        array('tipo' => 'textarea', 'name' => 'mensagem', 'form_group' => TRUE, 'label' => 'Texto', 'classe' => '', 'valor' => FALSE, 'extras' => ''),
                        array('tipo' => 'hidden', 'name' => 'captcha', 'form_group' => FALSE, 'label' => NULL, 'classe' => 'honeypot-captcha hide', 'valor' => FALSE, 'extras' => ''),
                        array('tipo' => 'hidden', 'name' => 'ok', 'form_group' => FALSE, 'label' => NULL, 'classe' => '', 'valor' => '1', 'extras' => ''),
                        array('tipo' => 'hidden', 'name' => 'id', 'form_group' => FALSE, 'label' => NULL, 'classe' => '', 'valor' => $id, 'extras' => ''),
                        array('tipo' => 'hidden', 'name' => 'id_imovel', 'form_group' => FALSE, 'label' => NULL, 'classe' => '', 'valor' => $id, 'extras' => ''),
                        array('tipo' => 'submit', 'name' => 'Enviar', 'form_group' => FALSE, 'label' => NULL, 'classe' => 'btn btn-success', 'valor' => NULL, 'extras' => ''),
                        );
        echo set_campo_itens($array);
        
    else:
        ?>
        <div>
            <h2>Pergunte sobre este imóvel</h2>
        </div>
        <div style="display: inline-block" class="form-grooup">
            <span class="texto"><br>Nome Completo:</span>
            <br>
            <input type="text" name="nome" value="<?php echo set_valor_formulario('nome');?>" size="20">
        </div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <div style="display: inline-block">
            <span class="texto"><br><br>Email:</span>
            <br>
            <input type="text" name="email" value="<?php echo set_valor_formulario('email');?>" size="20">
        </div>  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <div style="display:inline-block">
            <span class="texto">Telefone com DDD:</span>
            <br>
            <input type="text" name="telefone" value="<?php echo set_valor_formulario('telefone');?>" size="18">
        </div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <div style=" display:inline-block; margin-top: 10px;" >
            <span class="texto">Estado:</span>
            <br>
            <input type="text" name="estado" value="<?php echo set_valor_formulario('estado'); ?>" size="2">
        </div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <div style="display: inline-block; " >
            <span class="texto">Cidade:</span>
            <br>
            <input type="text" name="f_cidade" value="<?php echo set_valor_formulario('f_cidade'); ?>" size="20">
        </div>
        <div style="display: inline-block; " >
            <input type="hidden" name="assunto" value="Informações sobre o Imóvel: <?php echo $id;?>" >
            <span class="texto"><br>Texto:</span><br>
            <textarea name="mensagem" cols=111 rows=5><?php echo set_valor_formulario('mensagem'); ?></textarea>
            <div style=" float:right; margin-top:10px; text-align: right; width: auto;" class="esp-botao">
                    <input type="text" name="captcha" class="honeypot-captcha hide">
                    <br><br><br><button type="submit" class="botao" style=" width: 63px; height: 23px; background-image: url('<?php echo base_url();?>imagens/btn_enviar.jpg');">&nbsp;</button>
                    <input type="hidden" name="ok" value="1">
                    <input type="hidden" name="id" value="<?php echo $id;?>">
                    <input type="hidden" name="id_imovel" value="<?php echo $id;?>">
            </div>
        </div>
        <?php
    endif;
    
    
    ?>
        
    
        
    </form>
</div>