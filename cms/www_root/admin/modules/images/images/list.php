<?php
	// No direct access
	defined('_ACCESS') or die;
	
	include_once "dao/image_dao.php";
	
	$image_dao = ImageDao::getInstance();
	
	if (isset($_GET['action']) && $_GET['action'] == 'search') {
		$keyword = NULL;
		if (isset($_GET['s_title']) && $_GET['s_title'] != '') {
			$keyword = $_GET['s_title'];
		}
		$filename = NULL;
		if (isset($_GET['s_filename']) && $_GET['s_filename'] != '') {
			$filename = $_GET['s_filename'];
		}
		$label_id = NULL;
		if (isset($_GET['s_label']) && $_GET['s_label'] != '') {
			$label_id = $_GET['s_label'];
		}
		
		$images = $image_dao->searchImages($keyword, $filename, $label_id);
	} else if (isset($_GET['no_labels']) && $_GET['no_labels'] == 'true') {
		$images = $image_dao->getAllImagesWithoutLabel();
	} else {
		$images = $image_dao->getAllImages();
	}
?>

<fieldset class="admin_fieldset images_list">
	<div class="fieldset-title">Gevonden afbeeldingen</div>
	<?php if (count($images) > 0): ?>
		<?php if (isset($_GET['s_title'])): ?>
		<p style="margin-left: 10px; margin-bottom: 0px;"><strong><em>Zoekterm: </em></strong>&nbsp;'<?= $_GET['s_title']; ?>'<br />
		<?php endif; ?>
		<?php if (isset($_GET['s_filename']) && $_GET['s_filename'] != ''): ?>
		<strong><em>Bestandsnaam: </em></strong>&nbsp;'<?= $_GET['s_filename']; ?>'<br />
		<?php endif; ?>
		<?php if (isset($label_id) && $label_id != ''): ?>
		<strong><em>Label: </em></strong>&nbsp;'<?php $label = $image_dao->getLabel($label_id); echo $label->getName(); ?>'</p>
		<?php endif; ?>
		<br />
		<table class="listing" width="95%" cellspacing="0" cellpadding="5" border="0">
			<colgroup width="50"></colgroup>			
			<colgroup width="200px"></colgroup>
			<colgroup width="50px"></colgroup>
			<colgroup width="50px"></colgroup>
			<colgroup width="10px"></colgroup>
			<thead>
				<tr>
					<th>Thumbnail</th>
					<th>Titel</th>
					<th>Aangemaakt op</th>
					<th>Aangemaakt door</th>
					<th>Gepubliceerd</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($images as $image): ?>
					<tr>
						<td><img title="<?= $image->getTitle(); ?>" src="/admin/upload.php?image=<?= $image->getId(); ?>&amp;thumb=true" alt="<?= $image->getTitle(); ?>" /></td>
						<td><a href="/admin/index.php?image=<?= $image->getId(); ?>" title="<?= $image->getTitle(); ?>"><?= $image->getTitle(); ?></a></td>
						<td><?= $image->getCreatedAt(); ?></td>
						<td>
							<?php
								$user = $image->getCreatedBy();
								if (!is_null($user)) {
									echo $user->getUsername();
								}
							?>
						</td>
						<td>
							<?php
								if ($image->getPublished() == 0) {
									echo '<img alt="Depubliceren" src="/admin/static.php?static=/default/img/default_icons/red_flag.png" />';
								} else {
									echo '<img alt="Publiceren" src="/admin/static.php?static=/default/img/default_icons/green_flag.png" />';
								}
							?>
						</td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
		<br />
	<?php else: ?>
	<?php 
		include_once "libraries/renderers/main_renderer.php";
		MainRenderer::renderInformationMessage("Geen artikelen gevonden.");
	?>
	<?php endif; ?>
</fieldset>
