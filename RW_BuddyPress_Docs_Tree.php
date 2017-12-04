<?php
/**
* Buddypress Docs Widget
* Description: displays a tree-view of the buddpress docs tom the loggedin user
* @autor: Joachim Happel
*/
class RW_BuddyPress_Docs_Tree {

    /**
     * @var     array
     * @since   0.1
     * @access  public
     */
    public $posts;

    /**
     * @var     int
     * @since   0.1
     * @access  public
     */
	public $group_id;

    /**
     * @var     srting
     * @since   0.1
     * @access  public
     */
	public $plugin_dir;

    /**
     * Constructor
     *
     * @since   0.1
     * @access public
     */
	public function __construct() {

        /* use jQuery extension jsTree to draw the Docs Tree */
		
		//add_action( 'wp_enqueue_scripts', array($this, 'load_jsTree_css') );
	}

    /**
     * javascript loader
     *
     * @access public
     * @since   0.1
     * @uses wp_enqueue_script
     */
    public function load_jsTree() {
        var_dump(get_stylesheet_uri);die();
		wp_enqueue_script(
			'custom-script',
            get_stylesheet_uri() . '/js/jquery.mjs.nestedSortable.js',
			array( 'jquery' )
		);
	}

    /**
     * stylesheet loader
     *
     * @access public
     * @since   0.1
     * @uses wp_enqueue_style
     */
    public function load_jsTree_css() {
		wp_enqueue_style( 'jsTreeStyle',$this->plugin_dir . 'js/themes/default/style.min.css' );
		wp_enqueue_style( 'jsTreeCustomStyle',$this->plugin_dir . 'css/style.css' );
	}


    /**
     * figure out the current used buddypress group_id
     *
     * @since   0.1
     * @access public
     * @returns int  $group_id
     */
	public function bd_docs_get_current_group_id(){
			 
		$group_id=false;
		
			
		if( bp_docs_is_bp_docs_page() && NULL !== bp_docs_get_current_doc() )
		{
			$group_id = bp_docs_get_associated_group_id(get_the_ID() );
		}
		else
			
		{
			
			$path = ( $_SERVER['REQUEST_URI'] );
			$p_arr = explode('/', $path );
			if( isset($p_arr[1]) && $p_arr[1] == bp_get_groups_root_slug() ){
				$slug = $p_arr[2];
				$group_id = BP_Groups_Group::get_id_from_slug( $slug ) ;
			}else{
				$u = parse_url( wp_get_referer() );
				$path = $u['path'];
				$p_arr = explode('/', $path );
			
				if( isset($p_arr[1]) && $p_arr[1] == bp_get_groups_root_slug() ){
					$slug = $p_arr[2];
					$group_id = BP_Groups_Group::get_id_from_slug( $slug ) ;
				}
			}
			
			
			
		}
		return $group_id;
		 
	}

	public static function bd_get_current_group_id(){
		return self::bd_docs_get_current_group_id();
	}

	public static function bd_get_query_args($defaults){

		global $bp;

		$qargs = array(
			'posts_per_page'   => -1,
			'paged'           => 0,
			'orderby'          => 'menu_order',
			'order'            => 'ASC',
			'post_type'        => 'bp_doc',
			'post_status'      => 'publish',
			'suppress_filters' => true
		);

		$tree = new RW_BuddyPress_Docs_Tree();

		$group_id = $tree->bd_docs_get_current_group_id();

		if($group_id){
			$qargs["tax_query"]=array(
				array(
					"taxonomy"=>"bp_docs_associated_item",
					"field"=>"slug",
					'terms'=>array( 'bp_docs_associated_group_'.$group_id )
				)
			) ;
		}else{
			$qargs['author'] = $bp->loggedin_user->id;
		}
		return wp_parse_args( $qargs, $defaults );
	}

    /**
     * Get Posts hierachical to prepare tree view
     *
     * @since   0.1
     * @access  public
     * @return array posts
     */
	function read_tree()
	{
		global $bp;
		
		$array=array();
		
		$this->group_id = $this->bd_docs_get_current_group_id();



		 $qargs = array(
			'posts_per_page'   => -1,
			'paged'           => 0,
			'orderby'          => 'menu_order',
			'order'            => 'ASC',
			'post_type'        => 'bp_doc',
			'post_status'      => 'publish',
			'suppress_filters' => true
		);
		
		if($this->group_id){			
			$qargs["tax_query"]=array(
				array(
					"taxonomy"=>"bp_docs_associated_item",
					"field"=>"slug",
					'terms'=>array( 'bp_docs_associated_group_'.$this->group_id )
				)
			) ;
		}else{
			$qargs['author'] = $bp->loggedin_user->id;
		}
				
		$posts = get_posts( $qargs );
		
		$this->posts = array();
		
		foreach ($posts as $post){


				$array[$post->ID]=$post->post_parent;
				$this->posts[$post->ID]=$post;

		}


		foreach ($array as $c => $p)  {
			if(!array_key_exists($p,$array)){
				$array[$c]=0;
			}
		}
		$tree = $this->to_tree($array);
		
		return $tree;
	}

    /**
     * array helper function
     *
     * @since   0.1
     * @access  private
     *
     * @param $array $posts
     * @return array $tree
     */
	
	private function to_tree($array)
	{
		
		$flat = array();
		$tree = array();
		foreach ($array as $child => $parent) {
			if (!isset($flat[$child])) {
				$flat[$child] = array();
			}
			if (!empty($parent)) {
				$flat[$parent][$child] =& $flat[$child];
			} else {
				$tree[$child] =& $flat[$child];
			}
		
		}
		return $tree;
	}

    /**
     * Display the Doc tree
     *
     * @since   0.1
     * @access  public
     *
     * @param array $tree
     */

	public function print_tree($tree, $tag='ul'){
		echo '<'.$tag.' class="tree-node">';
		foreach($tree as $parent=>$child){
			?>
			<li id="doc-id_<?php echo $parent;?>" data-id="<?php echo $parent;?>" class="sortableListsOpen">
                <?php

                if($parent!='root'){
                    $post=$this->posts[$parent];
                    ?>
					<div>
						<a class="docs-tree-link" title="<?php echo $post->post_title;?>" href="/<?php echo bp_docs_get_slug();?>/<?php echo $post->post_name;?>">
							<i class="fa fa-file-o"></i>
							<?php echo $post->post_title;?>
						</a>
					</div>
                    <?php

                }else{
                    if($child!='root'){
                        echo serialize($child);
                    }
                }
                if(count($child)>0){
                    $this->print_tree($child);
                }
                ?>

            </li>
            <?php
		}
		echo '</'.$tag.'>';
	}

    /**
     * Displays the widget
     *
     * @since   0.1
     * @access  public
     *
     * @used by register_widget
     *
     * @param array $args
     * @param array $instance
     */
	public static function the_docs_tree( $tag = 'ul' ,&$group_id) {
		
        $tree = new self;

		$posts = $tree->read_tree() ;
        $tree->print_tree($posts, $tag);

		$group_id = $tree->group_id;

	}
			
	/**
     * Widget Backend
     * Displays the Widget Config Form in Adminview
     *
     * @since   0.1
     * @access  public
     * @used by register_widget
     *
     */
	public function form( $instance ) {
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = __( 'Docs', 'boss' );
		}
		// Widget admin form
		?>
		<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>
		<?php 
	}

    /**
     * Widget Backend
     * Updating widget replacing old instances with new
     *
     * @since   0.1
     * @access  public
     * @used by register_widget
     *
     */
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		return $instance;
	}

    /**
     * register a new sidebar for the Docs Template
     *
     * @static
     * @since   0.1
     * @access  public

     */
    static function create_bp_docs_sitebar(){


        register_sidebar( array(
            'name' => __( 'Docs', 'buddypress-docs' ),
            'id' => 'docs',
            'description' => __( 'Widgets in this area will be shown on buddypress docs pages.', 'bp-docs-tree' ),
            'before_widget' => '<li id="%1$s" class="widget %2$s">',
            'after_widget'  => '</li>',
            'before_title'  => '<h3 class="widgettitle">',
            'after_title'   => '</h3>',
            )
        );

    }

    /**
     * checks, whether the current page contains buddypress docs contents
     *
     * @static
     * @since   0.1
     * @access  public
     *
     * @returns bool
     */
	static function is_bp_doc_page(){
		
		$dirs = explode ('/', $_SERVER['REQUEST_URI'] );
		$slug = isset( $dirs[1] ) ? $dirs[1] : '';
		
		if( $slug == bp_docs_get_slug()){
			return true;
		}
		if(!bp_is_group() && !bp_is_my_profile() && get_post_type() == 'bp_doc'  && !is_archive() )
		{
			return true;
		}
		return false;
	}


    /**
     * checks, whether the current page is in desktop view by checking the $_COOKIE['switch_mode']
     *
     * @static
     * @since   0.1
     * @access  public
     *
     * @used by boss theme
     * @ToDo move functions to extended Class
     *
     * @returns bool
     */
	static function is_desktop(){
		return !self::is_mobile();
	}

    /**
     * checks, whether the current page is in mobile view by checking the $_COOKIE['switch_mode']
     *
     * @static
     * @since   0.1
     * @access  public
     *
     * @used by boss theme
     * @ToDo move functions to extended Class
     *
     * @returns bool
     */
	static function is_mobile(){
		 if(isset ($_COOKIE['switch_mode']) && $_COOKIE['switch_mode']=='mobile'){
			return true;
		 }elseif (wp_is_mobile() ){
			return true;
		}
		return false;
	}
} // Class RW_BuddyPress_Docs_Tree_Widget ends here
