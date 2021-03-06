var WelcomeSettingsWizard = function() {

    "use strict";
    var wizardContent = $('#wizard');
    var wizardForm = $('#form');
    var numberOfSteps = $('.swMain > ul > li').length;
    var initWizard = function() {
        // function to initiate Wizard Form
        wizardContent.smartWizard({
            selected: 0,
            keyNavigation: false,
            onLeaveStep: leaveAStepCallback,
            onShowStep: onShowStep,
        });
        var numberOfSteps = 0;
        animateBar();
        initValidator();
    };
    var animateBar = function(val) {
        if ((typeof val == 'undefined') || val == "") {
            val = 1;
        };

        var valueNow = Math.floor(100 / numberOfSteps * val);
        $('.step-bar').css('width', valueNow + '%');
    };
    var validateCheckRadio = function(val) {
        $("input[type='radio'], input[type='checkbox']").on('ifChecked', function(event) {
            $(this).parent().closest(".has-error").removeClass("has-error").addClass("has-success").find(".help-block").remove().end().find('.symbol').addClass('ok');
        });
    };
    var initValidator = function() {
        $.validator.addMethod("cardExpiry", function() {
            //if all values are selected
            if ($("#card_expiry_mm").val() != "" && $("#card_expiry_yyyy").val() != "") {
                return true;
            } else {
                return false;
            }
        }, 'Please select a month and year');
        $.validator.setDefaults({
            errorElement: "span", // contain the error msg in a span tag
            errorClass: 'help-block',
            errorPlacement: function(error, element) { // render error placement for each input type
                if (element.attr("type") == "radio" || element.attr("type") == "checkbox") { // for chosen elements, need to insert the error after the chosen container
                    error.insertAfter($(element).closest('.form-group').children('div').children().last());
                } else if (element.attr("name") == "card_expiry_mm" || element.attr("name") == "card_expiry_yyyy") {
                    error.appendTo($(element).closest('.form-group').children('div'));
                } else {
                    error.insertAfter(element);
                    // for other inputs, just perform default behavior
                }
            },
            ignore: ':hidden',
            rules: {
                registration_code: {
                    required: true
                },
                code_for_admin: {
                    required: true
                },
                code_for_students: {
                    required: true
                },
                code_for_teachers: {
                    required: true
                },
                first_name: {
                    required: true,
                    minlength: 2
                },
                last_name: {
                    required: true,
                    minlength: 2
                },
                sex: {
                    required: true
                }
            },
            messages: {
                first_name: "Please specify your first name",
                last_name: "Please specify your Last name",
                registration_code: "Please specify your Last name"
            },
            highlight: function(element) {
                $(element).closest('.help-block').removeClass('valid');
                // display OK icon
                $(element).closest('.form-group').removeClass('has-success').addClass('has-error').find('.symbol').removeClass('ok').addClass('required');
                // add the Bootstrap error class to the control group
            },
            unhighlight: function(element) { // revert the change done by hightlight
                $(element).closest('.form-group').removeClass('has-error');
                // set error class to the control group
            },
            success: function(label, element) {
                label.addClass('help-block valid');
                // mark the current input as valid and display OK icon
                $(element).closest('.form-group').removeClass('has-error').addClass('has-success').find('.symbol').removeClass('required').addClass('ok');
            }
        });
    };
    var displayConfirm = function() {
        $('.display-value', form).each(function() {
            var input = $('[name="' + $(this).attr("data-display") + '"]', form);
            if (input.attr("type") == "text" || input.attr("type") == "email" || input.is("textarea")) {
                $(this).html(input.val());
            } else if (input.is("select")) {
                $(this).html(input.find('option:selected').text());
            } else if (input.is(":radio") || input.is(":checkbox")) {

                $(this).html(input.filter(":checked").closest('label').text());
            } else if ($(this).attr("data-display") == 'card_expiry') {
                $(this).html($('[name="card_expiry_mm"]', form).val() + '/' + $('[name="card_expiry_yyyy"]', form).val());
            }
        });
    };
    var onShowStep = function(obj, context) {
        if (context.toStep == numberOfSteps) {
            $('.anchor').children("li:nth-child(" + context.toStep + ")").children("a").removeClass('wait');
            displayConfirm();
        }
        $(".next-step").unbind("click").click(function(e) {

            e.preventDefault();
            if($(this).attr('id') === "next-step-1"){

                var data = {
                    registration_code: $(this).parents('#step-1').find('#registration-code').val(),
                    code_for_teachers: $(this).parents('#step-1').find('#code-for-teachers').val(),
                    code_for_admin: $(this).parents('#step-1').find('#code-for-admin').val(),
                    code_for_students: $(this).parents('#step-1').find('#code-for-students').val(),
                    group_id:  $(this).parents('#step-1').find('#group-id').val(),
                };

                $.blockUI({
                    message: '<i class="fa fa-spinner fa-spin"></i> Validating Your School Codes......'
                });
                $.ajax({
                    url: serverUrl + '/school/validation',
                    dataType: 'json',
                    method: 'POST',
                    data: data,
                    success: function(data, response) {
                        $.unblockUI();
                        if(data.status == "success"){
                            toastr.success("Thank You , You Have Been Registered with The School: <br>" + data.result.school.school_name);
                            wizardContent.smartWizard("goForward");
                        }else{
                            toastr.warning("Sorry U cant be Registered.<br> Contact Your School");
                        }
                    }
                });
            }else if($(this).attr('id') === "next-step-2"){

                var data = {
                    first_name: $(this).parents('#step-2').find('#first-name').val(),
                    last_name: $(this).parents('#step-2').find('#last-name').val(),
                    sex:       $('input:radio[name=sex]:checked').val()
                };

                $.blockUI({
                    message: '<i class="fa fa-spinner fa-spin"></i> Updating Your Details......'
                });
                $.ajax({
                    url:  serverUrl + '/school/brief/update',
                    dataType: 'json',
                    cache: false,
                    method: 'POST',
                    data: data,
                    success: function(data, response) {
                        $.unblockUI();
                        if(data.status == "success"){
                            toastr.success("Welcome " + data.result.details.first_name + "<br> Your Details Have been Updated");
                            wizardContent.smartWizard("goForward");
                        }else{
                            toastr.warning("Sorry Ur details cant be Updated.<br>Contact Your School");
                        }
                    }
                });
            }
        });
        $(".back-step").unbind("click").click(function(e) {
            e.preventDefault();
            wizardContent.smartWizard("goBackward");
        });
        $(".finish-step").unbind("click").click(function (e) {
            e.preventDefault();
            var group_id = $('input[name="group_id"]').val();
            console.log(group_id);
            onFinish(obj, context, group_id);
        });
    };
    var leaveAStepCallback = function(obj, context) {
        return validateSteps(context.fromStep, context.toStep);
        // return false to stay on step and true to continue navigation
    };
    var onFinish = function(obj, context, group_id) {
        if (validateAllSteps()) {
            if(group_id == Student){
                changeUrl(serverUrl + '/user/class/set/intial');
            }else if(group_id == Administrator){
                $.ajax({
                    url:  serverUrl + '/admin/check/current/session/is/set',
                    dataType: 'json',
                    method: 'POST',
                    success: function(data, response) {
                        if(data.result){
                            changeUrl(serverUrl + '/admin/home');
                        }else{
                            changeUrl(serverUrl + '/admin/class/set/intial');
                        }
                    }
                });
            }else if(group_id == Teacher){
                changeUrl(serverUrl + '/teacher/class/set/intial');
            }

            $('.anchor').children("li").last().children("a").removeClass('wait').removeClass('selected').addClass('done').children('.stepNumber').addClass('animated tada');
            //wizardForm.submit();
        }
    };
    var validateSteps = function(stepnumber, nextstep) {
        var isStepValid = false;
        if (numberOfSteps >= nextstep && nextstep > stepnumber) {

            // cache the form element selector
            if (wizardForm.valid()) { // validate the form
                wizardForm.validate().focusInvalid();
                for (var i = stepnumber; i <= nextstep; i++) {
                    $('.anchor').children("li:nth-child(" + i + ")").not("li:nth-child(" + nextstep + ")").children("a").removeClass('wait').addClass('done').children('.stepNumber').addClass('animated tada');
                }
                //focus the invalid fields
                animateBar(nextstep);
                isStepValid = true;
                return true;
            };
        } else if (nextstep < stepnumber) {
            for (i = nextstep; i <= stepnumber; i++) {
                $('.anchor').children("li:nth-child(" + i + ")").children("a").addClass('wait').children('.stepNumber').removeClass('animated tada');
            }

            animateBar(nextstep);
            return true;
        }
    };
    var validateAllSteps = function() {
        var isStepValid = true;
        // all step validation logic
        return isStepValid;
    };
    return {
        init: function() {
            initWizard();
            validateCheckRadio();
        }
    };
}();