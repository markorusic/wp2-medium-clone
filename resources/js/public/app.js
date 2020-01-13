import router from '../shared/router'
import markdownEditor from '../shared/markdown-editor'
import postActions from './post-actions'
import auth from './auth'
import followAction from './follow-action'

window.Popper = require('popper.js').default
window.$ = window.jQuery = require('jquery')
require('bootstrap')
window.auth = auth

router.match('/posts/:id', ({ id }) => {
    const mde = markdownEditor.init('#content-ta')
    const $content = document.querySelector('#content')
    $content.innerHTML = mde.toHTML(mde.value())
    mde.remove()

    postActions.init(id)
    followAction.init()
})
