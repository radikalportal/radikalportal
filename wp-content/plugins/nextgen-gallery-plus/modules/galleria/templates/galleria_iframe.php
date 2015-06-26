<!DOCTYPE html>
<html>
	<head>
        <meta name='viewport' content='width=device-width, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no'>
		<script type="text/javascript" src="<?php echo esc_attr($jquery_url); ?>"></script>
		<script type="text/javascript" src="<?php echo esc_attr($galleria_url); ?>"></script>
		<script type="text/javascript"><?php echo $galleria_instance_js; ?></script>
		<style type="text/css">
			body, html, #galleria {
				height: 100%;
				width: 100%;
				outline: 0;
			}
		</style>
	</head>
	<body>
		<div id="galleria" tabindex="1"></div>
        <script type='text/javascript'>
            window.ngg_settings = <?php echo json_encode($ngg_settings); ?>;
            window.Galleria_Instance.create("<?php echo $id; ?>");
        </script>
	</body>
</html>
