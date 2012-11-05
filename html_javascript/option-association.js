function boxes_empty(box_options) {
	//for(i=0; i<box_options.length; i++) {    bug...... box_options.length will decrease with the iteration because its element is set to null.
	for(m=box_options.length-1; m>=0; m--) {
		box_options[m] = null; 
	}
}

function boxes_evaluation(box_options, group, type) {
	for(i=0; i<group[type].length; i++) {
		box_options[i] = new Option(group[type][i].text, group[type][i].value);
	}
}

function scen_box() {
	var scen_options = document.getElementById("scenario").options;
	var box_options = document.getElementById("test_box").options;
	
	var boxes = new Array();
	// init all the available options for test boxes
	boxes[0] = new Option("Auto Scheduling", "");
	boxes[1] = new Option("VT-NHM6", "VT-NHM6");
	boxes[2] = new Option("VT-DP5", "VT-DP5");
	boxes[3] = new Option("VT-SNB6", "VT-SNB6");
	
	// group[0] is 'all', group[1] is 'VT-d', group[2] is 'SR-IOV'.
	var type_count = 3;
	var all = 0;
	var vtd = 1;
	var sriov = 2;
	var group=new Array(type_count)
	for (i=0; i<type_count; i++) {
		group[i]=new Array();
	}
	for (i=0; i<boxes.length; i++) {
		group[all][i] = boxes[i];
	}
	group[vtd][0] = boxes[1];
	group[vtd][1] = boxes[2];
	group[sriov][0] = boxes[1];
	
	// set the type of the box group. (sriov, vtd, or all)
	var type = all;
	var scen_index = document.getElementById("scenario").selectedIndex;
	var scen_selected = scen_options[scen_index].value.toLowerCase()
	if ( scen_selected.indexOf("sriov") >= 0 || scen_selected.indexOf("nightly")  >=0 || scen_selected.indexOf("full") >= 0 )
		type = sriov;
	else if (scen_selected.indexOf("vtd") >= 0 )
		type = vtd;
	else
		type = all;
	
	boxes_empty(box_options)
	boxes_evaluation(box_options, group, type)
}

function load() {
	var scen_obj = document.getElementById("scenario");
	
	// Firefox or Chrome support addEventListener; IE supports attachEvent.
	if (scen_obj.addEventListener) {
		scen_obj.addEventListener("change", scen_box);
        }
	else {
		scen_obj.attachEvent("onchange", scen_box);
	}
}

if (window.addEventListener) {
	window.addEventListener('load', load);
} else if (window.attachEvent) {
	window.attachEvent('onload', load);
}