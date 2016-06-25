(function () {
    initDragula();

    function initDragula() {
        var options = {
            containers: [document.querySelector('#drag'), document.querySelector('#drop')],
            isContainer: function (el) {
                return false;
            },
            moves: function (el, source, handle, sibling) {
                return true;
            },
            accepts: function (el, target, source, sibling) {
                return true;
            },
            invalid: function (el, handle) {
                return false;
            },
            direction: 'vertical',
            copy: true,
            copySortSource: false,
            revertOnSpill: false,
            removeOnSpill: false,
            mirrorContainer: document.body,
            ignoreInputTextSelection: true
        };
        dragula(options);
    }
})();