<?php
include_once('../../../../wp-config.php');
include_once('../../../../wp-load.php');
include_once('../../../../wp-includes/wp-db.php');

$meta_product = get_post_meta($_POST['idp'], "_eshop_product",true);
$meta_a_magazzino = get_post_meta($_POST['idp'], "_eshop_stock",true);

if(!isset($meta_a_magazzino))
	$is_magazziono=0;
if($meta_a_magazzino=='0' and $_POST['disponibile']=='1')
	$meta_a_magazzino=$_POST['disponibile'];
elseif($meta_a_magazzino=='1' and $_POST['disponibile']=='0')
	$meta_a_magazzino='0';
	
$meta_product['description']=$_POST['desc'];

$opt=json_decode(stripslashes($_POST['opzioni']),true);

$conto=count($opt);

if($conto>=count($meta_product['description']))
	$limit=$conto;
else
	$limit=count($meta_product['description']);
	
$meta_product['products']=null;

for($i=1;$i<=$limit;$i++){
	$meta_product['products'][""+$i]['option']=$opt[($i-1)]['option'];
	$meta_product['products'][""+$i]['price']=$opt[($i-1)]['price'];
	$meta_product['products'][""+$i]['weight']=$opt[($i-1)]['weight'];
}


$serial_prod=maybe_serialize($meta_product);

update_post_meta($_POST['idp'], "_eshop_product", $meta_product); 

if($meta_a_magazzino=='0')
	delete_post_meta($_POST['idp'], "_eshop_stock", $meta_a_magazzino);
else
	add_post_meta($_POST['idp'], "_eshop_stock", '1');

//echo " count json: ".$conto; 
?>