$(function(){
    $('input[type="file"]').on('change', function(e){
        var reader = new FileReader();
        reader.onload = function (e) {
            $('.userphoto2').attr('src', e.target.result);
        }
        reader.readAsDataURL(e.target.files[0]);
        $('.userphoto1').addClass('displayNone');
        //位置調整
            $('.userphoto2').attr('style', 'top:10px;');
    })
});

//位置調整
$(function(){
    if($('.userphoto1').hasClass('displayNone')){
        $('.userphoto2').attr('style', 'top:20px;');
    }
})
//パスワードのマスキングを外す。
//つまり、checkboxにチェックされたらinputのtype属性をpassword⇒textに変更
$(function(){
    var check = $('input[type="checkbox"]');
    var password = $('input[name="password"]');
    var passwordConfirm = $('input[name="passwordConfirm"]');
    $(check).change(function(){
        if($(this).prop('checked')) {
            $(password).attr('type', 'text');
            $(passwordConfirm).attr('type', 'text');
        }else {
            $(password).attr('type', 'password');
            $(passwordConfirm).attr('type', 'password');
        }
    });
});


//ユーザー名が変更になったら、タイトルの名前が入るところを変更
$(function(){
    var input;
    $('input[name="name"]').on('change', function(){
        input = $(this).val();
        $('span.userTitleName').text(input);
    })    
});


//お気に入りをマイページから削除
//高速化のためフロントとバックエンドで処理を分ける
//フロントでは×マークを押したお気に入りの本が消える(のみ)
//バックエンドでは×マークが押されたのをきっかけにデータベースからお気に入りのデータを削除する
//まず、フロント
$(function(){
    var dataNo;
    var selecter;
    $('.fa-window-close').on('click', function(){
        dataNo = $(this).data('no');
        console.log(dataNo);
        selecter = 'i[data-no='+'"'+dataNo+'"'+']';
        $(selecter).parent().attr('style', 'display: none;');
    });
});
//次にバックエンド
$(function(){
    var bookNo;
    var userId;
    var selecter;
    $('.fa-window-close').on('click', function(){
        dataNo = $(this).data('no');
        selecter = 'i[data-no='+'"'+dataNo+'"'+']';
        userId = $(selecter).parent().data('id');
        bookNo = $(selecter).parent().data('no');
        $.ajax({
            type: 'POST',
            url: './mypageEditFavoriteAjax.php',
            data: {
                "bookNo": bookNo,
                "userId": userId,
            }
        }).done(function(data){
            console.log('Ajax Success');
            console.log(data);
        }).fail(function(msg){
            console.log('Ajax Error');
            console.log(msg);
        });
    });
});


//レビューをマイページから削除(上記のお気に入りの削除と同様の流れで)
//まず、フロント
$(function(){
    var dataReviewId;
    var selecter;
    $('.fa-window-close').on('click', function(){
        dataReviewId = $(this).data('reviewid');
        console.log(dataReviewId);
        selecter = 'i[data-reviewid='+'"'+dataReviewId+'"'+']';
        $(selecter).parent().parent().attr('style', 'display: none;');
    });
});
//次にバックエンド
$(function(){
    var reviewId;
    var bookNo;
    var userId;
    var selecter;
    $('.fa-window-close').on('click', function(){
        reviewId = $(this).data('reviewid');
        console.log(reviewId);
        selecter = 'i[data-reviewid='+'"'+reviewId+'"'+']';
        userId = $(selecter).parent().data('id');
        bookNo = $(selecter).parent().data('no');
        reviewId = $(selecter).parent().data('reviewid');
        console.log(userId);
        console.log(bookNo);
        console.log(reviewId);
        $.ajax({
            type: 'POST',
            url: './mypageEditReviewAjax.php',
            data: {
                "bookNo": bookNo,
                "userId": userId,
                "reviewId":reviewId
            }
        }).done(function(data){
            console.log('Ajax Success');
            console.log(data);
        }).fail(function(msg){
            console.log('Ajax Error');
            console.log(msg);
        });
    });
});

