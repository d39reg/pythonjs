function getOpcode(func)
{
	var opcode = [];

	opcode[1] = function()
	{
		func.popVar();
		func.count++;
	}

	opcode[2] = function() // ROT_TWO
	{
		a1 = func.popVar();
		a2 = func.popVar();
		func.pushVar(a1);
		func.pushVar(a2);
	}
	opcode[3] = function() // ROT_THREE
	{
		a1 = func.popVar();
		a2 = func.popVar();
		a3 = func.popVar();
		func.pushVar(a1);
		func.pushVar(a3);
		func.pushVar(a2);
	}
	opcode[9] = function(){} // NOP
	opcode[10] = function() // UNARY_POSITIVE
	{
		func.pushVar(func.popVar());
	}
	opcode[11] = function() // UNARY_NEGATIVE
	{
		func.pushVar(-func.popVar());
	}
	opcode[15] = function() // 
	{
		func.pushVar(~func.popVar());
	}
	opcode[19] = function() // 
	{
		a1 = func.popVar();
		a2 = func.popVar();
		func.pushVar(Math.pow(a2,a1));
	}
	opcode[20] = function() // 
	{
		a1 = func.popVar();
		a2 = func.popVar();
		func.pushVar(a2*a1);
	}
	opcode[22] = function() // 
	{
		a1 = func.popVar();
		a2 = func.popVar();
		func.pushVar(a2%a1);
	}
	opcode[23] = function() // 
	{
		a1 = func.popVar();
		a2 = func.popVar();
		func.pushVar(a2+a1);
		++func.count;
	}
	opcode[24] = function() // 
	{
		a1 = func.popVar();
		a2 = func.popVar();
		func.pushVar(a2-a1);
	}
	opcode[25] = function() // 
	{
		a1 = func.popVar();
		a2 = func.popVar();
		func.pushVar(a2[a1]);
	}
	opcode[26] = function() // 
	{
		a1 = func.popVar();
		a2 = func.popVar();
		func.pushVar(parseInt(a2/a1));
	}
	opcode[27] = function() // 
	{
		a1 = func.popVar();
		a2 = func.popVar();
		func.pushVar(a2/a1);
	}
	opcode[28] = function() // INPLACE
	{
		a1 = func.popVar();
		a2 = func.popVar();
		func.pushVar(parseInt(a2/a1));
	}
	opcode[29] = function() // INPLACE_TRUE_DIVIDE
	{
		a1 = func.popVar();
		a2 = func.popVar();
		func.pushVar(a2/a1);
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
		a1 = func.popVar();
		a2 = func.popVar();
		func.pushVar(a2+a1);
	}
	opcode[56] = function() // a-=1
	{
		a1 = func.popVar();
		a2 = func.popVar();
		func.pushVar(a2-a1);
	}
	opcode[57] = function() // INPLACE_MULTIPLY FAST
	{
		a1 = func.popVar();
		a2 = func.popVar();
		func.pushVar(a2*a1);
	}
	opcode[59] = function() // % INPLACE
	{
		a1 = func.popVar();
		a2 = func.popVar();
		func.pushVar(a2%a1);
	}
	opcode[60] = function() // 
	{
		a1 = func.popVar();
		a2 = func.popVar();
		a2[a1] = func.popVar();
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
		func.ret = func.popVar();
		func.count = func.len;
	}
	opcode[87] = function() // POP_BLOCK
	{
		func.popBreak();
	}
	opcode[90] = function() // STORE_NAME
	{
		func.setVariable(func.popVar());
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
		a1 = func.popVar();
		a1[func.getName()] = func.popVar();
	}
	opcode[97] = function() // STORE_GLOBAL
	{
		func.setVariableGlobal(func.popVar());
	}
	opcode[100] = function() // LOAD_CONST
	{
		func.pushVar(func.getConst());
	}
	opcode[101] = function() // LOAD_NAME
	{
		func.pushVar(func.getVariable());
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
		var count = func.getInt();
		var array = [];
		while(--count>=0) array[count] = func.popVar();
		array.append = function(str)
		{
			array[array.length] = str;
		}
		func.pushVar(array);
	}
	opcode[105] = function() //
	{
		func.getInt();
		func.pushVar({});
	}
	opcode[106] = function() // LOAD_ATTR
	{
		func.context = func.popVar();
		func.pushVar(func.context[func.getName()]);
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
		func.callFunction();
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
	return opcode;
}
