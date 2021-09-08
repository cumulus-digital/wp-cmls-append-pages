<?php

namespace CUMULUS\Wordpress\AppendPages;

// Exit if accessed directly.
\defined( 'ABSPATH' ) || exit( 'No direct access allowed.' );

require __DIR__ . '/required.php';
require __DIR__ . '/acf.php';
require __DIR__ . '/config.php';

function outputPages( $new_pages ) {
	while ( $new_pages->have_posts() ): $new_pages->the_post(); ?>
		<div class="wp-cmls-append-pages">
			<?php \the_content(); ?>
		</div>
	<?php
	endwhile;
	\wp_reset_postdata();
}

function appendPages() {
	if ( \is_admin() || ( ! \is_page() && ! \is_single() ) ) {
		return;
	}

	$our_id = \get_the_ID();

	if ( ! $our_id ) {
		return;
	}

	$append_pages = \get_field( 'field_5cddcbcdfab73', $our_id, false );

	if ( ! $append_pages ) {
		return;
	}

	$new_pages = new \WP_Query( [ 'post__in' => $append_pages, 'post_type' => 'page' ] );

	if ( ! $new_pages->have_posts() ) {
		return;
	}

	// Use custom action injection point from CMLS_Base theme if possible
	if ( \function_exists( '\CMLS_Base\ns' ) ) {
		\add_action( 'cmls_template-singular-after_post', function () use ( $our_id, $new_pages ) {
			if ( \get_the_ID() !== $our_id ) {
				return;
			}

			outputPages( $new_pages );
		}, 99 );
	} else {
		\add_filter( 'the_content', function ( $content ) use ( $our_id, $new_pages ) {
			if ( \get_the_ID() !== $our_id ) {
				return $content;
			}

			\ob_start();
			outputPages( $new_pages );
			$content .= \ob_get_clean();

			return $content;
		}, 99, 1 );
	}
}

\add_action( 'wp', __NAMESPACE__ . '\\appendPages' );
