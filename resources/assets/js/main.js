$(function () {
    $('[data-toggle="tooltip"]').tooltip();

    // $('select').select2();

    $(document).on('card:update', function (e, data) {
        var el = $('[data-card-id="' + data.id + '"]');

        $.get(base_url + 'cards/' + data.id + '/item', function (res) {
            if (el.length) {
                el.replaceWith(res.card);
            } else {
                $('.column[data-id=' + res.column_id + '] .column-cards').append(res.card);
                bindSortable();
            }
        })
    });

    if (card_id && card_id !== '0') {
        $(document).trigger('modal:show', base_url + 'cards/' + card_id)
    }


    // EVENTS
    $(document).on('comment:created', function (e, res) {
        $('.comment-list').append(res.comment);
    });

    $(document).on('card:saved', function (e, res) {
        $('.modal-content').html(res.modal);
        $(document).trigger('card:update', res);
    });

    $(document).on('user:saved', function (e, res) {
        var el = $('[data-id=' + res.id + ']');
        if (el.length > 0) {
            el.replaceWith(res.user);
        } else {
            $('.card-list').append(res.user);
        }
        $('#modal').modal('hide');
    });

    $(document).on('task:created', function (e, res) {
        $('.task-list').append(res.task);
    });

    $(document).on('log:saved', function (e, res) {
        $('.log-list').prepend(res.log);
    });

    $(document).on('click', '.js-add-column', function (e) {
        var newColumn = $('[name="new_column_title"]');
        if (val === '') {
            alert('Column title cannot be empty!');
            return false;
        }
        var url = $(this).prop('href');
        var val = newColumn.val();
        $.post(url, {title: val}, function (res) {
            $('.project-columns').append(res)
        });
        newColumn.val('');
        e.preventDefault();
    });

    $(document).on('click', '.js-add-label', function (e) {
        var newLabel = $('[name="new_label_title"]');
        var url = $(this).prop('href');
        var val = newLabel.val();
        $.post(url, {title: val}, function (res) {
            $('.project-labels').append(res)
        });
        newLabel.val('');
        e.preventDefault();
    });

    $(document).on('change', '.js-toggle-task', function (e) {
        var url = $(this).data('url');
        var val = $(this).val();
        var el = $(this);
        $.get(url, function (res) {
            el.closest('.task').replaceWith(res.task);
            $(document).trigger('card:update', res)
        }, 'json');
        e.preventDefault();
    });

    $(document).on('click', '.js-edit-user', function (e) {
        var url = $(this).prop('href');
        $.get(url, function (res) {
            $('.modal-content').html(res)
        });
        e.preventDefault();
    });

    // Edit card
    $(document).on('click', '.js-edit-card', function (e) {
        var url = $(this).prop('href');
        var modalContent = $('.modal-content');

        $.get(url, function (res) {
            modalContent.html(res);
            // $('select').select2();
        });

        e.preventDefault();
    });

// Delete card
    $(document).on('click', '.js-delete-card', function (e) {
        if( ! confirm("Are you sure to delete?")) {
            $('#modal').modal('hide');
            return false;
        }

        var url = $(this).prop('href');
        $.get(url, function (res) {
            $('.modal-content').html('');
            $('#modal').modal('hide');
            $('[data-card-id="' + res.id + '"]').remove();
        });
        e.preventDefault();
    });

    $(document).on('click', '.js-delete-column', function (e) {
        if( ! confirm("Are you sure to delete?")) {
            return false;
        }
        $(this).closest('.form-group').remove();
        e.preventDefault();
    });

    $(document).on('submit', '.card-edit-form', function (res) {
        $('#modal').html(res)
    });

    var timer, time;

    String.prototype.toHHMMSS = function () {
        var sec_num = parseInt(this, 10); // don't forget the second param
        var hours = Math.floor(sec_num / 3600);
        var minutes = Math.floor((sec_num - (hours * 3600)) / 60);
        var seconds = sec_num - (hours * 3600) - (minutes * 60);

        if (hours < 10) {
            hours = "0" + hours;
        }
        if (minutes < 10) {
            minutes = "0" + minutes;
        }
        if (seconds < 10) {
            seconds = "0" + seconds;
        }
        return hours + ':' + minutes + ':' + seconds;
    };

    $(document).on('timer:check', function () {
        var timeWidget = $('.timewidget');
        $.get(base_url + 'logs/current', function (res) {
            if (res.current) {
                $('[data-card-id="' + res.current.card_id + '"]').find('.card-timer').addClass('active')
            } else {
                $('.card').find('.card-timer').removeClass('active');
            }
            if (res.current) {
                timeWidget.find('.timewidget-time').text(res.current.length);
                time = res.current.length;
                timer = setInterval(function () {
                    time++;
                    timeWidget.find('.timewidget-time').text(time.toString().toHHMMSS());
                }, 1000);
                timeWidget.addClass('active');
            } else {
                clearInterval(timer);
                timeWidget.removeClass('active');
            }

            timeWidget.html(res.widget);
        });
    });

    $(document).trigger('timer:check');

    $(document).on('click', '.js-track-widget', function (e) {
        var url = $(this).prop('href');
        $.get(url, function (res) {
            if (!res.status) {
                $(document).trigger('card:update', res);
            }
            $(document).trigger('timer:check');
        });
        e.preventDefault();
    });

    $(document).on('click', '.js-track-card', function (e) {
        var url = $(this).data('url');
        var el = $(this);
        $.get(url, function (res) {
            if (res.status) {
                el.addClass('active')
            } else {
                el.removeClass('active')
                $(document).trigger('card:update', res);
            }
            $(document).trigger('timer:check');
        });
        e.stopPropagation();
        e.preventDefault();
    });

    $(document).on('click', '[data-toggle="tab"]', function (e) {
        var target = $(this).prop('href');
        $(this).parent().find('.tabs-link').removeClass('active');
        $(this).addClass('active');
        $('.tabs-panel').removeClass('active');
        $('#' + (target.split('#')[1]) + '.tabs-panel').addClass('active');
        e.preventDefault();
    });
});