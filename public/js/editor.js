const titleInput = document.getElementById("title");
const contentInput = document.getElementById("content");

let noteId = new URLSearchParams(window.location.search).get("id"); // Read id param from current url
let saveTimer = null;

// Debounce: wait 1s after last keystroke before saving
function scheduleSave() {
    clearTimeout(saveTimer);
    saveTimer = setTimeout(save, 1000);
}

async function save() {
    const title = titleInput.value.trim() || "Untitled";
    const content = contentInput.value;

    const response = await fetch("/api/notes/save.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ id: noteId, title, content }),
    });

    const data = await response.json();

    // Update note id if new note was created
    if (!noteId && data.id) {
        noteId = data.id;
        history.replaceState(null, "", `?id=${noteId}`); // Change browser url without reloading page
    }
}

// Save before navigating back
document.querySelector("header a").addEventListener("click", async (e) => {
    e.preventDefault(); // Prevent instant default navigation
    clearTimeout(saveTimer);
    await save();
    window.location.href = "/notes.php";
});

titleInput.addEventListener("input", scheduleSave);
contentInput.addEventListener("input", scheduleSave);

const confirmBtn = document.getElementById("confirm-btn");

titleInput.addEventListener("focus", () => (confirmBtn.style.display = "flex"));
contentInput.addEventListener(
    "focus",
    () => (confirmBtn.style.display = "flex"),
);

confirmBtn.addEventListener("click", () => {
    titleInput.blur();
    contentInput.blur();
    confirmBtn.style.display = "none";
});

// Markdown parser
function parseMarkdown(text) {
    return text
        .replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;") // Escape special HTML characters to prevent XSS
        .replace(/^### (.+)$/gm, "<h3>$1</h3>") // ### Heading -> <h3>
        .replace(/^## (.+)$/gm, "<h2>$1</h2>") // ## Heading -> <h2>
        .replace(/^# (.+)$/gm, "<h1>$1</h1>") // # Heading -> <h1>
        .replace(/\*\*(.+?)\*\*/g, "<strong>$1</strong>") // **bold** -> <strong>
        .replace(/\*(.+?)\*/g, "<em>$1</em>") // *italic* -> <em>
        .replace(/`(.+?)`/g, "<code>$1</code>") // `code` -> <code>
        .replace(/\n/g, "<br>"); // Newlines -> <br>
}

// Tab switching
const tabWrite = document.getElementById('tab-write');
const tabPreview = document.getElementById('tab-preview');
const preview = document.getElementById('preview');

// Switch to write mode
tabWrite.addEventListener('click', () => {
	tabWrite.classList.add('active');
	tabPreview.classList.remove('active');
	contentInput.style.display = 'block'; // Show textarea
	preview.style.display = 'none'; // Hide preview
});

// Switch to preview mode - parse & render md
tabPreview.addEventListener('click', () => {
	tabPreview.classList.add('active');
	tabWrite.classList.remove('active');
	contentInput.style.display = 'none'; // Hide textarea
	preview.style.display = 'block'; // Show preview
	preview.innerHTML = parseMarkdown(contentInput.value); // Render md
})