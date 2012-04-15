<?=$tpl['str']?>


<?
if($check == false)
{
	showMsg(Configure::read("description.not_confirm"));
	?>
	<script>
	_parent$("CommentDataConfirmForm<?=$parameter['id']?>").confirm_password.value = "";
	</script>
	<?
}
else
{
	if($parameter['mode'] == "CommentModify")
	{
		?>
		<script>
		parent.displayViewChange("CommentModify<?=$parameter['id']?>", "CommentView<?=$parameter['id']?>,CommentReply<?=$parameter['id']?>,CommentConfirm<?=$parameter['id']?>",'fix');
		</script>
		<?
	}
	else if($parameter['mode'] == "CommentDelete")
	{
		?>
		<script>
		var chk;chk=confirm('<?=Configure::read('description.confirm_delete')?>');if(chk){_parent$('CommentDataFormDelete').id.value='<?=$parameter['id']?>';_parent$('CommentDataFormDelete').id.value='<?=$parameter['id']?>';_parent$('CommentDataFormDelete').submit();}
		</script>
		<?
	}
}
?>