<h1>Notable</h1>

<?php
$PAGE = $_SERVER['PHP_SELF'] . '?p=notable';
$mimeTypes = array('image/jpeg','image/png','image/gif','image/pjpeg');
$file = new fUpload();
$file->setMimeTypes($mimeTypes,'The image must be jpg, png or gif');
$file->setImageDimensions(188,100,188,100);
$file->setMaxSize('30k');
$file->enableOverwrite();
?>

<!-- =============== -->
<!-- = ADD NOTABLE = -->
<!-- =============== -->

<div id="addNotableShell">
	<h3>Add Notable</h3>
	
	<?php

	if($_FILES && $_POST['notableTitle'] && filter_var($_POST['notableURL'],FILTER_VALIDATE_URL))
	{
		try
		{
			$img = $file->move(NOTABLE_DIR,'image');
			$newName = time() . '_' . md5_file(NOTABLE_DIR . $img->getName()) . '.' . $img->getExtension();
			$img->rename($newName,true);
			if($img->getWidth() != 188 || $img->getHeight() != 100)
			{
				$img->resize(188,100,true);
			}
			$img->saveChanges();
			$title = $_POST['notableTitle'];
			$url   = $_POST['notableURL'];
			if(GL::addNotable($title,$newName,$url))
			{
				echo '<p>' . $_POST['notableTitle'] . ' was successfully added.</p>';
			}else
			{
				echo '<p>There was a problem saving the item. Please try again.</p>';
			}
			
		}catch(fValidationException $e)
		{
			echo '<p>Please try again, making sure the image meets the requirements below.</p>';
		}
	}else
	{
		if($_POST)
		{
			echo '<p>Please choose a title, url and image.</p>';
		}
	}

	?>
	
	<p style="font-size:11px;">Max file size: 30kb, Dimensions: 188px x 100px, Formats: jpg, png, gif</p>

	<!-- upload form -->
	<div id="">
		<form name="" action="<?= $PAGE ?>" method="POST" enctype="multipart/form-data" accept-charset="utf-8">
			<input type="hidden" name="MAX_FILE_SIZE" value="30000"/>
			<div id="addNotableTextRow">
				<input id="notableTitle" name="notableTitle" type="text" value="Title"/>
				<input id="notableURL" name="notableURL" type="text" value="URL"/>
			</div>
			<div id="addNotableButtonRow">
				<input id="image" name="image" type="file"/>
				<input type="submit" value="Upload"></input>
			</div>
		</form>
	</div>
</div>


<!-- ================ -->
<!-- = EDIT NOTABLE = -->
<!-- ================ -->

<div id="editNotableShell">
	<h3>Edit Notable</h3>

	<table id="notableList" border="0" cellspacing="0" cellpadding="2">
	</table>
</div>


<?php
/*

(
    [image] => Array
        (
            [name] => IMG_0199.jpg
            [type] => image/jpeg
            [tmp_name] => /Applications/MAMP/tmp/php/phpdq0COx
            [error] => 0
            [size] => 6190787
        )

)


$uploads_dir = '/uploads';
foreach ($_FILES["pictures"]["error"] as $key => $error) {
    if ($error == UPLOAD_ERR_OK) {
        $tmp_name = $_FILES["pictures"]["tmp_name"][$key];
        $name = $_FILES["pictures"]["name"][$key];
        move_uploaded_file($tmp_name, "$uploads_dir/$name");
    }
}

*/
?>








<?php
function addItem()
{
	$errors = array();
	if(!empty($_FILES['fullSizeImgs']['name'][0]))
	{
		foreach($_FILES['image']['name'] as $key => $val)
		{
			$img1 = getimagesize($_FILES['fullSizeImgs']['tmp_name'][$key]);
			$img2 = getimagesize($_FILES['smlThumbs']['tmp_name'][$key]);
			
			if(isset($_FILES['fullSizeImgs']['name'][$key])
			&& $img1[1] == IMG_FULL_HEIGHT 
			&& isset($_FILES['smlThumbs']['name'][$key]) 
			&& $img2[0] == IMG_SML_SIZE 
			&& $img2[1] == IMG_SML_SIZE 
			&& isset($_POST['pubs'][$key]) 
			&& isset($_POST['months'][$key]) 
			&& isset($_POST['years'][$key]))
			{
				$nameKey[$key] = time() + $key;
				$uploadCode = 0;
				$uploadCode += move_uploaded_file($_FILES['fullSizeImgs']['tmp_name'][$key],PRESS_IMG_DIR . DS . IMG_PREFIX . $nameKey[$key] . IMG_FULL_SUFFIX . IMG_EXT);
				$uploadCode += move_uploaded_file($_FILES['smlThumbs']['tmp_name'][$key],PRESS_IMG_DIR . DS . IMG_PREFIX . $nameKey[$key] . IMG_SML_SUFFIX . IMG_EXT);
				$query = "INSERT INTO `press` (`pub`,`month`,`year`,`image`) ";
				$query .= "VALUES(";
				$query .= "'" . mysqlClean($_POST['pubs'][$key]) .		"',";
				$query .= "'" . mysqlClean($_POST['months'][$key]) .		"',";
				$query .= "'" . mysqlClean($_POST['years'][$key]) .		"',";
				$query .= "'" . mysqlClean($nameKey[$key]) .			"')";
				
				if($uploadCode == 2)
				{
					if(!runQuery($query))
					{
						redirectTo("press.php?result=0&msg=" . urlencode("There was an error adding {$_POST['pubs'][$key]}. Please try again."));
					}else
					{
						redirectTo("press.php?result=1&msg=" . urlencode("{$_POST['pubs'][$key]} was successfully added."));
					}
				}
			}else
			{
				redirectTo("press.php?result=0&msg=" . urlencode("Please make sure your images are the correct size and try again."));
			}
		}
	}
}

function deleteItem()
{
	// delete item(s) from press
	if(isset($_GET['order']))
	{
		deleteImagesInSet($_GET['order'],'press');
	}
}

?>

<div style="margin-bottom:100px"></div>

<script type="text/javascript" charset="utf-8">
	notable.init();
</script>
