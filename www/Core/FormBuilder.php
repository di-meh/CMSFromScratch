<?php

namespace App\Core;

class FormBuilder
{

	public static function render($form){

		$html = "<form 
				method='".($form["config"]["method"]??"GET")."' 
				id='".($form["config"]["id"]??"")."' 
				class='".($form["config"]["class"]??"")."' 
				action='".($form["config"]["action"]??"")."'>";


		foreach ($form["inputs"] as $name => $configInput) {
			$html .="<label for='".($configInput["id"]??"")."'>".($configInput["label"]??"")." </label>";


			if($configInput["type"] == "select"){
				$html .= self::renderSelect($name, $configInput);
			}else{
				$html .= self::renderInput($name, $configInput);
			}

		}



		$html .= "<input type='submit' value=\"".($form["config"]["submit"]??"Valider")."\">";

		$html .= "</form>";



		echo $html;

	}


	public static function renderInput($name, $configInput){
		return "<input 
						name='".$name."' 
						type='".($configInput["type"]??"text")."'
						id='".($configInput["id"]??"")."'
						class='".($configInput["class"]??"")."'
						placeholder='".($configInput["placeholder"]??"")."'
						".(!empty($configInput["required"])?"required='required'":"")."
					><br>";
	}




	public static function renderSelect($name, $configInput){
		$html = "<select name='".$name."' id='".($configInput["id"]??"")."'
						class='".($configInput["class"]??"")."'>";


		foreach ($configInput["options"] as $key => $value) {
			$html .= "<option value='".$key."'>".$value."</option>";
		}

		$html .= "</select><br>";

		return $html;
	}

}