<?php
/**
 * Classe responsável pela manipulação de banco de dados
 * @access public
 * @author Carlos Claro <carlos@carlosclaro.com.br> 
 * @copyright (c) 2013, Carlos Claro
 * @version 1.0
 * @package classes
 */
class DB
{ 
    private $conn_mysqli = FALSE;
    /**
     * Endereço host
     * @var host
     * @access protected
     */
    protected $host =  'mysql.guiasjp.com';//'mysql.isaojose.com';
    protected $host_local =  'localhost';//'mysql.isaojose.com';
    /**
     * usuario db
     * @var user
     * @access protected
     */
    protected $user = 'guiasjp';//'isaojose01';
    protected $user_local = 'root';//'isaojose01';
    /**
     * senha db
     * @var password
     * @access protected
     */
    protected $password = 'aqua09';//'b4125sj';
    protected $password_local = '';//'b4125sj';
    /**
     * database default
     * @var database
     * @access protected
     */
    protected $database = 'guiasjp';//'isaojose01';
    /**
     * Variavel de conexão ao banco de dados
     * @var conn
     * @access private
     */
    public $conn;
    
    /**
     * Função de construção
     * @param type $db
     * @return this
     */
    public function __construct(  ) 
    {
    	
        if ( strstr($_SERVER['HTTP_HOST'], 'localhost') )
        {
            $this->conn = new mysqli($this->host_local, $this->user_local, $this->password_local, $this->database, 3306);
            if ( $this->conn->connect_errno )
            {
                echo "N�o foi possivel se conectar ao MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
            }
            $this->conn_mysqli = TRUE;
            //echo $this->conn->host_info;
//$this->conn = @mysql_connect($this->host_local, $this->user_local, $this->password_local );
        }
        else
        {
            $this->conn = new mysqli($this->host, $this->user, $this->password, $this->database, 3306);
            if ( $this->conn->connect_errno )
            {
                echo "N�o foi possivel se conectar ao MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
            }
            $this->conn_mysqli = TRUE;
            //$this->conn = @mysql_connect($this->host, $this->user, $this->password );
            //mysql_select_db($this->database);
        }
        if ( ! $this->conn )
        {
            die('Falha na conexão: ' . mysql_error() );
        }
        return $this;
    }
    
    public function teste()
    {
        return $this->host;
    }
    
    /**
     * Função que seta dados no db
     * @param array $data [ contem: coluna, tabela, where, group, order, limit, offset  ]
     * @return array
     */
    public function select ( $data, $debug = FALSE, $tipo_retorno = 'object' )
    {
        $consulta = 'SELECT ';
        if ( isset( $data['coluna'] ) )
        {
            
            if ( is_array( $data['coluna'] ) )
            {
                $consulta .= implode(', ', $data['coluna'] );
            }
            else
            {
                $consulta .= $data['coluna'];
            }
        }
        else
        {
            $consulta .= ' * ';
        }
        if ( isset( $data['tabela'] ) )
        {
            $consulta .= ' FROM ';
            if ( is_array( $data['tabela'] ) )
            {
                $consulta .= $data['tabela'][0]['nome'];
                unset( $data['tabela'][0] );
                foreach ( $data['tabela'] as $join )
                {
                    $consulta .=  ( ( isset( $join['tipo'] ) ) ? ' '.$join['tipo'].' JOIN ' : ' INNER JOIN ' ).$join['nome'].' ON '.$join['where'];
                }
            }
            else
            {
                $consulta .= $data['tabela'];
            }
        }
        else
        {
            $consulta .= '';
        }
        if ( isset( $data['where'] ) )
        {
            $consulta .= ' WHERE ';
            if ( is_array( $data['where'] ) )
            {
                foreach ( $data['where'] as $chave_where => $where )
                {
                    if ( is_array($where) )
                    {
                        $consulta .= ( ( isset( $where['operador'] ) ) ? $where['operador'] : '' ).' '.$where['campo'].' '.$where['tipo'].' '.$where['valor'];
                    }
                    else
                    {
                        $consulta .= ($chave_where > 0 ? ' AND ' : '').$where;
                    }
                }
            }
            else
            {
                $consulta .= $data['where'];
            }
        }
        if ( isset( $data['group'] ) )
        {
            $consulta .= ' GROUP BY '.$data['group'];
        }
        if ( isset( $data['order'] ) )
        {
            $consulta .= ' ORDER BY ';
            if ( is_array( $data['order'] ) )
            {
                $consulta .= $data['order']['campo'].' '.$data['order']['sequencia'];
            }
            else
            {
                $consulta .= $data['order'];
            }
        }
        if ( isset( $data['limit'] ) )
        {
            $consulta .= ' LIMIT '.( ( isset( $data['offset'] ) ) ? $data['offset'].', ' : '' ).$data['limit'];
        }
        if ( $debug )
        {
            echo $consulta.  PHP_EOL;
        }
        
        $retorno = $this->_retorna_object( $consulta, $tipo_retorno );
        return $retorno;
    }
    
    /**
     * Função que adiciona dados ao db
     * @param array $data [contem: tabela, dados]
     * @return integer
     */
    public function adicionar ( $data , $option = TRUE ,$debug = FALSE)
    {
        
	$sql = "INSERT INTO ".$data['tabela']." ";
	foreach ( $data['dados'] as $chave => $valor )
	{
            $chaves[] = $chave;
            $valores[] = ( ( is_int( $valor ) ) ? $valor : "'".$valor."'" ); 
	}
	$sql .= "(".implode(', ', $chaves ).") VALUES (".implode(', ', $valores ).");";
        if($debug)
        {
            var_dump($sql);
        }
	if ( $this->conn_mysqli )
        {
            $result = $this->conn->query($sql);
            if ( $result )
            {
                return $this->conn->insert_id;
            }
            else
            {
                return $result;
                
            }
        }
        else
        {
            $result = mysql_query($sql, $this->conn);
            if($option)
            {
                return mysql_insert_id();
            }
            else
            {
                return mysql_affected_rows();
            }
        }
    }

    /**
     * edita itens no db
     * @param array $data [ dados, tabela ]
     * @return integer affected rows
     */
    public function editar ( $data, $debug = FALSE )
    {
        $sql = "UPDATE ".$data['tabela']." SET ";
	foreach ( $data['dados'] as $chave => $valor )
	{
		$valores[] = " ".$chave." = ".( ( is_int( $valor ) ) ? $valor : "'".$valor."'" )." ";
	}
	$sql .= implode(', ', $valores);
	$sql .= " WHERE ".$data['where'];
        if(isset($debug) && $debug)
        {
            var_dump($sql);
        }
        
	if ( $this->conn_mysqli )
        {
            $result = $this->conn->query($sql);
            return $this->conn->affected_rows;
        }
        else
        {
            $result = mysql_query($sql);
            return mysql_affected_rows();
        }
    }
    /**
     * deleta itens no db
     * @param array $data [ where, tabela ]
     * @return integer affected rows
     */
    public function deletar ( $data, $debug = FALSE )
    {
	$sql = "DELETE FROM ".$data['tabela']." WHERE ".$data['where']." ;";
        if(isset($debug) && $debug)
        {
            var_dump($sql);
        }
        
	if ( $this->conn_mysqli )
        {
            $result = $this->conn->query($sql);
            return $this->conn->affected_rows;
        }
        else
        {
            $result = mysql_query($sql);
            return mysql_affected_rows();
        }
    }

    /**
     * Gera objeto para retorno de ação
     * @param string $sql
     * @return array / bolean
     */
    public function _retorna_object( $sql = '', $tipo_retorno = 'object' )
    {
	if ( ! empty($sql) )
	{
            if ( $this->conn_mysqli )
            {
                $result = $this->conn->query($sql);
                //var_dump($result);
                if ( isset($result->num_rows) && $result->num_rows > 0 )
                {
	                $linhas['qtde'] = $result->num_rows;
	                while ( $valor = $result->fetch_assoc() )
	                {
	                    //var_dump($valor);
	                    if ( $tipo_retorno == 'object' )
	                    {
		                    $linhas['itens'][] = (object)$valor;
	                    }
	                    else
	                    {
		                    $linhas['itens'][] = $valor;
	                    }
	                }
                }
                else
                {
                	$linhas = FALSE;
                }
                //var_dump($linhas);
                $retorno = $linhas;
            }
            else
            {
                $result = mysql_query($sql, $this->conn);
                if( $result && ( mysql_num_rows( $result ) > 0 ) )
                {
                    $linhas['qtde'] = mysql_num_rows( $result );
                    while( $row = mysql_fetch_assoc( $result ) )
                    {	
                        $linhas['itens'][] = (object)$row;
                    }
                    mysql_free_result( $result );
                    $retorno = $linhas;
                }
                else
                {
                    $retorno = FALSE;
                }
            }
	}
        else
        {
            $retorno = FALSE;
        }
        //var_dump($retorno);
        return $retorno;
    }

    public function converte_data_mysql($data)
    {
            $data_explode = explode(' ', $data);
            $data_i = explode('/',$data_explode[0]);
            $data = $data_i[2].'-'.$data_i[1].'-'.$data_i[0].' '.$data_explode[1];
            return $data;
    }
    public function converte_data_numero($data)
    {
        $data = $this->converte_data_mysql($data);
        $data = time($data);
        return $data;
    }
    public function converte_valor_mysql($valor)
    {
        
        $valor = str_replace('.', '', $valor);
        $valor = str_replace('R$', '', $valor);
        $valor = str_replace(',', '.', $valor);
        return $valor;
    }
    
    public function removerCodigoMalicioso ( $item = NULL, $completo = FALSE )
    {
        $retorno = NULL;
        if(isset($item) && $item)
        {
            $item = is_array($item) ? $item : array($item);
            foreach($item as $c => $v)
            {
                if(is_array($v))
                {
                    foreach($v as $value)
                    {
                        $value = addslashes($value);
                        $value = htmlspecialchars($value);
                        if ( $completo )
                        {
                            $value = preg_replace(("/(from|FROM|select|SELECT|insert|INSERT|delete|DELETE|where|WHERE|DROP TABLE|drop table|SHOW TABLES|show tables|ALTER TABLE|alter table| AND | and | OR | or |DATABASE|database| # |\*|--|\\\\)/"),"",$value);
                            $value = trim($value);
                        }
                        $retorno[$c][$value] = $value;
                    }
                }
                else
                {
                    $v = addslashes($v);
                    $v = htmlspecialchars($v);
                    if ( $completo )
                    {
                        $v = preg_replace(("/(from|FROM|select|SELECT|insert|INSERT|delete|DELETE|where|WHERE|DROP TABLE|drop table|SHOW TABLES|show tables|ALTER TABLE|alter table| AND | and | OR | or |DATABASE|database| # |\*|--|\\\\)/"),"",$v);
                        $v = trim($v);
                    }
                    $retorno[$c] = $v;
                }
            }
        }
        return $retorno;
    }
    
    public function verifica_login( $login, $senha )
    {
        if ( ( isset($login) && $login && ! empty($login) ) && ( isset($senha) && $senha && ! empty($senha) ) )
        {
                //coluna, tabela, where, group, order, limit, offset
                $data['coluna'] = 'usuarios.nome as nome';
                $data['tabela'] = 'usuarios';
                $data['where'] = array(
                                        array(  'campo' => 'senha',         'tipo' => 'LIKE',       'valor' => '"'.md5($senha).'"'  ),
                                        array(  'campo' => 'email',         'tipo' => 'LIKE',       'valor' => '"'.$login.'"',      'operador' => 'AND'),
                                        array(  'campo' => 'ativo',         'tipo' => '=',          'valor' => 1,           'operador' => 'AND'),
                                        );
                $data['limit'] = 1;
                $item = $this->select($data);
                if ( $item && isset($item) && $item['qtde'] > 0 )
                {
                    $retorno = $item['itens'][0];
                }
                else
                {
                    $retorno = NULL;
                }
            
        }
        else
        {
            $retorno = NULL;
        }
        return $retorno;
    }
    
    public function set_campo_select ( $itens )
    {
        
    }
    
    /**
     * Consulta e insere views no destaque
     * @param type $id_imovel
     * @param type $id_empresa
     * @param type $local
     * @param type $site
     * @return boolean
     */
    public function get_destaques_views ( $id_imovel, $id_empresa, $local= 'venda', $site = 'destaques' )
    {
        $data['coluna'] = '*';
        $data['tabela'] = 'imoveis_destaques_views';
        $data['where'][] = 'imoveis_destaques_views.id_empresa = '.$id_empresa;
        $data['where'][] = 'imoveis_destaques_views.id_imovel = '.$id_imovel;
        $data['where'][] = 'imoveis_destaques_views.mes = "'.date('n').'"';
        $data['where'][] = 'imoveis_destaques_views.ano = "'.date('Y').'"';
        $data['where'][] = 'imoveis_destaques_views.site = "'.$site.'"';
        $data['where'][] = 'imoveis_destaques_views.local = "'.$local.'"';
        $dados = $this->select($data);
        if ( isset($dados['itens']) && $dados['qtde'] > 0 )
        {
            //update
            $update['tabela'] = 'imoveis_destaques_views';
            $update['dados'] = array(
                                    'views'         => ( $dados['itens'][0]->views + 1 ), 
                                    );
            $update['where'] = 'imoveis_destaques_views.id = '.$dados['itens'][0]->id;
            $afetados_update = $this->editar($update);
            
        }
        else
        {
            //insert
            $insert['tabela'] = 'imoveis_destaques_views';
            $insert['dados'] = array(
                                    'views'         => 1, 
                                    'id_empresa'    => $id_empresa,
                                    'id_imovel'     => $id_imovel,
                                    'mes'           => date('n'),
                                    'ano'           => date('Y'),
                                    'local'         => $local,
                                    'site'          => $site
                                    );
            $id_insert = $this->adicionar($insert);
        }
        if ( ( isset($id_insert) && $id_insert ) || ( isset($afetados_update) && $afetados_update ) )
        {
            return TRUE;
        }
    }
    
    /**
     * implementado para adicionar ou inserir clicks aos destaques
     * @param type $id_imovel 
     * @param type $id_empresa 
     * @param type $local
     * @param type $site
     * @return boolean
     */
    public function get_destaques_clicks ( $id_imovel, $id_empresa, $local= 'venda', $site = 'destaques' )
    {
        $data['coluna'] = '*';
        $data['tabela'] = 'imoveis_destaques_views';
        $data['where'][] = 'imoveis_destaques_views.id_empresa = '.$id_empresa;
        $data['where'][] = 'imoveis_destaques_views.id_imovel = '.$id_imovel;
        $data['where'][] = 'imoveis_destaques_views.mes = "'.date('n').'"';
        $data['where'][] = 'imoveis_destaques_views.ano = "'.date('Y').'"';
        $data['where'][] = 'imoveis_destaques_views.site = "'.$site.'"';
        $data['where'][] = 'imoveis_destaques_views.local = "'.$local.'"';
        $dados = $this->select($data);
        if ( isset($dados['itens']) && $dados['qtde'] > 0 )
        {
            //update
            $update['tabela'] = 'imoveis_destaques_views';
            $update['dados'] = array(
                                    'clicks'         => ( $dados['itens'][0]->clicks + 1 ), 
                                    );
            $update['where'] = 'imoveis_destaques_views.id = '.$dados['itens'][0]->id;
            $afetados_update = $this->editar($update);
            
        }
        else
        {
            //insert
            $insert['tabela'] = 'imoveis_destaques_views';
            $insert['dados'] = array(
                                    'views'         => 1, 
                                    'clicks'         => 1, 
                                    'id_empresa'    => $id_empresa,
                                    'id_imovel'     => $id_imovel,
                                    'mes'           => date('n'),
                                    'ano'           => date('Y'),
                                    'local'         => $local,
                                    'site'          => $site
                                    );
            $id_insert = $this->adicionar($insert);
        }
        if ( ( isset($id_insert) && $id_insert ) || ( isset($afetados_update) && $afetados_update ) )
        {
            return TRUE;
        }
    }
    
    public function get_imovel_views ( $imovel )
    {
            //update
            $update['tabela'] = 'imoveis';
            $update['dados'] = array(
                                    'views'         => ( $imovel->views + 1 ), 
                                    );
            $update['where'] = 'imoveis.id = '.$imovel->id;
            return $this->editar($update);
            
        
    }
    
	public function set_tipo_por_id( $id, $coluna = '*' )
    {
        $data['coluna'] = $coluna;
        $data['tabela'] = 'imoveis_tipos';
        $data['where'] = 'id = '.$id;
        $retorno = $this->select($data);
        $retorno_coluna = $coluna != '*' ? $retorno['itens'][0]->$coluna : $retorno['itens'][0];
        return $retorno_coluna;
    }
    
    public function set_bairro_por_id( $id, $coluna = '*' )
    {
    	if ( isset($id) && $id )
    	{
    		$data['coluna'] = $coluna;
    		$data['tabela'] = 'bairros';
    		$data['where'] = 'id = '.$id;
    		$retorno = $this->select($data);
    		$retorno_coluna = $coluna != '*' ? $retorno['itens'][0]->$coluna : $retorno['itens'][0];
    	}
    	else
    	{
    		$retorno_coluna = FALSE;
    	}
    	return $retorno_coluna;
    }
    
    public function set_estilo_por_id( $id, $coluna = '*' )
    {
        $data['coluna'] = $coluna;
        $data['tabela'] = 'imoveis_estilos';
        $data['where'] = 'id = '.$id;
        $retorno = $this->select($data);
        $retorno_coluna = $coluna != '*' ? $retorno['itens'][0]->$coluna : $retorno['itens'][0];
        return $retorno_coluna;
    }
    
    public function set_cidade_por_id( $id, $coluna = '*' )
    {
        $data['coluna'] = $coluna;
        $data['tabela'] = 'cidades';
        $data['where'] = 'id = '.$id;
        $retorno = $this->select($data);
        $retorno_coluna = $coluna != '*' ? $retorno['itens'][0]->$coluna : $retorno['itens'][0];
        return $retorno_coluna;
    }
    
    public function set_logradouro_por_id( $id, $coluna = '*' )
    {
        $data['coluna'] = $coluna;
        $data['tabela'] = 'logradouros';
        $data['where'] = 'id = '.$id;
        $retorno = $this->select($data);
        $retorno_coluna = $coluna != '*' ? $retorno['itens'][0]->$coluna : $retorno['itens'][0];
        return $retorno_coluna;
    }
    
    public function get_empresa_por_id( $id, $tipo_retorno = 'object', $coluna = '*' )
    {
        $data['coluna'] = $coluna;
        $data['tabela'] = 'empresas';
        $data['where'] = 'id = '.$id;
        $retorno = $this->select($data, FALSE, $tipo_retorno);
        $retorno_coluna = $coluna != '*' ? $retorno['itens'][0]->$coluna : $retorno['itens'][0];
        return $retorno_coluna;
    }
    
    public function get_empresa_por_dominio( $dominio, $tipo_retorno = 'object', $coluna = '*' )
    {
        $dominio = str_replace(array('http://', 'www.'), array('',''), $dominio);
        $data['coluna'] = $coluna;
        $data['tabela'] = 'empresas';
        $data['where'] = 'empresas.empresa_dominio LIKE "%'.$dominio.'"';
        $retorno = $this->select($data, FALSE, $tipo_retorno);
        $retorno_coluna = $coluna != '*' ? $retorno['itens'][0]->$coluna : $retorno['itens'][0];
        return $retorno_coluna;
    }
    
    public function get_parametros_por_id_empresa( $id_empresa, $tipo_retorno = 'object', $coluna = '*' )
    {
        $data['coluna'] = $coluna;
        $data['tabela'] = 'hotsite_parametros';
        $data['where'] = 'id_empresa = '.$id_empresa;
        $retorno = $this->select($data, FALSE, $tipo_retorno);
        $retorno_coluna = $coluna != '*' ? $retorno['itens'][0]->$coluna : $retorno['itens'][0];
        return $retorno_coluna;
    }
    
}

 