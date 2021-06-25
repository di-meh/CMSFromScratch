<?php

namespace App\Core;

class FormBuilder
{

	public static function render($form)
	{

		$html = "<form 
				method='" . ($form["config"]["method"] ?? "GET") . "' 
				id='" . ($form["config"]["id"] ?? "") . "' 
				class='" . ($form["config"]["class"] ?? "") . "' 
				action='" . ($form["config"]["action"] ?? "") . "'>";


		foreach ($form["inputs"] as $name => $configInput) {
			$html .= "<div class='input-group'>";
			$html .= "<label for='" . ($configInput["id"] ?? "") . "'>" . ($configInput["label"] ?? "") . " </label>";


			if ($configInput["type"] == "select") {
				$html .= self::renderSelect($name, $configInput);
			} else {
				$html .= self::renderInput($name, $configInput);
			}
			$html .= "</div>";
		}



		$html .= "<button type='submit' value=\"" . ($form["config"]["submit"] ?? "Valider") . "\" class='btn btn-primary'>" . ($form["config"]["submit"] ?? "Valider") . "</button>";

		$html .= "</form>";



		echo $html;
	}


	public static function renderInput($name, $configInput)
	{
		return "<input 
						name='" . $name . "' 
						type='" . ($configInput["type"] ?? "text") . "'
						id='" . ($configInput["id"] ?? "") . "'
						class='input-field " . ($configInput["class"] ?? "") . "'
						placeholder='" . ($configInput["placeholder"] ?? "") . "'
						value='" . ($configInput['value'] ?? '') . "'
						" . (!empty($configInput["required"]) ? "required='required'" : "") . "
						" . (isset($configInput["disabled"]) ? 'disabled=disabled' : '') . "'

					><br>";
	}




	public static function renderSelect($name, $configInput)
	{
		$html = "<select name='" . $name . "' id='" . ($configInput["id"] ?? "") . "'
						class='input-field " . ($configInput["class"] ?? "") . "'>";


		foreach ($configInput["options"] as $key => $value) {
			$html .= "<option value='" . $key . "'>" . $value . "</option>";
		}

		$html .= "</select><br>";

		return $html;
	}
}
