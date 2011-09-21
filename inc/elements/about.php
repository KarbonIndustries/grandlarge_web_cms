<h1>About</h1>

<?php
$PAGE = $_SERVER['PHP_SELF'] . '?p=about';
$mimeTypes = array('image/jpeg');
$file = new fUpload();
$file->setMimeTypes($mimeTypes,'The image must be jpg');
$file->setImageDimensions(ABOUT_IMG_WIDTH,ABOUT_IMG_HEIGHT,ABOUT_IMG_WIDTH,ABOUT_IMG_HEIGHT);
$file->setMaxSize('200k');
$file->enableOverwrite();
$upload = !empty($_FILES['image']['tmp_name']);
$post = $_SERVER['REQUEST_METHOD'] == 'POST';
?>

<!-- ============== -->
<!-- = EDIT ABOUT = -->
<!-- ============== -->

<div id="addAboutShell">
	<h3>Edit About</h3>

<?php

if($post)
{
	$error = false;
	if($upload)
	{
		try
		{
			$img = $file->move(ABOUT_DIR,'image');
			$img->rename(ABOUT_IMG_NAME,true);
			if($img->getWidth() != ABOUT_IMG_WIDTH || $img->getHeight() != ABOUT_IMG_HEIGHT)
			{
				$img->resize(ABOUT_IMG_WIDTH,ABOUT_IMG_HEIGHT,true);
			}
			$img->saveChanges();

		}catch(fValidationException $e)
		{
			$error = true;
			echo '<p>Please try again, making sure the image meets the requirements below.</p>';
		}
	}

	if(!$error)
	{
		$info['image'] = ABOUT_IMG_NAME;
		$info['col1']  = $_POST['col1'];
		$info['col2']  = $_POST['col2'];
		$updated = GL::updateAbout($info,false);
		echo $updated ? '<p>About was successfully updated.</p>' : null;
	}
}

$about = GL::getAbout();
?>

	<div id="">
		<h3>Image</h3>
		<img src="<?=ABOUT_DIR . ABOUT_IMG_NAME?>?v" />
		<form name="" action="<?= $PAGE ?>" method="POST" enctype="multipart/form-data" accept-charset="utf-8">
			<input type="hidden" name="MAX_FILE_SIZE" value="200000"/>
			<p style="font-size:11px;">Max file size: 200kb, Dimensions: 960px x 214px, Format: jpg</p>

			<div id="addAboutImageRow">
				<input id="image" name="image" type="file"/>
			</div>
			<div id="addAboutTextRow">
				<div style="float:left;margin-right:15px;">
					<h3>Column 1</h3>
					<textarea style="font-size:14px;" name="col1" rows="14" cols="58"><?=$about ? $about['col1'] : ''?></textarea>
				</div>
				<div style="float:left;">
					<h3>Column 2</h3>
					<textarea style="font-size:14px;" name="col2" rows="14" cols="58"><?=$about ? $about['col2'] : ''?></textarea>
				</div>
				<div style="clear:both;"></dvi>
			</div>

			<p><input type="submit" value="Submit"></input></p>
		</form>
	</div>
</div>

<div style="margin-bottom:100px"></div>

<script type="text/javascript" charset="utf-8">
	//notable.init();
</script>