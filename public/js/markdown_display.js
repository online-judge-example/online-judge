jQuery(document).ready(function() {
    /// problem description
    $('#des_pre').click(function () {
        $(this).markdown_parse('#description_text', $("input[type=hidden]").val(), '#description_display');
        $(this).addClass('btn-active');
        $('#des_edit').removeClass('btn-active');
    });

    $('#des_edit').click(function () {
        $(this).addClass('btn-active');
        $('#description_display').addClass('d-none');
        $('#des_pre').removeClass('btn-active');
        $('#description_text').removeClass('d-none');
    });

    /// input format
    $('#input_pre').click(function () {
        $(this).markdown_parse('#input_text', $("input[type=hidden]").val(), '#input_display');
        $(this).addClass('btn-active');
        $('#input_edit').removeClass('btn-active');
    });

    $('#input_edit').click(function () {
        $(this).addClass('btn-active');
        $('#input_display').addClass('d-none');
        $('#input_pre').removeClass('btn-active');
        $('#input_text').removeClass('d-none');
    });

    /// output format
    $('#output_pre').click(function () {
        $(this).markdown_parse('#output_text', $("input[type=hidden]").val(), '#output_display');
        $(this).addClass('btn-active');
        $('#output_edit').removeClass('btn-active');
    });

    $('#output_edit').click(function () {
        $(this).addClass('btn-active');
        $('#output_display').addClass('d-none');
        $('#output_pre').removeClass('btn-active');
        $('#output_text').removeClass('d-none');
    });

    /// note
    $('#note_pre').click(function () {
        $(this).markdown_parse('#note_text', $("input[type=hidden]").val(), '#note_display');
        $(this).addClass('btn-active');
        $('#note_edit').removeClass('btn-active');
    });

    $('#note_edit').click(function () {
        $(this).addClass('btn-active');
        $('#note_display').addClass('d-none');
        $('#note_pre').removeClass('btn-active');
        $('#note_text').removeClass('d-none');
    });

    // full preview
    $('#full_pre_btn').click(function () {
        if($('#form_container').hasClass('dis-none')){
            // display form
            $('#form_container').removeClass('dis-none');
            // show problem_container
            $('#full_pre_container').addClass('dis-none');
            $(this).html('Full Preview');
            return;
        }
        let title = $('.title').val();
        let sample_input = $('#input').val();
        let sample_output = $('#output').val();

        // ajax request
        $(this).full_preview(0);
        // display title, input and output
        $('.title_con').html(title);
        $('.sample_input_con').html($(this).nl2br(sample_input));
        $('.sample_output_con').html($(this).nl2br(sample_output));

        // hide form
        $('#form_container').addClass('dis-none');
        // show problem_container
        $('#full_pre_container').removeClass('dis-none');

       $(this).html('Exit Preview');

    });

    $.fn.full_preview = function(x){
        var containers = ['.description_con', '.input_con', '.output_con', '.note_con'];
        var problem = ['#description_text','#input_text', '#output_text', '#note_text'];

        $.ajax({
            type:'POST',
            url: request_url,
            data:{
                text: $(problem[x]).val(),
                _token: $("input[type=hidden]").val()
            } ,

            success: function(result){
                $(containers[x]).html(result);
                if(x<4) $(this).full_preview(x+1);
            }
        });

    }

    $.fn.markdown_parse = function(editor, token, display){
        $.ajax({
            type:'POST',
            url: request_url,
            data:{
                text: $(editor).val(),
                _token: token
            } ,

            success: function(result){
                $(editor).addClass('d-none')
                $(display).removeClass('d-none')
                $(display).html(result)
                //console.log(result)
            }
        });
    }

    $.fn.nl2br = function(str, is_xhtml){
        if (typeof str === 'undefined' || str === null) {
            return '';
        }
        var breakTag = (is_xhtml || typeof is_xhtml === 'undefined') ? '<br />' : '<br>';
        return (str + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1' + breakTag + '$2');

    }

});
