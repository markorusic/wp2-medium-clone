const markdownEditor = {
    init(textAreaSelector, options = {}) {
        require('simplemde/dist/simplemde.min.css')
        require('highlight.js/styles/github.css')
        window.hljs = require('highlight.js')
        const SimpleMDE = require('simplemde')

        const $textarea = document.querySelector(textAreaSelector)

        let mde = new SimpleMDE({
            autoDownloadFontAwesome: false,
            element: $textarea,
            renderingConfig: {
                codeSyntaxHighlighting: true
            },
            ...options
        })

        return {
            instance: mde,
            toHTML: mde.options.previewRender.bind(mde.options),
            value: mde.value.bind(mde),
            remove() {
                mde.toTextArea()
                mde = null
            }
        }
    }
}

export default markdownEditor
