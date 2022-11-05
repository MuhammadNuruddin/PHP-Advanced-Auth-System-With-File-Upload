<?php 

class Validate 
{
	private $_passed = false,
			$_errors = array(),
			$_db = null;



	public function __construct() {
		$this->_db = DB::connect();
	}

	public function check($source, $items) {
		foreach ($items as $item => $rules) {
			foreach ($rules as $rule => $rule_value) {
				// echo "".$rules['field_name']." {$rule} must be {$rule_value}</br>";
				// if($source !== $_POST || $source !== $_GET) {
				// 	$source = $_FILES;
				// }
				$value = trim($source[$item]);
				$item = escape($item);
				if ($rule === 'required' && empty($value)) {
					$this->addError($item,"".$rules['field_name']." is required");
				}else if(!empty($rule)) {
					switch ($rule) {
						case 'min':
							if (strlen($value) < $rule_value) {
								$this->addError($item,"".$rules['field_name']." must 
								be a minimum of {$rule_value} characters");
							}
							break;
						case 'number':
							if (!is_numeric($value)) {
								$this->addError($item,"".$rules['field_name']." must be a number");
							}
							break;
						// case 'file':
						// 	if (!is_file($value) && $_FILES[$item]['name'] == "") {
						// 		$this->addError($item,"".$rules['field_name']." must be a file");
						// 	}
						// 	break;
						case 'max':
							if (strlen($value) > $rule_value) {
								$this->addError($item,"".$rules['field_name']." must be a 
								maximum of {$rule_value} characters");
							}
							break;
						case 'matches':
							if ($value != $source[$rule_value]) {
								$this->addError($item,"".$rules['field_name']." must match");
							}
							break;	
						case 'unique':
							$check = $this->_db->select($rule_value, array($item,'=',$value));
							if ($check->count()) {
								$this->addError($item,"{$item} already exist");
							}
							break;					
					}
				}
			}
		}

		if (empty($this->_errors)) {
			$this->_passed = true;
		}
		return $this;
	}


	private function addError($key,$error) {
		$this->_errors[$key] = $error;
	}

	public function errors() {
		return $this->_errors;
	}

	public function passed() {
		return $this->_passed;
	}
}