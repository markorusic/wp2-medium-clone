import router from '../shared/router'
import markdownEditor from '../shared/markdown-editor'

window.Popper = require('popper.js').default
window.$ = window.jQuery = require('jquery')
require('bootstrap')

router.match('/posts/:id', () => {
    const mde = markdownEditor.init('#content-ta')
    const $content = document.querySelector('#content')
    $content.innerHTML = mde.toHTML(mde.value())
    mde.remove()
})
