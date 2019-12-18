$(document).ready(function () {
    var counter = 5;

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
    });

    $(window).scroll(function (event) {
        if ($(window).scrollTop() + $(window).height() === $(document).height()) {
            let topicId = $('#topicId').val();
            counter = counter+5;

            $.post('/blog/comments/up/'+topicId+'/'+counter)
                .then(function (response, textStatus, xhr) {
                    if (xhr.status===202) {
                        $(this).off(event);
                    }
                    if (xhr.status===201) {
                        $('#commentInput').val("");
                        $("#spinner").show().delay(2000).fadeOut(500);
                        $('.comments-container').append(response['content']);
                    }
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