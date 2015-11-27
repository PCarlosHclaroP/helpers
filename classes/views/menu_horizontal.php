<table style="width: <?php echo $largura;?> text-align: right;">
	<tr>
		<td width="340"> 
        	<font face="Verdana" size="3" color="<?php echo $params['cor_fantasia_mh'];?>"><b>	<?php echo $empresa['empresa_nome_fantasia'];?> </b>   </font>  
        </td>
        <td width="70" align="right"> 
			<a href="<?php echo base_url();?>hotsite.php?id=<?php echo $params['id_empresa'];?>&amp;servico=menu_tipos" >
				<font face="Verdana" size="2" color="<?php echo $params['cor_texto_mh'];?>">Início</font>  
			</a>
		</td>
        <td  width="72" align="right"> 
			<a href="<?php echo base_url();?>hotsite.php?id=<?php echo $params['id_empresa'];?>&amp;servico=buscadestaques" >
				<font face="Verdana" size="2" color="<?php echo $params['cor_texto_mh'];?>">Busca</font>  
			</a>
		</td>
        <td width="146"  align="right"> 
        	<a href="<?php echo base_url();?>hotsite.php?id=<?php echo $params['id_empresa'];?>&amp;servico=busca&amp;avancada=1" >
				<font face="Verdana" size="2" color="<?php echo $params['cor_texto_mh'];?>">Busca Avançada</font>  
			</a>
    	</td>
		<td width="84" align="right"> 
        	<a href="<?php echo base_url();?>hotsite.php?id=<?php echo $params['id_empresa'];?>&amp;servico=contato" >
				<font face="Verdana" size="2" color="<?php echo $params['cor_texto_mh'];?>">Contato </font>
	    	</a>
		</td>
	    <td width="50" align="right"> 
	    </td>
 	</tr>
    <tr>
		<td > 
	    	<font face="Verdana" size="1" color="<?php echo $params['cor_fantasia_mh'];?>"><b>	Creci:<?php echo $creci;?> </b>   </font>  
		  			</td>
				</tr>
      		</table>
		</td>
	</tr>
	