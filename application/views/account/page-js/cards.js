$(document).ready(function() {});
(function() {
    var controller = 'Account';
    addNewCard = function() {
        $.facebox(function() {
            fcom.ajax(fcom.makeUrl(controller, 'addCardForm'), '', function(t) {
                $.facebox(t, 'medium-fb-width');
            });
        });

    };

    setupNewCard = function() {
        $.facebox(function() {
            fcom.ajax(fcom.makeUrl(controller, 'addCardForm'), '', function(t) {
                $.facebox(t, 'medium-fb-width');
            });
        });

    };

    removeCard = function(cardId) {
        if (!confirm(langLbl.confirmDelete)) {
            return false;
        };
        var data = 'cardId=' + cardId;
        fcom.ajax(fcom.makeUrl(controller, 'removeCard', []), data, function(t) {
            t = $.parseJSON(t);
            if (1 > t.status) {
                $.mbsmessage(t.msg, false, 'alert--danger');
                return false;
            }
            $.mbsmessage(t.msg, false, 'alert--success');
            location.reload();
        });
    };
})();