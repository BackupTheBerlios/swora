function open_newomsg(userid) {
	window.open("index.php?newom=1&userid="+userid,"OnlineNachrichten","width=500,height=210,directories=no,status=no,scrollbars=no,resize=no,menubar=no");
}

function openuserprofil(userid) {
	window.open("index.php?section=user&show=user&stopheaderoutput_main=1&userid="+userid,"MitgliederProfil","width=640,height=350,directories=no,status=no,scrollbars=yes,resize=no,menubar=no");
}

function openugb(userid) {
	window.open("index.php?section=user&action=ugb&stopheaderoutput_main=1&uid="+userid,"MitgliederGästeBuch","width=450,height=350,directories=no,status=no,scrollbars=yes,resize=no,menubar=no");
}

function open_help(str) {
	window.open("index.php?section=help&info="+str,"Hilfe","width=400,height=200,directories=no,status=no,scrollbars=yes,resize=no,menubar=no");
}

function setboxvalue(f,select) {
	var count = f.elements.length;
	for(var i=0;i<count;i++) {
		if(f.elements[i].type=="checkbox") {
			if(select==1) {f.elements[i].checked=true;}
			if(select==2) {f.elements[i].checked=false;}
			if(select==3) {
				if(f.elements[i].checked==false) {f.elements[i].checked=true;}
				else {f.elements[i].checked=false;}
			}
		}
	}
}
