<?php 
/**
 * Classe responsável por montagem de banner
 * @author Carlos Claro <carlos@carlosclaro.com.br>
 * @version 2
 * melhoria para todas as atividades sejam realizadas dentro da classe
 */
include_once base_cwd().'/classes/db.php';

class Banner extends DB
{
	/**
	 * 
	 * Variavel para uso publico resposta url, setado em set_url
	 * @var string 
	 */
	public $url = '';
	/**
	 * 
	 * Global para inicializacao do banner
	 * @var array
	 */
	public $banner = array();
	
	public $empresa = NULL;
	public $parametros = NULL;
	public $id_pagina = NULL;
	public $servico = NULL;
        public $parametro_banner = NULL;
        public $paginas_banner = FALSE;
	
	public function __construct( $empresa = NULL, $parametros = NULL, $id_pagina = NULL, $servico = NULL )
	{
            parent::__construct();
            $this->empresa = $empresa;
            $this->parametros = $parametros;
            $this->id_pagina = $id_pagina;
            $this->servico = $servico;
            $pesquisa['tabela'] = 'hotsite_banner_rotativo';
            $pesquisa['where'] = 'id_empresa = '.$this->empresa['id'];
            $b = $this->select($pesquisa, FALSE, 'array');
            $this->parametro_banner = isset( $b['itens'][0] ) ? $b['itens'][0] : NULL;
            if( isset($this->id_pagina) )
            {
                $pesquisa_['tabela'] = 'hotsite_banner_rotativo_paginas';
                $pesquisa_['where'] = 'id_empresa = '.$this->empresa['id'].' AND id_pagina = '.$this->id_pagina;
                $c = $this->select($pesquisa_, FALSE, 'array');
                $this->paginas_banner = isset( $c['itens'][0] ) ? $c['itens'][0] : FALSE;
            }
	}
	
	/**
	 * 
	 * Carrega a variavel global url
	 * @param string $url
	 * 
	 */
	private function set_url ( $url = '' )
	{
		$this->url = $url;
		return $this;
	}
	
	/**
	 * 
	 * Inicia as variaveis
	 * @param array $banner
	 * @param string $url
	 * @
	 */
	public function inicializa ( )
	{
		if ( $this->parametros['banner_rotativo'] || $this->parametros['super_destaque_fullscreen'] )
		{
			$array = array();
			$busca = array();
			$busca['coluna'] = '*';
			$busca['tabela'][] = array('nome' => 'hotsite_banner_rotativo');
			$busca['order'] =  'ordem ASC';
			if( isset($this->id_pagina) && $this->id_pagina )
			{
				$busca['tabela'][] = array('tipo' => 'INNER', 'nome' => 'hotsite_banner_rotativo_paginas', 'where' => 'hotsite_banner_rotativo_paginas.id_banner = hotsite_banner_rotativo.id');
				$busca['where'] = 'hotsite_banner_rotativo.id_empresa = '.$this->empresa['id'].' AND hotsite_banner_rotativo_paginas.id_pagina = '.$this->id_pagina;
				/*
				$query_BR = "SELECT
				*
				FROM hotsite_banner_rotativo
				INNER JOIN hotsite_banner_rotativo_paginas ON hotsite_banner_rotativo_paginas.id_banner = hotsite_banner_rotativo.id
				WHERE hotsite_banner_rotativo.id_empresa=".$this->empresa['id']." AND hotsite_banner_rotativo_paginas.id_pagina = ".$this->id_pagina."
				ORDER BY ordem ASC";
				*/
			}
			else
			{
				
				$busca['where'] = 'hotsite_banner_rotativo.id_empresa = '.$this->empresa['id'].' AND ( hotsite_banner_rotativo.menu_tipos = 1 || hotsite_banner_rotativo.menu_buscas = 1 || hotsite_banner_rotativo.paginas_automaticas = 1 )';
				/*
				$query_BR = "SELECT
				*
				FROM hotsite_banner_rotativo
				WHERE hotsite_banner_rotativo.id_empresa=".$id." AND ( hotsite_banner_rotativo.menu_tipos = 1 || hotsite_banner_rotativo.menu_buscas = 1 || hotsite_banner_rotativo.paginas_automaticas = 1 )
				ORDER BY ordem ASC";
				*/
			}
			$resultado = $this->select($busca, FALSE, 'array');
			if ( isset($resultado) && $resultado['qtde'] > 0 )
                        {
                            $Local_BR  = $this->empresa['empresa_dominio'].'/powsites/'.$this->empresa['id'].'/ban/';
                            foreach ( $resultado['itens'] as $item )
                            {
                                    /**
                                     array que deve retornar do banco de dados. em forma de object, se precisar posso enviar uma
                                     classe que transforma array ou retorno de banco de dados em object
                                     */
                                    $array[] = (object)array(  'id' => $item['id'], 'titulo' => $item['alt'], 'arquivo' => $Local_BR.$item['nome_banner'], 'link' => $item['link'], 'nova_janela' => $item['nova_janela'] );
                            }
                            
                        }
			/**
			 *
			 * coloque aqui a url do site que vai apontar os arquivos.
			 */
			$this->url = base_url();
                        $this->banner = $array;
			//var_dump($url);
		}
	}
	
	/**
	 * 
	 * Retorna o cabecalho para o layout
	 */
	public function get_cabecalho ( $jquery = TRUE )
	{
            $retorno = '';
            if ( $jquery )
            {
                $retorno .= ' 
                            <script type="text/javascript" src="'.$this->url.'js/jquery.min.js"></script>
                            <script type="text/javascript" src="'.$this->url.'js/bootstrap.min.js"></script>
                            <link rel="stylesheet" href="'.$this->url.'css/bootstrap.min.css" type="text/css" />';
                
            }
            $retorno .= ' 
                        <script type="text/javascript" src="'.$this->url.'js/funcs.js"></script>
                        <link rel="stylesheet" href="'.$this->url.'css/estilo.css" type="text/css" />';

            return $retorno;
	}
        
        public function set_banner( $posicao = 'topo' )
        {
            $retorno = '';
            if( $this->parametros['banner_rotativo'] == 1 && ( isset($this->parametro_banner) && $this->parametro_banner['posicao'] == $posicao ) && ( count($this->banner) > 0 ) )
            {   
                if ( ( isset( $this->servico) &&  isset($this->parametro_banner[$this->servico]) && $this->parametro_banner[$this->servico] ) || $this->parametro_banner['paginas_automaticas'] || $this->paginas_banner )
                {
                    if ( $this->parametros['banner_rotativo_fullscreen'] )
                    {
                        $retorno .= '</table>';
                        $retorno .= '<script type="text/javascript" src="'.base_url().'js/banner_ajusta_janela.js"></script>';
                        $retorno .= '<table style="width:100%; text-align=center;" class="banner_rotativo_fullscreen" border="0"  cellspacing="0" cellpadding="0" data-max="'.$this->empresa['largura'].'">';
                    }
                    $retorno .= '<tr><td align="center"><div class="banner">';
                    $retorno .= $this->html();
                    $retorno .= '</div></td></tr>';
                    if ( $this->parametros['banner_rotativo_fullscreen']  )
                    {
                        $retorno .= '</table>';
                        $retorno .= '<table width="'.$this->empresa['largura'].'" align="center" >';

                    }
                }
            }
            return $retorno;
        }
        
	
	/**
	 * 
	 * retorna o html do banner
	 */
	public function html()
	{
            $retorno = '';
            if ( isset($this->banner) && count($this->banner) > 0)
            {
                $retorno .= '<div id="myCarousel" class="carousel slide banner" style="z-index:0;" >'.PHP_EOL;
                $retorno .= '<div class="carousel-inner">'.PHP_EOL;
                if ( isset($this->banner) )
                {
                    $a = 0;
                    foreach ( $this->banner as $b )
                    {
                        $retorno .= '<div class="item '.( ( $a == 0 ) ? 'active' : '' ).'">'.PHP_EOL;
                        $retorno .= ( ( isset($b->link) and ( ! empty($b->link)) ) ? '<a href="'.$b->link.'" '.( ( isset($b->nova_janela) && $b->nova_janela ) ? ' target="_blank"' : '' ).' style="border:none;">' : '' );
                        if(strpos($b->arquivo,".swf"))
                        {   
                            $vet = getimagesize($local); 
                            $retorno .= '
                            <object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=5,0,0,0" width="'.$vet[0].'" height="'.$vet[1].'">
                            <param name=movie value="'.$b->arquivo.'">
                            <param name=quality value=high>
                            <embed src="'.$b->arquivo.'" quality=high pluginspage="http://www.macromedia.com/shockwave/download/index.cgi?P1_Prod_Version=ShockwaveFlash" type="application/x-shockwave-flash" width="'.$vet[0].'" height="'.$vet[1].'"></embed>
                            </object>'.PHP_EOL;
                        } 
                        else 
                        { 
                            $retorno .= '<img border="0" src="'.$b->arquivo.'" alt="'.$b->titulo.'" >'.PHP_EOL; 
                        }


                        $retorno .= ( ( isset($b->link) and (!empty($b->link)) ) ? '</a>' : '' );
                        $retorno .= '</div>'.PHP_EOL;
                        $a++;
                    } 
                }
                $retorno .= '</div>'.PHP_EOL;
                $retorno .= '<a class="left carousel-control" href="#myCarousel" data-slide="prev">&lsaquo;</a>';
                $retorno .= '<a class="right carousel-control" href="#myCarousel" data-slide="next">&rsaquo;</a>';
                $retorno .= '<ol class="carousel-indicators">'.PHP_EOL;
                for( $c = 0; $c < count($this->banner); $c++ )
                {
                    $retorno .= '<li data-target="#myCarousel" data-slide-to="'.$c.'" class=" '.(($c==0) ? 'active' : '').'"></li>'.PHP_EOL;
                }
                $retorno .= '</ol>'.PHP_EOL;
                $retorno .= '</div>'.PHP_EOL;
            }
            return $retorno;
	}
	
}

?>

