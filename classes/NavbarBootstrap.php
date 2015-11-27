<?php
/**
 * Classe responsável por criacão da Navbar
 * @author William Andrade
 * @version 0.1
 */

include_once 'db.php';

class NavbarBootstrap extends DB{
    var $parametros;    
    var $id;

    
    
    public function inicializa ($id)
    {
        $this->id = $id;
        $this->valorar();
    }    
    
    private function valorar()
    {
        $data = array(
            'coluna' => '*',
            'tabela' => 'guiasjp.hotsite_paginas',
            'where' => "id_empresa='$this->id' AND ativa = 1",
        );
        
        $this->parametros = $this->select($data);
        
    }
    
    public function testar(){
        var_dump($this->id);
    }
  
    public function get_html(){
        $retorno = '';
        $retorno .= '<tr>'.PHP_EOL;
        $retorno .= '<td align="center">'.PHP_EOL;
        $retorno .= '<div class="navbar">'.PHP_EOL;
        $retorno .= '<div class="navbar-inner">'.PHP_EOL;
        $retorno .= '<ul class="nav">'.PHP_EOL;
        if($this->parametros){
            foreach($this->parametros['itens'] as $pa){
                $retorno .= '<li><a href="hotsite.php?id='.$this->id.'&id_pagina='.$pa->id.'&testando=1">'.$pa->nome_pagina.'</a></li>'.PHP_EOL;
            }    
        }
        $retorno .= '<li><a href="hotsite.php?id='.$this->id.'&servico=contato&testando=1">Contato</a></li>'.PHP_EOL;
        $retorno .= '</ul></div></div></td></tr>';
        return $retorno;
    }
    
    
    
}


?>


