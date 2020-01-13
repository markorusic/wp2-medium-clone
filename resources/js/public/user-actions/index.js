import like from './like'

const userActions = {
    postId: null,
    init({ postId }) {
        this.postId = postId
        like.init()
    }
}

export default userActions
