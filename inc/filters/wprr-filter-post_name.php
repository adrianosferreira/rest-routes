<?php

//add_filter( 'wprr_filter_query', 'route_filter_post_name_in', 10, 2 );

function route_filter_post_name_in( $args, $post_id ){

	$post_name_in = array_filter( unserialize( get_post_meta( $post_id, '_wprr_post_name', true ) ) );

	if( isset($post_name_in) && ($post_name_in != '') ){

			$args['post_name__in'] = $post_name_in;

	}

	return $args;
}

//add_filter( 'wprr_add_filter_elements', 'route_add_filter_element_post_name_in', 10, 2);

function route_add_filter_element_post_name_in($output, $post_id){

	$post_name = unserialize( get_post_meta( $post_id, '_wprr_post_name', true ) );

	$output .= '<table class="form-table">
				    <tbody>
				        <tr>
						    <th scope="row">Post name</th>
						    <td>
						        <fieldset>';

					            	$output .= '
					            		<label for="wprr_post_type">
							                <input type="text" value="'.implode(", ", $post_name).'" id="wprr_post_name" name="wprr_post_name">
							            </label><br />
							            <small>Multiple names separated by comma: 123,456,789.</small>
					            	';

		$output .= '			</fieldset>
						    </td>
						</tr>
				    </tbody>
				</table>';

		echo $output;
}

add_action( 'wprr_save_fields', 'route_save_field_post_name_in', 10, 2 );

function route_save_field_post_name_in( $fields, $post_id ){
	$post_name_field = serialize( explode( ',', $fields['wprr_post_name'] ) );
	update_post_meta( $post_id, '_wprr_post_name', $post_name_field );
}

?>