<?php
	exec("python translate.py test.py bytecode 2>&1",$out);
	$out = json_decode($out[0]);
	//print_r($out);exit;
?>
	<head>
		<!--<script src="json.js"></script>-->
		<meta http-equiv="Cache-Control" content="no-cache">
	</head>
	<body>
	<script>	
		var BCode = JSON.parse('<?php echo json_encode($out);?>');
		var chr=[],ord={};
		for(var i=0;256>i;i++)chr[i]=function(i){var r=String.fromCharCode;return r(0==i?65533:i>191?i+848:i>127?[1026,1027,8218,1107,8222,8230,8224,8225,8364,8240,1033,8249,1034,1036,1035,1039,1106,8216,8217,8220,8221,8226,8211,8212,152,8482,1113,8250,1114,1116,1115,1119,160,1038,1118,1032,164,1168,166,167,1025,169,1028,171,172,173,174,1031,176,177,1030,1110,1169,181,182,183,1105,8470,1108,187,1112,1029,1109,1111][i-128]:i)}(i),ord[chr[i]]=i;i=void 0;
		var stack = [],stackI = 0, selfFunction = null;
		
		
	var variableParent,used,opcode = [];
		opcode[1] = function()
		{
			--stackI;
		}

		opcode[2] = function() // ROT_TWO
		{
			a = stack[stackI-1];
			stack[stackI-1] = stack[stackI-2];
			stack[stackI-2] = a;
		}
		opcode[3] = function() // ROT_THREE
		{
			a = stack[stackI-1];
			stack[stackI-1] = stack[stackI-2];
			stack[stackI-2] = stack[stackI-3];
			stack[stackI-3] = a;
		}
		opcode[4] = function() // DUP_TOP
		{
			stack[stackI++] = stack[stackI-1];
		}
		opcode[5] = function() // ROT_FOUR
		{
			a = stack[stackI-1];
			stack[stackI-1] = stack[stackI-2];
			stack[stackI-2] = stack[stackI-3];
			stack[stackI-3] = stack[stackI-4];
			stack[stackI-4] = a;
		}
		opcode[9] = function(){} // NOP
		opcode[10] = function() // UNARY_POSITIVE
		{
			//stack[stackI-1] = stack[stackI-1];
		}
		opcode[11] = function() // UNARY_NEGATIVE
		{
			stack[stackI-1] = -stack[stackI-1];
		}
		opcode[12] = function() // UNARY_NOT
		{
			stack[stackI-1] = stack[stackI-1]?true:false;
		}
		opcode[15] = function() // UNARY_INVERT
		{
			stack[stackI-1] = ~stack[stackI-1];
		}
		opcode[19] = function() // BINARY_POWER
		{
			stack[--stackI-1] =  Math.pow(stack[stackI-1],stack[stackI]);
		}
		opcode[20] = function() // BINARY_MULTIPLY
		{
			stack[--stackI-1] =  stack[stackI-1]*stack[stackI];
		}
		opcode[22] = function() // BINARY_MODULO
		{
			stack[--stackI-1] =  stack[stackI-1]%stack[stackI];
		}
		opcode[23] = function() // BINARY_ADD
		{
			stack[--stackI-1] =  stack[stackI-1]+stack[stackI];
		}
		opcode[24] = function() // BINARY_SUBTRACT
		{
			stack[--stackI-1] =  stack[stackI-1]-stack[stackI];
		}
		opcode[25] = function() // BINARY_SUBSCR
		{
			stack[--stackI-1] =  stack[stackI-1][stack[stackI]];
		}
		opcode[26] = function() // BINARY_FLOOR_DIVIDE
		{
			stack[--stackI-1] =  +(Math.log(stack[stackI-1])/Math.log(stack[stackI])).toFixed(10);
		}
		opcode[27] = function() // BINARY_TRUE_DIVIDE
		{
			stack[--stackI-1] =  stack[stackI-1]/stack[stackI];
		}
		opcode[28] = function() // INPLACE_FLOOR_DIVIDE
		{
			stack[--stackI-1] =  +(Math.log(stack[stackI-1])/Math.log(stack[stackI])).toFixed(10);
		}
		opcode[29] = function() // INPLACE_TRUE_DIVIDE
		{
			stack[--stackI-1] =  stack[stackI-1]/stack[stackI];
		}
		opcode[54] = function() // store map
		{
			a1 = func.popVar();
			a2 = func.popVar();
			storeMap[a1] = a2;
			func.pushVar(storeMap);
		}
		opcode[55] = function() // a+=1 INPLACE_ADD
		{
			stack[--stackI-1] =  stack[stackI-1]+stack[stackI];
		}
		opcode[56] = function() // a-=1
		{
			stack[--stackI-1] =  stack[stackI-1]-stack[stackI];
		}
		opcode[57] = function() // INPLACE_MULTIPLY FAST
		{
			stack[--stackI-1] =  stack[stackI-1]*stack[stackI];
		}
		opcode[59] = function() // % INPLACE
		{
			stack[--stackI-1] =  stack[stackI-1]%stack[stackI];
		}
		opcode[60] = function() // 
		{
			var a,b;
			a = stack[--stackI];
			b = stack[--stackI];
			b[a] = stack[--stackI];
		}
		opcode[61] = function() // 
		{
			var a = stack[--stackI];
			delete stack[--stackI][a];
		}
		opcode[62] = function() // <<
		{
			a1 = func.popVar();
			a2 = func.popVar();
			func.pushVar(a2<<a1);
		}
		opcode[63] = function() // >>
		{
			a1 = func.popVar();
			a2 = func.popVar();
			func.pushVar(a2>>a1);
		}
		opcode[64] = function() // &
		{
			a1 = func.popVar();
			a2 = func.popVar();
			func.pushVar(a2&a1);
		}
		opcode[65] = function() // ^
		{
			a1 = func.popVar();
			a2 = func.popVar();
			func.pushVar(a2^a1);
		}
		opcode[66] = function() // |
		{
			a1 = func.popVar();
			a2 = func.popVar();
			func.pushVar(a2|a1);
		}
		opcode[67] = function() //** INPLACE
		{
			a1 = func.popVar();
			a2 = func.popVar();
			func.pushVar(Math.pow(a2,a1));
		}
		opcode[68] = function() // GET_ITER
		{
			a1 = func.popVar();
			func.pushForinter([0,a1.length,a1]);
		}
		opcode[71] = function() // LOAD_BUILD_CLASS
		{
			func.pushVar({});
			++func.count;
		}
		opcode[75] = function() // << INPLACE_LSHIFT
		{
			a1 = func.popVar();
			a2 = func.popVar();
			func.pushVar(a2<<a1);
		}
		opcode[76] = function() // >> INPLACE_RSHIFT
		{
			a1 = func.popVar();
			a2 = func.popVar();
			func.pushVar(a2>>a1);
		}
		opcode[77] = function() // & INPLACE
		{
			a1 = func.popVar();
			a2 = func.popVar();
			func.pushVar(a2&a1);
		}
		opcode[78] = function() //^ INPLACE
		{
			a1 = func.popVar();
			a2 = func.popVar();
			func.pushVar(a2^a1);
		}
		opcode[79] = function() // | INPLACE
		{
			a1 = func.popVar();
			a2 = func.popVar();
			func.pushVar(a2|a1);
		}
		opcode[80] = function() // BREAK_LOOP
		{
			func.count = func.popBreak();
			func.popForinter();
		}
		opcode[83] = function() // return
		{
			used.return = stack[--stackI];
			used.i = used.l;
		}
		opcode[87] = function() // POP_BLOCK
		{
			func.popBreak();
		}
		opcode[90] = function() // STORE_NAME
		{
			globalData[used.name[used.code[used.i]]] = stack[--stackI];
		}
		opcode[91] = function() // DELETE_NAME
		{
			delete variables[name[getInt()]];
		}
		opcode[92] = function() // UNPACK_SEQUENCE array
		{
			func.getInt();
			a1 = func.popVar();
			a2 = a1.length;
			while(a2) func.pushVar(a1[--a2]);
		}
		opcode[93] = function() // FOR_ITER
		{
			a1 = func.getInt();
			a = func.popForinter();
			a2 = a[0];
			a3 = a[1];
			a4 = a[2];
			func.pushVar(a4[a2++]);
			if(a2>a3) func.count = a1+func.count;
			else func.pushForinter([a2,a3,a4]);
		}
		opcode[95] = function() // Store attr
		{
			var a = stack[--stackI];
			a[used.name[used.code[used.i]]] = stack[--stackI];
		}
		opcode[97] = function() // STORE_GLOBAL
		{
			func.setVariableGlobal(func.popVar());
		}
		opcode[100] = function() // LOAD_CONST
		{
			stack[stackI++] = used.cnst[used.code[used.i]];
		}
		opcode[101] = function() // LOAD_NAME
		{
			//stack[stackI++] = func.getVariable());
			stack[stackI++] = globalData[used.name[used.code[used.i]]];
		}
		opcode[102] = function() // BUILD_TUPLE
		{
			a1 = func.getInt();
			a2 = [];
			while(a1--) a2[a1] = func.popVar();
			func.pushVar(a2);
		}
		opcode[103] = function() // GEN Array
		{
			var count = used.code[used.i],array = [];
			while(--count>=0) array[count] = stack[--stackI];
			array.append = function(str)
			{
				array[array.length] = str;
			}
			stack[stackI++] = array;
		}
		opcode[105] = function() //
		{
			func.getInt();
			func.pushVar({});
		}
		opcode[106] = function() // LOAD_ATTR
		{
			used.context = stack[stackI-1];
			stack[stackI-1] = used.context[used.name[used.code[used.i]]];
		}
		opcode[107] = function() // compare
		{
			a1 = func.popVar();
			a2 = func.popVar();
			switch(func.getInt())
			{
				case 0: // <
					func.pushVar(a2<a1);
				break;
				case 1: // <=
					func.pushVar(a2<=a1);
				break;
				case 2: // ==
					func.pushVar(a2==a1);
				break;
				case 3: // ==
					func.pushVar(a2!=a1);
				break;
				case 4: // >
					func.pushVar(a2>a1);
				break;
				case 5: // >=
					func.pushVar(a2>=a1);
				break;
				case 6: // in
					func.pushVar(a1.indexOf(a2) != -1)
				break;
				default:
					func.pushVar(false);
				break;
			}
		}
		opcode[108] = function() // IMPORT_NAME
		{
			a1 = func.popVar();
			a2 = func.popVar();
			func.pushVar(func.importLibrary(func.getName(),a1,a2));
		}
		opcode[109] = function() // IMPORT_FROM
		{
			func.pushVar(func.importLibraryFrom(func.getName()));
		}
		opcode[110] = function() // JUMP_FORWARD
		{
			a1 = func.getInt();
			func.count+=a1;
		}
		opcode[113] = function() // JUMP_ABSOLUTE
		{
			func.count = func.getInt();
		}
		opcode[114] = function() // POP_JUMP_IF_FALSE
		{
			a1 = func.getInt();
			if(!func.popVar()) func.count = a1;
		}
		opcode[116] = function() // LOAD_GLOBAL
		{
			func.pushVar(func.getVariable());
		}
		opcode[120] = function() // SETUP_LOOP
		{
			a1 = func.getInt();
			func.pushBreak(a1+func.count);
		}
		opcode[124] = function() // LOAD_FAST
		{
			func.pushVar(func.getVariableLocal());
		}
		opcode[125] = function() // STORE_FAST
		{
			func.setVariableLocal(func.popVar());
		}
		opcode[131] = function() // CALL_FUNCTION
		{
			var arg = used.code[used.i],args = [],f;
			while(arg) args[--arg] = stack[--stackI];
			f = stack[--stackI];
			switch(typeof f)
			{
				case 'object': // object bytecode python
					return exec(f,args);
				break;
				case 'function': // function execute
					return f.apply(this.context,args);
				break;
				case 'undefined': // function undefined
					console.error("Функция не существует! Position:"+used.i);
					used.i = used.l;
					return f;
				break;
			}
		}
		opcode[132] = function() // MAKE_FUNCTION
		{
			func.makeFunction();
		}
		opcode[133] = function() // BUILD_SLICE
		{
			switch(func.getInt())
			{
				case 2:
					a1 = func.popVar();
					a2 = func.popVar();
					a3 = func.popVar();
					
					func.pushVar([func.sub1(a3,a2,a1)]);
					func.pushVar(0);
				break;
				case 3:
					a1 = func.popVar();
					a2 = func.popVar();
					a3 = func.popVar();
					a4 = func.popVar();
					func.pushVar([func.sub2(a4,a3,a2,a1)]);
					func.pushVar(0);
				break;
			}
		}
		opcode[135] = function() // LOAD_CLOSURE
		{
			alert(func.getInt());
		}
		opcode[136] = function() // LOAD_DEREF
		{
			func.pushVar(variableParent[func.getInt()]);
			
		}
		opcode[137] = function() // STORE_DEREF
		{
			variableParent[func.getInt()] = func.popVar();
		}
		
		var globalData = 
		{
			'sleep':function(x)
			{
				var o,i = selfFunction[1].count;
				selfFunction[1].count = selfFunction[1].len;
				setTimeout(function()
				{
					
					selfFunction[1].count = i;
					o = selfFunction[0];
					o();
				},x);
			}
			,'alert':function(arg)
			{
				alert(arg);
			}
			,'input':function(txt)
			{
				if(txt==undefined) txt='';
				return prompt('Input:', txt);
			}
			,'log':function(txt)
			{
				console.log(txt);
			}
			,'print':function(txt)
			{
				var l = arguments.length, i = 0;
				while(i<l) document.write(arguments[i++]);	
			}
			,'int':function(i)
			{
				return parseInt(i);
			}
			,'len':function(str)
			{
				return str.length;
			}
			,'range':function(start,stop,step)
			{
				var array = [],i=0,x;
				if(start==undefined)start = 0;
				if(stop==undefined)stop = 0;
				if(step==undefined)step = 1;
				switch(arguments.length)
				{
					case 1:
						while(i<start) array[i] = i++;
						return array;
					break;
					case 2:
						while(start<stop) array[i++] = start++;
						return array;
					break;
					case 3:
						while(start<stop) 
						{
							array[i++] = start;
							start+=step;
						}
						return array;
					break;
				}
			}
			,'window':window
			,'chr':function(x){return chr[x];}
			,'ord':function(x){return ord[x];}
			,'Function':function(binary)
			{
				return function()
				{
					return exec(binary,arguments);
				}
			}
		};
		function exec(BCode,args)
		{
			if(args==undefined) args = [];
			if(BCode == null)
			{
				console.error("Error compile program!");
				return false;
			}
			var local = BCode[3],code = BCode[2], variablesLocal = {}, varLocI = 0, ret;
			while(varLocI < args.length)variablesLocal[local[varLocI]] = args[varLocI++];
			var arrayLocal = [],arrayLocalI = 0;
			
			function pushVarLocal(v)
			{
				arrayLocal[arrayLocalI++] = v;
			}
		
			function popVarLocal()
			{
				if(--arrayLocalI<0) arrayLocalI = 0;
				return arrayLocal[arrayLocalI];
			}
			var o = 
			{
				i:0
				,l:code.length
				,code:code
				,local:local
				,name:BCode[0]
				,cnst:BCode[1]
				,context:null
				,return:undefined
			};
			var a1,a2,backUsed = used?used:o;
			used = o;
			function runOpcode()
			{
				while(o.i < o.l)
				{
					a1 = code[o.i];
					a2 = opcode[a1];
					if(a2 == undefined)
					{
						a2 = a1.toString(16);
						if(a2.length<2) a2 = '0' + a2;
						console.error("Error opcode `0x"+a2+"/"+a1+"`, index opcode `"+o.i+"`!");
						return null;
					}
					++o.i;
					a2();
					++o.i;
				}
			}
			//selfFunction = [runOpcode,o];
			runOpcode();
			used = backUsed;
			return used.return;
		}
		
		window.onload = function()
		{
			var runtime = Date.now();
			exec(BCode);
			console.log("Runtime: "+(Date.now() - runtime)+" ms");
		}
	</script>
	</body>
