const templateRender = {
    if: (cond, value) => (cond ? value : ''),
    switch: (value, views) => {
        const view = views[value]
        if (view) {
            return view
        }
        if (views.default) {
            return views.default
        }
        return ''
    },
    list: (items = [], mapFn) => items.map(mapFn).join('')
}

export default templateRender
