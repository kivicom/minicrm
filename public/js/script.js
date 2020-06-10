$(document).ready(function(){
    console.log(window.location.search);
    $("#inputNumAutomat, #inputSumPromo").keypress(function(event){
        event = event || window.event;
        if (event.charCode && event.charCode!== 0 && event.charCode !== 46 && (event.charCode < 48 || event.charCode > 57) )
            return false;
    });

    $('#orderBy').change(function(){
        window.location = '?order_by=' + $(this).val();
    });

    /* Form POST credit*/
    var action = $("#form").attr("action");
    //var credit = $("form input[name='credit']").val();

    //$("#form input[name='credit']").val(credit);

    $('body').on('click', '#form .btn', function (e) {
        e.preventDefault();
        var num_automat = $("#form input[name='num_automat']").val();
        var sum_promo = $("#form input[name='sum_promo']").val();
        var phone_of_client = $("#form input[name='phone_of_client']").val();
        var rand_gen = $("#form input[name='rand_gen']").val();
        $.ajax({
            url: '/getdiscount',
            data: {
                num_automat:num_automat,
                sum_promo:sum_promo,
                phone_of_client:phone_of_client,
                rand_gen:rand_gen
            },
            type: 'POST',
            /*beforeSend: function(){
                $('.preloader').fadeIn(300, function(){
                    $('.product-one').hide();
                });
            },
            */
            success: function(res){
                $('#result').html(res).fadeIn();
            },
            error: function (res) {
                $('#result').html(res).fadeIn();
            }
        });
    });

    //sendsms from appeal edit
    $('form').on('click', '#sendsms', function (e) {
        e.preventDefault();
        //console.log( $('form').serialize() );
        data = $("form").serialize();
        $.ajax({
            url: '/sendsms',
            data: data,
            type: 'POST',
            /*beforeSend: function(){
                $('.preloader').fadeIn(300, function(){
                    $('.product-one').hide();
                });
            },
            */
            success: function(res){
                $('#result').html('');
                $('#result').append('<p class="alert-success p-3">'+res+'</p>').fadeIn();
            },
            error: function (res) {
                $('#result').html('');
                $('#result').append('<p class="alert-danger p-3">'+res+'</p>').fadeIn();
            }
        });
    });

    //orderby table on home page
    $('#appeal').on('click', 'thead tr th a', function (e) {
        e.preventDefault();
        //console.log( $('form').serialize() );
        action = $(this).attr("href");
        data = action;
        $.ajax({
            url: '/',
            data: data,
            type: 'GET',
            success: function(res){
                console.log(res);
                $('body').html(res);
            }
        });
    });

});