function showMenu(classMenu) {

	var menu = document.getElementsByClassName(classMenu);

	if (menu[0].style.display == "block") {
		menu[0].style.display = "none";
	} else {
		menu[0].style.display = "block";
	}
}