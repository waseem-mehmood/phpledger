<?php
require_once('../../functions.php');

function addEntryDetail($voucher_id, $account_description, $transaction_type, $journal_amount, $entry_description){
	$now_date = getDateTime( date('now'),"mySQL" );
	if($transaction_type == 'debit'){
					DB::insert(DB_PREFIX.$_SESSION['co_prefix']."journal_voucher_details", array(
												 'voucher_id' => $voucher_id
												 ,'account_id'	=> $account_description
												 ,'entry_description'	=> $entry_description
												 ,'debit_amount'	=> $journal_amount
												 ,'credit_amount'	=> '0'
												 ,'created_by'		=> $_SESSION['user_name']
												 ,'created_on'		=> $now_date
												 ,'voucher_detail_status' => 'active'
												));
	$debit_total = DB::queryFirstField("SELECT SUM(jv.`debit_amount`) FROM ".DB_PREFIX.$_SESSION['co_prefix']."journal_voucher_details jv WHERE voucher_id=$voucher_id");
	//update debit total in journal voucher 
			DB::update(DB_PREFIX.$_SESSION['co_prefix'].'journal_vouchers', array(
						  'debits_total' => $debit_total
						), "voucher_id=%s", $voucher_id);
	} else {
					DB::insert(DB_PREFIX.$_SESSION['co_prefix']."journal_voucher_details", array(
												 'voucher_id' => $voucher_id
												 ,'account_id'	=> $account_description
												 ,'entry_description'	=> $entry_description
												 ,'debit_amount'	=> '0'
												 ,'credit_amount'	=> $journal_amount
												 ,'created_by'		=> $_SESSION['user_name']
												 ,'created_on'		=> $now_date
												 ,'voucher_detail_status' => 'active'
												));
			$credit_total = DB::queryFirstField("SELECT SUM(jv.`credit_amount`) FROM ".DB_PREFIX.$_SESSION['co_prefix']."journal_voucher_details jv WHERE voucher_id=$voucher_id");									
			//update credits total in journal voucher 
			DB::update(DB_PREFIX.$_SESSION['co_prefix'].'journal_vouchers', array(
						  'credits_total' => $credit_total
						), "voucher_id=%s", $voucher_id);									
	}
	
	
	
}
function deleteEntry( $voucher_detail_id, $voucher_amount, $voucher_type, $voucher_id ){
	// Delete voucher Detail
	DB::delete(DB_PREFIX.$_SESSION['co_prefix']."journal_voucher_details" , "voucher_detail_id=%s", $voucher_detail_id);
	if($voucher_type == 'debit'){
		$debit_total = DB::queryFirstField("SELECT SUM(jv.`debits_total`) FROM ".DB_PREFIX.$_SESSION['co_prefix']."journal_vouchers jv WHERE voucher_id=$voucher_id");
		$debit_total = $debit_total - $voucher_amount;
		//update debits total in journal voucher 
			DB::update(DB_PREFIX.$_SESSION['co_prefix'].'journal_vouchers', array(
						  'debits_total' => $debit_total
						), "voucher_id=%s", $voucher_id);
	} else {
		
				$credit_total = DB::queryFirstField("SELECT SUM(jv.`credits_total`) FROM ".DB_PREFIX.$_SESSION['co_prefix']."journal_vouchers jv WHERE voucher_id=$voucher_id");
		$credit_total = $credit_total - $voucher_amount;
		//update credits total in journal voucher 
			DB::update(DB_PREFIX.$_SESSION['co_prefix'].'journal_vouchers', array(
						  'credits_total' => $credit_total
						), "voucher_id=%s", $voucher_id);
	}
}



if(isset($_POST['operation'])){
	$operation = $_POST['operation'];
	switch($operation){
		case 'addEntry':
			$voucher_id = $_POST['voucher_id'];
			$account_description = $_POST['account_description'];
			$transaction_type = $_POST['transaction_type']; 
			$journal_amount = $_POST['journal_amount']; 
			$entry_description = $_POST['entry_description'];
			addEntryDetail($voucher_id, $account_description, $transaction_type, $journal_amount,  $entry_description);
		break;
		case 'delEntry':
				$voucher_detail_id = $_POST['voucher_detail_id'];
				$voucher_amount = $_POST['voucher_amount'];
				$voucher_type = $_POST['voucher_type'];
				$voucher_id = $_POST['voucher_id'];
				deleteEntry( $voucher_detail_id, $voucher_amount, $voucher_type, $voucher_id );
		break;
		default:
		
		break;
	}
}
?>