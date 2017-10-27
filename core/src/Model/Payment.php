<?php 

namespace SG\Model;

use \SG\DB\Sql;
use \SG\Model;
use \SG\Mailer;
use \SG\Model\User;
use \SG\Model\Messsage;
use \SG\Model\Participant;
use MercadoPago\mercadopago;

class Payment {



	public static function PaymentCredit($email, $amount, $token, $installments, $payment_method_id, $name, $site)
	{

		$mp = new mercadopago("TEST-5867712708748536-102517-80bb2592d32bec67d8d0da42db86ad3c__LD_LA__-203534313");

		$idempotency_key = uniqid(rand());

		$payment_data = array(
			"transaction_amount" => $amount,
			"token" => $token,
			"description" => "SG Congressos",
			"installments" => intval($installments),
			"payment_method_id" => $payment_method_id,
			"payer" => array (
				"email" => $email
			),
			
		);

		$payment = $mp->post("/v1/payments", $payment_data);

		var_dump($payment);

		$status_code = $payment['status'];

		if($status_code == 201){
			foreach ($payment as $key => $value) {
				$date = $value['date_created'];
				$status = $value['status'];
				$status_detail = $value['status_detail'];

				// echo $date;
				// echo "<br>";
				// echo $status;
				// echo "<br>";
				// echo $status_detail;

				if($status == 'in_process' && $status_detail == 'pending_contingency')
				{

					$mailer = new Mailer($email, $name, "Pagamento em processo", "payment_process", array(
					"name"=> $name
					));

					$mailer->send();
					

				}
				else{
					if($status == 'rejected')
					{

						$mailer = new Mailer($email, $name, "Pagamento rejeitado", "payment_rejected", array(
						"name"=> $name
						));

						$mailer->send();

						

					}
					if($status == 'approved')
					{

						$mailer = new Mailer($email, $name, "Pagamento aprovado", "payment_approved", array(
						"name"=> $name
						));

						$mailer->send();

					}

				}
			}

			return $status_code;
		}


	}

	public static function PaymentMethods()
	{


		$mp = new mercadopago ("APP_USR-5867712708748536-102517-db9a877d6ddcc170d8fb2953723748bc__LB_LC__-203534313");

		$payment_methods = $mp->get ("/v1/payment_methods");

		print_r ($payment_methods);



	}


	public function ticketPay($date_of_expiration, $first_name, $last_name, $cpf, $email,
	 $street_name, $neighborhood, $street_number, $city, $state, $cep, $amount, $participant_id, $event_id)
	{
		
		$mp = new mercadopago 
		("APP_USR-5867712708748536-102517-db9a877d6ddcc170d8fb2953723748bc__LB_LC__-203534313");
		

		$payment_data = 
			array(
				"date_of_expiration" => $date_of_expiration,
				"transaction_amount" => $amount,
				"description" => "SG Congressos",
				"payment_method_id" => "bolbradesco",
				"payer"=> array(
					"email" => $email,
					"first_name"=> $first_name,
					"last_name"=> $last_name,
					"identification"=> array(
						"type"=> "CPF",
						"number"=> $cpf
					),
						
						
					"address" => array(
						"zip_code"=> "38304-040",
						"street_name"=> $street_name,
						"street_number"=> $street_number,
						"neighborhood"=> $neighborhood,
						"city"=> $city,
						"federal_unit"=> $state
					)
				),
				
		);
		
		$payment = $mp->post('/v1/payments', $payment_data);

		foreach ($payment as $key => $value) {
			$transaction = $value['transaction_details'];
			
			$url = $transaction['external_resource_url'];
					
		}

		$payment_id = $value['id'];

		$payment_method = $value['payment_type_id'];

		$status = $value['status'];

		// var_dump($payment_id);

		// var_dump($amount);

		// var_dump($date_of_expiration);

		// var_dump($payment_method);

		// var_dump($status);

		// var_dump($participant_id);

		// var_dump($event_id);

		$sql = new Sql();

		$results = $sql->query("INSERT INTO tb_payment (payment_id, total_amount, expiration, payment_method, status, create_user_id, event_id) 
			VALUES (:payment_id, :total_amount, :expiration, :payment_method, :status, :create_user_id, :event_id)
			", array(
				":payment_id" => $payment_id,
				":total_amount" => $amount,
				":expiration" => $date_of_expiration,
				":payment_method" => $payment_method,
				":status" => $status,
				":create_user_id" => $participant_id,
				":event_id" => $event_id

			));

		 if($results === 1)  return $results;


	}

	public static function searchPayment($iduser)
	{

		$sql = new Sql();

		$search = $sql->select("SELECT * FROM tb_payment WHERE create_user_id = :iduser", array(
			":create_user_id" => $iduser

			));

		return $search;

	}

	public static function checkPayment($iduser)
	{

		$mp = new mercadopago 
		("APP_USR-5867712708748536-102517-db9a877d6ddcc170d8fb2953723748bc__LB_LC__-203534313");

		$sql = new Sql();

		$search = $sql->select("SELECT * FROM tb_payment WHERE create_user_id = :iduser", array(
			":iduser" => $iduser

			));

		$data = $search[0];

		$payment_id = $data['payment_id'];

		$payment_method = $data['payment_method'];

		$payment = $mp->get('/v1/payments/'.$payment_id);


		
		foreach ($payment as $key => $value) 
		{

			$status = $value['status'];
					
		}

		var_dump($status);


		if($status == 'approved')
		{

			$results = $sql->query("CALL sp_payment_approved (:pidparticipant)", 
				array(
				":pidparticipant" => $iduser
				));

			return $results;

		}
		

	}


}

?>