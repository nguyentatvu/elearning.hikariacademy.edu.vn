document.addEventListener("DOMContentLoaded",function() {
    smoothScrollToComment();
});

function smoothScrollToComment() {
    const urlParams = new URLSearchParams(window.location.search);
    const commentId = urlParams.get('comment_id');
    const element = document.getElementById('comment_id_' + commentId);

    if (commentId == null || element == null) return;

    $('a[href="#tab2"]').trigger('click');
    window.scrollTo({
        top: element.getBoundingClientRect().top - 80,
        behavior: 'smooth'
    });
}