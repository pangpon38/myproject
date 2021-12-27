<?php if($WF_SCREEN_NO != ""){ ?>
<div class="row-fluid">
<div class="col-lg-12">
	<div class="f-right">
		<small><b><i class="icon-screen-desktop"></i> <?php echo $WF_SCREEN_NO; ?></b></small>
	</div>
</div>
</div><?php
}
if($HIDE_HEADER != "Y"){
?>
<!-- Modal -->
<div class="modal fade modal-flex" id="bizModal" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static">
	<div class="modal-dialog modal-lg " role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close biz-close-modal" data-number="bizModal" aria-label="Close"><span aria-hidden="true">&times;</span>x</button>
				<h4 class="modal-title" id="myModalLabel"></h4>
			</div>
			<div class="modal-body">

			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-danger biz-close-modal" data-number="bizModal">ปิด</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade modal-flex" id="bizModal2" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static">
	<div class="modal-dialog modal-lg " role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close biz-close-modal" data-number="bizModal2" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel"></h4>
			</div>
			<div class="modal-body">

			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-danger biz-close-modal" data-number="bizModal2">ปิด</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade modal-flex" id="bizModal3" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static">
	<div class="modal-dialog modal-lg " role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close biz-close-modal" data-number="bizModal3" aria-label="Close" ><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel"></h4>
			</div>
			<div class="modal-body">

			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-danger biz-close-modal" data-number="bizModal3">ปิด</button>
			</div>
		</div>
	</div>
</div>

	<script>
		$('button.biz-close-modal').click(function()
		{
			var modal_number = $(this).attr('data-number');
			var modal_id = $(this).parents(':eq(3)').attr('id');
			$('#' + modal_number).modal('hide');
			$('#' + modal_id + ' .modal-title, #' + modal_id + ' .modal-body').html('');
		});
	</script>

</body>
</html>
<?php
}
db::db_close();
?>