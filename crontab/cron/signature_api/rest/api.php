<?php
	require_once("Rest.inc.php");
	
	class API extends REST {
		public $data = "";
		public function __construct(){
			parent::__construct();				// Init parent contructor
		}

		protected function user(){
			// Cross validation if the request method is POST else it will return "Not Acceptable" status
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
            $signature_id = (int)$this->_request['signature_id'];
			if(!empty($signature_id)){
				$info_array = array(
						"fields"=>"signature_encode",
						"where"=>"id = '".$signature_id."'"
					);
				$user_data = $this->GetSingleRecord("users",$info_array);

				if(count($user_data)>0) {
					//$response_array['status']='success';
					//$response_array['message']='Valid Record.';
					//$response_array['data']=$user_data;
					//$this->response($this->json($response_array), 200);
					echo $user_data['signature_encode'];
				} else {
					$response_array['status']='fail';
					$response_array['message']='Record not found.';
					$response_array['data']='';
					$this->response($this->json($response_array));
				}
			}

			// If invalid inputs "Bad Request" status message and reason
			//$error = array('status' => "Failed", "msg" => "Invalid data");
			//$this->response($this->json($error), 400);
		}

		protected function users(){
			// Cross validation if the request method is GET else it will return "Not Acceptable" status
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}

			$info_array = array(
						"fields"=>"id,user_type,user_sub_type,user_full_name,user_email,is_approved,is_locked,signature,signature_encode"
					);
			$user_data = $this->GetRecord("users",$info_array);

			if(count($user_data)>0) {
				$response_array['status']='success';
				$response_array['message']='Total '.count($user_data).' record(s) found.';
				$response_array['total_record']= count($user_data);
				$response_array['data']=$user_data;
				$this->response($this->json($response_array), 200);
			} else {
				$response_array['status']='fail';
				$response_array['message']='Record not found.';
				$response_array['data']='';
				$this->response($this->json($response_array), 204);
			}
		}
	}
	// Initiiate Library
	$api = new API();
	$api->processApi();
?>