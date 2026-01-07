(function () {
    // Ensure Axios OR jQuery is loaded
    if (typeof axios === 'undefined' && typeof jQuery === 'undefined') {
        console.warn('Axios/jQuery not loaded. JWT Auth delayed.');
        return;
    }

    const token = localStorage.getItem('token');

    // Set default header if token exists
    if (token) {
        if (typeof axios !== 'undefined') axios.defaults.headers.common['Authorization'] = 'Bearer ' + token;
        if (typeof jQuery !== 'undefined') {
            jQuery.ajaxSetup({
                headers: { 'Authorization': 'Bearer ' + token }
            });
        }
    }

    // Response Interceptor
    axios.interceptors.response.use(function (response) {
        // Check for token refresh in headers (case-insensitive)
        const authHeader = response.headers['authorization'] || response.headers['Authorization'];
        if (authHeader && authHeader.startsWith('Bearer ')) {
            const newToken = authHeader.split(' ')[1];
            localStorage.setItem('token', newToken);
            axios.defaults.headers.common['Authorization'] = 'Bearer ' + newToken;
            // Dispatch event for other listeners (like timer)
            window.dispatchEvent(new CustomEvent('token-refreshed', { detail: newToken }));
        }
        return response;
    }, function (error) {
        if (error.response && error.response.status === 401) {
            // Unauthorized - Clear token and Redirect to Login
            // But only if we are NOT on the login page/routes that allow anon
            const currentPath = window.location.pathname;
            if (!currentPath.includes('/admin/login') && !currentPath.includes('esqueceu_a_senha') && !currentPath.includes('recuperar_senha')) {
                localStorage.removeItem('token');
                // Redirect top window
                window.top.location.href = '/admin/login';
            }
        }
        return Promise.reject(error);
    });
})();
