document.querySelectorAll(".like-btn").forEach(elem => {
    elem.addEventListener("click", async e => {

        const postId = e.currentTarget.dataset.id;
        const button = e.currentTarget;

        const res = await fetch(`http://localhost:8000/posts/${postId}/toggle_like`, {
            method : "POST",
            headers : {
                "Content-Type" : "application/json",
                "Accept" : "application/json"
            }
        });
        if(res.status === 200){
            const {is_liked, likes} = await res.json();
            
            button.innerHTML = `${likes} <i class="${is_liked ? "fa-solid" : "fa-regular"} fa-heart"></i> `
        }
    })
})
