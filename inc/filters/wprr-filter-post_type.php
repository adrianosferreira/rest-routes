<?php

add_filter( 'wprr_filter_query', 'route_filter_post_type', 10, 2 );

function route_filter_post_type( $args, $post_id ){

	$post_type = unserialize( get_post_meta( $post_id, '_wprr_post_type', true ) );

	if( isset($post_type) ){
		foreach ($post_type as $key => $value) {
			$args['post_type'] = $post_type;
		}
	}

	return $args;
}

add_filter( 'wprr_add_filter_elements', 'route_add_filter_element_post_type', 10, 2);

function route_add_filter_element_post_type($output, $post_id){

	$post_type_arr = array(
		array("slug" => "post", "nome" => "Post"),
		array("slug" => "page", "nome" => "Page"),
	);

	$post_type = unserialize( get_post_meta( $post_id, '_wprr_post_type', true ) );

	$output .= '<table class="form-table">
				    <tbody>
				        <tr>
						    <th scope="row">Post Type</th>
						    <td>
						        <fieldset>';
						            foreach ($post_type_arr as $key => $value) {

						            	$field_search = (string) array_search($value['slug'], $post_type);

						            	if( $field_search != ''){

						            		$checked = 'checked=checked';

						            	}else{

						            		$checked = '';

						            	}

						            	$output .= '
						            		<label for="wprr_post_type">
								                <input type="checkbox" '.$checked.' value="'.$value['slug'].'" id="wprr_post_type_post" name="wprr_post_type[]"> '.$value['nome'].'
								            </label><br />
						            	';
						            }

		$output .= '				    </fieldset>
						    </td>
						</tr>
				    </tbody>
				</table>';

		echo $output;
}

add_action( 'wprr_save_fields', 'route_save_field_post_type', 10, 2 );

function route_save_field_post_type( $fields, $post_id ){
	$post_type = serialize( $fields['wprr_post_type'] );
	update_post_meta( $post_id, '_wprr_post_type', $post_type );
}

add_filter( 'wprr_add_filter_indexes', 'route_add_filter_index_post_type', 10 );

function route_add_filter_index_post_type( $output ){

	$output .= '<li id="wprr_index_post_type" data-wprr-index="post_type"><a>Post Type</a></li>';

	return $output;
}

add_filter( 'wprr_add_filter_contents', 'route_add_filter_content_post_type', 10, 2 );

function route_add_filter_content_post_type( $output, $post_id ){

	$post_types = get_post_types();
	$post_types_exclude = array(
		'rest-routes',
		'nav_menu_item'
	);
	$post_types_exclude = apply_filters( 'wprr_excluded_post_types', $post_types_exclude );

	$post_type_arr = [];

	foreach ($post_types as $key => $value) {
		if( !array_search($value, $post_types_exclude) && array_search($value, $post_types_exclude) !== 0 )
			$post_type_arr[] = array( "slug" => $value, "nome" => $value );
	}

	$post_type = unserialize( get_post_meta( $post_id, '_wprr_post_type', true ) );

	$output .= '
				<div id="wprr_post_type_content">
					<table class="form-table">
					    <tbody>
					        <tr>
							    <th scope="row">Post Type</th>
							    <td>
							        <fieldset>';
							            foreach ($post_type_arr as $key => $value) {

							            	$field_search = '';

							            	if( is_array( $post_type ) ){
							            		$field_search = (string) array_search($value['slug'], $post_type);
							            	}

							            	if( $field_search != ''){

							            		$checked = 'checked=checked';

							            	}else{

							            		$checked = '';

							            	}

							            	$output .= '
							            		<label for="wprr_post_type">
									                <input type="checkbox" '.$checked.' value="'.$value['slug'].'" id="wprr_post_type_post" name="wprr_post_type[]"> '.$value['nome'].'
									            </label><br />
							            	';
							            }

			$output .= '				    </fieldset>
							    </td>
							</tr>
					    </tbody>
					</table>
				</div>';

	return $output;
}

?>