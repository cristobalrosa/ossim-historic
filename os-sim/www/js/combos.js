	//
	// COMBO functions
	//
	// delete selected options in a combo
	function deletefrom (mysel) {
		var delems = [];
		var myselect=document.getElementById(mysel)
		for (var i=0; i<myselect.options.length; i++)
			if (myselect.options[i].selected==true) {
				delems.push(i);
				myselect.options[i].selected=false;
			}
		for (var i=delems.length-1; i>=0; i--)
			myselect.remove(delems[i])
	}
	// add element to a combo
	function addto (mysel,txt,val) {
		if (val==null) val=txt
		if (!exists_in_combo(mysel,txt,val)) {
			var elOptNew = document.createElement('option');
			elOptNew.text = txt
			elOptNew.value = val
			try {
				document.getElementById(mysel).add(elOptNew, null); // standards compliant; doesn't work in IE
			}
			catch(ex) {
				document.getElementById(mysel).add(elOptNew); // IE only
			}
		}
	}
	// exist txt,val in combo mysel
	function exists_in_combo(mysel,txt,val) {
		var myselect=document.getElementById(mysel)
		for (var i=0; i<myselect.options.length; i++)
			if (myselect.options[i].value==val && myselect.options[i].text==txt)
				return true;
		return false;
	}
	// select all elements of a multiselect combo
	function selectall (mysel) {
		var myselect=document.getElementById(mysel)
		for (var i=0; i<myselect.options.length; i++)
			myselect.options[i].selected=true;
	}
	// return all combo elements
	function getcombotext (mysel) {
		var elems = [];
		var myselect=document.getElementById(mysel)
		for (var i=0; i<myselect.options.length; i++)
			elems.push(myselect.options[i].text);
		return elems;
	}
	// return all selected combo elements
	function getselectedcombotext (mysel) {
		var elems = [];
		var myselect=document.getElementById(mysel)
		for (var i=0; i<myselect.options.length; i++)
			if (myselect.options[i].selected==true)
				elems.push(myselect.options[i].text);
		return elems;
	}
