$(document).ready(function () {
    console.log('likes');
    const topicId = $('#topicId').val();
    const likeButton = $('#likeButton');
    const dislikeButton = $('#dislikeButton');
    const likeCounter = $('#likeCounter');
    const dislikeCounter = $('#dislikeCounter');
    const impressionBar = $('#impressionBar');
    const impression = $('#userImpressionId').val();

    let initialImpressionType;
    let newImpressionType;
    let impressionRate;

    let userImpressionId = 0;
    if (impression) {
        userImpressionId = impression;
    }

    let likeCounterVal = +likeCounter.text();
    let dislikeCounterVal = +dislikeCounter.text();
    if (likeCounterVal!==0) {
        impressionRate = likeCounterVal/(likeCounterVal+dislikeCounterVal)*100;
    } else {
        impressionRate = 0;
    }
    impressionBar.css('width', impressionRate+'%');

    if (likeButton.hasClass("active")) {
        initialImpressionType = 1;
    } else if (dislikeButton.hasClass("active")) {
        initialImpressionType = 2;
    } else {
        initialImpressionType = 0;
    }
    console.log('initialType'+initialImpressionType);

    $.fn.impressionRateSet = function () {
        let likeCounterValue = +likeCounter.text();
        let dislikeCounterValue = +dislikeCounter.text();
        if (likeCounterValue!==0 && dislikeCounterValue!==0) {
            impressionRate = likeCounterValue/(likeCounterValue+dislikeCounterValue)*100;
        } else if (likeCounterValue===0 && dislikeCounterValue>0) {
            impressionRate = 0;
        } else if (dislikeCounterValue===0 && likeCounterValue>0) {
            impressionRate = 100;
        } else if (likeCounterValue===0 && dislikeCounterValue===0) {
            impressionRate = 0;
        }
        console.log('inside impression function');
    };

    likeButton.on('click', function (e) {
        let likeCounterValue = +likeCounter.text();

        if (likeButton.hasClass("active")) {
            dislikeButton.attr("disabled", false);
            newImpressionType = 0;
            likeCounter.text(likeCounterValue-1);
            $.fn.impressionRateSet();
            impressionBar.css('width', impressionRate+'%');

            console.log('like unchecked, new type = '+newImpressionType)
        } else {
            dislikeButton.attr("disabled", true);
            newImpressionType = 1;
            likeCounter.text(likeCounterValue+1);
            $.fn.impressionRateSet();
            impressionBar.css('width', impressionRate+'%');

            console.log('like checked, new type = '+newImpressionType)
        }
    });

    dislikeButton.on('click', function (e) {
        let dislikeCounterValue = +dislikeCounter.text();

        if (dislikeButton.hasClass("active")) {
            likeButton.attr("disabled", false);
            newImpressionType = 0;
            dislikeCounter.text(dislikeCounterValue-1);
            console.log(impressionRate);
            $.fn.impressionRateSet();
            impressionBar.css('width', impressionRate+'%');

            console.log('dislike unchecked, new type = '+newImpressionType)
        } else {
            likeButton.attr("disabled", true);
            newImpressionType = 2;
            dislikeCounter.text(dislikeCounterValue+1);
            $.fn.impressionRateSet();
            impressionBar.css('width', impressionRate+'%');

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