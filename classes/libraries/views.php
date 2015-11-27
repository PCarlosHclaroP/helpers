<?php
include_once base_cwd().'/classes/db.php';
class Views
{
    public function __construct() 
    {
        
    }
    
    public function get_html( $data = FALSE, $retorno = FALSE )
    {
        ob_start();
        if ( isset($data['pagina']) )
        {
            $paginas = $data['pagina'];
            unset($data['pagina']);
        }
        else
        {
            $paginas = 'hotsite.php';
        }
        foreach( $data as $chave => $valor )
        {
            $$chave = $valor;
        }
        if ( ! is_array($paginas) )
        {
            $pagina = strstr($paginas, '.php') ? $paginas : $paginas.'.php';
            include_once ( strstr( $pagina, 'http://' ) ? '' : BASE ) . $pagina;
        }
        else
        {
            foreach( $paginas as $p )
            {
                $pagina = strstr($p, '.php') ? $p : $p.'.php';
                include_once BASE.$pagina;
            }
        }
        $output = ob_get_contents();
        ob_clean();
        if ( $retorno )
        {
            return $output;
        }
        else
        {
            echo $output;
        }
    }
    
}