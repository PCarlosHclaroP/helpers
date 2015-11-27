
<title><?php echo $titulo;?></title>
<meta name="description" content="<?php echo $description;?>" />

<meta name="distribution" content="global" />
<meta http-equiv="Content-Type" content="text/html; charset=windows-1252" />
<meta http-equiv="refresh" content="" />
<meta name="rating" content="General" />
<meta name="owner" content="POW Internet. www.powinternet.com em São José dos Pinhais" />
<meta name="googlebot" content="yes" />
<meta name="googlebot" content="all" />
<meta name="robots" content="all" />
<meta http-equiv="Pragma" content="no-cache" />
<meta http-equiv="Expires" content="Mon, 06 Jan 1980 00:00:01 GMT" />
<!-- <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">   -->
<link rel="shortcut icon" href="<?php echo base_url();?>imoveis/imagens/favicon.ico" >

<?php 
if ( $jquery )
{
    ?>
<link rel="stylesheet" href="<?php echo base_url();?>css/3_0/bootstrap.css" type="text/css" />
<script type="text/javascript" src="<?php echo base_url();?>js/3_0/jquery.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/3_0/bootstrap.js"></script>
    <?php
}
?>        
<link rel="stylesheet" href="<?php echo base_url();?>css/3_0/estilo.css" type="text/css" />
<script type="text/javascript" src="<?php echo base_url();?>js/3_0/default.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/confirma_formulario.js"></script>

<?php 
if ( $lightbox )
{
    ?>
    <link rel="stylesheet" href="<?php echo base_url();?>js/3_0/lightbox/css/lightbox.css" type="text/css" />
    <script type="text/javascript" src="<?php echo base_url();?>js/3_0/lightbox/js/lightbox.min.js"></script>
    <?php
}
?>        
