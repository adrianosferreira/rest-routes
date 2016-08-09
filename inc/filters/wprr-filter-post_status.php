<?php

add_filter( 'wprr_filter_query', 'route_filter_post_status', 10, 2 );

function route_filter_post_status( $args, $post_id ){

	$post_status = unserialize( get_post_meta( $post_id, '_wprr_post_status', true ) );

	if( isset($post_status) ){
		foreach ($post_status as $key => $value) {
			$args['post_status'] = $post_status;
		}
	}

	return $args;
}

add_filter( 'wprr_add_filter_elements', 'route_add_filter_element_post_status', 10, 2);

function route_add_filter_element_post_status($output, $post_id){

	$post_status_arr = array(
		array("slug" => "publish", "nome" => "Published"),
		array("slug" => "pending", "nome" => "Pending"),	
		array("slug" => "draft", "nome" => "Draft"),
		array("slug" => "auto-draft", "nome" => "Auto Draft"),	
		array("slug" => "future", "nome" => "Future"),	
		array("slug" => "private", "nome" => "Private"),	
		array("slug" => "inherit", "nome" => "Inherit"),	
		array("slug" => "trash", "nome" => "Trash"),	
	);

	$post_status = unserialize( get_post_meta( $post_id, '_wprr_post_status', true ) );

	$output .= '		<table class="form-table">
				    <tbody>
				        <tr>
						    <th scope="row">Post Status</th>
						    <td>
						        <fieldset>';
						            foreach ($post_status_arr as $key => $value) {

						            	$field_search = (string) array_search($value['slug'], $post_status);

						            	if( $field_search != ''){

						            		$checked = 'checked=checked';

						            	}else{

						            		$checked = '';

						            	}

						            	$output .= '
						            		<label for="wprr_post_status">
								                <input type="checkbox" '.$checked.' value="'.$value['slug'].'" id="wprr_post_status_published" name="wprr_post_status[]"> '.$value['nome'].'
								            </label>
								            <br>
						            	';
						            }
		$output .= '     				</fieldset>
						    </td>
						</tr>
				    </tbody>
				</table>';

		echo $output;
}

add_action( 'wprr_save_fields', 'route_save_field_post_status', 10, 2 );

function route_save_field_post_status( $fields, $post_id ){
	$post_status = serialize( $fields['wprr_post_status'] );
	update_post_meta( $post_id, '_wprr_post_status', $post_status );
}

add_filter( 'wprr_add_filter_indexes', 'route_add_filter_index_post_status', 10 );

function route_add_filter_index_post_status( $output ){

	$output .= '<li data-wprr-index="post_status"><a>Post Status</a></li>';

	return $output;
}

add_filter( 'wprr_add_filter_contents', 'route_add_filter_content_post_status', 10, 2 );

function route_add_filter_content_post_status( $output, $post_id ){

	$post_status_arr = array(
		array("slug" => "publish", "nome" => "Published"),
		array("slug" => "pending", "nome" => "Pending"),	
		array("slug" => "draft", "nome" => "Draft"),
		array("slug" => "auto-draft", "nome" => "Auto Draft"),	
		array("slug" => "future", "nome" => "Future"),	
		array("slug" => "private", "nome" => "Private"),	
		array("slug" => "inherit", "nome" => "Inherit"),	
		array("slug" => "trash", "nome" => "Trash"),	
	);

	$post_status = unserialize( get_post_meta( $post_id, '_wprr_post_status', true ) );

	$output .= '
			<div id="wprr_post_status_content">	
				<table class="form-table">
				    <tbody>
				        <tr>
						    <th scope="row">Post Status</th>
						    <td>
						        <fieldset>';
						            foreach ($post_status_arr as $key => $value) {

						            	$field_search = '';
						            	if( $post_status )
						            		$field_search = (string) array_search($value['slug'], $post_status);

						            	if( $field_search != ''){

						            		$checked = 'checked=checked';

						            	}else{

						            		$checked = '';

						            	}

						            	$output .= '
						            		<label for="wprr_post_status">
								                <input type="checkbox" '.$checked.' value="'.$value['slug'].'" id="wprr_post_status_published" name="wprr_post_status[]"> '.$value['nome'].'
								            </label>
								            <br>
						            	';
						            }
		$output .= '     				</fieldset>
						    </td>
						</tr>
				    </tbody>
				</table>
			</div>';

	return $output;
}

?>