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
		echo $this->Html->script(array('jquery'));
//		echo $this->Html->script(array('jquery','base/common','base/calender','admin/etc','admin/form','admin/ajax','admin/description'));
		echo $scripts_for_layout;
	?>
</head>
<body>
<center>
<table width='800' cellpadding='0' cellspacing='0'>
	<tr>
		<td style='padding:10' align='right'>
			<?
			if($_board_user['check'] == "YES")
			{
			?>
			<a href="<?=HOSTHOME?>/users/logout">로그아웃</a>
			/
			<a href="<?=HOSTHOME?>/board/board_info/data_list">게시판목록</a>
			<?
			}
			else
			{
			?>
			<a href="<?=HOSTHOME?>/users/login">로그인</a>
			<?
			}
			?>
		</td>
	</tr>

	<?if($controller == "board_article"){?>
	<tr>
		<td style='padding-top:10px;padding-bottom:20px;' align='right'>
			<table width='100%' cellpadding='0' cellspacing='0' border='0'>
			<form name='TotalSearchForm' id='TotalSearchForm' action="<?=HOSTHOME?>/board/board_article/total_search_data_list" method="POST">
				<tr>
					<td align='center'>통합검색</td>
				</tr>
				<tr>
					<td>
						검색어 : 
						<input type='text' name='data[BoardArticle][total_search_keyword]' id='total_search_keyword' size="20" value="">
						<input type='button' onclick="if(_$('TotalSearchForm').total_search_keyword.value == ''){alert('검색어를 입력하세요!');}else{_$('TotalSearchForm').submit();}" value='검색'>
					</td>
				</tr>
			</form>
			</table>
		</td>
	</tr>
	<?}?>

	<tr>
		<td style='padding:10px'>
<?php echo $content_for_layout; ?>
		</td>
	</tr>
</table>
</center>
</body>
<?php echo $this->element('sql_dump'); ?>
</html>