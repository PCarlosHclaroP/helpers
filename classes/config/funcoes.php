<?php
if ( strstr($_SERVER['HTTP_HOST'], 'localhost' ) )
{
	$define = 'www.mceinvestimentos.com.br';
}
else
{
	$define = $_SERVER['HTTP_HOST'];
}
define('WWW',$define);
define('BASE', getcwd().'/');

function removerCodigoMalicioso ($item = array())
{
	$retorno = NULL;
	if(isset($item) && $item)
	{
		foreach($item as $chave => $valor)
		{
			if(is_array($valor))
			{
				$retorno[$chave] = removerCodigoMalicioso($valor);
			}
			else
			{
				$valor = removerCodigoMalicioso_unico($valor);
				$retorno[$chave] = $valor;
			}
		}
	}
	return $retorno;
}
function removerCodigoMalicioso_unico ($item )
{
    if ( isset($item) )
    {
	$valor = addslashes($item);
	$valor = htmlspecialchars($valor);
	$valor = preg_replace(("/(from|FROM|select|SELECT|insert|INSERT|UPDATE|update|delete|DELETE|where|WHERE|DROP TABLE|drop table|SHOW TABLES|show tables|ALTER TABLE|alter table| AND | and | OR | or |SLEEP|sleep|\(|\)| \(| \)|DATABASE|database|#|\*|--|\\\\)/"),"",$valor);
	$valor = trim($valor);
	$retorno = $valor;
    }
    else
    {
        $retorno = NULL;
    }
	return $retorno;
}
function base_url()
{
	if ( strstr($_SERVER['HTTP_HOST'],'localhost') )
	{
		$retorno = 'http://localhost/powsites/';
	}
	else
	{
		$retorno = 'http://'.( strstr($_SERVER['HTTP_HOST'],'www') ? $_SERVER['HTTP_HOST'] : 'www.'.$_SERVER['HTTP_HOST'] ).'/';
	}
	return $retorno;
}
function base_cwd()
{
	if ( strstr($_SERVER['HTTP_HOST'],'localhost') )
	{
		$retorno = '/var/www/html/powsites';
	}
	else
	{
		$retorno = '/home/pow/www';
	}
	return $retorno;
}

function tira_acento ( $palavra )
{
	//$palavra = strtolower($palavra);
	//$array_a = array('  ', '   ', 'Á','á','à','À','é','É','í','Í','ì','ó','Ó','ú','Ú','â','Â','ê','Ê','ô','Ô','à','ã','Ã','õ','Õ','ü','ç','Ç','/','-','´','!', "'",'"','º','(',')','=','%','?',',','$', ' ','<','>','?','!','+++','++','+-+',':','...', '_','-','1','2','3','4','5','6','7','8','9','0 ','1 ','2 ','3 ','4 ','5 ','6 ','7 ','8 ','9 ','0 ');
	//$array_b = array('+' , '+'  , 'a','a','a','a','e','e','i','i','i','o','o','u','u','a','a','e','e','o','o','a','a','a','o','o','u','c','c','', '', '', '', '+','+','+','+','+','+','+','+','+','S', '+' ,'+','+','+','+', ''  ,''  , '', '','', '','','','','','','','','','','',' ','','','','','','','','','','');
	//$retorno = str_replace($array_a, $array_b, $palavra);
	//return $retorno;

	//$palavra = strtolower($palavra);
	$array_a = array('  ', '   ', 'Á','á','à','À','é','É','í','Í','ì','ó','Ó','ú','Ú','â','Â','ê','Ê','ô','Ô','à','ã','Ã','õ','Õ','ü','ç','Ç','/','-','´','!', "'",'"','º','(',')','=','%','?',',','$', ' ','<','>','?','!','+++','++','+-+',':','...', '_','?','.','#','0',',');
	$array_b = array('+' , '+'  , 'a','a','a','a','e','e','i','i','i','o','o','u','u','a','a','e','e','o','o','a','a','a','o','o','u','c','c','', '', '', '',  '', '', '', '', '', '', '', '', '', '',  '+', '','', '', '', ''  , ''  ,'',   '', ''   , '+','' ,'', '' ,'','+' );
	$retorno = str_replace($array_a, $array_b, $palavra);
	$sim = 0;
	for( $a = 0; $a <= 9; $a++ )
	{
		$e = stripos($retorno, strval($a) );
		if ( ( $e && $e < 2 ) || $e == 0 )
		{
			$sim = 1;
			$retorno = str_replace(strval($a), '', $retorno);
		}
	}

	if ( $sim )
	{
		for( $a = 0; $a <= 9; $a++ )
		{
			$e = stripos($retorno, strval($a) );
			if ( ( $e && $e < 2 ) || $e == 0 )
			{
				$retorno = str_replace(strval($a).'+', '', $retorno);
			}
		}
	}
	if ( substr($retorno, 0, 1) == '+' )
	{
		$retorno = substr($retorno, 1);
	}
	return strtolower($retorno);
}

function set_valor_formulario( $campo, $default = FALSE )
{
	if ( isset($_GET[$campo]) )
	{
            $retorno = removerCodigoMalicioso_unico( $_GET[$campo] );
	}
	elseif ( isset($_POST[$campo]) )
        {
            $retorno = removerCodigoMalicioso_unico( $_POST[$campo] );
            
        }
	else
	{
		$retorno = $default ? $default : '';
	}
	return $retorno;
}

/**
 * Monta campo de formulario com base em data['tipo']
 * @param string $data - pode ser unica ou um array de chave numerica
 * $data[0][tipo] ou
 * @param string $data[tipo] - tipo do input ou textarea
 * @param string $data[valor] - set_value, se não constar usar ->set_valor_formulario
 * @param string $data[label] - Titulo do Campo
 * @param string $data[form_group] - Se completo vai com form-group
 * @param string $data[classe] = classe adicional
 */
function set_campo_itens($data = array() )
{
    $retorno = '';
    if ( isset($data) && count($data) > 0 )
    {
        foreach ( $data as $itens )
        {
            $retorno .= set_campo($itens);
        }
    }
    return $retorno;
}

/**
 * Monta campo de formulario com base em data['tipo']
 * @param string $data[tipo] - tipo do input ou textarea
 * @param string $data[valor] - set_value, se não constar usar ->set_valor_formulario
 * @param string $data[label] - Titulo do Campo
 * @param string $data[name] - name do campo
 * @param string $data[form_group] - Se completo vai com form-group
 * @param string $data[classe] = classe adicional
 * @param string $data[extras] = campo adicional para o meio do input
 */
function set_campo ( $data = array() )
{
    if (isset($data['form_group']) && $data['form_group'] )
    {
        $form['abre'] = '<div class="form-group '.$data['name'].'">';
        if ( isset($data['label']) )
        {
            $form['abre'] .= '<label for="">'.$data['label'].'</label>';
        }
        $form['fecha'] = '<p class="help-block"></p>';
        $form['fecha'] .= '</div>';
    }
    else
    {
        $form['abre'] = '<div style="display: inline-block; " >';
        if ( isset($data['label']) )
        {
            $form['abre'] .= '<span class="texto">'.$data['label'].':</span><br>';
        }
        $form['fecha'] = '</div>';
        
    }
    switch ( $data['tipo'] )
    {
        case 'text':
        case 'hidden':
        case 'radio':
        case 'checkbox':
            $input = '<input '.( isset($data['extras']) ? $data['extras'] : '').' type="'.$data['tipo'].'" name="'.$data['name'].'" class="'.(isset($data['classe']) ? $data['classe']  : '').' '.( isset($data['form_group']) && $data['form_group'] ? 'form-control' : '' ).'" id="'.$data['name'].'" value="'.set_valor_formulario($data['name'], (isset($data['valor']) ? $data['valor'] : FALSE)).'" >';
            break;
        case 'select':
            $input = '<select '.( isset($data['extras']) ? $data['extras'] : '').' name="'.$data['name'].'" class="'.(isset($data['classe']) ? $data['classe']  : '').' '.( isset($data['form_group']) && $data['form_group'] ? 'form-control' : '' ).'" id="'.$data['name'].'" >';
            $input .= '<option value="0">Selecione</option>';
            if ( isset($data['itens']) && count($data['itens']) > 0 ) 
            {
                foreach ($data['itens'] as $item )
                {
                    $input .= '<option value="'.$item->id.'">'.$item->descricao.'</option>';
                }
            }
            $input .= '</select>';
            break;
        case 'textarea':
            $input = '<textarea '.( isset($data['extras']) ? $data['extras'] : '').' name="'.$data['name'].'" id="'.$data['name'].'" class="'.(isset($data['classe']) ? $data['classe']  : '').' '.( isset($data['form_group']) && $data['form_group'] ? 'form-control' : '' ).'">'.set_valor_formulario($data['name'], (isset($data['valor']) ? $data['valor'] : FALSE)).'</textarea>';
            break;
        case 'submit':
            $input = '<button type="submit" id="'.$data['name'].'" class="'.(isset($data['classe']) ? $data['classe']  : '').'">'.$data['name'].'</button>';
            break;
    }
    $retorno = $form['abre'];
    $retorno .= $input;
    $retorno .= $form['fecha'];
    return $retorno;
}

function set_assinatura()
{
	$retorno = '<div align="right" ><a href="http://www.sitesimobiliarios.com" target="_blank"><img src="'.base_url().'powsites/imagens/im_hot_pow3.gif" width="90" height="20" border="0" title="Conheça a ferramenta do POW internet para criação de sites imobiliários" alt="Conheça a ferramenta do POW internet para criação de sites imobiliários" /></a></div>';
	return $retorno;
}

/**
 * Tratamento para imagens de imovel
 * @param string $image endereço da imagem
 * @param string $id - id do imovel
 * @param array $empresa - array da empresa
 * @param string $width
 * @param string $height
 * @return string
 * to-do - para qualquer tipo de site, imovel, comerce, se
 */
function trata_image( $image, $id, $empresa, $width = 250, $height = 200, $sequencia = FALSE )
{
    $tem_http = strstr($image,'http://') ? TRUE : FALSE;
    $pasta_image_cwd = base_cwd().'/powsites/'.$empresa['id'].'/imo/';
    $pasta_image = base_url().'powsites/'.$empresa['id'].'/imo/';
    if ( ! is_dir($pasta_image_cwd) )
    {
        mkdir( $pasta_image_cwd, 777, TRUE );
    }
    $local_image = ( $tem_http ? $image : $pasta_image_cwd.$image );
    $propriedades_image = getimagesize($local_image);
    $nome_arquivo_modificado = 'especial_'.$id.'_'.( $sequencia ? $sequencia.'_' : '').$width.'_'.$height.get_extensao($propriedades_image);
    if ( file_exists( $pasta_image_cwd.$nome_arquivo_modificado ) )
    {
        $retorno =  $pasta_image.$nome_arquivo_modificado;
    }
    else
    {
        if ( file_exists($local_image) || isset($propriedades_image) )
        {
            $endereco_image = ( $tem_http ? $image : base_url().'powsites/'.$empresa['id'].'/imo/'.$image );
            $gerou = gera_image($endereco_image, $pasta_image_cwd.$nome_arquivo_modificado, $propriedades_image, $width, $height);
            if ( $gerou )
            {
                    $retorno = $pasta_image.$nome_arquivo_modificado;
            }
            else
            {
                    $retorno = $endereco_image;
            }
        }
        else
        {
                $retorno = base_url().'imagens/imagem_nao_disponivel.png';
        }

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

function gera_image( $local, $arquivo, $propriedades, $width, $height )
{
    $altura = $propriedades[0];
    
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
    $width_fator = ( $propriedades[0] - $width ) / 2;
    $height_fator = ($propriedades[1] - $height) / 2;
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

/**
 * Função para enviar emails genericos utilizando o phpmailer, loada, carrega, e dispara
 * @param array $data, são obrigatorios:
 * data['assunto']
 * data['conteudo']
 * data['to']
 * data['nome']
 * data['from']
 * @return boolean 
 */
function envia_email( $data )
{
    require(base_cwd()."/phpmailer/class.phpmailer.php");
    $mail = new PHPMailer();
    $mail->IsSMTP();

    $mail->Host = 'smtp.pow.com.br';
    $mail->Port = 587;
    $mail->Username = 'siteenvia@pow.com.br';
    $mail->Password = '150oA71.';
    $mail->SMTPAuth = true;

    $mail->From = isset($data['from']) ? $data['from'] : "siteenvia@pow.com.br";    // quem envia
    $mail->ReplyTo = isset($data['from']) ? $data['from'] : "siteenvia@pow.com.br";
    $mail->FromName =  isset( $data['nome'] ) ? $data['nome'] : '';               // nome de quem envia
    $mail->AddAddress($data["to"], $data['nome'] );
    $mail->WordWrap = 80;                   // quebrar linhas com mais de  50 characters
    $mail->IsHTML(TRUE);                    // true se  formato HTML e false caso contrário
    $mail->Subject = $data['assunto'];       // Assunto do email
			// Corpo do email
    $mail->Body    = $data['conteudo'];
    // enviar
    if( ! $mail->Send() )
    {
        $retorno = FALSE;
        /*
	   echo "Message could not be sent. <p>";
	   echo "Mailer Error: " . $mail->ErrorInfo;
	   exit;
         * 
         */
    }
    else
    {
        $retorno = TRUE;
    }
    return $retorno;
}

/**
 * Função para administrar os uploads, excessoes devem ser tratadas na array e inseridas com documentação anexa.
 * A implementação desta função é inspirada, modificada e retornará os varlores necessarios na class formulario_controller função upload
 * @param array $data
 * $data['post'] contem as informações sobre o arquivo, pasta, tamanho maximo, type 
 * @since 2015-11-14 13:00
 * @author Carlos Claro < programacao@pow.com.br>
 * @return array com erros e acertos para montar os espaços de resposta...
 */
include_once base_cwd().'/classes/model/arquivos_model.php';
function upload($data)
{
    $arquivos_model = new Arquivos_model();
    $post = $data['post'];
    $images = $data['images'];
    $post['id'] = isset($post['id_imovel']) ? $post['id_imovel'] : $post['id_empresa'];
    if ( isset( $images['name'] ) && count( $images['name'] ) > 0 )
    {
        $retorno['status']['erro'] = FALSE;
        $complemento['type'] = explode('|',$post['type']);
        $complemento['tipo'] = $arquivos_model->get_item_tipo_por_tipo( $post['tipo'] );
        $complemento['pasta'] = base_cwd().'/'.str_replace('[id]', $post['id_empresa'], $complemento['tipo']->pasta);
        if ( ! is_dir( $complemento['pasta'] ) )
        {
            mkdir( $complemento['pasta'], 0777, TRUE );
        }
        
        if ( is_array( $images['name'] ) )
        {
            $qtde_upload = count( $images['name'] );
            for( $a = 0; $a < $qtde_upload; $a++ )
            {
                $retorno['arquivo'][$a] = processa_arquivo($images['name'][$a], $images['type'][$a], $images['tmp_name'][$a], $images['size'][$a], $post, $complemento);
            }
                    
        }
        else
        {
            $retorno['arquivo'] = processa_arquivo($images['name'], $images['type'], $images['tmp_name'], $images['size'], $post, $complemento);
        }
    }
    else
    {
        $retorno['status']['erro'] = TRUE;
        $retorno['status']['message'] = 'Nenhum arquivo válido selecionado, tente novamente.';
    }
    return $retorno;

}

function processa_arquivo( $name, $type, $tmp_name, $size, $post, $complemento )
{
    $arquivos_model = new Arquivos_model();
    if ( strstr( $type, '/' ) )
    {
        $e_type = explode( '/', $type );
        $type_compare = $e_type[1];
    }
    else
    {
        if ( ! empty( $type ) )
        {
            $type_compare = $type;
        }
        else
        {
            $type_compare = substr($name, -4);
            $type_compare = str_replace('.', '', $type_compare);
        }
    }
    $extensao = substr($name, -4);
    $extensao = str_replace('.', '', $extensao);
    if ( in_array($type_compare,$complemento['type'])  )
    {
        if ( $size <= $post['limite_kb'] )
        {
            $arquivo_nome = md5( $name.time() ).'.'.strtolower($extensao);
            $arquivo_dir = $complemento['pasta'].$arquivo_nome;
            if ( move_uploaded_file( $tmp_name, $arquivo_dir ) )
            {
                $insert_arquivo = array('arquivo' => $arquivo_nome, 'id_empresa' => $post['id_empresa'], 'data' => date('Y-m-d H:i') );
                $id_arquivo = $arquivos_model->adicionar_image_arquivo($insert_arquivo);
                $insert_pai = array( 'id_image_tipo' => $complemento['tipo']->id, 'id_image_arquivo' => $id_arquivo, 'id_pai' => $post['id'], 'titulo' => $name );
                $id_relacao = $arquivos_model->adicionar_image_pai($insert_pai);
                $retorno = array('erro' => FALSE, 'arquivo' => $arquivo_nome, 'caminho' => $arquivo_dir, 'id_empresa' => $post['id_empresa'], 'id_arquivo' => $id_arquivo, 'id_relacao' => $id_relacao );

            }
            else
            {
                $retorno = array('erro' => TRUE, 'mensagem' => 'Problemas no Upload do arquivo.' );
            }
        }
        else
        {
            $retorno = array('erro' => TRUE, 'mensagem' => 'O arquivo deve ter no maximo: '.$post['limite_kb'].' kb' );
        }
    }
    else
    {
        $retorno = array('erro' => TRUE, 'mensagem' => 'O arquivo '.$name.' deve utilizar os seguintes formatos: '.implode(', ',$complemento['type']) );
    }
    return $retorno;
}