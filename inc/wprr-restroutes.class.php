<?php

class RestRoutes {

	public $default_fields = array(
					'ID',
					'post_author',
					'post_date',
					'post_content',
					'post_title',
					'post_excerpt',
					'post_status',
					'post_name',
					'post_modified',
					'post_parent',
					'post_type',
					'guid',
					'menu_order',
			);

	function __construct() {

		add_action( 'init', array( $this, 'wprr_init') );

	}

	function wprr_init() {
		$this->wprr_register_post_type();

		add_action( 'add_meta_boxes', array( $this, 'wprr_meta_box_main_data' ) );
		add_action( 'save_post', array( $this, 'wprr_save_meta_box_data') );

		$this->wprr_filters();

		add_action( 'rest_api_init', function () {
		    register_rest_route( 'rest-routes/v2', '/(?P<id>[a-zA-Z-_0-9]+)', array(
			    'methods' => WP_REST_Server::READABLE,
			    'callback' => array( $this, 'custom_get_route' )
			) );
		} );

		add_action( 'admin_menu', array( $this, 'wprr_admin_menu' ) );

	}


	function wprr_admin_menu() {

		add_menu_page('WP Rest Routes', 'WP Rest Routes', 'manage_options', 'edit.php?post_type=rest-routes', '',  'dashicons-share');
		add_submenu_page( 'edit.php?post_type=rest-routes', 'Routes', 'My Routes', 'manage_options', 'edit.php?post_type=rest-routes');
		//add_submenu_page( 'edit.php?post_type=rest-routes', 'Settings', 'Settings', 'manage_options', 'wprr-settings', 'wprr_admin_menu_settings' );
		//add_submenu_page( 'edit.php?post_type=rest-routes', 'Help', 'Help', 'manage_options', 'wprr-help', 'wprr_admin_menu_help' );

	}

	function wprr_admin_menu_routes(){
	}

	function wprr_register_post_type() {

		$labels = array(
			'name'               => _x( 'Routes', 'post type general name', TEXTDOMAIN ),
			'singular_name'      => _x( 'Route', 'post type singular name', TEXTDOMAIN ),
			'menu_name'          => _x( 'Routes', 'admin menu', TEXTDOMAIN ),
			'name_admin_bar'     => _x( 'Route', 'add new on admin bar', TEXTDOMAIN ),
			'add_new'            => _x( 'Add New', 'book', TEXTDOMAIN ),
			'add_new_item'       => __( 'Add New Route',  TEXTDOMAIN ),
			'new_item'           => __( 'New Route', TEXTDOMAIN ),
			'edit_item'          => __( 'Edit Route', TEXTDOMAIN ),
			'view_item'          => __( 'View Route', TEXTDOMAIN ),
			'all_items'          => __( 'All Routes', TEXTDOMAIN ),
			'search_items'       => __( 'Search Routes', TEXTDOMAIN ),
			'parent_item_colon'  => __( 'Parent Routes:', TEXTDOMAIN ),
			'not_found'          => __( 'No routes found.', TEXTDOMAIN ),
			'not_found_in_trash' => __( 'No routes found in Trash.', TEXTDOMAIN )
		);

		$args = array(
			'labels'             => $labels,
	        'description'        => __( 'Description.', TEXTDOMAIN ),
			'public'             => false,
			'publicly_queryable' => false,
			'show_ui'            => true,
			'show_in_menu'       => false,
			'query_var'          => false,
			'rewrite'            => array( 'slug' => 'rest-routes' ),
			'capability_type'    => 'post',
			'has_archive'        => true,
			'hierarchical'       => false,
			'menu_position'      => null,
			'supports'           => array( 'title' )
		);

		register_post_type( 'rest-routes', $args );
	}

	function wprr_meta_box_main_data() {

		$screens = array( 'rest-routes' );

		foreach ( $screens as $screen ) {

			add_meta_box(
				'wprr_query_builder',
				__( 'Query Builder', TEXTDOMAIN ),
				array( $this, 'wprr_query_builder_callback' ),
				$screen
			);

			add_meta_box(
				'wprr_content_output',
				__( 'Content Output', TEXTDOMAIN ),
				array( $this, 'wprr_content_output_callback' ),
				$screen
			);

			add_meta_box(
				'wprr_route_info',
				__( 'Route Details', TEXTDOMAIN ),
				array( $this, 'wprr_route_details_callback' ),
				$screen
			);
		}
	}

	function wprr_query_builder_callback( $post ) {

		// Add a nonce field so we can check for it later.
		wp_nonce_field( 'wprr_save_meta_box_data', 'wprr_meta_box_nonce' );

		$output = '';

		echo '
		<style>
			.route-container .index-column{
				float: left;
				width: 20%;
				clear: both;
			}

			.route-container .content-column{
				float: left;
				width: 70%;
				padding: 10px;
			}

			.route-container .index-column ul{
			    margin: 0px;
			}

			.route-container .index-column ul li {
			    background: #F1F1F1;
			    display: block;
			    padding: 10px;
			    margin-bottom: 0px;
			    border: 1px solid #E5E5E5;
			    border-left: none;
			    border-top: none;
			}

			.route-container .index-column ul li a{
				text-decoration: none;
			}

			.route-container .index-column ul li:hover{
				background: #E5E5E5;
				cursor: pointer;
			}

			.route-container .index-column ul .item-active{
				background: #E5E5E5;
				cursor: pointer;
			}

			#wprr_query_builder .inside{
				margin: 0px;
				padding: 0px;
			}

			#wprr_query_builder .clean{
				clear: both;
			}

			#wprr_query_builder .form-table th{
				padding: 0px;
			}

			#wprr_query_builder .form-table td{
				padding: 0px;
			}

			.wprr-output-fields td{
				padding: 0px;
			}
		}

		</style>

		<script>

			jQuery("document").ready(function(){
				jQuery(".content-column div").hide();
				jQuery("#wprr_post_type_content").show("fast");
				jQuery("#wprr_post_type_content").show("fast");
				jQuery("#wprr_index_post_type").addClass("item-active");

				jQuery(".index-column ul li").click(function(){
					wprr_index_data = jQuery(this).data("wprr-index");

					jQuery(".index-column ul li").each(function(index){
						jQuery(this).removeClass("item-active");
					});

					jQuery(".content-column div").hide();					
					jQuery("#wprr_"+wprr_index_data+"_content").show("fast");
					jQuery(this).addClass("item-active");
				});
			});

		</script>

		<form method="post">';

		$index_output = apply_filters( 'wprr_add_filter_indexes', $output );
		$content_output = apply_filters( 'wprr_add_filter_contents', $output, $post->ID );

		$output .= '
				<div class="route-container">
					<div class="index-column">
						<ul>';

		$output .= $index_output;

		$output .=     '</ul>
					</div>
					<div class="content-column">';

		$output .= $content_output;

		$output .= '</div>
					<div class="clean"></div>
				</div>
		';

		//$output = 'teste';

		//$output .= apply_filters( 'wprr_add_filter_elements', $output, $post->ID );

		echo $output;
	}

	function wprr_content_output_callback( $post ) {

		global $wpdb;

		// Add a nonce field so we can check for it later.
		wp_nonce_field( 'wprr_save_meta_box_data', 'wprr_meta_box_nonce' );

		/*
		 * Use get_post_meta() to retrieve an existing value
		 * from the database and use the value for the form.
		 */
		$value = get_post_meta( $post->ID, '_my_meta_value_key', true );

		/*
		$post_fields_arr = array(
			'ID',
			'post_author',
			'post_title',
			'post_content',
			'post_excerpt',
			'post_status',
			'comment_count',
			'post_status',
			'post_name',
			'post_type',
			'post_date',
			'post_modified',
		);
		*/
	
		$post_fields_arr = $this->default_fields;
		
		$custom_fields_query = $wpdb->get_results(
			'SELECT distinct( meta_key )
			 FROM '.$wpdb->postmeta.'
			 WHERE meta_key NOT LIKE "\_%"',
			 ARRAY_N
		);

		$custom_fields_arr = array();
		foreach ($custom_fields_query as $key => $value) {
			foreach ($value as $key2 => $value2) {
				$custom_fields_arr[] = $value2;
			}
		}

		$post_fields = unserialize( get_post_meta($post->ID, '_wprr_output_fields', true) );

		$post_fields_arr = array_merge($post_fields_arr, $custom_fields_arr);

		$max_fields = 1;

		echo '	<p>Define the output of your custom route.</p>
				<table class="form-table wprr-output-fields">
				    <tbody>
				        <tr>
						    <th scope="row">Post Fields</th>
						    <td id="wprr_output_fields">';

						    $i = 0;

						    if( $post_fields ){

							    foreach ($post_fields as $key => $value) {

							    	$i++;

			echo '				        <fieldset id="wprr_output_fieldset_'.$i.'">
							            <label for="wprr_ordering_dir">
							                <select class="wprr_output_fields" id="wprr_output_field_'.$i.'" name="wprr_output_fields[]">';

							                	foreach ($post_fields_arr as $key2 => $value2) {

									            	if( $value == $value2){

									            		$selected = 'selected';

									            	}else{

									            		$selected = '';

									            	}

							                		echo '<option '.$selected.' value="'.$value2.'">'.$value2.'</option>';
							                	}

			echo '						    </select> <a class="wprr-remove-item button button-small" data-wprr-field="'.$i.'">Delete</a>
							            </label>
							            <br>
										</fieldset>';
								}

							}else{

								$i = 1;

			echo '				        <fieldset id="wprr_output_fieldset_'.$i.'">
							            <label for="wprr_ordering_dir">
							                <select class="wprr_output_fields" id="wprr_output_field_'.$i.'" name="wprr_output_fields[]">';

							                	foreach ($post_fields_arr as $key2 => $value2) {

									            	if( $value == $value2){

									            		$selected = 'selected';

									            	}else{

									            		$selected = '';

									            	}

							                		echo '<option '.$selected.' value="'.$value2.'">'.$value2.'</option>';
							                	}

			echo '						    </select> <a class="wprr-remove-item button button-small" data-wprr-field="'.$i.'">Delete</a>
							            </label>
							            <br>
										</fieldset>';					

							}

		echo '				</td>';
		echo '			</tr>
						<tr>
							<th></th>
							<td class="wprr-add-field">';

		echo '<a class="wprr-add-item button button-primary button-large">Add Field</a>';					

		echo '				</td>
						</tr>
				    </tbody>
				</table>';
		echo '</form>';
		echo '<script>
				jQuery( document ).ready(function() {

					var counter = 0;

				  	jQuery( ".wprr-add-item" ).click(function(event) {

					   	jQuery("#wprr_output_fields").append("<fieldset><label for=\"wprr_ordering_dir\"><select class=\"wprr_output_fields\" id=\"wprr_output_field_new_"+counter+"\" name=\"wprr_output_fields[]\"></select></label></fieldset>");

						jQuery("#wprr_output_field_1 option").clone().appendTo("#wprr_output_field_new_"+counter);

					   	counter = counter + 1;

					});

					jQuery(".wprr-remove-item").click(function(event){
						jQuery("#wprr_output_fieldset_"+jQuery(this).data("wprr-field")).remove();
					})
				});
			  </script>
			  ';

	}

	function wprr_route_details_callback( $post ) {

		$output = '';

		echo '<p>Access your custom route through the following link: 
			  <a target="_blank" href="'.site_url().'/wp-json/rest-routes/v2/'.$post->post_name.'">'.site_url().'/wp-json/rest-routes/v2/'.$post->post_name.'</a></p>';
	}

	function custom_get_route( WP_REST_Request $request) {

		global $wpdb;

		if( null !== $request->get_param( 'id' ) ){
			$route_info = $request->get_param( 'id' );
			$route = get_post( $route_info );

			if( $route != null ){
				$route_id = $route->ID;
			}else{
				$route_by_name = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $wpdb->posts WHERE post_name = %s", $route_info ) );
				$route_id = $route_by_name->ID;
			}

		}

	    return $this->wprr_get_posts($args, $route_id, $this->default_fields);
	}

	function wprr_save_meta_box_data( $post_id ) {

		// Check if our nonce is set.
		if ( ! isset( $_POST['wprr_meta_box_nonce'] ) ) {
			return;
		}

		// Verify that the nonce is valid.
		if ( ! wp_verify_nonce( $_POST['wprr_meta_box_nonce'], 'wprr_save_meta_box_data' ) ) {
			return;
		}

		// If this is an autosave, our form has not been submitted, so we don't want to do anything.
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		// Check the user's permissions.
		if ( isset( $_POST['post_type'] ) && 'rest-routes' == $_POST['post_type'] ) {

			if ( ! current_user_can( 'edit_page', $post_id ) ) {
				return;
			}

		} else {

			if ( ! current_user_can( 'edit_post', $post_id ) ) {
				return;
			}
		}

		add_action( 'wprr_save_fields', 'route_save_output', 10, 2 );

		function route_save_output( $fields, $post_id ){

			$output = serialize( $fields['wprr_output_fields'] );
			update_post_meta( $post_id, '_wprr_output_fields', $output );

		}		

		do_action( 'wprr_save_fields', $_POST, $post_id );
	}

	function wprr_filters(){

		foreach ( array_reverse( glob( WPRR_INC_PATH . '/filters/wprr-filter-*.php' ) ) as $filename ) {
			include $filename;
		}

	}

	function wprr_output( $post_id, $default_fields, $post_ids ){

		global $wpdb;

		$output_columns_db = unserialize( get_post_meta( $post_id, '_wprr_output_fields', true ) );

		$post_fields = array();
		$post_custom_fields = array();

		foreach ($output_columns_db as $key => $value) {
			if( (string) array_search($value, $default_fields) != '' ){
				$post_fields[] = $value;
			}else{
				$post_custom_fields[] = $value;
			}
		}

		$post_meta_key = array();
		$output_columns = '';

		if( isset($post_fields) ){
			$posts_table = $wpdb->posts . ' p';
			$output_columns .= 'p.' . implode( ", p.", $post_fields );

			if ($post_custom_fields){
				$output_columns .= ', ';
			}
		}

		if( isset($post_custom_fields) ){

			foreach ($post_custom_fields as $key => $value) {

				$output_columns .= "COALESCE( ( SELECT 
									meta_value
									FROM
									".$wpdb->postmeta." pm
									WHERE pm.post_id = p.ID
									AND pm.meta_key = '".$value."' ), '' ) as " . $value;

				if( $value != end( $post_custom_fields ) ){
					$output_columns .= ', ';
				}
		
			}
		}

		$output_query = $wpdb->get_results(
			"
			SELECT
			".$output_columns."
			FROM 
			".$posts_table."
			WHERE
			p.ID IN (".$post_ids.")
			"
		);

		if( count( $output_columns_db ) === 1 ){

			$output_one_column = array();

			foreach ( $output_query as $key => $value ) {
				$output_one_column[] = $value->$output_columns_db[0];
			}

			return $output_one_column;
			
		}else{

			return $output_query;

		}
	}

	function wprr_get_posts( $args, $post_id, $default_fields ){

		global $wpdb;

		$args['fields'] = 'ids';
		$args['posts_per_page'] = -1;

		$post_ids_query = new WP_Query( apply_filters( 'wprr_filter_query', $args, $post_id ) );

		$post_ids = implode(',', $post_ids_query->posts);

		return $this->wprr_output($post_id, $default_fields, $post_ids);
	}

}

?>