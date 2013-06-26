<?php


trait CheckTemplate {


	/**
	 * Verifie que tous les aspects définis par les check_ sont corrects.
	 * @return boolean True si tout est bon, lance une exception sinon.
	 */
	public function run(){
		foreach ( get_class_methods(get_class($this)) as $key => $method) 
		{
			if(stristr($method, 'check_') != false)
			{
				$m = explode('_', $method);
				array_shift($m);
				if(call_user_func(get_class($this).'::'.$method) === false)
				{
					throw new CoreException(
						"Problem when checking "
						.implode(' ', $m), 5);
					return false;
				}
			}
		}
		return true;
	}

	public function getReportArray()
	{
		$interrupted = false;
		$results = array();
		foreach ( get_class_methods(get_class($this)) as $key => $method) {
			if(stristr($method, 'check_') != false){
				$m = explode('_', $method);
				array_shift($m);
				if(!$interrupted)
					$r = call_user_func(get_class($this).'::'.$method);
				else
					$r = 'o';
				$results[implode(' ', $m)] = $r;
				if(!$r)
					$interrupted = true;
			}
		}
		return $results;
	}

	public function toSimpleHtml()
	{
		$results = $this->getReportArray();
		$output = '<table>';
		foreach ($results as $meth => $res) {
			$output .= '<tr>';
			if($res == false){
				$output .= '<td style="color: red;">✖</td>';
			}
			else if($res === true){
				$output .= '<td style="color: green;">✔</td>';
			}
			else {
				$output .= '<td style="color: gray;">❓</td>';
			}
			$output .= '<td style="color: lightgray;"> ----- </td>';
			$output .= '<td>'.$meth.'</td>';
			$output .= '</tr>';
		}
		$output .= '</table>';
		return $output;
	}

}
