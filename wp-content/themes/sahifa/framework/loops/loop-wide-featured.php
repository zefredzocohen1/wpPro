<div class="post-listing archive-box">
<?php while ( have_posts() ) : the_post(); ?>

	<article <?php tie_post_class('item-list'); ?>>
	
		<?php if ( function_exists("has_post_thumbnail") && has_post_thumbnail() ) : ?>			
		<div class="post-thumbnail single-post-thumb archive-wide-thumb">
			<a href="<?php the_permalink(); ?>">
				<?php the_post_thumbnail( 'slider' ); ?>
				<span class="fa overlay-icon"></span>
			</a>
		</div>
		<div class="clear"></div>
		<?php endif; ?>
		
		<h2 class="post-box-title">
			<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
		</h2>
		
		<?php get_template_part( 'framework/parts/meta-archives' ); ?>					
		
		<div class="entry">
			<p><?php tie_excerpt() ?></p>
			<a class="more-link" href="<?php the_permalink() ?>"><?php _eti( 'Read More &raquo;' ) ?></a>
		</div>
		
		<?php if( tie_get_option( 'archives_socail' ) ) get_template_part( 'framework/parts/share' );  // Get Share Button template ?>
		
		<div class="clear"></div>
	</article><!-- .item-list -->
	
<?php endwhile;?>
</div>
