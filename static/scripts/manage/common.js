$("form.popup-form").submit(run_form).find("input.reset").click(function(){$(this).parents("form").hide()});
$(".error-form").click(function(){$(this).hide()});
