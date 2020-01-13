import like from './like'
import comment from './comment'

const userActions = {
    postId: null,
    init({ postId }) {
        this.postId = postId
        like.init()
        comment.init()
    }
}

export default userActions
