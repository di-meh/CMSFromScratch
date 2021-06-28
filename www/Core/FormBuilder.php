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
				enctype='" . ($form["config"]["enctype"] ?? "") . "'
				action='" . ($form["config"]["action"] ?? "") . "'>";


		foreach ($form["inputs"] as $name => $configInput) {
			$html .= "<br><label for='" . ($configInput["id"] ?? "") . "'>" . ($configInput["label"] ?? "") . " </label>";


			if ($configInput["type"] == "select") {
				$html .= self::renderSelect($name, $configInput);
			} elseif ($configInput["type"] == "textarea") {
				$html .= self::renderTextArea($name, $configInput);
			} else {
				$html .= self::renderInput($name, $configInput);
			}
		}



		$html .= "</br><input type='submit' value=\"" . ($form["config"]["submit"] ?? "Valider") . "\">";

		$html .= "</form>";



		echo $html;
	}


	public static function renderInput($name, $configInput)
	{
		return "<input 
						name='" . $name . "' 
						type='" . ($configInput["type"] ?? "text") . "'
						id='" . ($configInput["id"] ?? "") . "'
						class='" . ($configInput["class"] ?? "") . "'
						placeholder='" . ($configInput["placeholder"] ?? "") . "'
						value='" . ($configInput['value'] ?? '') . "'
						" . (!empty($configInput["required"]) ? "required='required'" : "") . "
						" . (isset($configInput["disabled"]) ? 'disabled=disabled' : '') . "'

					><br>";
	}




	public static function renderSelect($name, $configInput)
	{
		$html = "<select name='" . $name . "' id='" . ($configInput["id"] ?? "") . "'
						class='" . ($configInput["class"] ?? "") . "'>";


		foreach ($configInput["options"] as $key => $value) {
			$html .= "<option value='" . $key . "'>" . $value . "</option>";
		}

		$html .= "</select><br>";

		return $html;
	}

	public static function renderTextArea($name, $configInput)
	{
		return "<textarea id='" . ($configInput["id"] ?? "") . "'
				class='" . ($configInput["class"] ?? "") . "' 
				name='" . ($name ?? "") . "' 
				rows='" . ($configInput["rows"] ?? "") . "'
				cols='" . ($configInput["cols"] ?? "") . "'>" . ($configInput['value'] ?? '') . "</textarea>";
	}
}
