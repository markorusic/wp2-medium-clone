import like from './like'
import comment from './comment'

const postActions = {
    id: null,
    init(id) {
        this.id = id
        like.init()
        comment.init()
    }
}

export default postActions
