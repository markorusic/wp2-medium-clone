import toastr from 'toastr'
import dayjs from 'dayjs'
import get from 'lodash/get'
import auth from '../auth'
import http from '../../shared/http-service'
import asyncEventHandler from '../../shared/async-event-handler'
import postActions from './index'
import templateRender from '../../shared/template-render'
import dataPagination from '../../shared/data-pagination'

const $dom = {
    form: null,
    title: null,
    list: null,
    listPagination: null
}

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
        <div class="d-flex flex-column mb-4 w-100 px-3 py-1 position-relative"
            style="background-color: #edfeeb;"
        >
            <div class="d-flex flex-column">
                <div class="d-flex justify-content-between">
                    <a class="text-dark font-weight-bold" href="/users/${
                        comment.user.id
                    }">
                        ${comment.user.name}
                    </a>
                    ${templateRender.if(
                        comment.user.id === get(auth.getUser(), 'id'),
                        `<a href="#"
                            data-remove-comment
                            class="text-dark position-absolute"
                            style="top: 0; right: 7px;"
                        >
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
    const $input = $dom.form.find('[name="content"]')
    const content = $input.val()
    return http
        .post(`/posts/${postActions.id}/comment`, { content })
        .then(response => {
            const user = auth.getUser()
            const comment = response.data

            $input.val('')
            $dom.list.prepend(commentTemplate({ ...comment, user }))
            $dom.title.text('Comments')
            $dom.list
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
                if ($('[data-comment-id]').length === 0) {
                    $dom.title.text('No comments yet')
                }
            })
    }
})

const comment = {
    init() {
        $dom.form = $('#comment-form')
        $dom.title = $('#comment-list-header')
        $dom.list = $('#comment-list')
        $dom.listPagination = $('<div class="my-2"></div>').appendTo(
            $dom.list.parent()
        )

        $dom.form.on('submit', onCommentSubmit)

        const fetchData = (page = 1) =>
            http
                .get(`/posts/${postActions.id}/comments?page=${page}&size=5`)
                .then(({ data }) => {
                    const comments = data.data
                    if (comments.length === 0) {
                        return $dom.title.text('No comments yet')
                    }
                    $dom.title.text('Comments')
                    $dom.list.html(
                        templateRender.list(comments, commentTemplate)
                    )
                    $dom.list
                        .find('[data-remove-comment]')
                        .on('click', onCommentRemove)
                    dataPagination.init($dom.listPagination, {
                        pagination: data,
                        onPageChange: fetchData
                    })
                })

        fetchData()
    }
}

export default comment
