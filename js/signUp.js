//ページが読み込まれたタイミングでajaxでwriteJsonFromDB.phpに飛ばす
//(writeJsonFromDB.phpではDBからuserId情報を取り出しJSONファイルに書き出す)

document.addEventListener('DOMContentLoaded', function(){
    $.ajax({
            type: 'GET',
            url: '../views/writeJsonFromDB.php',
        }).done(function(data){
            console.log('Ajax Success');
            console.log(data);
        }).fail(function(msg){
            console.log('Ajax Error');
            console.log(msg);
        });
},false);

$(function(){
    $('input[name="newUserId"]').keyup(function(){
        var userId = $(this).val();
        var stack;
        $.getJSON('../json/userId.json', function(data){
            for(var i in data){
                if(userId === i){
                    stack = i;
                }
            }
            if(userId === ''){
                $('.userIdError_ajax').text('× ユーザーIDの入力は必須です。');
                if($('.userIdError_ajax').hasClass('green')){
                    $('.userIdError_ajax').removeClass('green');
                }    
            }else if(stack){
                $('.userIdError_ajax').text('× ユーザーIDは既に使用されています。');
                if($('.userIdError_ajax').hasClass('green')){
                    $('.userIdError_ajax').removeClass('green');
                }
            }else {
                $('.userIdError_ajax').text('〇 ユーザーIDは使用できます。');
                if(!$('.userIdError_ajax').hasClass('green')){
                    $('.userIdError_ajax').addClass('green');
                }
            }
        })
    });
})


//フォームの入力値が変更されたら、入力値とJSON形式のuserIdを照合する。
$(function(){
    $('input[name="newUserId"]').keyup(function(){
        
        //classの初期化
        if($('.userIdError_ajax').hasClass('userIdEmpty')){
            $('.userIdError_ajax').removeClass('userIdEmpty');
        }
        
        var userId = $(this).val();
        
        $.ajax({
            type: 'POST',
            url: '../views/readJsonFromDB.php',
            data: {"userId": userId}
        }).done(function(data){
            console.log('Ajax Success');
            console.log(data)
            var result = data;
            console.log(result);
            var val;
            val = $('input[name="newUserId"]').val();
            if(val === ''){
                $('.userIdError_ajax').text('× ユーザーIDの入力は必須です。');
                if($('.userIdError_ajax').hasClass('green')){
                    $('.userIdError_ajax').removeClass('green');
                }    
            }else if(result === ''){
                $('.userIdError_ajax').text('〇 ユーザーIDは使用できます。');
                if(!$('.userIdError_ajax').hasClass('green')){
                    $('.userIdError_ajax').addClass('green');
                }
            }else {
                $('.userIdError_ajax').text('× ユーザーIDは既に使用されています。');
                if($('.userIdError_ajax').hasClass('green')){
                    $('.userIdError_ajax').removeClass('green');
                }
            }
            
            if($('.userIdError_ajax').text() === '× ユーザーIDの入力は必須です。') {
                $('.userIdError_ajax').addClass('userIdEmpty');
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
