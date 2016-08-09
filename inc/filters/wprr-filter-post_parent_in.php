<?php

add_filter( 'wprr_filter_query', 'route_filter_post_parent_in', 10, 2 );

function route_filter_post_parent_in( $args, $post_id ){

	$post_parent_in = array_filter( unserialize( get_post_meta( $post_id, '_wprr_post_parent', true ) ) );

	if( isset($post_parent_in) && ($post_parent_in != '') && ( $post_parent_in ) ){

			$args['post_parent__in'] = $post_parent_in;

	}

	return $args;
}

add_filter( 'wprr_filter_query', 'route_filter_post_parent_not_in', 10, 2 );

function route_filter_post_parent_not_in( $args, $post_id ){

	$post_parent_not_in = array_filter( unserialize( get_post_meta( $post_id, '_wprr_post_parent_not_in', true ) ) );

	if( isset($post_parent_not_in) && ($post_parent_not_in != '') && ( $post_parent_not_in ) ){

			$args['post_parent__not_in'] = $post_parent_not_in;

	}

	return $args;
}

add_filter( 'wprr_add_filter_elements', 'route_add_filter_element_post_parent_in', 10, 2);

function route_add_filter_element_post_parent_in($output, $post_id){

	$post_parent = unserialize( get_post_meta( $post_id, '_wprr_post_parent', true ) );

	$output .= '<table class="form-table">
				    <tbody>
				        <tr>
						    <th scope="row">Post Parent</th>
						    <td>
						        <fieldset>';

					            	$output .= '
					            		<label for="wprr_post_type">
							                <input type="text" value="'.implode(", ", $post_parent).'" id="wprr_post_parent" name="wprr_post_parent">
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

add_action( 'wprr_save_fields', 'route_save_field_post_parent_in', 10, 2 );

function route_save_field_post_parent_in( $fields, $post_id ){
	$post_parent_field = serialize( explode( ',', $fields['wprr_post_parent'] ) );
	update_post_meta( $post_id, '_wprr_post_parent', $post_parent_field );
}

add_action( 'wprr_save_fields', 'route_save_field_post_parent_not_in', 10, 2 );

function route_save_field_post_parent_not_in( $fields, $post_id ){
	$post_parent_not_in_field = serialize( explode( ',', $fields['wprr_post_parent_not_in'] ) );
	update_post_meta( $post_id, '_wprr_post_parent_not_in', $post_parent_not_in_field );
}

add_filter( 'wprr_add_filter_indexes', 'route_add_filter_index_post_parent', 10 );

function route_add_filter_index_post_parent( $output ){

	$output .= '<li data-wprr-index="post_parent"><a>Post Parent</a></li>';

	return $output;
}

add_filter( 'wprr_add_filter_contents', 'route_add_filter_content_post_parent', 10, 2 );

function route_add_filter_content_post_parent( $output, $post_id ){

	if( $post_parent = unserialize( get_post_meta( $post_id, '_wprr_post_parent', true ) ) ){
		$post_parent = implode(", ", $post_parent);
	}else{
		$post_parent = '';	
	}

	$output .= '
			<div id="wprr_post_parent_content">
				<table class="form-table">
				    <tbody>
				        <tr>
						    <th scope="row">Post Parent</th>
						    <td>
						        <fieldset>';

					            	$output .= '
					            		<label for="wprr_post_type">
							                <input type="text" value="'.$post_parent.'" id="wprr_post_parent" name="wprr_post_parent">
							            </label><br />
							            <small>Multiple ID separated by comma: 123,456,789.</small>
					            	';

		$output .= '			</fieldset>
						    </td>
						</tr>
				    </tbody>
				</table>';

	if( $post_parent_not_in = unserialize( get_post_meta( $post_id, '_wprr_post_parent_not_in', true ) ) ){
		$post_parent_not_in = implode(", ", $post_parent_not_in);
	}else{
		$post_parent_not_in = '';	
	}

	$output .= '
				<table class="form-table">
				    <tbody>
				        <tr>
						    <th scope="row">Exclude Post Parent</th>
						    <td>
						        <fieldset>';

					            	$output .= '
					            		<label for="wprr_post_type">
							                <input type="text" value="'.$post_parent_not_in.'" id="wprr_post_parent_not_in" name="wprr_post_parent_not_in">
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