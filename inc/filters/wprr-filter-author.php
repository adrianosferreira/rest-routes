<?php

add_filter( 'wprr_filter_query', 'route_filter_author', 10, 2 );

function route_filter_author( $args, $post_id ){

	$author = array_filter( unserialize( get_post_meta( $post_id, '_wprr_author', true ) ) );

	if( isset( $author ) && ( $author != '' ) && ( $author ) ){

			$args['author__in'] = $author;

	}

	return $args;
}

add_filter( 'wprr_add_filter_elements', 'route_add_filter_element_author', 10, 2);

function route_add_filter_element_author($output, $post_id){

	$author = unserialize( get_post_meta( $post_id, '_wprr_author', true ) );

	$output .= '<table class="form-table">
				    <tbody>
				        <tr>
						    <th scope="row">Author ID</th>
						    <td>
						        <fieldset>';

					            	$output .= '
					            		<label for="wprr_post_type">
							                <input type="text" value="'.implode(", ", $author).'" id="wprr_author" name="wprr_author">
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

add_action( 'wprr_save_fields', 'route_save_field_author', 10, 2 );

function route_save_field_author( $fields, $post_id ){
	$author = serialize( explode( ',', $fields['wprr_author'] ) );
	update_post_meta( $post_id, '_wprr_author', $author );

}

add_filter( 'wprr_add_filter_indexes', 'route_add_filter_index_author', 10 );

function route_add_filter_index_author( $output ){

	$output .= '<li data-wprr-index="author"><a>Post Author</a></li>';

	return $output;
}

add_filter( 'wprr_add_filter_contents', 'route_add_filter_content_author', 10, 2 );

function route_add_filter_content_author( $output, $post_id ){

	if( $author = unserialize( get_post_meta( $post_id, '_wprr_author', true ) ) ){
		$author = implode(", ", $author);
	}else{
		$author = '';	
	}

	$output .= '
			<div id="wprr_author_content">	
				<table class="form-table">
				    <tbody>
				        <tr>
						    <th scope="row">Author ID</th>
						    <td>
						        <fieldset>';

					            	$output .= '
					            		<label for="wprr_post_type">
							                <input type="text" value="'.$author.'" id="wprr_author" name="wprr_author">
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