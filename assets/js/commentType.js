$(document).ready(function () {
    var counter = 0;
    console.log('comment scroll console');

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

    $(window).scroll(function (event) {
        // console.log('scroll event');

        if ($(window).scrollTop() + $(window).height() === $(document).height()) {
        // if($(window).scrollTop() + $(window).height() > $(document).height() - 50) {
            let topicId = $('#topicId').val();
            counter = counter+5;
            console.log('scroll down event');

            $.post('/blog/comments/up/'+topicId+'/'+counter)
                .then(function (response, textStatus, xhr) {
                    if (xhr.status===202) {
                        $(this).off(event);
                    }
                    if (xhr.status===201) {
                        $('#commentInput').val("");
                        $('.comments-container').append(response['content']);
                    }
                    console.log('post success event');

                })
                .fail(function (xhr) {
                    let response = JSON.parse(xhr.responseText);
                    console.log(response['message']);
                    counter = counter-5;
                })
            ;
        }
    });
});