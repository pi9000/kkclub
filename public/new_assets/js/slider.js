document.addEventListener('DOMContentLoaded', function() {
  const swiper = new Swiper('#main-swiper', {
    // Optional parameters
    loop: true,

    slidesPerView: 1,

    // Center active slide
    centeredSlides: true,

    // Set spacing between slides
    spaceBetween: 10,

    // Pagination
    pagination: {
      el: '.hide',
    },

    // Navigation arrows
    navigation: {
      nextEl: '.hide',
      prevEl: '.hide',
    },

    autoplay: {
      delay: 3000, 
      disableOnInteraction: true,
    },
  });

  const referral_swiper = new Swiper ('#referral-swiper', {
    // Optional parameters
    loop: false,
    slidesPerView: 1,
    spaceBetween: 10,
    fade: true,
    centeredSlides: false,
    slidesOffsetBefore: 0,

   

    pagination: {
      el: '.swiper-pagination', // Use the pagination element you've defined in your HTML
      clickable: true, // Enable clickable pagination
    },
      
    });

  const sign_swiper = new Swiper ('#sign-swiper', {
    // Optional parameters
    loop: true,
    slidesPerView: 1,
    spaceBetween: 10,
    loopFillGroupWithBlank: true,
    fade: true,
    centeredSlides: true,

    autoplay: {
        delay: 3000, // Delay between slides in milliseconds
        disableOnInteraction: false // Continue autoplay even when user interacts with the slider
      }
      
    });
});