import like from './like'
import comment from './comment'
import postDelete from './post-delete'

const postActions = {
    id: null,
    init(id) {
        this.id = id
        like.init()
        comment.init()
        postDelete.init()
    }
}

export default postActions
