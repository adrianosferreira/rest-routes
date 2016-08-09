<?php

add_filter( 'wprr_filter_query', 'route_filter_limit_offset', 10, 2 );

function route_filter_limit_offset( $args, $post_id ){

	$limit = get_post_meta( $post_id, '_wprr_limit', true );
	$offset = get_post_meta( $post_id, '_wprr_offset', true );

	if( isset($limit) ){
		$args['posts_per_page'] = $limit;
	}

	if( isset($offset) ){
		$args['offset'] = $offset;
	}

	return $args;
}

add_filter( 'wprr_add_filter_elements', 'route_add_filter_element_limit_offset', 10, 2);

function route_add_filter_element_limit_offset($output, $post_id){

	$limit = get_post_meta( $post_id, '_wprr_limit', true );
	$offset = get_post_meta( $post_id, '_wprr_offset', true );

	$output .= '<table class="form-table">
				    <tbody>
				        <tr>
						    <th scope="row">Limit</th>
						    <td>
						        <fieldset>
						            <label for="wprr_lmit_offset">
						                <input type="text" value="'.$limit.'" id="wprr_limit" name="wprr_limit">
						            </label>
						            <br>
						        </fieldset>
						    </td>
						</tr>
				    </tbody>
				</table>';

	$output .= '<table class="form-table">
				    <tbody>
				        <tr>
						    <th scope="row">Offset</th>
						    <td>
						        <fieldset>
						            <label for="wprr_lmit_offset">
						                <input type="text" value="'.$offset.'" id="wprr_offset" name="wprr_offset">
						            </label>
						            <br>
						        </fieldset>
						    </td>
						</tr>
				    </tbody>
				</table>';

		echo $output;
}

add_action( 'wprr_save_fields', 'route_save_limit_offset', 10, 2 );

function route_save_limit_offset( $fields, $post_id ){
	$limit = $fields['wprr_limit'];
	update_post_meta( $post_id, '_wprr_limit',  $limit);

	$offset = $fields['wprr_offset'];
	update_post_meta( $post_id, '_wprr_offset',  $offset);
}

add_filter( 'wprr_add_filter_indexes', 'route_add_filter_index_limit_offset', 10 );

function route_add_filter_index_limit_offset( $output ){

	$output .= '<li data-wprr-index="limit"><a>Limit & Offset</a></li>';

	return $output;
}

add_filter( 'wprr_add_filter_contents', 'route_add_filter_content_limit_offset', 10, 2 );

function route_add_filter_content_limit_offset( $output, $post_id ){

	$limit = get_post_meta( $post_id, '_wprr_limit', true );
	$offset = get_post_meta( $post_id, '_wprr_offset', true );

	$output .= '
			<div id="wprr_limit_content">	
				<table class="form-table">
				    <tbody>
				        <tr>
						    <th scope="row">Limit</th>
						    <td>
						        <fieldset>
						            <label for="wprr_lmit_offset">
						                <input type="text" value="'.$limit.'" id="wprr_limit" name="wprr_limit">
						            </label>
						            <br>
						        </fieldset>
						    </td>
						</tr>
				    </tbody>
				</table>';

	$output .= '<table class="form-table">
				    <tbody>
				        <tr>
						    <th scope="row">Offset</th>
						    <td>
						        <fieldset>
						            <label for="wprr_lmit_offset">
						                <input type="text" value="'.$offset.'" id="wprr_offset" name="wprr_offset">
						            </label>
						            <br>
						        </fieldset>
						    </td>
						</tr>
				    </tbody>
				</table>
			</div>';

	return $output;
}

?>