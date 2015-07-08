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
	}
}
?>