

function manageClass(idMenu,classToChange) {

	var elem = document.getElementById(idMenu);

	if (elem.classList.contains(classToChange)) {
		elem.classList.remove(classToChange);
	} else {
		elem.classList.add(classToChange);
	}
}

window.onresize = resizeMenu;

function resizeMenu() {
	var menu = document.getElementById('menu');
	if(window.innerWidth >= 768) {
		menu.classList.remove('position-absolute');
	}
	if (window.innerWidth < 768) {
		menu.classList.add('position-absolute');
	}
}


function showHint(str) {
    if (str.length == 0) {
        document.getElementById("txtHint").innerHTML = "";
        return;
    } else {
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("txtHint").innerHTML = this.responseText;
            }
        };
        xmlhttp.open("GET", "index.php?controller=medicamentslist&pPresentation=" + str, true);
        xmlhttp.send();
    }
}

function add(str, code , instruction = "") {
	document.getElementById("libelle").innerHTML = str;
	document.getElementById("code").value = code;
	document.getElementById("instruction").value = instruction;
}