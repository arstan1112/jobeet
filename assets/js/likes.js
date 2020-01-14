$(document).ready(function () {
    console.log('likes');
    var initialImpressionType;
    var newImpressionType;

    var topicId = $('#topicId').val();
    var likeButton = $('#likeButton');
    var dislikeButton = $('#dislikeButton');
    var userImpressionId = 0;
    var impression = $('#userImpressionId').val();
    var likeCounter = $('.likeCounter');
    var dislikeCounter = $('.dislikeCounter');

    if (impression) {
        userImpressionId = impression;
    }

    if (likeButton.hasClass("active")) {
        initialImpressionType = 1;
    } else if (dislikeButton.hasClass("active")) {
        initialImpressionType = 2;
    } else {
        initialImpressionType = 0;
    }
    console.log('initialType'+initialImpressionType);

    likeButton.on('click', function (e) {
        let likeCounterValue = +likeCounter.text();

        if (likeButton.hasClass("active")) {
            dislikeButton.attr("disabled", false);
            newImpressionType = 0;
            likeCounter.text(likeCounterValue-1);
            console.log('like unchecked, new type = '+newImpressionType)
        } else {
            dislikeButton.attr("disabled", true);
            newImpressionType = 1;
            likeCounter.text(likeCounterValue+1);
            console.log('counter added');
            console.log('like checked, new type = '+newImpressionType)
        }
    });

    dislikeButton.on('click', function (e) {
        let dislikeCounterValue = +dislikeCounter.text();

        if (dislikeButton.hasClass("active")) {
            likeButton.attr("disabled", false);
            newImpressionType = 0;
            dislikeCounter.text(dislikeCounterValue-1);
            console.log('dislike unchecked, new type = '+newImpressionType)
        } else {
            likeButton.attr("disabled", true);
            newImpressionType = 2;
            dislikeCounter.text(dislikeCounterValue+1);
            console.log('dislike checked, new type = '+newImpressionType)
        }
    });

    // window.onbeforeunload = function () {
    window.onunload = function () {
        if (initialImpressionType !== newImpressionType) {
            $.post('/blog/evaluate/'+topicId+'/'+newImpressionType+'/'+userImpressionId)
            // .then(function (response) {
            //     console.log('like topic success');
            //     console.log(response['content']);
            //     console.log(response['type']);
            // })
            // .fail(function () {
            //     console.log('like fail');
            // })
            ;
        }
    };
});