window.axios.get('/token')
.then({data} => {
    localStorage.setItem('token', data.token);
});