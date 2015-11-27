<?php

include_once base_cwd().'/classes/db.php';

class Config_Model extends DB
{
    public function __construct() 
    {
        parent::__construct();
    } 
    
    public function set_empresa()
    {
        $data = array(
            'coluna'    => ' empresas.id as id, empresas.mobile as mobile, hotsite_parametros.inicial_tipo as tipo, hotsite_parametros.inicial_valor as valor, hotsite_parametros.inicial_adicional as adicional, empresas.bloqueado as bloqueado, hotsite_parametros.url_amigavel as url_amigavel',
            'tabela'    => array(
                                    array('nome' => 'empresas'),
                                    array('nome' => 'hotsite_parametros', 'where' => 'empresas.id = hotsite_parametros.id_empresa', 'tipo' => 'INNER')
                                ),
            'where'     => array(
                                array( 'campo' => 'empresas.empresa_dominio', 'tipo' => ' LIKE ', 'valor' => '"%'.WWW.'"' ),
                                ),

        );
        $retorno = $this->select($data);
        if ( ! $retorno )
        {
            $data['where'] = array(array( 'campo' => 'empresas.empresa_dominio', 'tipo' => ' LIKE ', 'valor' => '"%'.WWW.'/"' ));
            $retorno = $this->select($data);
        }
        return isset($retorno['itens'][0]->id) ? $retorno['itens'][0] : FALSE ; 
    }
    
}
 