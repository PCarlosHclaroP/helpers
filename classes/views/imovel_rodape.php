<div id="rodape">
	<div id="menurodape" class="menu_rodape" align=center>
		<?php 
		if( $carrinho ) :
			?>
			<a href="<?php echo base_url().$_SERVER['PHP_SELF'];?>?id_imovel=<?php echo $id;?>&amp;remsel=<?echo $id;?>">
			<img src="<?php echo base_url();?>imagens/remover.jpg" width="25" height="25" border="0" title="" alt="" />Remover seleção</a>  
			<?php 
		else:
			?>
			<a href="<?php echo base_url().$_SERVER['PHP_SELF'];?>?id=<?php echo $id;?>&amp;id_imovel=<?php echo $id;?>&amp;addcart=1">
			<img src="<?php echo base_url();?>imagens/selecionar.jpg" width="25" height="25" border="0" title="" alt="" />Selecionar </a> 
			<?php 
		endif;
		?>
		<a href="<?php echo base_url();?>imoveis_imprime_ficha.php?id_imovel=<?php echo $id;?>" target="_blank">
			<img src="<?php echo base_url();?>imagens/impressora.jpg" width="25" height="25" border="0" title="" alt="" />
			Imprimir
		</a>
		<a href="<?php echo base_url();?>imoveis_popdetalhes_envio.php?id_imovel=<?php echo $id;?>">
			<img src="<?php echo base_url();?>imagens/enviar_email.jpg" width="25" height="25" border="0" title="" alt="" />
			Enviar por email
		</a>  
        <a href="javascript:self.close();">
			<img src="<?php echo base_url();?>imagens/fechar.jpg" width="25" height="25" border="0" title="" alt="" />
			Fechar
		</a>
		</div>
		<div align="center" class="texto">
			  Os dados, inclusive preços, deste imóvel poderão sofrer alterações sem aviso prévio.
        	<br>
        	Im&oacute;vel atualizado em <?php echo date("d/m/Y H:i:s",$data_atualizacao);?>, por <?php echo $empresa_nome_fantasia;?>. 
		</div>
	</div>
</div>