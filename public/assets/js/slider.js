$('.slide-images').slick({
    infinite: true,
    dots: true,
    autoplay: true,
    autoplaySpeed: 4800,
    prevArrow: `
        <button type="button" class="slick-prev">
            <i class="fa-solid fa-arrow-left"></i>
        </button>`,
    nextArrow: `
        <button type="button" class="slick-next">
            <i class="fa-solid fa-arrow-right"></i>
        </button>`
  });