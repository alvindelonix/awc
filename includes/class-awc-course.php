<?php


class Awc_Course {
		
	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;
	
	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {
		
		$this->plugin_name = $plugin_name;
		$this->version = $version;
		
		$this->define_my_post_type();
		
		//add_action('after_setup_theme',array($this,'define_my_metabox'));//$this->define_my_metabox();
		add_action('acf/init', array($this,'define_my_custom_fields') );
	}
	
	/**
	 * Define the Course post type.
	 *
	 * @since     1.0.0
	 * 
	 */
	private function define_my_post_type() {		
		
		$course = new CPT(
			array(
				'post_type_name' => 'awc_courses',
				'singular' => 'Course',
				'plural' => 'Courses',
				'slug' => 'course'
			),
			array(
				'menu_position' => 99,
				'public' => true,
				'has_archive' => true,
				'hierarchical'       => false,
				'capability_type'    => 'post',
				'supports'           => array( 'title', 'editor', 'thumbnail' )
			)
		);
		
		$course->menu_icon("dashicons-book-alt");
		
		$course->register_taxonomy(
			array(
				'taxonomy_name' => 'course_category',
				'singular' => 'Category',
				'plural' => 'Categories',
				'slug' => 'course-hub'
			),
			array(
				'hierarchical'      => true,
				'show_ui'           => true,
				'show_admin_column' => true,
				'query_var'         => true,
			)
		);
		
		$course->register_taxonomy(
			array(
				'taxonomy_name' => 'course_delivery',
				'singular' => 'Delivery Method',
				'plural' => 'Delivery Method',
				'slug' => 'delivery-method'
			),
			array(
				'hierarchical'      => false,
				'show_ui'           => true,
				'show_admin_column' => false,
				'query_var'         => true,
			)
		);
		
		$course->register_taxonomy(
			array(
				'taxonomy_name' => 'course_duration',
				'singular' => 'Duration',
				'plural' => 'Duration',
				'slug' => 'course-duration'
			),
			array(
				'hierarchical'      => false,
				'show_ui'           => true,
				'show_admin_column' => false,
				'query_var'         => true,
			)
		);
		
		$course->register_taxonomy(
			array(
				'taxonomy_name' => 'course_costs',
				'singular' => 'Cost',
				'plural' => 'Costs',
				'slug' => 'course-costs'
			),
			array(
				'hierarchical'      => false,
				'show_ui'           => true,
				'show_admin_column' => false,
				'query_var'         => true,
			)
		);
		
		$course->register_taxonomy(
			array(
				'taxonomy_name' => 'awc_location',
				'singular' => 'Location',
				'plural' => 'Location',
				'slug' => 'location'
			),
			array(
				'hierarchical'      => true,
				'show_ui'           => true,
				'show_admin_column' => true,
				'query_var'         => true,
			)
		);
		
		$course->columns(array(
			'cb' => '<input type="checkbox" />',
			'title' => __('Title'),
			'icon' => __('Featured Image'),
			'course_category' => __('Course Categories'),
			'awc_location' => __('Location'),
			'date' => __('Date')
		));
	}
	
/* 	private function define_my_metabox(){
		$sked_mb = new VP_Metabox(
			array(
				'id'          => 'awc_course_sked',
				'types'		  => array('awc_courses'),
				'title'       => __('Upcoming Schedule and Location', 'awc'),
				'priority'    => 'high',
				'prefix'    => '_awc_',
				'template'    => array(
					array(
						'type' => 'textbox',
						'name' => 'awc_course_caption',
						'label' => __('Caption', 'awc'),
						'description' => __('', 'awc'),
						'validation' => 'required|minlength[5]|maxlength[40]'
					),
				)
			)
		); 
	} */
	
	/**
	 * Define the Course custom fields powered by Advanced Custom Fields.
	 *
	 * @since     1.0.0
	 * 
	 */
	function define_my_custom_fields(){
				
		acf_add_local_field_group(array (
			'key' => 'group_awc_course_sked',
			'title' => 'Upcoming Schedule and Location',
			'fields' => array (
				array(
				"key" => "field_course_sked",
                "label" => "Schedule and Location",
                "name" => "sked_and_location",
                "type" => "repeater",
                "instructions" => "",
                "required" => 0,
                "conditional_logic" => 0,
                "wrapper" => array(
                    "width" => "",
                    "class" => "",
                    "id" => ""
                ),
                "collapsed" => "",
                "min" => "",
                "max" => "",
                "layout" => "row",
                "button_label" => "Add Schedule",
                "sub_fields" => array(
					array(
                        "key" => "field_sked_date",
                        "label" => "Date",
                        "name" => "start_date",
                        "type" => "date_picker",
                        "instructions" => "",
                        "required" => 0,
                        "conditional_logic" => array(
                            /* array(
                                {
                                    "field": "field_572193cb1be4c",
                                    "operator": "==",
                                    "value": "1"
                                },
                                {
                                    "field": "field_5747eaa820656",
                                    "operator": "!=",
                                    "value": "online-selfpaced"
                                }
                            ) */
                        ),
                        "wrapper" => array(
                            "width" => 80,
                            "class" => "",
                            "id" => ""
                        ),
                        "display_format" => "d/m/Y",
                        "return_format" => "d/m/Y",
                        "first_day" => 1
                    ),
				)
				)
			),
			'location' => array (
				array (
					array (
						'param' => 'post_type',
						'operator' => '==',
						'value' => 'awc_courses',
					),
				),
			),
			'menu_order' => 0,
			'position' => 'normal',
			'style' => 'default',
			'label_placement' => 'left',
			'instruction_placement' => 'label',
			'hide_on_screen' => '',
		));

	}
	
	/**
	 * Get a single course by id.
	 *
	 * @since     	1.0.0
	 * @param 		integer		$course_id	The course id. 
	 * @return 		object/  				The course object. Return false if no param provided.
	 */
	public function get_the_awc_course($course_id = 0) {
		
		global $post;
		
		if($course_id == 0) {
			$course_id = $post->ID;
		}  
		
		$course = get_post($course_id);
		
		return $course;
		
	}
	
	public function get_the_awc_courses( $args ) {
		
		$default_args = array(
			'post_type' => 'awc_courses',
			'post_status' => 'publish',
			'posts_per_page' => -1,
			
		);
		
		
	}
	
}