<?php
	exec("python translate.py test.py json 2>&1",$out);
	$out = json_decode($out[0]);
	//print_r($out);exit;
	
	$arrayListenVariable = array();
	$incGroup = 0;
	function genName()
	{
		global $arrayListenVariable,$incGroup;
		$symbols1 = 'QWERTYUIOPASDFGHJKLZXCVBNMqwertyuiopasdfghjklzxcvbnm_';
		$symbols2 = 'QWERTYUIOPASDFGHJKLZXCVBNMqwertyuiopasdfghjklzxcvbnm_0123456789';
		$i = count($arrayListenVariable);
		$s = $symbols1[rand(0,52)];
		if($i)
		{
			if(!($i%52)) ++$incGroup;
			$ii = $incGroup;
			while($ii--) $s.=$symbols2[rand(0,62)];
		}
		if(in_array($s,$arrayListenVariable)) return genName();
		$arrayListenVariable[] = $s;
		return $s;
	}
	$globData = array();
	function addData($func)
	{
		global $globData;
		if(gettype($func)=='string' && ($func[0]!='"'||$func[0]!="'")) return;
		if(in_array($func,$globData)) return array_search($func,$globData);
		$name = genName();
		$globData[$name] = $func;
		return $name;
	}
	
	$dbVariable = array();
	function addVar($v)
	{
		global $dbVariable;
		if(in_array($v,$dbVariable)) return array_search($v,$dbVariable);
		$dbVariable[] = $v;
		return array_search($v,$dbVariable);
	}
	

	function LOAD_CONST($a)
	{
		echo addData($a);
	}
	
	function STORE_NAME($a)
	{
		return $a;
	}
	
	function RETURN_VALUE($a)
	{
		return $a;
	}
	function LOAD_NAME($a)
	{
		return $a;
	}
	function CALL_FUNCTION($a)
	{
		return $a;
	}
	function POP_TOP($a)
	{
		return $a;
	}
	
	$listFunction = array(
		'LOAD_CONST'=>'function(x){alert(x);}'
		,'STORE_NAME'=>'function(x){}'
		,'RETURN_VALUE'=>'function(x){}'
	);
	

	function js($out)
	{
		global $listFunction,$globData;
		$main = array();
		$bc = array();
		foreach($out as $k=>$v)
		{
			$name = $v[0];
			$arg1 = $v[4];
			$arg2 = $v[1];
			//if($arg == 'None') $main[] = $name(null);
			//else $main[] = $name($arg2);
			$name = addData($listFunction[$name]);
			$bc[] = addVar($name);
			
			$name = addData($arg1);
			$bc[] = addVar($name);
		}
		
		$len = count($bc);
		$main[] = ',q=['.implode(',',$bc).'],i=0;while(i<'.$len.')(o[q[i++]])(q[i++]);';
		return 'var o=['.implode(',',$globData).']'.implode('',$main);
	}
	
?>

<code><?php echo js($out);?></code>