<?php
/* Miln Photo Feed Template for displaying photograph feeds. */

header('Content-Type: ' . feed_content_type('rss-http') . '; charset=' . get_option('blog_charset'), true);
$more = 1;

echo '<?xml version="1.0" encoding="'.get_option('blog_charset').'"?'.'>'; ?>

<rss version="2.0"
	xmlns:content="http://purl.org/rss/1.0/modules/content/"
	xmlns:dc="http://purl.org/dc/elements/1.1/"
	xmlns:atom="http://www.w3.org/2005/Atom"
	xmlns:sy="http://purl.org/rss/1.0/modules/syndication/"
>
<channel>
	<title><?php bloginfo_rss('name'); ?> - Photo Feed</title>
	<atom:link href="<?php self_link(); ?>" rel="self" type="application/rss+xml" />
	<link><?php bloginfo_rss('url') ?></link>
	<description><?php bloginfo_rss("description") ?></description>
	<lastBuildDate><?php echo mysql2date('D, d M Y H:i:s +0000', get_lastpostmodified('GMT'), false); ?></lastBuildDate>
	<language><?php bloginfo_rss( 'language' ); ?></language>
	<sy:updatePeriod><?php echo apply_filters( 'rss_update_period', 'hourly' ); ?></sy:updatePeriod>
	<sy:updateFrequency><?php echo apply_filters( 'rss_update_frequency', '1' ); ?></sy:updateFrequency>
	<?php while( have_posts()) : the_post(); ?>
	<?php
		$args = array( 'post_type' => 'attachment', 'numberposts' => -1, 'post_status' =>'any', 'post_parent' => $post->ID ); 
		$attachments = get_posts($args);
		if ($attachments) {
			foreach ( $attachments as $attachment ) {
				if ( wp_attachment_is_image( $attachment->ID ) ) {
					$image_attributes = wp_get_attachment_image_src( $attachment->ID , 'full', false );
	?>
		<item>
			<title><?php echo apply_filters( 'the_title' , $attachment->post_title ); ?></title>
			<pubDate><?php echo mysql2date('D, d M Y H:i:s +0000', $attachment->post_date_gmt, false); ?></pubDate>
			<dc:creator><?php the_author() ?></dc:creator>
			<link><?php echo get_attachment_link( $attachment->ID ); ?></link>
			<guid isPermaLink="false"><?php echo get_permalink( $attachment->ID ); ?></guid>
			<description><![CDATA[<p><?php echo the_attachment_link( $attachment->ID, 'full' ); ?></p><p><?php echo $attachment->post_content; ?></p>]]></description>
			<enclosure url="<?php echo $image_attributes[0] ?>" type="<?php echo $attachment->post_mime_type; ?>" length="<?php echo filesize( get_attached_file( $attachment->ID ) ); ?>" />			
		</item>
	<?php
				}
			}
		}
	?>
	<?php endwhile; ?>
</channel>
</rss>