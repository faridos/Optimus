
 window.onload = function ()	 {
  var inputTest = document.getElementById('input-test');
	var selectTest = document.getElementById('select-test');
    
	inputTest.onkeydown = function (e) {
		var key = e.keyCode || e.which;
		if (key == 13) {
			selectTest.focus();
		}

		if (key == 40 || key == 38) {
			selectTest.focus();
			dropDown('select-test');
		}
	};

	selectTest.onchange = function(e) {
		inputTest.value = selectTest.options[selectTest.selectedIndex].text;
		inputTest.focus();
	};
};

 	var dropDown = function (elementId) {
        var dropdown = document.getElementById(elementId);
        try {
            showDropdown(dropdown);
        } catch(e) {
        	console.log(e);
        }
        return false;
    };

    var showDropdown = function (element) {
        var event;
        event = document.createEvent('MouseEvents');
        event.initMouseEvent('mousedown', true, true, window);
        element.dispatchEvent(event);
    };