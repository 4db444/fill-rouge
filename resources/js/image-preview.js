/**
 * Live preview for multi-image file input.
 * Shows thumbnails of selected images with remove functionality.
 */

const imageInput = document.getElementById("image-input");
const previewContainer = document.getElementById("image-preview");

if (imageInput && previewContainer) {
    imageInput.addEventListener("change", () => {
        previewContainer.innerHTML = "";

        const files = imageInput.files;
        if (files.length === 0) return;

        // Show warning if too many files
        if (files.length > 5) {
            previewContainer.innerHTML = `
                <p class="text-xs text-red-500">You can upload a maximum of 5 images.</p>
            `;
            imageInput.value = "";
            return;
        }

        Array.from(files).forEach((file, index) => {
            const reader = new FileReader();

            reader.onload = (e) => {
                const wrapper = document.createElement("div");
                wrapper.className = "relative group";

                const img = document.createElement("img");
                img.src = e.target.result;
                img.alt = file.name;
                img.className = "w-20 h-20 rounded-lg object-cover border border-gray-200";

                const sizeLabel = document.createElement("span");
                sizeLabel.className = "block text-center text-[10px] text-gray-400 mt-1 truncate max-w-[80px]";
                sizeLabel.textContent = file.name.length > 12 ? file.name.slice(0, 12) + "..." : file.name;

                wrapper.appendChild(img);
                wrapper.appendChild(sizeLabel);
                previewContainer.appendChild(wrapper);
            };

            reader.readAsDataURL(file);
        });
    });
}
