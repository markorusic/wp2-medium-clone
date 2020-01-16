import toastr from 'toastr'
import dayjs from 'dayjs'
import get from 'lodash/get'
import auth from '../auth'
import http from '../../shared/http-service'
import asyncEventHandler from '../../shared/async-event-handler'
import postActions from './index'
import templateRender from '../../shared/template-render'
import dataPagination from '../../shared/data-pagination'

const commentTemplate = comment => `
    <div class="d-flex" data-comment-id="${comment.id}">
        <div class="d-flex mb-2 mr-3">
            <a href="/users/${comment.user.id}">
                <img class="avatar"
                    src="${comment.user.avatar}"
                    alt="${comment.user.name}"
                >
            </a>
        </div>
        <div class="d-flex flex-column mb-4 w-100">
            <div class="d-flex flex-column">
                <div class="d-flex justify-content-between">
                    <a class="text-dark" href="/users/${comment.user.id}">
                        ${comment.user.name}
                    </a>
                    ${templateRender.if(
                        comment.user.id === get(auth.getUser(), 'id'),
                        `<a href="#" class="text-dark" data-remove-comment>
                            <i class="fa fa-times" aria-hidden="true"></i>
                         </a>`
                    )}
                </div>
                <span class="text-secondary">
                    ${dayjs(comment.created_at).format('MMM D, YYYY')}
                </span>
            </div>
            <div>${comment.content}</div>
        </div>
    </div>
`

const onCommentSubmit = asyncEventHandler(event => {
    if (!auth.isAuthenticated()) {
        return toastr.info('Login to complete that action.')
    }
    const $input = $(event.currentTarget).find('[name="content"]')
    const content = $input.val()
    return http
        .post(`/posts/${postActions.id}/comment`, { content })
        .then(response => {
            const user = auth.getUser()
            const comment = response.data
            const $commentList = $('#comment-list')

            $input.val('')
            $commentList.prepend(commentTemplate({ ...comment, user }))
            $commentList
                .children()
                .first()
                .find('[data-remove-comment]')
                .on('click', onCommentRemove)
        })
})

const onCommentRemove = asyncEventHandler(event => {
    const $comment = $(event.currentTarget).closest('[data-comment-id]')
    if (confirm('Are you sure that you want to remove this comment?')) {
        const { commentId } = $comment.data()
        return http
            .delete(`/posts/${postActions.id}/comment/${commentId}/remove`)
            .then(() => {
                toastr.success('Successfully removed comment!')
                $comment.remove()
            })
    }
})

const comment = {
    init() {
        const $commentForm = $('#comment-form')
        const $commentListHeader = $('#comment-list-header')
        const $commentList = $('#comment-list')
        const $commentListPagination = $('<div class="my-2"></div>').appendTo(
            $commentList.parent()
        )

        $commentForm.on('submit', onCommentSubmit)

        const fetchData = (page = 1) =>
            http
                .get(`/posts/${postActions.id}/comments?page=${page}&size=5`)
                .then(({ data }) => {
                    const comments = data.data
                    $commentListHeader.text(
                        comments.length === 0
                            ? 'Be first to comment!'
                            : 'Comments'
                    )
                    $commentList.html(
                        templateRender.list(comments, commentTemplate)
                    )
                    $commentList
                        .find('[data-remove-comment]')
                        .on('click', onCommentRemove)
                    dataPagination.init($commentListPagination, {
                        pagination: data,
                        onPageChange: fetchData
                    })
                })

        fetchData()
    }
}

export default comment
