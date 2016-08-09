<?php

add_filter( 'wprr_filter_query', 'route_filter_post_id', 10, 2 );

function route_filter_post_id( $args, $post_id ){

	$post_in = array_filter( unserialize( get_post_meta( $post_id, '_wprr_post_id', true ) ) );

	if( isset($post_in) && ($post_in != '') && ( $post_in ) ){

		$args['post__in'] = $post_in;

	}

	return $args;
}

add_filter( 'wprr_add_filter_elements', 'route_add_filter_element_post_id', 10, 2);

function route_add_filter_element_post_id($output, $post_id){

	$post_in = unserialize( get_post_meta( $post_id, '_wprr_post_id', true ) );

	$output .= '<table class="form-table">
				    <tbody>
				        <tr>
						    <th scope="row">Post ID</th>
						    <td>
						        <fieldset>';

					            	$output .= '
					            		<label for="wprr_post_type">
							                <input type="text" value="'.implode(", ", $post_in).'" id="wprr_post_id" name="wprr_post_id">
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

add_action( 'wprr_save_fields', 'route_save_field_post_id', 10, 2 );

function route_save_field_post_id( $fields, $post_id ){
	$post_id_field = serialize( explode( ',', $fields['wprr_post_id'] ) );
	update_post_meta( $post_id, '_wprr_post_id', $post_id_field );
}

add_filter( 'wprr_add_filter_indexes', 'route_add_filter_index_post_id', 10 );

function route_add_filter_index_post_id( $output ){

	$output .= '<li data-wprr-index="post_id"><a>Post ID</a></li>';

	return $output;
}

add_filter( 'wprr_add_filter_contents', 'route_add_filter_content_post_id', 10, 2 );

function route_add_filter_content_post_id( $output, $post_id ){

	if( $post_in = unserialize( get_post_meta( $post_id, '_wprr_post_id', true ) ) ){
		$post_in = implode(", ", $post_in);
	}else{
		$post_in = '';	
	}

	$output .= '
			<div id="wprr_post_id_content">
				<table class="form-table">
				    <tbody>
				        <tr>
						    <th scope="row">Post ID</th>
						    <td>
						        <fieldset>';

					            	$output .= '
					            		<label for="wprr_post_type">
							                <input type="text" value="'.$post_in.'" id="wprr_post_id" name="wprr_post_id">
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