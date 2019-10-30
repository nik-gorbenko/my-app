$(document).ready(function() {
    let comment = {
        type: null,
        id: null,
        text: null
    };
    function comment_query(comment) {
        let result = $.post( "/FormController.php", comment);

        return new Promise((resolve, reject) => {
            let res;
            result.done(function( data ) {
                res = JSON.parse(data);
                resolve(res);
            });
        })
    }

    $(".comment__text").on("focusout", function() {
        comment.type = "edit";
        comment.id = $(this).attr("data-id");
        comment.text = $(this).val();

        comment_query(comment);
    });

    $(".comment__edit").on("click", function() {
        let id = $(this).attr("data-id");
        let textel = ".comment__text[data-id='" + id + "']";

        switch ($(this).attr("data-type")) {
            case "wait":
                $(this).attr("data-type", "process");
                $(textel).focus();
            break;
            case "process":
                $(this).attr("data-type", "wait");
                $(textel).trigger('focusout');
            break;
        }
    })

    $(".comment__remove").on("click", async function() {
        let id = $(this).attr("data-id");
        let textel = ".comment[data-id='" + id + "']";
        comment.type = "remove";
        comment.id = $(this).attr("data-id");
        $res = await comment_query(comment);
        console.log($res);
        if ($res) {
            $(textel).remove();
        }
    })

    $(".comment__add").on("click", function() {
        $("[name='form-comment__element-id']").val($(this).attr("data-element-id"));
    })

    $(".attention").on("click", function() {
        $(this).fadeOut();
    });


});