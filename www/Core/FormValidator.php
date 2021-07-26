<?php

namespace App\Core;

class FormValidator
{

	public static function checkIfRequired($form, $data){
		$errors = [];

		foreach ($form["inputs"] as $name => $configInput) {
			
			if(!empty($configInput["minLength"]) &&
				is_numeric($configInput["minLength"]) &&
				strlen($data[$name]) < $configInput["minLength"] &&
				(isset($configInput['required']) && $configInput['required'] == false)
				){
				$errors[] = $configInput["error"];
			}

		}

		return $errors;
	}

	public static function check2($form, $data){
		$errors = [];

		foreach ($form["inputs"] as $name => $configInput) {
			
			if(!empty($configInput["minLength"]) &&
				is_numeric($configInput["minLength"]) &&
				strlen($data[$name]) < $configInput["minLength"]
				){
				$errors[] = $configInput["error"];
			}

		}

		return $errors;
	}

	public static function check($form, $data){
		$errors = [];

		if( count($data) == count($form["inputs"])){

			foreach ($form["inputs"] as $name => $configInput) {
				
				if(!empty($configInput["minLength"]) &&
					is_numeric($configInput["minLength"]) &&
					strlen($data[$name]) < $configInput["minLength"]
					){
					$errors[] = $configInput["error"];
				}

			}


		}else{
			$errors[] = "Tentative de Hack";
		}

		return $errors;
	}


}