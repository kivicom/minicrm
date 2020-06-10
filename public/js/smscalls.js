$(document).ready(function(){
	//sendsms photoconfirm 
	$('form.editCall').on('click', '.btn.photoconfirm', function (e) {
        e.preventDefault();
		console.log( $('form.editCall').serialize() );
		customer = $("form.editCall input[name='AN']").val();
		//console.log(customer);
        $.ajax({
            url: '/sms/photoconfirm/' + customer,
            type: 'GET',
            /*beforeSend: function(){
                $('.preloader').fadeIn(300, function(){
                    $('.product-one').hide();
                });
            },
            */
            success: function(res){
                $('#photoconfirm').html('');
                $('#photoconfirm').append('<p class="alert-success p-3">'+res+'</p>').fadeIn();
            },
            error: function (res) {
                $('#photoconfirm').html('');
                $('#photoconfirm').append('<p class="alert-danger p-3">'+res+'</p>').fadeIn();
            }
        });
    });

	//sendsms responsible 
	responsible = $('#Responsible option:selected').val();
	$('#Responsible').on('change',function() { 
		responsible = $('#Responsible option:selected').val();
		console.log(responsible);
	});
    $('form.editCall').on('click', '.btn.responsible', function (e) {
        e.preventDefault();

		console.log(responsible);
		
		
        $.ajax({
            url: '/sms/responsible/' + responsible,
            type: 'GET',
            /*beforeSend: function(){
                $('.preloader').fadeIn(300, function(){
                    $('.product-one').hide();
                });
            },
            */
            success: function(res){
                $('#responsible').html('');
                $('#responsible').append('<p class="alert-success p-3">'+res+'</p>').fadeIn();
            },
            error: function (res) {
                $('#responsible').html('');
                $('#responsible').append('<p class="alert-danger p-3">'+res+'</p>').fadeIn();
            }
        });
    });
	
	
	//sendsms taskcompletion 
    $('form.editCall').on('click', '.btn.taskcompletion', function (e) {
        e.preventDefault();
		customer = $("form.editCall input[name='AN']").val();
		console.log(customer);
        $.ajax({
            url: '/sms/taskcompletion/' + customer,
            type: 'GET',

            success: function(res){
                $('#taskcompletion').html('');
                $('#taskcompletion').append('<p class="alert-success p-3">'+res+'</p>').fadeIn();
            },
            error: function (res) {
                $('#taskcompletion').html('');
                $('#taskcompletion').append('<p class="alert-danger p-3">'+res+'</p>').fadeIn();
            }
        });
    });
	
	
	//sendsms compensation 
    $('form.editCall').on('click', '.btn.compensation', function (e) {
        e.preventDefault();
		customer = $("form.editCall input[name='AN']").val();
		compensation = $("form.editCall input[name='Compensation']").val();
		console.log(customer);
		console.log(compensation);
        $.ajax({
            url: '/sms/costrecovery/' + customer,
            type: 'GET',
			data:{
				sum:compensation
			},
            success: function(res){
                $('#compensation').html('');
                $('#compensation').append('<p class="alert-success p-3">'+res+'</p>').fadeIn();
            },
            error: function (res) {
                $('#compensation').html('');
                $('#compensation').append('<p class="alert-danger p-3">'+res+'</p>').fadeIn();
            }
        });
    });
});