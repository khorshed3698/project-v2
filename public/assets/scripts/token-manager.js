const TokenManager = {
    token: null,
    
    async fetchToken() {
        try {
            // If token already exists, return it
            if (this.token) {
                return this.token;
            }
    
            // Get CSRF token from meta tag
            const csrfToken = $('meta[name="csrf-token"]').attr('content');
    
            const response = await $.ajax({
                url: "/bida-oss-landing/insightdb-token",
                method: 'POST',
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                }
            });
    
            this.token = response.token || response;
            return this.token;
        } catch (error) {
            console.error("Error fetching token:", error);
            throw error;
        }
    },
    
    getToken() {
        return this.token;
    },

    initializeTokenRefresh(tokenUrl = '/bida-oss-landing/insightdb-token', interval = 270000) {
        this.token = null;
        setInterval(() => this.fetchToken(tokenUrl), interval);
    }
};

window.TokenManager = TokenManager;