<?php
include_once('../../../../wp-config.php');
include_once('../../../../wp-load.php');
include_once('../../../../wp-includes/wp-db.php');


$meta_product = get_post_meta($_POST['id'], "_eshop_product",true);
$meta_a_magazzino = get_post_meta($_POST['id'], "_eshop_stock",true);
if(!isset($meta_a_magazzino) || $meta_a_magazzino==null)
	$meta_a_magazzino=0;

//echo print_r ($meta_product);
if(!isset($meta_a_magazzino) || $meta_a_magazzino==null){
	 _e("L'articolo non ha ancora associati dei valori, andare alla","eShop-me")?><a href="<?php echo admin_url();?>/post.php?post=<?php echo $_POST['id'];?>&action=edit"> <?php _e("pagina relativa","eShop-me");?></a> 
	<?php
}
else{
?>

<strong><?php _e("modifica","eShop-me"); ?></strong><br/><br/>
<?php _e("titolo","eShop-me"); ?>: <input type="text" value="<?php echo $meta_product['description'];?>" style="width:300px"> 
<?php _e("disponibile","eShop-me") ?>: <input type="checkbox" name="disponibile" value="<?php echo $meta_a_magazzino;?>" <?php if($meta_a_magazzino=='1') echo 'checked="checked"'?> />
<?php
$products=$meta_product['products'];


	foreach($products as $product){
	?>
		<div class="product_opt">
			<?php _e("opzioni","eShop-me"); ?>: <input type="text" value="<?php echo $product['option'];?>" style="width:100px" name="opzioni">
			<?php _e("prezzo","eShop-me"); ?>: <input type="text" value="<?php echo $product['price'];?>" style="width:100px" name="prezzo" onClick="jQuery(this).css('color','black');">
			<?php _e("volume","eShop-me"); ?>: <input type="text" value="<?php echo $product['weight'];?>" style="width:100px" name="volume"onClick="jQuery(this).css('color','black');">
			<button class="add-button ui-state-default ui-corner-all" onClick="add_row(jQuery(this));"></button>
			<button class="del-button ui-state-default ui-corner-all" onClick="del_row(jQuery(this));"></button>
		</div>
	<?php
	}
	echo '<button type="button" class="preview button" onClick="modify_product(\''.$_POST['div_id'].'\',\''.$_POST['id'].'\',\''.$meta_product['description'].'\')">modifica</button>';
}

?>

