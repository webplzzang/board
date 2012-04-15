<?php
/**
 *
 * PHP versions 4 and 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2011, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2011, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       cake
 * @subpackage    cake.cake.console.libs.templates.skel.views.layouts
 * @since         CakePHP(tm) v 0.10.0.1076
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
?>
<!DOCTYPE html>
<head>
	<?php echo $this->Html->charset(); ?>
	<title>
		<?php echo $title_for_layout; ?>
	</title>
	<?php
//		echo $this->Html->meta('favicon.ico','/baseballid/img/favicon.ico',array('type' =>'icon'));
		echo $this->Html->css('/board/css/sample.css');
//		echo $this->Html->script(array('jquery','base/common','base/calender','admin/etc','admin/form','admin/ajax','admin/description'));
		echo $scripts_for_layout;
	?>
</head>
<body>
<center>
<table width='500' cellpadding='0' cellspacing='0'>
	<tr>
		<td style='padding:10'>
<?php echo $content_for_layout; ?>
		</td>
	</tr>
</table>
</center>
</body>
<?php echo $this->element('sql_dump'); ?>
</html>