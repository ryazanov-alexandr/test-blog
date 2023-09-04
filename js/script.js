window.addEventListener('DOMContentLoaded', () => {
    const postData = async (url, data) => {
        let result = await fetch(url, {
            method: 'POST',
            body: data,
        });

        return await result;
    };

    let form = document.querySelector('.search'),
        comments = document.querySelector('.comments'),
        input = document.querySelector('input[type=search]'); 

    form.addEventListener('submit', (e) => {
        e.preventDefault();
        comments.innerHTML = '';

        const formData = new FormData(form);

        postData('search.php', formData)
            .then(res => res.ok ? res.json() : Promise.reject(res))
            .then(data => {

                for(const comment of data) {
                    let commentItem = document.createElement('div');
                    commentItem.classList.add('comment__item');
                    commentItem.innerHTML = `
                        <h2 class="comment__title">${comment.title}</h2>
                        <div class="comment__descr">${comment.body}</div>
                    `;
                    
                    comments.append(commentItem);     
                }
            })
            .catch((res) => console.log(res))
    })

    input.addEventListener("keyup", (e) => {
        const escCode = 27; 
        if(e.keyCode === escCode) {
            comments.innerHTML = '';
        }
    });
    
    input.addEventListener("search", () => {
        comments.innerHTML = '';
    });
})