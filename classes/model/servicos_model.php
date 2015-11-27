<?php

include_once BASE.'classes/db.php';

class Servicos_Model extends DB
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
    
    public function get_empresa()
    {
        $data = array(
            'coluna'    => '*',
            'tabela'    => array(
                                    array('nome' => 'empresas'),
                                    //array('nome' => 'hotsite_parametros', 'where' => 'empresas.id = hotsite_parametros.id_empresa', 'tipo' => 'INNER')
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
    
    public function get_parametros()
    {
        $data = array(
            'coluna'    => 'hotsite_parametros.*',
            'tabela'    => array(
                                    array('nome' => 'hotsite_parametros'),
                                    array('nome' => 'empresas', 'where' => 'empresas.id = hotsite_parametros.id_empresa', 'tipo' => 'INNER')
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
        return isset($retorno['itens'][0]) ? $retorno['itens'][0] : FALSE ; 
    }
    
    public function get_pagina_por_link_id_empresa( $link, $id_empresa)
    {
        $data = array(
            'coluna'    => 'hotsite_paginas.*',
            'tabela'    => array(
                                    array('nome' => 'hotsite_paginas'),
                                    array('nome' => 'empresas', 'where' => 'empresas.id = hotsite_paginas.id_empresa', 'tipo' => 'INNER')
                                ),
            'where'     => array(
                                array( 'campo' => 'empresas.id', 'tipo' => ' = ', 'valor' => $id_empresa ),
                                array( 'campo' => 'hotsite_paginas.link', 'tipo' => ' like ', 'valor' => '"'.$link.'"', 'operador' => ' AND' ),
                                array( 'campo' => 'hotsite_paginas.ativa', 'tipo' => ' = ', 'valor' => 1, 'operador' => ' AND' ),
                                ),

        );
        $retorno = $this->select($data);
        
        return isset($retorno['itens'][0]) ? $retorno['itens'][0] : FALSE ; 
    }
    
    public function get_cidade_por_link( $link )
    {
        $data = array(
            'coluna'    => 'cidades.id as id',
            'tabela'    => array(
                                    array('nome' => 'cidades'),
                                ),
            'where'     => array(
                                array( 'campo' => 'cidades.link', 'tipo' => ' like ', 'valor' => '"'.$link.'"' ),
                                ),

        );
        $retorno = $this->select($data);
        
        return isset($retorno['itens'][0]->id) ? $retorno['itens'][0]->id : FALSE ; 
    }
    public function get_cidade_completa_por_link( $link )
    {
        $data = array(
            'coluna'    => 'cidades.*',
            'tabela'    => array(
                                    array('nome' => 'cidades'),
                                ),
            'where'     => array(
                                array( 'campo' => 'cidades.link', 'tipo' => ' like ', 'valor' => '"'.$link.'"' ),
                                ),

        );
        $retorno = $this->select($data);
        
        return isset($retorno['itens'][0]) ? $retorno['itens'][0] : FALSE ; 
    }
    
    public function get_tipo_por_link( $link )
    {
        $data = array(
            'coluna'    => 'imoveis_tipos.*',
            'tabela'    => array(
                                    array('nome' => 'imoveis_tipos'),
                                ),
            'where'     => array(
                                array( 'campo' => 'imoveis_tipos.link', 'tipo' => ' like ', 'valor' => '"'.$link.'"' ),
                                ),

        );
        $retorno = $this->select($data);
        
        return isset($retorno['itens'][0]->id) ? $retorno['itens'][0]->id : FALSE ; 
    }
    
    public function get_imovel( $id_imovel )
    {
        $data = array(
            'coluna'    => 'imoveis.*,'
                        . 'cidades.nome as cidade,'
                        . 'cidades.uf as uf,'
                        . 'logradouros.logradouro as logradouro,'
                        . 'imoveis_tipos.nome as imovel_tipo,'
                        . 'imoveis_estilos.nome as imovel_estilo,'
                        . 'imoveis_tipos.nome as cidade,',
            'tabela'    => array(
                                    array('nome' => 'imoveis'),
                                    array('nome' => 'imoveis_tipos', 'tipo' => 'LEFT', 'where' => 'imoveis.id_tipo = imoveis_tipos.id'),
                                    array('nome' => 'imoveis_estilos', 'tipo' => 'LEFT', 'where' => 'imoveis.id_estilo = imoveis_estilos.id'),
                                    array('nome' => 'cidades', 'tipo' => 'LEFT', 'where' => 'imoveis.id_cidade = cidades.id'),
                                    array('nome' => 'logradouros', 'tipo' => 'LEFT', 'where' => 'imoveis.id_logradouro = logradouros.id'),
                                ),
            'where'     => array(
                                array( 'campo' => 'imoveis.id', 'tipo' => ' = ', 'valor' => $id_imovel ),
                                ),

        );
        $retorno = $this->select($data);
        
        return isset($retorno['itens'][0]->id) ? (array)$retorno['itens'][0] : FALSE ; 
        
    }
    
}
 