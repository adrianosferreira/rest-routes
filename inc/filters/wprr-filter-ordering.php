<?php

add_filter( 'wprr_filter_query', 'route_filter_ordering', 10, 2 );

function route_filter_ordering( $args, $post_id ){

	$ordering_dir = get_post_meta( $post_id, '_wprr_ordering_dir', true );
	$ordering_field = get_post_meta( $post_id, '_wprr_ordering_field', true );

	if( isset($ordering_dir) ){
		$args['order'] = $ordering_dir;
	}

	if( isset($ordering_field) ){
		$args['orderby'] = $ordering_field;
	}

	return $args;
}

add_filter( 'wprr_add_filter_elements', 'route_add_filter_element_ordering', 10, 2);

function route_add_filter_element_ordering($output, $post_id){

	$post_ordering_dir_arr = array(
		array("slug" => "asc", "nome" => "ASC"),
		array("slug" => "desc", "nome" => "DESC"),	
	);

	$post_ordering_field_arr = array(
		array("slug" => "ID", "nome" => "ID"),
		array("slug" => "author", "nome" => "Post author"),
		array("slug" => "title", "nome" => "Post title"),
		array("slug" => "name", "nome" => "Post name"),
		array("slug" => "type", "nome" => "Post type"),
		array("slug" => "date", "nome" => "Post date"),
		array("slug" => "modified", "nome" => "Post modified date"),
		array("slug" => "parent", "nome" => "Post parent"),
		array("slug" => "rand", "nome" => "Rand"),
	);

	$post_ordering_dir = get_post_meta( $post_id, '_wprr_ordering_dir', true );
	$post_ordering_field = get_post_meta( $post_id, '_wprr_ordering_field', true );

	$output .= '<table class="form-table">
				    <tbody>
				        <tr>
						    <th scope="row">Ordering</th>
						    <td>
						        <fieldset>
						            <label for="wprr_ordering_dir">
						                <select id="wprr_ordering_dir" name="wprr_ordering_dir">';

						                	foreach ($post_ordering_dir_arr as $key => $value) {

						                		$field_search = (string) strpos($value['slug'], $post_ordering_dir);

								            	if( $field_search != ''){

								            		$selected = 'selected';

								            	}else{

								            		$selected = '';

								            	}

						                		$output .= '<option '.$selected.' value="'.$value['slug'].'">'.$value['nome'].'</option>';
						                	}

		$output .= '						    </select>
						            </label>
						            <br>';

        $output .= '				<label for="wprr_ordering_field">
						                <select id="wprr_ordering_field" name="wprr_ordering_field">';

						                	foreach ($post_ordering_field_arr as $key => $value) {

						                		$field_search = (string) strpos($value['slug'], $post_ordering_field);

								            	if( $field_search != ''){

								            		$selected = 'selected';

								            	}else{

								            		$selected = '';

								            	}

						                		$output .= '<option '.$selected.' value="'.$value['slug'].'">'.$value['nome'].'</option>';
						                	}

		$output .= '						    </select>
						            </label>
						            <br>
						        </fieldset>
						    </td>
						</tr>
				    </tbody>
				</table>';

		echo $output;
}

add_action( 'wprr_save_fields', 'route_save_ordering', 10, 2 );

function route_save_ordering( $fields, $post_id ){
	$post_ordering_dir = $fields['wprr_ordering_dir'];
	update_post_meta( $post_id, '_wprr_ordering_dir',  $post_ordering_dir);

	$post_ordering_field = $fields['wprr_ordering_field'];
	update_post_meta( $post_id, '_wprr_ordering_field',  $post_ordering_field);
}

add_filter( 'wprr_add_filter_indexes', 'route_add_filter_index_ordering', 10 );

function route_add_filter_index_ordering( $output ){

	$output .= '<li data-wprr-index="ordering"><a>Ordering</a></li>';

	return $output;
}

add_filter( 'wprr_add_filter_contents', 'route_add_filter_content_ordering', 10, 2 );

function route_add_filter_content_ordering( $output, $post_id ){

	$post_ordering_dir_arr = array(
		array("slug" => "asc", "nome" => "ASC"),
		array("slug" => "desc", "nome" => "DESC"),	
	);

	$post_ordering_field_arr = array(
		array("slug" => "ID", "nome" => "ID"),
		array("slug" => "author", "nome" => "Post author"),
		array("slug" => "title", "nome" => "Post title"),
		array("slug" => "name", "nome" => "Post name"),
		array("slug" => "type", "nome" => "Post type"),
		array("slug" => "date", "nome" => "Post date"),
		array("slug" => "modified", "nome" => "Post modified date"),
		array("slug" => "parent", "nome" => "Post parent"),
		array("slug" => "rand", "nome" => "Rand"),
	);

	$post_ordering_dir = get_post_meta( $post_id, '_wprr_ordering_dir', true );
	$post_ordering_field = get_post_meta( $post_id, '_wprr_ordering_field', true );

	$output .= '
			<div id="wprr_ordering_content">	
				<table class="form-table">
				    <tbody>
				        <tr>
						    <th scope="row">Ordering</th>
						    <td>
						        <fieldset>
						            <label for="wprr_ordering_dir">
						                <select id="wprr_ordering_dir" name="wprr_ordering_dir">';

						                	foreach ($post_ordering_dir_arr as $key => $value) {

						                		$field_search = '';

								            	if( is_array( $post_ordering_dir ) ){
								            		$field_search = (string) strpos($value['slug'], $post_ordering_dir);
								            	}

								            	if( $field_search != ''){

								            		$selected = 'selected';

								            	}else{

								            		$selected = '';

								            	}

						                		$output .= '<option '.$selected.' value="'.$value['slug'].'">'.$value['nome'].'</option>';
						                	}

		$output .= '						    </select>
						            </label>
						            <br>';

        $output .= '				<label for="wprr_ordering_field">
						                <select id="wprr_ordering_field" name="wprr_ordering_field">';

						                	foreach ($post_ordering_field_arr as $key => $value) {

						                		$field_search = '';

								            	if( is_array( $post_ordering_field ) ){
								            		$field_search = (string) strpos($value['slug'], $post_ordering_field);
								            	}

								            	if( $field_search != ''){

								            		$selected = 'selected';

								            	}else{

								            		$selected = '';

								            	}

						                		$output .= '<option '.$selected.' value="'.$value['slug'].'">'.$value['nome'].'</option>';
						                	}

		$output .= '						    </select>
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