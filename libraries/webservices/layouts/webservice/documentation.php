<?php
/**
 * @package     Redcore.Webservice
 * @subpackage  Layouts
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die;

$view = !empty($displayData['view']) ? $displayData['view'] : null;
$xml = !empty($displayData['options']['xml']) ? $displayData['options']['xml'] : array();
$soapEnabled = $displayData['options']['soapEnabled'];
$print = $displayData['options']['print'];
$date   = new JDate;

$halLink = JApiHalHelper::buildWebserviceFullUrl($view->client, $view->webserviceName, $view->webserviceVersion, 'hal');
$docsLink = JApiHalHelper::buildWebserviceFullUrl($view->client, $view->webserviceName, $view->webserviceVersion, 'hal', 'doc');

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta name="viewport" content="width=device-width, initial-scale=1\" />
	<link rel="stylesheet" href="http://netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css" />
<?php
	if ($print) :
?>
	<script type="text/javascript">
		function printWindow() {
			window.print();
			window.close();
		};
	</script>
<?php
	endif;
?>
</head>
<body<?php if ($print) : ?> onload="printWindow()"<?php endif; ?>>
<div class="container-fluid">
	<?php if (empty($xml)) : ?>
		<h1><?php echo JText::_('LIB_WEBSERVICES_API_HAL_WEBSERVICE_DOCUMENTATION_NONE'); ?></h1>
	<?php else : ?>
		<h1><?php echo $xml->name; ?> (<?php echo JText::_('JVERSION'); ?> <?php echo $xml->config->version; ?>)</h1>
		<div class="well">
			<?php if (isset($xml->author)) : ?>
				<strong><?php echo JText::_('JAUTHOR'); ?></strong>: <?php echo (string) $xml->author; ?><br />
			<?php endif; ?>
			<?php if (isset($xml->copyright)) : ?>
				<strong><?php echo JText::_('LIB_WEBSERVICES_COPYRIGHT'); ?></strong>: <?php echo (string) $xml->copyright; ?><br />
			<?php endif; ?>
			<strong><?php echo JText::_('LIB_WEBSERVICES_API_HAL_WEBSERVICE_DOCUMENTATION_GENERATED'); ?></strong>: <?php echo $date->toRFC822(); ?><br />
			<strong><?php echo JText::_('LIB_WEBSERVICES_API_HAL_WEBSERVICE_DOCUMENTATION_SUPPORTED_FORMATS'); ?></strong>:
			json (<?php echo JText::_('JDEFAULT'); ?>), xml<br />
			<strong>
				<?php echo JText::_('LIB_WEBSERVICES_API_HAL_WEBSERVICE_DOCUMENTATION_CLIENT'); ?>:
			</strong>
			<?php echo ucfirst($view->client); ?><br />
			<strong>
				<?php echo JText::_('LIB_WEBSERVICES_API_HAL_WEBSERVICE_DOCUMENTATION_ACCESS_OPTION'); ?>:
			</strong>
			<?php echo $xml->config->name; ?> (com_<?php echo $xml->config->name; ?>)<br />
			<strong><?php echo JText::_('LIB_WEBSERVICES_API_HAL_WEBSERVICE_DOCUMENTATION_ACCESS_URL'); ?></strong>:
			<small>
				<a href="<?php echo $halLink ?>">
					<?php echo $halLink ?>
				</a>
			</small>
			<br />
			<?php
				if ($soapEnabled) :
					$wsdlLink = JApiHalHelper::buildWebserviceFullUrl($view->client, $view->webserviceName, $view->webserviceVersion, 'soap') . '&wsdl';
			?>
				<strong><?php echo JText::_('LIB_WEBSERVICES_API_HAL_WEBSERVICE_DOCUMENTATION_WSDL_ACCESS_URL'); ?></strong>:
				<small>
					<a href="<?php echo $wsdlLink ?>">
						<?php echo $wsdlLink ?>
					</a>
				</small>
				<br />
			<?php endif; ?>
			<strong><?php echo JText::_('LIB_WEBSERVICES_API_HAL_WEBSERVICE_DOCUMENTATION_DOCUMENTATION_URL'); ?></strong>:
			<small>
				<a href="<?php echo $docsLink ?>">
					<?php echo $docsLink ?>
				</a>
			</small>
		</div>
			<?php if (isset($xml->description)) : ?>
			<div class="well">
				<h4><?php echo JText::_('JGLOBAL_DESCRIPTION'); ?></h4>
				<p><?php echo (string) $xml->description; ?></p>
			</div>
			<?php endif; ?>

		<h2><?php echo JText::_('LIB_WEBSERVICES_API_HAL_WEBSERVICE_DOCUMENTATION_ALLOWED_OPERATIONS'); ?></h2>
		<p>
			<?php echo JText::_('LIB_WEBSERVICES_API_HAL_WEBSERVICE_DOCUMENTATION_ALLOWED_OPERATIONS_DESC'); ?>
		</p>
		<?php foreach ($xml->operations as $operations) : ?>
			<?php foreach ($operations as $operationName => $operation) : ?>
					<?php if ($operationName == 'documentation') :
						continue;
					elseif ($operationName == 'read') :
						if (isset($xml->operations->read->list)) : ?>
							<a name="<?php echo $operationName . 'list'; ?>"></a>
							<?php echo JLayoutHelper::render(
								'webservice.documentationoperation',
								array(
									'view' => $view,
									'options' => array (
										'xml' => $xml,
										'operationXml' => $operation->list,
										'operationName' => $operationName . ' ' . 'list',
										'soapEnabled' => $soapEnabled,
									)
								),
								JPATH_WEBSERVICES . '/layouts'
							);?>
							<br />
						<?php endif;

						if (isset($xml->operations->read->item)) : ?>
							<a name="<?php echo $operationName . 'item'; ?>"></a>
							<?php echo JLayoutHelper::render(
								'webservice.documentationoperation',
								array(
									'view' => $view,
									'options' => array (
										'xml' => $xml,
										'operationXml' => $operation->item,
										'operationName' => $operationName . ' ' . 'item',
										'soapEnabled' => $soapEnabled,
									)
								),
								JPATH_WEBSERVICES . '/layouts'
							);?>
							<br />
						<?php endif;
					elseif ($operationName == 'task') :
						foreach ($operation as $taskName => $task) : ?>
							<a name="<?php echo $operationName . $taskName; ?>"></a>
							<?php echo JLayoutHelper::render(
								'webservice.documentationoperation',
								array(
									'view' => $view,
									'options' => array (
										'xml' => $xml,
										'operationXml'  => $task,
										'operationName' => $operationName . ' ' . $taskName,
										'taskName'      => $taskName,
										'soapEnabled' => $soapEnabled,
									)
								),
								JPATH_WEBSERVICES . '/layouts'
							);?>
							<br />
						<?php endforeach;
					else : ?>
						<a name="<?php echo $operationName; ?>"></a>
							<?php echo JLayoutHelper::render(
								'webservice.documentationoperation',
								array(
									'view' => $view,
									'options' => array (
										'xml' => $xml,
										'operationXml' => $operation,
										'operationName' => $operationName,
										'soapEnabled' => $soapEnabled,
									)
								),
								JPATH_WEBSERVICES . '/layouts'
							);?>
						<br />
					<?php endif; ?>
			<?php endforeach; ?>
		<?php endforeach; ?>
	<?php endif; ?>
</div>
</body>
</html>
