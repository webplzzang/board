<script>
<?if($tpl['check'] == true){?>
opener.document.getElementById("contentsByAdd<?=$parameter['pt']?>").innerHTML = "<?=$parameter['add_original']?>";
opener.document.getElementById("file_add_root<?=$parameter['pt']?>").value = "<?=$parameter['root']?>";
opener.document.getElementById("file_add_path<?=$parameter['pt']?>").value = "<?=$parameter['add_path']?>";
opener.document.getElementById("file_add_convert<?=$parameter['pt']?>").value = "<?=$parameter['add_convert']?>";
opener.document.getElementById("file_add_original<?=$parameter['pt']?>").value = "<?=$parameter['add_original']?>";
this.close();
<?}else{?>
alert("용량을 초과하여 업로드 할 수 없습니다.");
this.close();
<?}?>
</script>