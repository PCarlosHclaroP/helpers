<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
include_once base_cwd().'/classes/db.php';
class Arquivos_model extends DB{
    
    public function __construct() 
    {
        parent::__construct();
    }
    
    
    public function adicionar_image_arquivo($data = array()) 
    {
        $dados['tabela'] = 'image_arquivo';
        $dados['dados'] = $data;
        return $this->adicionar($dados);
    }
    
    public function adicionar_image_pai($data = array())
    {
        $dados['tabela'] = 'image_pai';
        $dados['dados'] = $data;
        return $this->adicionar($dados);
        
    }
    
    public function adicionar_image_arquivo_has_usuarios($data = array())
    {
        $dados['tabela'] = 'image_arquivo_has_usuarios';
        $dados['dados'] = $data;
        return $this->adicionar($dados);
        
    }
    
    public function editar_image_pai($id, $item) 
    {
        $dados['tabela'] = 'image_pai';
        $dados['dados'] = $item;
        $dados['where'] = 'image_pai.id = '.$id;
        return $this->editar($dados);
    }
    
    public function deletar_image_arquivo($id) 
    {
        $dados['tabela'] = 'image_arquivo';
        $dados['where'] = 'image_arquivo.id = '.$id;
        return $this->deletar($dados);
    }
    
    public function deletar_image_arquivo_has_usuarios($id) 
    {
        $dados['tabela'] = 'image_arquivo_has_usuarios';
        $dados['where'] = 'image_arquivo_has_usuarios.id_image_arquivo = '.$id;
        return $this->deletar($dados);
    }
    
    public function deletar_image_arquivo_has_usuarios_total($filtro) 
    {
        $dados['tabela'] = 'image_arquivo_has_usuarios';
        $dados['where'] = $filtro;
        return $this->deletar($dados);
    }
    
    public function deletar_image_pai($id)
    {
        $dados['tabela'] = 'image_pai';
        $dados['where'] = 'image_pai.id = '.$id;
        return $this->deletar($dados);
        
    }
    
    public function get_item_tipo_por_tipo( $tipo )
    {
        $dados['coluna'] = '*';
        $dados['tabela'] = 'image_tipo';
        $dados['where'] = 'image_tipo.tipo like "'.$tipo.'"';
        $retorno = $this->select($dados);
        return isset($retorno['itens'][0]) ? $retorno['itens'][0] : FALSE;
        
    }
    
    public function get_arquivo_has_usuarios( $id_arquivo, $id_usuario = FALSE )
    {
        $data['coluna'] = 'image_arquivo_has_usuarios.id_image_arquivo as id_arquivo';
        $data['coluna'] .= ', usuarios.id as id_usuario';
        $data['coluna'] .= ', usuarios.nome as nome';
        $data['tabela'][] = array('nome' => 'image_arquivo_has_usuarios');
        $data['tabela'][] = array('nome' => 'usuarios', 'tipo' => 'INNER', 'where' => 'image_arquivo_has_usuarios.id_usuarios = usuarios.id' );
        $data['where'] = 'image_arquivo_has_usuarios.id_image_arquivo = '.$id_arquivo;
        if ( $id_usuario )
        {
            $data['where'] .= ' AND image_arquivo_has_usuarios.id_usuarios = '.$id_usuario;
        }
        $data['order'] = 'usuarios.nome ASC';
        $usuarios = $this->select($data);
        return $usuarios;
    }
    
    public function get_itens( $id_empresa )
    {
        $data['coluna'] = 'image_arquivo.arquivo as arquivo';
        $data['coluna'] .= ', image_arquivo.id as id_arquivo';
        $data['coluna'] .= ', image_arquivo.data as data';
        $data['coluna'] .= ', image_arquivo.id_empresa as id_empresa';
        $data['coluna'] .= ', image_tipo.pasta as pasta';
        $data['coluna'] .= ', image_pai.id as id';
        $data['coluna'] .= ', image_pai.titulo as titulo';
        $data['coluna'] .= ', image_pai.descricao as descricao';
        $data['tabela'][] = array('nome' => 'image_arquivo');
        $data['tabela'][] = array('nome' => 'image_pai', 'tipo' => 'INNER', 'where' => 'image_pai.id_image_arquivo = image_arquivo.id' );
        $data['tabela'][] = array('nome' => 'image_tipo', 'tipo' => 'INNER', 'where' => 'image_pai.id_image_tipo = image_tipo.id' );
        $data['where'] = 'image_arquivo.id_empresa = '.$id_empresa;
        $data['order'] = 'image_arquivo.data DESC';
        $usuarios = $this->select($data);
        return $usuarios;
    }
    
    public function get_itens_por_filtro( $filtro )
    {
        $data['coluna'] = 'image_arquivo.arquivo as arquivo';
        $data['coluna'] .= ', image_arquivo.id as id_arquivo';
        $data['coluna'] .= ', image_arquivo.data as data';
        $data['coluna'] .= ', image_arquivo.id_empresa as id_empresa';
        $data['coluna'] .= ', image_tipo.pasta as pasta';
        $data['coluna'] .= ', image_pai.id as id';
        $data['coluna'] .= ', image_pai.titulo as titulo';
        $data['coluna'] .= ', image_pai.descricao as descricao';
        $data['tabela'][] = array('nome' => 'image_arquivo');
        $data['tabela'][] = array('nome' => 'image_pai', 'tipo' => 'INNER', 'where' => 'image_pai.id_image_arquivo = image_arquivo.id' );
        $data['tabela'][] = array('nome' => 'image_tipo', 'tipo' => 'INNER', 'where' => 'image_pai.id_image_tipo = image_tipo.id' );
        $data['where'] = $filtro;
        $data['order'] = 'image_arquivo.data DESC';
        $usuarios = $this->select($data);
        return $usuarios;
    }
    
    public function get_itens_por_usuario( $id_usuario )
    {
        $data['coluna'] = 'image_arquivo.arquivo as arquivo';
        $data['coluna'] .= ', image_arquivo.id as id_arquivo';
        $data['coluna'] .= ', image_arquivo.data as data';
        $data['coluna'] .= ', image_arquivo.id_empresa as id_empresa';
        $data['coluna'] .= ', image_tipo.pasta as pasta';
        $data['coluna'] .= ', image_pai.id as id';
        $data['coluna'] .= ', image_pai.titulo as titulo';
        $data['coluna'] .= ', image_pai.descricao as descricao';
        $data['tabela'][] = array('nome' => 'image_arquivo');
        $data['tabela'][] = array('nome' => 'image_arquivo_has_usuarios', 'tipo' => 'INNER', 'where' => 'image_arquivo_has_usuarios.id_image_arquivo = image_arquivo.id' );
        $data['tabela'][] = array('nome' => 'image_pai', 'tipo' => 'INNER', 'where' => 'image_pai.id_image_arquivo = image_arquivo.id' );
        $data['tabela'][] = array('nome' => 'image_tipo', 'tipo' => 'INNER', 'where' => 'image_pai.id_image_tipo = image_tipo.id' );
        $data['where'] = 'image_arquivo_has_usuarios.id_usuarios = '.$id_usuario;
        $data['order'] = 'image_pai.titulo DESC';
        $data['group'] = 'image_arquivo.id';
        $usuarios = $this->select($data);
        return $usuarios;
    }
    
    public function get_item( $id )
    {
        $data_usuarios['colunas'] = '*';
        $data_usuarios['tabela'] = 'usuarios';
        $data_usuarios['where'] = 'usuarios.id = '.$id;
        $usuarios = $this->select($data_usuarios);
        return isset($usuarios['itens'][0]) ? $usuarios['itens'][0] : FALSE;
    }
    
}