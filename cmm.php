<?php
	exec("python translate.py test.py json 2>&1",$out);
	$out = json_decode($out[0]);
	

	function LOAD_CONST($a)
	{
		if($a==null)return 0;
		return '"'.$a.'"';
	}
	
	function STORE_NAME($a)
	{
		return '#'.$a;
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

	

	function c($out)
	{
		global $listFunction,$globData;
		$main = array();
		$bc = array();
		foreach($out as $k=>$v)
		{
			$name = $v[0];
			$arg1 = $v[4];
			$arg2 = $v[1];
			$nameType = $name;
			
			if($arg1 == 'None') $arg1 = 0;
			if(gettype($arg2)=='string')
			{
				if($arg1[0]=='"'||$arg1[0]=='\'') 
				{
					$arg1='"'.$arg2.'"';
					if($name=='LOAD_CONST') $nameType = 'LOAD_CONST_STRING';
				}
				else if($arg1!='')$arg1='#'.$arg2;
			}
			elseif($name=='LOAD_CONST') $nameType = 'LOAD_CONST_INT';
			//if($arg == 'None') $o = $name(null);
			//else $o = $name($arg2);
			$main[] = "$nameType($arg1);";
		
		}
		print_r($main);
	}
	
?>

<code><?php echo c($out);?></code>