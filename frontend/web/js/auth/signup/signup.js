$(".methodSelectionSignup a").on('click', function(e){
    if(!$(this).hasClass('active')){ //Если кнопка не активна, что-бы не делать повторных вещей если нажать нескольео раз
        $(".methodSelectionSignup a").removeClass('active'); //Удаляем активную
        $(this).addClass('active'); //Делаем текущую активной
        let name = $(this).data('btn');  //Получаем название поля с которым нужно выполнить действие


        $("#signup-form input").removeClass('is-valid').removeClass('is-invalid').val(""); //Убираем маркера указывающие на ошибку
        $("#signup-form .help-block").text(''); //Очищаем ощибки

        $("#signup-form .blockFields input").attr("disabled", "disabled");
        $("#signup-form .blockFields #signupform-" + name).removeAttr('disabled');

        $("#signup-form .blockFields .form-group").css('display', 'none');

        $("#signup-form .blockFields  .field-signupform-" + name).css('display', 'block');

    }
    return false;

});
