import toastr from 'toastr'
import auth from '../auth'
import http from '../../shared/http-service'
import asyncEventHandler from '../../shared/async-event-handler'
import { fetchUserList } from '../user-list'
import postActions from './index'

const iconType = {
    like: 'fa-thumbs-o-up',
    unlike: 'fa-thumbs-up'
}

const onLikeClick = asyncEventHandler(event => {
    if (!auth.isAuthenticated()) {
        return toastr.info('Login to complete that action.')
    }
    return http.post(`/posts/${postActions.id}/like`).then(({ data }) => {
        const $likeIcon = $(event.currentTarget).find('i')
        const $likeCount = $('[data-likes-count]')

        const isLiked = data !== 1
        const likeCount = parseInt($likeCount.text())

        if (isLiked) {
            $likeIcon.removeClass(iconType.like).addClass(iconType.unlike)
            $likeCount.text(likeCount + 1)
        } else {
            $likeIcon.removeClass(iconType.unlike).addClass(iconType.like)
            $likeCount.text(likeCount - 1)
        }
    })
})

const like = {
    init() {
        $('#like-action').on('click', onLikeClick)
        $('#like-users-modal').on(
            'show.bs.modal',
            fetchUserList(`/posts/${postActions.id}/likes`, {
                onFetch: ({ total }) => $('[data-likes-count]').text(total)
            })
        )
    }
}

export default like
