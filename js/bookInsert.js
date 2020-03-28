
//+ボタン押すと本が消える
$(function(){
	$('.fa-plus').on('click', function(){
        	$(this).parent().attr('style', 'display: none;');
    	});
});
//次にバックエンド
$(function(){
	var bookNo;
    	var userId;
    	$('.fa-plus').on('click', function(){
        	bookNo = $(this).parent().data('no');
        	//console.log(bookNo);
        	userId = $(this).parent().data('id');
        	//console.log(userId);
        	$.ajax({
            		type: 'POST',
            		url: './bookInsertAjax.php',
            		data: {
                		"bookNo": bookNo,
                		"userId": userId,
            		}
       	 	}).done(function(data){
            		//console.log('Ajax Success');
            		//console.log(data);
        	}).fail(function(msg){
            		//console.log('Ajax Error');
            		//console.log(msg);
        	});
    	});
});

