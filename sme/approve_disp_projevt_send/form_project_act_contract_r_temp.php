<?php
/* @session_start();
$path = "../../";
include($path."include/config_header_top.php");
$path_a="../../fileupload/file_contract/";
$link = "r=home&menu_id=".$menu_id."&menu_sub_id=".$menu_sub_id;  /// for mobile
$paramlink = url2code($link);
$sub_menu = ""; */

session_start();
$path = "../../";
$path_a = "../../fileupload/file_prjp/";
include($path . "include/config_header_top.php");
$link = "r=home&menu_id=" . $menu_id . "&menu_sub_id=" . $menu_sub_id;  /// for mobile
$paramlink = url2code($link);
$sub_menu = "";

$ACT = '15';
if ($_POST['PRJP_ID'] != '') {
	$PRJP_ID = $_POST['PRJP_ID'];
} else {
	$PRJP_ID = $PRJP_ID;
}
$sql_head = "SELECT PRJP_ID,PRJP_CODE,PRJP_NAME,PRJP_CON_ID FROM prjp_project WHERE PRJP_ID = '" . $PRJP_ID . "'";
$query_head = $db->query($sql_head);
$rec_head = $db->db_fetch_array($query_head);

$filter == "";
if ($_POST['s_round_year_bud'] != "") {
	$filter .= " AND PRJP_NAME LIKE '%" . $_POST['s_round_year_bud'] . "%' ";
}
$sql = "SELECT a.*, b.PRJP_ACT_CONTRACT_CO_ORG_PARENT_ID, b.PRJP_ACT_CONTRACT_CO_ORG_SECTOR_ID, c.CO_ORG_TYPE_NAME, d.CO_ORG_SECTOR_NAME from prjp_act_contract_temp a 
LEFT JOIN prjp_act_contract_co_org_temp b on a.PRJP_ACT_CONTRACT_ID = b.PRJP_ACT_CONTRACT_ID
LEFT JOIN setup_co_org_type c on b.PRJP_ACT_CONTRACT_CO_ORG_PARENT_ID = c.CO_ORG_TYPE_ID
LEFT JOIN setup_co_org_sector d on b.PRJP_ACT_CONTRACT_CO_ORG_SECTOR_ID = d.CO_ORG_SECTOR_ID
WHERE PRJP_ID = '" . $PRJP_ID . "'";
$query = $db->query($sql);
$num_rows = $db->db_num_rows($query);



// $sql_co_org = "SELECT a.CO_ORG_TYPE_ID, a.CO_ORG_TYPE_NAME, b.PRJP_ACT_CONTRACT_ID FROM setup_co_org_type a 
// LEFT JOIN prjp_act_contract_co_org_temp b on a.CO_ORG_TYPE_ID = b.PRJP_ACT_CONTRACT_CO_ORG_PARENT_ID
// WHERE ACTIVE_STATUS = 1 AND PRJP_ACT_CONTRACT_ID = $idc";
// $query_co_org = $db->query($sql_co_org);
// $rec_co_org = $db->db_fetch_array($query_co_org);
// echo $rec_co_org['CO_ORG_TYPE_ID'];

// $sql_co_sector = "SELECT a.CO_ORG_SECTOR_ID FROM setup_co_org_sector a
// LEFT JOIN prjp_act_contract_co_org_temp b on a.CO_ORG_SECTOR_ID = b.PRJP_ACT_CONTRACT_CO_ORG_SECTOR_ID
// WHERE ACTIVE_STATUS = 1 AND PRJP_ACT_CONTRACT_ID = $idc ";
// $query_co_sector = $db->query($sql_co_sector);
// $rec_co_sector = $db->db_fetch_array($query_co_sector);

?>
<!DOCTYPE html>
<html>

<head>
	<?php include($path . "include/inc_main_top.php"); ?>
	<script src="js/disp_project_act_contract.js?<?php echo rand(); ?>"></script>
	<script type="text/javascript">
		var menu_id = "<?php echo $menu_id; ?>"
		var menu_sub_id = "<?php echo $menu_sub_id; ?>"

		function addData() {
			$('#proc2').val("add_lesson");
			$('#modal_contract .modal-body').load("form_project_act_contract_detail.php?menu_id=" + menu_id + "&menu_sub_id=" + menu_sub_id)
			$('#modal_contract').modal('show');
		}

		function editData(idc, idp) {
			$('#proc2').val("edit_lesson");
			$('#modal_contract .modal-body').load("form_project_act_contract_detail.php?menu_id=" + menu_id + "&menu_sub_id=" + menu_sub_id + "&idc=" + idc)
			$('#modal_contract').modal('show');
		}

		function remove_id(id, tb_id) {
			$('#' + id).remove();
			sum_bdg();
			run_number_tb(tb_id);
		}

		function get_org_sector(e) {
			if (e > 0 && $.trim(e) != "") {
				$.ajax({
					url: "process/disp_project_act_process.php",
					type: "POST",
					data: {
						proc: "get_org_sector",
						CO_ORG_PARENT_ID: e
					},
					success: function(data) {
						$("#CO_ORG_SECTOR").html(data);
						$('select').trigger('liszt:updated');
					}
				});
			} else {
				$("#CO_ORG_SECTOR").html('<option value=""></option>');
			}
		}

		function add_row_lesson() {
			var table = document.getElementById('tb_data_lesson');
			var rowCount = (table.rows.length);
			var row = table.insertRow(rowCount);

			var id_tbc = rowCount + "" + parseInt((Math.random() * 10) + 1);
			table.rows[rowCount].id = id_tbc;
			table.rows[rowCount].style.background = "#FFFFFF";
			var j = 0;

			for (var i = 0; i < 3; i++) {
				table.rows[rowCount].insertCell(i);
			}

			table.rows[rowCount].cells[0].align = "center";
			table.rows[rowCount].cells[1].align = "left";
			table.rows[rowCount].cells[2].align = "center";

			//data 
			var url = "process/disp_project_act_process.php";

			table.rows[rowCount].cells[0].innerHTML = "" + (rowCount);

			$.post(url, {
				proc: "get_lesson",
				id_tbc: id_tbc
			}, function(msg) {
				table.rows[rowCount].cells[1].innerHTML = "" + msg + "";

				$(".PRJP_ACT_CONTRACT_LESSON_DETAIL_DATE" + id_tbc).each(function() { //ปฏิทิน
					var date_for = $(this).attr("for");
					$('span[for=' + date_for + ']').attr('data-date', $('#' + date_for).val());

					$("span[for=" + date_for + "]").datepicker({
						language: "th-th"
					});
					$("span[for=" + date_for + "]").on("changeDate", function(e) { //onchangeDate
						$('#' + date_for).val(e.format('dd/mm/yyyy'));
						$('span[for=' + date_for + ']').datepicker('hide');
					});
					$("#" + date_for).on("keyup", function(e) { //onkeyup
						beginchk(this, e, this.id);
					});
				});
			});

			table.rows[rowCount].cells[2].innerHTML = "<a class=\"btn btn-danger btn-xs\" onClick=\"remove_id(" + id_tbc + ",'tb_data_coor');sum_bdg();\">" + img_del + " ลบ</a> ";

		}

		function delData(idc, idp) {
			if (confirm("ต้องการลบข้อมูลใช่หรือไม่ ?")) {
				$("#proc").val("del_lesson");
				$("#PRJP_ID").val(idp);
				$("#PRJP_ACT_CONTRACT_ID").val(idc);
				$("#frm-search").attr("action", "process/disp_project_act_process.php").submit();
			}
		}

		function chkinput() {
			if (confirm("กรุณายืนยันการบันทึกอีกครั้ง ?")) {
				$("#frm-contract").submit();
			}
		}

		function sum_bdg() {
			var sum = 0;
			$.each($('input[type="text"][name="PRJP_ACT_CONTRACT_LESSON_DETAIL[]"][class*="bdg-detail"]'), function() {
				var this_val = parseFloat($(this).val().split(",").join("")) || 0.00;
				sum += this_val;
			});
			$("#PRJP_ACT_CONTRACT_BDG").val(number_format_txt(sum, 2));
		}

		function chk_old(id) {
			if (id == 0) {
				$("#hide_old").val(1);
			} else if (id == 1) {
				$("#hide_old").val(2);
			} else if (id == 2) {
				$("#hide_old").val(1);
			}
			id = $("#hide_old").val();
			show_old(id);
		}

		function show_old(id) {
			if (id == 1) {
				$("#data_old").show();
				$("#data_old2").show();
				$("#TB_BR").html("<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>");
			} else {
				$("#data_old").hide();
				$("#data_old2").hide();
				$("#TB_BR").html("");
			}
		}

		function textfrm_sel(e) {
			$(".txtfrm").hide();
			$(".txtfrm" + e).show();
		}

		function tab_list(id) {
			$(".tb_v").hide();
			$(".re_act").removeClass("active");

			$("#show_rb_" + id).show();
			$("#tablist_" + id).addClass("active");

		}
	</script>
	<style>
		table#tb_file_prjp2 tr td {
			white-space: nowrap;
		}
	</style>
</head>

<body>
	<div class="container-full">
		<div><?php include($path . "include/header.php"); ?></div>
		<!--<div class="col-xs-12 col-sm-12">
		<ol class="breadcrumb">
			<li><a href="index.php?<?php echo $paramlink; ?>">หน้าแรก</a></li>
			<li><a href="disp_send_project.php?<?php echo url2code("menu_id=" . $menu_id . "&menu_sub_id=" . $menu_sub_id); ?>"><?php echo Showmenu($menu_sub_id); ?></a></li>
			<li><a href="disp_project_act.php?<?php echo url2code("menu_id=" . $menu_id . "&menu_sub_id=" . $menu_sub_id . "&PRJP_ID=" . $PRJP_ID . "&MONEY_PMAIN=" . $MONEY_PMAIN); ?>">กิจกรรม</a></li>
			<li class="active">สัญญาจ้าง</li>
		</ol>
    </div>-->
		<div class="col-xs-12 col-sm-12">
			<ol class="breadcrumb">
				<li><a href="index.php?<?php echo $paramlink; ?>">หน้าแรก</a></li>
				<li><a href="disp_approve_project_temp.php?<?php echo url2code("menu_id=" . $menu_id . "&menu_sub_id=" . $menu_sub_id); ?>">อนุมัติการรายงานผล</a></li>
				<li class="active">รายละเอียดผลตัวชี้วัดของผลผลิต</li>
			</ol>
		</div>
		<div class="col-xs-12 col-sm-12">
			<div class="groupdata">
				<form id="frm-search" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
					<input name="menu_id" type="hidden" id="menu_id" value="<?php echo $menu_id; ?>">
					<input name="menu_sub_id" type="hidden" id="menu_sub_id" value="<?php echo $menu_sub_id; ?>">
					<input name="page" type="hidden" id="page" value="<?php echo $page; ?>">
					<input name="page_size" type="hidden" id="page_size" value="<?php echo $page_size; ?>">
					<input type="hidden" id="PRJP_ID" name="PRJP_ID" value="<?php echo $PRJP_ID; ?>">
					<input type="hidden" id="PRJP_ACT_CONTRACT_ID" name="PRJP_ACT_CONTRACT_ID">

					<!-- Modal -->

					<div class="row">
						<div class="col-xs-12 col-sm-12"><?php include("tab_menu2_r_temp.php"); ?></div>
						<?php
						if ($_SESSION["sys_group_id"] == '5' || $_SESSION["sys_group_id"] == '9') {
						?>
							<div class="col-xs-12 col-sm-12"><?php include("tab_menu_300.php"); ?></div>
						<?php
						}
						?>
					</div>

					<table width="100%">
						<tr>
							<td align="center"><strong class="font-blue"><?php echo $rec_head['PRJP_CODE'] . " " . text($rec_head['PRJP_NAME']) ?></strong></td>
						</tr>
					</table>
					<br />
					<div class="col-xs-12 col-sm-12">
						<div class="">
							<table width="22%" class="table table-bordered table-striped table-hover table-condensed">
								<thead>
									<tr class="bgHead">
										<th width="3%" rowspan="2" nowrap>
											<div align="center"><strong>ลำดับ</strong></div>
										</th>
										<th width="5%" rowspan="2" nowrap>
											<div align="center"><strong>เลขที่สัญญา</strong></div>
										</th>
										<th width="5%" rowspan="2" nowrap>
											<div align="center"><strong>วันที่จัดทำ</strong></div>
										</th>
										<th width="5%" rowspan="2" nowrap>
											<div align="center"><strong>วันที่ลงนาม</strong></div>
										</th>
										<th colspan="3">
											<div align="center"><strong>ประเภทสัญญา</strong></div>
										</th>
										<th rowspan="2" width="5%" nowrap>
											<div align="center"><strong>งบประมาณ</strong></div>
										</th>
										<th colspan="2" width="5%" nowrap>
											<div align="center"><strong>สำหรับสัญญาร่วมดำเนินการ ระบุชื่อหน่วยร่วม</strong></div>
										</th>
										<th rowspan="2" width="7%" nowrap>
											<div align="center"><strong>จัดการ</strong></div>
										</th>
									</tr>
									<tr class="bgHead">
										<th width="10%">
											<div align="center"><strong>สัญญาจ้าง</strong></div>
										</th>
										<th width="10%">
											<div align="center"><strong>MOU</strong></div>
										</th>
										<th width="10%">
											<div align="center"><strong>สัญญาร่วมดำเนินการ</strong></div>
										</th>
										<th width="10%">
											<div align="center"><strong>สังกัด/ประเภท</strong></div>
										</th>
										<th width="10%">
											<div align="center"><strong>หน่วยร่วม</strong></div>
										</th>
									</tr>
								</thead>
								<tbody>
									<?php
									if ($num_rows > 0) {
										$tg = 0;
										$mg = 0;
										$wg = 0;
										$i = 1;
										$query = $db->query($sql);
										$chk_type = "<i class=\"fa fa-check\" aria-hidden=\"true\"></i>";
										while ($rec = $db->db_fetch_array($query)) {

									?>
											<tr bgcolor="#FFFFFF">
												<td align="center"><?php echo $i; ?>.</td>
												<td align="center"><?php echo text($rec['PRJP_ACT_CONTRACT_NO']); ?>.</td>
												<td align="center"><?php echo conv_date($rec['PRJP_ACT_CONTRACT_DATE']); ?></td>
												<td align="left"><?php echo conv_date($rec['PRJP_ACT_CONTRACT_DATE_COMMIT']); ?></td>
												<td align="center"><?php echo $rec['PRJP_ACT_CONTRACT_TYPE'] == '1' ? $chk_type : ''; ?></td>
												<td align="center"><?php echo $rec['PRJP_ACT_CONTRACT_TYPE'] == '2' ? $chk_type : ''; ?></td>
												<td align="center"><?php echo $rec['PRJP_ACT_CONTRACT_TYPE'] == '3' ? $chk_type : ''; ?></td>
												<td align="right"><?php echo number_format($rec['PRJP_ACT_CONTRACT_BDG'], 2); ?></td>
												<td align="right"><?php echo text($rec['CO_ORG_TYPE_NAME']); ?></td>
												<td align="right"><?php echo text($rec['CO_ORG_SECTOR_NAME']); ?></td>
												<td align="center" nowrap>
													<?php if ($rec['PRJP_ACT_CONTRACT_FILE'] != "" && file_exists($path_a . $rec['PRJP_ACT_CONTRACT_FILE'])) { ?>
														<a class="btn btn-info" href="<?php echo $path_a . $rec['PRJP_ACT_CONTRACT_FILE']; ?>" target="_blank" title="donwload_file"><?php echo $img_download; ?> ดาวน์โหลด</a>
													<?php } ?>
												</td>
											</tr>
										<?php
											$i++;
										}
									} else {
										?>
										<tr>
											<td colspan="9" align="center">ไม่พบข้อมูล</td>
										</tr>
									<?php
									}
									?>
								</tbody>
							</table>
						</div>
					</div>
				</form>
			</div>
		</div>
		<?php include($path . "include/footer.php"); ?>
	</div>
	<!-- Modal -->
	<div class="modal fade" id="modal_contract" tabindex="-1" role="dialog" aria-labelledby="modal_contract_label" aria-hidden="true" data-backdrop="static" data-keyboard="false">
		<div class="modal-dialog" role="document" style="width: 100%;max-width:800px;">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title" id="modal_contract_label"><i class="fa fa-edit" aria-hidden="true"></i> เพิ่มสัญญาจ้าง</h4>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				</div>
				<form id="frm-contract" method="post" action="process/disp_project_act_process.php" enctype="multipart/form-data">
					<input name="proc" type="hidden" id="proc2">
					<input name="menu_id" type="hidden" id="menu_id" value="<?php echo $menu_id; ?>">
					<input name="menu_sub_id" type="hidden" id="menu_sub_id" value="<?php echo $menu_sub_id; ?>">
					<input name="page" type="hidden" id="page" value="<?php echo $page; ?>">
					<input name="page_size" type="hidden" id="page_size" value="<?php echo $page_size; ?>">
					<input type="hidden" id="PRJP_ID" name="PRJP_ID" value="<?php echo $PRJP_ID; ?>">
					<input type="hidden" id="PRJP_ACT_CONTRACT_ID" name="PRJP_ACT_CONTRACT_ID" value="<?php echo $_POST['PRJP_ACT_CONTRACT_ID'] ?>">

					<div class="modal-body"></div>

				</form>

				<div class="modal-footer">
					<button type="summit" class="btn btn-success" onclick="chkinput();"><i class="fa fa-check" aria-hidden="true"></i> บันทึก</button>
					<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-close" aria-hidden="true"></i> ยกเลิก</button>
				</div>
			</div>
		</div>
	</div>
</body>

</html>
<!-- Modal -->
<div class="modal fade" id="myModal"></div>
<!-- /.modal -->