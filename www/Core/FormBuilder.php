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

			$html .= "<div class='input-group'>";

			if (isset($configInput["label"])) {
        if(!isset($configInput['hidden']) || (isset($configInput['hidden']) && $configInput['hidden'] == false)){
				  $html .= "<label for='" . ($configInput["id"] ?? "") . "'>" . ($configInput["label"] ?? "") . " </label>";
			  }
			}

			if ($configInput["type"] == "select") {
				$html .= self::renderSelect($name, $configInput);
			} elseif ($configInput["type"] == "textarea") {
				$html .= self::renderTextArea($name, $configInput);
			} else {
				$html .= self::renderInput($name, $configInput);
			}

			$html .= "</div>";
		}

		if(isset($form["config"]["name"])){
			$namePost = "name=\"".$form["config"]["name"]."\"";
		}

		$html .= "<button ".($namePost??'')." type='submit' value=\"" . ($form["config"]["submit"] ?? "Valider") . "\" class='btn btn-primary'>" . ($form["config"]["submit"] ?? "Valider") . "</button>";

		// 	foreach ($form["buttons"] as $name => $configButton) {


		// 		$html .= "<a id='" . ($configButton["id"] ?? "") . "' class='" . ($configButton["class"] ?? "") . "' ";
		// 		if (isset($configButton["href"])) {
		// 			$html .= "href='" . ($configButton["href"] ?? "") . "'><button class='" . ($configButton["btn_class"] ?? "") . "'>" . ($configButton["text"] ?? "");
		// 		}
	
		// 		$html .= "</button></a>";
		// 	}
		// }


		$html .= "</form>";

		echo $html;
	}


	public static function renderInput($name, $configInput)
	{
		if(!isset($configInput['hidden']) || (isset($configInput['hidden']) && $configInput['hidden'] == false)){
			return sprintf('<input name="%s" type="%s" id="%s" class="%s" placeholder="%s" value="%s"'
                            . (!empty($configInput["required"]) ? "required='required'" : "")
                             . (isset($configInput["disabled"]) ? 'disabled=disabled' : '')
						 . (!empty($configInput["checked"]) ? "checked" : "") . '><br>',
						$name,
                        $configInput["type"] ?? "text",
                        $configInput["id"] ?? "",
                        $configInput["class"] ?? "",
                        $configInput["placeholder"] ?? "",
                        $configInput["value"] ?? "");

						/*name='" . $name . "'
						type='" . ($configInput["type"] ?? "text") . "'
						id='" . ($configInput["id"] ?? "") . "'
						class='input-field " . ($configInput["class"] ?? "") . "'
						placeholder='" . ($configInput["placeholder"] ?? "") . "'
						value='" . ($configInput['value'] ?? '') . "'
						" . (!empty($configInput["required"]) ? "required='required'" : "") . "
						" . (isset($configInput["disabled"]) ? 'disabled=disabled' : '') . "
						" . (!empty($configInput["checked"]) ? "checked" : "") . "


					><br>');*/
		}
	}




    public static function renderSelect($name, $configInput)
    {
        $html = "<select name='" . $name . "' id='" . ($configInput["id"] ?? "") . "'
						class='input-field " . ($configInput["class"] ?? "") . "' ";
        $html .= (!empty($configInput["required"])) ? "required='required' " : "";
        $html .= (!empty($configInput["multiple"])) ? "multiple='multiple'" : "";
        $html .= ">";

        if (empty($configInput["value"])){
            foreach ($configInput["options"] as $key => $item){
                $html .= "<option value='" . $item . "'>" . $item . "</option>";
            }
        }else{
            $not_selected = array_diff($configInput['options'],$configInput['value']);
            foreach ($configInput["value"] as $item => $value) {
                $html .= "<option value='" . $value . "' selected>" . $value . "</option>";
            }
            foreach ($not_selected as $key => $option) {
                $html .= "<option value='" . $option . "'>" . $option . "</option>";
            }
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