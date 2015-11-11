<?php
function exporta_excel($data, $name)
{
	function cleanData(&$str) 
	{ 
		$str = preg_replace("/\t/", "\\t", $str); 
		$str = preg_replace("/\r?\n/", "\\n", $str);
		$str = html_entity_decode($str,ENT_QUOTES,'UTF-8');
		$str = utf8_decode($str);
		$str = strip_tags($str);
		
		if(strstr($str, '"')) 
		{
			$str = '"' . str_replace('"', '""', $str) . '"';
		} 
		
	}	
	header("Content-Disposition: attachment; filename=".$name); 
	header("Content-Type: application/vnd.ms-excel");

	$flag = false; 
	
	foreach($data['itens'] as $row) 
	{
		$row_alt = (array)$row;
		if(!$flag) 
		{
			$cabecalho =  implode("\t", array_keys($row_alt)) . "\r\n";
			echo $cabecalho; 
			$flag = true;
		} 
		array_walk($row_alt, 'cleanData'); 
		$texto = implode("\t", array_values($row_alt)) . "\r\n";
		echo $texto;		 
	} 
	exit;
}
if ( ! function_exists('form_select'))
{
	function form_select( $item, $selecionado = '', $selecione = 1 )
	{
		$campo  = '<select name="'.$item['nome'].'" id="'.$item['nome'].'" '.$item['extra'].' '.(!empty($item['disabled']) ? 'disabled="disabled"' : '').'>'.PHP_EOL;
		
                $campo .= isset($selecione) ? '<option value="">Selecione...</option>'.PHP_EOL : '';
		
                foreach ($item['valor'] as $opcao)
		{
			$campo .= '<option value="'.$opcao->id.'"'.(($opcao->id == $selecionado) ? ' SELECTED="SELECTED" ' : '').' title="'.$opcao->descricao.'">';
			$campo .= $opcao->descricao;
			$campo .= '</option>'.PHP_EOL;
		}
		$campo .= '</select>'.PHP_EOL;
		return $campo;
	}
}

if ( ! function_exists('form_checkbox_'))
{
	function form_checkbox_($item, $selecionado)
	{
		$campo = '<div style="width:250px;"><table class="table"><tbody>';
		foreach ($item['valor'] as $opcao)
		{
			$campo .= '<tr><td width="30"><input name="'.$item['nome'].'['.$opcao->id.']" id="'.$item['nome'].'" '.((array_key_exists($opcao->id,$selecionado))? 'checked="checked"' : '').' '.$item['extra'].' type="checkbox" value="'.$opcao->id.'"  ></td>';
			$campo .= '<td><label>'.$opcao->descricao.'</label></td></tr>'.PHP_EOL; 
		}
		$campo .= '</tbody></table></div>';
		return $campo;
	}
}

function form_selecionavel($item, $selecionado = NULL, $link = TRUE, $valor = TRUE)
{
    $retorno = '<div class="list-group">';
    foreach( $item['valor'] as $data )
    {
        if ( isset($data->qtde) && $data->qtde > 0 )
        {
            $retorno .= '<a rel="nofollow" tabindex="1"';
            $retorno .= ( ( $link && isset($data->link) ) ? 'href="' . $data->link . '"' : ''  );
            $retorno .= ' class="list-group-item '.$item['link'].' col-lg-4 col-sm-6 col-md-6 col-xs-12 '.( isset($selecionado) && ($data->id == $selecionado ) ? 'active' : '' ).'" data-item="' . $data->id . '" >';
            $retorno .= '<p class="list-group-item-text">' . $data->descricao . '</p> ';
            $retorno .= (isset($data->qtde) && $valor) ? '<span class="badge pull-right">' . $data->qtde . '</span>' : '';
            $retorno .= '</a>';
        }
    }
    $retorno .= '</div>';
    return $retorno;
}

function converte_data_mysql($data)
{
	$data_explode = explode(' ', $data);
	$data_i = explode('/',$data_explode[0]);
	$data = $data_i[2].'-'.$data_i[1].'-'.$data_i[0].' '.$data_explode[1];
	return $data;
}
function tira_especiais ( $palavra )
{
    $palavra = strtolower($palavra);
    $array_a = array('Á','á','à','À','é','É','í','Í','ì','ó','Ó','ú','Ú','â','Â','ê','Ê','ô','Ô','à','ã','Ã','õ','Õ','ü','ç','Ç','/','-','´','!', "'",'"','º','(',')','+','=','%','�',',','$');		
    $array_b = array('a','a','a','a','e','e','i','i','i','o','o','u','u','a','a','e','e','o','o','a','a','a','o','o','u','c','c','_','_','_','_','_','_','_','_','_','_','_','_','_','_','S');		
    $retorno = str_replace($array_a, $array_b, $palavra);	
    return $retorno;
}

function tira_acento ( $palavra )	
{		
    
    $palavra = strtolower($palavra);
    $array_a = array('Á','á','à','À','é','É','í','Í','ì','ó','Ó','ú','Ú','â','Â','ê','Ê','ô','Ô','à','ã','Ã','õ','Õ','ü','ç','Ç','  ',' ','/','-','´', "'",'"','º','(',')','+','=','%','�',',');		
    $array_b = array('a','a','a','a','e','e','i','i','i','o','o','u','u','a','a','e','e','o','o','a','a','a','o','o','u','c','c','_','_','_','_','_', '_','_','' ,'' ,'' ,'' ,'','','',''  );		
    $retorno = str_replace($array_a, $array_b, $palavra);	
    return $retorno; 	
     
} 

function monta_info ( $id )
{
    $sql = ' SELECT 
                    imoveis.id as id_imovel, 
                    imoveis.nome as nome,
                    IF ( imoveis.venda = 1, "venda" , IF ( imoveis.locacao = 1 , "locacao", "locacao_dia" )   ) as tipo_link,
                    bairros.link as bairro_link,
                    imoveis_tipos.link as tipo_imovel_link,
                    cidades.link as cidade_link
            FROM
                    imoveis
                    INNER JOIN empresas ON imoveis.id_empresa = empresas.id AND empresas.pagina_visivel = 1
                    LEFT JOIN imoveis_tipos ON imoveis.id_tipo = imoveis_tipos.id
                    LEFT JOIN bairros ON imoveis.bairro_combo = bairros.id
                    INNER JOIN cidades ON cidades.id = bairros.cidade
            WHERE
                    imoveis.id = '.$id;
    
    
    $i = retorna_object($sql);
    $info = isset( $i['item'][0] ) ? $i['item'][0] : array();
    
    $retorno = '$amp;info=';
    $retorno .= (isset($info->tipo_link) ? $info->tipo_link : '' ).'_'.( isset($info->tipo_imovel_link ) ? $info->tipo_imovel_link : '').'_'.( isset($info->cidade_link) ? $info->cidade_link : '' ).'_'.( isset($info->bairro_link) ? $info->bairro_link : '' );
    return $retorno;
}

function retorna_object($sql, $debug = FALSE)
{
    if ( empty( $sql ) )
    {
        
        $retorno = FALSE;
    }
    else
    {
        $result = mysql_query($sql);
        if( $result && (mysql_num_rows($result)>0) )
        {
            $linhas['qtde'] = mysql_num_rows($result);
            while($row = mysql_fetch_assoc($result))
            {	
                $linhas['item'][] = (object)$row;
            }
            mysql_free_result($result);
            $retorno = $linhas;
        }
        else
        {
            //
            $retorno = FALSE;
        }
    }
    if ( $debug )
    {
        var_dump($sql);
        var_dump($retorno);
        
        var_dump( mysql_error() );
    }
    
    return $retorno;
}

function set_modal_checklist( $item )
{
    $retorno = '';
    $retorno .= '<div class="modal modal-'.$item['name'].' fade" id="modal-'.$item['name'].'" tabindex="-1" role="dialog" aria-labelledby="myModalLabel-'.$item['name'].'" aria-hidden="true">';
    $retorno .= '<div class="modal-dialog">';
    $retorno .= '<div class="modal-content">';
    $retorno .= '<div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button><p>Escolha '.$item['titulo'].':</p><span class="area-selecao"></span></div>';
    $retorno .= '<div class="modal-body container">';
    $retorno .= set_itens_modal($item['valor'], $item['name']);
    $retorno .= '</div><!-- .modal-body -->';   
    $retorno .= '<div class="modal-footer">';
    $retorno .= '<button type="button" class="btn btn-default" data-dismiss="modal">'.( isset($item['legenda_fecha']) ? $item['legenda_fecha'] : 'Fechar' ).'</button>';
    $retorno .= '</div><!-- .modal-footer -->';
    $retorno .= '</div>';
    $retorno .= '</div><!-- .modal-dialog -->';
    $retorno .= '</div><!-- .modal -->';
       
    
    return $retorno;
}

function set_itens_modal ( $itens = array(), $nome = NULL )
{
    $retorno = '';
    if ( count($itens) > 0  )
    {
        $retorno .= '<ul class="list-inline">';
        foreach($itens as $item)
        {
            
            $retorno .= '<li class="btn btn-default col-lg-4 col-sm-6 col-md-6 col-xs-12" data-item="'.$item->id.'" data-descricao="'.$item->descricao.'">'.$item->descricao.'</li>';
        }
        $retorno .= '</ul>';
    }
    return $retorno;
}

function set_modal_contato( )
{
    $retorno = '';
    $retorno .= '<div class="modal modal-contato fade container" id="modal-contato" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">';
    $retorno .= '<div class="modal-dialog">';
    $retorno .= '<div class="modal-content">';
    $retorno .= '<div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button><p>Contato: </p><span class="titulo-contato"></span></div>';
    $retorno .= '<div class="modal-body container">';
    $retorno .= '<div class="formulario">';
    $retorno .= '<p>Entre em contato com: <span class="titulo-contato"></span></p>';
    $retorno .= '<form role="form" method="post" action="'.base_url().'index/email_empresa">';
    $retorno .= '<input type="hidden" name="id_empresa" class="id_empresa" value="">';
    $retorno .= '<input type="hidden" name="local" class="local" value="4n">';
    $retorno .= '<input type="hidden" name="assunto" value="Via lista de imobiliárias">';
    $retorno .= '<input type="hidden" name="validador" value="">';
    $retorno .= '<input type="hidden" name="redirect" class="redirect" value="'.base_url().'imobiliarias">';
    $retorno .= '<div class="form-group"><input type="text" name="remetente[nome]" class="form-control" placeholder="Nome Completo" required></div>';
    $retorno .= '<div class="form-group"><input type="email" name="remetente[email]" class="form-control" placeholder="E-mail de Contato" required></div>';
    $retorno .= '<div class="form-group"><input type="text" name="remetente[fone]" class="form-control telefone" placeholder="Telefone 41 1111 1111 " required></div>';
    $retorno .= '<div class="form-group"><textarea name="mensagem" class="form-control" placeholder="Escreva sua mensagem"></textarea></div>';
    $retorno .= '<div class="form-group"><label><input type="checkbox" name="aceito" value="1" checked> Gostaria de receber noticias e novidades da Rede Portais Imobiliários</label></div>';
    $retorno .= '<div class="form-group"><button class="btn btn-primary" type="submit"> Enviar Contato </button></div>';
    $retorno .= '</form>';
    $retorno .= '</div><!-- .formulario -->';
    $retorno .= '</div><!-- .modal-body -->';   
    $retorno .= '<div class="modal-footer">';
    $retorno .= '<button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>';
    $retorno .= '</div><!-- .modal-footer -->';
    $retorno .= '</div>';
    $retorno .= '</div><!-- .modal-dialog -->';
    $retorno .= '</div><!-- .modal -->';
       
    
    return $retorno;
}

/**
 * retorna a imagem para exibição
 * @since 1.0 06/01/2014
 * @param int $id -> id do imóvel
 * @param string $arquivo -> arquivo a ser modificado
 * @param int $id_empresa -> codigo da empresa
 * @param bolean $mudou -> se a empresa já mudou de repositorio
 * @param string $fs -> 32bits md5 do arquivo
 * @param int $sequencia -> sequencia do arquivo - default 1
 * @param string $tipo -> tipo do arquivo, tm, t5, t3
 * @return string -> endereço completo do arquivo.
 */
function set_arquivo_image( $id, $arquivo, $id_empresa, $mudou = FALSE, $fs = '', $sequencia = 1, $tipo = 'TM')
{
    
    // 120 x 90 -> TM -> só faz da foto 1
    // 60 x 45 -> t3 -> faz todas
    // 300 -> T5_codimovel_numerodafoto -> faz todas
    // md5_file da original
    $array_tamanho = array(
                            'TM' => array('width' => '240', 'height' => '180', 'crop' => FALSE),
                            'T3' => array('width' => '120', 'height' => '90', 'crop' => FALSE),
                            'destaque' => array('width' => '235', 'height' => 'auto', 'crop' => FALSE),
                            'destaque_home' => array('width' => '208', 'height' => '160', 'crop' => FALSE),
                            'T5' => array('width' => '650', 'height' => 'auto', 'crop' => FALSE),
                            '650_F' => array('width' => '900', 'height' => 'auto', 'crop' => FALSE),
                            );
    $nao_tem_sequencia = ( $tipo == 'TM' || $tipo == 'destaque' || $tipo == 'destaque_home' ) ? TRUE : FALSE;
    if (strstr( $arquivo, 'http' ) )
    {
        $pasta_local = getcwd().str_replace('codEmpresa', $id_empresa, URL_INTEGRACAO_LOCAL);
        $nome_arquivo = $tipo.'_'.$id.( $nao_tem_sequencia ? '' : '_'.$sequencia ).'.';
        $nome_arquivo .= str_replace('.','',substr($arquivo, -4)); 
        $existe = $pasta_local.$nome_arquivo;
        //var_dump($existe, $nome_arquivo);
        if (file_exists($existe))
        {
            $a = base_url().str_replace('codEmpresa', $id_empresa, substr(URL_INTEGRACAO_LOCAL, 1)).$nome_arquivo;
        }
        else
        {
            if ( empty($fs)  )
            {
                if ( ! is_dir($pasta_local) )
                {
                    mkdir( $pasta_local, 0777, TRUE );
                }
                $propriedades_image = getimagesize($arquivo);
                $tamanho = $array_tamanho[$tipo];
                $gerou = gera_image($arquivo, $pasta_local.$nome_arquivo, $propriedades_image, $tamanho['width'], $tamanho['height'], $tamanho['crop']);
                if ( $gerou )
                {
                    $a = base_url().str_replace('codEmpresa', $id_empresa, substr(URL_INTEGRACAO_LOCAL, 1)).$nome_arquivo;
                }
                else
                {
                    $a = $arquivo;
                }
                
            }
            else
            {
                $a = str_replace('codEmpresa', $id_empresa, URL_INTEGRACAO);
                $a .= $tipo.'_'.$id.( $nao_tem_sequencia ? '' : '_'.$sequencia ).'.';
                $a .= str_replace('.','',substr($arquivo, -4)); 
            }
        }
    }
    else
    {
        if ( $tipo == '650F_F' && $mudou == 1 )
        {
            $a = ( ( $mudou == 1 ) ? str_replace('codEmpresa', $id_empresa, URL_IMAGE_MUDOU) : URL_IMAGE_NAO_MUDOU);
            $a .= $tipo.'_'.$id.( $nao_tem_sequencia ? '' : '_'.$sequencia ).'.';
            $a .= str_replace('.','',substr($arquivo, -4));
        }
        else
        {
            $a = ( ( $mudou == 1 ) ? str_replace('codEmpresa', $id_empresa, URL_IMAGE_MUDOU) : URL_IMAGE_NAO_MUDOU);
            $a .= $arquivo;
        }
    }
    return $a;
}
        
function set_extensao_arquivo( $mime )
{
    switch ($mime)
    {
        case 'image/png':
            $retorno = '.png';
            break;
        case 'image/jpeg':
        case 'image/jpg':
            $retorno = '.jpg';
            break;
        case 'image/gif':
            $retorno = '.gif';
            break;
        case 'image/bmp':
            $retorno = '.bmp';
            break;
    }
    return $retorno;
}


function get_extensao( $propriedade )
{
    switch($propriedade["mime"]){
            case "image/gif":
                    $retorno = '.gif';
                    break;
            case "image/png":
                    $retorno = '.png';
                    break;
            case "image/jpeg":
            default:
                    $retorno = '.jpg';
                    break;
    }
    return $retorno;
}

function gera_image( $local, $arquivo, $propriedades, $width, $height, $crop = FALSE )
{
    
    $altura = $propriedades[0];
    if ( $height == 'auto' )
    {
        $height = ( $propriedades[0] > $width ) ? ( $propriedades[1] / ( $propriedades[0] / $width ) ) : ( $propriedades[1] / ( $width / $propriedades[0] ) ) ;
    }
    //var_dump($height, $propriedades, $width); die();
    if ( $propriedades[0] > $propriedades[1] )
    {
        $proporcao = ceil($propriedades[0] / $width );
    }
    else
    {
        $proporcao = ceil($propriedades[1] / $height );
    }
    $width_src = $propriedades[0];
    $height_src = $propriedades[1];
    $width_fator = $crop ? ( ( $propriedades[0] - $width ) / 2 ) : 0;
    $height_fator = $crop ? ( ($propriedades[1] - $height) / 2 ) : 0;
    $image_destino = ImageCreateTrueColor($width,$height);
    switch( $propriedades['mime']){
            case 'image/gif':
                    $image = ImageCreateFromgif($local);
                    break;
            case 'image/png':
                    $image = ImageCreateFrompng($local);
                    break;
            case 'image/jpeg':
            default:
                    $image = ImageCreateFromjpeg($local);
                    break;
    }
    if ( $propriedades[1] < $height )
    {
        $height_fator = 0;
        $height_src = ($propriedades[1] < $height ? $height : $propriedades[1]);
    }
    if ( $proporcao > 3 )
    {
        $width_src = $propriedades[0] / 2;
        $height_src = $propriedades[1] / 2;
        $width_fator = ( $width_src - $width ) / 2;
        $height_fator = ( $height_src - $height ) / 2;
    }
    if ( ! $crop )
    {
        $width_src = $width;
        $height_src = $height;
        $width_fator = 0;
        $height_fator = 0;
    }
    //var_dump($height, $height_fator, $propriedades[1], $proporcao);
    //die();
    imagecopyresampled($image_destino,$image,0,0,$width_fator,$height_fator,$width_src,$height_src,$propriedades[0],$propriedades[1]);
    $arq = fopen($arquivo,'w');
    fclose($arq);
    switch($propriedades['mime']){
            case 'image/gif':
                    imagegif($image_destino, $arquivo);
                    break;
            case 'image/png':
                    imagepng($image_destino, $arquivo);
                    break;
            case 'image/jpeg':
            default:
                    imagejpeg($image_destino, $arquivo,100);
                    break;
    }
    if ( file_exists($arquivo) )
    {
        $retorno = TRUE;
    }
    else
    {
        $retorno = FALSE;
    }
    return $retorno;
}

function set_embed_video( $video )
{
    $existe_watch = strstr($video, 'v=' );
    if ( $existe_watch )
    {
        $v = explode('v=', $video);
        $existe_feature = strstr($v[1], 'feature');
        if ( $existe_feature )
        {
            $vi = explode('&', $v[1]);
            $retorno = $vi[0];
        }
        else
        {
            $retorno = $v[1];
        }
    }
    else
    {
        if (strstr($video, '.be/') )
        {
            $v = explode('.be/', $video);
            $retorno = $v[1];
        }
        else
        {
            $retorno = '';
        }
    }
    return '//www.youtube.com/embed/'.$retorno;
}

function tira_http( $texto )
{
    
}

function set_valor_descrito( $valor )
{
    $valor = intval($valor);
    $a = strlen($valor);
    $b = floor( ($a - 1) / 3);
    $c = $a - ( $b * 3 );
    $d = $c;
    switch($b)
    {
        case 0:
        default:
            $sufixo = '';
            break;
        case 1:
            $sufixo = 'Mil';
            break;
        case 2:
            $sufixo = ' Milhões';
            $c = $c + 2;
            break;
        case 3:
            $sufixo = ' Bilhões';
            $c = $c + 2;
            break;
        case 4:
            $sufixo = ' Trilhões';
            $c = $c + 2;
            break;
        }
        $decimais = substr($valor, 0, $c);
        if ( strlen($sufixo) > 3 )
        {
            $decimais = substr($decimais, 0, $d).','.substr($decimais, $d, 2);
        }
        else
        {
            if ( strlen($valor) == 4 )
            {
                $decimais = substr($decimais, 0, $d).','.substr($valor, $d, 2);
            }
        }
        $retorno = $decimais . ' ' . $sufixo;
        return $retorno;
}