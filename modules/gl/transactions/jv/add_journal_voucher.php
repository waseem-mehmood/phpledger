<?php

$now = getDateTime( date('now'),"mySQL" );
if(isset($_POST['addExpenseVoucer'])){
	$voucher_ref = $_POST['voucher_ref'];
	$voucher_date = $_POST['voucher_date'];
	$voucher_date = getDateTime( $voucher_date ,"mySQL" );
	$entry_description = $_POST['entry_description'];
	$insert = DB::insert(DB_PREFIX.$_SESSION['co_prefix'].'journal_vouchers', array(
											'voucher_date'				=> 	$voucher_date
											,'voucher description'		=>	$entry_description
											,'voucher_ref_no'			=>	$voucher_ref
											,'created_by'				=>	$_SESSION['user_name']
											,'created_on'				=>	$now
											,'voucher_status'			=>	'draft'
											));
	if($insert){
		$voucher_id = DB::insertId();	
		echo '<script>window.location.replace("'.$_SERVER['PHP_SELF'].'?route=modules/gl/transactions/jv/add_journal_voucher_detail&voucher_id='.$voucher_id.'");</script>';
	} else{
		echo '<script> alert("Whoops..! Something wrong") </script>';
	}
										
	
}


?>

<!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
          	Journal Voucher
            <small>Create Journal Voucher (Draft)</small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="<?php echo SITE_ROOT; ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="#">Journal Vouchers</a></li>
            <li class="active">Add New Journal Voucher</li>
          </ol>
        </section>

        <!-- Main content -->
        <section class="content">

 <!-- Default box -->
          <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title">Create Journal Voucher (Header)</h3>
              <div class="box-tools pull-right">
                <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                <button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
<div class="box-body">
     <div class="progress">
		<div class="progress-bar progress-bar-success progress-bar-striped" role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100" style="width: 50%">
        <span class="sr-only">50% Complete  </span>
        </div>
      </div>
<form class="form-horizontal" role="form" method="POST" action="">
				<div class="form-group">
					<label class="col-md-3 col-sm-3 control-label">Entry Ref#&nbsp;</label>
						<div class="col-md-2 col-sm-2">
						<input class="form-control" required="required" name="voucher_ref" id="voucher_ref" value="" />				
						<p class="help-block"> </p>
					</div><!-- /.col -->
				</div> <!-- /form-group --> 
				<div class="form-group">
					<label class="col-md-3 col-sm-3 control-label">Entry Date&nbsp;</label>
						<div class="col-md-9 col-sm-9">
						<input name="voucher_date" required="required" id="voucher_date"   class="date-picker form-control" size="16" type="text" value="" />				
						<p class="help-block"> </p>
					</div><!-- /.col -->
				</div> <!-- /form-group --> 
				
				<div class="form-group">
					<label class="col-md-3 col-sm-3 control-label">Entry Description: &nbsp;</label>
						<div class="col-md-9 col-sm-9">
				<textarea  name="entry_description" id="entry_description" class="form-control textarea"  ></textarea>				
						<p class="help-block"> </p>
					</div><!-- /.col -->
				</div> <!-- /form-group --> 
        
<div class="form-group">
	<div class="col-sm-12">
		<button type="submit" class='btn btn-success btn-lg pull-right' name="addExpenseVoucer" id="addExpenseVoucer" value="Next">Next &nbsp; <i class="fa fa-chevron-circle-right"></i></button>
	</div>	<!-- /.col -->
</div>		<!-- /form-group -->	   
</form>
            <!-- Add Account Form Goes here -->
             </div><!-- /.box-body -->
            <div class="box-footer">
             <small> Please provide the expense header</small>
            </div><!-- /.box-footer-->
          </div><!-- /.box -->
     	 </section><!-- /.content -->