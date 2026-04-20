const commentContainer = document.getElementById("comments-container");
const commentForm = document.getElementById("comment-form");
const commentsCountElement = document.getElementById("comments-count");
const deleteCommentBtns = document.querySelectorAll(".delete-comment");

const postId = + location.pathname.split("/").filter(elem => elem)[1];
let commentsCount = + commentsCountElement.textContent

async function handleDelete (e) {
    if(confirm("Are you sure you want to delete this comment ? ")){
        const commentId = e.currentTarget.dataset.id;
        
        const res = await fetch (`http://localhost:8000/posts/${postId}/comments/${commentId}`, {
            method : "DELETE",
            headers : {
                "Accept" : "Application/json",
                "Content-Type" : "Application/json",
            }
        })

        if(res.status === 200){
            e.target.closest(".comment").remove();
            commentsCountElement.innerHTML = --commentsCount
        }
    }
}

commentForm.addEventListener("submit", async e => {
    e.preventDefault();

    const content = commentForm.content.value.trim();

    if(content){
        const res = await fetch(`http://localhost:8000/posts/${postId}/comments`, {
            method : "POST",
            headers : {
                "Content-Type" : "Application/json",
                "accept" : "Application/json",
            },
            body : JSON.stringify({content})
        });

        if(res.status === 201){
            const {comment} = await res.json();
            
            const button = document.createElement("button")
            button.dataset.id = comment.id;
            button.innerHTML = `<i class="fa-solid fa-trash"></i>`;
            button.className = "delete-comment text-xs text-gray-400 hover:text-red-500 transition-colors shrink-0";
            button.addEventListener("click", handleDelete);

            const li = document.createElement("li");
            li.className = "comment flex flex-col gap-3 py-4 border-b border-gray-100 last:border-0";

            const headerDiv = document.createElement("div");
            headerDiv.className = "flex items-center justify-between w-full";

            const authorLink = document.createElement("a");
            authorLink.href = `/profile/${comment.user.id}`;
            authorLink.className = "flex items-center gap-3 group";

            const avatar = document.createElement("img");
            avatar.src = `/${comment.user.profile.img_url}`;
            avatar.alt = comment.user.first_name;
            avatar.className = "w-9 h-9 rounded-full object-cover ring-2 ring-gray-200 group-hover:ring-gray-400 transition-all";

            const nameSpan = document.createElement("span");
            nameSpan.className = "text-sm font-semibold text-gray-900 group-hover:text-black transition-colors";
            nameSpan.textContent = `${comment.user.first_name} ${comment.user.last_name}`;

            authorLink.appendChild(avatar);
            authorLink.appendChild(nameSpan);
            headerDiv.appendChild(authorLink);
            headerDiv.appendChild(button);

            const contentP = document.createElement("p");
            contentP.className = "text-sm text-gray-600 leading-relaxed pl-12";
            contentP.textContent = comment.content;

            li.appendChild(headerDiv);
            li.appendChild(contentP);

            commentContainer.insertAdjacentElement("afterbegin", li)

            commentsCountElement.innerHTML = ++commentsCount

            commentForm.content.value = "";
        }

    }
})

deleteCommentBtns.forEach(elem => {
    elem.addEventListener("click", handleDelete)
})