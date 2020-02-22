//ユーザーIDが既に使用されているか
//入力値が変更されたタイミングでAjaxでデータベースと照合しエラーメッセージを書き換える
$(function(){
    var val
    $('input[name="newUserId"]').change(function(){
        val = $(this).val();
        console.log(val);
        $.ajax({
            type: 'POST',
            url: './userIdValidationAjax.php',
            data: {"val": val}
        }).done(function(data){
            console.log('Ajax Success');
            console.log(data);
            console.log(typeof data);
            if(data === "0"){
                $('.userIdError_ajax').text('〇 ユーザーIDは使用できます。');
                if(!$('.userIdError_ajax').hasClass('green')){
                    $('.userIdError_ajax').addClass('green');
                }
            }else if(data === "1") {
                $('.userIdError_ajax').text('× ユーザーIDは既に使用されています。');
                if($('.userIdError_ajax').hasClass('green')){
                    $('.userIdError_ajax').removeClass('green');
                }
            }else {
                $('.userIdError_ajax').text('× ユーザーIDの入力は必須です。');
                if($('.userIdError_ajax').hasClass('green')){
                    $('.userIdError_ajax').removeClass('green');
                }
            }
            
            if($('.userIdError_ajax').text() === '× ユーザーIDの入力は必須です。') {
                $('.userIdError_ajax').attr('style', 'margin-right: 15px;');
            }else {
                if($('.userIdError_ajax').is('[style]')){
                    $('.userIdError_ajax').removeAttr('style');
                }
            }
        }).fail(function(msg){
            console.log('Ajax Error');
            console.log(msg);
        });
    });
});
//ユーザーIDの入力欄で前回入力が保持された状態でのエラー文は
//入力欄が変更されたら削除する
$('input[name="newUserId"]').keyup(function(){
    $('.userIdError_first').attr('style', 'display:none;');
});

//必須項目エラーは入力されたらエラー文消す。
$('input[name="newUserId"]').change(function(){
    $('.checkInputEmpty_newUserId').attr('style', 'display:none;');
});
$('input[name="password"]').change(function(){
    $('.checkInputEmpty_password').attr('style', 'display:none;');
});
$('input[name="passwordConfirm"]').change(function(){
    $('.checkInputEmpty_passwordConfirm').attr('style', 'display:none;');
});

//パスワードの形式不正エラーが解消されたら、エラー文消す。
var preg = /^(?=.*?[a-z])(?=.*?[A-Z])(?=.*?\d)[a-zA-Z\d]{8,100}$/;
var input;
$('input[name="password"]').change(function(){
    input = $(this).val();
    if(input.match(preg) !== null){
        $('.passwordFormat').attr('style', 'display:none;');
    }
});

//パスワード不一致エラーが解消されたら、エラー文消す。
var input_password;
var input_passwordConfirm;
$('input[name="password"]').change(function(){
    input_password = $(this).val();    
})
$('input[name="passwordConfirm"]').change(function(){
    input_passwordConfirm = $('input[name="passwordConfirm"]').val();
    if(input_password === input_passwordConfirm){
        $('.confirmPassword').attr('style', 'display:none;');
    }
});


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
