<?php
/*
Plugin Name: Generate Meta
Plugin URI: http://vzazerkalie.com/portf/
Description: Alicelf Generate Metaboxes plugin - Upload and Activate.
Author: Alicelf
Version: 0.1
Author URI: http://vzazerkalie.com/portf/
*/
global $post, $wp_meta_boxes;

/**
 * Class GenerateMeta
 * Available fields - number, text, file, color, textarea, select, checkboxlist
 */
//Template Todo: add types of fields- radiolist, datepicker

class GenerateMeta {

	public $id;
	public $title;
	public $screen;
	public $callback_arg;

	public $field_type = 'text';
	public $context = 'normal';
	public $priority = 'default';
	public $options;

	function __construct( $id, $title, $screen = null, $callback_args = null ) {
		$this->id     = $id;
		$this->title  = $title;
		$this->screen = $screen;

		$this->callback_arg = $callback_args;
	}

	public function setInputplace( $val = null ) {
		if ( $val ) {
			$this->context = $val;
		}

		return $this->context;
	}

	public function setPriority( $val ) {
		if ( $val ) {
			$this->priority = $val;
		}

		return $this->priority;
	}

	public function setFieldtype( $val = null ) {
		if ( $val ) {
			$this->field_type = $val;
		}

		return $this->field_type;
	}

	public function setOptions($val = null) {
		if($val){
			$this->options = $val;
		}
		return $this->options;
	}

	public function metaInvokeCallback($post) {
		$value = "";
		$output = null;
		$con_def_start = '<div class="GenerateMeta generateFieldMeta">';
		$con_check_start = '<div class="GenerateMeta checkbox-meta">';
		$con_file_start = '<div class="GenerateMeta generateFieldMeta al-filemeta-upload-wrap">';

		$def_label = '<label for="'.$this->id.'">'.$this->title.'</label>';

		if ( get_post_meta( $post->ID, $this->id, true ) ) {
			$value = get_post_meta( $post->ID, $this->id, true );
		}

		// Select type Field
		if ($this->field_type == 'select') {
			$output = $con_def_start . $def_label . "<select name='$this->id' id='$this->id'><option value=''>Empty</option>";
			foreach ( $this->options as $option ) {
				$selected = $value == $option['value']? 'selected="selected"':null;
				$output.="<option $selected value='".$option['value']."'>".$option['label']."</option> ";
			}
			echo $output.='</select></div>';

			// Checkbox type Field
		} elseif ( $this->field_type == 'checkbox' ) {
			$output = $con_check_start;
			foreach ( $this->options as $option) {
				if (is_array($value)) {
					$checked = in_array( $option[ 'value' ], $value ) ? "checked='checked'" : null;
				}
				$output.="<p><input type='checkbox' name='".$this->id."[]' id='".$option['value']."_checkid' $checked value='".$option['value']."'>";
				$output.="<label for='".$option[ 'value' ]."_checkid'>".$option['label']."</label></p>";
			}
			echo $output.='</div>';

			// File type Field
		} elseif ( $this->field_type == 'file' ) {
			$img = get_post_meta( $_GET[ 'post' ], $this->id, true );
			$output = $con_file_start.wp_nonce_field( plugin_basename( __FILE__ ), $this->id );
			$output.="<label class='label-meta-upload-cp' for='".$this->id."_id'><i class='fa fa-upload'></i>$this->title</label>";
			$output.="<input type='file' id='".$this->id."_id' name='$this->id' value='' size='25'>";

			if($img) {
				$output.="<div class='al-meta-img-container-wrap'>";
				$output.="<input id='".$this->id."_checkid' type='checkbox' name='".$this->id ."_remove_image' value=''>";
				$output.="<label title='Remove Image' for='".$this->id."_checkid'><i class='dissmiss-image fa fa-remove'></i></label>";
				$output.="<img src='".$img[ 'url' ]."' alt='admin_image'></div>";
			}
			echo $output.="</div>";

			// Textarea type Field
		} elseif ( $this->field_type == 'textarea' ) {
			$output = "<div class='GenerateMeta'><label for='".$this->id."_identifier'>$this->title</label>";
			echo $output.="<textarea name='$this->id' id='".$this->id."_identifier'>$value</textarea></div>";

			// Color type field
		} elseif ( $this->field_type == 'color' ) {
			$output = "<div class='GenerateMeta'><label for='".$this->id."_identifier'>$this->title</label>";
			echo $output.="<input class='meta_color_picker' type='text' id='".$this->id."_identifier' name='$this->id' value='$value'></div>";

			// Other types (text or number in current case)
		}  else {
			$output = "<div class='GenerateMeta'><label for='".$this->id."_identifier'>$this->title</label>";
			echo $output.="<input type='$this->field_type' value='$value' name='$this->id' id='".$this->id."_identifier'></div>";
		}
		//File Types END
	}

	public function metaInvoke() {
		add_meta_box(
			$this->id,     // $id
			$this->title,  // $title
			// CallBack
			array($this, 'metaInvokeCallback'),
			$this->screen,      // $post_type
			$this->context,     // $context  side normal advanced
			$this->priority     // $priority
//		  $this->callback_arg // ?
		);
	}

	public function run( $type = null, $place = null, $prio = null, $options = null ) {
		if ( $type ) {
			$this->setFieldtype( $type );
		}
		if ( $place ) {
			$this->setInputplace( $place );
		}
		if ( $prio ) {
			$this->setPriority( $prio );
		}
		if ($options && is_array($options)) {
			$this->setOptions($options);
		}

		add_action( 'add_meta_boxes', array($this, 'metaInvoke'));
	}

	//End Run Method  'project_price'
	public function saveMetaCallback($post_id) {
		if(isset($_POST[$this->id])) {
			update_post_meta( $post_id, $this->id, $_POST[ $this->id] );
		}
	}

	public function saveMetadata() {
		add_action('save_post', array($this, 'saveMetaCallback'));
	}

	public function saveFileCallback($id) {

		if(!isset($_POST[$this->id.'_remove_image'])) {
			if(!empty($_FILES[$this->id]['name'])) {
				$upload = wp_upload_bits($_FILES[$this->id]['name'], null, file_get_contents($_FILES[$this->id]['tmp_name']));

				if(isset($upload['error']) && $upload['error'] != 0) {
					wp_die('There was an error uploading your file. The error is: ' . $upload['error']);
				} else {
					update_post_meta($id, $this->id, $upload);

					$filename = $_FILES[$this->id]['name'];
					$filetype = wp_check_filetype( basename( $filename ), null );

					$attachment = array(
						'guid'           => $upload['url'],
						'post_mime_type' => $filetype['type'],
						'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $filename ) ),
						'post_content'   => '',
						'post_status'    => 'inherit'
					);
					$attach_id = wp_insert_attachment( $attachment, $upload['url'], $id );
					require_once( ABSPATH . 'wp-admin/includes/image.php' );
					$attach_data = wp_generate_attachment_metadata( $attach_id, $upload['url'] );
					wp_update_attachment_metadata( $attach_id, $attach_data );
				}

			}
		} else {
			update_post_meta($id, $this->id, $_POST[$this->id.'_remove_image']);
		}
	}

	public function saveFileData() {

		add_action('save_post', array($this, 'saveFileCallback'));
	}

	public function renderMetadata() {
		$post      = $_GET;
		$post_id   = $post[ 'post' ];
		$get_post  = get_post( $post_id, ARRAY_A );
		$result_id = $get_post[ 'ID' ];

		if ( get_post_meta( $result_id, $this->id, true ) ) {
			return get_post_meta( $result_id, $this->id, true );
		}
	}

	public function fetchAllMeta( $post_type = null ) {
		global $post;
		$ignore_values = array( '_edit_last', '_edit_lock', '_thumbnail_id' );
		if ( get_post_type() == $post_type ) {
			?>
			<table class="table table-striped table-bordered table-hover">
				<tr>
					<th>Key</th>
					<th>Value</th>
				</tr>
				<?php foreach ( get_post_meta( $post->ID ) as $single_meta => $val ) {
					if ( in_array( $single_meta, $ignore_values ) ) {
						continue;
					}
					$meta_item_parse = get_post_meta( $post->ID, $single_meta, true );
					if ( is_array( $meta_item_parse ) ) {
						echo "<tr><td>" . ucfirst( str_replace( '_', ' ', $single_meta ) ) . "</td>
							<td>" . $meta_item_parse[ 'url' ] . "</td></tr>";
					} else {
						echo "<tr><td>" . ucfirst( str_replace( '_', ' ', $single_meta ) ) . "</td>
							<td>" . $meta_item_parse . "</td></tr>";
					}

				} ?>
			</table>
		<?php
		}
	}

}

function update_form_tag_incapsulation() {
	echo ' enctype="multipart/form-data"';
}

add_action( 'post_edit_form_tag', 'update_form_tag_incapsulation' );