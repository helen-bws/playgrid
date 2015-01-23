<?php

/**
 * Class AliceInit
 * Theme Customizer version 1.0
 * __construct() - Create dir for uploads, set favicon and enque scripts
 * createTable() - Creates database tables
 * describe() -show fields
 * tableData() - get results from table
 * invokeAction() - action of form ($_POST , $_FILES etc)
 * formStart() - render open form tag with invokeAction()
 * formEnd() - render close form tag with submit
 * globs() - set globals
 */
class AliceInit {

	protected $_page_title;
	protected $_menu_title;
	protected $_capability = 'edit_theme_options';
	protected $_menu_slug;

	protected $_table_name;
	protected $_table_fields;
	protected $_post_submit;
	protected $_dir_path;

	public static function getInstance() {
		static $instance = null;
		if ( null === $instance ) {
			$instance = new static();
		}

		return $instance;
	}

	/**
	 * Include scripts for Admin Page
	 */
	public function adminScripts() {
		wp_enqueue_style( 'al-bootstrap-parts', get_template_directory_uri() . '/alicelf_theme_setup/bootstrap-fragments.css' );
		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_style( 'al-them-stl', get_template_directory_uri() . '/alicelf_theme_setup/theme_admin_stl.css' );
		wp_enqueue_script( 'wp-color-picker' );
		wp_enqueue_script( 'alicelf-theme-script', get_template_directory_uri() . '/alicelf_theme_setup/theme-script.js', array(), false, true );
	}

	public function getDynamicStyles() {
		include_once get_template_directory() . '/alicelf_theme_setup/dynamic_style.php';
	}

	public function getDynamicScripts() {
		include_once get_template_directory() . '/alicelf_theme_setup/admin_dynamic_script.php';
	}

	public function __construct() {
		$this->_dir_path = get_template_directory() . '/alice_upload';

		add_action( 'admin_enqueue_scripts', array( $this, 'adminScripts' ) ); // Collection of regular scripts/styles for admin page
		add_action( 'wp_head', array( $this, 'getDynamicStyles' ) );           // Dynamic styles in frontend with favicon
		add_action( 'admin_footer', array( $this, 'getDynamicScripts' ) );     // Dynamic scripts (for featured reasons) backend

		if ( ! is_dir( $this->_dir_path ) ) {
			mkdir( $this->_dir_path, 0777 );
		}
	}

	public function __clone() {
	}

	public function createTable( $table_name, $args ) {
		global $wpdb;
		$this->_post_submit  = $table_name . '_submit';
		$this->_table_name   = $wpdb->prefix . $table_name;
		$this->_table_fields = $args;
		$combine_query       = "";

		foreach ( $args as $field ) {
			$var_val = strpos( $field[ 1 ], 'varchar' ) > - 1 ? '(255)' : '';
			$combine_query .= $field[ 0 ] . " " . strtoupper( $field[ 1 ] ) . $var_val . " NOT NULL, ";
		}

		$q = "CREATE TABLE IF NOT EXISTS $this->_table_name (
			id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY, " . substr( $combine_query, 0, - 2 ) . ")
			ENGINE = InnoDB CHARACTER SET utf8 COLLATE utf8_general_ci;";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $q );
	}

	public function globs() {
		$alice_globals = array();
		if ( $this->tableData() ) {
			foreach ( $this->tableData() as $result ) {
				foreach ( $result as $r => $v ) {
					if ( $r == 'id' ) {
						continue;
					}
					$alice_globals[ $r ] = $v;
				}
			}
		}

		return $alice_globals;
	}

	public function describe() {
		global $wpdb;

		return $wpdb->get_results( "DESCRIBE $this->_table_name", ARRAY_A );
	}

	public function tableData() {
		global $wpdb;
		$result = $wpdb->get_results( "SELECT * from $this->_table_name", ARRAY_A );
		if ( ! empty( $result ) ) {
			return $result;
		}

		return false;
	}

	public function formStart() {
		$this->invokeAction();
		echo "<h1 class='form-heading'>$this->_page_title</h1>
		<form id='" . $this->_table_name . "-form-identifier' class='al_admin_area' method='post' enctype='multipart/form-data'><div class='row inset-row'>";
	}

	public function formEnd() {
		echo "</div><button name='" . $this->_post_submit . "' class='btn form-button' type='submit'>
		<i class='fa fa-refresh'></i> Save Changes
		</button></form>";
	}

	public function invokeAction() {
		global $wpdb;
		if ( $this->tableData() ) {
			foreach ( $this->tableData() as $data ) {
				$id = $data[ 'id' ];
			}
		}

		if ( isset( $_POST[ $this->_post_submit ] ) ) {
			$result = array();

			if ( $_FILES ) {
				foreach ( $_FILES as $file => $attributes ) {
					$tmp_name = $_FILES[ $file ][ "tmp_name" ];
					$name     = $_FILES[ $file ][ "name" ];

					// '_hiden_val'
					if ( ! file_exists( "$this->_dir_path/$name" ) ) {
						move_uploaded_file( $tmp_name, "$this->_dir_path/$name" );
						if ( ! empty( $name ) ) {
							$_POST[ $file ] = get_template_directory_uri() . '/alice_upload/' . $name;
						}
					} elseif ( file_exists( "$this->_dir_path/$name" ) && empty( $_POST[ $file . '_hiden_val' ] ) ) {
						if ( ! empty( $name ) ) {
							$_POST[ $file ] = get_template_directory_uri() . '/alice_upload/' . $name;
						}
					} else {
						$_POST[ $file ] = $_POST[ $file . '_hiden_val' ];
					}
				}
			}

			foreach ( $this->describe() as $row ) {
				if ( $row[ 'Field' ] == 'id' ) {
					continue;
				}

				if ( ! is_array( $_POST[ $row[ 'Field' ] ] ) ) {
					$result[ $row[ 'Field' ] ] = $_POST[ $row[ 'Field' ] ];
				} else {
					$result[ $row[ 'Field' ] ] = serialize( $_POST[ $row[ 'Field' ] ] );
				}
			}

			if ( ! $this->tableData() ) {
				$wpdb->insert( $this->_table_name, $result );
			} else {
				$wpdb->update( $this->_table_name, $result, array( 'id' => $id ) );
			}
		}
	}

}