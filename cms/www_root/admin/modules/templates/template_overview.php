<?php
	// No direct access
	defined('_ACCESS') or die;
		
	include_once "dao/template_dao.php";
	include_once "libraries/renderers/form_renderer.php";
	include_once "dao/scope_dao.php";
	
	$template_dao = TemplateDao::getInstance();
	$scope_dao = ScopeDao::getInstance();
	
	$template_dir = Settings::find()->getFrontendTemplateDir();
	
	$template_id = '';
	if (isset($_GET['template'])) {
		$template_id = $_GET['template'];
	} else if (isset($_POST['template_id']) && $_POST['template_id'] != '') {
		$template_id = $_POST['template_id'];
	}
	
	if ($template_id != '') {
		$current_template = $template_dao->getTemplate($template_id);
	}
?>

<form id="template_form" method="post" action="/admin/index.php<?php if (isset($current_template)) { echo '?template=' . $current_template->getId(); } ?>" enctype="multipart/form-data">
	
	<fieldset class="displaynone">
		<input type="hidden" name="action" id="action" value="" />
	</fieldset>
	
	<?php if (isset($current_template) && !is_null($current_template)): ?>
		<fieldset class="admin_fieldset">
			<div class="fieldset-title">Template bewerken</div>
			<input type="hidden" value="<?= $current_template->getId(); ?>" name="template_id" id="template_id" />
			
			<ul class="admin_form">
				<?php
								
					echo '<li>';
					FormRenderer::renderTextField('name', 'Naam', $current_template->getName(), true, false, NULL);
					echo '</li>';
					
					echo '<li>';
					FormRenderer::renderTextField('file_name', 'Bestandsnaam', $current_template->getFileName(), false, false, NULL);
					echo '</li>';
					
					echo '<li>';
					$scopes_name_value_pair = array();
					foreach ($scope_dao->getScopes() as $scope) {
						array_push($scopes_name_value_pair, array('name' => $scope->getName(), 'value' => $scope->getId()));
					}
					$scope = $current_template->getScope();
					FormRenderer::renderPullDown('scope', 'Scope', (is_null($scope) ? null : $scope->getId()), $scopes_name_value_pair, 200, true);
					echo '</li>';
					
					echo '<li>';
					FormRenderer::renderFileUpload('template_file', 'Template', false);
					echo '</li>';
				?>
			</ul>
			<img class="back-image" src="/admin/static.php?static=/<?= $current_module->getIdentifier(); ?>/img/back.png" alt="Terug"/><p class="back"><a href="/admin/index.php" title="Terug naar templates overzicht">Terug</a></p>
		</fieldset>
	<?php else: ?>
		<?php foreach($scope_dao->getScopes() as $scope): ?>
			<fieldset class="admin_fieldset">			
				<div class="fieldset-title"><?= $scope->getName(); ?></div>
				
				<?php $templates = $template_dao->getTemplatesByScope($scope); ?>
				<?php if (count($templates) > 0): ?>
				<table class="listing" width="900px" cellpadding="5" cellspacing="0" border="0">
					<colgroup width="200px"></colgroup>
					<colgroup width="200px"></colgroup>
					<colgroup width="150px"></colgroup>
					<colgroup width="100px"></colgroup>
					<thead>
						<tr class="header">
							<th>Naam</th>
							<th>Bestandsnaam</th>
							<th class="file_column">Bestand gevonden</th>
							<th class="delete_column">Verwijder</th>
						</tr>
					</thead>
					<tbody>						
						<?php foreach ($template_dao->getTemplatesByScope($scope) as $template): ?>
						<tr>
							<td><a href="/admin/index.php?template=<?= $template->getId(); ?>" title="<?= $template->getName(); ?>"><?= $template->getName(); ?></a></td>
							<td><?= $template->getFileName(); ?></td>
							<td class="file_column">
								<?php if (file_exists($template_dir . "/" . $template->getFileName()) && $template->getFileName() != ""): ?>
									<img src="/admin/static.php?static=/<?= $current_module->getIdentifier(); ?>/img/check.gif" alt="Bestand aanwezig" />
								<?php else: ?>
									<img src="/admin/static.php?static=/<?= $current_module->getIdentifier(); ?>/img/delete.png" alt="Bestand ontbreekt" />
								<?php endif; ?>
							</td>
							<td class="delete_column">
								<?php
									FormRenderer::renderSingleValuedCheckbox('template_' . $template->getId() . '_delete', '', 0, false, '');
								?>
							</td>
						</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
				<?php else: ?>
				<?php 
					include_once "libraries/renderers/main_renderer.php";
					
					MainRenderer::renderInformationMessage("Geen templates gevonden. Klik op 'toevoegen' om een nieuw template te maken.");
				?>
				<?php endif; ?>
			</fieldset>
		<?php endforeach; ?>
	<?php endif; ?>
</form>