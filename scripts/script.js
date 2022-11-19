

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