<?php

/**
 * Class AliceJwrap
 * run() - init app
 * render() - render form
 * fieldFactory() - generate form fields
 * text, number, textarea, colorpicker, upload, checkboxlist(radiolist), select
 */
class AliceJwrap extends AliceInit {

	protected $_sections;

	public function getWpdb() {
		global $wpdb;

		return $wpdb;
	}

	public function bootstrapParts() {
		$path = get_template_directory_uri() . '/bootstrap_sass/javascripts/bootstrap';
		wp_enqueue_script( 'admin-tab-script', $path . '/tab.js', array(), false, true );
		wp_enqueue_script( 'tooltip-script', $path . '/tooltip.js', array(), false, true );
	}

	public function createAdminPage() {
		add_menu_page( $this->_page_title, $this->_menu_title, $this->_capability, $this->_menu_slug, array( $this, 'render' ), '', 61 );
	}

	public function __construct() {
		parent::__construct();
		add_action( 'admin_head', array( $this, 'bootstrapParts' ) );
	}

	public function run( $pt, $mt, $ms ) {
		$this->_page_title = $pt;
		$this->_menu_title = $mt;
		$this->_menu_slug  = $ms;

		add_action( 'admin_menu', array( $this, 'createAdminPage' ) );
	}

	public function render() {

		$data = array();

		$this->formStart();
		foreach ( $this->describe() as $row ) {
			if ( $row[ 'Field' ] == 'id' ) {
				continue;
			}

			$fieldtype = null;

			if ( strpos( $row[ 'Type' ], 'int' ) > - 1 ) {
				$fieldtype               = 'number';
				$data[ $row[ 'Field' ] ] = $this->fieldFactory( $fieldtype, $row[ 'Field' ] );
			} elseif ( strpos( $row[ 'Type' ], 'varchar' ) > - 1 ) {

				if ( strpos( $row[ 'Field' ], 'upload' ) > - 1 ) {
					$fieldtype               = 'file';
					$data[ $row[ 'Field' ] ] = $this->fieldFactory( $fieldtype, $row[ 'Field' ] );
				} else {
					$fieldtype               = 'text';
					$data[ $row[ 'Field' ] ] = $this->fieldFactory( $fieldtype, $row[ 'Field' ] );
				}

			} elseif ( strpos( $row[ 'Type' ], 'text' ) > - 1 ) {
				$fieldtype               = 'textarea';
				$data[ $row[ 'Field' ] ] = $this->fieldFactory( $fieldtype, $row[ 'Field' ] );
			}
		}
		$this->renderSections( $data );
		$this->formEnd();
	}

	public function renderSections( $data ) {
		//var_dump( $data ); -all fields is here
		if ( $this->_sections ) {

			$head_part      = '';
			$content_part   = '';
			$active_tab     = 'active';
			$active_content = 'active';

			foreach ( $this->_sections as $section => $field ) {
				$id      = str_replace( " ", "-", strtolower( $section ) );
				$f       = '';
				$arrayed = null;

				foreach ( $field as $table_data ) {
					if ( ! is_array( $table_data ) ) {
						$f .= $data[ $table_data ];
					} else {
						$arrayed = true;
					}
				}
				/**
				 * If is no regular field - setup arrayed values
				 */
				if ( $arrayed ) {
					foreach ( $field as $table_field => $value_field ) {

						if ( is_array( $value_field ) ) {
							$select_from_db_handler = $this->getWpdb()->get_results( "SELECT $table_field FROM $this->_table_name", ARRAY_A );

							$in_before = '<div class="clearfix row-with-input col-sm-4 well al_' . $value_field[ 0 ]
							             . '"><label>' . str_replace( "_", " ", $table_field ) . '</label>';
							$in_after  = '</div>';

							$f .= $in_before;
							if ( $value_field[ 0 ] == 'select' ) {
								$f .= '<select id="' . $table_field . '" name="' . $table_field . '[]">';
							}
							foreach ( array_slice( $value_field, 1 ) as $option_value ) {
								$checked = '';
								foreach ( $select_from_db_handler as $serialized_array ) {
									foreach ( $serialized_array as $unserialized ) {
										if ( $unserialized ) {
											if ( in_array( $option_value, unserialize( $unserialized ) ) ) {

												if ( $value_field[ 0 ] !== 'select' ) {
													$checked = 'checked';
												} else {
													$checked = 'selected';
												}
											}
										}
									}
								}
								$labl = ucfirst( str_replace( "_", " ", $option_value ) );

								if ( $value_field[ 0 ] !== 'select' ) {
									$f .= '<p class="child_labels"><label for="' . $table_field . $option_value . '">' . $labl . '</label>
											<input ' . $checked . ' value="' . $option_value . '" id="' . $table_field . $option_value . '" type="' . $value_field[ 0 ] . '" name="' . $table_field . '[]"></p>';
								} else {
									$f .= '<option ' . $checked . ' value="' . $option_value . '">' . $option_value . '</option>';
								}
							}
							if ( $value_field[ 0 ] == 'select' ) {
								$f .= "</select>";
							}
							$f .= $in_after;

						}
					}
				}
				$head_part .= '<li class="' . $active_tab . '" role="presentation"><a href="#' . $id . '" role="tab" data-toggle="tab"><h3>' . $section . '</h3></a></li>';
				$content_part .= '<div role="tabpanel" class="tab-pane ' . $active_content . '" id="' . $id . '">' . $f . '</div>';
				$active_tab     = null;
				$active_content = null;
//				unset ( $active_tab, $active_content );
			}
			$total_heads   = '<ul class="nav nav-tabs" role="tablist">' . $head_part . '</ul>';
			$total_content = '<div class="tab-content">' . $content_part . '</div>';
		}
		echo $total_heads . $total_content;
	}

	public function createSections( $data ) {
		$this->_sections = $data;
	}

	public function fieldFactory( $field, $id ) {

		foreach ( $this->getWpdb()->get_results( "SELECT $id FROM $this->_table_name", ARRAY_A ) as $key ) {
			foreach ( $key as $val ) {
				$value = $val;
			}
		}
		$input_class = null;
		if ( strpos( $id, 'color' ) > - 1 ) {
			$input_class = 'al_colorpicker';
		} else {
			$input_class = 'al_textinput';
		}

		$label = ucfirst( str_replace( "_", " ", $id ) );
		switch ( $field ) {
			case 'textarea':
				$output = "
					<div class='clearfix row-with-input col-sm-4 well al_textarea'>
					<label for='" . $id . "'><i class='fa'></i>" . $label . "</label>
					<textarea id='" . $id . "' name='" . $id . "'>" . $value . "</textarea></div>";
				break;
			// Template Todo: add available file types and file sizes
			case 'file':
				$img    = $value ? "<div class='image-wrap'><img src='" . $value . "'><i class='dismiss-image fa fa-remove'></i></div>" : "";
				$output = "
					<div class='clearfix row-with-input col-sm-4 well al_fileinput'>
					<label for='" . $id . "'><i class='fa fa-upload'></i>" . $label . "</label>" . $img . "
					<input id='" . $id . "' type='file' name='" . $id . "'/>
					<input type='hidden' value='" . $value . "' name='" . $id . "_hiden_val'>
					</div>";
				break;

			case 'number' :
				$output = "
					<div class='clearfix row-with-input col-sm-4 well al_numberinput'>
					<label for='" . $id . "'><i class='fa'></i>" . $label . "</label>
					<input value=" . $value . " id='" . $id . "' type='number' name='" . $id . "'/></div>";
				break;

			default :
				$output = "
					<div class='clearfix row-with-input col-sm-4 well " . $input_class . "'>
					<label for='" . $id . "'><i class='fa'></i>" . str_replace( 'Social ', '', $label ) . "</label>
					<input value='" . $value . "' id='" . $id . "' type='text' name='" . $id . "'/></div>";
		}

		return $output;
	}
}