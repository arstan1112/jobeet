$(document).ready(function () {
    var slideIndex = 0;

    // $('.prev').on('click', function () {
    //     showSlides(slideIndex += (-1));
    // });
    // var slideIndex = 1;
    // var slideIndex = 0;

    // showSlides(slideIndex);
    showSlides();

    // Next/previous controls
    function plusSlides(n)
    {
        showSlides(slideIndex += n);
    }

    // Thumbnail image controls
    function currentSlide(n)
    {
        showSlides(slideIndex = n);
    }

    function showSlides(n= 0)
    {
        var i;
        var slides = document.getElementsByClassName("mySlides");
        var dots   = document.getElementsByClassName('dot');
        for (i = 0; i < slides.length; i++) {
            slides[i].style.display = "none";
        }
        slideIndex++;
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

        setTimeout(showSlides, 4000); // Change image every 2 seconds
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