import UrlPattern from 'url-pattern'
import noop from 'lodash/noop'

const router = {
    match: (url, cb = noop) => {
        const pattern = new UrlPattern(url)
        if (pattern.match(location.pathname)) {
            cb()
        }
    }
}

export default router
