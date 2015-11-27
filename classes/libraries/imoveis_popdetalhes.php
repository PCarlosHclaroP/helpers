<?php
include_once base_cwd().'/classes/libraries/views.php';
include_once base_cwd().'/classes/db.php';
class Imoveis_popdetalhes extends DB
{
    public $id_imovel = FALSE;
    public $imovel = FALSE;
    private $tipo_imovel = FALSE;
    public $empresa = FALSE;
    public $parametros = FALSE;
    private $logradouro = FALSE;
    private $cidade = FALSE;
    private $bairro = FALSE;
    private $views = NULL;
    
    public function __construct( $id_imovel = FALSE, $dados_imovel = NULL, $parametros = NULL, $dados_empresa = NULL  ) 
    {
        parent::__construct(TRUE);
        $this->views = new Views();
        $this->id_imovel = $id_imovel;
        $this->imovel = $this->set_imovel($dados_imovel);
        $this->tipo_imovel = $this->set_tipo_por_id($this->imovel->id_tipo);
        $this->empresa = $this->set_empresa($dados_empresa);
        $this->parametros = $this->set_parametros($parametros);
        $this->bairro = $this->set_bairro_por_id($this->imovel->bairro_combo);
        $this->set_pageviews();
        return $this;
    }
    
    public function set_pageviews()
    {
        $data_tem = array(
                            'coluna' => '*',
                            'tabela' => 'imoveis_pageviews',
                            'where' => array(
                                            'pagina LIKE "imoveis_popdetalhes"',
                                            'mes = "'.date('n').'"',
                                            'ano = "'.date('Y').'"',
                                            ),
                            );
        $tem = $this->select($data_tem);
		if( $tem['qtde'] > 0 )
        {
            $data_update = array(
                                'tabela' => 'imoveis_pageviews',
                                'dados' => array('views' => ( $tem['itens'][0]->views + 1 ) ),
                                'where' => 'id = '.$tem['itens'][0]->id,
                                );
            $aff = $this->editar($data_update);
		}
        else
        {
            $data_insert = array(
                                'tabela' => 'imoveis_pageviews',
                                'dados' => array(
                                                'views' => 1,
                                                'pagina' => 'imoveis_popdetalhes',
                                                'mes' => date('n'),
                                                'ano' => date('Y'),
                                                ),
                                );
            $this->adicionar($data_insert);
		}
        $data_insert_portal = array(
                                'tabela' => 'imoveis_pageviews_portal',
                                'dados' => array(
                                                'portal' => str_replace( array('http://','www.'),'',base_url()),
                                                'pagina' => 'imoveis_popdetalhes',
                                                'ip' => $_SERVER['REMOTE_ADDR'],
                                                'dia' => date("d"),
                                                'mes' => date('n'),
                                                'ano' => date('Y'),
                                                ),
                                );
		$aff_ = $this->adicionar($data_insert_portal);	
    }
    
    public function set_imovel( $data )
    {
        if ( isset($data) )
        {
            $retorno = (object)$data;
        }
        else
        {
            $data_imovel = array(
                                'coluna' => 'imoveis.*,'
                                            . 'cidades.nome as cidade,'
                                            . 'cidades.link as cidade_link,'
                                            . 'cidades.uf as uf,'
                                            . 'logradouros.logradouro as logradouro,'
                                            . 'imoveis_tipos.nome as imovel_tipo,'
                                            . 'imoveis_tipos.link as imovel_tipo_link,'
                                            . 'imoveis_estilos.nome as imovel_estilo,'
                                            . 'imoveis_tipos.nome as tipo',
                                'tabela'    => array(
                                            array('nome' => 'imoveis'),
                                            array('nome' => 'imoveis_tipos', 'tipo' => 'LEFT', 'where' => 'imoveis.id_tipo = imoveis_tipos.id'),
                                            array('nome' => 'imoveis_estilos', 'tipo' => 'LEFT', 'where' => 'imoveis.id_estilo = imoveis_estilos.id'),
                                            array('nome' => 'cidades', 'tipo' => 'LEFT', 'where' => 'imoveis.id_cidade = cidades.id'),
                                            array('nome' => 'logradouros', 'tipo' => 'LEFT', 'where' => 'imoveis.id_logradouro = logradouros.id'),
                                ),
                                'where' => 'imoveis.id = '.$this->id_imovel,
                                );
            $consulta_imovel = $this->select($data_imovel);
            $retorno = $consulta_imovel['itens'][0];
        }
        return $retorno;
    }
    
    public function set_empresa( $data )
    {
        if ( isset($data) )
        {
            $retorno = (object)$data;
        }
        else
        {
            $data_empresa = array(
                                'coluna' => '*',
                                'tabela' => 'empresas',
                                'where' => 'empresas.id = '.$this->imovel->id_empresa,
                                );
            $consulta = $this->select($data_empresa);
            $retorno = $consulta['itens'][0];
        }
        return $retorno;
        
    }
    
    public function set_parametros( $data )
    {
        if ( isset($data) )
        {
            $retorno = (object)$data;
        }
        else
        {
            $data_parametro = array(
                                'coluna' => '*',
                                'tabela' => 'hotsite_parametros',
                                'where' => 'hotsite_parametros.id_empresa = '.$this->imovel->id_empresa,
                                );
            $consulta = $this->select($data_parametro);
            $retorno = $consulta['itens'][0];
        }
        return $retorno;
        
    }
    
    public function inicia()
    {
        
    }
    
    public function set_destaques_clicks ( $local= 'venda', $site = 'destaques' )
    {
        return $this->get_destaques_clicks( $this->id_imovel, $this->imovel->id_empresa, $local, $site);
    }
    
    public function get_carrinho( $verifica_imovel = FALSE )
    {
        $data['tabela'] = 'imoveis_carrinho';
        $data['coluna'] = '*';
        if ( $verifica_imovel )
        {
            $data['where'][] = 'id_imovel = '.$this->id_imovel;
        }
        $data['where'][] = 'sessao = "'.session_id().'"';
        $itens = $this->select($data);
        $retorno = ( $verifica_imovel ? ( isset($itens['itens'][0]) ? TRUE : FALSE ) : $itens['itens'] );
        return $retorno;
    }
    
    public function adicionar_ao_carrinho( )
    {
        $tem = $this->get_carrinho( TRUE );
        if ( ! $tem )
        {
            $insert['tabela'] = 'imoveis_carrinho';
            $insert['dados'] = array('data' => time(), 'sessao' => session_id(), 'id_imovel' => $this->id_imovel );
            $retorno = $this->adicionar($insert);
            if ( $retorno )
            {
                echo '<script>alert("Imóvel adicionado a sua lista de interesse");</script>';
            }
        }
       
    }
    
    
    public function remover_do_carrinho(  )
    {
        $data['tabela'] = 'imoveis_carrinho';
        $data['where'] = 'sessao = "'.session_id().'" AND id_imovel= "'.$this->id_imovel.'"';
        $afetado = $this->deletar($data);
        if ( $afetado )
        {
            echo '<script>alert("Imóvel removido de sua lista de interesse");</script>';
        }
        
    }
    
    public function set_imovel_views (  )
    {
        $retorno = $this->get_imovel_views($this->imovel);
        return $retorno;
    }
    
    public function set_caracteristicas()
    {
        $caracteristica = array();
        $caracteristica['Código'] = $this->id_imovel;
        if ( $this->imovel->referencia )
        {
            $caracteristica['Referência'] = $this->imovel->referencia;
        }
        if ( $this->imovel->venda || $this->imovel->locacao || $this->imovel->locacao_dia )
        {
            if ( $this->imovel->venda )
            {
                $negocio[] = ' Venda ';
            }
            if ( $this->imovel->locacao )
            {
                $negocio[] = ' Locação ';
            }
            if ( $this->imovel->locacao_dia )
            {
                $negocio[] = ' Locação Temporada ';
            }
            $caracteristica['Imóvel para'] = implode(' ou ',$negocio);
        }
        if ( isset($this->imovel->com_res) && $this->imovel->com_res )
        {
            $caracteristica['Especificação'] = $this->imovel->com_res;
        }
        if ( isset($this->imovel->id_tipo) )
        {
            $caracteristica['Tipo'] = $this->set_tipo_por_id($this->imovel->id_tipo, 'nome');
        }
        if ( isset($this->imovel->id_estilo) )
        {
            $caracteristica['Estilo'] = $this->set_estilo_por_id($this->imovel->id_estilo, 'nome');
        }
        if ( $this->imovel->condominio )
        {
            $caracteristica['Condomínio'] = 'Sim';
        }
        if ( $this->imovel->condominio_valor )
        {
            if ( is_numeric($this->imovel->condominio_valor) )
            {
                $caracteristica['Valor do condomínio'] = "R$ ".number_format( $this->imovel->condominio_valor, 2, ",", "." );
            }
            else
            {
                $caracteristica['Valor do condomínio'] = $this->imovel->condominio_valor;
            }
        }
        if ( $this->imovel->iptu )
        {
            if ( is_numeric($this->imovel->iptu) )
            {
                $caracteristica['IPTU/ITR'] = "R$ ".number_format( $this->imovel->iptu, 2, ",", "." );
            }
            else
            {
                $caracteristica['IPTU/ITR'] = $this->imovel->iptu;
            }
        }
        if ( $this->imovel->quartos )
        {
            $caracteristica['Quartos'] = $this->imovel->quartos;
        }
        if ( $this->imovel->suites )
        {
            $caracteristica['Suítes'] = $this->imovel->suites;
        }
        if ( $this->imovel->garagens )
        {
            $caracteristica['Garagem'] = $this->imovel->garagens;
        }
        if ( $this->imovel->mobiliado )
        {
            $caracteristica['Mobiliado'] = 'Sim';
        }
        if ( $this->imovel->semimobiliado )
        {
            $caracteristica['Semi Mobiliado'] = 'Sim';
        }
        if ( $this->imovel->troca )
        {
            $caracteristica['Aceita Troca'] = ! empty($this->imovel->troca_texto) ? $this->imovel->troca_texto : 'Sim';
        }
        if ( $this->imovel->novo )
        {
            switch ( $this->imovel->novo )
            {
                case 1:
                    $caracteristica['Imóvel novo'] = 'Sim';
                    break;
                case 2:
                    $caracteristica['Imóvel na Planta'] = 'Sim';
                    break;
                case 3:
                    $caracteristica['Imóvel em Contrução'] = 'Sim';
                    break;
                case 4:
                    $caracteristica['Imóvel de Revenda'] = 'Sim';
                    break;
            }
        }
        if ( $this->imovel->cobertura )
        {
            $caracteristica['Cobertura'] = 'Sim';
        }
        if ( $this->imovel->area > 0 )
        {
            $caracteristica['Área construida em m&sup2;'] = number_format($this->imovel->area,2,",",".");
        }
        if ( $this->imovel->area_util > 0 )
        {
            $caracteristica['Área útil em m&sup2;'] = number_format($this->imovel->area_util,2,",",".");
        }
        if ( $this->imovel->area_terreno > 0 )
        {
            $caracteristica['Área terreno em m&#178;'] = number_format($this->imovel->area_terreno,2,",",".");
        }
        if ( $this->imovel->condominio )
        {
            $caracteristica['Condomínio'] = $this->imovel->condominio;
        }
        $retorno = '';
        $retorno .= '<div id="caracteristica">';
        $retorno .= '<h2> Características do imóvel </h2><br>';
        $retorno .= '<div class="texto">';
        foreach( $caracteristica as $chave => $valor )
        {
        	$retorno .= '&bull;&nbsp;<b>'.$chave.' : </b>'.$valor.'  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ';
        }
		$retorno .= '</div>';
		$retorno .= '</div>';
        return $retorno;
    }
    
    public function set_css()
    {
        if( isset( $this->parametros->cor_txt_ficha_imovel ) && ! empty( $this->parametros->cor_txt_ficha_imovel ) )
        {
            $ficha_imovel_color = $this->parametros->cor_txt_ficha_imovel;
        }
        else
        {
            $ficha_imovel_color = isset($this->parametros->cor_destaque_pagina_padrao) ? $this->parametros->cor_destaque_pagina_padrao : '';
        }
        $data['parametro'] = $this->parametros;
        $data['ficha_imovel_color'] = $ficha_imovel_color;
        $data['pagina'] = 'classes/views/imovel_css';
        $retorno = $this->views->get_html($data);
        return $retorno;
    }
    
    public function set_topo()
    {
        $data['empresa'] = $this->empresa;
        $data['parametros'] = $this->parametros;
        $data['logradouro'] = $this->set_logradouro_por_id($this->empresa->id_logradouro);
        $data['cidade'] = $this->set_cidade_por_id($data['logradouro']->id_cidade);
        $data['pagina'] = 'classes/views/imovel_topo';
        $retorno = $this->views->get_html($data);
        return $retorno;
    }
    
    public function set_identifica()
    {
    	$retorno = '';
    	$retorno .= '<h1>';
    	$retorno .= $this->tipo_imovel->nome;
    	if ( isset($this->imovel->bairro) && ! empty($this->imovel->bairro) )
    	{
    		$retorno .= ' | '.$this->imovel->bairro;
    	}
    	if ( isset($this->cidade->nome) )
    	{
    		$retorno .= ' | '.$this->cidade->nome;
    	}
    	$retorno .= '</h1>';
    	$status = '';
		if( $this->imovel->reservaimovel )
		{
    		$status .= 'Reservado ';
		}
		if ( $this->imovel->vendido )
		{
			$status .= 'Vendido ';
		}
		if ( $this->imovel->locado )
		{
			$status .= 'Locado ';
		}
		if ( ! empty($status) )
		{
			$retorno .= '<div class="status_do_imovel_menor"><strong> Imóvel '.$status.'</strong></div>';
		}
		$retorno .= $this->set_precos();
		return $retorno;
    }
    
    public function set_precos()
    {
    	$retorno = '';
        $so_valor = array();
        if($this->imovel->preco_venda > 0 )
    	{
            $retorno .= '<h2>Preço para compra: '; 
    	    $so_valor['venda'] = '<b>R$ '.number_format($this->imovel->preco_venda,2,",",".").'</b> ';
            $retorno .= $so_valor['venda'];
    	    $retorno .= $this->parametros->obs_valor_venda.'</h2><BR>';
        } 
    	if( $this->imovel->preco_locacao > 0 )
    	{
            $retorno .= '<h2>Preço para locação: ';
    	    $so_valor['locacao'] = '<b>R$ '.number_format($this->imovel->preco_locacao,2,",",".").'</b> ';
            $retorno .= $so_valor['locacao'];
            
    	    $retorno .= $this->parametros->obs_valor_locacao.'</h2><BR>';
        } 
        if($this->imovel->preco_locacao_dia > 0 )
    	{ 
    		$retorno .= '<h2>Preço para locação diária: ';
    		$so_valor['locacao_dia'] = '<b>R$ '.number_format($this->imovel->preco_locacao_dia,2,",",".").'</b>';
                $retorno .= $so_valor['locacao_dia'];
                $retorno .= '</h2>';
    	 }
         
        if ( $this->parametros->modelo_detalhe == 4 )
        {
            $retorno = $so_valor;
        }
        return $retorno; 
    }
    
    public function set_galeria( $contador = NULL )
    {
    	$data['contador'] = $contador;
    	$data['imovel'] = $this->imovel;
    	$data['empresa'] = $this->empresa;
    	$data['parametros'] = $this->parametros;
    	$data['pagina'] = 'classes/views/galeria_fotos';
    	$retorno = $this->views->get_html($data, TRUE);
    	return $retorno;
    }
    
    public function set_descricao()
    {
    	$retorno = '';
    	$retorno .= '<h2> Detalhes do Imóvel </h2>';
    	$retorno .= '<div id="identifica" >';
    	$retorno .= '<h2>'.$this->imovel->nome.'<BR></h2>';
    	$retorno .= '<span class="negrito">';
    	$retorno .= $this->imovel->logradouro;
    	if( $this->imovel->numero )
    	{
    		$retorno .= ', '.$this->imovel->numero;
    	}
    	if( $this->imovel->complemento )
    	{
    		$retorno .= ' / '.$this->imovel->complemento; 
    	}
    	if( $this->imovel->esquina )
    	{ 
    		$retorno .= '<br>Esquina com'. $this->imovel->esquina.'<BR>';
    	} 
    	if( $this->imovel->bairro ) 
    	{ 
    		$retorno .= ', ';
    	} 
    	$retorno .= $this->imovel->bairro; 
    	if ( ( isset($this->bairro->nome) ) && ( $this->imovel->bairro != $this->bairro->nome ) )
    	{ 
    		$retorno .= ', '.$this->bairro->nome;
    	} 
    	if ( isset($this->cidade->nome) ) 
    	{ 
    		$retorno .= ', '.$this->cidade->nome;
    	} 
    	$retorno .= '<br></span>';
    	$retorno .= '</div>';
    	$retorno .= '<span class="texto">';
    	$retorno .= nl2br( $this->imovel->descricao );
		$retorno .= '</span>';
		$retorno .= '<div>';
		$retorno .= '<h2>'.$this->set_precos().'</h2>';
		$retorno .= '</div>';
		return $retorno;
    }
    
    public function set_formulario_contato( $url = NULL )
    {
        if ( isset($url) )
        {
            $data['url'] = $url;
        }
    	$data['pagina'] = 'classes/views/formulario_contato';
    	$data['id'] = $this->id_imovel;
    	$data['modelo_detalhe'] = $this->parametros->modelo_detalhe;
    	$retorno = $this->views->get_html($data, TRUE);
    	return $retorno;
    }
    
    public function set_mapa( $width = FALSE, $height = FALSE )
    {
    	$retorno = '';
    	if ( $this->parametros->imoveis_mostramapa )
    	{
            if ( $this->imovel->mostramapa )
            {
                if ( ! empty($this->imovel->latitude) && ! empty( $this->imovel->longitude ) )
                {
                    $retorno .= '<div id="mapa">';
                    if ( ! $width )
                    {
                        $retorno .= '<h2> MAPA DE LOCALIZAÇÃO DO IMÓVEL </h2>';
                    }
                    $retorno .= '<iframe src="http://www.icuritiba.com/index.php/imoveis/mapa/'.$this->id_imovel.'" width="'.( $width ? $width : '950' ).'" frameborder="0" height="'.( $height ? $height : '480' ).'" scrolling="no"></iframe>';
                    $retorno .= '</div>';
                } 
    	    }
    	} 
    	return $retorno;
    }
    
    public function set_video()
    {
    	$retorno = '';
    	if ( ! empty($this->imovel->video) )
    	{
    		$video = $this->imovel->video;
    		$popvideo = explode("?",$video);
    		$querystring = explode("&",$popvideo[1]);
    		for ($i = 0; $i<= count($querystring); $i++)
    		{
    			if (substr($querystring[$i],0,2) == "v=")
    			{
    				$idvideo = substr($querystring[$i],2);
    			}
    		}
    		if ( ! empty($idvideo) ) 
    		{
    			$retorno .= '<div id="video">';
    			$retorno .= '<h2> VÍDEO DO IMÓVEL </h2>';
    			$retorno .= '<object >';
    			$retorno .= '<param name="movie" value="http://www.youtube.com/v/'.$idvideo.'&amp;hl=pt-br&amp;fs=1"></param>';
    			$retorno .= '<param name="allowFullScreen" value="true"></param>';
    			$retorno .= '<param name="allowscriptaccess" value="always"></param>';
    			$retorno .= '<embed src="http://www.youtube.com/v/'.$idvideo.'&amp;hl=pt-br&fsamp;=1" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="853" height="480"></embed>';
    			$retorno .= '</object>';
    			$retorno .= '</div>';
    	
    		} 
    	}
   		return $retorno;   	
    }
    
    public function set_rodape()
    {
    	$data['pagina'] = 'classes/views/imovel_rodape';
    	$data['id'] = $this->id_imovel;
    	$data['carrinho'] = $this->get_carrinho(TRUE);
    	$data['data_atualizacao'] = $this->imovel->data_atualizacao;
    	$data['empresa_nome_fantasia'] = $this->empresa->empresa_nome_fantasia;
    	$retorno = $this->views->get_html($data);
    	return $retorno;
    }
    
    public function set_title()
    {
        $tit = '';
        $addvenda = '';
        if( $this->imovel->venda )
        { 
            $tit = "Vende-se";
            $addvenda = " e ";
        }
        if( $this->imovel->locacao )
        {
            $tit = $tit . $addvenda . "Aluga-se";
        }
        $cidade = $this->set_cidade_por_id($this->imovel->id_cidade,'nome');
        $retorno['titulo'] =  $this->tipo_imovel->nome . " em " . $cidade .   " - " . $this->imovel->bairro . " " . $tit . " " .  $this->imovel->nome . " - " .  $this->empresa->empresa_nome_fantasia;
        $retorno['description'] = $this->empresa->empresa_nome_fantasia . ", Vende e Aluga ".$this->tipo_imovel->nome." em ".$cidade . " pelo site ". $this->empresa->empresa_dominio;
        return $retorno;
    }
    
    public function set_head( $lightbox = TRUE, $jquery = TRUE, $return = FALSE )
    {
        $data = $this->set_title();
        $data['lightbox'] = $lightbox;
        $data['jquery'] = $jquery;
        $data['pagina'] = 'classes/views/imovel_head';
        $retorno = $this->views->get_html($data, $return);
        return $retorno;
    }
}