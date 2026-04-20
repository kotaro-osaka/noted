const menuBtn = document.getElementById('menu-btn');
const dropdown = document.getElementById('dropdown');

menuBtn.addEventListener('click', () => {
	dropdown.classList.toggle('open');
});

document.addEventListener('click', (e) => {
	if (!menuBtn.contains(e.target) && !dropdown.contains(e.target)) {
		dropdown.classList.remove('open');
	}
});