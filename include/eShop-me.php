<?php
class EshopMe{

	public static function view_editing_page(){
		wp_enqueue_style('ui_eshopMe',plugins_url().'/eShop-me/css/custom-me/jquery-ui-1.8.21.custom.css');		wp_enqueue_style('eShop-Me-style',plugins_url().'/eShop-me/css/style.css');
?>
	<script>
			jQuery(function() {
				jQuery( '#accordion' ).accordion();								jQuery('.post_category').each(function(){					jQuery(this).css('height','auto');					});
				
				jQuery( "#dialog" ).dialog({
					autoOpen: false,
					show: "blind",
					hide: "explode"
				});

			});		
			
			function ajx_form_modify(id_post,form_id){
				
				jQuery.ajax({
					type: "POST",
					url: "<?php echo  plugins_url();?>/eShop-me/include/return_form_post.php",
					data: {id: id_post,
							div_id:form_id},
					beforeSend: function() { 
							var gif_load='<img src="<?php echo  plugins_url();?>/eShop-me/img/spinner.gif" alt="loading" style="margin-left:150px;">';
							jQuery('#'+form_id).html(gif_load);
						},
					success: function(data) {
						jQuery('#'+form_id).html(data);
						restyle(); // do lo stile ai bottoni presi via AJAX
						//console.debug('#'+form_id); 
					  }
				});
			}
			
			function modify_product(id,post_id,post){
				var tit=jQuery('#'+id+' input');
				//var dsp=jQuery(tit).next().attr('checked'); 
				if(jQuery(tit).next().is(':checked'))
					dsp='1';
				else
					dsp='0';
				
				//var arrayOptions=new Array();
				var arrayOptions={};
				
				var i=0;
				
				var error_num=0;
				
				jQuery('#'+id+' div.product_opt').each(function(){
					//arrayOptions[i]=new Array(3);
					arrayOptions[i]={};
					arrayOptions[i]["option"]=jQuery(this).children().val();
					var now=jQuery(this).children().next();
					// controllo se prezzo è un dato numerico valido
					if(!controlloNum(jQuery(now).val())){
						jQuery( "#dialog" ).dialog( "open" );
							jQuery(now).first().css('color','red');
							//console.debug("now-> "+jQuery(now).val());
							error_num=1;
							return;
					}
					arrayOptions[i]["price"]=jQuery(now).val();
					now=jQuery(now).next();
					// controllo se volume è un dato numerico valido
					if(!controlloNum(jQuery(now).val())){
						jQuery( "#dialog" ).dialog( "open" );
							jQuery(now).css('color','red');
							error_num=1;
							return;
					}
					
					arrayOptions[i]["weight"]=jQuery(now).val();
					i++;
				});
				
				//console.debug(JSON.stringify(arrayOptions));
					
				if(!error_num){
					jQuery.ajax({
					  type: "POST",
					  url: "<?php echo  plugins_url();?>/eShop-me/include/save_prod_data.php",
					  data: { 
								desc: jQuery(tit).val(),
								disponibile: dsp,
								opzioni:JSON.stringify(arrayOptions),
								//opzioni:JSON.stringify(jtest),
								idp:post_id
							},
					   beforeSend: function() { 
								var gif_load='<img src="<?php echo  plugins_url();?>/eShop-me/img/spinner.gif" alt="loading" style="margin-left:150px;">';
								jQuery('#'+id).html(gif_load);
							},
					  success: function(data) {
									var msg ='<p style="background-color:#FAF8C0; height:25px; text-align:center; padding:10px;"><span class="ui-icon ui-icon-info" style="float: left; margin-right: .3em;"></span><?php _e("modifica all \'articolo","eShop-me"); ?>'+post+' <?php _e("eseguita correttamente","eShop-me");?></p>';
									//alert('Load was performed. '+data );
									jQuery('#'+id).html(msg);
								}
					});
				}
			}
			
			function restyle(){
				jQuery('.add-button').button({
						icons: {
							primary: "ui-icon-circle-plus"
						},
						text: false
					});
					
				jQuery('.del-button').button({
						icons: {
							primary: "ui-icon-circle-minus"
						},
						text: false
					});
			}
			
			function add_row(element){
				var row="<div>"+
					"<?php _e("opzioni","eShop-me"); ?>: <input type=\"text\" value=\"<?php echo $product['option'];?>\" style=\"width:100px\" name=\"opzioni\">"+
					"<?php _e("prezzo","eShop-me"); ?>: <input type=\"text\" value=\"<?php echo $product['price'];?>\" style=\"width:100px\" name=\"prezzo\" onClick=\"jQuery(this).css('color','black');\">"+
					"<?php _e("volume","eShop-me"); ?>: <input type=\"text\" value=\"<?php echo $product['weight'];?>\" style=\"width:100px\" name=\"volume\" onClick=\"jQuery(this).css('color','black');\">"+
					"<button class=\"add-button ui-state-default ui-corner-all\" onClick=\"add_row(jQuery(this));\"></button>"+
					"<button class=\"del-button ui-state-default ui-corner-all\" onClick=\"del_row(jQuery(this));\"></button>"+
					"</div>";
				//alert('added '+jQuery(element).html());
				jQuery(element).parent().after(row);
				jQuery(element).parent().next().addClass("product_opt");
				restyle();
			}
						
			function del_row(element){
				//alert('removed '+jQuery(element).html());
				jQuery(element).parent().remove();
			}
			
			function controlloNum(data){
				if(!isNaN(data))
					return true
				else
					return false
			}
	</script>
	<div class='wrap'>
		<h2>Massive editing</h2>
		
		<div id='mass_category'>
			<div id='accordion'>
				<?php
					$args=array(
						  'orderby' => 'name',
						  'order' => 'ASC',
						  'hide_empty ' => 1,
						  'hierarchical' => 0,
						  'exclude' => get_option('escludi_cat')
						  );
					$categories=get_categories($args);
										
					foreach($categories as $category){
						/***** creo il nome del div da quello della categoria rendendolo continuo se ha spazi *********/
						$exp=explode(" ",$category->cat_name);
						$cat=implode('_',$exp);
						
						$args = array(
									  'category_name'=>$category->cat_name,
									  'post_status'=>'publish',
									  'orderby' => 'title',
									  'order'=>'ASC',
									  'posts_per_page'=>-1
									  ); 
							$the_query = new WP_Query( $args );
							$cont=0;
						?>	
						<h3><a href="#"><?php echo $category->cat_name;?></a></h3>
						<div class='post_category'>	
							<table>									
								<tr>
									<td>
										<ul>
											<?php
												while ( $the_query->have_posts() ) : $the_query->the_post();
													echo '<li>';
													echo "<a class='post_title' onClick='ajx_form_modify(\"".get_the_ID()."\",\"id_".$cat."\")'>".get_the_title()."</a>";
													echo '</li>';
													$cont++;
												endwhile;

												// Reset Post Data
												wp_reset_postdata();
											?>
										</ul>
									</td>
									<td>
										<div id='id_<?php echo $cat; ?>' class='madmin_post'>
										</div>
									</td>
									</tr>
							</table>
						</div>						
						<?php
					}
				?>
			</div>

		</div>
		<div id="dialog" title="Errore">
		<p> <span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 50px 0;"></span><?php _e("Inserire un valore numerico valido, usando il punto come separatore dei decimali","eShop-me");?></p>
		</div>
	</div>
<?php	
	}

	public static function register_me_settings(){
		register_setting( 'eShopMe_settings', 'escludi_cat' ); // applico slider alle pagine
		wp_enqueue_script( 'jquery-ui-core' );
		wp_enqueue_script( 'jquery-ui-accordion' );
		wp_enqueue_script( 'jquery-ui-button' );
		wp_enqueue_script( 'jquery-ui-dialog' );
		
		wp_enqueue_script('json2');
	}	
	
	public static function setting_page(){
		if(isset($_POST['parent_cat'])){
				update_option("escludi_cat", $_POST['parent_cat']);
				/******** calcolo il livello di profondità delle categorie *********/
			}
		?>
		<div class='wrap'>
			<h2>Setting eShop-me</h2>
			<form method="post" action=""> <?php
				settings_fields( 'agile_slider_settings' );
				do_settings_fields( 'agile_slider_settings' );
			?>
			<?php _e("Escludi categorie mediante ID separati da virgola","eShop-me");?>: <input type='text' name='parent_cat' id='sett_category'value='<?php echo get_option('escludi_cat');?>'/>
			<?php
			submit_button();
			?>
			</form>
		</div>
		<?php
	}
	
}
?>
