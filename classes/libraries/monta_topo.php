<?php
include_once base_cwd().'/ope/menu_controller.php';
include_once base_cwd().'/classes/libraries/views.php';
class Monta_topo
{
	private $empresa = NULL;
	private $parametros = NULL;
	private $pagina = NULL;
	private $id_pagina = FALSE;
	private $servico = NULL;
	private $views = NULL;
	private $menu = FALSE;
	private $banner = NULL;
	
	public function __construct( $empresa = NULL, $parametros = NULL, $pagina = NULL, $servico = NULL, $id_pagina = FALSE)
	{
            $this->empresa = $empresa;
            $this->parametros = $parametros;
            $this->pagina = $pagina;	
            $this->id_pagina = $id_pagina;	
            $this->servico = $servico;
	}
	
	public function inicia_menu_controller( $data = array() )
	{
		$this->menu = new Menu_Controller( $data );
		return $this;
	}
	
	public function inicia_views()
	{
		$this->views = new Views();
		return $this;
	}
	
        
	public function set_h1( $id_tipo = NULL, $id_cidade = NULL )
	{
		$retorno = '';
		if ( ! empty( $this->parametros['texto_h1'] ) && ( ! isset($id_tipo) && ! isset($id_cidade) ) && ( isset($this->servico) && $this->servico == 'menu_tipos' ) ) 
		{
			$retorno .= '<tr><td style="text-align: center;"><h1>'.$this->parametros['texto_h1'].'</h1></td></tr>';
		}
 		return $retorno;
	}
	
	public function set_imagem_topo()
	{
            $retorno = '';
            if( isset($this->pagina) && $this->pagina['imagem_topo'] )
            {
                $local= base_url().'powsites/'.$this->empresa['id']. '/'. $this->pagina['imagem_topo'];
                $vet = getimagesize( $local );
                if( strpos( $this->pagina['imagem_topo'],'.swf' ) )
                {
                    $retorno .= '<td>'.$this->get_banner_flash($local, FALSE);
                }
                else
                {
                    $Noflash = 1;             
                    $retorno .= $this->get_banner_imagem_background($local, $this->empresa['largura'], $vet[1]);
                }
            }
            elseif( $this->parametros['imagem_topo'] )
            {
                $local= base_url().'powsites/'.$this->empresa['id'].'/'.$this->parametros['imagem_topo'];
                $local_verifica= base_cwd().'/powsites/'.$this->empresa['id'].'/'.$this->parametros['imagem_topo'];
                if ( file_exists( $local_verifica ) )
                {
                    $vet = getimagesize($local);
                }
                else
                {
                    $vet = array();
                }
                if( strpos( $this->parametros['imagem_topo'],'.swf' ) )
                {              
                    $retorno .= '<td>'.$this->get_banner_flash($local, FALSE, $this->empresa['largura']);
                }
                else
                {
                    $Noflash = 1;            
                    $retorno .= $this->get_banner_imagem_background($local, $this->empresa['largura'], ( isset($vet[1]) ? $vet[1] : NULL ));
                }
            }
		return $retorno;
	}
	
	public function set_menu_horizontal()
	{
		$retorno = '';
		if ($this->parametros['menu_horizontal'] )
		{
			$this->inicia_views();
			$data['largura'] = $this->empresa['largura'];
			$data['creci'] = $this->empresa['pagina_creci'];
			$data['nome_fantasia'] = $this->empresa['empresa_nome_fantasia'];
			$data['params'] = $this->parametros;
			$data['empresa'] = $this->empresa;
			$data['pagina'] = '/classes/views/menu_horizontal';
			
			$retorno .= $this->views->get_html($data, TRUE);
		} 
		return $retorno;
	}
	
	public function set_menu_por_posicao( $posicao = 1 )
	{
		//var_dump($posicao);
		switch( $posicao )
		{
			case 1:
			default:
				$valor_posicao = 'super_topo';
				break;
			case 2:
				$valor_posicao = 'topo';
				break;
			case 3:
				$valor_posicao = 'rodape';
				break;
		}
		$retorno = '';
		$menu_posicao = 'menu_posicao'.$posicao;
		if ( ( isset( $this->parametros['menu_horizontal_automatico'] ) && $this->parametros['menu_horizontal_automatico'] ) && ( $this->parametros[$menu_posicao] ) )
		{
			$dados_menu_hotsite = array('id_empresa' => $this->empresa['id'], 'tipo' => $posicao, 'posicao' => $valor_posicao, 'id_menu' => $this->parametros[$menu_posicao], 'url_amigavel' => $this->parametros['url_amigavel']);
			$this->inicia_menu_controller();
			//var_dump($dados_menu_hotsite);
		  	$tem = $this->menu->verifica_menu($dados_menu_hotsite);
			if ( isset($tem) )
			{
				$retorno .= '<tr><td>';
				$retorno .= '<link href="'.base_url().'css/bootstrap_cols.css" rel="stylesheet" type="text/css">';
	            $dados_cabecalho_menu  = array('id_menu' => $this->parametros[$menu_posicao], 'posicao' => $valor_posicao);
				//var_dump($dados_menu_hotsite);
	           	$retorno .= $this->menu->montar_menu_hotsite($dados_menu_hotsite);
				$retorno .= $this->menu->get_style_menu($dados_cabecalho_menu);
				$retorno .= $this->menu->set_javascript();
		        $retorno .= '</td></tr>';
			}
		}  
		return $retorno;
	}
        
        public function get_banner()
        {
            include_once base_cwd().'/classes/banner.php';
            $this->banner = new Banner( $this->empresa, $this->parametros, $this->id_pagina, isset($this->servico) ? $this->servico : NULL );
            $this->banner->inicializa();
        }
	
        public function set_banner( $posicao = 'topo' )
        {
            $retorno = $this->banner->set_banner($posicao);
            return $retorno;
        }
        
        public function set_banner_super_destaque( $id_tipo = NULL, $id_cidade = NULL, $verifica = FALSE, $full = TRUE )
        {
            $retorno = '';
            if ( $verifica )
            {
                if ( $this->parametros['superdestaque'] && $this->parametros['superdestaque_posicao'] =='topo' && ($this->servico == 'menu_tipos' || $this->servico == 'menu_buscas') )
                {
                    $verifica = FALSE;
                }
            }
            if ( ! $verifica )
            {
                $retorno .= '<tr><td align="center">';
                $por_tipo = ( (isset( $id_tipo ) && ! empty($id_tipo) ) ? ' AND id_tipo = '.$id_tipo : '' ) ;
                $por_linha = ( ( isset( $id_linha ) && ! empty( $id_linha ) ) ? ' AND id_linha = '.$id_linha : '' ) ;
                $por_quero = ( ( isset( $quero ) && ! empty( $quero ) ) ? ' AND '.$quero.' = 1' : '' ) ;
                include_once base_cwd().'/super_destaque_controller.php';
                $super_destaque_obj = new Super_Destaque();
                $function = $full ? 'monta_super_destaque_full' : 'monta_super_destaque'; 
                $resposta_sp_destaque = $super_destaque_obj->$function( $this->parametros['id_empresa'], $this->parametros['tipo_superdestaque'], 2, $por_tipo.$por_linha.$por_quero, $this->parametros['url_amigavel']);
                //var_dump($resposta_sp_destaque);
                
                $retorno .= $resposta_sp_destaque; 
                $retorno .= '<script type="text/javascript">$(function(){$(".carousel").carousel()});</script>';
                $retorno .= '</td></tr>';
            }
            return $retorno;
        }
        
        
	public function set_banner_super_destaque_full_screen( $id_tipo = NULL, $id_cidade = NULL )
	{
            $retorno = '';
            if ( $this->parametros['super_destaque_fullscreen'] && $this->servico == 'menu_tipos' && ( ! ( isset($id_tipo) || isset($id_cidade) ) ) )
            {
        	$retorno .= '</table>';
        	$retorno .= '<table style="width:100%; text-align=center;" class="super_destaque_fullscreen" border="0"  cellspacing="0" cellpadding="0" data-max="'.$this->empresa['largura'].'">';
                $retorno .= $this->set_banner_super_destaque($id_tipo, $id_cidade);
                $retorno .= '</table>';
                $retorno .= '<table width="'.$this->empresa['largura'].'" align="center" >';
            }
            return $retorno;
	}
	
	public function set_banner_por_posicao( $posicao = 'acima', $nova_tabela = FALSE, $width = FALSE )
	{
		$retorno = '';
		$abre_tabela = '';
		$fecha_tabela = '';
		if( $this->parametros['banner_posicao'] == $posicao )
		{
			if( isset( $this->parametros['banner'] ) && ! empty( $this->parametros['banner'] ))
			{
				if ( $nova_tabela )
				{
					$abre_tabela = '<table style="width:'.$width.'" align="right"><tr><td><div align="center">';
					$fecha_tabela = '</div></td></tr></table>';
					
				}
				$retorno .= $abre_tabela;
				$local= base_url().'powsites/'.$this->empresa['id'].'/'.$this->parametros['banner'];
				if( strpos( $this->parametros['banner'],'.swf' ) )
				{   		  
					$retorno .= $this->get_banner_flash($local);				        	
				}
				else
				{    
					$retorno .= $this->get_banner_image( $local, $width );
				}
				$retorno .= $fecha_tabela;
			}
		}     
		return $retorno;
	}
	
	public function get_banner_imagem_background( $local, $width = FALSE, $height = FALSE )
	{
		$retorno = '<td background="'.$local.'" width="'.$width.'"  height="'.$height.'">';
		return $retorno;
	}
	
	public function get_banner_image( $local, $width = FALSE )
	{
		$retorno = '';
		$retorno .= '<tr height =0 ><td align="center">';
		$abre = '';
		$fecha = '';
		if( $this->parametros['banner_link'] )
		{
			$abre = '<a href="'.$this->parametros['banner_link'].'">';
			$fecha = '</a>';
		}
		$retorno .= $abre.'<img src="'.$local.'" border="0" alt="" '.( $width ? 'style="width: '.$width.'"' : '' ).' />'.$fecha;
		$retorno .= '</td></tr>';
		return $retorno;
	}
	
	public function get_banner_flash( $local, $td = TRUE, $width = FALSE, $height = FALSE )
	{
		$retorno = '';
		$vet = getimagesize( $local );
		if ( $td )
		{
			$abre = '<tr><td align="center">';
			$fecha = '</td></tr>';
		}
		$retorno .= isset($abre) ? $abre : '';
		$retorno .= '<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=5,0,0,0" width="'.$vet[0].'" height="'.$vet[1].'"><param name=movie value="'.$local.'"><param name=quality value=high>';
		$retorno .= '<embed src="'.$local.'" '. ( ($this->parametros['transparencia'] ) ? 'wmode="transparent"' : '' ) .' quality=high pluginspage="http://www.macromedia.com/shockwave/download/index.cgi?P1_Prod_Version=ShockwaveFlash" type="application/x-shockwave-flash" width="'. ( $width ? $width : $vet[0] ).'" height="'.( $height ? $height : $vet[1] ).'"></embed></object>';
		$retorno .= isset($fecha) ? $fecha : '';
		return $retorno;
	}
		
	
	public function get_img ()
	{
		$img = $this->parametros['imagem_fundo_janela'];
		return $img;
	}
	
	public function set_includes ( $img = '', $jquery = TRUE )
	{
            $this->get_banner();
            $img = $this->get_img();
            if ( empty($img) ) 
            {
                    $tx = "bgcolor=".$this->parametros['cor_janela']."";
            } 
            else 
            { 
                    $img_d = base_url().'powsites/'.$this->parametros["id_empresa"] . '/'. $img  ;
            }
            $retorno = '<style type="text/css">';
            $retorno .= 'body { ';
            if ( ! empty($img) ) 
            { 
                if ( $this->parametros['imagem_fundo_janela_fixa'] ) 
                { 
                            $retorno .= 'background-image: url("'.$img_d.'"); background-attachment: fixed;'; 
                }
                else 
                {
                            $retorno .= 'background : #ffffff url("'.$img_d.'") center top; background-repeat: '.$this->parametros['default_repeat'].';'; 
                }
            } 
            $retorno .= 'margin: 0px; }';
            $retorno .= 'a:link { FONT-STYLE: normal; TEXT-DECORATION: none; color:#666666; } a:active { FONT-STYLE: normal; TEXT-DECORATION: none; color:#666666; }';
            $retorno .= 'a:visited {FONT-STYLE: normal;TEXT-DECORATION: none;color:#666666;} a:hover {TEXT-DECORATION: underline;color:#666666;} .goog-te-gadget-simple {display: inline-flex!important;}</style>';
            $retorno .= '<script type="text/javascript" src="'.base_url().'js/nova_janela_functions.js"  ></script>';
            $retorno .= $this->banner->get_cabecalho($jquery);
            if( ( $this->parametros['superdestaque']  ) && isset($this->servico) && ( ($this->servico == "menu_tipos") || ($this->servico == "buscadestaques") || ($this->servico == "menu_buscas")) )
            {
                $this->inicia_views();
                $data['pagina'] = 'imoveis_carrosel.inc';
                $retorno .= $this->views->get_html($data, TRUE);
                //include(base_cwd().'/imoveis_carrosel.inc.php');
            }  
            return $retorno;
	}
	
	public function set_newsletter()
	{
		require_once base_cwd().'/ope/newsletter_controller.php';
		$news_letter_controller = new Newsletter_Controller(TRUE);
		return $news_letter_controller->inicia($this->empresa['id'], $this->parametros );
	}
	
        public function set_tradutor()
        {
            $retorno = '';
            if ( $this->parametros['tradutor'] )
            {
                $this->inicia_views();
                $data['empresa'] = $this->empresa;
                $data['params'] = $this->parametros;
                $data['pagina'] = 'hotsite_tradutor.inc';
                $retorno .= $this->views->get_html($data, TRUE);
            }
            return $retorno;
            
        }
        
        public function set_menu_guiasjp()
        {
            $retorno = '';
            if( $this->empresa['menu_guiasjp'] )
            { 
                $data['pagina'] = 'http://www.guiasjp.com/topo_guia.inc.php';
                $retorno .= '<tr align="center"><td>';
                $retorno .= $this->views->get_html($data, TRUE);
                $retorno .= '</td></tr>';
            }
            return $retorno;
        }
        
        
        
        public function set_topo_html( $id_tipo = NULL, $id_cidade = NULL)
        {
            $retorno = '';
            $retorno .= $this->set_newsletter();
            $retorno .= '<table style="width:'.$this->empresa['largura'].'px; border: none; margin: 0 auto;" border="0" align="center" cellpadding="0" cellspacing="0">';
            $retorno .= $this->set_tradutor();
            $retorno .= $this->set_menu_guiasjp();
            $retorno .= $this->set_banner_por_posicao('acima');
            $retorno .= $this->set_menu_por_posicao(1);
            $retorno .= $this->set_imagem_topo();
            $retorno .= $this->set_banner_por_posicao('dentro', TRUE, 500);
            $retorno .= $this->set_menu_horizontal();
            $retorno .= $this->set_menu_por_posicao(2);
            $retorno .= $this->set_banner_por_posicao('abaixo');
            $retorno .= $this->set_banner_super_destaque_full_screen( ( isset($id_tipo) ? $id_tipo : NULL ), ( isset($id_cidade) ? $id_cidade : NULL ) );
	    $retorno .= $this->set_banner('topo');
            $retorno .= $this->set_banner_super_destaque( ( isset($id_tipo) ? $id_tipo : NULL ), ( isset($id_cidade) ? $id_cidade : NULL ), TRUE, FALSE );
            $retorno .= $this->set_h1( ( isset($id_tipo) ? $id_tipo : NULL ), ( isset($id_cidade) ? $id_cidade : NULL ));
            return $retorno;
        }
}