/**
 * Dashboard search with debouncing.
 * Searches posts and users via AJAX and displays results inline.
 */

const searchInput = document.getElementById("search-input");
const searchResults = document.getElementById("search-results");
const postsFeed = document.getElementById("posts-feed");
const loadingSkeleton = document.getElementById("search-loading");

let debounceTimer = null;

searchInput.addEventListener("input", (e) => {
    const query = e.target.value.trim();

    // Clear previous timer
    clearTimeout(debounceTimer);

    // If query is empty, show the regular feed
    if (query.length < 2) {
        searchResults.classList.add("hidden");
        loadingSkeleton.classList.add("hidden");
        postsFeed.classList.remove("hidden");
        return;
    }

    // Show loading skeleton, hide feed and results
    postsFeed.classList.add("hidden");
    searchResults.classList.add("hidden");
    loadingSkeleton.classList.remove("hidden");

    // Debounce the search by 400ms
    debounceTimer = setTimeout(async () => {
        try {
            const res = await fetch(`/dashboard/search?q=${encodeURIComponent(query)}`, {
                headers: {
                    "Accept": "application/json"
                }
            });

            if (res.ok) {
                const { posts, users } = await res.json();
                renderResults(posts, users, query);
            }
        } catch (err) {
            console.error("Search error:", err);
        } finally {
            loadingSkeleton.classList.add("hidden");
        }
    }, 400);
});

/**
 * Render search results into the search-results container.
 */
function renderResults(posts, users, query) {
    searchResults.innerHTML = "";

    // If no results at all
    if (posts.length === 0 && users.length === 0) {
        searchResults.innerHTML = `
            <div class="text-center py-12">
                <p class="text-sm text-gray-400">No results found for "<span class="font-medium text-gray-600">${escapeHtml(query)}</span>"</p>
            </div>
        `;
        searchResults.classList.remove("hidden");
        return;
    }

    let html = "";

    // Users section
    if (users.length > 0) {
        html += `<h3 class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-3">Users</h3>`;
        html += `<div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mb-6">`;
        users.forEach(user => {
            html += `
                <a href="/profile/${user.id}" class="flex items-center gap-3 p-3 border border-gray-200 rounded-lg bg-white hover:border-gray-400 transition-colors">
                    <img src="/${user.profile_image}" class="w-10 h-10 rounded-full object-cover" alt="${escapeHtml(user.first_name)}">
                    <div class="min-w-0">
                        <p class="text-sm font-semibold text-black truncate">${escapeHtml(user.first_name)} ${escapeHtml(user.last_name)}</p>
                        <p class="text-xs text-gray-400 truncate">${escapeHtml(user.city || "")}${user.city && user.country ? ", " : ""}${escapeHtml(user.country || "")}</p>
                    </div>
                </a>
            `;
        });
        html += `</div>`;
    }

    // Posts section
    if (posts.length > 0) {
        html += `<h3 class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-3">Posts</h3>`;
        html += `<div class="space-y-3">`;
        posts.forEach(post => {
            html += `
                <a href="/posts/${post.id}" class="block border border-gray-200 rounded-lg p-4 bg-white hover:border-gray-400 transition-colors">
                    <div class="flex items-center gap-2 mb-2">
                        <img src="/${post.author_image}" class="w-6 h-6 rounded-full object-cover" alt="${escapeHtml(post.author)}">
                        <span class="text-xs text-gray-500">${escapeHtml(post.author)}</span>
                        <span class="text-xs text-gray-300">·</span>
                        <span class="text-xs text-gray-400">${escapeHtml(post.created_at)}</span>
                    </div>
                    <h4 class="text-sm font-semibold text-black mb-1">${escapeHtml(post.title)}</h4>
                    ${post.address ? `<p class="text-xs text-gray-400 mb-1">${escapeHtml(post.address)}</p>` : ""}
                    <p class="text-sm text-gray-600 leading-relaxed mb-2">${escapeHtml(post.content)}</p>
                    <div class="flex items-center gap-3 pt-2 border-t border-gray-100">
                        <span class="text-xs text-gray-400">${post.likes_count} likes</span>
                        <span class="text-xs text-gray-400">${post.comments_count} comments</span>
                    </div>
                </a>
            `;
        });
        html += `</div>`;
    }

    searchResults.innerHTML = html;
    searchResults.classList.remove("hidden");
}

/**
 * Escape HTML special characters to prevent XSS.
 */
function escapeHtml(text) {
    if (!text) return "";
    const div = document.createElement("div");
    div.textContent = text;
    return div.innerHTML;
}
