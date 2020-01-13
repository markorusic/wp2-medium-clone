import UrlPattern from 'url-pattern'
import noop from 'lodash/noop'

const router = {
    match: (url, cb = noop) => {
        const pattern = new UrlPattern(url)
        const params = pattern.match(location.pathname)
        if (params) {
            cb(params)
        }
    }
}

export default router
