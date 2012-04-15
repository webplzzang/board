
			<?=$this->Html->script(array('/board/js/board.common'))?>

			<table width='100%' cellpadding='0' cellspacing='0' border='0'>
				<tr>
					<td align='center'>게시판 목록</td>
				</tr>
			</table>


			<form name='GoArticleForm' id='GoArticleForm' method='post' action='<?=HOSTHOME?>/board/board_article/data_list'>
			<input type='hidden' name='data[BoardArticle][board_info_id]' id='board_info_id'>
			</form>
			<form name='GoCategoryForm' id='GoCategoryForm' method='post' action='<?=HOSTHOME?>/board/board_category/data_list'>
			<input type='hidden' name='data[BoardCategory][board_info_id]' id='board_info_id'>
			</form>
			<form name='DataFormDelete' id='DataFormDelete' method='post' action='<?=HOSTHOME?>/board/board_info/data_delete'>
			<input type='hidden' name='data[BoardInfo][id]' id='id'>
			</form>
			<form name='DataForm' id='DataForm' method='post' action='<?=HOSTHOME?>/board/board_info/data_fast_process'>
			<input type='hidden' name='data[BoardInfo][prkey_value]' id='prkey_value'>
			<table width='100%' border='1'>
			<col width="30"><col width="50"><col width="100"><col><col width="100"><col width="150"><col width="150"><col width="100">
			<tr>
				<td><input type='checkbox' onclick="allCheck('DataForm','id_check',this);"></td>
				<td>번호</td>
				<td>게시판종류</td>
				<td>게시판이름</td>
				<td>게시물수</td>
				<td>게시글 마지막 등록일</td>
				<td>등록일</td>
				<td>목록/수정/삭제</td>
			</tr>

				<?
				if(sizeof($db['data']['result']) > 0)
				{
					for($i=0;$i<sizeof($db['data']['result']);$i++)
					{
						$data = $db['data']['result'][$i];
				?>
				<tr>
					<td><input type="checkbox" name='id_check' value="<?=$data['column']['id']?>"></td>
					<td><?=$data['virtual_num']?></td>
					<td><?=$data['type']?></td>
					<td><?=$data['column']['title']?></td>
					<td><?=$data['column']['article_count']?></td>
					<td><?=$data['column']['last_article_date']?></td>
					<td><?=$data['column']['created']?></td>
					<td>
						<a href="javascript:_$('GoArticleForm').board_info_id.value='<?=$data['column']['id']?>';_$('GoArticleForm').submit();">게시글목록</a><br />
						<a href="javascript:_$('GoCategoryForm').board_info_id.value='<?=$data['column']['id']?>';_$('GoCategoryForm').submit();">카테고리목록</a><br />
						<a href="<?=HOSTHOME?>/board/board_info/data_form/<?=$data['column']['id']?>">수정</a><br />
						<a onclick="var chk;chk=confirm('<?=Configure::read('description.confirm_delete')?>');if(chk){_$('DataFormDelete').id.value='<?=$data['column']['id']?>';_$('DataFormDelete').id.value='<?=$data['column']['id']?>';_$('DataFormDelete').submit();}" style='cursor:pointer'>삭제</a><br />
					</td>
				</tr>
				<?
						unset($data);
					}
				}
				else
				{
				?>
				<tr>
					<td colspan='8' height='200'><?=Configure::read('description.no_data')?></td></td>
				</tr>
				<?
				}
				?>

			</table>
			</form>


			<table width='100%' cellpadding='0' cellspacing='0' border='0'>
				<tr>
					<td height='10'></td>
				</tr>
			</table>


			<table width='100%' cellpadding='0' cellspacing='0' border='0'>
				<tr>
					<td>
						<a href="javascript:dataFastProc('DataForm','id_check');">빠른삭제</a>
						<a href='<?=HOSTHOME?>/board/board_info/data_form'>게시판등록</a>
					</td>
				</tr>
			</table>