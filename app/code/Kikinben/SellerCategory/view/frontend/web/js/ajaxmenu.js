define([
        'jquery',
        ],
    function($,ajaxmenu){

    return {
        ajaxMenu:function(ajaxurl,sellerId,categoryId){                       
                $.ajax({
                    url:ajaxurl,
                    type:'POST',
                    //showLoader: true,
                    dataType: 'html',
					contentType: "application/html; charset=utf-8",
                    //data: {id:sellerId,catgid:categoryId},                    
                    success:function(response){
                    	
                    	$('#display_products_all').hide();         
                    	$('#display_products').html(response);
                    	                    	
                    }
                });
           
        }
    }

});