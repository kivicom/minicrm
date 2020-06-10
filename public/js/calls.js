$(document).ready(function(){
	$('table#calls').on('click', 'a.del', function (e) {
        e.preventDefault();
		if (confirm("Вы подтверждаете удаление?")) {
			let href = this.href;
			let call_id = $(this).data('id');
			let tr = $(this).closest( "tr" );
			//console.log($(this).closest( "tr" )); 
			$.ajax({
				url: '/call/'+call_id+'/delete',
				type: 'GET',
				success: function(res){
					$('#result').html('');
					$('#result').append('<p class="alert-success p-3">'+res+'</p>').fadeIn();
					$(tr).hide();
				},
				error: function (res) {
					$('#result').html('');
					$('#result').append('<p class="alert-danger p-3">'+res+'</p>').fadeIn();
				}
			});
			return true;
		} else {
			return false;
		}
    });
});
