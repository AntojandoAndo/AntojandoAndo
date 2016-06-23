
<?php
/**
 * The single post loop Default template
 **/
 
if (have_posts()) {
    the_post();

    $td_mod_single = new td_module_single($post);
    ?>

    <article id="post-<?php echo $td_mod_single->post->ID;?>" class="<?php echo join(' ', get_post_class());?>" <?php echo $td_mod_single->get_item_scope();?>>
        <div class="td-post-header">

            <?php echo $td_mod_single->get_category(); ?>

            <header class="td-post-title">
                <?php echo $td_mod_single->get_title();?>


                <?php if (!empty($td_mod_single->td_post_theme_settings['td_subtitle'])) { ?>
                    <p class="td-post-sub-title"><?php echo $td_mod_single->td_post_theme_settings['td_subtitle'];?></p>
                <?php } ?>


                <div class="td-module-meta-info">
                    <?php echo $td_mod_single->get_author();?>
                    <?php echo $td_mod_single->get_date(false);?>
                    <?php echo $td_mod_single->get_comments();?>
                    <?php echo $td_mod_single->get_views();?>
                </div>

            </header>

        </div>

        <?php echo $td_mod_single->get_social_sharing_top();?>


        <div class="td-post-content">

        <?php
			// override the default featured image by the templates (single.php and home.php/index.php - blog loop)
			if (!empty(td_global::$load_featured_img_from_template)) {
				echo $td_mod_single->get_image(td_global::$load_featured_img_from_template);
			} else {
				echo $td_mod_single->get_image('td_696x0');
			}
        ?>
		
<div class="td-post-content">
	<div class="essb_links essb_counters essb_displayed_top essb_share essb_template_copy-retina essb_2107722554 print-no" id="essb_displayed_top_2107722554" data-essb-postid="5908" data-essb-position="top" data-essb-button-style="button" data-essb-template="copy-retina" data-essb-counter-pos="left" data-essb-url="http://antojandoando.com/colombianismos/lo-que-le-diga-es-mentira/" data-essb-twitter-url="http://antojandoando.com/colombianismos/lo-que-le-diga-es-mentira/" data-essb-instance="2107722554">
	<ul class="essb_links_list">
	<li class="essb_item essb_totalcount_item" data-counter-pos="left">
		<span class="essb_totalcount essb_t_l_big" title="">
		<span class="essb_t_nb"></span></span>
	</li>
	<li class="essb_item essb_link_facebook nolightbox"> 
	<a href="http://www.facebook.com/sharer/sharer.php?u=http://antojandoando.com/colombianismos/lo-que-le-diga-es-mentira/&t=Lo+que+le+diga+es+mentira" title="" onclick="essb_window(&#39;http://www.facebook.com/sharer/sharer.php?u=http://antojandoando.com/colombianismos/lo-que-le-diga-es-mentira/&t=Lo+que+le+diga+es+mentira&#39;,&#39;facebook&#39;,&#39;2107722554&#39;); return false;" target="_blank" rel="nofollow"><span class="essb_icon"></span><span class="essb_network_name">Facebook</span></a></li><li class="essb_item essb_link_twitter nolightbox"> <a href="#" title="" onclick="essb_window(&#39;https://twitter.com/intent/tweet?text=Lo+que+le+diga+es+mentira&amp;url=http://antojandoando.com/colombianismos/lo-que-le-diga-es-mentira/&amp;counturl=http://antojandoando.com/colombianismos/lo-que-le-diga-es-mentira/&#39;,&#39;twitter&#39;,&#39;2107722554&#39;); return false;" target="_blank" rel="nofollow"><span class="essb_icon"></span><span class="essb_network_name">Twitter</span></a></li><li class="essb_item essb_link_pinterest nolightbox"> <a href="#" title="" onclick="essb_pinterest_picker(&#39;2107722554&#39;); return false;" target="_blank" rel="nofollow"><span class="essb_icon"></span><span class="essb_network_name">Pinterest</span></a></li><li class="essb_item essb_link_mail nolightbox"> <a href="#" title="" onclick="essb_mailform_2107722554(&#39;2107722554&#39;); return false;" target="_blank" rel="nofollow"><span class="essb_icon"></span><span class="essb_network_name">Email</span></a></li><li class="essb_item essb_link_whatsapp nolightbox"> <a href="whatsapp://send?text=Lo%20que%20le%20diga%20es%20mentira%20http%3A%2F%2Fantojandoando.com%2Fcolombianismos%2Flo-que-le-diga-es-mentira%2F" title="" onclick="essb_tracking_only('', 'whatsapp', '2107722554', true);" target="_blank" rel="nofollow"><span class="essb_icon"></span><span class="essb_network_name">WhatsApp</span></a></li></ul>
	</div>

	<h3>Definición</h3>
	<p><?php echo get_field( "custom_definicion_colomb" ); ?></p>

	<h3>Usos</h3>
	<?php echo get_field( "custom_uso_colomb" ); ?>


	<div class="navigation">
		<p>			
		<?php
			$current = $td_mod_single->post->ID;
			$prevID = $current-1;
			$nextID = $current+1;
		?>
		<div class="navigation">
			<?php if (!empty($prevID)) { ?>
				<div class="alignleft" >	
					<a class="post-definition-previous" onmouseover="this.style.background='#ff9955';this.style.color='white';" onmouseout="this.style.background='white'; this.style.color='#ff9955';"  href="<?php echo get_permalink($prevID); ?>"
						title="<?php echo get_the_title($prevID); ?>">Anterior</a>
				</div>
			<?php }
			if (!empty($nextID)) { ?>
				<div class="alignright">
					<a class="post-definition-next" onmouseover="this.style.background='#ff9955';this.style.color='white';" onmouseout="this.style.background='white'; this.style.color='#ff9955';" href="<?php echo get_permalink($nextID); ?>" 
						title="<?php echo get_the_title($nextID); ?>">Proxima</a>
				</div>
			<?php } ?>
		</div>
		<!-- .navigation -->
		</p>
	</div>

	<!-- Popular Posts -->
	<div class="container" >
		<div class="row">
			<div class="col-xs-12">
					<div class="clearfix"></div>
					<h1 class="alignleft">Popular</h1>
					<div class="clearfix"></div>
			<div class="popular-post-wrapper" >
				<ol class="popular-post-ol">
						<?php
						$args = array( 'post_type' => 'popular' , 'posts_per_page' => 10 );
						$myposts = get_posts( $args );
						$count = 0;
						foreach ( $myposts as $post ) : setup_postdata( $post ); ?>
							<?php $count++;						
							if($count <= 5) {?>
								<div style="width: 50%; float: left; padding: 10px; ">
									<li>
										<a href="<?php the_field('popular_post_url'); ?>"><?php the_title(); ?></a>
									</li>
								</div>
							<?php } else {?>	
								<div style="width: 50%;	float: left; padding: 10px;">
									<li>
										<a href="<?php the_field('popular_post_url'); ?>"><?php the_title(); ?></a>
									</li>
								</div>
							<?php } ?>
						<?php endforeach; 
						wp_reset_postdata();?>
				</ol>
			</div>	
				
			</div>
		</div>	
	</div>

	<!-- Common Button -->
	<div class="container">
	<a style=" text-decoration: none;" href="http://antojandoando.com/quieres-sugerir-un-colombianismo/"> 
			<div class="row">
				<div class="col-xs-12">
						<div class="clearfix"></div>
						<h1 class="alignleft"> </h1>
						<div class="clearfix"></div> 
						<div class="button-text-div" onmouseover="this.style.background='#25356d';" onmouseout="this.style.background='#ff9955';">
							<p onmouseover="this.style.color='#d93937';" onmouseout="this.style.color='white';"> Tengo una Colombianismo que me gusta sugerir </p>
						</div>	
				</div>
			</div>	
	</a>	
	</div>


	<!-- Popular Posts -->
	<div class="container">
		<div class="row">
			<div class="col-xs-12" style="margin-top: 13px;">
					<div class="clearfix"></div>
					<div class="clearfix"></div>
					<div style="width: 100%; float:left;" >
						<ol class="featureposts-image">
							<?php
							$args = array( 'post_type' => 'feature_post' , 'posts_per_page' => 3 );
							$myposts = get_posts( $args );
							
							foreach ( $myposts as $post ) : setup_postdata( $post ); ?>
								<li>
									<a href="<?php the_field('feature_post_url'); ?>">
										<?php $image = get_field('feature_post_image')?>
										<?php if($image) { ?>
											<div>
												<img src="<?php echo $image['url']; ?>" height="170px" width="170px" />
												<h6 style="margin: 10px 0 0 0; float: center;"><?php $posttitle = the_title('','',FALSE); echo substr($posttitle, 0, 48); ?> </h6>
											</div>
										<?php } else { ?>
											<div>
												<img src="<?php echo get_stylesheet_directory_uri(); ?>/includes/image/feature-post.png"  height="170px" width="170px" >
												<h6 style="margin: 10px 0 0 0; float: center;"><?php $posttitle = the_title('','',FALSE); echo substr($posttitle, 0, 48); ?> </h6>
											</div>
									</a>
										<?php } ?>
								</li>
							<?php endforeach; 
							wp_reset_postdata();?>
						</ol>
					</div>
			</div>
		</div>	
	</div>

	<h5 style="margin: 25px 0 0 0;">Tienes otros definiciones o usos para esta palabra/expresion que quieres sugerir? <br>Escribe tu sugerencia en la seccion de commentarios abajo</h5>
	<h3 style="margin: 10px 0 0 0;">Comentarios</h3>
	<div class="fb-comments" data-href="http://antojandoando.com/colombianismos/lo-que-le-diga-es-mentira/" data-num-posts="10" data-width="100%" data-colorscheme="light">
	</div> 
</div>
		
</div>

        <footer>
            <?php echo $td_mod_single->get_post_pagination();?>
            <?php echo $td_mod_single->get_review();?>

            <div class="td-post-source-tags">
                <?php echo $td_mod_single->get_source_and_via();?>
                <?php echo $td_mod_single->get_the_tags();?>
            </div>

            <?php echo $td_mod_single->get_social_sharing_bottom();?>
            <?php echo $td_mod_single->get_next_prev_posts();?>
            <?php echo $td_mod_single->get_author_box();?>
	        <?php echo $td_mod_single->get_item_scope_meta();?>
        </footer>

    </article> <!-- /.post -->

    <?php echo $td_mod_single->related_posts();?>

<?php
} else {
    //no posts
    echo td_page_generator::no_posts();
}