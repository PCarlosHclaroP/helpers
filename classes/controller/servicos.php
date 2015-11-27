<?php

include_once BASE.'classes/model/servicos_model.php';
include_once BASE.'classes/libraries/views.php';
class Servicos extends Servicos_Model
{
    private $empresa = FALSE;
    private $parametros = FALSE;
    private $uri = array();
    private $views = NULL;
    
    
    public function __construct( $uri ) 
    {
        parent::__construct();
        $this->views = new Views();
        $this->uri = $uri;
        $this->empresa = $this->get_empresa();
        $this->parametros = $this->get_parametros();
        $this->inicia_pagina();
    } 
    
    public function inicia_pagina ( )
    {
        if ( isset($this->uri[0]) && ! empty($this->uri[0]) )
        {
            $pagina = $this->get_pagina_por_link_id_empresa($this->uri[0], $this->empresa->id);
            if ( isset($pagina) && $pagina )
            {
                $data['id_pagina'] = $pagina->id;
                $data['servico'] = '';
            }
            else
            {
                switch( $this->uri[0] )
                {
                    case 'imoveis':
                        $data['servico'] = 'menu_tipos';
                        if ( isset($this->uri[1]) && ! empty($this->uri[1]) )
                        {
                            switch( $this->uri[1] )
                            {
                                case 'venda':
                                case 'locacao':
                                case 'locacao_dia':
                                    $data['quero'] = $this->uri[1];
                                    break;
                                default:
                                    $data['id_cidade'] = $this->get_cidade_por_link($this->uri[1]);
                                    $data['cidade'] = $this->get_cidade_completa_por_link($this->uri[1]);
                                    break;
                            }
                            
                        }
                        if ( isset($this->uri[2]) && ! empty($this->uri[2]) )
                        {
                            switch( $this->uri[2] )
                            {
                                case 'venda':
                                case 'locacao':
                                case 'locacao_dia':
                                    $data['quero'] = $this->uri[2];
                                    break;
                                default:
                                    $data['id_tipo'] = $this->get_tipo_por_link($this->uri[2]);
                                    break;
                            }
                        }
                        if ( isset($this->uri[3]) && ! empty($this->uri[3]) )
                        {
                            $data['bairro'] = $this->get_tipo_por_link($this->uri[2]);
                        }
                        break;
                    case 'imprime_selecao':
                        $data['pagina'] = 'imoveis_imprimeselecao';
                        break;
                    case 'imovel':
                    	if ( $this->parametros->modelo_detalhe == 4 )
                    	{
                            include_once base_cwd().'/classes/controller/imovel.php';
                            $data['controller'] = 'imovel';
                    	}
                    	else
                    	{
	                        $data['pagina'] = 'imoveis_popdetalhes';
                    	}
                        if( isset( $this->uri[1] ) && ! empty( $this->uri[1] ) )
                        {
                            $data['id_imovel'] = $this->uri[1];
                            $data['dados'] = $this->get_imovel($data['id_imovel']);
                        }
                        break;
                    case 'comparacao':
                        $data['servico'] = 'selecionadas';
                        break;
                    case 'busca':
                        $data['servico'] = 'busca';
                        break;
                    case 'busca_avancada':
                        $data['servico'] = 'busca';
                        $data['avancada'] = 1;
                        break;
                }
            }
        }
        else
        {
            $data['servico'] = 'menu_tipos';
        }
        
        $data['params'] = (array)$this->parametros;
        $data['empresa'] = (array)$this->empresa;
        $data['id'] = $this->empresa->id;
        if ( isset( $data['controller'] ) )
        {
            //$imovel = new Imovel($data);
            $var = $data['controller'];
            $var = new $var($data);
        }
        else
        {
            $this->views->get_html($data);
        }
    }
    
    
}