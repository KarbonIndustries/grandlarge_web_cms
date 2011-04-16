<?php
require_once($_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'inc' . DIRECTORY_SEPARATOR . 'init.php');

$directors = GL::getDirectors();

foreach($directors as $d)
{?>
	<tr directorId="<?= $d['id'] ?>" class="<?= GL::altStr('altRow') ?>">
		<td class="directorName"><?= $d['firstName'] . ' ' . $d['lastName'] ?></td>
		<td class="removeBtn"><button class="removeDirectorBtn">Remove</button></td>
	</tr>
<?}

GL::resetAlt();
?>