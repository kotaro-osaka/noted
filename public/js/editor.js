const titleInput = document.getElementById('title');
const contentInput = document.getElementById('content');

let noteId = new URLSearchParams(window.location.search).get('id'); // Read id param from current url
let saveTimer = null;

// Debounce: wait 1s after last keystroke before saving
function scheduleSave() {
	clearTimeout(saveTimer);
	saveTimer = setTimeout(save, 1000);
}

async function save() {
	const title = titleInput.value.trim() || 'Untitled';
	const content = contentInput.value;

	const response = await fetch('/api/notes/save.php', {
		method: 'POST',
		headers: { 'Content-Type': 'application/json' },
		body: JSON.stringify({ id: noteId, title, content })
	});

	const data = await response.json();

	// Update note id if new note was created
	if (!noteId && data.id) {
		noteId = data.id;
		history.replaceState(null, '', `?id=${noteId}`); // Change browser url without reloading page
	}
}

// Save before navigating back
document.querySelector('header a').addEventListener('click', async (e) => {
	e.preventDefault(); // Prevent instant default navigation
	clearTimeout(saveTimer);
	await save();
	window.location.href = '/notes.php';
});

titleInput.addEventListener('input', scheduleSave);
contentInput.addEventListener('input', scheduleSave);