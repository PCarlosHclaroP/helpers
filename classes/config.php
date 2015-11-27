<?php

include_once base_cwd().'/classes/model/config_model.php';

/**
 * Classe responsavel pela configuraÃ§Ã£o do sistema de direcionamento de url
 * @author Carlos Claro <carlos@carlosclaro.com.br>
 * @version 1.0
 * 
 */
class Config extends Config_Model
{
    private $empresa = FALSE;
    private $uri = array();
    
    
    public function __construct() 
    {
        parent::__construct();
    } 

    
    
    /**
     * Verifica os parametros de retorno
     * @access public
     */
    public function verificar ()
    { 
        $this->empresa = $this->set_empresa();
        if ( $this->empresa )
        {
            if ( $this->empresa->bloqueado == 1 )
            { 
                echo '<!DOCTYPE HTML"><html><head><meta charset="UTF-8" ></head><body><a href="http://www.powinternet.com" >Este site é Hospedado por POW Internet.<br><br>Desde 1999 criando soluções para você.</a></body></html>';
            }
            elseif ( $this->empresa->url_amigavel )
            {
                if ( isset( $_SERVER['PATH_INFO'] ) )
                {
                    $path = $_SERVER['PATH_INFO'];
                    $primeiro_caracter = substr($path, 0, 1 );
                    if ( $primeiro_caracter == '/' )
                    {
                        $path = substr($path, 1);
                    }
                    $this->uri = explode('/',$path);
                }
                else
                {
                    $this->uri[] = 'imoveis';//$this->empresa->tipo;
                    //$this->uri[] = '';//$this->empresa->valor;
                    //$this->uri[] = '';//$this->empresa->adicional;
                    
                }
                include_once BASE.'classes/controller/servicos.php';
                $servico = new Servicos($this->uri);
                //echo time();
                //echo '<br>'.mktime('04', '20', 0, 8, 20, 2015);
                //var_dump($this->uri);
                //var_dump($_SERVER);
                //var_dump($this->empresa);
            }
            else
            {
                $url = $this->_monta_url();
                header( "HTTP/1.1 301 Moved Permanently" );
                header("Location: ".$url." ");
                exit;
            }
            
        }
        else
        {
            echo '<!DOCTYPE HTML"><html><head><meta charset="UTF-8" ></head><body><a href="http://www.powinternet.com" >Este site é Hospedado por POW Internet.<br><br>Desde 1999 criando soluções para você.</a></body></html>';
            exit;
        }
    }
    
    
    private function _monta_url ()
    {
        $url = base_url();
        
        if (  strstr(strtolower($_SERVER['HTTP_USER_AGENT']), 'mobile') &&  $this->empresa->mobile == 1 )
        {
            if ( isset($_GET['classico']) && $_GET['classico'] == 1 )
            {
                $url .= 'hotsite.php?id='.$this->empresa->id.'&'.$this->empresa->tipo.'='.$this->empresa->valor.( ( $this->empresa->valor == 'album' ) ? '&alb='.$this->empresa->adicional : '' );
            }
            else
            {
                $url .= 'm';
            }
        }
        else
        {
            $url .= 'hotsite.php?id='.$this->empresa->id.'&'.$this->empresa->tipo.'='.$this->empresa->valor.( ( $this->empresa->valor == 'album' ) ? '&alb='.$this->empresa->adicional : '' );
        }
        return $url;
    }
    
    private function routes( $data )
    {
        $retorno = array();
        return $retorno;
    }
}   

