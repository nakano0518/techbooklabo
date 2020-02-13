
/* --------------------------------------------------------
並べ替えボタンクリック時の動作
----------------------------------------------------------*/
$('.sort').on('click', function(event) {
    event.preventDefault();
    if($('.sortForm').hasClass('tag')) {
        $('.sortForm').slideDown(100);
        $('.sortForm').removeClass('tag');
    }else {
        $('.sortForm').slideUp(100);
        $('.sortForm').addClass('tag');
    }
});

