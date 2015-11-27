<?php
include_once base_cwd().'/classes/libraries/monta_topo.php';
include_once base_cwd().'/classes/libraries/monta_rodape.php';
include_once base_cwd().'/classes/libraries/imoveis_popdetalhes.php';
include_once base_cwd().'/classes/libraries/views.php';
include_once base_cwd().'/imoveis_library.php';
include_once base_cwd().'/classes/db.php';

class Imovel 
{
    public $data = array();
    public $imoveis_popdetalhes = NULL;
    private $topo = NULL;
    private $item = array();
    private $db = array();
    
    public function __construct( $data = NULL ) 
    {
        $this->views = new Views();
        $this->db = new DB();
        //var_dump($data);die();
        $this->imoveis_popdetalhes = new Imoveis_popdetalhes($data['id_imovel'], ( $data['dados'] ? $data['dados'] : NULL ), $data['params'], $data['empresa'] );
        unset($data['params']);
        $this->data = $data;
        if ( $this->imoveis_popdetalhes->parametros->modelo_detalhe == 4 )
        {
            $topo = new Monta_topo($this->data['empresa'], (array)$this->imoveis_popdetalhes->parametros, NULL, 'imovel');
            $rodape = new Monta_rodape($this->data['empresa'], (array)$this->imoveis_popdetalhes->parametros);
            $library = new Imoveis_destaque((array)$this->data['empresa'], (array)$this->imoveis_popdetalhes->parametros);
            $this->data['topo']['includes'] = $topo->set_includes();
            $this->data['topo']['includes'] .= $this->imoveis_popdetalhes->set_head( FALSE, FALSE, TRUE );
            $this->data['topo']['html'] = $topo->set_topo_html();
            $this->data['topo']['img'] = $topo->get_img();
            $this->data['documentacao'] = $this->set_documentacao();
            $this->data['mapa'] = $this->imoveis_popdetalhes->set_mapa('1100', '330');
            $this->data['imovel'] = $this->imoveis_popdetalhes->imovel;
            $this->data['empresa'] = $this->imoveis_popdetalhes->empresa;
            $data_css['parametros'] = $this->imoveis_popdetalhes->parametros;
            $data_css['pagina'] = '/css/imovel_modelo_4';
            $this->data['topo']['includes_b'] = $this->views->get_html( $data_css, TRUE );
            $this->data['precos'] = $this->imoveis_popdetalhes->set_precos();
            $this->data['galeria'] = $this->imoveis_popdetalhes->set_galeria( $this->verifica_qtde_images() );
            $this->data['formulario'] = $this->imoveis_popdetalhes->set_formulario_contato(base_url().'imovel/'.$data['id_imovel'].'/');
            $this->data['rodape'] = $rodape->set_rodape( TRUE );
            $this->data['relacionados'] = $library->set_destaques( $this->data['imovel']->id_cidade, 4);
            
        }
        $this->set_contato();
        $this->set_views();
        $this->data['pagina'][] = '/classes/views/imovel_modelo_4';
        $this->views->get_html( $this->data );
        
        
    }
    
    private function verifica_qtde_images()
    {
        $contador = 0;
        for( $a = 1; $a <= $this->data['empresa']->pagina_limite_ofertas; $a++ )
        {
            $foto = 'foto'.$a;
            $e = $this->data['imovel']->$foto;
            if ( isset($e) && ! empty( $e ) )
            {
                $contador++;
            }
        }
        return $contador;
    }
    
    private function set_contato()
    {
        if ( isset($_POST['nome']) )
        {
            //var_dump($_POST);die();
            $post = isset($_POST) ? $_POST : FALSE;
            if ( $post )
            {
                foreach( $post as $chave_post => $valor_post )
                {
                    $data[$chave_post] = stripslashes($valor_post);
                    //var_dump( $$chave_post );
                }


            }
            $data['dados'] = (array)$this->imoveis_popdetalhes->imovel;
            $data['pagina'] = 'imoveis_envia_email_sms.inc.php';
            $this->views->get_html( $data, TRUE );
        }
    }
    
    private function set_views()
    {
        if( ! ( isset( $_GET['addcart'] ) && $_GET['addcart'] )  AND ! ( isset( $_GET['remsel'] ) && $_GET['remsel'] ) ) 
        {
            $this->imoveis_popdetalhes->set_imovel_views();
            $this->imoveis_popdetalhes->set_pageviews();
        }

    }
    
    
    private function set_documentacao ( )
    {
        $data['coluna'] = 'image_pai.id, image_pai.titulo, image_pai.descricao, image_tipo.pasta, image_arquivo.arquivo as arquivo';
        $data['tabela'][] = array('nome' => 'image_pai');
        $data['tabela'][] = array('nome' => 'image_tipo', 'tipo' => 'INNER', 'where' => 'image_tipo.id = image_pai.id_image_tipo' );
        $data['tabela'][] = array('nome' => 'image_arquivo', 'tipo' => 'INNER', 'where' => 'image_arquivo.id = image_pai.id_image_arquivo' );
        $data['tabela'][] = array('nome' => 'imoveis', 'tipo' => 'INNER', 'where' => 'imoveis.id = image_pai.id_pai' );
        $data['where'] = 'imoveis.id = '.$this->data['id_imovel'];
        $documento = $this->db->select($data);
        return isset($documento) && $documento['qtde'] > 0 ? $documento['itens'] : NULL;
    }
    
}
