

          <div class="">
           <input name="YEAR_C[<?php echo $val;?>]" id="YEAR_C_<?php echo $val;?>" type="hidden" size="5" class="form-control number_format" value="<?php echo ($val); ?>">
            <table width="22%" class="table table-bordered table-striped table-hover table-condensed">
              <thead>
                <tr class="bgHead">
                  <th width="1%" nowrap rowspan="2"><div align="center"><strong>ลำดับ</strong></div></th>
                  <th width="1%" nowrap rowspan="2"><div align="center"><strong>ชื่อผลลัพธ์</strong></div></th>
                  <th width="1%" nowrap rowspan="2"><div align="center"><strong>ผลการส่งเสริม</strong></div></th>
                  <th width="1%" nowrap rowspan="2"><div align="center"><strong>เป้าหมาย</strong></div></th>
				  <th width="1%" nowrap rowspan="2"><div align="center"><strong>ผลสะสม</strong></div></th>
                  <th width="1%" nowrap rowspan="2"><div align="center"><strong>หน่วยนับ</strong></div></th>
                  <th width="1%" nowrap rowspan="2"><div align="center"><strong></strong></div></th>
                  <th width="1%" nowrap><div align="center"><strong>ผลที่ได้ของปี&nbsp;<?php echo $_SESSION['year_round']; ?></strong></div></th>
                  <th width="" nowrap><div align="center"><strong>รายละเอียดการดำเนินงาน/ปัญหาและอุปสรรค</strong></div></th>
                </tr>
                <tr class="bgHead">
                 <th width="1%" nowrap colspan="2"><div align="center"><strong><?php echo $month[$smh*1].$syh;?></strong></div></th>
                </tr>
				</thead>
				<tbody>
				<?php
				
				
				
			   $sql_result_copy = "SELECT 	a.PRJP_RESULT_ID,
				a.PRJP_ID,
				a.TYPE_RES_ID,
				a.PRJP_RESULT_NAME,
				a.GOAL_VALUE,
				a.UNIT_RES_NAME,
				b.UNIT_ID,
				(select TYPE_RES_NAME FROM setup_type_result WHERE setup_type_result.TYPE_RES_ID = a.TYPE_RES_ID)as TYPE_RES_NAME,
				(select UNIT_NAME_TH FROM setup_unit WHERE setup_unit.UNIT_ID = a.UNIT_ID)as UNIT_NAME_TH,
				c.PRJP_RESULT_OLD_ID
		  		FROM prjp_result a 
				JOIN setup_type_result b ON b.TYPE_RES_ID = a.TYPE_RES_ID
				LEFT JOIN result_join c on c.PRJP_ID = a.PRJP_ID AND a.PRJP_RESULT_ID = c.PRJP_RESULT_ID
				WHERE 1=1 AND a.PRJP_ID = '".$PRJP_ID."' 
				order by a.PRJP_RESULT_ID
				";
				$query_result_copy = $db->query($sql_result_copy);
				$num_rows_result_copy = $db->db_num_rows($query_result_copy);
				$num_rows_result_copy;
				
                if($num_rows_result_copy > 0){
                $i_result_copy=1;
				//$query_result_copy_desc = $db->query($sql_result_copy);
				while($rec_result_copy = $db->db_fetch_array($query_result_copy)){
					?>
					<tr bgcolor="#FFFFFF">
					  <td align="center" ><?php echo $i_result_copy; ?>. 
					   <input type="hidden" id="PRJP_RESULT_ID_<?php echo $val;?>" name="PRJP_RESULT_ID[<?php echo $val;?>][<?php echo $rec_result_copy['PRJP_RESULT_ID']; ?>]" value="<?php echo $rec_result_copy['PRJP_RESULT_ID']; ?>">
					  <input type="hidden" id="PRJP_RESULT_ID_DEL" name="PRJP_RESULT_ID_DEL[]" value="<?php echo $rec_result_copy['PRJP_RESULT_ID']; ?>">
					  </td>
						<td align="left"><textarea rows="9" cols="15" style="border:none;background: transparent;resize: none; width: auto;" disabled><?php echo text($rec_result_copy['PRJP_RESULT_NAME']);?></textarea>
						<input type="hidden" id="PRJP_RESULT_NAME[]" name="PRJP_RESULT_NAME[<?php echo $rec_result_copy['PRJP_RESULT_ID']; ?>]" value="<?php echo text($rec_result_copy['PRJP_RESULT_NAME']); ?>">
						</td>
						<td align="left"><?php echo text($rec_result_copy['TYPE_RES_NAME']); ?>
						  <input type="hidden" id="TYPE_RES_ID[]" name="TYPE_RES_ID[<?php echo $rec_result_copy['PRJP_RESULT_ID']; ?>]" value="<?php echo $rec_result_copy['TYPE_RES_ID']; ?>">
						</td>
						<td align="left" ><?php echo number_format($rec_result_copy['GOAL_VALUE'], 2);?></td>
						<td align="center"><?php echo number_format($arr_pval_s_include_result[$rec_result_copy['PRJP_RESULT_ID']]+$arr_pval_son[$rec_result_copy['PRJP_RESULT_OLD_ID']], 2); ?></td>
						<td align="left" ><?php if($rec_result_copy['TYPE_RES_ID']!='9999'){ echo text($rec_result_copy['UNIT_NAME_TH']);}else{echo text($rec_result_copy['UNIT_RES_NAME']);}?>
						<input type="hidden" id="UNIT_ID[]" name="UNIT_ID[<?php echo $rec_result_copy['PRJP_RESULT_ID']; ?>]" value="<?php echo $rec_result_copy['UNIT_ID']; ?>">
						</td>
						<td align="center">ผล</td>
						<td align="center">
						 <input name="YEAR[<?php echo $val;?>][<?php echo $rec_result_copy['PRJP_RESULT_ID']; ?>]" id="YEAR_<?php echo $val;?>" type="hidden" size="5" class="form-control number_format" value="<?php echo ($syh); ?>">
						<input name="PLAN_VALUE_INCLUDE[<?php echo $val;?>][<?php echo $rec_result_copy['PRJP_RESULT_ID']; ?>]" id="PLAN_VALUE_INCLUDE_<?php echo $val;?>" type="text" size="5" class="form-control number_format" value="<?php echo number_format($arr_pval_include_result[$rec_result_copy['PRJP_RESULT_ID']][$val],2); ?>" onBlur="NumberFormat(this,2);" style="text-align:right" <?php echo $distxt; ?>></td>
						 <td align="left">
						 รายละเอียดการดำเนินงาน :<br>
						 <textarea name="DESC_NAME_INCLUDE[<?php echo $val;?>][<?php echo $rec_result_copy['PRJP_RESULT_ID']; ?>]" <?php echo $distxt; ?> id="DESC_NAME_INCLUDE_<?php echo $val;?>" cols="50" rows="3"><?php echo text($arr_desc_include_restule[$rec_result_copy['PRJP_RESULT_ID']][$val]); ?></textarea><br>
						 ปัญหาและอุปสรรค :<br>
						 <textarea name="RICK_NAME_INCLUDE[<?php echo $val;?>][<?php echo $rec_result_copy['PRJP_RESULT_ID']; ?>]" <?php echo $distxt; ?> id="RICK_NAME_INCLUDE_<?php echo $val;?>" cols="50" rows="3"><?php echo text($arr_rick_include_result[$rec_result_copy['PRJP_RESULT_ID']][$val]); ?></textarea><br />
						 แนวทางแก้ไข :<br>
						 <textarea name="SOLUTION_NAME_INCLUDE[<?php echo $val;?>][<?php echo $rec_result_copy['PRJP_RESULT_ID']; ?>]" <?php echo $distxt; ?> id="SOLUTION_NAME_INCLUDE_<?php echo $val;?>" cols="50" rows="3"><?php echo text($arr_solution_include_result[$rec_result_copy['PRJP_RESULT_ID']][$val]); ?></textarea>
						 </td>
					</tr>
					<?php 
						$i_result_copy++;
					} 
				}else{
                echo "<tr><td align=\"center\" colspan=\"14\">ไม่พบข้อมูล</td></tr>";
				}
				?>
              </tbody>
            </table>
          </div>
