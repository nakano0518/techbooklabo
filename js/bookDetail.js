//お気に入りボタン押す⇒データベース接続⇒フロントでその情報を反映：2sかかる
//Ajaxの高速化を図るために、フロントとバックエンドを分ける
//フロント:ボタン押すとアイコン変化と総数の増減のみ
//バックエンド:ボタン押すとデータベースを変更するのみ

//フロント
//お気に入りボタン押すとアイコンの変更と総数の増減
$(function(){
    //総数の取得
    var count = parseInt($('.starCount').text());
    $('.fa-star').on('click', function(){
        if($(this).hasClass('far')){
            $(this).removeClass('far');
            $(this).addClass('fas');
            count++;
            $('.starCount').text(count);
        }else if($(this).hasClass('fas')) {
            $(this).removeClass('fas');
            $(this).addClass('far');
            count--;
            $('.starCount').text(count);
        }
    }); 
});

//お気に入りボタン押す⇒データベース変更のみ
$(function(){
    var bookNo;
    var userId;
    $('.fa-star').on('click', function(){
        bookNo = $(this).parents('.post').data('no');
        userId = $(this).parents('.post').data('id');
        $.ajax({
            type: 'POST',
            url: '../views/starAjax.php',
            data: {
                "bookNo": bookNo,
                "userId": userId
            }
        }).done(function(data){
            console.log('Ajax Success');
        }).fail(function(msg){
            console.log('Ajax Error');
            console.log(msg);
        });
    });
});
