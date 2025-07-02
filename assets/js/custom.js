"use strict";

/* input type customize */
function setInputFilter(textbox, inputFilter) {
	["input", "keydown", "keyup", "mousedown", "mouseup", "select", "contextmenu", "drop"].forEach(function (event) {
		if (textbox != null) {
			textbox.addEventListener(event, function () {
				if (inputFilter(this.value)) {
					this.oldValue = this.value;
					this.oldSelectionStart = this.selectionStart;
					this.oldSelectionEnd = this.selectionEnd;
				} else if (this.hasOwnProperty("oldValue")) {
					this.value = this.oldValue;
					this.setSelectionRange(this.oldSelectionStart, this.oldSelectionEnd);
				} else {
					this.value = "";
				}
			});
		}
	});
}


// Install input filters.
setInputFilter(document.getElementById("phone"), function (value) {
	return /^-?\d*$/.test(value);
});
setInputFilter(document.getElementById("tahun"), function (value) {
	return /^-?\d*$/.test(value);
});
setInputFilter(document.getElementById("harga"), function (value) {
	return /^-?\d*[.]?\d{0,2}$/.test(value);
});
setInputFilter(document.getElementById("banyak"), function (value) {
    return /^-?\d*[.]?\d{0,2}$/.test(value);
});

// setInputFilter(document.getElementById("citizen_number"), function (value) {
// 	return /^\d*$/.test(value) && (value === "" || parseInt(value).length() <= 16||parseInt(value).length() >= 1);
// });

setInputFilter(document.getElementById("floatTextBox"), function (value) {
	return /^-?\d*[.,]?\d*$/.test(value);
});
setInputFilter(document.getElementById("currencyTextBox"), function (value) {
	return /^-?\d*[.,]?\d{0,2}$/.test(value);
});
setInputFilter(document.getElementById("latinTextBox"), function (value) {
	return /^[a-z]*$/i.test(value);
});
setInputFilter(document.getElementById("hexTextBox"), function (value) {
	return /^[0-9a-f]*$/i.test(value);
});

/* npwp format */
// setInputFilter(document.getElementById("number_npwp"), function (value) {
// 	return /^[0-9]+$/.test(value);
// });
