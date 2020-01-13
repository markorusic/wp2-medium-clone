import to from 'await-to-js'
import toastr from 'toastr'
import moment from 'moment'
import auth from '../auth'
import http from '../../shared/http-service'
import asyncEventHandler from '../../shared/async-event-handler'
import userActions from './index'

const onCommentSubmit = asyncEventHandler(async event => {
    if (!auth.isAuthenticated()) {
        return toastr.info('Login to complete that action.')
    }
    const $content = $(event.currentTarget).find('[name="content"]')
    const content = $content.val()
    const [err, { data }] = await to(
        http.post(`/posts/${userActions.postId}/comment`, { content })
    )
    if (err) {
        return toastr.error('Error occured during this action!')
    }

    const user = auth.getUser()
    const $commentList = $('#comment-list')
    const commentHTML = `
        <div class="d-flex" data-comment-id="${data.id}">
            <div class="d-flex mb-2">
                <img
                    class="avatar mr-3"
                    src="${user.avatar}"
                    alt="${user.name}"
                >
            </div>
            <div class="d-flex flex-column mb-4 w-100">
                <div class="d-flex flex-column">
                    <div class="d-flex justify-content-between">
                        <span>${user.name}</span>
                        <a href="#" class="text-dark" data-user-action="remove-comment">
                            <i class="fa fa-times" aria-hidden="true"></i>
                        </a>
                    </div>
                    <span class="text-secondary">
                        ${moment(data.created_at).format('MMM D, Y')}
                    </span>
                </div>
                <div>${content}</div>
            </div>
        </div>
    `
    $content.val('')
    $commentList.prepend(commentHTML)
    $commentList
        .children()
        .first()
        .find('[data-user-action="remove-comment"]')
        .on('click', onCommentRemove)
})

const onCommentRemove = asyncEventHandler(async event => {
    const $comment = $(event.currentTarget).closest('[data-comment-id]')
    if (confirm('Are you sure that you want to delete this comment?')) {
        const { commentId } = $comment.data()
        const [err] = await to(
            http.delete(
                `/posts/${userActions.postId}/comment/${commentId}/remove`
            )
        )
        if (err) {
            return toastr.error('Error occured during this action!')
        }
        toastr.success('Successfully deleted!')
        $comment.remove()
    }
})

const comment = {
    init() {
        $('#comment-form').on('submit', onCommentSubmit)
        $('#comment-list [data-user-action="remove-comment"]').on(
            'click',
            onCommentRemove
        )
    }
}

export default comment
