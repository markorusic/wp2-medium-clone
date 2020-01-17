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
        $('[data-likes-count]').text(data.likes_count)
        if (data.liked) {
            $likeIcon.removeClass(iconType.like).addClass(iconType.unlike)
        } else {
            $likeIcon.removeClass(iconType.unlike).addClass(iconType.like)
        }
    })
})

const like = {
    init() {
        $('#like-action').on('click', onLikeClick)
        $('#like-users-modal').on(
            'show.bs.modal',
            fetchUserList(`/posts/${postActions.id}/likes`, {
                onChange: ({ total }) => $('[data-likes-count]').text(total)
            })
        )
    }
}

export default like
