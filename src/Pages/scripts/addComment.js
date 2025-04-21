const postBtn = document.querySelector('.comment-btn')
const commentsContainer = document.querySelector('.comments')
const commentTextArea = document.querySelector('.comment-input')

postBtn.addEventListener('click', async () => {
    const userName = postBtn.dataset.username
    const userId = postBtn.dataset.user
    const jobId = postBtn.dataset.job
    const comment = commentTextArea.value

    const res = await fetch('http://localhost:8080/praksa-projekat-1/src/Pages/postComment.php', {
        method: 'POST',
        body: JSON.stringify({
            user_id: userId,
            job_id: jobId,
            text: comment
        })
    })

    const date = new Date()
    const createdAtString = `${date.getDate()}.${date.getMonth()+1}.${date.getFullYear()}`

    const noCommentsYetMessage = commentsContainer.querySelector('.no-comments')
    if (noCommentsYetMessage) {
        commentsContainer.removeChild(noCommentsYetMessage)
    }

    commentsContainer.insertAdjacentHTML('beforeend', `
        <div class="comment">
            <div class="comment-header">
                <h3 class="comment-user">${userName}</h3>
                <span class="comment-header--gray">&bull;</span>
                <p class="comment-created-at comment-header--gray">${createdAtString}</p>
            </div>
            <p class="comment-text">${comment}</p>
        </div>
    `)

    commentTextArea.value = '';
})