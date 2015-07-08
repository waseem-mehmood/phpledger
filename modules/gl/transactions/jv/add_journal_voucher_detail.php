<?php
$voucher_id = '';
if(isset($_POST['voucher_id'])){
	$voucher_id = $_POST['voucher_id'];
}
if(isset($_GET['voucher_id'])){
	$voucher_id = $_GET['voucher_id'];
}
$journal_voucher = DB::queryFirstRow("SELECT * FROM ".DB_PREFIX.$_SESSION['co_prefix']."journal_vouchers WHERE voucher_id = $voucher_id");
$voucher_ref = $journal_voucher['voucher_ref_no'];
$voucher_date = getDateTime( $journal_voucher['voucher_date'], 'dShort');
$account_desc_long = $journal_voucher['voucher description'];

?> 
<!-- check voucher id, if no voucher id then nothing to show -->
<?php if($voucher_id <> ''){ ?>       
		<!-- Main content -->
        <section class="invoice">
          <!-- title row -->
          <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title">Add Journal Voucher Details</h3>
              <div class="box-tools pull-right">
                <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                <button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
   <div class="box-body">
	<?php if($voucher_id <> ''){ ?>
     <div class="progress">
		<div class="progress-bar progress-bar-success progress-bar-striped" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%">
        <span class="sr-only">100% </span>
        </div>
      </div>	
	<?php } else { ?>
		<div class="progress">
		<div class="progress-bar progress-bar-success progress-bar-striped" role="progressbar" aria-valuenow="90" aria-valuemin="0" aria-valuemax="90" style="width: 90%">
        <span class="sr-only">90% </span>
        </div>
      </div>
	<?php } ?>
          <div class="row">
            <div class="col-xs-12">
              <h2 class="page-header">
				<?php $logo_path = DB::queryfirstfield('SELECT company_logo_icon FROM '.DB_PREFIX.'companies'); ?>
                <i><img src="<?php echo $logo_path; ?>" height="50px" width="50px" /></i> <?php echo $_SESSION['company_name']; ?>
                <small class="pull-right"><?php echo date("j / n / Y"); ?></small>
              </h2>
            </div><!-- /.col -->
          </div>
          <!-- info row -->
          <div class="row invoice-info">
            <div class="col-sm-4 invoice-col">
                <strong>Voucher Description</strong><BR/>
				<?php echo $account_desc_long; ?>
				<BR/>
				
            </div><!-- /.col -->
			<div class="col-sm-4 invoice-col">
			<?php $invoice_no = DB::queryfirstfield("SELECT COUNT(*) FROM ".DB_PREFIX.$_SESSION['co_prefix']."journal_vouchers"); ?>
              <b>Entry# </b><?php echo $invoice_no+1; ?><br/>
              <b>Reference#:</b> <?php echo $voucher_ref; ?><br/>
              <b>Date:</b> <?php echo $voucher_date; ?><br/>
            </div><!-- /.col -->
			 <div class="col-sm-4 invoice-col">
              <a href="#addExpenseDetailModal" role="button" class="btn btn-large btn-primary pull-right" data-toggle="modal"><i class="fa fa-credit-card"></i> Add Detail</a>
            </div><!-- /.col -->
          </div><!-- /.row -->
		</div>
	</div>	
          <!-- Table row -->
          <div class="row">
            <div class="col-xs-12 table-responsive">
              <table id="example1" class="table table-bordered table-striped">
                    <thead>
                      <tr>
						<th>Account Code</th>
                        <th>Account Description</th>
                        <th>Debit</th>
                        <th>Credit</th>
						<th>Description</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
						<?php
						$journal_sql = DB::query("SELECT * FROM ".DB_PREFIX.$_SESSION['co_prefix']."journal_voucher_details ex WHERE ex.`voucher_id`='".$voucher_id."'");
						foreach($journal_sql as $journal){
						?>
                      <tr>
                        <td><?php echo $journal['account_id']; ?></td>
                        <td><?php echo $journal['account_id'];  ?></td>
                        <td><?php echo $journal['debit_amount'];  ?></td>
						<td><?php echo $journal['credit_amount'];  ?></td>
                        <td><?php echo $journal['entry_description'];  ?></td>
						
                        <td><a href="#delExpenseDetailModal" class="btn btn-danger" data-toggle="modal"><i class="fa fa-trash"></i>&nbsp;Delete</a>
						<a href="#editExpenseDetailModal" class="btn btn-success" data-toggle="modal"><i class="fa fa-pencil"></i>&nbsp;Edit</a>
						</td>
                      </tr>
					  <?php } ?>
					</tbody>
				</table>
            </div><!-- /.col -->
          </div><!-- /.row -->

          <div class="row">
            <div class="col-xs-6">
              <p class="lead">Amount Due <?php echo $voucher_date; ?></p>
              <div class="table-responsive">
			  
                <table class="table">
					<tr>
				   <th>Total:</th>
                    <th>Debit:</th>
					<th>Credit:</th>
                  </tr>
				  <tr>
					<td>&nbsp;</td>
                    <td><?php echo $journal_voucher['debits_total']; ?></td>
					<td><?php echo $journal_voucher['credits_total']; ?></td>
				  </tr>
                </table>
              </div>
            </div><!-- /.col -->
          </div><!-- /.row -->

          <!-- this row will not appear when printing -->
          <div class="row no-print">
            <div class="col-xs-12">
              <a href="invoice-print.php" target="_blank" class="btn btn-default"><i class="fa fa-print"></i> Print</a>
			   <button class="btn btn-default pull-right" style="margin-right: 5px;"><i class="fa fa-download"></i> Generate PDF</button>&nbsp;
			   <button class="btn btn-success pull-right" style="margin-right: 5px;"><i class="fa fa-check"></i> Process Entry</button> 
             
            </div>
          </div>
        </section>
<?php } else { ?>
	<p style="color:red"> &nbsp;&nbsp;&nbsp;&nbsp;Sorry..! Please provide the voucher header details </p>
	<a href="<?php echo SITE_ROOT."index.php?route=modules/gl/transactions/expense/add_journal_voucher" ?>"> &nbsp;&nbsp;&nbsp;&nbsp;Click here to add journal voucher </a>
<?php } ?>		
<!-- Modal Add Detail -->

<script type="text/javascript">
$(document).ready(function(){
	$('#save_entry').click(function(){
        $.ajax({
				type: "POST",
				url: "<?php echo SITE_ROOT."includes/ajax-helpers/ajax_journal_voucher.php"; ?>",
				data: $('#frm_journal_entry').serialize(),
				success: function(msg){
					alert(msg);
					$("#addExpenseDetailModal").modal('hide');
					window.location.reload();
					},
				error: function(){
					alert("failure");
					}
			});
		});
});

</script>

<div id="addExpenseDetailModal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Add Journal Entry Detail</h4>
            </div>
            <div class="modal-body">
				 <form class="form-horizontal" role="form" method="POST" action="" id="frm_journal_entry">
    <div class="form-group">
      <label class="control-label col-sm-4" for="expense_type">Account Description:</label>
      <div class="col-sm-8">
        <select class="form-control" id="account_description" name="account_description">
		<?php $account_desc = DB::query("SELECT	c.`account_code`, c.`account_desc_short` FROM ".DB_PREFIX.$_SESSION['co_prefix']."coa c WHERE c.`activity_account` = 1 AND c.`account_status`='active'"); 
		foreach($account_desc as $coa){
		?>
			<option value="<?php echo $coa['account_code']; ?>"><?php echo $coa['account_desc_short']; ?></option>
		<?php } ?>
		</select>
      </div>
    </div>
    <div class="form-group">
      <label class="control-label col-sm-4" for="transaction_type">Transaction Type:</label>
      <div class="col-sm-8">          
       <select class="form-control" id="transaction_type" name="transaction_type">
			<option value="debit">Debit</option>
			<option value="credit">Credit</option>
	   </select>
      </div>
    </div>
    <div class="form-group">
      <label class="control-label col-sm-4" for="journal_amount">Amount:</label>
      <div class="col-sm-8">          
        <input type="text" class="form-control" id="journal_amount" name="journal_amount" placeholder="Journal Amount">
      </div>
    </div>
	<div class="form-group">
      <label class="control-label col-sm-4" for="entry_description">Entry Description:</label>
      <div class="col-sm-8">          
        <input type="text" class="form-control" id="entry_description" name="entry_description" placeholder="Entry Description">
      </div>
    </div>
	<input type="hidden" name="voucher_id" id="voucher_id" value="<?php echo $voucher_id; ?>" />
		<input type="hidden" name="operation" id="add-adj-operation" value="addEntry" />
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="save_entry btn btn-primary" name="save_entry" id="save_entry">Add</button>
            </div>
			</form>
        </div>
    </div>
</div>	

<!-- Modal Edit Detail -->

<div id="editExpenseDetailModal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Edit Journal Entry Detail</h4>
            </div>
            <div class="modal-body">
				 <form class="form-horizontal" role="form" method="POST" action="" id="frm_journal_entry_edit">
    <div class="form-group">
      <label class="control-label col-sm-4" for="expense_type">Account Description:</label>
      <div class="col-sm-8">
        <select class="form-control" id="account_description" name="account_description">
		<?php $account_desc = DB::query("SELECT	c.`account_code`, c.`account_desc_short` FROM ".DB_PREFIX.$_SESSION['co_prefix']."coa c WHERE c.`activity_account` = 1 AND c.`account_status`='active'"); 
		foreach($account_desc as $coa){
		?>
			<option value="<?php echo $coa['account_code']; ?>"><?php echo $coa['account_desc_short']; ?></option>
		<?php } ?>
		</select>
      </div>
    </div>
    <div class="form-group">
      <label class="control-label col-sm-4" for="transaction_type">Transaction Type:</label>
      <div class="col-sm-8">          
       <select class="form-control" id="transaction_type" name="transaction_type">
			<option value="debit">Debit</option>
			<option value="credit">Credit</option>
	   </select>
      </div>
    </div>
    <div class="form-group">
      <label class="control-label col-sm-4" for="journal_amount">Amount:</label>
      <div class="col-sm-8">          
        <input type="text" class="form-control" id="journal_amount" name="journal_amount" placeholder="Journal Amount">
      </div>
    </div>
	<div class="form-group">
      <label class="control-label col-sm-4" for="entry_description">Entry Description:</label>
      <div class="col-sm-8">          
        <input type="text" class="form-control" id="entry_description" name="entry_description" placeholder="Entry Description">
      </div>
    </div>
		<input type="hidden" name="operation" id="add-adj-operation" value="editEntry" />
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="edit_entry btn btn-primary" name="edit_entry" id="edit_entry">Add</button>
            </div>
			</form>
        </div>
    </div>
</div>	

<!-- Delete Modal Journal Detail -->
<div class="modal fade" id="delExpenseDetailModal" tabindex="-1" role="dialog" aria-labelledby="delModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="delModalLabel">Confirm Delete</h4>
      </div>
<form role="form" method="POST"  id="frmdel2" name="delete" action="" class="form-inline" >
 
      <div class="modal-body bg-warning">
       Are you sure you want to delete this Journal Entry..
				<input type="hidden" name="voucher_detail_id" id="voucher_detail_id" value="0" />
		<input type="hidden" name="operation" id="add-adj-operation" value="delEntry" />
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="submitdeladj btn btn-danger">Delete</button>
      </div>
	  </form>
    </div>
  </div>
</div>

