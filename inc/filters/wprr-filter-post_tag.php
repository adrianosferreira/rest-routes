<?php

add_filter( 'wprr_filter_query', 'route_filter_tag', 10, 2 );

function route_filter_tag( $args, $post_id ){

	$tag = array_filter( unserialize( get_post_meta( $post_id, '_wprr_tag', true ) ) );

	if( isset( $tag ) && ( $tag != '' ) && ( $tag ) ){

			$args['tag__in'] = $tag;

	}

	return $args;
}

add_filter( 'wprr_filter_query', 'route_filter_tag_exclude', 10, 2 );

function route_filter_tag_exclude( $args, $post_id ){

	$tag = unserialize( get_post_meta( $post_id, '_wprr_tag_exclude', true ) );

	if( isset( $tag ) && ( $tag != '' ) && ( $tag ) ){

			$args['tag__not_in'] = $tag;

	}

	return $args;
}

add_filter( 'wprr_add_filter_elements', 'route_add_filter_element_tag', 10, 2);

function route_add_filter_element_tag($output, $post_id){

	$tag = unserialize( get_post_meta( $post_id, '_wprr_tag', true ) );

	$output .= '<table class="form-table">
				    <tbody>
				        <tr>
						    <th scope="row">Tag</th>
						    <td>
						        <fieldset>';

					            	$output .= '
					            		<label for="wprr_category">
							                <input type="text" value="'.implode(", ", $tag).'" id="_wprr_tag" name="wprr_tag">
							            </label><br />
							            <small>Multiple ID separated by comma: 123,456,789.</small>
					            	';

		$output .= '			</fieldset>
						    </td>
						</tr>
				    </tbody>
				</table>';

		echo $output;
}

add_action( 'wprr_save_fields', 'route_save_field_tag', 10, 2 );

function route_save_field_tag( $fields, $post_id ){
	$tag = serialize( explode( ',', $fields['wprr_tag'] ) );
	update_post_meta( $post_id, '_wprr_tag', $tag );
}

add_action( 'wprr_save_fields', 'route_save_field_tag_exclude', 10, 2 );

function route_save_field_tag_exclude( $fields, $post_id ){
	$tag = serialize( explode( ',', $fields['wprr_tag_exclude'] ) );
	update_post_meta( $post_id, '_wprr_tag_exclude', $tag );
}

add_filter( 'wprr_add_filter_indexes', 'route_add_filter_index_post_tag', 10 );

function route_add_filter_index_post_tag( $output ){

	$output .= '<li data-wprr-index="post_tag"><a>Post Tag</a></li>';

	return $output;
}

add_filter( 'wprr_add_filter_contents', 'route_add_filter_content_post_tag', 10, 2 );

function route_add_filter_content_post_tag( $output, $post_id ){

	if( $tag = unserialize( get_post_meta( $post_id, '_wprr_tag', true ) ) ){
		$tag = implode(", ", $tag);
	}else{
		$tag = '';	
	}

	$output .= '
			<div id="wprr_post_tag_content">
				<table class="form-table">
				    <tbody>
				        <tr>
						    <th scope="row">Tag</th>
						    <td>
						        <fieldset>';

					            	$output .= '
					            		<label for="wprr_category">
							                <input type="text" value="'.$tag.'" id="_wprr_tag" name="wprr_tag">
							            </label><br />
							            <small>Multiple ID separated by comma: 123,456,789.</small>
					            	';

		$output .= '			</fieldset>
						    </td>
						</tr>
				    </tbody>
				</table>';

	if( $tag_exclude = unserialize( get_post_meta( $post_id, '_wprr_tag_exclude', true ) ) ){
		$tag_exclude = implode(", ", $tag_exclude);
	}else{
		$tag_exclude = '';	
	}

	$output .= '<table class="form-table">
				    <tbody>
				        <tr>
						    <th scope="row">Exclude Tag</th>
						    <td>
						        <fieldset>';

					            	$output .= '
					            		<label for="wprr_category">
							                <input type="text" value="'.$tag_exclude.'" id="_wprr_tag_exclude" name="wprr_tag_exclude">
							            </label><br />
							            <small>Multiple ID separated by comma: 123,456,789.</small>
					            	';

		$output .= '			</fieldset>
						    </td>
						</tr>
				    </tbody>
				</table>
			</div>';			

	return $output;
}

?>