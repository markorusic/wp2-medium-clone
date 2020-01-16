import { fetchUserList } from './user-list'

const $profile = {
    followers: null,
    following: null
}

const userProfile = {
    init(id) {
        $profile.followers = $('#followers-modal')
        $profile.following = $('#following-modal')

        $profile.followers.on(
            'show.bs.modal',
            fetchUserList(`/users/${id}/followers`)
        )

        $profile.following.on(
            'show.bs.modal',
            fetchUserList(`/users/${id}/following`)
        )
    }
}

export default userProfile
