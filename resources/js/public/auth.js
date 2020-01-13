let user = null

const auth = {
    init(_user) {
        user = _user
    },
    isAuthenticated: () => user !== null,
    getUser: () => user
}

export default auth
