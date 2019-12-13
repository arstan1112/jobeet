$(document).ready(function () {
    $('#searchInput').on('input', function (e) {
        let $this = $(this);

        // let value = $this.val();
        let value = $this.val().replace(/ /g,'');

        // if ( ! value.trim() /*|| value.length < 3*/) {
        if ( ! value /*|| value.length < 3*/) {
            return false;
        }

        $.post('/blog/list/'+value)
            .then(function (response) {
                $('.topics-container').html(response['content']);
            })
            .fail(function (xhr) {
                let response = JSON.parse(xhr.responseText);

                $('.topics-container').html(response['message']);
            })
        ;
    });

    // $("#ajaxButton").on("click", function (event) {
    //     let searchInput = $("#searchInput").val();
    //
    //     $.ajax({
    //         url:        '/blog/ajax/list/'+searchInput,
    //         type:       'POST',
    //         dataType:   'json',
    //         // async:      true,
    //
    //         success: function (data, status) {
    //             console.log('ajax success');
    //             console.log(searchInput);
    //             // console.log(data.topics);
    //             $("body").empty().append(data.list);
    //         },
    //         error : function (xhr, textStatus, errorThrown) {
    //             console.log('ajax failed');
    //         }
    //     });
    // });
});
