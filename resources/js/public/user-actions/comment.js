import to from 'await-to-js'
import toastr from 'toastr'
import moment from 'moment'
import auth from '../auth'
import http from '../../shared/http-service'
import asyncEventHandler from '../../shared/async-event-handler'
import userActions from './index'

const onSubmit = asyncEventHandler(async event => {
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
        <div class="d-flex">
            <div class="d-flex mb-2">
                <img
                    class="avatar mr-3"
                    src="${user.avatar}"
                    alt="${user.name}"
                >
            </div>
            <div class="d-flex flex-column mb-4">
                <div class="d-flex flex-column">
                    <span>${user.name}</span>
                    <span class="text-secondary">
                        ${moment(data.created_at).format('MMM d, Y')}
                    </span>
                </div>
                <div>${content}</div>
            </div>
        </div>
    `
    $commentList.prepend(commentHTML)
    $content.val('')
})

const comment = {
    init() {
        $('#comment-form').on('submit', onSubmit)
    }
}

export default comment
