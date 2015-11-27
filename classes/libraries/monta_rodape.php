<?php

include_once base_cwd().'/classes/libraries/views.php';

class Monta_rodape extends Monta_topo
{
	private $empresa = NULL;
	private $parametros = NULL;
	private $pagina = NULL;
	private $servico = NULL;
	private $views = NULL;
	private $menu = FALSE;
	private $banner = NULL;
	
	public function __construct( $empresa = NULL, $parametros = NULL)
	{
            $this->empresa = $empresa;
            $this->parametros = $parametros;
            parent:: __construct($empresa, $parametros);
	}
        
        public function set_rodape( $lightbox = FALSE )
        {
            $retorno = '';
            if ( isset($this->parametros['fundo_rodape']) && ! empty($this->parametros['fundo_rodape'])  ) 
            {
                $style_expansivo = 'style="background: url("'.base_url().'powsites/'.$this->parametros["id_empresa"].'/'.$this->parametros['fundo_rodape'].') repeat;"';
            }
            if ( $this->parametros['cor_fundo_rodape_expansivo'] ) 
            {
                $retorno .= '</table><table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" '.( isset($style_expansivo) ? $style_expansivo : '' ).'>';
            }
            if( $this->parametros['imagem_rodape'] )
            {    
                $local= base_url().'powsites/'.$this->parametros["id_empresa"] . '/'. $this->parametros["imagem_rodape"];
                $imagem_fundo_rodape = $local;
                $vet = getimagesize($local);
                if ( empty( $this->parametros['texto_rodape'] ) ) 
                {
                    $retorno .= '<tr><td bgcolor="'.$this->parametros['cor_fundo_rodape'].'" style="text-align: center;">';
                    if ( strpos( $this->parametros['imagem_rodape'],".swf") )
                    {
                        $retorno .= $this->get_banner_flash($local, FALSE, $this->empresa['largura']);
                    }
                    else
                    {
                        $retorno .= $this->get_banner_image($local);
                    }
                    $retorno .= '</td></tr>';
                }
            }  
            if ( $this->parametros['cor_fundo_rodape_expansivo'] )
            {
                $retorno .= '</table>';
                $retorno .= '<table width="'.$this->empresa['largura'].'" border="0" align="center" cellpadding="0" cellspacing="0" style="margin:0 auto;" >';
            }
            $retorno .= '<tr><td >';
            $retorno .= $this->set_menu_por_posicao(3);
            $retorno .= '</td></tr>';
            if ( $this->parametros['cor_fundo_rodape_expansivo'] )
            {
                $retorno .= '</table><table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" >';
            }
            $retorno .= '<tr><td align="center" bgcolor="'.$this->parametros['cor_fundo_rodape'].'" style="'.( isset($this->parametros['cor_texto_rodape']) && ! empty($this->parametros['cor_texto_rodape']) ? 'color:'.$this->parametros['cor_texto_rodape'].'; ' : '' ).( isset($imagem_fundo_rodape) ? 'background: url('.$imagem_fundo_rodape.') repeat ;' : '' ).'">';
            $retorno .= nl2br($this->parametros['texto_rodape']);
            $retorno .= '</td></tr>';
            if ( $this->parametros['cor_fundo_rodape_expansivo'] )
            {
                $retorno .= '</table><table width="'.$this->empresa['largura'].'" border="0" align="center" cellpadding="0" cellspacing="0" style="margin:0 auto;">';
            }
            if( $this->empresa['pagina_tipo'] != 'normal')
            {   
                $retorno .= '<tr><td align="right" ><a href="http://www.sitesimobiliarios.com" target="_blank"><img src="'.base_url().'powsites/imagens/im_hot_pow.gif" width="90" height="20" border="0" title="Conheça a ferramenta do POW internet para criação de sites imobiliários" alt="Conheça a ferramenta do POW internet para criação de sites imobiliários" /></a></td></tr>';
            } 
            else
            {    
                $retorno .= '<tr><td align="right" > <a href="http://www.powsites.com.br" target="_blank"><img src="'.base_url().'powsites/imagens/im_hot_pow.gif" width="90" height="20" border="0" title="Conheça a ferramenta do POW internet para criação de sites"alt="Conheça a ferramenta do POW internet para criação de sites Institucionais e E-commerce" /></a></td></tr>';
            } 
            if ( $lightbox )
            {
                $retorno .= '<link rel="stylesheet" href="'.base_url().'js/3_0/lightbox/css/lightbox.css" type="text/css" /><script type="text/javascript" src="'.base_url().'js/3_0/lightbox/js/lightbox-plus-jquery.min.js"></script>';
            }
            return $retorno;
        }
        
}