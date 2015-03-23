/******************************************************************************* 
						========================
						Software Guide Quicktags  
						========================
--------------------------------------------------------------------------------
Version: 	1.0
Author:		Michael Wöhrer, http://www.sw-guide.de
Based on: 	JS QuickTags version 1.2, Copyright (c) 2002-2005 Alex King
			http://alexking.org/index.php?content=software/javascript/content.php
Licensed: 	Under the LGPL license, http://www.gnu.org/copyleft/lesser.html
--------------------------------------------------------------------------------
Purpose:
    This JavaScript will insert the tags below at the cursor position in IE and 
    Gecko-based browsers (Mozilla, Camino, Firefox, Netscape). For browsers that 
    do not support inserting at the cursor position (Safari, OmniWeb) it appends
    the tags to the end of the content.
--------------------------------------------------------------------------------
This program is distributed in the hope that it will be useful, but without any 
warranty; without even the implied warranty of merchantability or fitness for a 
particular purpose. 
*******************************************************************************/

/*******************************************************************************
 * Some needed variables
 ******************************************************************************/
var qtButtons = new Array();
var qtOpenTags = new Array();



/*******************************************************************************
 *  --------   CONFIGURATION ----------
 ******************************************************************************/
	
	/*===============================================
	PART 1: Modifying height of textarea field.
		2 buttons are provided to modify the height of the textarea.
		Below you can change the default values. Each value sets the number
		of lines. Please note that Firefox always adds one extra line, so if
		you enter for example "10", Firefox will display 11 lines...
	================================================= */
	var height_max = 30;	// Maximum height 
	var height_min = 5;		// Minimum height
	var height_step = 5;	// How many lines being increased/decreased per button click
	
	
	
	/*===============================================
	PART 2: Define buttons. 
		The "function qtButton" shows the properties you have.
		Feel free to change the order the buttons by moving the
		"qtButtons.push()" up or down.  
	================================================= */
	function qtButton(id, display, tagStart, tagEnd, title, access, open) {
		this.id = id;				// used to name the toolbar button
		this.display = display;		// label on button
		this.tagStart = tagStart; 	// open tag
		this.tagEnd = tagEnd;		// close tag
		this.title = title;			// title...
		this.access = access;		// key access via alt + ...
		this.open = open;			// set to -1 if tag does not need to be closed
	}
	
	qtButtons.push(
		new qtButton('qt_bold', 'Fett', '<strong>' ,'</strong>' ,'Fettschrift', 'b')
	);
	qtButtons.push(
		new qtButton('qt_italic', 'Kursiv', '<em>', '</em>' ,'Kursivschrift', 'k')
	);
	qtButtons.push(
		new qtButton('qt_block', 'Zitat', '<blockquote>', '</blockquote>' ,'Zitieren', 'z')
	);
	qtButtons.push(
		new qtButton('qt_code', 'Code', '<code lang="php">', '</code>' ,'Code', 'c')
	);
	qtButtons.push( // Special button: LINK
		new qtButton('qt_link', 'Link', '', '</a>' ,'Link einf&uuml;gen', 'l')
	);
	/*
	qtButtons.push( // Special button: CLOSE TAGS
		new qtButton('qt_close', 'Tags schliessen', '', '' ,'Alle Tags schliessen', 'l', -1)
	);
	*/
	qtButtons.push( // Special button: INCREASE TEXTAREA HEIGHT
		new qtButton('qt_increase', '+', '', '' ,'Textfeld vergr&ouml;&szlig;ern', 'l', -1)
	);
	qtButtons.push( // Special button: DECREASE TEXTAREA HEIGHT
		new qtButton('qt_decrease', '-', '', '' ,'Textfeld verkleinern', 'l', -1)
	);
/*******************************************************************************
 *  --------   END OF CONFIGURATION ----------
 ******************************************************************************/


/*******************************************************************************
 * Main function to display the quicktags
 ******************************************************************************/
function displayQuicktags(textareaID) {

	textarea_id = textareaID;

	for (i = 0; i < qtButtons.length; i++) {
		qtShowButton(qtButtons[i], i);
	}
}


/*******************************************************************************
 * Auxiliary function: Change height of textarea
 ******************************************************************************/
function qtTextareaSize(type) {

	var height_current = document.getElementById(textarea_id).rows;

	switch (type) {
		case 'increase':
			if ( (height_current + height_step) > height_max ) {
				var my_heightchange = height_max;
			} else {
				var my_heightchange = height_current + height_step;
			}
			break;
		case 'decrease':
			if ( (height_current - height_step) < height_min ) {
				var my_heightchange = height_min;
			} else {
				var my_heightchange = height_current - height_step;
			}
			break;
		default:
			break;
	}
	document.getElementById(textarea_id).rows = my_heightchange;
}



/*******************************************************************************
 * Auxiliary function: Insert Link
 ******************************************************************************/
function qtInsertLink(myField, i, defaultValue) {
	if (!defaultValue) {
		defaultValue = 'http://';
	}
	if (!qtCheckOpenTags(i)) {
		var URL = prompt('URL:' ,defaultValue);
		if (URL) {
			qtButtons[i].tagStart = '<a href="' + URL + '">';
			qtInsertTag(myField, i);
		}
	}
	else {
		qtInsertTag(myField, i);
	}
}

/*******************************************************************************
 * Auxiliary function: Display buttons
 ******************************************************************************/
function qtShowButton(button, i) {

	// Add access key if variable is set
	if (button.access) {
		var accesskey = ' accesskey = "' + button.access + '"'
	}
	else {
		var accesskey = '';
	}

	// Add title key if variable is set
	if (button.title) {
		var titlevalue = ' title = "' + button.title + '"'
	}
	else {
		var titlevalue = '';
	}

	// Display button
	switch (button.id) {
		case 'qt_close': // close tags
			document.write('<input type="button" id="' + button.id + '" ' + accesskey + titlevalue + ' class="qt_button" onclick="qtCloseAllTags();" value="' + button.display + '" />');
			break;
		case 'qt_link': // link
			document.write('<input type="button" id="' + button.id + '" ' + accesskey + titlevalue + ' class="qt_button" onclick="qtInsertLink(document.getElementById(textarea_id), ' + i + ');" value="' + button.display + '" />');
			break;
		case 'qt_increase': // increase textarea size
			document.write('<input type="button" id="' + button.id + '" ' + accesskey + titlevalue + ' class="qt_button" onclick="qtTextareaSize(\'increase\');" value="' + button.display + '" />');
			break;
		case 'qt_decrease': // decrease textarea size
			document.write('<input type="button" id="' + button.id + '" ' + accesskey + titlevalue + ' class="qt_button" onclick="qtTextareaSize(\'decrease\');" value="' + button.display + '" />');
			break;
		default:
			document.write('<input type="button" id="' + button.id + '" ' + accesskey + titlevalue + ' class="qt_button" onclick="qtInsertTag(document.getElementById(textarea_id), ' + i + ');" value="' + button.display + '" />');
			break;
	}
}

/*******************************************************************************
 * Auxiliary function: Add Tag
 ******************************************************************************/
function qtAddTag(button) {
	if (qtButtons[button].tagEnd != '') {
		qtOpenTags[qtOpenTags.length] = button;
		document.getElementById(qtButtons[button].id).value = '/' + document.getElementById(qtButtons[button].id).value;
	}
}

/*******************************************************************************
 * Auxiliary function: Remove Tag
 ******************************************************************************/
function qtRemoveTag(button) {
	for (i = 0; i < qtOpenTags.length; i++) {
		if (qtOpenTags[i] == button) {
			qtOpenTags.splice(i, 1);
			document.getElementById(qtButtons[button].id).value = document.getElementById(qtButtons[button].id).value.replace('/', '');
		}
	}
}

/*******************************************************************************
 * Auxiliary function: Check open tags
 ******************************************************************************/
function qtCheckOpenTags(button) {
	var tag = 0;
	for (i = 0; i < qtOpenTags.length; i++) {
		if (qtOpenTags[i] == button) {
			tag++;
		}
	}
	if (tag > 0) {
		return true; // tag found
	}
	else {
		return false; // tag not found
	}
}	

/*******************************************************************************
 * Auxiliary function: Close all tags
 ******************************************************************************/
function qtCloseAllTags() {
	var count = qtOpenTags.length;
	for (o = 0; o < count; o++) {
		qtInsertTag(document.getElementById(textarea_id), qtOpenTags[qtOpenTags.length - 1]);
	}
}

/*******************************************************************************
 Insert Code functions
 ******************************************************************************/
function qtInsertTag(myField, i) {
	// IE support
	if (document.selection) {
		myField.focus();
	    sel = document.selection.createRange();
		if (sel.text.length > 0) {
			sel.text = qtButtons[i].tagStart + sel.text + qtButtons[i].tagEnd;
		}
		else {
			if (!qtCheckOpenTags(i) || qtButtons[i].tagEnd == '') {
				sel.text = qtButtons[i].tagStart;
				qtAddTag(i);
			}
			else {
				sel.text = qtButtons[i].tagEnd;
				qtRemoveTag(i);
			}
		}
		myField.focus();
	}
	// MOZILLA/NETSCAPE support
	else if (myField.selectionStart || myField.selectionStart == '0') {
		var startPos = myField.selectionStart;
		var endPos = myField.selectionEnd;
		var cursorPos = endPos;
		var scrollTop = myField.scrollTop;
		if (startPos != endPos) {
			myField.value = myField.value.substring(0, startPos)
			              + qtButtons[i].tagStart
			              + myField.value.substring(startPos, endPos) 
			              + qtButtons[i].tagEnd
			              + myField.value.substring(endPos, myField.value.length);
			cursorPos += qtButtons[i].tagStart.length + qtButtons[i].tagEnd.length;
		}
		else {
			if (!qtCheckOpenTags(i) || qtButtons[i].tagEnd == '') {
				myField.value = myField.value.substring(0, startPos) 
				              + qtButtons[i].tagStart
				              + myField.value.substring(endPos, myField.value.length);
				qtAddTag(i);
				cursorPos = startPos + qtButtons[i].tagStart.length;
			}
			else {
				myField.value = myField.value.substring(0, startPos) 
				              + qtButtons[i].tagEnd
				              + myField.value.substring(endPos, myField.value.length);
				qtRemoveTag(i);
				cursorPos = startPos + qtButtons[i].tagEnd.length;
			}
		}
		myField.focus();
		myField.selectionStart = cursorPos;
		myField.selectionEnd = cursorPos;
		myField.scrollTop = scrollTop;
	}
	else {
		if (!qtCheckOpenTags(i) || qtButtons[i].tagEnd == '') {
			myField.value += qtButtons[i].tagStart;
			qtAddTag(i);
		}
		else {
			myField.value += qtButtons[i].tagEnd;
			qtRemoveTag(i);
		}
		myField.focus();
	}
}

function qtInsertContent(myField, myValue) {
	// IE support
	if (document.selection) {
		myField.focus();
		sel = document.selection.createRange();
		sel.text = myValue;
		myField.focus();
	}
	// MOZILLA/NETSCAPE support
	else if (myField.selectionStart || myField.selectionStart == '0') {
		var startPos = myField.selectionStart;
		var endPos = myField.selectionEnd;
		var scrollTop = myField.scrollTop;
		myField.value = myField.value.substring(0, startPos)
		              + myValue 
                      + myField.value.substring(endPos, myField.value.length);
		myField.focus();
		myField.selectionStart = startPos + myValue.length;
		myField.selectionEnd = startPos + myValue.length;
		myField.scrollTop = scrollTop;
	} else {
		myField.value += myValue;
		myField.focus();
	}
}
