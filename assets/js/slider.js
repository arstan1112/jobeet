$(document).ready(function () {
    var slideIndex = 0;
    var autoSlide = true;
    var timer = null;

    $('.prev').on('click', function () {
        if (slideIndex>1) {
            showSlides(slideIndex += (-1));
        } else {
            showSlides(-1);
        }
    });

    $('.next').on('click', function () {
        showSlides(slideIndex += 1);
    });

    $('.dot').on('click', function () {
        var dotn = $(this).index();
        autoSlide = false;
        showSlides(dotn+1);
    });

    if (autoSlide) {
        showSlides();
    }

    // function plusSlides(n)
    // {
    //     showSlides(slideIndex += n);
    // }

    // function currentSlide(n)
    // {
    //     // showSlides(slideIndex = n);
    //     showSlides(n);
    //     console.log('current slide function');
    //     console.log(n);
    // }

    function showSlides(n= 0)
    {
        var i;
        var slides = document.getElementsByClassName("mySlides");

        if (slides[0]) {
            var dots   = document.getElementsByClassName('dot');
            for (i = 0; i < slides.length; i++) {
                slides[i].style.display = "none";
                dots[i].style.backgroundColor = "silver";

            }

            slideIndex++;

            if (n>0) {
                slideIndex = n;
            }

            if (n===-1) {
                slideIndex = slides.length;
            }

            if (slideIndex > slides.length) {
                slideIndex = 1}
            slides[slideIndex-1].style.display = "block";
            dots[slideIndex-1].style.backgroundColor = "#4C4442";
            if ((slideIndex-2)>=0) {
                dots[slideIndex-2].style.backgroundColor = "silver";
            }
            if ((slideIndex-1)!==(dots.length-1)) {
                dots[dots.length-1].style.backgroundColor = "silver";
            }

            if (timer) {
                clearTimeout(timer); //cancel the previous timer.
                timer = null;
            }
            timer = setTimeout(showSlides, 2000);
        }


        // var i;
        // var slides = document.getElementsByClassName("mySlides");
        // var dots = document.getElementsByClassName("dot");
        // if (n > slides.length) {
        //     slideIndex = 1}
        // if (n < 1) {
        //     slideIndex = slides.length}
        // for (i = 0; i < slides.length; i++) {
        //     slides[i].style.display = "none";
        // }
        // console.log('show slide console');
        // for (i = 0; i < dots.length; i++) {
        //     dots[i].className = dots[i].className.replace(" active", "");
        // }
        // slides[slideIndex-1].style.display = "block";
        // dots[slideIndex-1].className += " active";
    }

});