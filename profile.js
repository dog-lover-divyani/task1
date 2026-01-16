const resumeInput = document.getElementById("resume");
const fileName = document.getElementById("fileName");
const form = document.getElementById("profileForm");

resumeInput.addEventListener("change", () => {
    if (resumeInput.files.length > 0) {
        fileName.textContent = resumeInput.files[0].name;
    } else {
        fileName.textContent = "No file selected";
    }
});

form.addEventListener("submit", (e) => {
    e.preventDefault();
    alert("Profile saved successfully!");
});
