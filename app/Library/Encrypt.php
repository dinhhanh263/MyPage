<?php

namespace App\Library;

use Illuminate\Support\Facades\Redirect;

class Encrypt
{
	private $sKey = 'Nb7ZfERe94m5aF3qEU66h2vJee1t758T';

	public function encode($value){
		if (!$value){
			return false;
		}

		$crypttext = openssl_encrypt($value, 'aes-256-ecb', $this->sKey);
		return trim($this->safe_b64encode($crypttext));
	}

	public function safe_b64encode($string) {
		$data = base64_encode($string);
		$data = str_replace(array('+','/','='),array('-','_',''),$data);
		return $data;
	}

	public function safe_b64decode($string) {
		$data = str_replace(array('-','_'),array('+','/'),$string);
		$mod4 = strlen($data) % 4;
		if ($mod4) {
			$data .= substr('====', $mod4);
		}
		return base64_decode($data);
	}

	public function clipter_encode($value) {
		$tmp_array = str_split($value);

		$rndm_str = null;
		foreach ($tmp_array as $no)
		{
			$rndm_str .= $this->makeRandStr(5) . $no;
		}

		return base64_encode($rndm_str);
	}

	public function clipter_decode($code)
	{
		$tmp = str_split(base64_decode($code));

		$i = 1;

		$id = null;
		foreach ($tmp as $value)
		{
			if (($i % 6) == 0)
			{
				$id .= $value;
			}
			$i++;
		}
		return $id;
	}

	function makeRandStr($length) {
		$str = array_merge(range('a', 'z'), range('0', '9'), range('A', 'Z'));
		$r_str = null;
		for ($i = 0; $i < $length; $i++) {
			$r_str .= $str[rand(0, count($str) - 1)];
		}
		return $r_str;
	}
}