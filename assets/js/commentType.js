$(document).ready(function () {

    $('#commentButton').on('click', function (e) {
        let value   = $('#commentInput').val();
        let topicId = $('#topicId').val();

        if ( ! value /*|| value.length < 3*/) {
            return false;
        }

        $.post('/blog/comment/'+topicId+'/'+value)
            .then(function (response) {
                $('#commentInput').val("");
                $('.comments-container').prepend(response['content']);
            })
            .fail(function (xhr) {
                let response = JSON.parse(xhr.responseText);
                $('.comments-container').html(response['message']);
            })
        ;
        console.log('comment console');
    });

});