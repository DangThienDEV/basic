$(document).ready(function(){ // ready sự kiện
    $('.btn-delete-project').click(function(){ // tạo 1 sự kiện click
        $(this).prop('disabled', true); // tránh click nhiều lần
        let projectImageId = $(this).data('project-image-id'); // Sử dụng đúng tên thuộc tính data

        $.post('delete-project-image', { id: projectImageId }) // Gửi tham số 'id' để khớp với controller
        .done(function(){ // nếu xóa thành công
            $("#project-form__image-container-" + projectImageId).remove();
            alert('Image deleted successfully!');
        })
        .fail(function(){ // nếu xóa không thành công
            $('.btn-delete-project').prop('disabled', false);
            $("#project-form__image-error-message-" + projectImageId).text('failed to delete image');
        });
    });
});
