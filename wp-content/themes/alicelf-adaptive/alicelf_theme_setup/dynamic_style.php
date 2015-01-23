<?php
global $alice;

$compiled_style = '';
$alice_constructor = $alice->globs();
$alice_constructor[ 'font_color' ] ? $color = $alice_constructor[ 'font_color' ] : $color = '#333';
$alice_constructor[ 'headers_color' ] ? $h_color = $alice_constructor[ 'headers_color' ] : $h_color = '#333';
$alice_constructor[ 'main_width' ] ? $basewidth = $alice_constructor[ 'main_width' ] : $basewidth = "900";

$compiled_style .= '#site-content {color :' . $color . '}';
$compiled_style .= 'h1, h2, h3, h4, h5, h6 {color:' . $h_color . ';}';

$navbar = unserialize( $alice_constructor[ 'navbar_options' ] );
if ( is_array( $navbar ) ) {
	foreach ( $navbar as $value ) {

		if ( $value == 'Stick Navbar' ) {
			$fixed_out = 'position: fixed; width : 100%; z-index: 3;';
			if ( is_user_logged_in() ) {
				$fixed_out .= 'top:32px;';
			} else {
				$fixed_out .= 'top : 0;';
			}
			$compiled_style .= ".site-header{margin-top:42px;} .navigator-wrapper { " . $fixed_out . " } ";
		}

	}
}
$favico = null;
foreach ( $alice->globs() as $k => $v ) {
	if ( strstr( $k, 'favicon' ) ) {
		if ( ! empty( $v ) ) {
			$favico = "<link rel='icon' type='image/png' href='" . $v . "'>";
		} else {
			$favico = "<link rel='icon' type='image/png' href='" . get_template_directory_uri() . "/favicon.ico'>";
		}
	}
}
if ( $favico ) {
	echo $favico;
}

?>
	<style>
		<? if($compiled_style) {echo $compiled_style;} ?>

		@media (min-width : <?= $basewidth ?>px) {
			.ghostly-wrap {
				max-width : <?= $basewidth ?>px;
				width     : 100%;
				margin    : 0 auto;
			}
		}

		@media (min-width : <?= $basewidth ?>px) {
			.container {
				width   : 100%;
				padding : 0;
			}
		}

		#alice_map_canvas {
			width  : 100%;
			height : 400px;
		}
	</style>
<?
// 49.9594179,36.3154955
if ( $alice_constructor[ 'contact_map' ] ) { ?>
	<script src="https://maps.googleapis.com/maps/api/js?sensor=false&language=en"></script>
	<script>
		function initialize() {
			var mapCanvas = document.getElementById('alice_map_canvas');
			var styles = [
				{
					featureType: "all",
					elementType: "geometry",
					stylers: [
						{hue: '#00ddff'},
						{saturation: -45},
						{lightnes: 10},
						{gamma: 0.58},
						{weight: 0.58}
					]
				}
			];
			var myLatlng = new google.maps.LatLng(<?=$alice_constructor[ 'contact_map' ]?>);
//		var myLatlng = new google.maps.LatLng(40.81,-73.04);
			var mapOptions = {
				center: myLatlng,
				zoom: 8
			};
			var map = new google.maps.Map(mapCanvas, mapOptions);
			map.set('styles', styles);
			var marker = new google.maps.Marker({
				position: myLatlng
			});
			marker.setMap(map)
		}
		google.maps.event.addDomListener(window, 'load', initialize);
	</script>
<?php } ?>