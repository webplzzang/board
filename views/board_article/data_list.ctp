
			<?=$this->Html->script(array('/board/js/board.common'))?>

			<table width='100%' cellpadding='0' cellspacing='0' border='0'>
				<tr>
					<td align='center'><?=$db['board_info']['column']['title']?> 목록</td>
				</tr>
			</table>


			<table width='100%' cellpadding='0' cellspacing='0' border='0'>
				<tr>
					<td align='right' style='padding-top:10px'>
						<?if(ValueIsCheck($parameter['view_type'],"gallery","equal") == true){?>
						<a href="<?=HOSTHOME."/board/board_article/data_list/page:".(IsEmpty($parameter['data_page'])==true?1:$parameter['data_page'])."?view_type=gallery&".ViewParameterParsing($parameter,"board_info_id,board_category_id")?>"><b>[앨범형]</b></a>
						<a href="<?=HOSTHOME."/board/board_article/data_list/page:".(IsEmpty($parameter['data_page'])==true?1:$parameter['data_page'])."?view_type=board&".ViewParameterParsing($parameter,"board_info_id,board_category_id")?>">[게시판형]</a>
						<?}?>
						<?if(ValueIsCheck($parameter['view_type'],"board","equal") == true || IsEmpty($parameter['view_type']) == true){?>
						<a href="<?=HOSTHOME."/board/board_article/data_list/page:".(IsEmpty($parameter['data_page'])==true?1:$parameter['data_page'])."?view_type=gallery&".ViewParameterParsing($parameter,"board_info_id,board_category_id")?>">[앨범형]</a>
						<a href="<?=HOSTHOME."/board/board_article/data_list/page:".(IsEmpty($parameter['data_page'])==true?1:$parameter['data_page'])."?view_type=board&".ViewParameterParsing($parameter,"board_info_id,board_category_id")?>"><b>[게시판형]</b></a>
						<?}?>
					</td>
				</tr>
			</table>



			<form name='GoArtcleForm' id='GoArtcleForm' method='post' action='<?=HOSTHOME?>/board/board_article/data_form'>
			<input type='hidden' name='data[BoardArticle][board_info_id]' id='board_info_id' value="<?=$parameter['board_info_id']?>">
			<input type='hidden' name='data[BoardArticle][board_category_id]' id='board_category_id' value="<?=ValueNullCheck($parameter['board_category_id'])?>">
			<input type='hidden' name='data[BoardArticle][data_page]' value='<?php echo ValueNullCheck($parameter['data_page'])?>'>
			<input type='hidden' name='data[BoardArticle][page_per_data_ea]' value='<?php echo ValueNullCheck($parameter['page_per_data_ea'])?>'>
			<input type='hidden' name='data[BoardArticle][search_method]' value='<?php echo ValueNullCheck($parameter['search_method'])?>'>
			<input type='hidden' name='data[BoardArticle][search_keyword]' value='<?php echo ValueNullCheck($parameter['search_keyword'])?>'>
			<input type='hidden' name='data[BoardArticle][view_type]' value='<?php echo ValueNullCheck($parameter['view_type'])?>'>
			</form>
			<form name='DataFormDelete' id='DataFormDelete' method='post' action='<?=HOSTHOME?>/board/board_article/data_delete'>
			<input type='hidden' name='data[BoardArticle][id]' id='id'>
			<input type='hidden' name='data[BoardArticle][board_info_id]' id='board_info_id' value="<?=$parameter['board_info_id']?>">
			<input type='hidden' name='data[BoardArticle][board_category_id]' id='board_category_id' value="<?=ValueNullCheck($parameter['board_category_id'])?>">
			</form>
			<form name='SearchForm' id='SearchForm' action="<?=HOSTHOME?>/board/board_article/data_list" method="POST">
			<input type='hidden' name='data[BoardArticle][view_type]' value='<?php echo ValueNullCheck($parameter['view_type'])?>'>
			<input type='hidden' name='data[BoardArticle][board_info_id]' value='<?php echo ValueNullCheck($parameter['board_info_id'])?>'>
			<input type='hidden' name='data[BoardArticle][data_page]' value='<?php echo ValueNullCheck($parameter['data_page'])?>'>
			<input type='hidden' name='data[BoardArticle][page_per_data_ea]' value='<?php echo ValueNullCheck($parameter['page_per_data_ea'])?>'>

			<?if(IsEmpty($db['board_category']) == false){?>
			<table width='100%' cellpadding='0' cellspacing='0' border='0'>
				<tr>
					<td align='right'>
						카테고리 : 
						<select name='data[BoardArticle][board_category_id]' onchange="_$('SearchForm').submit();">
						<option value="no"<?if(ValueIsCheck($parameter['board_category_id'],"no","equal") == true || IsEmpty($parameter['board_category_id']) == true){?> selected<?}?>>전체</option>
						<?
						for($i=0;$i<sizeof($db['board_category']);$i++)
						{
							$data = $db['board_category'][$i];
						?>
							<option value="<?=$data['column']['id']?>"<?if(ValueIsCheck($parameter['board_category_id'],$data['column']['id'],"equal") == true){?> selected<?}?>><?=$data['column']['title']?></option>
						<?
						}
						?>
						</select>

						검색종류 : 
						<select name='data[BoardArticle][search_method]'>
							<option value='no'<?if(ValueIsCheck($parameter['search_method'],"no","equal") == true || IsEmpty($parameter['search_method']) == true){?> selected<?}?>>전체</option>
							<option value="title"<?if(ValueIsCheck($parameter['search_method'],"title","equal") == true){?> selected<?}?>>제목</option>
							<option value="content"<?if(ValueIsCheck($parameter['search_method'],"content","equal") == true){?> selected<?}?>>내용</option>
							<option value="all"<?if(ValueIsCheck($parameter['search_method'],"both","equal") == true){?> selected<?}?>>제목 + 내용</option>
						</select>

						검색어 : 
						<input type='text' name='data[BoardArticle][search_keyword]' size="20" value="<?php echo ValueNullCheck($parameter['search_keyword'])?>">

						<input type='submit' value='검색'>
					</td>
				</tr>
			</table>
			<?}?>

			</form>
			<form name='DataForm' id='DataForm' method='post' action='<?=HOSTHOME?>/board/board_article/data_fast_process'>
			<input type='hidden' name='data[BoardArticle][prkey_value]' id='prkey_value'>
			<input type='hidden' name='data[BoardArticle][board_info_id]' id='board_info_id' value="<?=$parameter['board_info_id']?>">
			<input type='hidden' name='data[BoardArticle][board_category_id]' id='board_category_id' value="<?=ValueNullCheck($parameter['board_category_id'])?>">
			<input type='hidden' name='data[BoardArticle][data_page]' value='<?php echo ValueNullCheck($parameter['data_page'])?>'>
			<input type='hidden' name='data[BoardArticle][page_per_data_ea]' value='<?php echo ValueNullCheck($parameter['page_per_data_ea'])?>'>
			<input type='hidden' name='data[BoardArticle][search_method]' value='<?php echo ValueNullCheck($parameter['search_method'])?>'>
			<input type='hidden' name='data[BoardArticle][search_keyword]' value='<?php echo ValueNullCheck($parameter['search_keyword'])?>'>
			<input type='hidden' name='data[BoardArticle][view_type]' value='<?php echo ValueNullCheck($parameter['view_type'])?>'>
			<table width='100%' border='1'>
			<?if($_board_user['admin'] == "YES"){?><col width="30"><?}?><col width="50"><col><col width="100"><col width="100"><col width="150"><col width="100">
			<tr>
				<?if($_board_user['admin'] == "YES"){?><td><input type='checkbox' onclick="allCheck('DataForm','bd_index_check',this);"></td><?}?>
				<td>번호</td>
				<td>제목</td>
				<td>작성자</td>
				<td>뷰수</td>
				<td>등록일</td>
				<td>수정/삭제</td>
			</tr>

				<?
				if(sizeof($db['data']['result']) > 0)
				{
					for($i=0;$i<sizeof($db['data']['result']);$i++)
					{
						$data = $db['data']['result'][$i];
				?>
				<tr>
					<?if($_board_user['admin'] == "YES"){?><td><input type="checkbox" name='id_check' value="<?=$data['column']['id']?>"></td><?}?>
					<td><?=$data['virtual_num']?></td>
					<td align='left'>
						<a href="<?=HOSTHOME?>/board/board_article/data_view/<?=$data['column']['id']?>/page:<?=(IsEmpty($parameter['data_page'])==true?1:$parameter['data_page'])?>?<?=ViewParameterParsing($parameter,"board_info_id,board_category_id")?>"<?if($data['column']['subject_bold'] == "Y"){echo " style='font-weight:bold'";}?>>
						<?=$data['space']." ".$data['re']?>
						<?if(IsEmpty($data['column']['board_category_id']) == false && ValueIsCheck($data['column']['board_category_id'],"0","not") == true) echo "[".$data['category']['column']['title']."]";?>
						<?=$data['column']['title']?>
						<?if($data['column']['ment_count'] > 0) echo "(".$data['column']['ment_count'].")";?>
						<?if(sizeof($data['file']) > 0) echo "[파일]";?>
						<?if($data['column']['secret'] == "Y") echo "[비밀글]";?>
						</a>
					</td>
					<td><?=ValueNullCheck($data['column']['owner_name'])?></td>
					<td><?=$data['column']['view_count']?></td>
					<td><?=date("Y-m-d",$data['column']['write'])?></td>
					<td>
						<a href="javascript:_$('GoArtcleForm').action=_$('GoArtcleForm').action+'/<?=$data['column']['id']?>';_$('GoArtcleForm').submit();">수정</a><br />
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
					<td colspan='<?if($_board_user['admin'] == "YES"){?>8<?}else{?>7<?}?>' height='200'><?=Configure::read('description.no_data')?></td></td>
				</tr>
				<?
				}
				?>

			</table>
			</form>


			<table width='100%' cellpadding='0' cellspacing='0' border='0'>
				<tr>
					<td height='10'>
						<?
						if($db['data']['search_total_page'] > 1)
						{
							$paginator->options = array('url'=>array('?' =>ViewParameterParsing($parameter,"board_info_id,board_category_id,view_type")));
							echo $this->Paginator->prev('« Previous', null, null, array('class' => 'disabled'))."&nbsp;";
							echo $this->Paginator->numbers()."&nbsp;";
							echo $this->Paginator->next('Next »', null, null, array('class' => 'disabled'));
							//echo $this->Paginator->counter();
						}
						?>
					</td>
				</tr>
			</table>


			<table width='100%' cellpadding='0' cellspacing='0' border='0'>
				<tr>
					<td>
						<?if($_board_user['admin'] == "YES"){?><a href="javascript:dataFastProc('DataForm','id_check');">빠른삭제</a><?}?>
						<a href="javascript:_$('GoArtcleForm').submit();">게시글 등록</a>
					</td>
				</tr>
			</table>