let inited = false
let isAuthenticated = false

const auth = {
    init(status = false) {
        if (!inited) {
            inited = true
            isAuthenticated = status
        }
    },
    isAuthenticated: () => isAuthenticated
}

export default auth
